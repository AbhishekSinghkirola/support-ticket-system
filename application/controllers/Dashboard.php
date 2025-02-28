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

	/* ----------------------- Function to update profile ----------------------- */
	public function update_profile()
	{
		$this->load->model('User_model', 'user_md');
		$params = $this->input->post();
		$data = [];

		$user_name = isset($params['user_name']) ? $params['user_name'] : '';

		if (validate_field($user_name, 'strname')) {
			$session = $this->session->userdata('support_session');
			$user_id = $session['user_id'];

			$user_details = $this->user_md->get_user($user_id);

			if ($user_details) {

				$update_data = [
					'name' => $user_name,
					'updated_at' => date('Y-m-d H:i:s'),
					'updated_by' => $user_id
				];

				$updated = $this->user_md->update_user($user_id, $update_data);

				if ($updated) {
					$data['Resp_code'] = 'RCS';
					$data['Resp_desc'] = 'User Profle Updated Successfully';
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Failed to Update Password';
				}
			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'User does not exist';
			}
		} else {
			$data['Resp_code'] = 'ERR';
			$data['Resp_desc'] = 'Invalid User Name';
		}


		echo json_encode($data);
	}
}
