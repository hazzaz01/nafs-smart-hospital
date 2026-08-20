<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Prescription extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->config->load("payroll");
        $this->load->library('Enc_lib');
        $this->marital_status = $this->config->item('marital_status');
        $this->payment_mode   = $this->config->item('payment_mode');
        $this->blood_group    = $this->config->item('bloodgroup');
        $this->load->model('prefix_model');
        $this->opd_prefix = $this->prefix_model->getByCategory(array('opd_no'))[0]->prefix;
        $this->load->model('finding_model');
        $this->load->model('optical_prescription_model');
        $this->load->helper('customfield_helper');
    }

    private function isOpticalPrescriptionDoctor()
    {
        $role = json_decode($this->customlib->getStaffRole(), true);
        if (!isset($role['name']) || strtoupper($role['name']) !== 'DOCTOR') {
            return false;
        }

        foreach ($this->staff_model->getStaffSpeciality((int) $this->customlib->getStaffID()) as $speciality) {
            $name = strtolower(trim($speciality->specialist_name));
            if ($name === 'ophthalmologist' || $name === 'ophthalmology') {
                return true;
            }
        }

        return false;
    }

    public function printPrescription()
    {
        $visitid               = $this->input->get('visitid');
        $data["print_details"] = $this->printing_model->getheaderfooter('opdpre');
        $result                = $this->prescription_model->getPrescriptionByVisitID($visitid);
        $data["result"]        = $result;
        $data["id"]     = $visitid;
        $data["opd_id"] = $result->opd_detail_id;
        $data['fields_prescription']   =  $this->customfield_model->get_custom_fields('prescription', '',1);
        $data['optical_prescription'] = $this->optical_prescription_model->getByPrescriptionId($result->prescription_id);
        $view           = !empty($data['optical_prescription']['ophthalmology_data']) ? 'admin/patient/_ophthalmology_print' : 'admin/patient/_printprescription';
        $page           = $this->load->view($view, $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function getPrescription($visitid)
    {
        $result                = $this->prescription_model->getPrescriptionByVisitID($visitid);
        $data["result"]        = $result;
        $data["print_details"] = $this->printing_model->getheaderfooter('opdpre');
        $data["id"]            = $visitid;
        $data["opd_id"]        = $result->opd_detail_id;
        $data['optical_prescription'] = $this->optical_prescription_model->getByPrescriptionId($result->prescription_id);
        
        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
            $data['fields_prescription']   =  $this->customfield_model->get_custom_fields('prescription', '',1); 
        } else {
            $data["print"] = 'no';
            $data['fields_prescription']   =  $this->customfield_model->get_custom_fields('prescription'); 
        } 
        $view = !empty($data['optical_prescription']['ophthalmology_data']) ? 'admin/patient/_ophthalmology_print' : 'admin/patient/prescription';
        $this->load->view($view, $data);
    }

    public function getPrescriptionmanual($visitid)
    {
        $result                   = $this->prescription_model->getmanual($visitid);
        $opddata                  = $this->patient_model->getopdvisitDetailsbyvisitid($visitid);
        $opdid                    = $opddata['opdid'];
        $data['blood_group_name'] = $opddata['blood_group_name'];
        $data["print_details"]    = $this->printing_model->getheaderfooter('opdpre');
        $data["result"]           = $result;
        $data["visitid"]          = $visitid;
        $data["opdid"]            = $opdid;

        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }

        $data['opd_prefix'] = $this->opd_prefix;

        $this->load->view("admin/patient/prescriptionmanual", $data);
    }

    public function getIPDPrescription()
    {
        $prescription_id       = $this->input->post('prescription_id');
        $result                = $this->prescription_model->getPrescriptionByTable($prescription_id, 'ipd_prescription');

        $data["print_details"] = $this->printing_model->getheaderfooter('ipdpres');
        $data["result"]        = $result;
        $data['optical_prescription'] = $this->optical_prescription_model->getByPrescriptionId($result->prescription_id);

        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
            $data['fields_prescription']   =  $this->customfield_model->get_custom_fields('prescription', '',1); 
        } else {
            $data["print"] = 'no';
            $data['fields_prescription']   =  $this->customfield_model->get_custom_fields('prescription', 1);
        }

        $view = !empty($data['optical_prescription']['ophthalmology_data']) ? 'admin/patient/_ophthalmology_print' : 'admin/patient/ipdprescription';
        $page = $this->load->view($view, $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));

    }

    public function printIPDPrescription()
    {
        $prescription_id = $this->input->post('prescription_id');
        $result          = $this->prescription_model->getPrescriptionByTable($prescription_id, 'ipd_prescription');

        $data["print_details"] = $this->printing_model->getheaderfooter('ipdpres');
        $data["result"]        = $result;
        $data['optical_prescription'] = $this->optical_prescription_model->getByPrescriptionId($result->prescription_id);

        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
            $data['fields_prescription']   =  $this->customfield_model->get_custom_fields('prescription', '',1);
        } else {
            $data["print"] = 'no';
            $data['fields_prescription']   =  $this->customfield_model->get_custom_fields('prescription');
        }

        $view = !empty($data['optical_prescription']['ophthalmology_data']) ? 'admin/patient/_ophthalmology_print' : 'admin/patient/_printIpdPrescription';
        $page = $this->load->view($view, $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function editPrescription($visitid)
    {
        $data['medicineCategory']  = $this->medicine_category_model->getMedicineCategory();
        $data['medicineName']      = $this->pharmacy_model->getMedicineName();
        $data['dosage']            = $this->medicine_dosage_model->getMedicineDosage();
        $result                    = $this->prescription_model->getvisit($visitid);
        $data['prescription_note'] = $this->prescription_model->prescription_note($visitid);
        $prescription_list         = $this->prescription_model->getPrescriptionByOPD($visitid);

        $data['roles']                  = $this->role_model->get();
        $pathology                      = $this->pathology_model->getPathology();
        $data['pathology']              = $pathology;
        $radiology                      = $this->radio_model->getRadiology();
        $data['radiology']              = $radiology;
        $prescription_test              = $this->prescription_model->getPrescriptiontestopd($result["presid"]);
        $data['prescription_test']      = $prescription_test;
        $pathology_list                 = $prescription_test['pathology_data'];
        $radiology_list                 = $prescription_test['radiology_data'];
        $data['prescription_pathology'] = $pathology_list;
        $data['prescription_radiology'] = $radiology_list;
        $data["result"]                 = $result;
        $data["id"]                     = $result['visit_id'];
        $data["opd_id"]                 = $result['opd_details_id'];
        $data["prescription_list"]      = $prescription_list;

        $this->load->view("admin/patient/edit_prescription", $data);
    }

    public function addipdPrescription()
    {
        $ipd_id                    = $this->input->post('ipd_id');
        $data['medicineCategory']  = $this->medicine_category_model->getMedicineCategory();
        $data['intervaldosage']    = $this->medicine_dosage_model->getIntervalDosage();
        $data['durationdosage']    = $this->medicine_dosage_model->getDurationDosage();
        $data['medicineName']      = $this->pharmacy_model->getMedicineName();
        $data['dosage']            = $this->medicine_dosage_model->getMedicineDosage();
        $data['roles']             = $this->role_model->get();
        $pathology                 = $this->pathology_model->getPathology();
        $data['pathology']         = $pathology;
        $radiology                 = $this->radio_model->getRadiology();
        $data['radiology']         = $radiology;
        $data['ipd_id']            = $ipd_id;
        $patient_record            = $this->patient_model->get_patientidbyIpdId($ipd_id);
        $data['eye_patient']       = $this->db->where('id', $patient_record['patient_id'])->get('patients')->row_array();
        $data['diagnosis_options'] = $this->prescription_model->getPatientDiagnosisOptions($patient_record['patient_id']);
        $findingresult             = $this->finding_model->getfindingcategory();
        $data['findingresult']     = $findingresult;
        $data['priscribe_list']    = $this->patient_model->getDoctorsipd($ipd_id);
        $consultant_doctor         = $this->patient_model->get_patientidbyIpdId($ipd_id);
        $data['consultant_doctor'] = $consultant_doctor;
        $data['is_optical_prescription_doctor'] = $this->isOpticalPrescriptionDoctor();
        $data['optical_prescription'] = array();
        
        $page = $this->load->view('admin/patient/_addipdprescription', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }
 
    public function addopdPrescription()
    {
        $data['visit_details_id'] = $this->input->post('visit_detail_id');
        $patient_record           = $this->patient_model->get_patientidbyvisitid($data['visit_details_id']);
        $data['eye_patient']      = $this->db->where('id', $patient_record['patient_id'])->get('patients')->row_array();
        $data['diagnosis_options'] = $this->prescription_model->getPatientDiagnosisOptions($patient_record['patient_id']);
        $data['medicineCategory'] = $this->medicine_category_model->getMedicineCategory();
        $data['intervaldosage']   = $this->medicine_dosage_model->getIntervalDosage();
        $data['durationdosage']   = $this->medicine_dosage_model->getDurationDosage();
        $data['medicineName']     = $this->pharmacy_model->getMedicineName();
        $data['dosage']           = $this->medicine_dosage_model->getMedicineDosage();
        $data['roles']            = $this->role_model->get();
        $pathology                = $this->pathology_model->getPathology();
        $data['pathology']        = $pathology;
        $radiology                = $this->radio_model->getRadiology();
        $data['radiology']        = $radiology;
        $findingresult            = $this->finding_model->getfindingcategory();
        $data['findingtype']      = $findingresult;
        $data['is_optical_prescription_doctor'] = $this->isOpticalPrescriptionDoctor();
        $data['optical_prescription'] = array();
        $page                     = $this->load->view('admin/patient/_addopdprescription', $data, true); 
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function editipdPrescription()
    {
        $prescription_id          = $this->input->post('prescription_id');
        $result                   = $this->prescription_model->getPrescriptionByTable($prescription_id, 'ipd_prescription');
        $data['medicineCategory'] = $this->medicine_category_model->getMedicineCategory();
        $data['intervaldosage']   = $this->medicine_dosage_model->getIntervalDosage();
        $data['durationdosage']   = $this->medicine_dosage_model->getDurationDosage();
        $data['medicineName']     = $this->pharmacy_model->getMedicineName();
        $data['dosage']           = $this->medicine_dosage_model->getMedicineDosage();
        $data['roles']            = $this->role_model->get();
        $pathology                = $this->pathology_model->getPathology();
        $data['pathology']        = $pathology;
        $radiology                = $this->radio_model->getRadiology();
        $data['radiology']        = $radiology;
        $data["result"]           = $result;
        $data['eye_patient']       = (array) $result;
        $data['diagnosis_options'] = $this->prescription_model->getPatientDiagnosisOptions($result->patient_id, $result->diagnosis);
        $data["prescription_id"]  = $prescription_id;
        $findingresult            = $this->finding_model->getfindingcategory();
        $data['findingresult']    = $findingresult;
        $priscribe_list           = $this->patient_model->getDoctorsipd($result->ipd_id);
        $doctor_name              = $result->name . " " . $result->surname . "(" . $result->employee_id . ")";

        $consultant_doctorarray[] = array('id' => $result->cons_doctor, 'name' => $doctor_name);
        foreach ($priscribe_list as $key => $value) {
            $consultant_doctorarray[] = array('id' => $value['consult_doctor'], 'name' => $value['ipd_doctorname'] . " " . $value['ipd_doctorsurname'] . "(" . $value['employee_id'] . ")");
        }
        $data['priscribe_list'] = $consultant_doctorarray;
        $data['is_optical_prescription_doctor'] = $this->isOpticalPrescriptionDoctor();
        $data['optical_prescription'] = $data['is_optical_prescription_doctor'] ? $this->optical_prescription_model->getByPrescriptionId($prescription_id) : array();
 
        $page = $this->load->view('admin/patient/_editipdprescription', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function editopdPrescription()
    {
        $prescription_id = $this->input->post('prescription_id');
        $result          = $this->prescription_model->getPrescriptionByTable($prescription_id, 'opd_prescription');
        
        $data['medicineCategory'] = $this->medicine_category_model->getMedicineCategory();
        $data['intervaldosage']   = $this->medicine_dosage_model->getIntervalDosage();
        $data['durationdosage']   = $this->medicine_dosage_model->getDurationDosage();
        $data['medicineName']     = $this->pharmacy_model->getMedicineName();
        $data['dosage']           = $this->medicine_dosage_model->getMedicineDosage();
        $data['roles']            = $this->role_model->get();
        $pathology                = $this->pathology_model->getPathology();
        $data['pathology']        = $pathology;
        $radiology                = $this->radio_model->getRadiology();
        $data['radiology']        = $radiology;
        $data["result"]           = $result;
        $data['eye_patient']       = (array) $result;
        $data['diagnosis_options'] = $this->prescription_model->getPatientDiagnosisOptions($result->patient_id, $result->diagnosis);
        $data["prescription_id"]  = $prescription_id;
        $findingresult            = $this->finding_model->getfindingcategory();
        $data['findingresult']    = $findingresult;
        $data['is_optical_prescription_doctor'] = $this->isOpticalPrescriptionDoctor();
        $data['optical_prescription'] = $data['is_optical_prescription_doctor'] ? $this->optical_prescription_model->getByPrescriptionId($prescription_id) : array();

        $page = $this->load->view('admin/patient/_editopdprescription', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function deleteopdPrescription($prescription_id)
    {
        $this->prescription_model->deleteopdPrescription($prescription_id);
        $json = array('status' => 'success', 'error' => '', 'msg' => $this->lang->line('delete_message'));
        echo json_encode($json);
    }

    public function deleteipdPrescription($id)
    {
        if (!empty($id)) {
            $this->prescription_model->deleteipdPrescription($id);
            $json = array('status' => 'success', 'error' => '', 'msg' => $this->lang->line('delete_message'));
            echo json_encode($json);
        }
    }
   
    public function download($visit_details_id)
    {
        $get_prescription    = $this->prescription_model->getPrescriptionByVisitID($visit_details_id);
        $this->media_storage->filedownload($get_prescription->attachment,"./uploads/prescription_document/");
    }

    public function downloadprescription($prescription_id)
    {
        $result = $this->prescription_model->getPrescriptionbyprescriptionid($prescription_id);
        $this->media_storage->filedownload($result[0]['attachment'],"./uploads/prescription_document/");
    }   





   

}
