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
     * Log whoever is opening the website
     * 
     * @param string $username The username that is opening the website
     * @return array|false User data array or false on failure
     */
    public function log_website_opening($username) {
        $this->db->insert('main_logs', ['username' => $username, 'action' => 'opening_website']);
    }
    
} 