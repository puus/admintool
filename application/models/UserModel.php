<?php
require_once APPPATH . 'models/data_access_layer.php';

Class UserModel extends Data_Access_Layer {

    const TABLE_NAME  = 'users';
    const PRIMARY_KEY = 'id';

    protected $_jsonFields     = [];
    protected $_validations    = [];
    protected $_tableRelations = [
        'manyToMany' => [
            [
                'model' => 'RoleModel',
                'property' => 'roles',
                'through' => 'users_roles',
                'keys' => [
                    'pk' => 'id',
                    'self' => 'user_id',
                    'relation' => 'role_id'
                ]
            ]
        ]
    ];

    public function __construct($id = null) {

        parent::__construct($id);
    }

    public function login() {

        $email    = $this->input->post('email');
        $password = $this->input->post('password');
        $user     = $this->getWhere('email', $email);

        if ($user && is_array($user) && count($user)) {
            $user = $user[0];

            if (password_verify($password, $user->password)) {
                unset($user->password);
                $this->session->loggedin = true;
                $this->session->user = $user;
                $this->session->set_flashdata('success', 'Login successful. Welcome back ' . $user->first_name);
                return true;
            }
        }

        $this->session->set_flashdata('error', 'Incorrect username or password');
        return false;
    }
}
?>
