<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Category_model extends CI_Model
{
    /* --------------------- function to get all categories --------------------- */
    public function get_categories()
    {
        $this->db->from('categories');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /* -------------------- function to check unique category ------------------- */
    public function unique_category($category_name)
    {
        $this->db->from('categories');
        $this->db->where('name', $category_name);
        $query = $this->db->get()->row_array();
        return $query;
    }

    /* ------------------------ function to add category ------------------------ */
    public function add_category($data)
    {
        $this->db->insert('categories', $data);
        return $this->db->insert_id();
    }

    /* ----------------------- function to delete category ---------------------- */
    public function delete_category($category_id)
    {
        $this->db->where('id', $category_id);
        $this->db->delete('categories');
        return $this->db->affected_rows();
    }

    /* ------------------------ function to edit category ----------------------- */
    public function edit_category($category_id, $data)
    {
        $this->db->where('id', $category_id);
        $this->db->update('categories', $data);
        return $this->db->affected_rows();
    }
}
