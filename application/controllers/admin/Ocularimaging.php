<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ocularimaging extends Admin_Controller
{
    private $role_name = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'customlib'));
        $this->load->model('ocularimaging_model');
        $role = json_decode($this->customlib->getStaffRole(), true);
        $this->role_name = isset($role['name']) ? strtoupper($role['name']) : '';
        if (!in_array($this->role_name, array('DOCTOR', 'SUPER ADMIN'), true)) access_denied();
        $this->session->set_userdata('top_menu', 'Ocular_Imaging');
    }

    private function doctorScope()
    {
        return $this->role_name === 'DOCTOR' ? (int) $this->customlib->getStaffID() : null;
    }

    public function index() { redirect('admin/ocularimaging/oct'); }
    public function oct() { $this->listing('oct'); }
    public function fundus() { $this->listing('fundus'); }
    public function topography() { $this->listing('topography'); }

    private function listing($modality)
    {
        $data['modality'] = $modality;
        $data['search'] = trim((string) $this->input->get('search', true));
        $data['subtype'] = trim((string) $this->input->get('type', true));
        $data['records'] = $this->ocularimaging_model->getAll($modality, $this->doctorScope(), $data['search'], $data['subtype']);
        $data['stats'] = $this->ocularimaging_model->stats($modality, $this->doctorScope());
        $this->render('admin/ocularimaging/index', $data);
    }

    public function oct_new() { $this->create('oct'); }
    public function fundus_new() { $this->create('fundus'); }
    public function topography_new() { $this->create('topography'); }

    private function create($modality)
    {
        $this->form_validation->set_rules('patient_id', 'Patient', 'trim|required|integer');
        $this->form_validation->set_rules('eye', 'Eye', 'trim|required|in_list[OD,OS]');
        if ($this->form_validation->run()) {
            $id = $this->ocularimaging_model->save($this->postedRecord($modality));
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . ($modality === 'fundus' ? 'Fundus photo' : ($modality === 'topography' ? 'Topography' : 'OCT scan')) . ' saved successfully.</div>');
            redirect('admin/ocularimaging/view/' . $id);
        }
        $data['modality'] = $modality;
        $data['patients'] = $this->ocularimaging_model->getPatients();
        $this->render('admin/ocularimaging/form', $data);
    }

    private function postedRecord($modality)
    {
        $measurement_fields = array(
            'oct' => array('central_macular_thickness','rnfl_thickness','gcl_thickness'),
            'fundus' => array(),
            'topography' => array('sim_k1','sim_k2','axis','astigmatism','thinnest_point','kpi','isa','sai','iha','ivs')
        );
        $finding_fields = $modality === 'fundus' ? array('optic_disc','macula','vessels','periphery') : ($modality === 'topography' ? array('thinnest_location') : array());
        $data = array(
            'record_number' => uniqid('IMG-TMP-'), 'modality' => $modality,
            'patient_id' => (int) $this->input->post('patient_id'), 'doctor_id' => (int) $this->customlib->getStaffID(),
            'eye' => $this->input->post('eye', true), 'subtype' => $this->input->post('subtype', true) ?: null,
            'device' => $this->input->post('device', true) ?: null, 'quality' => $this->input->post('quality', true) ?: null,
            'field_name' => $this->input->post('field', true) ?: null,
            'classification' => $this->input->post('classification', true) ?: null, 'dilated' => $this->input->post('dilated') ? 1 : 0,
            'interpretation' => $this->input->post('interpretation', true) ?: null, 'notes' => $this->input->post('notes', true) ?: null,
            'recorded_at' => date('Y-m-d H:i:s')
        );
        $measurements = array(); foreach ($measurement_fields[$modality] as $field) $measurements[$field] = $this->input->post($field, true);
        $findings = array(); foreach ($finding_fields as $field) $findings[$field] = $this->input->post($field, true);
        $pathologies = $this->input->post('pathologies', true);
        $data['measurements_json'] = json_encode($measurements); $data['findings_json'] = json_encode($findings);
        $data['pathologies_json'] = json_encode(is_array($pathologies) ? array_values($pathologies) : array());
        return $data;
    }

    public function view($id)
    {
        $data['record'] = $this->ocularimaging_model->getById($id, $this->doctorScope());
        if (!$data['record']) show_404();
        $this->render('admin/ocularimaging/view', $data);
    }

    public function delete($id)
    {
        if (strtoupper($this->input->method()) !== 'POST') show_error('Method not allowed', 405);
        $record = $this->ocularimaging_model->getById($id, $this->doctorScope()); if (!$record) show_404();
        $this->ocularimaging_model->delete($id, $this->doctorScope());
        $this->session->set_flashdata('msg', '<div class="alert alert-success">Imaging record deleted.</div>');
        redirect('admin/ocularimaging/' . $record['modality']);
    }

    private function render($view, $data)
    {
        $this->load->view('layout/header', $data); $this->load->view($view, $data); $this->load->view('layout/footer');
    }
}
