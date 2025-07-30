<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Gift Controller
 * 
 * This controller handles the display of the wedding registry page
 * and all related AJAX API endpoints for booking and purchasing gifts.
 * 
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Gift_model $gift_model
 */
class Gift extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load necessary libraries and helpers
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('gift_model');



    }

    /**
     * Main Registry Page
     * Corresponds to the URL: gift.digiland.space/{username}
     *
     * @param string $username The username of the guest viewing the page.
     */
    public function index($username = '') {
        if (empty($username)) {
            // If no username provided, show a default page or redirect
            $this->show_welcome_page();
            return;
        }
        
        // Get or create user by username
        $user = $this->gift_model->get_user_by_username($username);
        if (!$user) {
            $this->show_missing_page();
            return;
        }

        //debug before sess destroy
        log_message('debug', 'index() before destroy session - User ID: ' . $user['id']);

        //destroy session
        // session_destroy();
        
        // Set user session data
        $this->session->set_userdata('user_id', $user['id']);
        $this->session->set_userdata('username', $username);
        $this->session->set_userdata('nama', $user['name']);

        //debug after sess destroy
        log_message('debug', 'index() after destroy session - User ID: ' . $user['id']);

        // Get all gifts from database
        $gifts = $this->gift_model->get_all_gifts();
        
        // Pass the initial gift data to the view
        $data['initial_gifts_json'] = json_encode($gifts);
        $data['username'] = $username;
        $data['nama'] = $user['name'];

        $this->load->view('gift', $data); 
    }

    /**
     * Welcome page when no username is provided
     */
    private function show_welcome_page() {
        $data['title'] = 'Selamat Datang di Digiland Wedding Registry';
        $data['message'] = 'Silahkan gunakan link dari undangan digital untuk mengakses registry. Terima kasih!';
        $this->load->view('welcome', $data);
    }

    /**
     * Missing page when  username is not found
     */
    private function show_missing_page() {
        $data['title'] = 'Wah! Username kamu tidak ditemukan!';
        $data['message'] = 'Silahkan gunakan link dari undangan digital untuk mengakses registry.';
        $this->load->view('welcome', $data);
    }

    /**
     * 404 Not Found handler
     */
    public function not_found() {
        $this->output->set_status_header(404);
        $data['title'] = '404 Page Not Found';
        $data['message'] = 'The page you are looking for could not be found.';
        $this->load->view('welcome', $data);
    }

    /**
     * API: Get Gift Details
     * Endpoint: gift.digiland.space/get_details
     * Method: GET
     */
    public function get_details() {
        $giftId = $this->input->get('id', TRUE);

        if (!$giftId) {
            return $this->output->set_status_header(400)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Gift ID is required.']));
        }

        $gift = $this->gift_model->get_gift($giftId);

        if ($gift) {
            return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode(['success' => true, 'data' => $gift]));
        } else {
            return $this->output->set_status_header(404)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Gift not found.']));
        }
    }

    /**
     * API: Book a Gift
     * Endpoint: gift.digiland.space/book
     * Method: POST
     */
    public function book() {
        $userId = $this->session->userdata('user_id');
        //debug user id
        log_message('debug', 'book() - User ID: ' . $this->session->userdata('user_id'));

        //debug last connection
        log_message('debug', 'book() - Last connection: ' . $this->session->userdata('last_connection'));
        if (!$userId) {
            //debug all userdata
            log_message('debug', 'book() - User ID not found in session: ' . print_r($this->session->all_userdata(), true));

            return $this->output->set_status_header(401)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Unauthorized User ID. Please log in.'.$userId]));
        }

        $data = json_decode($this->input->raw_input_stream, true);
        $giftId = isset($data['giftId']) ? (int)$data['giftId'] : 0;

        // Server-Side Validation
        // 1. Check if user already has a booking
        if ($this->gift_model->check_user_booking($userId)) {
            return $this->output->set_status_header(409)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'You already have an active booking.'.$userId]));
        }

        // 2. Check if gift is available and book it
        if (!$this->gift_model->book_gift($giftId, $userId)) {
            return $this->output->set_status_header(409)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Bingkisan Tidak Tersedia. Mohon refresh.']));
        }

        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode(['success' => true, 'message' => 'Bingkisan berhasil di booking.']));
    }

    /**
     * API: Confirm a Purchase
     * Endpoint: gift.digiland.space/confirm_purchase
     * Method: POST
     */
    public function confirm_purchase() {
        $userId = $this->session->userdata('user_id');
        if (!$userId) {
            return $this->output->set_status_header(401)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Unauthorized.']));
        }

        $data = json_decode($this->input->raw_input_stream, true);
        $giftId = isset($data['giftId']) ? (int)$data['giftId'] : 0;
        $orderNumber = isset($data['orderNumber']) ? trim($data['orderNumber']) : '';

        if (!$giftId || empty($orderNumber)) {
            return $this->output->set_status_header(400)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Gift ID and Order Number are required.']));
        }

        // Confirm purchase in database
        if (!$this->gift_model->confirm_purchase($giftId, $userId, $orderNumber)) {
            return $this->output->set_status_header(403)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'You are not authorized to confirm this purchase.']));
        }

        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode(['success' => true, 'message' => 'Purchase confirmed.']));
    }
    
    /**
     * API: Cancel a Booking
     * Endpoint: gift.digiland.space/cancel_booking
     * Method: POST
     */
    public function cancel_booking() {
        $userId = $this->session->userdata('user_id');
        if (!$userId) {
            return $this->output->set_status_header(401)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Unauthorized.']));
        }

        $data = json_decode($this->input->raw_input_stream, true);
        $giftId = isset($data['giftId']) ? (int)$data['giftId'] : 0;

        // Cancel booking in database
        if (!$this->gift_model->cancel_booking($giftId, $userId)) {
            return $this->output->set_status_header(403)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'You are not authorized to cancel this booking.']));
        }

        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode(['success' => true, 'message' => 'Booking cancelled.']));
    }

    /**
     * API: Get Updated Gift List
     * Endpoint: gift.digiland.space/get_gifts
     * Method: GET
     * Used for refreshing the gift list after bookings/purchases
     */
    public function get_gifts() {
        $gifts = $this->gift_model->get_all_gifts();
        
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode(['success' => true, 'data' => $gifts]));
    }
}