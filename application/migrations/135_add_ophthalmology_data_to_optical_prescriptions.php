<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_ophthalmology_data_to_optical_prescriptions extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('optical_prescriptions') && !in_array('ophthalmology_data', $this->db->list_fields('optical_prescriptions'), true)) {
            $this->db->query("ALTER TABLE `optical_prescriptions` ADD `ophthalmology_data` text NULL AFTER `notes`");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('optical_prescriptions') && in_array('ophthalmology_data', $this->db->list_fields('optical_prescriptions'), true)) {
            $this->db->query('ALTER TABLE `optical_prescriptions` DROP COLUMN `ophthalmology_data`');
        }
    }
}
