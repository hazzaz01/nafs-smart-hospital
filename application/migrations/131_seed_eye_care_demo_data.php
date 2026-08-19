<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Seed_eye_care_demo_data extends CI_Migration
{
    private $marker = 'CLIENT-DEMO-131';

    public function up()
    {
        $doctor = $this->db->where('email', 'ajay@gmail.com')->get('staff')->row_array();
        if (!$doctor) return;
        $doctor_id = (int) $doctor['id'];
        $wanted = array(95, 27, 167, 44, 67, 7, 28, 3);
        $rows = $this->db->select('id')->where_in('id', $wanted)->get('patients')->result_array();
        $available = array_map('intval', array_column($rows, 'id'));
        $patients = array_values(array_intersect($wanted, $available));
        if (count($patients) < 8) {
            $rows = $this->db->select('id')->where('is_active', 'yes')->where('is_dead', 'no')->order_by('age', 'DESC')->limit(8)->get('patients')->result_array();
            $patients = array_map('intval', array_column($rows, 'id'));
        }
        if (count($patients) < 8) return;

        $this->db->trans_start();
        $this->seedEyeExams($doctor_id, $patients);
        $this->seedGlaucoma($doctor_id, $patients);
        $this->seedDr($doctor_id, $patients);
        $this->seedSurgery($doctor_id, $patients);
        $this->seedImaging($doctor_id, $patients);
        $this->db->trans_complete();
    }

    private function seedEyeExams($doctor, $p)
    {
        if ($this->db->like('plan', $this->marker)->count_all_results('eye_examinations')) return;
        $cases = array(
            array($p[0], 'Progressive blurred vision and glare at night', '6/60', '6/36', 17, 16, 'H25.13', 'Age-related nuclear cataract', 'Cataract counseling; biometry and surgical planning.'),
            array($p[1], 'Routine glaucoma follow-up', '6/9', '6/9', 24, 25, 'H40.1112', 'Primary open-angle glaucoma, moderate stage', 'Continue pressure-lowering drops and repeat RNFL OCT.'),
            array($p[2], 'Difficulty reading and eye strain', '6/12', '6/12', 15, 16, 'H52.4', 'Presbyopia', 'Updated near addition and spectacle prescription.'),
            array($p[3], 'Sudden floaters in the right eye', '6/12', '6/6', 14, 14, 'H43.811', 'Posterior vitreous detachment, right eye', 'Retinal detachment precautions explained; review in two weeks.'),
            array($p[4], 'Diabetic annual eye screening', '6/9', '6/12', 16, 17, 'E11.3293', 'Mild nonproliferative diabetic retinopathy', 'Optimize glycemic control and obtain fundus photography.'),
            array($p[5], 'Redness, itching and watering', '6/6', '6/6', 13, 13, 'H10.13', 'Allergic conjunctivitis, bilateral', 'Cold compresses and topical antihistamine.'),
            array($p[6], 'Progressive distortion of vision', '6/18', '6/9', 18, 17, 'H35.371', 'Epiretinal membrane, right eye', 'Macular OCT and retinal consultation.'),
            array($p[7], 'Routine comprehensive eye examination', '6/6', '6/6', 14, 15, 'Z01.00', 'Normal eye examination', 'Routine review in one year.')
        );
        foreach ($cases as $i => $c) {
            $this->db->insert('eye_examinations', array(
                'patient_id'=>$c[0], 'doctor_id'=>$doctor, 'exam_date'=>date('Y-m-d H:i:s', strtotime('-'.$i.' days')), 'chief_complaint'=>$c[1],
                'history_present_illness'=>'Symptoms reviewed during a complete ophthalmic assessment.', 'va_scale'=>'Snellen',
                'ucva_distance_od'=>$c[2], 'ucva_distance_os'=>$c[3], 'ucva_near_od'=>'N8', 'ucva_near_os'=>'N8',
                'bcva_distance_od'=>'6/6', 'bcva_distance_os'=>'6/6', 'bcva_near_od'=>'N6', 'bcva_near_os'=>'N6', 'pinhole_od'=>'6/6', 'pinhole_os'=>'6/6',
                'refraction_od'=>'SPH -0.50 CYL -0.50 AXIS 90', 'refraction_os'=>'SPH -0.25 CYL -0.50 AXIS 85', 'iop_od'=>$c[4], 'iop_os'=>$c[5], 'iop_method'=>'Goldmann Applanation',
                'anterior_segment_od'=>'Clear cornea, deep quiet chamber, reactive pupil.', 'anterior_segment_os'=>'Clear cornea, deep quiet chamber, reactive pupil.',
                'fundus_od'=>'Disc, macula, vessels and periphery assessed.', 'fundus_os'=>'Disc, macula, vessels and periphery assessed.',
                'diagnosis'=>$c[7], 'diagnoses_json'=>json_encode(array(array('icd_code'=>$c[6], 'description'=>$c[7], 'eye'=>'OU'))),
                'plan'=>$c[8].' ['.$this->marker.']', 'medications_json'=>json_encode($i===1 ? array(array('medication'=>'Latanoprost','dosage'=>'0.005%','frequency'=>'Once nightly','duration'=>'Ongoing','eye'=>'OU')) : array()),
                'follow_up_recommended'=>1, 'follow_up_interval'=>$i===3?'2 weeks':($i===7?'12 months':'3 months'), 'follow_up_reason'=>'Monitor clinical status and treatment response.', 'follow_up_date'=>date('Y-m-d', strtotime('+'.($i===3?14:90).' days'))
            ));
        }
    }

    private function seedGlaucoma($doctor, $p)
    {
        if ($this->db->like('notes', $this->marker)->count_all_results('glaucoma_records')) return;
        $cases = array(
            array($p[1],'poag','moderate',18,18,.72,.68,'stable',array(21,22,19,20,17,18),array('family_history','high_iop'),array(array('name'=>'Latanoprost','dosage'=>'0.005%','frequency'=>'OD','eye'=>'OU'))),
            array($p[0],'pacg','severe',16,16,.82,.78,'progressing',array(25,24,22,21,20,19),array('family_history','thin_cornea'),array(array('name'=>'Dorzolamide/Timolol','dosage'=>'2%/0.5%','frequency'=>'BD','eye'=>'OU'))),
            array($p[2],'suspect','mild',20,20,.55,.58,'stable',array(22,21,20,19,18,18),array('suspicious_disc'),array()),
            array($p[4],'ntg','moderate',14,14,.70,.74,'stable',array(17,18,16,16,14,13),array('thin_cornea','vascular_disease'),array(array('name'=>'Brimonidine','dosage'=>'0.2%','frequency'=>'BD','eye'=>'OU')))
        );
        foreach ($cases as $i=>$c) {
            $this->db->insert('glaucoma_records', array('patient_id'=>$c[0],'doctor_id'=>$doctor,'glaucoma_type'=>$c[1],'severity'=>$c[2],
                'diagnosis_date'=>date('Y-m-d',strtotime('-'.(250+$i*90).' days')),'family_history'=>$i<2?1:0,'risk_factors_json'=>json_encode($c[9]),
                'target_iop_od'=>$c[3],'target_iop_os'=>$c[4],'cdr_od'=>$c[5],'cdr_os'=>$c[6],'rim_thinning_od'=>$i===1?'severe':'moderate','rim_thinning_os'=>$i===1?'severe':'mild',
                'disc_hemorrhage_od'=>$i===1?1:0,'disc_hemorrhage_os'=>0,'nfl_defect_od'=>$i!==2?1:0,'nfl_defect_os'=>$i<2?1:0,'progression_status'=>$c[7],
                'medications_json'=>json_encode($c[10]),'next_visit'=>date('Y-m-d',strtotime('+'.(30+$i*15).' days')),'notes'=>'Longitudinal glaucoma management record for client presentation. ['.$this->marker.']'));
            $record=(int)$this->db->insert_id();
            for($r=0;$r<3;$r++) $this->db->insert('glaucoma_iop_readings',array('glaucoma_record_id'=>$record,'doctor_id'=>$doctor,'measured_at'=>date('Y-m-d H:i:s',strtotime('-'.((2-$r)*45+$i).' days')),'iop_od'=>$c[8][$r*2],'iop_os'=>$c[8][$r*2+1],'method'=>'goldmann','notes'=>$r===2?'Latest clinic measurement':'Follow-up pressure check'));
        }
    }

    private function seedDr($doctor, $p)
    {
        if ($this->db->like('notes', $this->marker)->count_all_results('dr_screenings')) return;
        $levels=array(
            array($p[7],'no_dr','no_dr','no_dme','no_dme',6.4,3,array(),array(),'12 months','Routine annual screening'),
            array($p[4],'mild_npdr','mild_npdr','no_dme','no_dme',7.2,7,array('microaneurysms'),array('microaneurysms'),'9 months','Mild NPDR monitoring'),
            array($p[2],'moderate_npdr','mild_npdr','non_ci_dme','no_dme',8.1,12,array('microaneurysms','hemorrhages','hard_exudates'),array('microaneurysms'),'6 months','Moderate NPDR monitoring'),
            array($p[0],'severe_npdr','moderate_npdr','ci_dme','non_ci_dme',9.3,18,array('hemorrhages','cotton_wool_spots','venous_beading','macular_edema'),array('hemorrhages','hard_exudates'),'3 months','Severe NPDR with macular edema'),
            array($p[1],'pdr','severe_npdr','ci_dme','ci_dme',10.2,22,array('neovascularization','vitreous_hemorrhage','macular_edema'),array('venous_beading','irma','macular_edema'),'1 month','Urgent retinal evaluation')
        );
        foreach($levels as $i=>$c) $this->db->insert('dr_screenings',array('patient_id'=>$c[0],'doctor_id'=>$doctor,'screening_date'=>date('Y-m-d H:i:s',strtotime('-'.$i.' days')),'diabetes_type'=>'type2','diabetes_duration'=>$c[6],'hba1c'=>$c[5],'last_hba1c_date'=>date('Y-m-d',strtotime('-15 days')),'bp_systolic'=>120+$i*5,'bp_diastolic'=>78+$i*2,'total_cholesterol'=>180+$i*10,'ldl'=>95+$i*8,'hdl'=>48,'triglycerides'=>130+$i*12,'od_dr_level'=>$c[1],'od_dme_status'=>$c[3],'od_findings_json'=>json_encode($c[7]),'os_dr_level'=>$c[2],'os_dme_status'=>$c[4],'os_findings_json'=>json_encode($c[8]),'fundus_photo'=>1,'oct'=>$i>=2?1:0,'ffa'=>$i===4?1:0,'next_screening'=>date('Y-m-d',strtotime('+'.(30+$i*20).' days')),'follow_up_frequency'=>$c[9],'follow_up_reason'=>$c[10],'notes'=>'Complete diabetic retinal screening demonstration. ['.$this->marker.']'));
    }

    private function seedSurgery($doctor, $p)
    {
        if ($this->db->like('surgery_number', 'DEMO-SUR-%')->count_all_results('eye_surgeries')) return;
        $cases=array(
            array($p[0],'cataract','OD','Phacoemulsification + IOL','scheduled','+0 days 10:30','topical','OR-1'),
            array($p[1],'glaucoma','OS','Trabeculectomy','scheduled','+1 day 09:00','local','OR-2'),
            array($p[2],'refractive','OU','LASIK','in_progress','+0 days 14:00','topical','Laser Suite'),
            array($p[3],'retinal','OD','Vitrectomy','completed','-7 days 11:00','local','OR-1'),
            array($p[4],'corneal','OS','Pterygium Excision','completed','-15 days 08:30','local','OR-3'),
            array($p[6],'oculoplastic','OU','Blepharoplasty','cancelled','+5 days 12:00','sedation','OR-2')
        );
        foreach($cases as $i=>$c) $this->db->insert('eye_surgeries',array('surgery_number'=>'DEMO-SUR-'.str_pad($i+1,3,'0',STR_PAD_LEFT),'patient_id'=>$c[0],'surgeon_id'=>$doctor,'created_by'=>$doctor,'surgery_type'=>$c[1],'eye'=>$c[2],'procedure_name'=>$c[3],'surgery_date'=>date('Y-m-d H:i:s',strtotime($c[5])),'anesthesia_type'=>$c[6],'operating_room'=>$c[7],'pre_op_notes'=>'Pre-operative checklist completed; consent and investigations reviewed. ['.$this->marker.']','status'=>$c[4],'cataract_technique'=>$c[1]==='cataract'?'phaco':null,'iol_model'=>$c[1]==='cataract'?'Alcon AcrySof SN60WF':null,'iol_power'=>$c[1]==='cataract'?21.0:null,'target_refraction'=>$c[1]==='cataract'?-0.25:null,'refractive_procedure'=>$c[1]==='refractive'?'lasik':null,'optical_zone'=>$c[1]==='refractive'?6.0:null,'ablation_zone'=>$c[1]==='refractive'?6.5:null,'target_sphere'=>$c[1]==='refractive'?0:null,'target_cylinder'=>$c[1]==='refractive'?0:null,'target_axis'=>$c[1]==='refractive'?0:null));
    }

    private function seedImaging($doctor, $p)
    {
        if ($this->db->like('record_number', 'DEMO-%')->count_all_results('ocular_imaging')) return;
        $rows=array(
            array('oct',$p[6],'OD','macula','Zeiss Cirrus','excellent',null,array('central_macular_thickness'=>384,'rnfl_thickness'=>92,'gcl_thickness'=>78),array(),array('epiretinal_membrane'),'Epiretinal membrane with retinal thickening.'),
            array('oct',$p[1],'OS','rnfl','Heidelberg Spectralis','good',null,array('central_macular_thickness'=>252,'rnfl_thickness'=>68,'gcl_thickness'=>70),array(),array('rnfl_thinning'),'Superior and inferior RNFL thinning.'),
            array('oct',$p[0],'OD','optic_nerve','Zeiss Cirrus','good',null,array('central_macular_thickness'=>248,'rnfl_thickness'=>74,'gcl_thickness'=>72),array(),array('rnfl_thinning'),'Glaucomatous structural change.'),
            array('oct',$p[7],'OS','macula','Topcon Triton','excellent',null,array('central_macular_thickness'=>265,'rnfl_thickness'=>96,'gcl_thickness'=>82),array(),array(),'Normal macular architecture.'),
            array('fundus',$p[4],'OD','color','Canon CR-2','good',null,array(),array('optic_disc'=>'Pink, C/D 0.4','macula'=>'Scattered microaneurysms','vessels'=>'Mild venous beading','periphery'=>'Flat, no breaks'),array('microaneurysms','hemorrhages'),'Features of mild NPDR.'),
            array('fundus',$p[1],'OS','red_free','Topcon NW400','excellent',null,array(),array('optic_disc'=>'Cupped, C/D 0.7','macula'=>'Flat','vessels'=>'Normal caliber','periphery'=>'Attached'),array('cupping'),'Glaucomatous optic disc cupping.'),
            array('fundus',$p[0],'OD','ffa','Heidelberg HRA','good',null,array(),array('optic_disc'=>'No leakage','macula'=>'Late perifoveal leakage','vessels'=>'Microaneurysms','periphery'=>'Ischemic areas'),array('microaneurysms','neovascularization'),'Proliferative diabetic changes.'),
            array('fundus',$p[7],'OS','color','Canon CR-2','excellent',null,array(),array('optic_disc'=>'Pink, healthy, C/D 0.3','macula'=>'Normal foveal reflex','vessels'=>'Normal AV ratio','periphery'=>'Flat, no breaks'),array(),'Normal fundus photography.'),
            array('topography',$p[6],'OD',null,'Pentacam',null,'keratoconus',array('sim_k1'=>47.8,'sim_k2'=>51.2,'axis'=>105,'astigmatism'=>3.4,'thinnest_point'=>438,'kpi'=>0.34,'isa'=>2.8,'sai'=>1.9,'iha'=>28,'ivs'=>0.42),array('thinnest_location'=>'Inferotemporal'),array(),'Keratoconus pattern with inferior steepening.'),
            array('topography',$p[2],'OS',null,'Pentacam',null,'normal',array('sim_k1'=>43.1,'sim_k2'=>44.0,'axis'=>88,'astigmatism'=>0.9,'thinnest_point'=>542,'kpi'=>0.04,'isa'=>0.5,'sai'=>0.3,'iha'=>5,'ivs'=>0.12),array('thinnest_location'=>'Central'),array(),'Regular symmetric corneal topography.'),
            array('topography',$p[3],'OD',null,'Sirius','good','suspect',array('sim_k1'=>45.2,'sim_k2'=>47.1,'axis'=>72,'astigmatism'=>1.9,'thinnest_point'=>486,'kpi'=>0.18,'isa'=>1.4,'sai'=>0.9,'iha'=>16,'ivs'=>0.25),array('thinnest_location'=>'Inferior'),array(),'Asymmetric inferior steepening; monitor.'),
            array('topography',$p[5],'OS',null,'Pentacam',null,'post_lasik',array('sim_k1'=>39.5,'sim_k2'=>40.2,'axis'=>92,'astigmatism'=>0.7,'thinnest_point'=>475,'kpi'=>0.07,'isa'=>0.6,'sai'=>0.4,'iha'=>7,'ivs'=>0.14),array('thinnest_location'=>'Central'),array(),'Stable post-LASIK topographic pattern.')
        );
        $seq=array('oct'=>0,'fundus'=>0,'topography'=>0);
        foreach($rows as $i=>$c){$seq[$c[0]]++;$prefix=$c[0]==='topography'?'TOPO':strtoupper($c[0]);$this->db->insert('ocular_imaging',array('record_number'=>'DEMO-'.$prefix.'-'.str_pad($seq[$c[0]],3,'0',STR_PAD_LEFT),'modality'=>$c[0],'patient_id'=>$c[1],'doctor_id'=>$doctor,'eye'=>$c[2],'subtype'=>$c[3],'device'=>$c[4],'quality'=>$c[5],'field_name'=>$c[0]==='fundus'?'45°':null,'classification'=>$c[6],'dilated'=>$c[0]==='fundus'?1:0,'measurements_json'=>json_encode($c[7]),'findings_json'=>json_encode($c[8]),'pathologies_json'=>json_encode($c[9]),'interpretation'=>$c[10],'notes'=>'High-quality client presentation example. ['.$this->marker.']','recorded_at'=>date('Y-m-d H:i:s',strtotime('-'.$i.' days'))));}
    }

    public function down()
    {
        $this->db->like('record_number','DEMO-%')->delete('ocular_imaging');
        $this->db->like('surgery_number','DEMO-SUR-%')->delete('eye_surgeries');
        $this->db->like('notes',$this->marker)->delete('dr_screenings');
        $glaucoma=$this->db->select('id')->like('notes',$this->marker)->get('glaucoma_records')->result_array();
        if($glaucoma)$this->db->where_in('glaucoma_record_id',array_column($glaucoma,'id'))->delete('glaucoma_iop_readings');
        $this->db->like('notes',$this->marker)->delete('glaucoma_records');
        $this->db->like('plan',$this->marker)->delete('eye_examinations');
    }
}
