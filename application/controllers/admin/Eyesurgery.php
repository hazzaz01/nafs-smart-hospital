<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Eyesurgery extends Admin_Controller
{
    private $role_name = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'customlib'));
        $this->load->model('eyesurgery_model');
        $role = json_decode($this->customlib->getStaffRole(), true);
        $this->role_name = isset($role['name']) ? strtoupper($role['name']) : '';
        if (!in_array($this->role_name, array('DOCTOR', 'SUPER ADMIN'), true)) access_denied();
        $this->session->set_userdata('top_menu', 'Eye_Surgery');
    }

    private function doctorScope()
    {
        return $this->role_name === 'DOCTOR' ? (int) $this->customlib->getStaffID() : null;
    }

    public function index()
    {
        $data['filters'] = array(
            'search' => trim((string) $this->input->get('search', true)),
            'type' => trim((string) $this->input->get('type', true)),
            'status' => trim((string) $this->input->get('status', true)),
            'date' => trim((string) $this->input->get('date', true))
        );
        $data['records'] = $this->eyesurgery_model->getAll($this->doctorScope(), $data['filters']);
        $data['stats'] = $this->eyesurgery_model->getStats($this->doctorScope());
        $this->render('admin/eyesurgery/index', $data);
    }

    public function create()
    {
        $this->form_validation->set_rules('patient_id', 'Patient', 'trim|required|integer');
        $this->form_validation->set_rules('surgery_type', 'Surgery Type', 'trim|required');
        $this->form_validation->set_rules('eye', 'Eye', 'trim|required|in_list[OD,OS,OU]');
        $this->form_validation->set_rules('procedure_name', 'Procedure', 'trim|required');
        $this->form_validation->set_rules('surgery_date', 'Date', 'trim|required');
        $this->form_validation->set_rules('surgery_time', 'Time', 'trim|required');
        if ($this->form_validation->run()) {
            $id = $this->eyesurgery_model->save($this->postedSurgery());
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Surgery scheduled successfully.</div>');
            redirect('admin/eyesurgery/view/' . $id);
        }
        $data['patients'] = $this->eyesurgery_model->getPatients();
        $data['doctors'] = $this->eyesurgery_model->getDoctors();
        $data['preset_type'] = trim((string) $this->input->get('type', true));
        $this->render('admin/eyesurgery/form', $data);
    }

    private function postedSurgery()
    {
        $surgeon = (int) $this->input->post('surgeon_id');
        if (!$surgeon) $surgeon = (int) $this->customlib->getStaffID();
        $fields = array('patient_id','surgery_type','eye','procedure_name','anesthesia_type','operating_room','pre_op_notes','cataract_technique','iol_model','iol_power','target_refraction','refractive_procedure','optical_zone','ablation_zone','target_sphere','target_cylinder','target_axis');
        $data = array('surgery_number' => uniqid('SUR-TMP-'), 'surgeon_id' => $surgeon, 'created_by' => (int) $this->customlib->getStaffID(), 'status' => 'scheduled');
        foreach ($fields as $field) {
            $value = $this->input->post($field, true);
            $data[$field] = $value === '' ? null : $value;
        }
        $data['patient_id'] = (int) $data['patient_id'];
        $data['surgery_date'] = $this->input->post('surgery_date', true) . ' ' . $this->input->post('surgery_time', true) . ':00';
        return $data;
    }

    public function view($id)
    {
        $data['record'] = $this->eyesurgery_model->getById($id, $this->doctorScope());
        if (!$data['record']) show_404();
        $this->render('admin/eyesurgery/view', $data);
    }

    public function iol_calculator()
    {
        $this->session->set_userdata('sub_sidebar_menu', 'admin/eyesurgery/iol_calculator');
        $this->render('admin/eyesurgery/iol_calculator', array());
    }

    public function iol_calculate()
    {
        if (strtoupper($this->input->method()) !== 'POST') show_error('Method not allowed', 405);
        $al = (float) $this->input->post('axial_length'); $k1 = (float) $this->input->post('k1');
        $k2 = (float) $this->input->post('k2'); $acd = (float) $this->input->post('acd');
        $a = (float) $this->input->post('a_constant'); $target = (float) $this->input->post('target_refraction');
        $formula = (string) $this->input->post('formula');
        if ($al < 15 || $al > 40 || $k1 < 25 || $k2 > 65 || $acd < 1 || $acd > 8) {
            return $this->json(array('success' => false, 'message' => 'Please enter valid biometry data.'));
        }
        $avg_k = ($k1 + $k2) / 2;
        $raw = $a - (0.9 * $avg_k) - (2.5 * $al) - $target;
        if ($formula === 'haigis') $raw += (3.2 - $acd) * 0.4;
        if ($formula === 'holladay1') $raw += (23.5 - $al) * 0.1;
        $power = round($raw * 2) / 2;
        $options = array();
        for ($p = $power - 1; $p <= $power + 1.01; $p += .5) $options[] = array('power' => $p, 'refraction' => round($target + ($raw - $p) * .7, 2));
        return $this->json(array('success' => true, 'power' => $power, 'predicted_refraction' => round($target + ($raw - $power) * .7, 2), 'average_k' => round($avg_k, 2), 'options' => $options));
    }

    private function json($payload)
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($payload));
    }

    public function delete($id)
    {
        if (strtoupper($this->input->method()) !== 'POST') show_error('Method not allowed', 405);
        if (!$this->eyesurgery_model->getById($id, $this->doctorScope())) show_404();
        $this->eyesurgery_model->delete($id, $this->doctorScope());
        $this->session->set_flashdata('msg', '<div class="alert alert-success">Surgery deleted.</div>');
        redirect('admin/eyesurgery');
    }

    private function render($view, $data)
    {
        $this->load->view('layout/header', $data); $this->load->view($view, $data); $this->load->view('layout/footer');
    }
}
