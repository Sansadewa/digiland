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
 * @property Log_model $log_model
 */
class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('gift_model');
        $this->load->model('log_model');
        
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
        $this->load->view('admin/header');
        $this->load->view('admin/users');
    }

    /**
     * Get users data for DataTables with server-side processing
     */
    public function get_users_ajax() {
        // Check if it's an AJAX request
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        // Get DataTables parameters
        $draw = intval($this->input->get('draw'));
        $start = intval($this->input->get('start'));
        $length = intval($this->input->get('length'));
        $search = $this->input->get('search')['value'] ?? '';
        $order_column = $this->input->get('order')[0]['column'] ?? 0;
        $order_dir = $this->input->get('order')[0]['dir'] ?? 'asc';

        // Map column index to database column name
        $columns = [
            0 => 'name',
            1 => 'phone',
            2 => 'username',
            3 => 'show_gift_section',
            4 => 'created_at'
        ];
        $order_by = $columns[$order_column] ?? 'created_at';

        // Get users data with pagination and search
        $users = $this->gift_model->get_users_datatable($start, $length, $search, $order_by, $order_dir);
        $total_records = $this->gift_model->count_all_users();
        $total_filtered = !empty($search) ? $this->gift_model->count_filtered_users($search) : $total_records;

        // Prepare data for DataTables
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'name' => '<div class="flex items-center">
                    
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">' . htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . '</div>
                        <div class="text-sm text-gray-500">@' . htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') . '</div>
                    </div>
                </div>',
                'phone' => $user['phone'] ?: 'N/A',
                'username' => '<a href="' . base_url($user['username']) . '" target="_blank" class="text-emerald-600 hover:text-emerald-900">' . 
                             htmlspecialchars(base_url($user['username']), ENT_QUOTES, 'UTF-8') . '</a>',
                'show_gift_section' => $user['show_gift_section'] ? 'Yes' : 'No',
                'created_at' => date('M j, Y g:i A', strtotime($user['created_at'])),
                'actions' => '<button data-name="' . htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . '" ' .
                           'data-username="' . htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') . '" ' .
                           'class="copy-invite-btn text-emerald-600 hover:text-emerald-900 mr-3" title="Copy Invitation">' .
                           '<i class="fas fa-envelope"></i> Invite</button>' .
                           '<a href="' . base_url('admin/users/edit/' . $user['id']) . '" class="text-emerald-600 hover:text-emerald-900 mr-3">' .
                           '<i class="fas fa-edit"></i> Edit</a>' .
                           '<a href="' . base_url('admin/users/delete/' . $user['id']) . '" ' .
                           'onclick="return confirm(\'Are you sure you want to delete this user? This action cannot be undone.\')" ' .
                           'class="text-red-600 hover:text-red-900">' .
                           '<i class="fas fa-trash"></i> Delete</a>'
            ];
        }

        // Prepare JSON response
        $response = [
            'draw' => $draw,
            'recordsTotal' => $total_records,
            'recordsFiltered' => $total_filtered,
            'data' => $data
        ];

        // Send JSON response
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * Add User
     */
    public function add_user() {
        // Set default response
        $response = ['status' => 'error', 'message' => 'An error occurred'];
        
        if ($this->input->is_ajax_request() && $this->input->post()) {
            $username = $this->input->post('username');
            
            // Check if username already exists
            $existing_user = $this->gift_model->get_user_by_username($username);
            
            if ($existing_user) {
                $response['message'] = 'Username ' . $username . ' already exists';
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
                return;
            }
            
            // Check if username uses preserved routes or methods
            $reserved_routes = ['admin', 'login', 'logout', 'users', 'add_user', 'edit_user', 'delete_user', 'gifts', 'add_gift', 'edit_gift', 'delete_gift', 'reset_gift'];
            if (in_array($username, $reserved_routes)) {
                $response['message'] = 'Username ' . $username . ' is reserved';
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
                return;
            }

            $user_data = [
                'username' => $username,
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'show_gift_section' => $this->input->post('show_gift_section') ? 1 : 0,
                'difficulty' => (int)$this->input->post('difficulty') ?: 0,
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($this->db->insert('users', $user_data)) {
                $response = [
                    'status' => 'success',
                    'message' => 'User added successfully!'
                ];
            } else {
                $response['message'] = 'Failed to add user. Please try again.';
            }
        } else {
            $response['message'] = 'Invalid request';
        }
        
        // Return JSON response
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
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
                'difficulty' => (int)$this->input->post('difficulty') ?: 0
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
     * Logs Management Page
     */
    public function logs() {
        $this->load->view('admin/header');
        $this->load->view('admin/log');
    }

    /**
     * Get logs by action via AJAX
     */
    public function get_logs_by_action() {
        $action = $this->input->get('action');
        $logs = $this->log_model->get_log($action);
        
        // Add gift names and format data
        foreach ($logs as &$log) {
            if (!empty($log['gift_id'])) {
                $gift = $this->gift_model->get_gift($log['gift_id']);
                $log['gift_name'] = $gift ? $gift['name'] : 'Unknown Gift';
            } else {
                $log['gift_name'] = '-';
            }
            $log['created_at'] = isset($log['created_at']) ? $log['created_at'] : date('Y-m-d H:i:s');
        }
        
        header('Content-Type: application/json');
        echo json_encode($logs);
    }

    /**
     * Get user activity via AJAX
     */
    public function get_user_activity() {
        $username = $this->input->get('username');
        $logs = $this->log_model->get_activity($username);
        
        // Add gift names and format data
        foreach ($logs as &$log) {
            if (!empty($log['gift_id'])) {
                $gift = $this->gift_model->get_gift($log['gift_id']);
                $log['gift_name'] = $gift ? $gift['name'] : 'Unknown Gift';
            } else {
                $log['gift_name'] = '-';
            }
            $log['created_at'] = isset($log['created_at']) ? $log['created_at'] : date('Y-m-d H:i:s');
        }
        
        header('Content-Type: application/json');
        echo json_encode($logs);
    }

    /**
     * Get all usernames for autocomplete via AJAX
     */
    public function get_usernames() {
        $usernames = $this->gift_model->get_all_users();
        $result = [];
        foreach ($usernames as $user) {
            $result[] = $user['username'];
        }
        
        header('Content-Type: application/json');
        echo json_encode($result);
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