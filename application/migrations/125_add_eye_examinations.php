<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_eye_examinations extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('eye_examinations')) {
            return;
        }

        $this->db->query("CREATE TABLE `eye_examinations` (
            `id` int NOT NULL AUTO_INCREMENT,
            `patient_id` int NOT NULL,
            `doctor_id` int NOT NULL,
            `exam_date` datetime NOT NULL,
            `chief_complaint` varchar(255) NOT NULL,
            `history_present_illness` text NULL,
            `va_scale` varchar(20) NOT NULL DEFAULT 'Snellen',
            `ucva_distance_od` varchar(30) NULL,
            `ucva_distance_os` varchar(30) NULL,
            `ucva_near_od` varchar(30) NULL,
            `ucva_near_os` varchar(30) NULL,
            `bcva_distance_od` varchar(30) NULL,
            `bcva_distance_os` varchar(30) NULL,
            `bcva_near_od` varchar(30) NULL,
            `bcva_near_os` varchar(30) NULL,
            `pinhole_od` varchar(30) NULL,
            `pinhole_os` varchar(30) NULL,
            `refraction_od` varchar(120) NULL,
            `refraction_os` varchar(120) NULL,
            `iop_od` decimal(5,2) NULL,
            `iop_os` decimal(5,2) NULL,
            `iop_method` varchar(60) NULL,
            `anterior_segment_od` text NULL,
            `anterior_segment_os` text NULL,
            `fundus_od` text NULL,
            `fundus_os` text NULL,
            `diagnosis` text NULL,
            `plan` text NULL,
            `follow_up_date` date NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `eye_exam_patient_idx` (`patient_id`),
            KEY `eye_exam_doctor_date_idx` (`doctor_id`,`exam_date`),
            CONSTRAINT `eye_exam_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
            CONSTRAINT `eye_exam_doctor_fk` FOREIGN KEY (`doctor_id`) REFERENCES `staff` (`id`) ON DELETE RESTRICT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3");
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS `eye_examinations`');
    }
}
