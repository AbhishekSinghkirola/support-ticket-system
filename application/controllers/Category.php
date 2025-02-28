<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends CI_Controller
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

        $this->load->model('Category_model', 'category_md');
    }

    /* ------------------------ Function To Load Categories Page ------------------------ */
    public function index()
    {
        $this->load->view('template/header');
        $this->load->view('categories');
        $this->load->view('template/footer');
    }

    /* ----------------------- Function to get categories ----------------------- */
    public function get_categories()
    {
        $categories = $this->category_md->get_categories();

        $data = [];

        $data['Resp_code'] = 'RCS';
        $data['Resp_desc'] = 'Categories Fetched Successfully';
        $data['data'] = is_array($categories) ? $categories : [];
        echo json_encode($data);
    }

    /* ------------------------ Function to add category ------------------------ */
    public function add_category()
    {
        $params = $this->input->post();


        $category_name = isset($params['category_name']) ? $params['category_name'] : '';
        $description = isset($params['description']) ? $params['description'] : '';

        if (validate_field($category_name, 'strname')) {

            if (validate_field($description, 'description')) {

                $unique_category = $this->category_md->unique_category($category_name);

                if (empty($unique_category)) {

                    $inserted_data = [
                        'name' => $category_name,
                        'description' => $description,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];

                    $category = $this->category_md->add_category($inserted_data);

                    if ($category) {
                        $data['Resp_code'] = 'RCS';
                        $data['Resp_desc'] = 'Category Added Successfully';
                    } else {
                        $data['Resp_code'] = 'ERR';
                        $data['Resp_desc'] = 'Failed to add category';
                    }
                } else {
                    $data['Resp_code'] = 'ERR';
                    $data['Resp_desc'] = 'Category Name already exists';
                }
            } else {
                $data['Resp_code'] = 'ERR';
                $data['Resp_desc'] = 'Description is required';
            }
        } else {
            $data['Resp_code'] = 'ERR';
            $data['Resp_desc'] = 'Category Name is required';
        }



        echo json_encode($data);
    }

    /* ----------------------- Function to delete category ---------------------- */
    public function delete_category()
    {
        $params = $this->input->post();

        $category_id = isset($params['category_id']) ? $params['category_id'] : '';

        if ($category_id) {

            $this->category_md->delete_category($category_id);

            $data['Resp_code'] = 'RCS';
            $data['Resp_desc'] = 'Category Deleted Successfully';
        } else {
            $data['Resp_code'] = 'ERR';
            $data['Resp_desc'] = 'Category ID is required';
        }

        echo json_encode($data);
    }

    /* ------------------------ Function to edit category ----------------------- */
    public function edit_category()
    {
        $params = $this->input->post();

        $category_id = isset($params['category_id']) ? $params['category_id'] : '';
        $category_name = isset($params['category_name']) ? $params['category_name'] : '';
        $description = isset($params['description']) ? $params['description'] : '';

        if ($category_id) {

            if (validate_field($category_name, 'strname')) {

                if (validate_field($description, 'description')) {

                    $unique_category = $this->category_md->unique_category($category_name);

                    if (empty($unique_category) || $unique_category['id'] == $category_id) {

                        $updated_data = [
                            'name' => $category_name,
                            'description' => $description,
                        ];

                        $this->category_md->edit_category($category_id, $updated_data);

                        $data['Resp_code'] = 'RCS';
                        $data['Resp_desc'] = 'Category Updated Successfully';
                    } else {
                        $data['Resp_code'] = 'ERR';
                        $data['Resp_desc'] = 'Category Name already exists';
                    }
                } else {
                    $data['Resp_code'] = 'ERR';
                    $data['Resp_desc'] = 'Description is required';
                }
            } else {
                $data['Resp_code'] = 'ERR';
                $data['Resp_desc'] = 'Category Name is required';
            }
        } else {
            $data['Resp_code'] = 'ERR';
            $data['Resp_desc'] = 'Category ID is required';
        }

        echo json_encode($data);
    }
}
