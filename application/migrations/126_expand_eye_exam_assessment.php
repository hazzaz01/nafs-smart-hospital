<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Expand_eye_exam_assessment extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('eye_examinations')) {
            return;
        }

        $fields = array(
            'diagnoses_json' => "LONGTEXT NULL AFTER `diagnosis`",
            'medications_json' => "LONGTEXT NULL AFTER `plan`",
            'follow_up_recommended' => "TINYINT(1) NOT NULL DEFAULT 1 AFTER `medications_json`",
            'follow_up_interval' => "VARCHAR(30) NULL AFTER `follow_up_recommended`",
            'follow_up_reason' => "VARCHAR(255) NULL AFTER `follow_up_interval`",
        );

        foreach ($fields as $name => $definition) {
            if (!$this->db->field_exists($name, 'eye_examinations')) {
                $this->db->query("ALTER TABLE `eye_examinations` ADD `{$name}` {$definition}");
            }
        }
    }

    public function down()
    {
        foreach (array('follow_up_reason', 'follow_up_interval', 'follow_up_recommended', 'medications_json', 'diagnoses_json') as $field) {
            if ($this->db->field_exists($field, 'eye_examinations')) {
                $this->db->query("ALTER TABLE `eye_examinations` DROP COLUMN `{$field}`");
            }
        }
    }
}
