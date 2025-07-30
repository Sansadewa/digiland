<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Log Model
 * 
 * Handles all database operations for the wedding registry gift log system.
 * This model manages gift logs.
 * 
 * @property CI_DB_query_builder $db
 */
class Log_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Log whoever is getting gift detail
     * 
     * @param string $username The username that is getting gift detail
     * @param string $gift_id The gift id that is getting detail
     * @return array|false User data array or false on failure
     */
    public function log_gift_detail($username, $gift_id) {
        $this->db->insert('gift_logs', ['username' => $username, 'gift_id' => $gift_id, 'action' => 'get_detail']);
    }

    /**
     * Log whoever is booking gift
     * 
     * @param string $username The username that is booking gift
     * @param string $gift_id The gift id that is booking
     * @return array|false User data array or false on failure
     */
    public function log_gift_booking($username, $gift_id) {
        $this->db->insert('gift_logs', ['username' => $username, 'gift_id' => $gift_id, 'action' => 'booking']);
    }

    /**
     * Log whoever is opening the website
     * 
     * @param string $username The username that is opening the website
     * @return array|false User data array or false on failure
     */
    public function log_website_opening($username) {
        $this->db->insert('gift_logs', ['username' => $username, 'action' => 'opening_website']);
    }

    /**
     * Log whoever is purchasing gift
     * 
     * @param string $username The username that is purchasing gift
     * @param string $gift_id The gift id that is purchasing
     * @return array|false User data array or false on failure
     */
    public function log_gift_purchasing($username, $gift_id) {
        $this->db->insert('gift_logs', ['username' => $username, 'gift_id' => $gift_id, 'action' => 'purchased']);
    }

    /**
     * Get log for admin dashboard
     * 
     * @return array Log data
     */
    public function get_log($action = null) {
        $logs = [];
        
        switch ($action) {
            case 'booking':
                $logs = $this->db->where('action', 'booking')->get('gift_logs')->result_array();
                break;
            case 'purchased':
                $logs = $this->db->where('action', 'purchased')->get('gift_logs')->result_array();
                break;
            case 'opening_website':
                $logs = $this->db->where('action', 'opening_website')->get('gift_logs')->result_array();
                break;
            default:
                $logs = $this->db->get('gift_logs')->result_array();
                break;
        }
        
        return $logs;
    }

    /**
     * Get activity for admin dashboard
     * 
     * @return array User activity data
     */
    public function get_activity($username = null) {
        $logs = $this->db->where('username', $username)->get('gift_logs')->result_array();
        return $logs;
    }
} 