<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Glaucoma extends Admin_Controller
{
    private $role_name = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'customlib'));
        $this->load->model('glaucoma_model');
        $role = json_decode($this->customlib->getStaffRole(), true);
        $this->role_name = isset($role['name']) ? strtoupper($role['name']) : '';
        if (!in_array($this->role_name, array('DOCTOR', 'SUPER ADMIN'), true)) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Glaucoma_Center');
    }

    private function doctorScope()
    {
        return $this->role_name === 'DOCTOR' ? (int) $this->customlib->getStaffID() : null;
    }

    public function index()
    {
        $filters = $this->filters();
        $data = $filters;
        $data['records'] = $this->glaucoma_model->getAll($this->doctorScope(), $filters['search'], $filters['type'], $filters['status']);
        $data['stats'] = $this->glaucoma_model->getStats($this->doctorScope());
        $this->render('admin/glaucoma/index', $data);
    }

    public function iop()
    {
        $filters = $this->filters();
        $filters['iop_control'] = trim((string) $this->input->get('iop_control', true));
        $data = $filters;
        $data['records'] = $this->glaucoma_model->getAll($this->doctorScope(), $filters['search'], '', '', $filters['iop_control']);
        $data['stats'] = $this->glaucoma_model->getStats($this->doctorScope());
        $this->session->set_userdata('sub_sidebar_menu', 'admin/glaucoma/iop');
        $this->render('admin/glaucoma/iop', $data);
    }

    private function filters()
    {
        return array(
            'search' => trim((string) $this->input->get('search', true)),
            'type' => trim((string) $this->input->get('type', true)),
            'status' => trim((string) $this->input->get('status', true)),
        );
    }

    public function create()
    {
        $this->editForm();
    }

    public function edit($id)
    {
        $record = $this->glaucoma_model->getById($id, $this->doctorScope());
        if (!$record) {
            show_404();
        }
        $this->editForm($record);
    }

    private function editForm($record = array())
    {
        $this->form_validation->set_rules('patient_id', 'Patient', 'trim|required|integer');
        $this->form_validation->set_rules('glaucoma_type', 'Glaucoma type', 'trim|required|in_list[poag,pacg,ntg,secondary,congenital,suspect,oht]');
        $this->form_validation->set_rules('severity', 'Severity', 'trim|required|in_list[suspect,mild,moderate,severe,end_stage]');
        $this->form_validation->set_rules('diagnosis_date', 'Diagnosis date', 'trim|required');
        foreach (array('target_iop_od', 'target_iop_os', 'iop_od', 'iop_os') as $field) {
            $this->form_validation->set_rules($field, strtoupper(str_replace('_', ' ', $field)), 'trim|numeric');
        }
        foreach (array('cdr_od', 'cdr_os') as $field) {
            $this->form_validation->set_rules($field, strtoupper(str_replace('_', ' ', $field)), 'trim|numeric|greater_than_equal_to[0]|less_than_equal_to[1]');
        }

        if ($this->form_validation->run()) {
            $data = $this->postedRecord($record);
            $reading = $this->postedReading();
            $id = $this->glaucoma_model->save($data, $reading, isset($record['id']) ? $record['id'] : null);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Glaucoma record saved successfully.</div>');
            redirect('admin/glaucoma/view/' . $id);
        }

        $data['record'] = $record;
        $data['patients'] = $this->glaucoma_model->getPatients();
        $this->render('admin/glaucoma/form', $data);
    }

    private function postedRecord($record)
    {
        $fields = array('patient_id', 'glaucoma_type', 'severity', 'diagnosis_date', 'target_iop_od', 'target_iop_os',
            'cdr_od', 'cdr_os', 'rim_thinning_od', 'rim_thinning_os', 'progression_status', 'next_visit', 'notes');
        $data = array();
        foreach ($fields as $field) {
            $value = $this->input->post($field, true);
            $data[$field] = $value === '' ? null : $value;
        }
        $data['patient_id'] = (int) $data['patient_id'];
        $data['family_history'] = $this->input->post('family_history') ? 1 : 0;
        foreach (array('disc_hemorrhage_od', 'disc_hemorrhage_os', 'nfl_defect_od', 'nfl_defect_os') as $field) {
            $data[$field] = $this->input->post($field) ? 1 : 0;
        }
        $risk_factors = $this->input->post('risk_factors', true);
        $allowed_risks = array('elevated_iop', 'thin_cornea', 'high_myopia', 'diabetes', 'hypertension', 'migraine', 'sleep_apnea', 'steroid_use');
        $risk_factors = is_array($risk_factors) ? array_values(array_intersect($risk_factors, $allowed_risks)) : array();
        $data['risk_factors_json'] = $risk_factors ? json_encode($risk_factors) : null;
        $data['medications_json'] = $this->cleanMedications($this->input->post('medications', true));
        $data['doctor_id'] = $this->role_name === 'SUPER ADMIN' && !empty($record['doctor_id'])
            ? (int) $record['doctor_id'] : (int) $this->customlib->getStaffID();
        return $data;
    }

    private function cleanMedications($rows)
    {
        $clean = array();
        if (is_array($rows)) {
            foreach ($rows as $row) {
                if (!is_array($row) || trim(isset($row['name']) ? $row['name'] : '') === '') {
                    continue;
                }
                $clean[] = array(
                    'name' => trim($row['name']),
                    'dosage' => trim(isset($row['dosage']) ? $row['dosage'] : ''),
                    'frequency' => trim(isset($row['frequency']) ? $row['frequency'] : ''),
                    'eye' => in_array(isset($row['eye']) ? $row['eye'] : '', array('OD', 'OS', 'OU'), true) ? $row['eye'] : 'OU',
                );
            }
        }
        return $clean ? json_encode($clean) : null;
    }

    private function postedReading()
    {
        $od = $this->input->post('iop_od', true);
        $os = $this->input->post('iop_os', true);
        if ($od === '' && $os === '') {
            return null;
        }
        $measured_at = str_replace('T', ' ', (string) $this->input->post('measured_at', true));
        return array(
            'doctor_id' => (int) $this->customlib->getStaffID(),
            'measured_at' => $measured_at ? date('Y-m-d H:i:s', strtotime($measured_at)) : date('Y-m-d H:i:s'),
            'iop_od' => $od === '' ? null : $od,
            'iop_os' => $os === '' ? null : $os,
            'method' => $this->input->post('iop_method', true) ?: 'goldmann',
            'notes' => null,
        );
    }

    public function view($id)
    {
        $data['record'] = $this->glaucoma_model->getById($id, $this->doctorScope());
        if (!$data['record']) {
            show_404();
        }
        $data['history'] = $this->glaucoma_model->getIopHistory($id);
        $this->render('admin/glaucoma/view', $data);
    }

    public function add_iop($id)
    {
        if (strtoupper($this->input->method()) !== 'POST') {
            show_error('Method not allowed', 405);
        }
        $record = $this->glaucoma_model->getById($id, $this->doctorScope());
        if (!$record) {
            show_404();
        }
        $reading = $this->postedReading();
        if (!$reading) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Enter at least one IOP value.</div>');
        } else {
            $reading['glaucoma_record_id'] = (int) $id;
            $reading['notes'] = $this->input->post('iop_notes', true);
            $this->glaucoma_model->addIop($reading);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">IOP reading added.</div>');
        }
        redirect('admin/glaucoma/view/' . $id);
    }

    public function delete($id)
    {
        if (strtoupper($this->input->method()) !== 'POST') {
            show_error('Method not allowed', 405);
        }
        if (!$this->glaucoma_model->getById($id, $this->doctorScope())) {
            show_404();
        }
        $this->glaucoma_model->delete($id, $this->doctorScope());
        $this->session->set_flashdata('msg', '<div class="alert alert-success">Glaucoma record deleted.</div>');
        redirect('admin/glaucoma');
    }

    private function render($view, $data)
    {
        $this->load->view('layout/header', $data);
        $this->load->view($view, $data);
        $this->load->view('layout/footer');
    }
}
