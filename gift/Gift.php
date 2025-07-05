<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Gifts Controller
 * * This controller handles the display of the wedding registry page
 * and all related AJAX API endpoints for booking and purchasing gifts.
 * * @property CI_Input $input
 * @property CI_Session $session
 * @property Gift_model $gift_model // Assuming you create a model named 'Gift_model'
 */
class Gifts extends CI_Controller {

    // --- Simulated Database Data ---
    // In a real application, this data would come from your database via a model.
    private $gifts_data = []; 

    public function __construct() {
        parent::__construct();
        // Load necessary libraries and helpers
        $this->load->library('session');
        $this->load->helper('url');
        // It's highly recommended to create a model to handle database interactions.
        // $this->load->model('gift_model'); 

        // Initialize the simulated data
        $this->_initialize_simulated_data();
    }

    /**
     * ===================================================================
     * DATABASE STRUCTURE RECOMMENDATION
     * ===================================================================
     * * To support this controller, I recommend the following database tables.
     *
     * 1. `users` table: To store guest information.
     * - id (INT, PRIMARY KEY, AUTO_INCREMENT)
     * - username (VARCHAR, UNIQUE) - e.g., 'john_doe'
     * - created_at (DATETIME)
     *
     * 2. `gifts` table: To store all the gift items for the registry.
     * - id (INT, PRIMARY KEY, AUTO_INCREMENT)
     * - name (VARCHAR)
     * - description (TEXT)
     * - price (DECIMAL(10, 2))
     * - image_url (VARCHAR)
     * - store_url (VARCHAR) - Link to the product page on an e-commerce site
     * - category (VARCHAR) - e.g., 'Home', 'Kitchen'
     * - status (ENUM('available', 'booked', 'purchased')) - Default: 'available'
     * - booked_by_user_id (INT, NULL, FOREIGN KEY to users.id)
     * - booked_until (DATETIME, NULL)
     * - purchased_by_user_id (INT, NULL, FOREIGN KEY to users.id)
     * - order_number (VARCHAR, NULL)
     *
     * This structure allows you to track which user has booked or purchased a gift,
     * manage booking expiration, and store all necessary gift details.
     *
     */


    /**
     * Main Registry Page
     * Corresponds to the URL: abc.com/gifts/{username}
     *
     * @param string $username The username of the guest viewing the page.
     */
    public function index($username = '') {
        if (empty($username)) {
            show_404();
        }
        
        // --- User Session Management ---
        // In a real CI3 app, you would have a login system.
        // This simulates setting a session when a user visits their unique URL.
        $this->session->set_userdata('user_id', 'user-123'); // Replace with dynamic user ID from DB
        $this->session->set_userdata('username', $username);

        // Pass the initial gift data to the view
        $data['initial_gifts_json'] = json_encode(array_values($this->gifts_data));

        $this->load->view('registry_view', $data); 
    }

    /**
     * API: Get Gift Details
     * Endpoint: /gifts/get_details
     * Method: GET
     */
    public function get_details() {
        $giftId = $this->input->get('id', TRUE);

        if (!$giftId) {
            return $this->output->set_status_header(400)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Gift ID is required.']));
        }

        $gift = isset($this->gifts_data[$giftId]) ? $this->gifts_data[$giftId] : null;

        if ($gift) {
            return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode(['success' => true, 'data' => $gift]));
        } else {
            return $this->output->set_status_header(404)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Gift not found.']));
        }
    }

    /**
     * API: Book a Gift
     * Endpoint: /gifts/book
     * Method: POST
     */
    public function book() {
        $userId = $this->session->userdata('user_id');
        if (!$userId) {
            return $this->output->set_status_header(401)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Unauthorized. Please log in.']));
        }

        $data = json_decode($this->input->raw_input_stream, true);
        $giftId = isset($data['giftId']) ? (int)$data['giftId'] : 0;

        // --- Server-Side Validation (Implemented with Simulation) ---

        // 1. Check if user already has a booking
        foreach ($this->gifts_data as $g) {
            if ($g['status'] === 'booked' && $g['booked_by_user_id'] === $userId) {
                return $this->output->set_status_header(409)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'You already have an active booking.']));
            }
        }

        // 2. Check if gift is available
        $gift = isset($this->gifts_data[$giftId]) ? $this->gifts_data[$giftId] : null;
        if (!$gift || $gift['status'] !== 'available') {
            return $this->output->set_status_header(409)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'This gift is no longer available.']));
        }
        
        // 3. Update the gift (Simulated DB update)
        // $booking_expiry = date('Y-m-d H:i:s', time() + 15 * 60);
        // In a real app, you would now call your model to update the database.
        // $this->gift_model->book_item($giftId, $userId, $booking_expiry);

        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode(['success' => true, 'message' => 'Gift booked successfully.']));
    }

    /**
     * API: Confirm a Purchase
     * Endpoint: /gifts/confirm_purchase
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

        // --- Server-Side Validation & Update (Implemented with Simulation) ---
        
        // 1. Verify the gift was booked by this user
        $gift = isset($this->gifts_data[$giftId]) ? $this->gifts_data[$giftId] : null;
        if (!$gift || $gift['booked_by_user_id'] !== $userId) {
            return $this->output->set_status_header(403)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'You are not authorized to confirm this purchase.']));
        }

        // 2. Update the gift status to 'purchased' (Simulated DB update)
        // In a real app, call your model:
        // $this->gift_model->confirm_purchase($giftId, $userId, $orderNumber);

        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode(['success' => true, 'message' => 'Purchase confirmed.']));
    }
    
    /**
     * API: Cancel a Booking
     * Endpoint: /gifts/cancel_booking
     * Method: POST
     */
    public function cancel_booking() {
        $userId = $this->session->userdata('user_id');
        if (!$userId) {
            return $this->output->set_status_header(401)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Unauthorized.']));
        }

        $data = json_decode($this->input->raw_input_stream, true);
        $giftId = isset($data['giftId']) ? (int)$data['giftId'] : 0;

        // --- Server-Side Validation & Update (Implemented with Simulation) ---
        
        // 1. Verify the gift was booked by this user
        $gift = isset($this->gifts_data[$giftId]) ? $this->gifts_data[$giftId] : null;
        if (!$gift || $gift['booked_by_user_id'] !== $userId) {
            return $this->output->set_status_header(403)->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'You are not authorized to cancel this booking.']));
        }

        // 2. Update the gift status back to 'available' (Simulated DB update)
        // In a real app, call your model:
        // $this->gift_model->cancel_booking($giftId);

        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode(['success' => true, 'message' => 'Booking cancelled.']));
    }

    /**
     * Private helper method to set up the simulated data.
     * In a real app, you would fetch this from the database in your model.
     */
    private function _initialize_simulated_data() {
        $gifts_array = [
            ['id' => 1, 'name' => 'AZKO Kris Hair Dryer Travel 600 Watt', 'price' => 104900, 'category' => 'Electronics', 'status' => 'available', 'booked_by_user_id' => null],
            ['id' => 2, 'name' => 'Informa Filio Meja Setrika Lipat Classic', 'price' => 230000, 'category' => 'Home', 'status' => 'purchased', 'booked_by_user_id' => 'user-456'],
            ['id' => 10, 'name' => 'Le Creuset Signature Round Dutch Oven', 'price' => 4500000, 'category' => 'Kitchen', 'status' => 'booked', 'booked_by_user_id' => 'user-123'], // Booked by current user
        ];

        // Convert array to associative array with ID as key for easy lookup
        foreach ($gifts_array as $gift) {
            $this->gifts_data[$gift['id']] = $gift;
        }
    }
}
