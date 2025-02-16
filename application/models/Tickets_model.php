<?php 

defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Tickets_model extends CI_Model
{ 

    public function get_tickets($tickets_id = null){
        
        $this->db->select('*');

        $this->db->from('tickets');
        $this->db->join('users AS agent', 'tickets.agent_id = agent.id');
        $this->db->join('users AS customer', 'tickets.user_id = customer.id');

        $this->db->join('users AS customer', 'tickets.user_id = customer.id', 'left');
        $this->db->join('users AS agent', 'tickets.agent_id = agent.id', 'left');

        // $this->db->join('users', 'tickets.agent_id=users.id');

        //dd($this->db->get_compiled_select());

        if ($tickets_id) {
            $this->db->where('id', $tickets_id);
            $res = $this->db->get()->row_array();

        } else {
            $res = $this->db->get()->result_array();
        }
        
        if ($res) {
            return $res;
        }


    }

}

?>