<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Invitation Model
 * 
 * Handles all database operations for the wedding registry gift system.
 * This model manages users, gifts, bookings, and purchases.
 * 
 * @property CI_DB_query_builder $db
 */
class Invitation_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get user by username
     * 
     * @param string $username The username to search for
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
     * Get all RSVP
     * 
     * @return array|false RSVP data array or false on failure
     */
    public function get_all_rsvp() {
        $rsvp = $this->db->get('rsvp')->result_array();
        return $rsvp;
    }

    /**
     *Insert RSVP
     * 
     * @param array $rsvp_data The RSVP data to insert
     * @return bool True on success, false on failure
     */
    public function insert_rsvp($rsvp_data) {
        return $this->db->insert('rsvp', $rsvp_data);
    }

} 