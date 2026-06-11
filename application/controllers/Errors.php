<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Error Pages Controller
 */
class Errors extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Auth_lib');
    }

    /**
     * 403 Forbidden
     */
    public function forbidden()
    {
        $this->output->set_status_header(403);
        $data = [
            'title' => '403 — Akses Ditolak',
            'toast' => $this->auth_lib->get_toast(),
        ];
        if ($this->auth_lib->is_logged_in()) {
            $data['page'] = 'errors/403';
            $this->load->view('layouts/app', $data);
        } else {
            $this->load->view('errors/403_standalone', $data);
        }
    }

    /**
     * 404 Not Found
     */
    public function page_404()
    {
        $this->output->set_status_header(404);
        $data = ['title' => '404 — Halaman Tidak Ditemukan'];
        $this->load->view('errors/404', $data);
    }
}
