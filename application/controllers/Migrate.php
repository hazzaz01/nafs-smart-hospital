<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Migrate extends CI_Controller
{

    public function index()
    {
        // The bundled database dump contains an empty migrations table. CodeIgniter
        // updates the existing version row but does not create one when the table
        // already exists, so seed version 0 before applying project migrations.
        $this->load->database();
        if ($this->db->table_exists('migrations') && $this->db->count_all('migrations') === 0) {
            $this->db->insert('migrations', array('version' => 0));
        }

        $this->load->library('migration');

        if ($this->migration->current() === false) {
            show_error($this->migration->error_string());
        } else {
            echo "Database updated successfully.";
        }
    }

}
