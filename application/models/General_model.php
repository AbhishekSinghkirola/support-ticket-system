<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class General_model extends CI_Model
{


    public function get_user($user_id)
    {

        $res = $this->db->get('users')->row_array();
        if ($res) return $res;
    }

    public function unique_user($email, $mobile)
    {
        $res = $this->db->where('email', $email)->or_where('mobile', $mobile)->get('users')->row_array();
        if ($res) return $res;
    }
}
