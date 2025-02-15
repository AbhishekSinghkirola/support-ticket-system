<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$session = $this->session->userdata('support_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}
	}

	public function index()
	{
		$this->load->view('template/header');
		$this->load->view('dashboard');
		$this->load->view('template/footer');
	}
}
