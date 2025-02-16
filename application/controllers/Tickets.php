<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tickets extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		// $session = $this->session->userdata('support_session');
		// if (!$session) {
		// 	$this->session->sess_destroy();
		// 	redirect('login');
		// }

		$this->load->model('Tickets_model', 'tickets_md');
	}

	public function index()
	{
		$this->load->view('template/header');
		$this->load->view('tickets');
		$this->load->view('template/footer');
	}

    public function get_tickets(){

        // $session = $this->session->userdata('cms_session');
		// if (!$session) {
		// 	$this->session->sess_destroy();
		// 	exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		// }

        $data = [];
		$tickets = $this->tickets_md->get_tickets();

		dd($tickets);
		$data['Resp_code'] = 'RCS';
		$data['Resp_desc'] = 'Tickets Fetched Successfully';
		$data['data'] = is_array($tickets) ? $tickets : [];

		exit(json_encode($data));

    }
}
