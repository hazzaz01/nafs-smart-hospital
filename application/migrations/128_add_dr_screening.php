<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_dr_screening extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('dr_screenings')) {
            return;
        }

        $this->db->query("CREATE TABLE `dr_screenings` (
            `id` int NOT NULL AUTO_INCREMENT,
            `patient_id` int NOT NULL,
            `doctor_id` int NOT NULL,
            `screening_date` datetime NOT NULL,
            `diabetes_type` varchar(20) NOT NULL DEFAULT 'type2',
            `diabetes_duration` int NOT NULL DEFAULT 0,
            `hba1c` decimal(4,1) NULL,
            `last_hba1c_date` date NULL,
            `bp_systolic` int NULL,
            `bp_diastolic` int NULL,
            `total_cholesterol` int NULL,
            `ldl` int NULL,
            `hdl` int NULL,
            `triglycerides` int NULL,
            `od_dr_level` varchar(30) NOT NULL DEFAULT 'no_dr',
            `od_dme_status` varchar(30) NOT NULL DEFAULT 'no_dme',
            `od_findings_json` longtext NULL,
            `os_dr_level` varchar(30) NOT NULL DEFAULT 'no_dr',
            `os_dme_status` varchar(30) NOT NULL DEFAULT 'no_dme',
            `os_findings_json` longtext NULL,
            `fundus_photo` tinyint(1) NOT NULL DEFAULT 0,
            `oct` tinyint(1) NOT NULL DEFAULT 0,
            `ffa` tinyint(1) NOT NULL DEFAULT 0,
            `next_screening` date NULL,
            `follow_up_frequency` varchar(30) NULL,
            `follow_up_reason` varchar(255) NULL,
            `notes` text NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `dr_screening_patient_date_idx` (`patient_id`,`screening_date`),
            KEY `dr_screening_doctor_date_idx` (`doctor_id`,`screening_date`),
            CONSTRAINT `dr_screening_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
            CONSTRAINT `dr_screening_doctor_fk` FOREIGN KEY (`doctor_id`) REFERENCES `staff` (`id`) ON DELETE RESTRICT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3");
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS `dr_screenings`');
    }
}
