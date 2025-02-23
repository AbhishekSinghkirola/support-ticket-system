<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tickets extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$session = $this->session->userdata('support_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$this->load->model('Tickets_model', 'tickets_md');
	}

	public function index()
	{
		$this->load->view('template/header');
		$this->load->view('tickets');
		$this->load->view('template/footer');
	}

    public function get_tickets(){

        $data = [];
		$tickets = $this->tickets_md->get_tickets();

		//dd($tickets);
		$data['Resp_code'] = 'RCS';
		$data['Resp_desc'] = 'Tickets Fetched Successfully';
		$data['data'] = is_array($tickets) ? $tickets : [];

		exit(json_encode($data));

    }

	public function add_tickets(){

		$data = [];
		$params = $this->input->post();
// dd($params);
		$user = get_logged_in_user();
		$user_id = $user['id'];

				$insert_data = [
					'title' => $params['ticket_title'],
					'description' => $params['ticket_desc'],
					'status' => $params['status'],
					'priority' => $params['priority'],
					'category_id' => $params['category_id'],
					'user_id' => $user_id,
					'agent_id' => NULL,
				];

				if ($this->tickets_md->add_tickets($insert_data)) {
					$data['Resp_code'] = 'RCS';
					$data['Resp_desc'] = 'Ticket Added successfully';
					$data['data'] = [];
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Internal Processing Error';
					$data['data'] = [];
				}
		
		exit(json_encode($data));
	}
}
