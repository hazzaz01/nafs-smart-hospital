<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Optical_prescription_model extends MY_Model
{
    private $table = 'optical_prescriptions';

    public function getByPrescriptionId($prescription_id)
    {
        return $this->db->where('prescription_basic_id', (int) $prescription_id)
            ->get($this->table)
            ->row_array();
    }

    public function save($prescription_id, $data)
    {
        $existing = $this->getByPrescriptionId($prescription_id);
        $data['prescription_basic_id'] = (int) $prescription_id;
        $data['updated_at'] = date('Y-m-d H:i:s');

        if (!empty($existing)) {
            $this->db->where('id', $existing['id'])->update($this->table, $data);
            return $existing['id'];
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function deleteByPrescriptionId($prescription_id)
    {
        return $this->db->where('prescription_basic_id', (int) $prescription_id)->delete($this->table);
    }
}
