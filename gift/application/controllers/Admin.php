<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin Controller
 * 
 * Handles the admin panel for managing gifts and users.
 * 
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Gift_model $gift_model
 */
class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('gift_model');
        
        // Check if admin is logged in (except for login page)
        if ($this->uri->segment(2) !== 'login' && !$this->is_admin_logged_in()) {
            redirect('admin/login');
        }
    }

    /**
     * Admin Dashboard
     */
    public function index() {
        $data['stats'] = $this->gift_model->get_statistics();
        $data['recent_users'] = $this->gift_model->get_all_users();
        $data['recent_gifts'] = $this->gift_model->get_all_gifts();
        $this->load->view('admin/header');
        $this->load->view('admin/dashboard', $data);
    }

    /**
     * Admin Login Page
     */
    public function login() {
        if ($this->is_admin_logged_in()) {
            redirect('admin');
        }

        if ($this->input->post()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            if ($this->authenticate_admin($username, $password)) {
                redirect('admin');
            } else {
                $data['error'] = 'Invalid username or password';
            }
        }
        $this->load->view('admin/login');
    }

    /**
     * Admin Logout
     */
    public function logout() {
        $this->session->unset_userdata('admin_id');
        $this->session->unset_userdata('admin_username');
        redirect('admin/login');
    }

    /**
     * Users Management
     */
    public function users() {
        $data['users'] = $this->gift_model->get_all_users();
        $this->load->view('admin/header');
        $this->load->view('admin/users', $data);
    }

    /**
     * Add User
     */
    public function add_user() {
        if ($this->input->post()) {
            $username = $this->input->post('username');
            
            // Check if username already exists
            $existing_user = $this->gift_model->get_user_by_username($username);

            
            if ($existing_user) {
                $this->session->set_flashdata('error', 'Username ' . $username . ' already exists');
                redirect('admin/users');
            }
            
            //check if username uses preserved routes or method
            $reserved_routes = ['admin', 'login', 'logout', 'users', 'add_user', 'edit_user', 'delete_user', 'gifts', 'add_gift', 'edit_gift', 'delete_gift', 'reset_gift'];
            if (in_array($username, $reserved_routes)) {
                $this->session->set_flashdata('error', 'Username ' . $username . ' is reserved');
                redirect('admin/users');
            }

            $user_data = [
                'username' => $username,
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'show_gift_section' => $this->input->post('show_gift_section'),
                'difficulty' => $this->input->post('difficulty'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Use the database insert directly since get_user_by_username creates users
            $this->db->insert('users', $user_data);
            
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success', 'User added successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to add user');
            }
            redirect('admin/users');
        }
        $this->load->view('admin/header');
        $this->load->view('admin/add_user');
    }

    /**
     * Edit User
     */
    public function edit_user($user_id = null) {
        if (!$user_id) {
            redirect('admin/users');
        }

        if ($this->input->post()) {
            $user_data = [
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'show_gift_section' => $this->input->post('show_gift_section'),
                'difficulty' => $this->input->post('difficulty')
            ];
            
            if ($this->gift_model->update_user($user_id, $user_data)) {
                $this->session->set_flashdata('success', 'User updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to update user');
            }
            redirect('admin/users');
        }

        $data['user'] = $this->gift_model->get_user($user_id);
        if (!$data['user']) {
            redirect('admin/users');
        }
        $this->load->view('admin/header');
        $this->load->view('admin/edit_user', $data);
    }

    /**
     * Delete User
     */
    public function delete_user($user_id = null) {
        if (!$user_id) {
            redirect('admin/users');
        }

        if ($this->gift_model->delete_user($user_id)) {
            $this->session->set_flashdata('success', 'User deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user');
        }
        redirect('admin/users');
    }

    /**
     * Gifts Management
     */
    public function gifts() {
        $data['gifts'] = $this->gift_model->get_all_gifts();
        $this->load->view('admin/header');
        $this->load->view('admin/gifts', $data);
    }

    /**
     * Add Gift
     */
    public function add_gift() {
        if ($this->input->post()) {
            $gift_data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'price' => $this->input->post('price'),
                'image_url' => $this->input->post('image_url'),
                'store_url' => $this->input->post('store_url'),
                'category' => $this->input->post('category'),
                'status' => 'available'
            ];
            
            if ($this->gift_model->add_gift($gift_data)) {
                $this->session->set_flashdata('success', 'Gift added successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to add gift');
            }
            redirect('admin/gifts');
        }
        $this->load->view('admin/header');
        $this->load->view('admin/add_gift');
    }

    /**
     * Edit Gift
     */
    public function edit_gift($gift_id = null) {
        if (!$gift_id) {
            redirect('admin/gifts');
        }

        if ($this->input->post()) {
            $gift_data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'price' => $this->input->post('price'),
                'image_url' => $this->input->post('image_url'),
                'store_url' => $this->input->post('store_url'),
                'category' => $this->input->post('category')
            ];
            
            if ($this->gift_model->update_gift($gift_id, $gift_data)) {
                $this->session->set_flashdata('success', 'Gift updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to update gift');
            }
            redirect('admin/gifts');
        }
        $data['gift'] = $this->gift_model->get_gift($gift_id);
        if (!$data['gift']) {
            redirect('admin/gifts');
        }
        $this->load->view('admin/header');
        $this->load->view('admin/edit_gift', $data);
    }

    /**
     * Delete Gift
     */
    public function delete_gift($gift_id = null) {
        if (!$gift_id) {
            redirect('admin/gifts');
        }

        if ($this->gift_model->delete_gift($gift_id)) {
            $this->session->set_flashdata('success', 'Gift deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete gift');
        }
        redirect('admin/gifts');
    }

    /**
     * Reset Gift Status
     */
    public function reset_gift($gift_id = null) {
        if (!$gift_id) {
            redirect('admin/gifts');
        }

        $gift_data = [
            'status' => 'available',
            'booked_by_user_id' => null,
            'booked_until' => null,
            'purchased_by_user_id' => null,
            'order_number' => null
        ];
        
        if ($this->gift_model->update_gift($gift_id, $gift_data)) {
            $this->session->set_flashdata('success', 'Gift status reset successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to reset gift status');
        }
        redirect('admin/gifts');
    }

    /**
     * Check if admin is logged in
     */
    private function is_admin_logged_in() {
        return $this->session->userdata('admin_id') !== null;
    }

    /**
     * Authenticate admin user
     */
    private function authenticate_admin($username, $password) {
        // For now, use hardcoded admin credentials
        // In production, you should use the admin_users table
        if ($username === 'admin' && $password === 'password') {
            $this->session->set_userdata('admin_id', 1);
            $this->session->set_userdata('admin_username', 'admin');
            return true;
        }
        return false;
    }
} 