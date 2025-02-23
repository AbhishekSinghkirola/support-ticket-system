<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Auth_model extends CI_Model
{
    /* ------------------------ Function to validate User ----------------------- */
    public function check_valid_user($mobile, $password, $role)
    {
        $mobile = is_string($mobile) ? trim($mobile) : '';
        $password = is_string($password) ? trim($password) : '';
        $role = is_string($role) ? trim($role) : '';
        $res = $this->db->get_where('users', ['mobile' => $mobile, 'password' => $password, 'role' => $role, 'account_status' => 'ACTIVE'])->row_array();
        if ($res) return $res;
    }

    /* ------------------- Function Check User Already Exsist ------------------ */
    public function check_user($email, $mobile)
    {
        if (!empty($email) && !empty($mobile)) {
            $res = $this->db->where(['email', $email])->or_where('mobile', $mobile)->get('users')->row_array();
            if ($res) return $res;
        }
    }

    /* ---------------------- Function To Insert User Data ---------------------- */
    public function insert_user($insert_array)
    {
        if (!empty($insert_array)) {
            $insert = $this->db->insert('users', $insert_array);
            return true;
        }
    }
}
