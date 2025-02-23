<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class User_model extends CI_Model
{
    /* ------------------------ Function to Get Users ----------------------- */
    public function get_users()
    {

        $res = $this->db->where('role', 'USER')->get('users')->result_array();

        if ($res) return $res;
    }

    /* ----------------------- Function To Get user By ID ----------------------- */
    public function get_user($user_id)
    {
        $res = $this->db->where('id', $user_id)->get('users')->row_array();
        if ($res) return $res;
    }

    /* ------------------------- Function to update user ------------------------ */
    public function update_user($user_id, $update_data)
    {
        $res = $this->db->where('id', $user_id)->update('users', $update_data);
        if ($res) return $res;
    }

    /* ------------------------- Function to delete user ------------------------ */
    public function delete_user($user_id)
    {
        $res = $this->db->where('id', $user_id)->delete('users');
        if ($res) return $res;
    }
}
