<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Samples extends CI_Controller {

    public function __construct() {

        parent::__construct();
    }

    /**
     * Index
     *
     * Desc
     */
	public function index() {

        $this->load->view('samples');
	}

    public function more() {

        $this->load->view('moresamples');
    }
}
