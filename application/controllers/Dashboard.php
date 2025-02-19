<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		// $session = $this->session->userdata('support_session');
		// if (!$session) {
		// 	$this->session->sess_destroy();
		// 	redirect('login');
		// }
	}

	/* --------------------- Function To Load Dashboard Page -------------------- */
	public function index()
	{
		$this->load->view('template/header', ['user' => get_logged_in_user()]);
		$this->load->view('dashboard');
		$this->load->view('template/footer');
	}

	/* ---------------------- Function To Load Profile Page --------------------- */
	public function profile()
	{
		$this->load->view('template/header');
		$this->load->view('profile', ['user' => get_logged_in_user()]);
		$this->load->view('template/footer');
	}
}
