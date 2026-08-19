<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_glaucoma_center extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('glaucoma_records')) {
            $this->db->query("CREATE TABLE `glaucoma_records` (
                `id` int NOT NULL AUTO_INCREMENT,
                `patient_id` int NOT NULL,
                `doctor_id` int NOT NULL,
                `glaucoma_type` varchar(30) NOT NULL,
                `severity` varchar(30) NOT NULL,
                `diagnosis_date` date NOT NULL,
                `family_history` tinyint(1) NOT NULL DEFAULT 0,
                `risk_factors_json` longtext NULL,
                `target_iop_od` decimal(5,2) NULL,
                `target_iop_os` decimal(5,2) NULL,
                `cdr_od` decimal(3,2) NULL,
                `cdr_os` decimal(3,2) NULL,
                `rim_thinning_od` varchar(30) NULL,
                `rim_thinning_os` varchar(30) NULL,
                `disc_hemorrhage_od` tinyint(1) NOT NULL DEFAULT 0,
                `disc_hemorrhage_os` tinyint(1) NOT NULL DEFAULT 0,
                `nfl_defect_od` tinyint(1) NOT NULL DEFAULT 0,
                `nfl_defect_os` tinyint(1) NOT NULL DEFAULT 0,
                `progression_status` varchar(30) NOT NULL DEFAULT 'stable',
                `medications_json` longtext NULL,
                `next_visit` date NULL,
                `notes` text NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `glaucoma_patient_idx` (`patient_id`),
                KEY `glaucoma_doctor_idx` (`doctor_id`,`diagnosis_date`),
                CONSTRAINT `glaucoma_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
                CONSTRAINT `glaucoma_doctor_fk` FOREIGN KEY (`doctor_id`) REFERENCES `staff` (`id`) ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3");
        }

        if (!$this->db->table_exists('glaucoma_iop_readings')) {
            $this->db->query("CREATE TABLE `glaucoma_iop_readings` (
                `id` int NOT NULL AUTO_INCREMENT,
                `glaucoma_record_id` int NOT NULL,
                `doctor_id` int NOT NULL,
                `measured_at` datetime NOT NULL,
                `iop_od` decimal(5,2) NULL,
                `iop_os` decimal(5,2) NULL,
                `method` varchar(30) NOT NULL DEFAULT 'goldmann',
                `notes` varchar(255) NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `glaucoma_iop_record_date_idx` (`glaucoma_record_id`,`measured_at`),
                CONSTRAINT `glaucoma_iop_record_fk` FOREIGN KEY (`glaucoma_record_id`) REFERENCES `glaucoma_records` (`id`) ON DELETE CASCADE,
                CONSTRAINT `glaucoma_iop_doctor_fk` FOREIGN KEY (`doctor_id`) REFERENCES `staff` (`id`) ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3");
        }
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS `glaucoma_iop_readings`');
        $this->db->query('DROP TABLE IF EXISTS `glaucoma_records`');
    }
}
