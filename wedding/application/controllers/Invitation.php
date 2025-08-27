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
 * @property Invitation_model $invitation_model
 */
class Invitation extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load necessary libraries and helpers
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('invitation_model');
        $this->load->model('log_model');



    }

    /**
     * Main Registry Page
     * Corresponds to the URL: gift.digiland.space/{username}
     *
     * @param string $username The username of the guest viewing the page.
     */
    public function index($username = '') {
        
        // Get or create user by username
        $user = $this->invitation_model->get_user_by_username($username);
        if (!$user || empty($username)) {
            $this->session->set_userdata('username', "Tamu");
            $this->session->set_userdata('name', "Teman dan Keluarga");
            $this->session->set_userdata('show_gift_section', 0);
        } else   {
            $this->session->set_userdata('user_id', $user['id']);
            $this->session->set_userdata('username', $username);
            $this->session->set_userdata('name', $user['name']);
            $this->session->set_userdata('show_gift_section', $user['show_gift_section']);
        }

        // Log website opening
        $this->log_model->log_website_opening($username);
        $this->load->view('main-invitation'); 
    }

    /**
     * API: Send RSVP
     * Endpoint: digiland.space/send-rsvp
     * Method: POST
     */
    public function send_rsvp() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // Validate input
        if (empty($data['username']) || empty($data['name'])) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Missing fields'. var_dump($data)]));
            return;
        }

        // Send data to model
        $saved = $this->invitation_model->insert_rsvp($data);

        if ($saved) {
            $response = ['status' => 'success', 'message' => 'RSVP saved'];
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to save'];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }


    public function get_rsvp_messages() {
        $rsvp = $this->invitation_model->get_all_rsvp();
        echo json_encode($rsvp);
    }
}