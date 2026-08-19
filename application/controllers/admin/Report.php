<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Report extends Admin_Controller
{ 
    public function __construct()
    {
        parent::__construct();
        $this->load->library('datatables');
        $this->load->model('report_model');
    }

    public function finance(){
        $data=array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/finance');
        $this->session->set_userdata('subsub_menu', '');

    	$this->load->view('layout/header');
        $this->load->view('admin/report/finance', $data);
        $this->load->view('layout/footer');
    } 

    public function appointment(){
    	$data=array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/appointment');
        $this->session->set_userdata('subsub_menu', '');

        $this->load->view('layout/header');
        $this->load->view('admin/report/appointment', $data);
        $this->load->view('layout/footer');
    }

    public function opd(){
    	$data=array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/opd');
        $this->session->set_userdata('subsub_menu', '');

        $this->load->view('layout/header');
        $this->load->view('admin/report/opd', $data);
        $this->load->view('layout/footer');
    }

    public function ipd(){
    	$data=array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/ipd');
        $this->session->set_userdata('subsub_menu', '');

        $this->load->view('layout/header');
        $this->load->view('admin/report/ipd', $data);
        $this->load->view('layout/footer');
    }

    public function pharmacy(){
    	$data=array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/pharmacy');
        $this->session->set_userdata('subsub_menu', '');
        
        $this->load->view('layout/header');
        $this->load->view('admin/report/pharmacy', $data);
        $this->load->view('layout/footer');
    }

    public function radiology(){
    	$data=array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/radiology');
        $this->session->set_userdata('subsub_menu', '');

        $this->load->view('layout/header');
        $this->load->view('admin/report/radiology', $data);
        $this->load->view('layout/footer');
    }

    public function pathology(){
        $data=array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/pathology');
        $this->session->set_userdata('subsub_menu', '');

        $this->load->view('layout/header');
        $this->load->view('admin/report/pathology', $data);
        $this->load->view('layout/footer');
    }

    public function blood_bank(){
    	$data=array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/bloodbank');
        $this->session->set_userdata('subsub_menu', '');

        $this->load->view('layout/header');
        $this->load->view('admin/report/blood_bank', $data);
        $this->load->view('layout/footer');
    }
 
    public function ambulance(){
    	$data=array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/ambulance');
        $this->session->set_userdata('subsub_menu', '');

        $this->load->view('layout/header');
        $this->load->view('admin/report/ambulance', $data); 
        $this->load->view('layout/footer');
    }

    public function birth_death(){
    	$data=array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/birth_death');
        $this->session->set_userdata('subsub_menu', '');

        $this->load->view('layout/header');
        $this->load->view('admin/report/birth_death', $data); 
        $this->load->view('layout/footer');
    }
 
    public function ot(){
    	$data=array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/ot');
        $this->session->set_userdata('subsub_menu', '');

        $this->load->view('layout/header');
        $this->load->view('admin/report/ot', $data); 
        $this->load->view('layout/footer');
    }

    public function human_resource(){
        $data=array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/human_resource');
        $this->session->set_userdata('subsub_menu', '');

        $this->load->view('layout/header');
        $this->load->view('admin/report/human_resource', $data); 
        $this->load->view('layout/footer');
    }

    public function tpa(){
    	$data=array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/tpa');
        $this->session->set_userdata('subsub_menu', '');

        $this->load->view('layout/header');
        $this->load->view('admin/report/tpa', $data); 
        $this->load->view('layout/footer');
    }

    public function inventory(){
    	$data=array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/inventory');
        $this->session->set_userdata('subsub_menu', '');

        $this->load->view('layout/header');
        $this->load->view('admin/report/inventory', $data); 
        $this->load->view('layout/footer');
    }

    public function live_consultation(){
    	$data=array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/live_consultation');
        $this->session->set_userdata('subsub_menu', '');

        $this->load->view('layout/header');
        $this->load->view('admin/report/live_consultation', $data); 
        $this->load->view('layout/footer');
    }

    public function log(){
    	$data=array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/log');
        $this->session->set_userdata('subsub_menu', '');
        
        $this->load->view('layout/header');
        $this->load->view('admin/report/log', $data); 
        $this->load->view('layout/footer');
    }

    public function patient(){
    	$data=array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/patient');
        $this->session->set_userdata('subsub_menu', '');
        
        $this->load->view('layout/header');
        $this->load->view('admin/report/patient', $data); 
        $this->load->view('layout/footer');
    }

    public function getpharmacystock()
    {

        $condition['medicine_category'] = $medicine_category =  $this->input->post('medicine_category');
        $condition['stock_type']        = $stock_type        = $this->input->post('stock_type');
        $start_date                     = '';
        $end_date                       = '';
		
        $dt_response = $this->pharmacy_model->getAllstockpharmacyRecord($condition);
        $dt_response = json_decode($dt_response);
       
        $dt_data     = array();
        if (!empty($dt_response->data)) {
            foreach ($dt_response->data as $key => $value) {
                
                $result   =   $this->pharmacy_model->getAvailableQuantity($value->id);
                
                if(!empty($result['used_quantity'])){
                    $used_quantity  =   $result['used_quantity'];
                }else{
                    $used_quantity  =  0 ;
                }                   
                $row = array();
                $available_qty = ($value->total_qty - $used_quantity);
                //====================================
                $status = "";
                $status1 = "";

                if($stock_type!=""){
                if ($available_qty <= 0 && $stock_type=="out_of_stock") {
                }elseif ( ($available_qty > 0 && $available_qty < $value->min_level) && $stock_type=="low_stock") {
                }elseif( ($available_qty <= $value->reorder_level)  && $stock_type=="reorder") {                   
                }else{
                    continue;
                }  
                }

                if ($available_qty <= 0) {
                    $status_val="out_of_stock";
                    $status = " <span class='text text-danger'> (" . $this->lang->line('out_of_stock') . ")</span>";
                } elseif ($available_qty > 0 && $available_qty < $value->min_level ) {
                    $status = " <span class='text text-warning'> (" . $this->lang->line('low_stock') . ")</span>"; 
                    $status_val="low_stock";
                }elseif($available_qty <= $value->reorder_level ) {                   
                    $status = " <span class='text text-info'> (" . $this->lang->line('reorder') . ")</span>";
                    $status_val="reorder";
                }  
                
             
                //==============================
                if(!empty($condition['stock_type'])){
                    if($status_val==$condition['stock_type']){
                        $row[]     = $value->medicine_name;
						$row[]     = $value->company_name;
						$row[]     = $value->medicine_composition;
						$row[]     = $value->medicine_category;
						$row[]     = $value->group_name;
						$row[]     = $value->unit_name;
						$row[]     = $available_qty . $status;
						$dt_data[] = $row;
                    }else{
						
                    }
                
                }else{
                    $row[]     = $value->medicine_name;
					$row[]     = $value->company_name;
					$row[]     = $value->medicine_composition;
					$row[]     = $value->medicine_category;
					$row[]     = $value->group_name;
					$row[]     = $value->unit_name;
					$row[]     = $available_qty . $status;
					$dt_data[] = $row;
                }
                
            }
        }
        $json_data = array(
            "draw"            => intval($dt_response->draw),
            "recordsTotal"    => intval(count($dt_data)),
            "recordsFiltered" => intval(count($dt_data)),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }
      public function stockcheckvalidation()
    {
        $search = $this->input->post('search');
       
            $param = array(
                
                'stock_type'          => $this->input->post('stock_type'),
                'medicine_category' => $this->input->post('medicine_category'),
            );

            $json_array = array('status' => 'success', 'error' => '', 'param' => $param, 'message' => $this->lang->line('success_message'));
    
        echo json_encode($json_array);
    }

    public function stock_report()
    {
        if (!$this->rbac->hasPrivilege('stock_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/report');
        $this->session->set_userdata('subsub_menu', 'reports/report/stock_report');        
        $supplierCategory         = array('reorder'=>$this->lang->line('reorder'),'low_stock'=>$this->lang->line('low_stock'),'out_of_stock'=>$this->lang->line('out_of_stock'));
        $data["supplierCategory"] = $supplierCategory;
        $medicineCategory         = $this->medicine_category_model->getMedicineCategory();
        $data["medicineCategory"] = $medicineCategory;
        $this->load->view('layout/header');
        $this->load->view('admin/report/stock_report', $data);
        $this->load->view('layout/footer');
    }

    public function balanceamountreport()
    {
        if (!$this->rbac->hasPrivilege('balance_amount_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/report');
        $this->session->set_userdata('subsub_menu', 'admin/report/balanceamountreport');
        $data['modules_type']   =  $modules_type= $this->input->post('modules_type');
        $data['patient_id']     =  $patient_id= $this->input->post('patient_id');
        $data['patient_name']   =  $patient_name= $this->input->post('patient_name');
        $data['balance_data']   = $this->report_model->getmodulewisebalance_report($modules_type,$patient_id);
        $data["modules"]        = $this->customlib->get_modules();
        $this->load->view('layout/header');
        $this->load->view('admin/report/balanceamountreport', $data);
        $this->load->view('layout/footer');
    }

    public function getPatientListAjax()
    {
        $search_term = $this->input->post("searchTerm");
        if (isset($search_term) && $search_term != '') {
            $result = $this->patient_model->getPatientListfilter($search_term);
            $data   = array();
            if (!empty($result)) {

                foreach ($result as $value) {
                    $data[] = array("id" => $value->id, "text" => $value->patient_name . " (" . $value->id . ")");
                }
            }
            echo json_encode($data);
        }
    }




}