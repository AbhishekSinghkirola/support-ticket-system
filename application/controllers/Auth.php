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

	/* ------------------ Function To Show change password page ----------------- */
	public function change_password()
	{
		$this->load->view('template/header');
		$this->load->view('change-password');
		$this->load->view('template/footer');
	}

	/* -------------------- Function to update user password -------------------- */
	public function update_password()
	{
		$this->load->model('User_model', 'user_md');

		$params = $this->input->post();
		$data = [];

		$old_password = isset($params['old_password']) ? $params['old_password'] : '';
		$new_password = isset($params['new_password']) ? $params['new_password'] : '';
		$confirm_password = isset($params['confirm_password']) ? $params['confirm_password'] : '';

		if (validate_field($old_password, 'strpass')) {

			if (validate_field($new_password, 'strpass')) {

				if (validate_field($confirm_password, 'strpass')) {

					if ($new_password === $confirm_password) {

						$session = $this->session->userdata('support_session');
						$user_id = $session['user_id'];

						$user_details = $this->user_md->get_user($user_id);

						if ($user_details) {

							if (md5($old_password) === $user_details['password']) {

								$update_data = [
									'password' => md5($new_password),
									'updated_at' => date('Y-m-d H:i:s'),
									'updated_by' => $user_id
								];

								$updated = $this->user_md->update_user($user_id, $update_data);

								if ($updated) {
									$data['Resp_code'] = 'RCS';
									$data['Resp_desc'] = 'Password Updated Successfully';
								} else {
									$data['Resp_code'] = 'ERR';
									$data['Resp_desc'] = 'Failed to Update Password';
								}
							} else {
								$data['Resp_code'] = 'ERR';
								$data['Resp_desc'] = 'Invalid Old Password';
							}
						} else {
							$data['Resp_code'] = 'ERR';
							$data['Resp_desc'] = 'User does not exist';
						}
					} else {
						$data['Resp_code'] = 'ERR';
						$data['Resp_desc'] = 'New Password and Confirm New Password does not match';
					}
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Confirm New Password is required';
				}
			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'New Password is required';
			}
		} else {
			$data['Resp_code'] = 'ERR';
			$data['Resp_desc'] = 'Old Password is required';
		}

		echo json_encode($data);
	}

	/* ------------------ Function To Show Forgot Password Page ----------------- */
	public function forgot_password()
	{

		$session = $this->session->has_userdata('support_session');
		if ($session) {
			redirect('/');
		} else {
			$this->load->view('forgot-password');
		}
	}

	/* ------------------ Function to send reset password link ------------------ */
	public function send_reset_password_link()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = [];
			$session = $this->session->has_userdata('support_session');
			if (!$session) {
				$params = $this->input->post();
				$params['email'] = isset($params['email']) ? (is_string($params['email']) ? trim($params['email']) : "") : "";

				if (validate_field($params['email'], 'email')) {

					$is_valid_user = $this->auth_md->check_user_email_exist($params['email']);

					if ($is_valid_user) {

						$this->load->library('email');

						$resetToken = md5($is_valid_user['email'] . time());
						$reset_link = base_url('reset-password/' . $resetToken);

						$this->auth_md->update_user($is_valid_user['id'], ['reset_token' => $resetToken]);

						$this->email->from(USER_EMAIL, USER_NAME);
						$this->email->to($params['email']);
						$this->email->subject('Reset Password Process');
						$this->email->message('<h1>Hello!</h1><p>Please click on this link <a href="' . $reset_link . '">' . $reset_link . '</a></p>');

						if ($this->email->send()) {
							$data['Resp_code'] = 'ERR';
							$data['Resp_desc'] = 'Email Sent Successfully';
							$data['data'] = [];
						} else {
							$data['Resp_code'] = 'ERR';
							$data['Resp_desc'] = 'User email does not exist';
							$data['data'] = [];
						}
					} else {
						$data['Resp_code'] = 'ERR';
						$data['Resp_desc'] = 'User email does not exist';
						$data['data'] = [];
					}
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Invalid Email Address';
					$data['data'] = [];
				}
			} else {
				$data['Resp_code'] = 'RLD';
				$data['Resp_desc'] = 'Session already exist';
				$data['data'] = [];
			}

			$this->session->set_flashdata('data', $data);
			redirect($_SERVER['HTTP_REFERER']);
		} else {
			redirect('login');
		}
	}

	/* ----------------------- Function To Show Reset Password Page ----------------------- */
	public function reset_password_view($resetToken = null)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			$data = [];
			$session = $this->session->has_userdata('support_session');
			if (!$session) {
				$user = $this->auth_md->check_user_reset_token($resetToken);

				if ($user) {
					$this->load->view('reset-password', ['resetToken' => $resetToken]);
				} else {
					redirect('login');
				}
			} else {
				$data['Resp_code'] = 'RLD';
				$data['Resp_desc'] = 'Session already exist';
				$data['data'] = [];
				$this->session->set_flashdata('data', $data);
				redirect($_SERVER['HTTP_REFERER']);
			}
		} else {
			redirect('login');
		}
	}

	/* ------------------------ Function to reset password ----------------------- */
	public function reset_password()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = [];
			$session = $this->session->has_userdata('support_session');
			if (!$session) {
				$params = $this->input->post();
				$params['reset_token'] = isset($params['reset_token']) ? (is_string($params['reset_token']) ? trim($params['reset_token']) : "") : "";
				$params['new_password'] = isset($params['new_password']) ? (is_string($params['new_password']) ? trim($params['new_password']) : "") : "";
				$params['confirm_password'] = isset($params['confirm_password']) ? (is_string($params['confirm_password']) ? trim($params['confirm_password']) : "") : "";
				$user = $this->auth_md->check_user_reset_token($params['reset_token']);
				if ($user) {

					if (validate_field($params['new_password'], 'strpass')) {

						if (validate_field($params['confirm_password'], 'strpass')) {

							if ($params['new_password'] === $params['confirm_password']) {

								$update_data = [
									'password' => md5($params['new_password']),
									'updated_at' => date('Y-m-d H:i:s'),
									'updated_by' => $user['id'],
									'reset_token' => null
								];

								$updated = $this->auth_md->update_user($user['id'], $update_data);

								if ($updated) {
									$data['Resp_code'] = 'RCS';
									$data['Resp_desc'] = 'Password Updated Successfully';
									$data['data'] = [];
								} else {
									$data['Resp_code'] = 'ERR';
									$data['Resp_desc'] = 'Failed to Update Password';
									$data['data'] = [];
								}
							} else {
								$data['Resp_code'] = 'ERR';
								$data['Resp_desc'] = 'New Password and Confirm Password does not match';
								$data['data'] = [];
							}
						} else {
							$data['Resp_code'] = 'ERR';
							$data['Resp_desc'] = 'Confirm Password is required';
							$data['data'] = [];
						}
					} else {
						$data['Resp_code'] = 'ERR';
						$data['Resp_desc'] = 'Invalid Password';
						$data['data'] = [];
					}
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Invalid Token';
					$data['data'] = [];
				}
			} else {
				$data['Resp_code'] = 'RLD';
				$data['Resp_desc'] = 'Session already exist';
				$data['data'] = [];
			}
			$this->session->set_flashdata('data', $data);
			redirect($_SERVER['HTTP_REFERER']);
		} else {
			redirect('login');
		}
	}
}
