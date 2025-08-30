<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Gift Model
 * 
 * Handles all database operations for the wedding registry gift system.
 * This model manages users, gifts, bookings, and purchases.
 * 
 * @property CI_DB_query_builder $db
 */
class Gift_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get or create user by username
     * 
     * @param string $username The username to search for or create
     * @return array|false User data array or false on failure
     */
    public function get_user_by_username($username) {
        // First, try to find existing user
        $user = $this->db->where('username', $username)->get('users')->row_array();
        
        if ($user) {
            return $user;
        }
        
        // If user doesn't exist, create new user with default values
        // $user_data = [
        //     'username' => $username,
        //     'name' => ucfirst(str_replace(['_', '-'], ' ', $username)), // Convert username to readable name
        //     'phone' => null,
        //     'created_at' => date('Y-m-d H:i:s')
        // ];
        
        // $this->db->insert('users', $user_data);
        
        // if ($this->db->affected_rows() > 0) {
        //     return $this->get_user_by_username($username); // Return the newly created user
        // }
        
        return false;
    }

    /**
     * Update user information
     * 
     * @param int $user_id The user ID
     * @param array $data User data to update
     * @return bool True on success, false on failure
     */
    public function update_user($user_id, $data) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }

    /**
     * Get all users
     * 
     * @return array Array of all users
     */
    public function get_all_users() {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('users')->result_array();
    }

    /**
     * Get user by ID
     * 
     * @param int $user_id The user ID
     * @return array|false User data array or false if not found
     */
    public function get_user($user_id) {
        return $this->db->where('id', $user_id)->get('users')->row_array();
    }

    /**
     * Delete user
     * 
     * @param int $user_id The user ID
     * @return bool True on success, false on failure
     */
    public function delete_user($user_id) {
        // First, clear any bookings/purchases by this user
        $this->db->where('booked_by_user_id', $user_id);
        $this->db->update('gifts', ['booked_by_user_id' => null, 'booked_until' => null, 'status' => 'available']);
        
        $this->db->where('purchased_by_user_id', $user_id);
        $this->db->update('gifts', ['purchased_by_user_id' => null]);
        
        // Then delete the user
        $this->db->where('id', $user_id);
        return $this->db->delete('users');
    }

    /**
     * Check if a booking is expired
     * 
     * @param string $booked_until The booking expiration timestamp
     * @return bool True if booking is expired, false otherwise
     */
    private function is_booking_expired($booked_until) {
        if (empty($booked_until)) {
            return false;
        }
        return strtotime($booked_until) < time();
    }

    /**
     * Get all gifts from the database with automatic expired booking cleanup
     * 
     * @return array Array of all gifts with expired bookings automatically cleared
     */
    public function get_all_gifts() {
        $this->db->select('g.*, u.username as booked_by_username, u.name as booked_by_name, pu.username as purchased_by_username, pu.name as purchased_by_name');
        $this->db->from('gifts g');
        $this->db->join('users u', 'u.id = g.booked_by_user_id', 'left');
        $this->db->join('users pu', 'pu.id = g.purchased_by_user_id', 'left');
        $this->db->order_by('g.id', 'ASC');
        
        $gifts = $this->db->get()->result_array();
        
        // Process gifts to handle expired bookings in-memory
        foreach ($gifts as &$gift) {
            if ($gift['status'] === 'booked' && $this->is_booking_expired($gift['booked_until'])) {
                // Clear expired booking data in the returned array
                $gift['status'] = 'available';
                $gift['booked_by_user_id'] = null;
                $gift['booked_until'] = null;
                $gift['booked_by_username'] = null;
                $gift['booked_by_name'] = null;
            }
        }
        
        return $gifts;
    }

    /**
     * Get a single gift by ID with automatic expired booking cleanup
     * 
     * @param int $gift_id The gift ID
     * @return array|false Gift data array or false if not found
     */
    public function get_gift($gift_id) {
        $this->db->select('g.*, u.username as booked_by_username, u.name as booked_by_name, pu.username as purchased_by_username, pu.name as purchased_by_name');
        $this->db->from('gifts g');
        $this->db->join('users u', 'u.id = g.booked_by_user_id', 'left');
        $this->db->join('users pu', 'pu.id = g.purchased_by_user_id', 'left');
        $this->db->where('g.id', $gift_id);
        
        $gift = $this->db->get()->row_array();
        
        // Handle expired booking in-memory
        if ($gift && $gift['status'] === 'booked' && $this->is_booking_expired($gift['booked_until'])) {
            $gift['status'] = 'available';
            $gift['booked_by_user_id'] = null;
            $gift['booked_until'] = null;
            $gift['booked_by_username'] = null;
            $gift['booked_by_name'] = null;
        }
        
        return $gift;
    }

    /**
     * Add new gift
     * 
     * @param array $data Gift data
     * @return int|false New gift ID or false on failure
     */
    public function add_gift($data) {
        $this->db->insert('gifts', $data);
        return $this->db->affected_rows() > 0 ? $this->db->insert_id() : false;
    }

    /**
     * Update gift
     * 
     * @param int $gift_id The gift ID
     * @param array $data Gift data to update
     * @return bool True on success, false on failure
     */
    public function update_gift($gift_id, $data) {
        $this->db->where('id', $gift_id);
        return $this->db->update('gifts', $data);
    }

    /**
     * Delete gift
     * 
     * @param int $gift_id The gift ID
     * @return bool True on success, false on failure
     */
    public function delete_gift($gift_id) {
        $this->db->where('id', $gift_id);
        return $this->db->delete('gifts');
    }

    /**
     * Check if a user already has an active booking
     * 
     * @param int $user_id The user ID to check
     * @return bool True if user has an active booking, false otherwise
     */
    public function check_user_booking($user_id) {
        // 1. Get the current time and explicitly set it to the UTC timezone.
        $now_utc = new DateTime('now', new DateTimeZone('UTC'));

        // 2. Format it into the IDENTICAL string format as your column.
        $now_utc_string = $now_utc->format('Y-m-d\TH:i:s\Z');
        // This will produce a string like '2025-07-29T13:52:21Z'

        // 3. Now, use THIS string in your query.
        $this->db->where('booked_by_user_id', $user_id);
        $this->db->where('status', 'booked');
        $this->db->where('booked_until >', $now_utc_string); // Only count non-expired bookings
        
        $count = $this->db->count_all_results('gifts');
        
        //log process and query for debug
        log_message('debug', 'check_user_booking query: ' . $this->db->last_query());
        log_message('debug', 'check_user_booking count: ' . $count.' user_id: '.$user_id);

        return $count > 0;
    }

    /**
     * Book a gift for a user with automatic expired booking handling
     * 
     * @param int $gift_id The gift ID to book
     * @param int $user_id The user ID booking the gift
     * @return bool True on success, false on failure
     */
    public function book_gift($gift_id, $user_id) {
        // First, check if the gift exists and get its current status
        $current_gift = $this->db->where('id', $gift_id)->get('gifts')->row_array();
        
        if (!$current_gift) {
            return false; // Gift doesn't exist
        }

        
        
        // If gift is booked but expired, clear the expired booking first
        if ($current_gift['status'] === 'booked' && $this->is_booking_expired($current_gift['booked_until'])) {
            $this->db->where('id', $gift_id);
            $this->db->update('gifts', [
                'status' => 'available',
                'booked_by_user_id' => null,
                'booked_until' => null
            ]);
        }

        //if current gift is not available
        if ($current_gift['status'] !== 'available') {
            return false; // Gift is not available
        }
        
        // Old code:
        // $booked_until = date('Y-m-d H:i:s', time() + (15 * 60));

        // New, correct code:
        // 1. Create a new DateTime object, explicitly setting its timezone to UTC.
        $booked_until = new DateTime('now', new DateTimeZone('UTC'));

        // 2. Add 15 minutes to it.
        $booked_until->add(new DateInterval('PT15M'));

        // 3. Format it as an ISO 8601 string to be saved/sent to the browser.
        $booked_until_string = $booked_until->format('Y-m-d\TH:i:s\Z');
        
        $data = [
            'status' => 'booked',
            'booked_by_user_id' => $user_id,
            'booked_until' => $booked_until_string
        ];
        
        $this->db->where('id', $gift_id);
        $this->db->where('status', 'available'); // Only allow booking available gifts
        
        return $this->db->update('gifts', $data);
    }

    /**
     * Confirm purchase of a gift
     * 
     * @param int $gift_id The gift ID
     * @param int $user_id The user ID confirming the purchase
     * @param string $order_number The order number from the purchase
     * @return bool True on success, false on failure
     */
    public function confirm_purchase($gift_id, $user_id, $order_number) {
        // Verify the gift was booked by this user
        $this->db->where('id', $gift_id);
        $this->db->where('booked_by_user_id', $user_id);
        $this->db->where('status', 'booked');
        
        $gift = $this->db->get('gifts')->row_array();
        
        if (!$gift) {
            return false; // Gift not found or not booked by this user
        }
        
        $data = [
            'status' => 'purchased',
            'purchased_by_user_id' => $user_id,
            'order_number' => $order_number,
            'booked_by_user_id' => null, // Clear booking info
            'booked_until' => null
        ];
        
        $this->db->where('id', $gift_id);
        
        return $this->db->update('gifts', $data);
    }

    /**
     * Cancel a booking
     * 
     * @param int $gift_id The gift ID
     * @param int $user_id The user ID canceling the booking
     * @return bool True on success, false on failure
     */
    public function cancel_booking($gift_id, $user_id) {
        // Verify the gift was booked by this user
        $this->db->where('id', $gift_id);
        $this->db->where('booked_by_user_id', $user_id);
        $this->db->where('status', 'booked');
        
        $gift = $this->db->get('gifts')->row_array();
        
        if (!$gift) {
            return false; // Gift not found or not booked by this user
        }
        
        $data = [
            'status' => 'available',
            'booked_by_user_id' => null,
            'booked_until' => null
        ];
        
        $this->db->where('id', $gift_id);
        
        return $this->db->update('gifts', $data);
    }

    /**
     * Clean up expired bookings
     * This method should be called periodically (e.g., via cron job)
     * 
     * @return int Number of expired bookings cleaned up
     */
    public function cleanup_expired_bookings() {
        $this->db->where('status', 'booked');
        $this->db->where('booked_until <', date('Y-m-d H:i:s'));
        
        $data = [
            'status' => 'available',
            'booked_by_user_id' => null,
            'booked_until' => null
        ];
        
        $this->db->update('gifts', $data);
        
        return $this->db->affected_rows();
    }

    /**
     * Get statistics for admin dashboard
     * 
     * @return array Statistics data
     */
    public function get_statistics() {
        $stats = [];
        
        // Total users
        $stats['total_users'] = $this->db->count_all('users');
        
        // Total gifts
        $stats['total_gifts'] = $this->db->count_all('gifts');
        
        // Available gifts
        $stats['available_gifts'] = $this->db->where('status', 'available')->count_all_results('gifts');
        
        // Booked gifts
        $stats['booked_gifts'] = $this->db->where('status', 'booked')->count_all_results('gifts');
        
        // Purchased gifts
        $stats['purchased_gifts'] = $this->db->where('status', 'purchased')->count_all_results('gifts');
        
        // Recent users (last 7 days)
        $stats['recent_users'] = $this->db->where('created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))->count_all_results('users');
        
        return $stats;
    }

    /**
 * Search users by name, email, or username
 * @param string $search Search term
 * @return array Array of matching users
 */
public function search_users($search) {
    if (empty($search)) {
        return [];
    }
    
    $this->db->select('id, username, email, first_name, last_name, created_at, is_active');
    $this->db->group_start();
    $this->db->like('username', $search);
    $this->db->or_like('email', $search);
    $this->db->or_like('first_name', $search);
    $this->db->or_like('last_name', $search);
    $this->db->or_like('CONCAT(first_name, " ", last_name)', $search);
    $this->db->group_end();
    $this->db->order_by('created_at', 'DESC');
    
    $query = $this->db->get('users');
    return $query->result_array();
}

    /**
     * Get users for DataTables with server-side processing
     * 
     * @param int $start Starting row (for pagination)
     * @param int $length Number of records to return
     * @param string $search Search term
     * @param string $order_by Column to order by
     * @param string $order_dir Order direction (ASC/DESC)
     * @return array Users data
     */
    public function get_users_datatable($start, $length, $search = '', $order_by = 'created_at', $order_dir = 'DESC') {
        $this->db->select('*');
        $this->db->from('users');
        
        // Apply search
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('username', $search);
            $this->db->or_like('phone', $search);
            $this->db->group_end();
        }
        
        // Apply ordering
        $this->db->order_by($order_by, $order_dir);
        
        // Apply pagination
        $this->db->limit($length, $start);
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Count all users
     * 
     * @return int Total number of users
     */
    public function count_all_users() {
        return $this->db->count_all('users');
    }
    
    /**
     * Count filtered users based on search term
     * 
     * @param string $search Search term
     * @return int Number of filtered users
     */
    public function count_filtered_users($search = '') {
        $this->db->from('users');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('username', $search);
            $this->db->or_like('phone', $search);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }
} 