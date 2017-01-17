<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model('UserModel');
    }

    /**
     * Index
     *
     * Desc
     */
	public function index() {

        $this->load->model('UserModel');
        $this->load->view('users', [
            'user' => new UserModel(1)
        ]);
	}

    /**
     * Login
     *
     * Desc
     *
     */
    public function login() {

        if ($this->session->loggedin && $this->session->loggedin === true)
            redirect('/dashboard', 'refresh');
        else
            $this->load->view('login');
    }

    public function do_login() {

        if($this->UserModel->login()) {
            redirect('/dashboard', 'refresh');
        } else {
            redirect('/', 'refresh');
        }
    }

    public function dashboard() {

        $this->load->view('dashboard');
    }

    /**
     * Logout
     *
     * Desc
     */
    public function logout() {

        if ($this->session->loggedin) {
            unset($this->session->loggedin);
        }

        $this->session->set_flashdata('success', 'You have been logged out.');
        redirect('/', 'refresh');
    }
}
