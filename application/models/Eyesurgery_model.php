<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Eyesurgery_model extends CI_Model
{
    private function scope($doctor_id)
    {
        if ($doctor_id !== null) $this->db->where('eye_surgeries.created_by', (int) $doctor_id);
    }

    public function getAll($doctor_id, $filters = array())
    {
        $this->db->select('eye_surgeries.*, patients.patient_name, patients.id AS patient_no, staff.name AS surgeon_name, staff.surname AS surgeon_surname')
            ->from('eye_surgeries')->join('patients', 'patients.id = eye_surgeries.patient_id')
            ->join('staff', 'staff.id = eye_surgeries.surgeon_id');
        $this->scope($doctor_id);
        if (!empty($filters['search'])) {
            $this->db->group_start()->like('patients.patient_name', $filters['search'])
                ->or_like('eye_surgeries.surgery_type', $filters['search'])
                ->or_like('eye_surgeries.surgery_number', $filters['search'])->group_end();
        }
        if (!empty($filters['type'])) $this->db->where('eye_surgeries.surgery_type', $filters['type']);
        if (!empty($filters['status'])) $this->db->where('eye_surgeries.status', $filters['status']);
        if (!empty($filters['date'])) $this->db->where('DATE(eye_surgeries.surgery_date) =', $filters['date']);
        return $this->db->order_by('eye_surgeries.surgery_date', 'DESC')->get()->result_array();
    }

    public function getById($id, $doctor_id = null)
    {
        $this->db->select('eye_surgeries.*, patients.patient_name, patients.id AS patient_no, patients.gender, patients.age, patients.mobileno, staff.name AS surgeon_name, staff.surname AS surgeon_surname')
            ->from('eye_surgeries')->join('patients', 'patients.id = eye_surgeries.patient_id')
            ->join('staff', 'staff.id = eye_surgeries.surgeon_id')->where('eye_surgeries.id', (int) $id);
        $this->scope($doctor_id);
        return $this->db->get()->row_array();
    }

    public function getStats($doctor_id)
    {
        $rows = $this->getAll($doctor_id);
        $stats = array('today' => 0, 'scheduled' => 0, 'completed' => 0, 'total' => count($rows));
        foreach ($rows as $row) {
            if (substr($row['surgery_date'], 0, 10) === date('Y-m-d')) $stats['today']++;
            if (isset($stats[$row['status']])) $stats[$row['status']]++;
        }
        return $stats;
    }

    public function getPatients()
    {
        return $this->db->select('id, patient_name, gender, age, mobileno')->from('patients')
            ->where('is_active', 'yes')->where('is_dead', 'no')->where('patient_name IS NOT NULL', null, false)
            ->order_by('patient_name')->get()->result_array();
    }

    public function getDoctors()
    {
        return $this->db->select('staff.id, staff.name, staff.surname')->from('staff')
            ->join('staff_roles', 'staff_roles.staff_id = staff.id')->join('roles', 'roles.id = staff_roles.role_id')
            ->where('staff.is_active', 1)->where('roles.name', 'Doctor')->order_by('staff.name')->get()->result_array();
    }

    public function save($data)
    {
        $this->db->trans_start();
        $this->db->insert('eye_surgeries', $data);
        $id = (int) $this->db->insert_id();
        $number = 'SUR-' . date('Y') . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
        $this->db->where('id', $id)->update('eye_surgeries', array('surgery_number' => $number));
        $this->db->trans_complete();
        return $id;
    }

    public function delete($id, $doctor_id = null)
    {
        $this->db->where('id', (int) $id);
        if ($doctor_id !== null) $this->db->where('created_by', (int) $doctor_id);
        return $this->db->delete('eye_surgeries');
    }
}
