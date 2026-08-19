<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_ocular_imaging extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('ocular_imaging')) return;
        $this->db->query("CREATE TABLE `ocular_imaging` (
            `id` int NOT NULL AUTO_INCREMENT,
            `record_number` varchar(30) NOT NULL,
            `modality` varchar(20) NOT NULL,
            `patient_id` int NOT NULL,
            `doctor_id` int NOT NULL,
            `eye` varchar(5) NOT NULL,
            `subtype` varchar(40) NULL,
            `device` varchar(100) NULL,
            `quality` varchar(20) NULL,
            `field_name` varchar(30) NULL,
            `classification` varchar(30) NULL,
            `dilated` tinyint(1) NOT NULL DEFAULT 0,
            `measurements_json` longtext NULL,
            `findings_json` longtext NULL,
            `pathologies_json` longtext NULL,
            `interpretation` text NULL,
            `notes` text NULL,
            `recorded_at` datetime NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `ocular_imaging_number_uq` (`record_number`),
            KEY `ocular_imaging_patient_idx` (`patient_id`,`modality`,`recorded_at`),
            KEY `ocular_imaging_doctor_idx` (`doctor_id`,`modality`,`recorded_at`),
            CONSTRAINT `ocular_imaging_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
            CONSTRAINT `ocular_imaging_doctor_fk` FOREIGN KEY (`doctor_id`) REFERENCES `staff` (`id`) ON DELETE RESTRICT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3");
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS `ocular_imaging`');
    }
}
