<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_optical_prescriptions extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('optical_prescriptions')) {
            return;
        }

        $this->db->query("CREATE TABLE `optical_prescriptions` (
            `id` int NOT NULL AUTO_INCREMENT,
            `prescription_basic_id` int NOT NULL,
            `patient_id` int NULL,
            `visit_details_id` int NULL,
            `ipd_id` int NULL,
            `doctor_id` int NULL,
            `prescription_type` varchar(20) NOT NULL DEFAULT 'spectacles',
            `od_sphere` varchar(16) NOT NULL DEFAULT '+0.00',
            `od_cylinder` varchar(16) NOT NULL DEFAULT '0.00',
            `od_axis` varchar(16) NOT NULL DEFAULT '180',
            `od_add` varchar(16) NOT NULL DEFAULT '+0.00',
            `od_pd` varchar(16) NOT NULL DEFAULT '32',
            `od_prism` varchar(16) NOT NULL DEFAULT '0',
            `os_sphere` varchar(16) NOT NULL DEFAULT '+0.00',
            `os_cylinder` varchar(16) NOT NULL DEFAULT '0.00',
            `os_axis` varchar(16) NOT NULL DEFAULT '180',
            `os_add` varchar(16) NOT NULL DEFAULT '+0.00',
            `os_pd` varchar(16) NOT NULL DEFAULT '32',
            `os_prism` varchar(16) NOT NULL DEFAULT '0',
            `lens_type` varchar(64) NULL,
            `lens_material` varchar(64) NULL,
            `frame_type` varchar(64) NULL,
            `coatings` text NULL,
            `validity_months` smallint NOT NULL DEFAULT 12,
            `notes` text NULL,
            `ophthalmology_data` text NULL,
            `created_at` datetime NOT NULL,
            `updated_at` datetime NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `optical_prescription_basic_unique` (`prescription_basic_id`),
            KEY `optical_patient_idx` (`patient_id`),
            KEY `optical_visit_idx` (`visit_details_id`),
            KEY `optical_ipd_idx` (`ipd_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3");
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS `optical_prescriptions`');
    }
}
