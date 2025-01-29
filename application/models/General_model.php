<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class General_model extends CI_Model
{
    public function get_roles($with_admin = true)
    {
        if (!$with_admin) {
            $this->db->where('role_type!=', 'ADMIN');
        }
        $res = $this->db->get('role')->result_array();
        if ($res) return $res;
    }


    public function get_user($user_id, $role_id)
    {

        $table = 'users';
        $this->db->where($table . '.user_id', $user_id);

        $this->db->join('role', $table . '.role_id=role.role_id');
        $res = $this->db->get($table)->row_array();
        if ($res) return $res;
        
        // $table = '';
        // if ($role_id == '1') {
        //     $table = 'users';
        //     $this->db->where($table . '.user_id', $user_id);
        // } else if ($role_id == '2') {
        //     $table = 'teacher';
        //     $this->db->where($table . '.teacher_id', $user_id);
        // } else if ($role_id == '3') {
        //     $table = 'student';
        //     $this->db->where($table . '.student_id', $user_id);
        // }

        // if ($table) {
        //     $this->db->join('role', $table . '.role_id=role.role_id');
        //     $res = $this->db->get($table)->row_array();
        //     if ($res) return $res;
        // } else {
        //     return false;
        // }
    }
  
}
