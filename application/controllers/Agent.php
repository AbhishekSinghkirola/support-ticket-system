<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Agent extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $session = $this->session->userdata('support_session');
        if (!$session) {
            $this->session->sess_destroy();
            redirect('login');
        }

        if ($session['role'] != 'ADMIN') {
            redirect('dashboard');
        }

        $this->load->model('Agent_model', 'agent_md');
    }

    /* ------------------------ Function To Load Agents Page ------------------------ */
    public function index()
    {
        $this->load->view('template/header');
        $this->load->view('agent');
        $this->load->view('template/footer');
    }

    /* ------------------------ Function to get all agents ----------------------- */
    public function get_agents()
    {
        $users = $this->agent_md->get_agents();

        $data = [];

        $data['Resp_code'] = 'RCS';
        $data['Resp_desc'] = 'Agents Fetched Successfully';
        $data['data'] = is_array($users) ? $users : [];
        echo json_encode($data);
    }

    /* ------------------------ Function to add new agent ------------------------ */
    public function add_agent()
    {
        $params = $this->input->post();

        $user_name = isset($params['user_name']) ? $params['user_name'] : '';
        $email = isset($params['email']) ? $params['email'] : '';
        $mobile = isset($params['mobile']) ? $params['mobile'] : '';

        if (validate_field($user_name, 'strname')) {

            if (validate_field($email, 'email')) {

                if (validate_field($mobile, 'mob')) {

                    $unique_user = unique_user($email, $mobile);

                    if (!$unique_user) {

                        $insert_data = [
                            'name' => $user_name,
                            'email' => $email,
                            'mobile' => $mobile,
                            'account_status' => 'ACTIVE',
                            'role' => 'SUPPORT',
                            'password' => md5('welcome@123'),
                            'created_at' => date('Y-m-d H:i:s'),
                        ];

                        $inserted = $this->agent_md->add_agent($insert_data);

                        if ($inserted) {
                            $data['Resp_code'] = 'RCS';
                            $data['Resp_desc'] = 'Agent Added Successfully';
                        } else {
                            $data['Resp_code'] = 'ERR';
                            $data['Resp_desc'] = 'Failed to Add User';
                        }
                    } else {
                        $data['Resp_code'] = 'ERR';
                        $data['Resp_desc'] = 'Agent Already Exists';
                    }
                } else {
                    $data['Resp_code'] = 'ERR';
                    $data['Resp_desc'] = 'Invalid Email Address';
                }
            } else {
                $data['Resp_code'] = 'ERR';
                $data['Resp_desc'] = 'Invalid Email Address';
            }
        } else {
            $data['Resp_code'] = 'ERR';
            $data['Resp_desc'] = 'Invalid User Name';
        }



        echo json_encode($data);
        exit;
    }

    /* -------------------------- Function to edit agent ------------------------- */
    public function edit_agent()
    {
        $session = $this->session->userdata('support_session');
        $params = $this->input->post();

        $user_id = isset($params['user_id']) ? $params['user_id'] : '';
        $user_name = isset($params['user_name']) ? $params['user_name'] : '';
        $email = isset($params['email']) ? $params['email'] : '';
        $mobile = isset($params['mobile']) ? $params['mobile'] : '';
        $account_status = isset($params['account_status']) ? $params['account_status'] : '';
        if ($user_id) {

            $user_details = $this->agent_md->get_agent($user_id);

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
                                        'updaetd_by' => $session['user_id']
                                    ];

                                    $updated = $this->agent_md->update_agent($params['user_id'], $update_data);

                                    if ($updated) {
                                        $data['Resp_code'] = 'RCS';
                                        $data['Resp_desc'] = 'Agent Updated Successfully';
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

    /* ------------------------- Function to delete agent ------------------------ */
    public function delete_agent()
    {
        $params = $this->input->post();

        $user_id = isset($params['user_id']) ? $params['user_id'] : '';

        if ($user_id) {

            $user_details = $this->agent_md->get_agent($user_id);

            if ($user_details) {

                $deleted = $this->agent_md->delete_agent($user_id);

                if ($deleted) {
                    $data['Resp_code'] = 'RCS';
                    $data['Resp_desc'] = 'Agent Deleted Successfully';
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
