<?php 

defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Tickets_model extends CI_Model
{ 

    public function get_tickets($tickets_id = null){
        
        // $this->db->select('tickets.*, users.email AS customer_email, users.email as agent_email');

        $this->db->select('
            tickets.id, 
            tickets.user_id, 
            customer.email AS customer_email, 
            tickets.agent_id, 
            agent.email AS agent_email, 
            tickets.category_id, 
            category.name AS category_name,
            tickets.title, 
            tickets.description, 
            tickets.status, 
            tickets.priority, 
            tickets.created_at, 
            tickets.updated_at
        ');
        $this->db->from('tickets');
        $this->db->join('users AS customer', 'tickets.user_id = customer.id','left');
        $this->db->join('users AS agent', 'tickets.agent_id = agent.id','left');
        $this->db->join('categories AS category', 'tickets.category_id = category.id');

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