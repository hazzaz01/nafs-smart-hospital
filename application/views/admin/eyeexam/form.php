<?php
$is_edit = !empty($exam['id']);
function eye_value($exam, $key, $fallback = '') { return set_value($key, isset($exam[$key]) ? $exam[$key] : $fallback); }
function eye_input($exam, $name, $label, $placeholder = '', $type = 'text') { ?>
    <div class="form-group col-sm-6"><label><?php echo $label; ?></label><input type="<?php echo $type; ?>" <?php echo $type === 'number' ? 'step="0.01" min="0"' : ''; ?> class="form-control" name="<?php echo $name; ?>" value="<?php echo html_escape(eye_value($exam, $name)); ?>" placeholder="<?php echo html_escape($placeholder); ?>"><span class="text-danger"><?php echo form_error($name); ?></span></div>
<?php }
function eye_textarea($exam, $name, $label) { ?>
    <div class="form-group col-sm-6"><label><?php echo $label; ?></label><textarea class="form-control" rows="3" name="<?php echo $name; ?>"><?php echo html_escape(eye_value($exam, $name)); ?></textarea></div>
<?php } ?>
<?php
$diagnoses = json_decode(isset($exam['diagnoses_json']) ? $exam['diagnoses_json'] : '', true);
if (!is_array($diagnoses) || !$diagnoses) {
    $diagnoses = !empty($exam['diagnosis']) ? array(array('icd_code' => '', 'description' => $exam['diagnosis'], 'eye' => 'OU')) : array(array('icd_code' => '', 'description' => '', 'eye' => 'OU'));
}
$posted_diagnoses = $this->input->post('diagnoses', true);
if (is_array($posted_diagnoses)) { $diagnoses = $posted_diagnoses; }
$medications = json_decode(isset($exam['medications_json']) ? $exam['medications_json'] : '', true);
if (!is_array($medications)) { $medications = array(); }
$posted_medications = $this->input->post('medications', true);
if (is_array($posted_medications)) { $medications = $posted_medications; }
$follow_recommended = isset($exam['follow_up_recommended']) ? (bool) $exam['follow_up_recommended'] : true;
if ($this->input->method() === 'post') { $follow_recommended = (bool) $this->input->post('follow_up_recommended'); }
?>
<style>.eye-tabs{margin-bottom:20px}.eye-section{display:none}.eye-section.active{display:block}.eye-pair-title{font-weight:600;color:#3c8dbc;margin:5px 0 12px}.nav-pills>li>a{cursor:pointer}.assessment-heading{display:flex;align-items:center;justify-content:space-between;margin:5px 0 12px}.assessment-row{background:#f8fafc;border:1px solid #dfe6ec;border-radius:4px;padding:14px 5px 0;margin:0 0 12px}.assessment-row .remove-row{margin-top:25px}.follow-up-panel{border-top:1px solid #eee;margin-top:12px;padding-top:18px}</style>
<div class="content-wrapper">
    <section class="content-header"><h1><i class="fa fa-eye"></i> <?php echo $is_edit ? 'Edit' : 'New'; ?> Eye Examination</h1></section>
    <section class="content">
        <form method="post" id="eyeExamForm">
            <?php echo $this->customlib->getCSRF(); ?>
            <div class="box box-primary">
                <div class="box-header with-border"><h3 class="box-title">Patient Information</h3></div>
                <div class="box-body row">
                    <div class="form-group col-sm-8"><label>Patient <span class="text-danger">*</span></label><select class="form-control select2" name="patient_id" required><option value="">Select patient</option><?php foreach ($patients as $patient) { ?><option value="<?php echo (int) $patient['id']; ?>" <?php echo eye_value($exam, 'patient_id') == $patient['id'] ? 'selected' : ''; ?>><?php echo html_escape($patient['patient_name'] . ' (ID: ' . $patient['id'] . ', ' . $patient['gender'] . ', ' . $patient['age'] . 'y)'); ?></option><?php } ?></select><span class="text-danger"><?php echo form_error('patient_id'); ?></span></div>
                    <div class="form-group col-sm-4"><label>Examination Date <span class="text-danger">*</span></label><input type="datetime-local" class="form-control" name="exam_date" required value="<?php echo html_escape(eye_value($exam, 'exam_date', date('Y-m-d H:i')) ? date('Y-m-d\TH:i', strtotime(eye_value($exam, 'exam_date', date('Y-m-d H:i')))) : ''); ?>"><span class="text-danger"><?php echo form_error('exam_date'); ?></span></div>
                </div>
            </div>
            <div class="box box-info">
                <div class="box-body">
                    <ul class="nav nav-pills eye-tabs" role="tablist">
                        <li class="active"><a data-eye-tab="basic">Basic Info</a></li><li><a data-eye-tab="acuity">Visual Acuity</a></li><li><a data-eye-tab="refraction">Refraction</a></li><li><a data-eye-tab="iop">IOP</a></li><li><a data-eye-tab="anterior">Anterior Segment</a></li><li><a data-eye-tab="fundus">Fundus</a></li><li><a data-eye-tab="assessment">Assessment</a></li>
                    </ul>
                    <div class="eye-section active row" data-eye-section="basic">
                        <div class="form-group col-sm-12"><label>Chief Complaint <span class="text-danger">*</span></label><input class="form-control" name="chief_complaint" required maxlength="255" value="<?php echo html_escape(eye_value($exam, 'chief_complaint')); ?>" placeholder="Blurred vision, eye pain, routine checkup"><span class="text-danger"><?php echo form_error('chief_complaint'); ?></span></div>
                        <div class="form-group col-sm-12"><label>History of Present Illness</label><textarea class="form-control" rows="5" name="history_present_illness"><?php echo html_escape(eye_value($exam, 'history_present_illness')); ?></textarea></div>
                    </div>
                    <div class="eye-section row" data-eye-section="acuity">
                        <div class="col-sm-12"><label class="radio-inline"><input type="radio" name="va_scale" value="Snellen" <?php echo eye_value($exam, 'va_scale', 'Snellen') === 'Snellen' ? 'checked' : ''; ?>> Snellen</label><label class="radio-inline"><input type="radio" name="va_scale" value="LogMAR" <?php echo eye_value($exam, 'va_scale') === 'LogMAR' ? 'checked' : ''; ?>> LogMAR</label><hr></div>
                        <div class="col-sm-12 eye-pair-title">Uncorrected Visual Acuity</div><?php eye_input($exam, 'ucva_distance_od', 'Distance OD', '20/20'); eye_input($exam, 'ucva_distance_os', 'Distance OS', '20/20'); eye_input($exam, 'ucva_near_od', 'Near OD', 'J1'); eye_input($exam, 'ucva_near_os', 'Near OS', 'J1'); ?>
                        <div class="col-sm-12 eye-pair-title">Best Corrected Visual Acuity (BCVA)</div><?php eye_input($exam, 'bcva_distance_od', 'Distance OD', '20/20'); eye_input($exam, 'bcva_distance_os', 'Distance OS', '20/20'); eye_input($exam, 'bcva_near_od', 'Near OD', 'J1'); eye_input($exam, 'bcva_near_os', 'Near OS', 'J1'); ?>
                        <div class="col-sm-12 eye-pair-title">Pinhole Visual Acuity</div><?php eye_input($exam, 'pinhole_od', 'OD', '20/20'); eye_input($exam, 'pinhole_os', 'OS', '20/20'); ?>
                    </div>
                    <div class="eye-section row" data-eye-section="refraction"><?php eye_input($exam, 'refraction_od', 'OD (Sphere / Cylinder / Axis)', '+0.50 / -0.75 x 90'); eye_input($exam, 'refraction_os', 'OS (Sphere / Cylinder / Axis)', '+0.25 / -0.50 x 80'); ?></div>
                    <div class="eye-section row" data-eye-section="iop">
                        <?php eye_input($exam, 'iop_od', 'OD (mmHg)', '', 'number'); eye_input($exam, 'iop_os', 'OS (mmHg)', '', 'number'); ?>
                        <div class="form-group col-sm-6"><label>Method</label><select class="form-control" name="iop_method"><?php foreach (array('Goldmann Applanation','Tonopen','iCare Rebound','Non-Contact (Air Puff)','Digital Palpation') as $method) { ?><option <?php echo eye_value($exam, 'iop_method', 'Goldmann Applanation') === $method ? 'selected' : ''; ?>><?php echo $method; ?></option><?php } ?></select></div>
                    </div>
                    <div class="eye-section row" data-eye-section="anterior"><?php eye_textarea($exam, 'anterior_segment_od', 'OD Findings'); eye_textarea($exam, 'anterior_segment_os', 'OS Findings'); ?></div>
                    <div class="eye-section row" data-eye-section="fundus"><?php eye_textarea($exam, 'fundus_od', 'OD Findings'); eye_textarea($exam, 'fundus_os', 'OS Findings'); ?></div>
                    <div class="eye-section row" data-eye-section="assessment">
                        <div class="col-sm-12">
                            <div class="assessment-heading"><strong>Diagnosis</strong><button type="button" class="btn btn-default btn-sm" id="addDiagnosis"><i class="fa fa-plus"></i> Add Diagnosis</button></div>
                            <div id="diagnosisRows">
                                <?php foreach ($diagnoses as $index => $diagnosis) { ?>
                                <div class="assessment-row diagnosis-row row">
                                    <div class="form-group col-sm-3"><label>ICD Code</label><input class="form-control" name="diagnoses[<?php echo $index; ?>][icd_code]" value="<?php echo html_escape(isset($diagnosis['icd_code']) ? $diagnosis['icd_code'] : ''); ?>" placeholder="H40.10"></div>
                                    <div class="form-group col-sm-5"><label>Description</label><input class="form-control" name="diagnoses[<?php echo $index; ?>][description]" value="<?php echo html_escape(isset($diagnosis['description']) ? $diagnosis['description'] : ''); ?>" placeholder="Primary open-angle glaucoma"></div>
                                    <div class="form-group col-sm-3"><label>Eye</label><select class="form-control" name="diagnoses[<?php echo $index; ?>][eye]"><?php foreach (array('OD' => 'OD (Right)', 'OS' => 'OS (Left)', 'OU' => 'OU (Both)') as $value => $label) { ?><option value="<?php echo $value; ?>" <?php echo (isset($diagnosis['eye']) ? $diagnosis['eye'] : 'OU') === $value ? 'selected' : ''; ?>><?php echo $label; ?></option><?php } ?></select></div>
                                    <div class="col-sm-1"><button type="button" class="btn btn-danger btn-sm remove-row" title="Remove diagnosis"><i class="fa fa-trash"></i></button></div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="form-group"><label>Plan</label><textarea class="form-control" rows="4" name="plan" placeholder="Treatment plan, further workup, referrals..."><?php echo html_escape(eye_value($exam, 'plan')); ?></textarea></div>
                            <div class="assessment-heading"><strong>Medications</strong><button type="button" class="btn btn-default btn-sm" id="addMedication"><i class="fa fa-plus"></i> Add Medication</button></div>
                            <div id="medicationRows">
                                <?php foreach ($medications as $index => $medication) { ?>
                                <div class="assessment-row medication-row row">
                                    <div class="form-group col-sm-3"><label>Medication</label><input class="form-control" name="medications[<?php echo $index; ?>][medication]" value="<?php echo html_escape(isset($medication['medication']) ? $medication['medication'] : ''); ?>" placeholder="Latanoprost"></div>
                                    <div class="form-group col-sm-2"><label>Dosage</label><input class="form-control" name="medications[<?php echo $index; ?>][dosage]" value="<?php echo html_escape(isset($medication['dosage']) ? $medication['dosage'] : ''); ?>" placeholder="0.005%"></div>
                                    <div class="form-group col-sm-2"><label>Frequency</label><input class="form-control" name="medications[<?php echo $index; ?>][frequency]" value="<?php echo html_escape(isset($medication['frequency']) ? $medication['frequency'] : ''); ?>" placeholder="Once daily"></div>
                                    <div class="form-group col-sm-2"><label>Duration</label><input class="form-control" name="medications[<?php echo $index; ?>][duration]" value="<?php echo html_escape(isset($medication['duration']) ? $medication['duration'] : ''); ?>" placeholder="3 months"></div>
                                    <div class="form-group col-sm-2"><label>Eye</label><select class="form-control" name="medications[<?php echo $index; ?>][eye]"><?php foreach (array('OD','OS','OU') as $eye) { ?><option value="<?php echo $eye; ?>" <?php echo (isset($medication['eye']) ? $medication['eye'] : 'OU') === $eye ? 'selected' : ''; ?>><?php echo $eye; ?></option><?php } ?></select></div>
                                    <div class="col-sm-1"><button type="button" class="btn btn-danger btn-sm remove-row" title="Remove medication"><i class="fa fa-trash"></i></button></div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="follow-up-panel">
                                <div class="checkbox"><label><input type="checkbox" id="followUpRecommended" name="follow_up_recommended" value="1" <?php echo $follow_recommended ? 'checked' : ''; ?>> <strong>Follow-up Recommended</strong></label></div>
                                <div id="followUpFields" class="row">
                                    <div class="form-group col-sm-4"><label>Interval</label><select class="form-control" name="follow_up_interval"><?php foreach (array('1 Week','2 Weeks','1 Month','3 Months','6 Months','1 Year') as $interval) { ?><option <?php echo eye_value($exam, 'follow_up_interval', '1 Month') === $interval ? 'selected' : ''; ?>><?php echo $interval; ?></option><?php } ?></select></div>
                                    <div class="form-group col-sm-8"><label>Reason</label><input class="form-control" name="follow_up_reason" value="<?php echo html_escape(eye_value($exam, 'follow_up_reason')); ?>" placeholder="IOP check, visual field..."></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer text-right"><a href="<?php echo site_url('admin/eyeexam'); ?>" class="btn btn-default">Cancel</a> <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Save Examination</button></div>
            </div>
        </form>
    </section>
</div>
<script>
(function($){
    var diagnosisIndex=<?php echo count($diagnoses); ?>, medicationIndex=<?php echo count($medications); ?>;
    function diagnosisRow(i){ return '<div class="assessment-row diagnosis-row row"><div class="form-group col-sm-3"><label>ICD Code</label><input class="form-control" name="diagnoses['+i+'][icd_code]" placeholder="H40.10"></div><div class="form-group col-sm-5"><label>Description</label><input class="form-control" name="diagnoses['+i+'][description]" placeholder="Primary open-angle glaucoma"></div><div class="form-group col-sm-3"><label>Eye</label><select class="form-control" name="diagnoses['+i+'][eye]"><option value="OD">OD (Right)</option><option value="OS">OS (Left)</option><option value="OU" selected>OU (Both)</option></select></div><div class="col-sm-1"><button type="button" class="btn btn-danger btn-sm remove-row" title="Remove diagnosis"><i class="fa fa-trash"></i></button></div></div>'; }
    function medicationRow(i){ return '<div class="assessment-row medication-row row"><div class="form-group col-sm-3"><label>Medication</label><input class="form-control" name="medications['+i+'][medication]" placeholder="Latanoprost"></div><div class="form-group col-sm-2"><label>Dosage</label><input class="form-control" name="medications['+i+'][dosage]" placeholder="0.005%"></div><div class="form-group col-sm-2"><label>Frequency</label><input class="form-control" name="medications['+i+'][frequency]" placeholder="Once daily"></div><div class="form-group col-sm-2"><label>Duration</label><input class="form-control" name="medications['+i+'][duration]" placeholder="3 months"></div><div class="form-group col-sm-2"><label>Eye</label><select class="form-control" name="medications['+i+'][eye]"><option>OD</option><option>OS</option><option selected>OU</option></select></div><div class="col-sm-1"><button type="button" class="btn btn-danger btn-sm remove-row" title="Remove medication"><i class="fa fa-trash"></i></button></div></div>'; }
    $('#addDiagnosis').on('click',function(){ $('#diagnosisRows').append(diagnosisRow(diagnosisIndex++)); });
    $('#addMedication').on('click',function(){ $('#medicationRows').append(medicationRow(medicationIndex++)); });
    $(document).on('click','.remove-row',function(){ $(this).closest('.assessment-row').remove(); });
    $('#followUpRecommended').on('change',function(){ $('#followUpFields').toggle(this.checked).find(':input').prop('disabled',!this.checked); }).trigger('change');
    $('[data-eye-tab]').on('click', function(){
        $('[data-eye-tab]').parent().removeClass('active'); $(this).parent().addClass('active');
        $('.eye-section').removeClass('active'); $('[data-eye-section="'+$(this).data('eye-tab')+'"]').addClass('active');
    });
    $('#eyeExamForm').on('submit', function(){
        var invalid=$(this).find(':invalid').first(); if(invalid.length){ var section=invalid.closest('.eye-section').data('eye-section'); if(section){ $('[data-eye-tab="'+section+'"]').click(); } }
    });
})(jQuery);
</script>
