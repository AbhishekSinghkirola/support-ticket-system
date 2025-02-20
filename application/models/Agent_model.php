<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Agent_model extends CI_Model
{
    /* ------------------------ Function to Get Agents ----------------------- */
    public function get_agents()
    {

        $res = $this->db->where('role', 'SUPPORT')->get('users')->result_array();

        if ($res) return $res;
    }

    /* ----------------------- Function to insert new agent ---------------------- */
    public function add_agent($insert_data)
    {
        $res = $this->db->insert('users', $insert_data);
        if ($res) return $res;
    }

    /* ----------------------- Function To Get agent By ID ----------------------- */
    public function get_agent($user_id)
    {
        $res = $this->db->where('id', $user_id)->get('users')->row_array();
        if ($res) return $res;
    }

    /* ------------------------- Function to update user ------------------------ */
    public function update_agent($user_id, $update_data)
    {
        $res = $this->db->where('id', $user_id)->update('users', $update_data);
        if ($res) return $res;
    }

    /* ------------------------- Function to delete user ------------------------ */
    public function delete_agent($user_id)
    {
        $res = $this->db->where('id', $user_id)->delete('users');
        if ($res) return $res;
    }
}
