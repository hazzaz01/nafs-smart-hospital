<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_eye_surgeries extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('eye_surgeries')) return;

        $this->db->query("CREATE TABLE `eye_surgeries` (
            `id` int NOT NULL AUTO_INCREMENT,
            `surgery_number` varchar(30) NOT NULL,
            `patient_id` int NOT NULL,
            `surgeon_id` int NOT NULL,
            `created_by` int NOT NULL,
            `surgery_type` varchar(30) NOT NULL,
            `eye` varchar(5) NOT NULL,
            `procedure_name` varchar(150) NOT NULL,
            `surgery_date` datetime NOT NULL,
            `anesthesia_type` varchar(30) NOT NULL,
            `operating_room` varchar(50) NULL,
            `pre_op_notes` text NULL,
            `status` varchar(20) NOT NULL DEFAULT 'scheduled',
            `cataract_technique` varchar(30) NULL,
            `iol_model` varchar(100) NULL,
            `iol_power` decimal(5,2) NULL,
            `target_refraction` decimal(5,2) NULL,
            `refractive_procedure` varchar(30) NULL,
            `optical_zone` decimal(4,2) NULL,
            `ablation_zone` decimal(4,2) NULL,
            `target_sphere` decimal(5,2) NULL,
            `target_cylinder` decimal(5,2) NULL,
            `target_axis` int NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `eye_surgery_number_uq` (`surgery_number`),
            KEY `eye_surgery_patient_date_idx` (`patient_id`,`surgery_date`),
            KEY `eye_surgery_surgeon_date_idx` (`surgeon_id`,`surgery_date`),
            CONSTRAINT `eye_surgery_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
            CONSTRAINT `eye_surgery_surgeon_fk` FOREIGN KEY (`surgeon_id`) REFERENCES `staff` (`id`) ON DELETE RESTRICT,
            CONSTRAINT `eye_surgery_creator_fk` FOREIGN KEY (`created_by`) REFERENCES `staff` (`id`) ON DELETE RESTRICT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3");
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS `eye_surgeries`');
    }
}
