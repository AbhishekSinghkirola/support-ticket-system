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
}
