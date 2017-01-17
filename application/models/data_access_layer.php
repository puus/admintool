<?php
    if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    /**
     * This Data Access Layer serves as an ORM our models can use to
     * transparently read/write values from our database tables. For an example
     * of how to set this up on a model see the UserModel. Once configured you
     * can use the ORM as follows:
     *
     * ```php
     * // Retreive a single record from the users table where PK = 1
     * $user = new UserModel(1);
     * echo $user->first_name; // Echo first_name field from retreived DB record
     *
     * // Retreive a single record by some arbitrary field
     * $userModel = new UserModel();
     * $user      = $userModel->getOneWhere('email', 'test@example.com');
     * ```
     *
     * Records are returned as objects with DB field names as properties
     *
     * @author Peter Walsh <peter@perceptiveweb.solutions>
     */

    abstract class Data_Access_Layer extends CI_Model {

        private $_data = [];

        /**
         * Create a new DAL instance. If provided with $id retrieve the table
         * record with corresponding PK value. If $recursive return not only
         * this record and it's relations, but all records related to its
         * relations (and their relations, and so on ...) as well.
         *
         * @param Mixed $id        The PK value of the record to be retreived
         * @param Bool  $recursive Whether or not to pull in secondary level and
         *                         beyond relations
         */
        public function __construct($id = null, $recursive = false) {

            parent::__construct();

            if ($id) {
                $result = $this->getOne($id, $recursive);

                if ($result && is_object($result))
                    $this->_data = $result;
                else if ($result && is_array($result) && count($result) && is_object($result[0]))
                    $this->_data = $result[0];
                else
                    throw new Exception("No result for ID $id");
            }
        }

        /**
         * Creates a new record in the underlying DB table
         *
         * @param Array $data Associative array of field -> value to be inserted
         */
        public function create($data) {

            if ($this->_validate($data)) {
                $this->db->insert(static::TABLE_NAME, $data);
                return $this->getOne($this->db->insert_id());
            } else {
                echo '{"error": "Invalid data"}';
            }
        }

        /**
         * Get the value of the PK for this record if it's been populated
         *
         * @return Mixed
         */
        private function _getPk() {

        }

        /**
         * Soft deletes a record from the underlying DB table
         *
         * @param Mixed $id PK value of record to be deleted. If value is falsey
         *        we'll try to extract if from table record data if any exists
         */
        public function delete($id = null) {

            if (!$id) {
                $id = $this->_getPk();
            }


        }

        /**
         * Really delete a record from the underlying DB Table
         *
         * @param Mixed $id PK value of record to be deleted. If value is falsey
         *        we'll try to extract if from table record data if any exists
         */
        public function hardDelete($id = null) {

            if (!$id) {
                $id = $this->_getPk();
            }

            $this->db->where(static::PRIMARY_KEY, $id);
            $this->db->delete(static::TABLE_NAME);
        }

        /**
         * Validate our associate data array based on the validation rules
         * specificed in our concrete class
         *
         * @param Array $data Associate array of values to be validated
         * @return bool
         */
        private function _validate($data = null) {

            if (!$data)
                $data = (array)$this->_data;

            $validations = $this->_validations;
            foreach($validations as $field => $type) {
                if (array_key_exists($field, $data)) {
                    $value = $data[$field];

                    switch($type) {
                        case 'int':
                            if (!is_int((int)$value))
                                return false;
                            break;
                        case 'string':
                            if (!is_string($value))
                                return false;
                            break;
                        case 'json':
                            json_decode($value);
                            if (json_last_error() != JSON_ERROR_NONE)
                                return false;
                            break;
                    }
                }
            }

            return true;
        }

        /**
         * Return the value for any of our DB table fields.
         *
         * @param String $key Field name whose value is to be retreived
         */
        public function get($key) {

            if (is_object($this->_data) && property_exists($this->_data, $key))
                return $this->_data->{$key};
            return false;
        }

        /**
         * Set a value both internally (within the DALs $_data[]) AND update
         * the underlying SQL data store
         *
         * @param String $key   Field to be updated
         * @param Mixed  $value Value to store in field
         */
        public function set($key, $value) {

            if (property_exists($this->_data, $key)) {
                if ($this->_validate([$key => $value])) {
                    $this->_data->{$key} = $value;
                    $this->_update();
                }
            }
        }

        /**
         * Update the underlying SQL data store
         */
        private function _update() {

            $pk = static::PRIMARY_KEY;
            $this->db->where($pk, $this->_data->{$pk});
            $this->db->update(static::TABLE_NAME, (array)$this->_data);
        }

        /**
         * Used to update many DAL based objects at once
         *
         * @param Array $data Array of DAL based objects
         */
        public function bulkUpdate($data) {

            if (is_array($data) && count($data)) {
                if ($this->_validate($data)) {
                    foreach($data as $key => $value) {
                        if (property_exists($this->_data, $key)) {
                            $this->_data->{$key} = $value;
                        }
                    }

                    $this->_update();
                }
            }
        }

        /**
         * Parses the JSON content of any table fields marked as JSON by our
         * concrete class
         *
         * @param Object[] $fields An array of objects with each object
         *                         representing a single field -> value
         *                         relationship
         */
        private function _parseJsonFields($fields) {

            foreach ($this->_jsonFields as $jField) {
                if (array_key_exists($jField, $fields)) {
                    $fields->{$jField} = json_decode($fields->{$jField});
                }
            }

            return $fields;
        }

        /**
         * Get all records from the underlying DB table
         *
         * @param Bool  $recursive Whether or not to pull in secondary level and
         *                         beyond relations
         */
        public function getAll($recursive = false) {

            $this->db->select('*');
            $this->db->from(static::TABLE_NAME);
            $results = $this->db->get()->result();
            $parsedResults = [];
            foreach($results as $result) {
                $parsedResults[] = $this->_parseJsonFields($result);
            }
            $results = $parsedResults;


            if ($recursive) {
                $return = [static::TABLE_NAME => []];
                foreach($results as $result) {
                    $return[static::TABLE_NAME][] = $this->_getRelations([$result]);
                }
            } else { $return = false; }

            return $return ? $return : $results;
        }

        /**
         * Get all records where the specified field matches the given value
         *
         * @param String $field
         * @param Mixed  $value
         * @param Bool   $recursive Whether or not to pull in secondary level and
         *                          beyond relations
         */
        public function getWhere($field, $value, $recursive = false) {

            $this->db->select('*');
            $this->db->from(static::TABLE_NAME);
            $this->db->where($field, $value);
            $results = $this->db->get()->result();

            $parsedResults = [];
            foreach($results as $result) {
                $parsedResults[] = $this->_parseJsonFields($result);
            }

            if ($recursive) {
                $return = [static::TABLE_NAME => []];
                foreach($parsedResults as $result) {
                    $return[static::TABLE_NAME][] = $this->_getRelations([$result]);
                }
            } else { $return = false; }

            return $return ? $return : $parsedResults;
        }

        /**
         * Get one record whose PK value matches the given $id
         *
         * @param Mixed $id
         * @param Bool  $recursive Whether or not to pull in secondary level and
         *                         beyond relations
         */
        public function getOne($id, $recursive = false) {

            $this->db->select('*');
            $this->db->where(static::PRIMARY_KEY, $id);
            $this->db->from(static::TABLE_NAME);
            $result = $this->db->get()->result();
            $result = $this->_parseJsonFields($result[0]);

            /*
            if (method_exists($this, '_getOne')) {
                $result = $this->_getOne($result);
            }
            */

            if ($recursive) {
                $result = $this->_getRelations([$result]);
            }

            return $result;
        }

        /**
         * Get one record where the specified field matches the given value
         *
         * @param String $field
         * @param Mixed  $value
         * @param Bool   $recursive Whether or not to pull in secondary level and
         *                          beyond relations
         */
        public function getOneWhere($field, $value, $recursive = false) {

            $this->db->select('*');
            $this->db->where($field, $value);
            $this->db->from(static::TABLE_NAME);
            $this->db->limit(1);

            $result = $this->db->get()->result();
            //var_dump($result);
            if (is_array($result) && count($result))
                $result = $this->_parseJsonFields($result[0]);

            if ($recursive) {
                $result = $this->_getRelations([$result]);
            }

            return $result;
        }

        /**
         * Return a number of records defined by the specifed offset and limit
         *
         * @param Int $offset
         * @param Int $limit
         */
        public function getSome($offset = 0, $limit = 25) {

            $offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
            $limit  = $this->input->get('limit')  ? $this->input->get('limit')  : 25;

            $query = $this->db->get(static::TABLE_NAME, $limit, $offset);
            return $query->result();
        }

        /**
         * Return a number of records defined by the specifed offset and limit
         * where each record has a given value for a specific field
         *
         * @param String $key The table field name
         * @param Mixed  $value
         * @param Int    $offset
         * @param Int    $limit
         * @param Bool   $recursive Whether or not to pull in secondary level and
         *                          beyond relations
         */
        public function getSomeWhere($key, $value, $offset = 0, $limit = 25, $recursive = false) {

            $offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
            $limit  = $this->input->get('limit')  ? $this->input->get('limit')  : 25;

            $this->db->select('*');
            $this->db->from(static::TABLE_NAME);
            $this->db->where($key, $value);
            $this->db->offset($offset);
            $this->db->limit($limit);
            $query = $this->db->get();
            $result = $query->result();

            if ($recursive) {
                foreach($result as $index => $row) {
                    $row = $this->_getRelations([$row]);
                    $result[$index] = $row;
                }
            }

            return $result;
        }

        /**
         * Get total number of records in this table
         */
        public function getTotal() {

            return $this->db->count_all(static::TABLE_NAME);
        }

        /**
         * Get total number of records in this table where the specified field
         * contained the provided value
         *
         * @param String $key The table field name
         * @param Mixed  $value
         */
        public function getTotalWhere($key, $value) {

            $this->db->select('*');
            $this->db->from(static::TABLE_NAME);
            $this->db->where($key, $value);
            $q = $this->db->get();

            return $q->num_rows();
        }

        /*
        public function getByKey($key, $value, $recursive = false) {

            $this->db->select('*');
            $this->db->where($key, $value);
            $this->db->from(static::TABLE_NAME);
            $result = $this->db->get()->result();
            //
            //$result = $this->_parseJsonFields($result[0]);

            //if ($recursive) {
            //    $result = $this->_getRelations($result);
            //}

            return $result;
        }
        */

        /**
         * Establish a M:N relation between two entities by inserting a record
         * in their joining (or 'through') table
         *
         * @todo Expand to handle the other relationship types
         * @param $relation
         * @param $id
         */
        public function relate($relation, $id) {

            $table = $this->_identifyRelation($relation);

            if ($table) {
                $this->db->insert($table['table'], [
                    $table['keys']['self']     => $this->get('id'),
                    $table['keys']['relation'] => $id
                ]);
            }
        }

        /**
         * Identify the type of relation connecting this model to $relation and
         * return the data necessary to create the relationship in the underlying
         * SQL DB
         *
         * @param $relation
         */
        private function _identifyRelation($relation) {

            foreach($this->_tableRelations as $type => $tableRelations) {
                foreach($tableRelations as $tableRelation) {
                    if($relation == $tableRelation['model']) {
                        switch($type) {
                            case 'oneToOne':
                                break;
                            case 'oneToMany':
                                break;
                            case 'manyToMany':
                                return [
                                    'table' => $tableRelation['through'],
                                    'keys'  => $tableRelation['keys']
                                ];
                                break;
                        }
                    }
                }
            }

            return false;
        }

        /**
         * Given a record from the DB table pull in all its related records
         *
         * @param $results
         */
        private function _getRelations($results) {

            $relations = $this->_tableRelations;

            if ($relations) {
                foreach($results as $index => $result) {
                    if (array_key_exists('oneToOne', $relations)) {
                        foreach($relations['oneToOne'] as $relation) {
                            $result->{$relation['property']} = $this->{$relation['model']}->getOne($result->{$relation['key']}, true);
                            $results = $result;
                        }
                    }

                    if (array_key_exists('oneToMany', $relations)) {
                        foreach($relations['oneToMany'] as $relation) {
                            $result->{$relation['property']} = $this->{$relation['model']}->getByKey($relation['keys']['relation'], $result->{$relation['keys']['self']}, true);
                            $results = $result;
                        }
                    }

                    if (array_key_exists('belongsToMany', $relations)) {
                        foreach($relations['belongsToMany'] as $relation) {
                            $request->{$relation['property']} = $this->{$relation['model']}->getWhere($relation['keys']['relation'], $result->{$relation['keys']['pk']});
                            $results = $result;
                        }
                    }

                    if (array_key_exists('manyToMany', $relations)) {

                        foreach($relations['manyToMany'] as $relation) {

                            $this->db->select('*');
                            $this->db->from($relation['through']);
                            $this->db->where($relation['keys']['self'], $result->{$relation['keys']['pk']});
                            $relationResult = $this->db->get();

                            if ($relationResult) {

                                $relationResult = $relationResult->result();

                                $result->{$relation['property']} = [];

                                foreach($relationResult as $rResult) {
                                    $res = $this->{$relation['model']}->getOne($rResult->{$relation['keys']['relation']}, true);
                                    if(is_array($res) && count($res))
                                        $result->{$relation['property']}[] = $res[0];
                                    else
                                        $result->{$relation['property']}[] = $res;
                                }
                                //var_dump($results);
                                //$results[$index] = $result;
                            }
                        }
                    }
                }
            }

            return $results;
        }
    }
?>
