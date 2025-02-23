<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Auth_model', 'auth_md');
	}

	/* ------------------------ Function To Load Login Page ------------------------ */
	public function login()
	{
		$session = $this->session->has_userdata('support_session');
		if ($session) {
			redirect('/');
		} else {
			$this->load->view('login', ['roles' => common_status_array('roles')]);
		}
	}

	/* ------------------------ Function To Iniate Login ------------------------ */
	public function init_login()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = [];
			$session = $this->session->has_userdata('support_session');
			if (!$session) {
				$params = $this->input->post();
				$params['mobile'] = isset($params['mobile']) ? (ctype_digit($params['mobile']) ? trim($params['mobile']) : "") : "";
				$params['password'] = isset($params['password']) ? (is_string($params['password']) ? trim($params['password']) : "") : "";
				$params['role'] = isset($params['role']) ? (is_string($params['role']) ? trim($params['role']) : "") : "";


				if (validate_field($params['mobile'], 'mob')) {

					if (validate_field($params['password'], 'strpass')) {
						$is_valid_user = $this->auth_md->check_valid_user($params['mobile'], md5($params['password']), $params['role']);

						if ($is_valid_user) {


							$session_array = array(
								"user_id" => $is_valid_user['id'],
								"role" => $is_valid_user['role'],
							);


							$this->session->set_userdata("support_session", $session_array);

							$data['Resp_code'] = 'RLD';
							$data['Resp_desc'] = 'User Logged In Successfully';
							$data['data'] = [];
						} else {
							$data['Resp_code'] = 'ERR';
							$data['Resp_desc'] = 'Invalid Credentials';
							$data['data'] = [];
						}
					} else {
						$data['Resp_code'] = 'ERR';
						$data['Resp_desc'] = 'Invalid Password';
						$data['data'] = [];
					}
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Invalid Mobile Number';
					$data['data'] = [];
				}
			} else {
				$data['Resp_code'] = 'RLD';
				$data['Resp_desc'] = 'Session already exist';
				$data['data'] = [];
			}
			echo json_encode($data);
		} else {
			redirect('login');
		}
	}

	/* ---------------------- Function To Log Out the User ---------------------- */
	public function logout()
	{
		$session = $this->session->has_userdata('support_session');
		if ($session) {
			$this->session->sess_destroy();
			redirect('login');
		} else {
			redirect('login');
		}
	}

	/* ------------------------ Function To Register User ----------------------- */
	public function register()
	{
		$session = $this->session->has_userdata('support_session');
		if ($session) {
			redirect('/');
		} else {
			$this->load->view('register');
		}
	}

	/* --------------------- Function For User Registration --------------------- */
	public function registration()
	{
		$session = $this->session->has_userdata('support_session');

		if ($session) {
			redirect('/');
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = [];

			$params = $this->input->post();
			$params['user_name'] = isset($params['user_name']) ? (is_string($params['user_name']) ? trim($params['user_name']) : "") : "";
			$params['email'] = isset($params['email']) ? (is_string($params['email']) ? trim($params['email']) : "") : "";
			$params['mobile'] = isset($params['mobile']) ? (is_string($params['mobile']) ? trim($params['mobile']) : "") : "";
			$params['password'] = isset($params['password']) ? (is_string($params['password']) ? trim($params['password']) : "") : "";

			if (validate_field($params['user_name'], 'strname')) {

				if (validate_field($params['email'], 'email')) {

					if (validate_field($params['mobile'], 'mob')) {

						if (validate_field($params['password'], 'strpass')) {

							$check_user = $this->auth_md->check_user($params['email'], $params['mobile']);
							if ($check_user) {
								$data['Resp_code'] = 'ERR';
								$data['Resp_desc'] = 'User Already Exsist';
								$data['data'] = [];
							} else {
								$insert_array = [
									'name' => $params['user_name'],
									'email' => $params['email'],
									'mobile' => $params['mobile'],
									'password' => md5($params['password']),
									'role' => 'USER',
									'created_at' => date('Y-m-d H:i:s'),
									'account_status' => 'ACTIVE',
								];
								$insert_user = $this->auth_md->insert_user($insert_array);
								if ($insert_user) {
									$data['Resp_code'] = 'RCS';
									$data['Resp_desc'] = 'Registered Successfully';
									$data['data'] = [];
								} else {
									$data['Resp_code'] = 'ERR';
									$data['Resp_desc'] = 'Something Went Wrong';
									$data['data'] = [];
								}
							}
						} else {
							$data['Resp_code'] = 'ERR';
							$data['Resp_desc'] = 'Invalid Password';
							$data['data'] = [];
						}
					} else {
						$data['Resp_code'] = 'ERR';
						$data['Resp_desc'] = 'Invalid Mobile Number';
						$data['data'] = [];
					}
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Invalid Email';
					$data['data'] = [];
				}
			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Invalid User Name';
				$data['data'] = [];
			}
			echo json_encode($data);
		} else {
			redirect('login');
		}
	}
}
