<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Drscreening extends Admin_Controller
{
    private $role_name = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'customlib'));
        $this->load->model('drscreening_model');
        $role = json_decode($this->customlib->getStaffRole(), true);
        $this->role_name = isset($role['name']) ? strtoupper($role['name']) : '';
        if (!in_array($this->role_name, array('DOCTOR', 'SUPER ADMIN'), true)) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'DR_Screening');
    }

    private function doctorScope()
    {
        return $this->role_name === 'DOCTOR' ? (int) $this->customlib->getStaffID() : null;
    }

    public function index()
    {
        $data['search'] = trim((string) $this->input->get('search', true));
        $data['level'] = trim((string) $this->input->get('level', true));
        $data['due_only'] = (bool) $this->input->get('due');
        $data['records'] = $this->drscreening_model->getLatest($this->doctorScope(), $data['search'], $data['level'], $data['due_only']);
        $data['stats'] = $this->drscreening_model->getStats($this->doctorScope());
        $this->render('admin/drscreening/index', $data);
    }

    public function create()
    {
        $this->form_validation->set_rules('patient_id', 'Patient', 'trim|required|integer');
        $this->form_validation->set_rules('diabetes_type', 'Diabetes Type', 'trim|required|in_list[type1,type2,gestational,other]');
        $this->form_validation->set_rules('diabetes_duration', 'Duration of Diabetes', 'trim|required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('hba1c', 'HbA1c', 'trim|numeric');
        foreach (array('bp_systolic', 'bp_diastolic', 'total_cholesterol', 'ldl', 'hdl', 'triglycerides') as $field) {
            $this->form_validation->set_rules($field, ucwords(str_replace('_', ' ', $field)), 'trim|integer|greater_than_equal_to[0]');
        }

        if ($this->form_validation->run()) {
            $data = $this->postedScreening();
            $id = $this->drscreening_model->save($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">DR screening record saved successfully.</div>');
            redirect('admin/drscreening/view/' . $id);
        }

        $data['patients'] = $this->drscreening_model->getPatients();
        $this->render('admin/drscreening/form', $data);
    }

    private function postedScreening()
    {
        $fields = array('patient_id', 'diabetes_type', 'diabetes_duration', 'hba1c', 'last_hba1c_date',
            'bp_systolic', 'bp_diastolic', 'total_cholesterol', 'ldl', 'hdl', 'triglycerides',
            'od_dr_level', 'od_dme_status', 'os_dr_level', 'os_dme_status', 'notes');
        $data = array();
        foreach ($fields as $field) {
            $value = $this->input->post($field, true);
            $data[$field] = $value === '' ? null : $value;
        }
        $data['patient_id'] = (int) $data['patient_id'];
        $data['doctor_id'] = (int) $this->customlib->getStaffID();
        $data['screening_date'] = date('Y-m-d H:i:s');
        $data['od_findings_json'] = $this->cleanFindings($this->input->post('od_findings', true));
        $data['os_findings_json'] = $this->cleanFindings($this->input->post('os_findings', true));
        foreach (array('fundus_photo', 'oct', 'ffa') as $field) {
            $data[$field] = $this->input->post($field) ? 1 : 0;
        }
        $follow_up = $this->followUpFor($data['od_dr_level'], $data['os_dr_level'], $data['od_dme_status'], $data['os_dme_status']);
        $data['next_screening'] = date('Y-m-d', strtotime('+' . $follow_up['months'] . ' months'));
        $data['follow_up_frequency'] = $follow_up['frequency'];
        $data['follow_up_reason'] = $follow_up['reason'];
        return $data;
    }

    private function cleanFindings($findings)
    {
        $allowed = array('microaneurysms', 'hemorrhages', 'hard_exudates', 'cotton_wool_spots', 'venous_beading', 'irma', 'neovascularization', 'vitreous_hemorrhage', 'tractional_rd', 'macular_edema', 'laser_scars');
        $clean = is_array($findings) ? array_values(array_intersect($findings, $allowed)) : array();
        return $clean ? json_encode($clean) : null;
    }

    private function followUpFor($od, $os, $od_dme, $os_dme)
    {
        $order = array('no_dr' => 0, 'mild_npdr' => 1, 'moderate_npdr' => 2, 'severe_npdr' => 3, 'pdr' => 4);
        $worst = max(isset($order[$od]) ? $order[$od] : 0, isset($order[$os]) ? $order[$os] : 0);
        if ($od_dme === 'ci_dme' || $os_dme === 'ci_dme' || $worst >= 4) {
            return array('months' => 1, 'frequency' => '1 month', 'reason' => 'Urgent retinal evaluation');
        }
        if ($worst === 3) return array('months' => 3, 'frequency' => '3 months', 'reason' => 'Severe NPDR monitoring');
        if ($worst === 2) return array('months' => 6, 'frequency' => '6 months', 'reason' => 'Moderate NPDR monitoring');
        if ($worst === 1) return array('months' => 9, 'frequency' => '9 months', 'reason' => 'Mild NPDR monitoring');
        return array('months' => 12, 'frequency' => 'Annual', 'reason' => 'Routine screening');
    }

    public function view($id)
    {
        $data['record'] = $this->drscreening_model->getById($id, $this->doctorScope());
        if (!$data['record']) show_404();
        $data['history'] = $this->drscreening_model->getPatientHistory($data['record']['patient_id'], $this->doctorScope());
        $this->render('admin/drscreening/view', $data);
    }

    public function ai_analysis()
    {
        $this->session->set_userdata('sub_sidebar_menu', 'admin/drscreening/ai_analysis');
        $this->render('admin/drscreening/ai_analysis', array());
    }

    public function delete($id)
    {
        if (strtoupper($this->input->method()) !== 'POST') show_error('Method not allowed', 405);
        if (!$this->drscreening_model->getById($id, $this->doctorScope())) show_404();
        $this->drscreening_model->delete($id, $this->doctorScope());
        $this->session->set_flashdata('msg', '<div class="alert alert-success">DR screening record deleted.</div>');
        redirect('admin/drscreening');
    }

    private function render($view, $data)
    {
        $this->load->view('layout/header', $data);
        $this->load->view($view, $data);
        $this->load->view('layout/footer');
    }
}
