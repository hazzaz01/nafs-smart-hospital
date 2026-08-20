<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_diagnosis_to_prescriptions extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('ipd_prescription_basic')) {
            return;
        }

        if (!in_array('diagnosis', $this->db->list_fields('ipd_prescription_basic'), true)) {
            $this->db->query("ALTER TABLE `ipd_prescription_basic` ADD `diagnosis` text NULL AFTER `finding_description`");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('ipd_prescription_basic') && in_array('diagnosis', $this->db->list_fields('ipd_prescription_basic'), true)) {
            $this->db->query('ALTER TABLE `ipd_prescription_basic` DROP COLUMN `diagnosis`');
        }
    }
}
