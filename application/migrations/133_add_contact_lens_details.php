<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_contact_lens_details extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('optical_prescriptions')) {
            return;
        }

        $fields = $this->db->list_fields('optical_prescriptions');
        if (!in_array('contact_lens_brand', $fields, true)) {
            $this->db->query("ALTER TABLE `optical_prescriptions` ADD `contact_lens_brand` varchar(100) NULL AFTER `frame_type`");
        }
        if (!in_array('contact_lens_base_curve', $fields, true)) {
            $this->db->query("ALTER TABLE `optical_prescriptions` ADD `contact_lens_base_curve` varchar(16) NULL AFTER `contact_lens_brand`");
        }
        if (!in_array('contact_lens_diameter', $fields, true)) {
            $this->db->query("ALTER TABLE `optical_prescriptions` ADD `contact_lens_diameter` varchar(16) NULL AFTER `contact_lens_base_curve`");
        }
        if (!in_array('contact_lens_replacement', $fields, true)) {
            $this->db->query("ALTER TABLE `optical_prescriptions` ADD `contact_lens_replacement` varchar(30) NULL AFTER `contact_lens_diameter`");
        }
    }

    public function down()
    {
        if (!$this->db->table_exists('optical_prescriptions')) {
            return;
        }
        $fields = $this->db->list_fields('optical_prescriptions');
        foreach (array('contact_lens_replacement', 'contact_lens_diameter', 'contact_lens_base_curve', 'contact_lens_brand') as $field) {
            if (in_array($field, $fields, true)) {
                $this->db->query('ALTER TABLE `optical_prescriptions` DROP COLUMN `' . $field . '`');
            }
        }
    }
}
