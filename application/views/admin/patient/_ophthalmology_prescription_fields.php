<?php
$optical = isset($optical_prescription) && is_array($optical_prescription) ? $optical_prescription : array();
$clinical = !empty($optical['ophthalmology_data']) ? json_decode($optical['ophthalmology_data'], true) : array();
if (!is_array($clinical)) { $clinical = array(); }
$value = function ($key, $default = '') use ($optical) { return isset($optical[$key]) ? $optical[$key] : $default; };
$clinical_value = function ($key) use ($clinical) { return isset($clinical[$key]) ? $clinical[$key] : ''; };
$doctor = $this->staff_model->getStaffByID((int) $this->customlib->getStaffID());
$doctor_name = $doctor ? trim($doctor->name . ' ' . $doctor->surname) : '';
$patient = isset($eye_patient) && is_array($eye_patient) ? $eye_patient : array();
$patient_name = isset($patient['patient_name']) ? $patient['patient_name'] : '';
$patient_age = isset($patient['age']) ? $patient['age'] : '';
$patient_gender = isset($patient['gender']) ? $patient['gender'] : '';
$patient_phone = isset($patient['mobileno']) ? $patient['mobileno'] : '';
$patient_id = isset($patient['id']) ? $patient['id'] : (isset($patient['patient_id']) ? $patient['patient_id'] : '');
$has_ophthalmology_data = !empty($optical['ophthalmology_data']);
?>
<div class="col-sm-12 ophthalmology-prescription-choice" style="margin:10px 0 4px;">
    <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-default ophthalmology-mode-standard active"><i class="fa fa-file-text-o"></i> Standard Prescription</button>
        <button type="button" class="btn btn-info ophthalmology-mode-eye"><i class="fa fa-eye"></i> Ophthalmology Prescription</button>
    </div>
</div>
<input type="hidden" name="optical_prescription_enabled" value="0">
<input type="hidden" name="optical_prescription_type" value="spectacles">
<input type="hidden" name="optical_validity_months" value="12">
<input type="hidden" name="optical_lens_type" value="">
<input type="hidden" name="optical_lens_material" value="">
<input type="hidden" name="optical_frame_type" value="">
<input type="hidden" name="optical_notes" value="">
<input type="hidden" name="optical_od_pd" value="">
<input type="hidden" name="optical_od_prism" value="">
<input type="hidden" name="optical_os_pd" value="">
<input type="hidden" name="optical_os_prism" value="">
<style>
.ophthalmology-pad{--paper:#f7f9f8;--ink:#16262a;--soft:#4b5f62;--teal:#0c4a4e;--teal2:#146b6e;--pale:#e4f1ef;--line:#b7cbc7;--softline:#dee8e6;background:var(--paper);color:var(--ink);font-family:Arial,sans-serif;margin:12px -5px;padding:0;box-shadow:0 1px 8px rgba(12,74,78,.2)}
.ophthalmology-pad *{box-sizing:border-box}.ophthalmology-pad .oph-head{background:var(--teal);color:#eff6f5;padding:25px 38px 20px;display:flex;justify-content:space-between;position:relative;overflow:hidden}.ophthalmology-pad .oph-brand{font:600 24px Georgia,serif}.ophthalmology-pad .oph-sub{font-size:10px;letter-spacing:.5px;text-transform:uppercase;color:#bfdbd8;margin-top:4px}.ophthalmology-pad .oph-address{text-align:right;font-size:10px;line-height:1.6;color:#cfe6e3}.ophthalmology-pad .oph-eye{position:absolute;right:40px;bottom:-2px;font:24px Georgia,serif;opacity:.2;letter-spacing:7px}.ophthalmology-pad .oph-doctor{display:flex;justify-content:space-between;align-items:center;padding:14px 38px;background:var(--pale);border-bottom:1px solid var(--softline)}.ophthalmology-pad .oph-doctor-name{font:600 15px Georgia,serif;color:var(--teal)}.ophthalmology-pad .oph-meta{font-size:10px;color:var(--soft);margin-top:3px}.ophthalmology-pad .oph-date{font-size:10px;color:var(--soft);width:160px}.ophthalmology-pad label{display:block;font-size:8px;letter-spacing:.55px;text-transform:uppercase;color:var(--soft);margin:0 0 3px}.ophthalmology-pad input,.ophthalmology-pad textarea{width:100%;border:0;border-bottom:1px solid var(--line);background:transparent;color:var(--ink);font:12px Arial,sans-serif;outline:none;padding:2px 0}.ophthalmology-pad textarea{resize:vertical;min-height:28px;line-height:1.35}.oph-patient{display:grid;grid-template-columns:2.3fr 1fr 1fr 1.5fr;gap:12px;padding:13px 38px;border-bottom:2px solid var(--teal)}.oph-body{display:grid;grid-template-columns:39% 61%;min-height:490px}.oph-exam{padding:16px 22px 16px 38px;border-right:1px solid var(--softline)}.oph-rx{padding:16px 38px 16px 24px;background:repeating-linear-gradient(to bottom,transparent 0,transparent 32px,var(--softline) 33px);background-position:0 87px}.oph-title{font-size:9px;font-weight:600;letter-spacing:1.1px;text-transform:uppercase;color:var(--teal2);border-bottom:1px solid var(--teal2);padding-bottom:5px;margin:0 0 10px}.oph-gap{margin-top:20px}.oph-table{width:100%;border-collapse:collapse;font-size:10px;margin:3px 0 9px}.oph-table th,.oph-table td{border:1px solid var(--softline);padding:3px;text-align:center}.oph-table th,.oph-table .oph-rowhead{background:var(--pale);color:var(--teal);font-size:8px;font-weight:600}.oph-table input{font-size:10px;text-align:center;border:0;padding:0}.oph-iop{display:flex;gap:16px;margin:8px 0;font-size:10px}.oph-iop div{display:flex;align-items:center;gap:4px}.oph-iop input{width:42px}.oph-rx-symbol{font:600 34px Georgia,serif;color:#ad7a2e;vertical-align:middle}.oph-rx-note{font-size:9px;color:var(--soft);letter-spacing:.6px;text-transform:uppercase}.oph-rx textarea{height:385px;border:0;background:transparent;line-height:33px;padding-top:12px}.oph-footer{border-top:2px solid var(--teal);padding:15px 38px 22px}.oph-footer-top{display:grid;grid-template-columns:1fr 1fr;gap:35px}.oph-footer textarea{height:55px}.oph-follow{display:flex;align-items:center;gap:8px;font-size:10px}.oph-follow input{width:155px;border:1px solid var(--line);padding:5px}.oph-sign{display:flex;justify-content:space-between;align-items:flex-end;margin-top:20px;font-size:9px;color:var(--soft)}.oph-signline{width:200px;border-bottom:1px solid var(--ink);height:40px;text-align:center;padding-top:44px;font-size:8px;letter-spacing:.4px;text-transform:uppercase}.oph-foot{text-align:center;border-top:1px solid var(--softline);margin-top:24px;padding-top:12px;font-size:8px;color:var(--soft)}.oph-foot strong{color:var(--teal2)}
@media(max-width:850px){.ophthalmology-pad{min-width:760px}.ophthalmology-pad .oph-head,.oph-doctor{padding-left:25px!important;padding-right:25px!important}.oph-patient{padding-left:25px;padding-right:25px}.oph-exam{padding-left:25px}.oph-rx{padding-right:25px}.oph-footer{padding-left:25px;padding-right:25px}}
</style>
<div class="col-sm-12 ophthalmology-pad-container" style="display:none;"><div class="ophthalmology-pad">
    <div class="oph-head"><div><div class="oph-brand">RGC Eye Hospital</div><div class="oph-sub">Retina · Glaucoma · Cornea · Superspecialty Care</div></div><div class="oph-address">57/E Panthapath, East Side of BRB Hospital, Dhaka<br>Appointment: <strong>09610-947575</strong> · rgceyehospital.com</div><div class="oph-eye">E FP TOZ LPED</div></div>
    <div class="oph-doctor"><div><div class="oph-doctor-name">Dr. <?php echo html_escape($doctor_name); ?></div><div class="oph-meta">Ophthalmology Consultant</div></div><div class="oph-date"><label>Date</label><input type="text" value="<?php echo date($this->customlib->getHospitalDateFormat()); ?>" readonly></div></div>
    <div class="oph-patient"><div><label>Patient name</label><input type="text" value="<?php echo html_escape($patient_name); ?>" readonly></div><div><label>Age</label><input type="text" value="<?php echo html_escape($patient_age); ?>" readonly></div><div><label>Sex</label><input type="text" value="<?php echo html_escape($patient_gender); ?>" readonly></div><div><label>UHID / Phone</label><input type="text" value="<?php echo html_escape(trim($patient_id . ' / ' . $patient_phone, ' /')); ?>" readonly></div></div>
    <div class="oph-body"><div class="oph-exam">
        <div class="oph-title">Chief complaint</div><textarea name="ophthalmology_chief_complaint"><?php echo html_escape($clinical_value('chief_complaint')); ?></textarea>
        <div class="oph-title oph-gap">Visual acuity</div><table class="oph-table"><tr><th></th><th>Unaided</th><th>Aided</th></tr><tr><td class="oph-rowhead">OD</td><td><input name="ophthalmology_va_od_unaided" value="<?php echo html_escape($clinical_value('va_od_unaided')); ?>"></td><td><input name="ophthalmology_va_od_aided" value="<?php echo html_escape($clinical_value('va_od_aided')); ?>"></td></tr><tr><td class="oph-rowhead">OS</td><td><input name="ophthalmology_va_os_unaided" value="<?php echo html_escape($clinical_value('va_os_unaided')); ?>"></td><td><input name="ophthalmology_va_os_aided" value="<?php echo html_escape($clinical_value('va_os_aided')); ?>"></td></tr></table>
        <div class="oph-iop"><div><b>IOP OD</b><input name="ophthalmology_iop_od" value="<?php echo html_escape($clinical_value('iop_od')); ?>"></div><div><b>OS</b><input name="ophthalmology_iop_os" value="<?php echo html_escape($clinical_value('iop_os')); ?>"> mmHg</div></div>
        <div class="oph-title oph-gap">Refraction</div><table class="oph-table"><tr><th></th><th>Sph</th><th>Cyl</th><th>Axis</th><th>Add</th></tr><?php foreach (array('od'=>'OD','os'=>'OS') as $eye=>$label) { ?><tr><td class="oph-rowhead"><?php echo $label; ?></td><?php foreach (array('sphere','cylinder','axis','add') as $field) { ?><td><input name="optical_<?php echo $eye . '_' . $field; ?>" value="<?php echo html_escape($value($eye . '_' . $field)); ?>"></td><?php } ?></tr><?php } ?></table>
        <div class="oph-title oph-gap">Anterior segment</div><textarea name="ophthalmology_anterior_segment"><?php echo html_escape($clinical_value('anterior_segment')); ?></textarea>
        <div class="oph-title oph-gap">Fundus</div><textarea name="ophthalmology_fundus"><?php echo html_escape($clinical_value('fundus')); ?></textarea>
        <div class="oph-title oph-gap">Diagnosis</div><textarea name="ophthalmology_diagnosis"><?php echo html_escape($clinical_value('diagnosis')); ?></textarea>
    </div><div class="oph-rx"><div><span class="oph-rx-symbol">℞</span> <span class="oph-rx-note">Medication &amp; treatment plan</span></div><textarea name="ophthalmology_treatment_plan"><?php echo html_escape($clinical_value('treatment_plan')); ?></textarea></div></div>
    <div class="oph-footer"><div class="oph-footer-top"><div><div class="oph-title">Advice</div><textarea name="ophthalmology_advice"><?php echo html_escape($clinical_value('advice')); ?></textarea></div><div><div class="oph-title">Follow up</div><div class="oph-follow">Next visit: <input name="ophthalmology_follow_up" value="<?php echo html_escape($clinical_value('follow_up')); ?>"></div></div></div><div class="oph-sign"><span>This prescription is valid for the diagnosis stated above.<br>Please bring this sheet on your next visit.</span><span class="oph-signline">Doctor's signature &amp; seal</span></div><div class="oph-foot"><strong>RGC Eye Hospital</strong> — Retina Glaucoma Center &amp; Superspecialty Eye Hospital | rgceyehospital.com | 09610-947575</div></div>
</div></div>
<script>
$(function () {
    var pad = $('.ophthalmology-pad').last();
    var padColumn = pad.closest('.col-sm-12');
    var choice = pad.closest('.row').find('.ophthalmology-prescription-choice').first();
    var standardFields = function () {
        return padColumn.prevAll().not(choice).add(padColumn.nextAll()).add(pad.closest('.col-sm-9').siblings('.col-sm-3'));
    };
    var switchMode = function (eyeMode) {
        padColumn.toggle(eyeMode);
        standardFields().toggle(!eyeMode).find(':input').prop('disabled', eyeMode);
        padColumn.find(':input').prop('disabled', !eyeMode);
        pad.closest('.row').find('input[name="optical_prescription_enabled"]').prop('disabled', false).val(eyeMode ? '1' : '0');
        choice.find('.ophthalmology-mode-standard').toggleClass('active', !eyeMode);
        choice.find('.ophthalmology-mode-eye').toggleClass('active', eyeMode);
    };
    padColumn.find(':input').prop('disabled', true);
    choice.on('click', '.ophthalmology-mode-standard', function () { switchMode(false); });
    choice.on('click', '.ophthalmology-mode-eye', function () { switchMode(true); });
    switchMode(<?php echo $has_ophthalmology_data ? 'true' : 'false'; ?>);
});
</script>
