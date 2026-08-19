<?php
function eye_show($value) { return $value === null || $value === '' ? '<span class="text-muted">Not recorded</span>' : nl2br(html_escape($value)); }
$diagnoses = json_decode(isset($exam['diagnoses_json']) ? $exam['diagnoses_json'] : '', true);
if (!is_array($diagnoses)) { $diagnoses = array(); }
if (!$diagnoses && !empty($exam['diagnosis'])) { $diagnoses[] = array('icd_code' => '', 'description' => $exam['diagnosis'], 'eye' => 'OU'); }
$medications = json_decode(isset($exam['medications_json']) ? $exam['medications_json'] : '', true);
if (!is_array($medications)) { $medications = array(); }
?>
<style>.eye-detail dt{color:#777;margin-top:10px}.eye-detail dd{font-size:15px}.eye-eye-card{border-top:3px solid #3c8dbc}.eye-print-title{display:none}@media print{.main-header,.main-sidebar,.content-header .btn,.box-tools,.no-print{display:none!important}.content-wrapper{margin-left:0!important}.eye-print-title{display:block}}</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-eye"></i> Eye Examination</h1>
        <div class="pull-right no-print" style="margin-top:-30px"><button onclick="window.print()" class="btn btn-default"><i class="fa fa-print"></i> Print</button> <a href="<?php echo site_url('admin/eyeexam/edit/' . $exam['id']); ?>" class="btn btn-primary"><i class="fa fa-pencil"></i> Edit</a></div>
    </section>
    <section class="content">
        <?php echo $this->session->flashdata('msg'); ?>
        <h2 class="eye-print-title">Eye Examination Record</h2>
        <div class="box box-primary"><div class="box-body"><div class="row"><div class="col-sm-4"><strong>Patient</strong><br><?php echo html_escape($exam['patient_name']); ?> (ID: <?php echo (int)$exam['patient_id']; ?>)</div><div class="col-sm-3"><strong>Patient Details</strong><br><?php echo html_escape($exam['gender'] . ', ' . $exam['age'] . ' years'); ?></div><div class="col-sm-3"><strong>Exam Date</strong><br><?php echo html_escape(date('d M Y, h:i A', strtotime($exam['exam_date']))); ?></div><div class="col-sm-2"><strong>Doctor</strong><br><?php echo html_escape(trim($exam['doctor_name'].' '.$exam['doctor_surname'])); ?></div></div></div></div>
        <div class="row">
            <div class="col-sm-6"><div class="box eye-eye-card"><div class="box-header"><h3 class="box-title">Clinical History</h3></div><div class="box-body"><dl class="eye-detail"><dt>Chief Complaint</dt><dd><?php echo eye_show($exam['chief_complaint']); ?></dd><dt>History of Present Illness</dt><dd><?php echo eye_show($exam['history_present_illness']); ?></dd></dl></div></div></div>
            <div class="col-sm-6"><div class="box eye-eye-card"><div class="box-header"><h3 class="box-title">Visual Acuity (<?php echo html_escape($exam['va_scale']); ?>)</h3></div><div class="box-body"><table class="table table-bordered"><tr><th></th><th>OD</th><th>OS</th></tr><tr><td>UCVA Distance</td><td><?php echo eye_show($exam['ucva_distance_od']); ?></td><td><?php echo eye_show($exam['ucva_distance_os']); ?></td></tr><tr><td>BCVA Distance</td><td><?php echo eye_show($exam['bcva_distance_od']); ?></td><td><?php echo eye_show($exam['bcva_distance_os']); ?></td></tr><tr><td>Pinhole</td><td><?php echo eye_show($exam['pinhole_od']); ?></td><td><?php echo eye_show($exam['pinhole_os']); ?></td></tr></table></div></div></div>
        </div>
        <div class="row">
            <div class="col-sm-6"><div class="box eye-eye-card"><div class="box-header"><h3 class="box-title">Refraction & IOP</h3></div><div class="box-body"><table class="table table-bordered"><tr><th></th><th>OD</th><th>OS</th></tr><tr><td>Refraction</td><td><?php echo eye_show($exam['refraction_od']); ?></td><td><?php echo eye_show($exam['refraction_os']); ?></td></tr><tr><td>IOP (mmHg)</td><td><?php echo eye_show($exam['iop_od']); ?></td><td><?php echo eye_show($exam['iop_os']); ?></td></tr></table><small class="text-muted">Method: <?php echo eye_show($exam['iop_method']); ?></small></div></div></div>
            <div class="col-sm-6"><div class="box eye-eye-card"><div class="box-header"><h3 class="box-title">Assessment & Plan</h3></div><div class="box-body">
                <strong>Diagnoses</strong>
                <?php if ($diagnoses) { ?><table class="table table-bordered table-condensed"><thead><tr><th>ICD Code</th><th>Description</th><th>Eye</th></tr></thead><tbody><?php foreach ($diagnoses as $diagnosis) { ?><tr><td><?php echo eye_show(isset($diagnosis['icd_code']) ? $diagnosis['icd_code'] : ''); ?></td><td><?php echo eye_show(isset($diagnosis['description']) ? $diagnosis['description'] : ''); ?></td><td><?php echo html_escape(isset($diagnosis['eye']) ? $diagnosis['eye'] : 'OU'); ?></td></tr><?php } ?></tbody></table><?php } else { ?><p><?php echo eye_show(null); ?></p><?php } ?>
                <dl class="eye-detail"><dt>Plan</dt><dd><?php echo eye_show($exam['plan']); ?></dd><dt>Follow-up</dt><dd><?php echo !empty($exam['follow_up_recommended']) ? html_escape(($exam['follow_up_interval'] ?: 'Recommended') . ($exam['follow_up_reason'] ? ' — ' . $exam['follow_up_reason'] : '')) : 'Not recommended'; ?></dd></dl>
            </div></div></div>
        </div>
        <div class="box eye-eye-card"><div class="box-header"><h3 class="box-title">Medications</h3></div><div class="box-body">
            <?php if ($medications) { ?><div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Medication</th><th>Dosage</th><th>Frequency</th><th>Duration</th><th>Eye</th></tr></thead><tbody><?php foreach ($medications as $medication) { ?><tr><td><?php echo eye_show(isset($medication['medication']) ? $medication['medication'] : ''); ?></td><td><?php echo eye_show(isset($medication['dosage']) ? $medication['dosage'] : ''); ?></td><td><?php echo eye_show(isset($medication['frequency']) ? $medication['frequency'] : ''); ?></td><td><?php echo eye_show(isset($medication['duration']) ? $medication['duration'] : ''); ?></td><td><?php echo html_escape(isset($medication['eye']) ? $medication['eye'] : 'OU'); ?></td></tr><?php } ?></tbody></table></div><?php } else { ?><p class="text-muted">No medications prescribed.</p><?php } ?>
        </div></div>
        <div class="row"><div class="col-sm-6"><div class="box"><div class="box-header"><h3 class="box-title">Anterior Segment</h3></div><div class="box-body"><strong>OD</strong><p><?php echo eye_show($exam['anterior_segment_od']); ?></p><strong>OS</strong><p><?php echo eye_show($exam['anterior_segment_os']); ?></p></div></div></div><div class="col-sm-6"><div class="box"><div class="box-header"><h3 class="box-title">Fundus</h3></div><div class="box-body"><strong>OD</strong><p><?php echo eye_show($exam['fundus_od']); ?></p><strong>OS</strong><p><?php echo eye_show($exam['fundus_os']); ?></p></div></div></div></div>
        <a class="btn btn-default no-print" href="<?php echo site_url('admin/eyeexam'); ?>"><i class="fa fa-arrow-left"></i> Back to examinations</a>
    </section>
</div>
