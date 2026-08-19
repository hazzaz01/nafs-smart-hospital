<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Drscreening_model extends CI_Model
{
    private function selectFields()
    {
        return 'dr_screenings.*, patients.patient_name, patients.id AS patient_no, patients.gender, patients.age, patients.mobileno, staff.name AS doctor_name, staff.surname AS doctor_surname';
    }

    private function applyScope($doctor_id)
    {
        if ($doctor_id !== null) {
            $this->db->where('dr_screenings.doctor_id', (int) $doctor_id);
        }
    }

    public function getLatest($doctor_id, $search = '', $level = '', $due_only = false)
    {
        $this->db->select($this->selectFields())->from('dr_screenings');
        $this->db->join('patients', 'patients.id = dr_screenings.patient_id');
        $this->db->join('staff', 'staff.id = dr_screenings.doctor_id');
        $this->db->where('NOT EXISTS (SELECT 1 FROM dr_screenings newer WHERE newer.patient_id = dr_screenings.patient_id AND newer.doctor_id = dr_screenings.doctor_id AND (newer.screening_date > dr_screenings.screening_date OR (newer.screening_date = dr_screenings.screening_date AND newer.id > dr_screenings.id)))', null, false);
        $this->applyScope($doctor_id);
        if ($search !== '') {
            $this->db->group_start()->like('patients.patient_name', $search)->or_like('patients.id', $search)->group_end();
        }
        if ($level !== '') {
            $this->db->group_start()->where('dr_screenings.od_dr_level', $level)->or_where('dr_screenings.os_dr_level', $level)->group_end();
        }
        if ($due_only) {
            $this->db->where('dr_screenings.next_screening <=', date('Y-m-d'));
        }
        return $this->db->order_by('dr_screenings.screening_date', 'DESC')->get()->result_array();
    }

    public function getById($id, $doctor_id = null)
    {
        $this->db->select($this->selectFields())->from('dr_screenings');
        $this->db->join('patients', 'patients.id = dr_screenings.patient_id');
        $this->db->join('staff', 'staff.id = dr_screenings.doctor_id');
        $this->db->where('dr_screenings.id', (int) $id);
        $this->applyScope($doctor_id);
        return $this->db->get()->row_array();
    }

    public function getPatientHistory($patient_id, $doctor_id = null)
    {
        $this->db->select($this->selectFields())->from('dr_screenings');
        $this->db->join('patients', 'patients.id = dr_screenings.patient_id');
        $this->db->join('staff', 'staff.id = dr_screenings.doctor_id');
        $this->db->where('dr_screenings.patient_id', (int) $patient_id);
        $this->applyScope($doctor_id);
        return $this->db->order_by('dr_screenings.screening_date', 'DESC')->get()->result_array();
    }

    public function getPatients()
    {
        return $this->db->select('id, patient_name, gender, age, mobileno')->from('patients')
            ->where('is_active', 'yes')->where('is_dead', 'no')
            ->where('patient_name IS NOT NULL', null, false)->order_by('patient_name')->get()->result_array();
    }

    public function getStats($doctor_id)
    {
        $records = $this->getLatest($doctor_id);
        $stats = array('total' => count($records), 'no_dr' => 0, 'mild' => 0, 'moderate' => 0, 'severe' => 0, 'pdr' => 0, 'due' => 0);
        $order = array('no_dr' => 0, 'mild_npdr' => 1, 'moderate_npdr' => 2, 'severe_npdr' => 3, 'pdr' => 4);
        $map = array('no_dr' => 'no_dr', 'mild_npdr' => 'mild', 'moderate_npdr' => 'moderate', 'severe_npdr' => 'severe', 'pdr' => 'pdr');
        foreach ($records as $record) {
            $od = isset($order[$record['od_dr_level']]) ? $order[$record['od_dr_level']] : 0;
            $os = isset($order[$record['os_dr_level']]) ? $order[$record['os_dr_level']] : 0;
            $worst = $od >= $os ? $record['od_dr_level'] : $record['os_dr_level'];
            $stats[$map[$worst]]++;
            if ($record['next_screening'] && $record['next_screening'] <= date('Y-m-d')) {
                $stats['due']++;
            }
        }
        return $stats;
    }

    public function save($data)
    {
        $this->db->insert('dr_screenings', $data);
        return (int) $this->db->insert_id();
    }

    public function delete($id, $doctor_id = null)
    {
        $this->db->where('id', (int) $id);
        if ($doctor_id !== null) {
            $this->db->where('doctor_id', (int) $doctor_id);
        }
        return $this->db->delete('dr_screenings');
    }
}
