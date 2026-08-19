<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Glaucoma_model extends CI_Model
{
    private function recordSelect()
    {
        return "glaucoma_records.*, patients.patient_name, patients.id AS patient_no,
            patients.gender, patients.age, patients.mobileno,
            staff.name AS doctor_name, staff.surname AS doctor_surname,
            (SELECT r.iop_od FROM glaucoma_iop_readings r WHERE r.glaucoma_record_id = glaucoma_records.id ORDER BY r.measured_at DESC, r.id DESC LIMIT 1) AS latest_iop_od,
            (SELECT r.iop_os FROM glaucoma_iop_readings r WHERE r.glaucoma_record_id = glaucoma_records.id ORDER BY r.measured_at DESC, r.id DESC LIMIT 1) AS latest_iop_os,
            (SELECT r.measured_at FROM glaucoma_iop_readings r WHERE r.glaucoma_record_id = glaucoma_records.id ORDER BY r.measured_at DESC, r.id DESC LIMIT 1) AS latest_iop_at";
    }

    private function applyScope($doctor_id)
    {
        if ($doctor_id !== null) {
            $this->db->where('glaucoma_records.doctor_id', (int) $doctor_id);
        }
    }

    public function getAll($doctor_id, $search = '', $type = '', $status = '', $iop_control = '')
    {
        $this->db->select($this->recordSelect(), false)->from('glaucoma_records');
        $this->db->join('patients', 'patients.id = glaucoma_records.patient_id');
        $this->db->join('staff', 'staff.id = glaucoma_records.doctor_id');
        $this->applyScope($doctor_id);
        if ($search !== '') {
            $this->db->group_start()->like('patients.patient_name', $search)
                ->or_like('patients.id', $search)->group_end();
        }
        if ($type !== '') {
            $this->db->where('glaucoma_records.glaucoma_type', $type);
        }
        if ($status !== '') {
            $this->db->where('glaucoma_records.progression_status', $status);
        }
        if ($iop_control === 'controlled') {
            $this->db->having('(latest_iop_od IS NOT NULL OR latest_iop_os IS NOT NULL) AND (latest_iop_od IS NULL OR latest_iop_od <= target_iop_od) AND (latest_iop_os IS NULL OR latest_iop_os <= target_iop_os)', null, false);
        } elseif ($iop_control === 'uncontrolled') {
            $this->db->having('(latest_iop_od > target_iop_od OR latest_iop_os > target_iop_os)', null, false);
        }
        return $this->db->order_by('glaucoma_records.updated_at', 'DESC')->get()->result_array();
    }

    public function getById($id, $doctor_id = null)
    {
        $this->db->select($this->recordSelect(), false)->from('glaucoma_records');
        $this->db->join('patients', 'patients.id = glaucoma_records.patient_id');
        $this->db->join('staff', 'staff.id = glaucoma_records.doctor_id');
        $this->db->where('glaucoma_records.id', (int) $id);
        $this->applyScope($doctor_id);
        return $this->db->get()->row_array();
    }

    public function getPatients()
    {
        return $this->db->select('id, patient_name, gender, age, mobileno')->from('patients')
            ->where('is_active', 'yes')->where('is_dead', 'no')
            ->where('patient_name IS NOT NULL', null, false)->order_by('patient_name', 'ASC')->get()->result_array();
    }

    public function save($data, $reading, $id = null)
    {
        $this->db->trans_start();
        if ($id) {
            $this->db->where('id', (int) $id)->update('glaucoma_records', $data);
            $record_id = (int) $id;
        } else {
            $this->db->insert('glaucoma_records', $data);
            $record_id = (int) $this->db->insert_id();
        }
        if ($reading && ($reading['iop_od'] !== null || $reading['iop_os'] !== null)) {
            $reading['glaucoma_record_id'] = $record_id;
            $this->db->insert('glaucoma_iop_readings', $reading);
        }
        $this->db->trans_complete();
        return $record_id;
    }

    public function addIop($reading)
    {
        $this->db->insert('glaucoma_iop_readings', $reading);
        return (int) $this->db->insert_id();
    }

    public function getIopHistory($record_id)
    {
        return $this->db->select('glaucoma_iop_readings.*, staff.name AS doctor_name, staff.surname AS doctor_surname')
            ->from('glaucoma_iop_readings')->join('staff', 'staff.id = glaucoma_iop_readings.doctor_id')
            ->where('glaucoma_record_id', (int) $record_id)->order_by('measured_at', 'DESC')->get()->result_array();
    }

    public function getStats($doctor_id)
    {
        $records = $this->getAll($doctor_id);
        $stats = array('total' => count($records), 'controlled' => 0, 'uncontrolled' => 0, 'high_iop' => 0);
        foreach ($records as $record) {
            $has = $record['latest_iop_od'] !== null || $record['latest_iop_os'] !== null;
            $uncontrolled = ($record['latest_iop_od'] !== null && $record['target_iop_od'] !== null && $record['latest_iop_od'] > $record['target_iop_od'])
                || ($record['latest_iop_os'] !== null && $record['target_iop_os'] !== null && $record['latest_iop_os'] > $record['target_iop_os']);
            if ($has && $uncontrolled) {
                $stats['uncontrolled']++;
            } elseif ($has) {
                $stats['controlled']++;
            }
            if (($record['latest_iop_od'] !== null && $record['latest_iop_od'] > 21) || ($record['latest_iop_os'] !== null && $record['latest_iop_os'] > 21)) {
                $stats['high_iop']++;
            }
        }
        return $stats;
    }

    public function delete($id, $doctor_id = null)
    {
        $this->db->where('id', (int) $id);
        if ($doctor_id !== null) {
            $this->db->where('doctor_id', (int) $doctor_id);
        }
        return $this->db->delete('glaucoma_records');
    }
}
