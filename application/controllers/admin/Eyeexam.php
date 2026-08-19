<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Eyeexam extends Admin_Controller
{
    private $role_name = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'customlib'));
        $this->load->model('eyeexam_model');
        $role = json_decode($this->customlib->getStaffRole(), true);
        $this->role_name = isset($role['name']) ? strtoupper($role['name']) : '';
        if (!in_array($this->role_name, array('DOCTOR', 'SUPER ADMIN'), true)) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Eye_Examinations');
    }

    private function doctorScope()
    {
        return $this->role_name === 'DOCTOR' ? (int) $this->customlib->getStaffID() : null;
    }

    public function index()
    {
        $search = trim((string) $this->input->get('search', true));
        $data['search'] = $search;
        $data['examinations'] = $this->eyeexam_model->getAll($this->doctorScope(), $search);
        $data['stats'] = $this->eyeexam_model->getStats($this->doctorScope());
        $this->render('admin/eyeexam/index', $data);
    }

    public function create()
    {
        $this->editForm();
    }

    public function edit($id)
    {
        $exam = $this->eyeexam_model->getById($id, $this->doctorScope());
        if (!$exam) {
            show_404();
        }
        $this->editForm($exam);
    }

    private function editForm($exam = array())
    {
        $this->form_validation->set_rules('patient_id', 'Patient', 'trim|required|integer');
        $this->form_validation->set_rules('exam_date', 'Examination date', 'trim|required');
        $this->form_validation->set_rules('chief_complaint', 'Chief complaint', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('iop_od', 'IOP OD', 'trim|numeric');
        $this->form_validation->set_rules('iop_os', 'IOP OS', 'trim|numeric');

        if ($this->form_validation->run()) {
            $data = $this->postedExam($exam);
            $id = $this->eyeexam_model->save($data, isset($exam['id']) ? $exam['id'] : null);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Eye examination saved successfully.</div>');
            redirect('admin/eyeexam/view/' . $id);
        }

        $data['exam'] = $exam;
        $data['patients'] = $this->eyeexam_model->getPatients();
        $this->render('admin/eyeexam/form', $data);
    }

    private function postedExam($exam = array())
    {
        $fields = array(
            'patient_id', 'chief_complaint', 'history_present_illness', 'va_scale',
            'ucva_distance_od', 'ucva_distance_os', 'ucva_near_od', 'ucva_near_os',
            'bcva_distance_od', 'bcva_distance_os', 'bcva_near_od', 'bcva_near_os',
            'pinhole_od', 'pinhole_os', 'refraction_od', 'refraction_os', 'iop_od', 'iop_os',
            'iop_method', 'anterior_segment_od', 'anterior_segment_os', 'fundus_od', 'fundus_os',
            'plan'
        );
        $data = array();
        foreach ($fields as $field) {
            $value = $this->input->post($field, true);
            $data[$field] = $value === '' ? null : $value;
        }
        $exam_date = str_replace('T', ' ', (string) $this->input->post('exam_date', true));
        $data['exam_date'] = date('Y-m-d H:i:s', strtotime($exam_date));
        $data['patient_id'] = (int) $data['patient_id'];
        $diagnoses = $this->cleanStructuredRows($this->input->post('diagnoses', true), array('icd_code', 'description', 'eye'), array('OD', 'OS', 'OU'));
        $medications = $this->cleanStructuredRows($this->input->post('medications', true), array('medication', 'dosage', 'frequency', 'duration', 'eye'), array('OD', 'OS', 'OU'));
        $data['diagnoses_json'] = $diagnoses ? json_encode($diagnoses) : null;
        $data['medications_json'] = $medications ? json_encode($medications) : null;
        // Keep the legacy diagnosis column populated for compatibility with exports and older records.
        $data['diagnosis'] = $diagnoses ? implode('; ', array_column($diagnoses, 'description')) : null;
        $data['follow_up_recommended'] = $this->input->post('follow_up_recommended') ? 1 : 0;
        $data['follow_up_interval'] = $data['follow_up_recommended'] ? $this->input->post('follow_up_interval', true) : null;
        $data['follow_up_reason'] = $data['follow_up_recommended'] ? $this->input->post('follow_up_reason', true) : null;
        // Super Admin may correct an existing record without taking ownership from its doctor.
        $data['doctor_id'] = $this->role_name === 'SUPER ADMIN' && !empty($exam['doctor_id'])
            ? (int) $exam['doctor_id']
            : (int) $this->customlib->getStaffID();
        return $data;
    }

    private function cleanStructuredRows($rows, $fields, $allowedEyes)
    {
        $clean = array();
        if (!is_array($rows)) {
            return $clean;
        }
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $item = array();
            foreach ($fields as $field) {
                $item[$field] = isset($row[$field]) ? trim((string) $row[$field]) : '';
            }
            if (isset($item['eye']) && !in_array($item['eye'], $allowedEyes, true)) {
                $item['eye'] = 'OU';
            }
            $meaningful = $item;
            unset($meaningful['eye']);
            if (implode('', $meaningful) !== '') {
                $clean[] = $item;
            }
        }
        return $clean;
    }

    public function view($id)
    {
        $data['exam'] = $this->eyeexam_model->getById($id, $this->doctorScope());
        if (!$data['exam']) {
            show_404();
        }
        $this->render('admin/eyeexam/view', $data);
    }

    public function delete($id)
    {
        if (strtoupper($this->input->method()) !== 'POST') {
            show_error('Method not allowed', 405);
        }
        $exam = $this->eyeexam_model->getById($id, $this->doctorScope());
        if (!$exam) {
            show_404();
        }
        $this->eyeexam_model->delete($id, $this->doctorScope());
        $this->session->set_flashdata('msg', '<div class="alert alert-success">Eye examination deleted.</div>');
        redirect('admin/eyeexam');
    }

    private function render($view, $data)
    {
        $this->load->view('layout/header', $data);
        $this->load->view($view, $data);
        $this->load->view('layout/footer');
    }
}
