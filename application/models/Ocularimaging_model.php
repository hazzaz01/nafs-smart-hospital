<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ocularimaging_model extends CI_Model
{
    private function scope($doctor_id)
    {
        if ($doctor_id !== null) $this->db->where('ocular_imaging.doctor_id', (int) $doctor_id);
    }

    public function getAll($modality, $doctor_id, $search = '', $subtype = '')
    {
        $this->db->select('ocular_imaging.*, patients.patient_name, patients.id AS patient_no, patients.gender, patients.age, staff.name AS doctor_name, staff.surname AS doctor_surname')
            ->from('ocular_imaging')->join('patients', 'patients.id = ocular_imaging.patient_id')
            ->join('staff', 'staff.id = ocular_imaging.doctor_id')->where('ocular_imaging.modality', $modality);
        $this->scope($doctor_id);
        if ($search !== '') $this->db->group_start()->like('patients.patient_name', $search)->or_like('patients.id', $search)->or_like('ocular_imaging.record_number', $search)->group_end();
        if ($subtype !== '') $this->db->where('ocular_imaging.subtype', $subtype);
        return $this->db->order_by('ocular_imaging.recorded_at', 'DESC')->get()->result_array();
    }

    public function getById($id, $doctor_id = null)
    {
        $this->db->select('ocular_imaging.*, patients.patient_name, patients.id AS patient_no, patients.gender, patients.age, patients.mobileno, staff.name AS doctor_name, staff.surname AS doctor_surname')
            ->from('ocular_imaging')->join('patients', 'patients.id = ocular_imaging.patient_id')
            ->join('staff', 'staff.id = ocular_imaging.doctor_id')->where('ocular_imaging.id', (int) $id);
        $this->scope($doctor_id);
        return $this->db->get()->row_array();
    }

    public function getPatients()
    {
        return $this->db->select('id, patient_name, gender, age, mobileno')->from('patients')
            ->where('is_active', 'yes')->where('is_dead', 'no')->where('patient_name IS NOT NULL', null, false)
            ->order_by('patient_name')->get()->result_array();
    }

    public function stats($modality, $doctor_id)
    {
        $rows = $this->getAll($modality, $doctor_id);
        $stats = array('total' => count($rows), 'today' => 0, 'month' => 0, 'week' => 0, 'macula' => 0, 'rnfl' => 0, 'pathology' => 0, 'normal' => 0, 'keratoconus' => 0);
        $week = date('Y-m-d', strtotime('-7 days')); $month = date('Y-m-01');
        foreach ($rows as $row) {
            $date = substr($row['recorded_at'], 0, 10);
            if ($date === date('Y-m-d')) $stats['today']++;
            if ($date >= $week) $stats['week']++;
            if ($date >= $month) $stats['month']++;
            if ($row['subtype'] === 'macula') $stats['macula']++;
            if ($row['subtype'] === 'rnfl') $stats['rnfl']++;
            if ($row['pathologies_json'] && $row['pathologies_json'] !== '[]') $stats['pathology']++;
            if ($row['classification'] === 'normal') $stats['normal']++;
            if ($row['classification'] === 'keratoconus') $stats['keratoconus']++;
        }
        return $stats;
    }

    public function save($data)
    {
        $this->db->trans_start();
        $this->db->insert('ocular_imaging', $data);
        $id = (int) $this->db->insert_id();
        $prefix = strtoupper($data['modality'] === 'topography' ? 'TOPO' : $data['modality']);
        $number = $prefix . '-' . date('Y') . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
        $this->db->where('id', $id)->update('ocular_imaging', array('record_number' => $number));
        $this->db->trans_complete();
        return $id;
    }

    public function delete($id, $doctor_id = null)
    {
        $this->db->where('id', (int) $id);
        if ($doctor_id !== null) $this->db->where('doctor_id', (int) $doctor_id);
        return $this->db->delete('ocular_imaging');
    }
}
