<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Eyeexam_model extends CI_Model
{
    public function getAll($doctor_id, $search = '')
    {
        $this->db->select('eye_examinations.*, patients.patient_name, patients.id as patient_no, staff.name as doctor_name, staff.surname as doctor_surname');
        $this->db->from('eye_examinations');
        $this->db->join('patients', 'patients.id = eye_examinations.patient_id');
        $this->db->join('staff', 'staff.id = eye_examinations.doctor_id');
        if ($doctor_id !== null) {
            $this->db->where('eye_examinations.doctor_id', $doctor_id);
        }
        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('patients.patient_name', $search);
            $this->db->or_like('patients.id', $search);
            $this->db->or_like('eye_examinations.chief_complaint', $search);
            $this->db->group_end();
        }
        return $this->db->order_by('eye_examinations.exam_date', 'DESC')->get()->result_array();
    }

    public function getById($id, $doctor_id = null)
    {
        $this->db->select('eye_examinations.*, patients.patient_name, patients.gender, patients.age, patients.mobileno, staff.name as doctor_name, staff.surname as doctor_surname, staff.employee_id as doctor_employee_id');
        $this->db->from('eye_examinations');
        $this->db->join('patients', 'patients.id = eye_examinations.patient_id');
        $this->db->join('staff', 'staff.id = eye_examinations.doctor_id');
        $this->db->where('eye_examinations.id', (int) $id);
        if ($doctor_id !== null) {
            $this->db->where('eye_examinations.doctor_id', $doctor_id);
        }
        return $this->db->get()->row_array();
    }

    public function getPatients()
    {
        return $this->db->select('id, patient_name, gender, age, mobileno')
            ->from('patients')->where('is_active', 'yes')->where('is_dead', 'no')
            ->where('patient_name IS NOT NULL', null, false)->order_by('patient_name', 'ASC')->get()->result_array();
    }

    public function getStats($doctor_id)
    {
        $where = $doctor_id === null ? '' : ' WHERE doctor_id = ' . $this->db->escape($doctor_id);
        $sql = "SELECT
            SUM(DATE(exam_date) = CURDATE()) AS today_count,
            SUM(YEARWEEK(exam_date, 1) = YEARWEEK(CURDATE(), 1)) AS week_count,
            SUM((iop_od > 21) OR (iop_os > 21)) AS high_iop_count,
            COUNT(*) AS total_count
            FROM eye_examinations" . $where;
        $row = $this->db->query($sql)->row_array();
        return array_map('intval', $row ?: array('today_count' => 0, 'week_count' => 0, 'high_iop_count' => 0, 'total_count' => 0));
    }

    public function save($data, $id = null)
    {
        if ($id) {
            $this->db->where('id', (int) $id)->update('eye_examinations', $data);
            return (int) $id;
        }
        $this->db->insert('eye_examinations', $data);
        return (int) $this->db->insert_id();
    }

    public function delete($id, $doctor_id = null)
    {
        $this->db->where('id', (int) $id);
        if ($doctor_id !== null) {
            $this->db->where('doctor_id', $doctor_id);
        }
        return $this->db->delete('eye_examinations');
    }
}
