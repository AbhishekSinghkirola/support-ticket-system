<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $session = $this->session->userdata('support_session');
        if (!$session) {
            $this->session->sess_destroy();
            redirect('login');
        }

        if (!in_array($session['role'], ['SUPPORT', 'ADMIN'])) {
            redirect('dashboard');
        }

        $this->load->model('User_model', 'user_md');
    }

    /* ------------------------ Function To Load Users Page ------------------------ */
    public function index()
    {
        $this->load->view('template/header');
        $this->load->view('user');
        $this->load->view('template/footer');
    }

    /* ------------------------ Function to get all users ----------------------- */
    public function get_users()
    {
        $users = $this->user_md->get_users();

        $data = [];

        $data['Resp_code'] = 'RCS';
        $data['Resp_desc'] = 'Users Fetched Successfully';
        $data['data'] = is_array($users) ? $users : [];
        echo json_encode($data);
    }

    /* -------------------------- Function to edit user ------------------------- */
    public function edit_user()
    {
        $session = $this->session->userdata('support_session');
        $params = $this->input->post();

        $user_id = isset($params['user_id']) ? $params['user_id'] : '';
        $user_name = isset($params['user_name']) ? $params['user_name'] : '';
        $email = isset($params['email']) ? $params['email'] : '';
        $mobile = isset($params['mobile']) ? $params['mobile'] : '';
        $account_status = isset($params['account_status']) ? $params['account_status'] : '';
        if ($user_id) {

            $user_details = $this->user_md->get_user($user_id);

            if ($user_details) {

                if (validate_field($user_name, 'strname')) {

                    if (validate_field($email, 'email')) {

                        if (validate_field($mobile, 'mob')) {

                            if (validate_field($account_status, 'account_status', 'SELECT')) {

                                $unique_user = unique_user($email, $mobile);

                                if (!empty($unique_user) && $unique_user['id'] != $params['user_id']) {
                                    $data['Resp_code'] = 'ERR';
                                    $data['Resp_desc'] = 'User Already Exists';
                                } else {
                                    $update_data = [
                                        'name' => $user_name,
                                        'email' => $email,
                                        'mobile' => $mobile,
                                        'account_status' => $account_status,
                                        'updated_at' => date('Y-m-d H:i:s'),
                                        'updated_by' => $session['user_id']
                                    ];

                                    $updated = $this->user_md->update_user($params['user_id'], $update_data);

                                    if ($updated) {
                                        $data['Resp_code'] = 'RCS';
                                        $data['Resp_desc'] = 'User Updated Successfully';
                                    } else {
                                        $data['Resp_code'] = 'ERR';
                                        $data['Resp_desc'] = 'Failed to Update User';
                                    }
                                }
                            } else {
                                $data['Resp_code'] = 'ERR';
                                $data['Resp_desc'] = 'Invalid Account Status';
                            }
                        } else {
                            $data['Resp_code'] = 'ERR';
                            $data['Resp_desc'] = 'Invalid Mobile Number';
                        }
                    } else {
                        $data['Resp_code'] = 'ERR';
                        $data['Resp_desc'] = 'Invalid Email Address';
                    }
                } else {
                    $data['Resp_code'] = 'ERR';
                    $data['Resp_desc'] = 'Invalid User Name';
                }
            } else {
                $data['Resp_code'] = 'ERR';
                $data['Resp_desc'] = 'User does not exist';
            }
        } else {
            $data['Resp_code'] = 'ERR';
            $data['Resp_desc'] = 'Invalid User ID';
        }

        echo json_encode($data);
        exit;
    }

    /* ------------------------- Function to delete user ------------------------ */
    public function delete_user()
    {
        $params = $this->input->post();

        $user_id = isset($params['user_id']) ? $params['user_id'] : '';

        if ($user_id) {

            $user_details = $this->user_md->get_user($user_id);

            if ($user_details) {

                $deleted = $this->user_md->delete_user($user_id);

                if ($deleted) {
                    $data['Resp_code'] = 'RCS';
                    $data['Resp_desc'] = 'User Deleted Successfully';
                } else {
                    $data['Resp_code'] = 'ERR';
                    $data['Resp_desc'] = 'Failed to Delete User';
                }
            } else {
                $data['Resp_code'] = 'ERR';
                $data['Resp_desc'] = 'User does not exist';
            }
        } else {
            $data['Resp_code'] = 'ERR';
            $data['Resp_desc'] = 'Invalid User ID';
        }

        echo json_encode($data);
        exit;
    }
}
