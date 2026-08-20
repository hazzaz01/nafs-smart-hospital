<?php
$optical = isset($optical_prescription) && is_array($optical_prescription) ? $optical_prescription : array();
$optical_value = function ($key, $default = '') use ($optical) {
    return isset($optical[$key]) && $optical[$key] !== '' ? $optical[$key] : $default;
};
$optical_coatings = !empty($optical['coatings']) ? json_decode($optical['coatings'], true) : array();
if (!is_array($optical_coatings)) {
    $optical_coatings = array();
}
$optical_options = array(
    'sphere' => array('+0.00', '+0.25', '+0.50', '+1.00', '-0.25', '-0.50', '-1.00', '-2.00', '-3.00'),
    'cylinder' => array('0.00', '-0.25', '-0.50', '-0.75', '-1.00', '-1.50', '-2.00', '-3.00'),
    'axis' => array('0', '30', '45', '60', '90', '120', '135', '150', '180'),
    'add' => array('+0.00', '+0.75', '+1.00', '+1.50', '+2.00', '+2.50', '+3.00'),
);
$optical_coating_choices = array('Anti Reflective', 'Blue Light', 'Photochromic', 'Scratch Resistant', 'Hydrophobic', 'UV Protection');
?>
<input type="hidden" name="optical_prescription_enabled" value="1">
<div class="box box-info optical-prescription-panel" style="margin: 15px 0; border-top-color: #00a99d;">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-eye text-aqua"></i> Optical Prescription</h3>
        <div class="box-tools pull-right"><span class="label label-info">Doctor feature</span></div>
    </div>
    <div class="box-body">
        <div class="form-group">
            <label>Prescription Type</label>
            <div class="btn-group optical-prescription-type" data-toggle="buttons">
                <?php foreach (array('spectacles' => 'Spectacles', 'contact_lens' => 'Contact Lens', 'both' => 'Both') as $value => $label) { ?>
                    <label class="btn btn-default <?php echo $optical_value('prescription_type', 'spectacles') === $value ? 'active' : ''; ?>">
                        <input type="radio" name="optical_prescription_type" value="<?php echo $value; ?>" <?php echo $optical_value('prescription_type', 'spectacles') === $value ? 'checked' : ''; ?>> <?php echo $label; ?>
                    </label>
                <?php } ?>
            </div>
        </div>

        <?php foreach (array('od' => 'OD (Right Eye)', 'os' => 'OS (Left Eye)') as $eye => $eye_label) { ?>
            <h5 style="font-weight:600; margin:16px 0 8px;"><?php echo $eye_label; ?></h5>
            <?php foreach (array('sphere' => 'Sphere', 'cylinder' => 'Cylinder', 'axis' => 'Axis', 'add' => 'Add') as $field => $label) { ?>
                <div class="col-md-2 col-sm-4 col-xs-6 form-group">
                    <label><?php echo $label; ?></label>
                    <select class="form-control" name="optical_<?php echo $eye . '_' . $field; ?>">
                        <?php foreach ($optical_options[$field] as $option) { $display = $field === 'axis' ? $option . '&deg;' : $option; ?>
                            <option value="<?php echo $option; ?>" <?php echo $optical_value($eye . '_' . $field, $field === 'axis' ? '180' : ($field === 'cylinder' ? '0.00' : '+0.00')) === $option ? 'selected' : ''; ?>><?php echo $display; ?></option>
                        <?php } ?>
                    </select>
                </div>
            <?php } ?>
            <div class="col-md-2 col-sm-4 col-xs-6 form-group"><label>PD</label><input class="form-control" name="optical_<?php echo $eye; ?>_pd" value="<?php echo html_escape($optical_value($eye . '_pd', '32')); ?>"></div>
            <div class="col-md-2 col-sm-4 col-xs-6 form-group"><label>Prism</label><input class="form-control" name="optical_<?php echo $eye; ?>_prism" value="<?php echo html_escape($optical_value($eye . '_prism', '0')); ?>"></div>
            <div class="clearfix"></div>
        <?php } ?>

        <div class="optical-spectacle-details">
            <h5 style="font-weight:600; margin:16px 0 8px;">Spectacle Details</h5>
            <div class="col-md-4 form-group"><label>Lens Type</label><select class="form-control" name="optical_lens_type"><?php foreach (array('Single Vision', 'Bifocal', 'Progressive', 'Reading') as $option) { ?><option <?php echo $optical_value('lens_type', 'Single Vision') === $option ? 'selected' : ''; ?>><?php echo $option; ?></option><?php } ?></select></div>
            <div class="col-md-4 form-group"><label>Lens Material</label><select class="form-control" name="optical_lens_material"><?php foreach (array('CR-39', 'Polycarbonate', 'High Index 1.67', 'Trivex') as $option) { ?><option <?php echo $optical_value('lens_material', 'CR-39') === $option ? 'selected' : ''; ?>><?php echo $option; ?></option><?php } ?></select></div>
            <div class="col-md-4 form-group"><label>Frame Type</label><select class="form-control" name="optical_frame_type"><?php foreach (array('Full Rim', 'Semi Rimless', 'Rimless') as $option) { ?><option <?php echo $optical_value('frame_type', 'Full Rim') === $option ? 'selected' : ''; ?>><?php echo $option; ?></option><?php } ?></select></div>
            <div class="clearfix"></div>
            <div class="form-group"><label>Coatings</label><br><?php foreach ($optical_coating_choices as $coating) { ?><label class="checkbox-inline"><input type="checkbox" name="optical_coatings[]" value="<?php echo $coating; ?>" <?php echo in_array($coating, $optical_coatings, true) ? 'checked' : ''; ?>> <?php echo $coating; ?></label><?php } ?></div>
        </div>
        <div class="optical-contact-lens-details">
            <h5 style="font-weight:600; margin:16px 0 8px;">Contact Lens Details</h5>
            <div class="col-md-3 col-sm-6 form-group"><label>Brand</label><input class="form-control" name="optical_contact_lens_brand" value="<?php echo html_escape($optical_value('contact_lens_brand')); ?>" placeholder="e.g., Acuvue Oasys"></div>
            <div class="col-md-3 col-sm-6 form-group"><label>Base Curve</label><input class="form-control" name="optical_contact_lens_base_curve" value="<?php echo html_escape($optical_value('contact_lens_base_curve', '8.6')); ?>"></div>
            <div class="col-md-3 col-sm-6 form-group"><label>Diameter</label><input class="form-control" name="optical_contact_lens_diameter" value="<?php echo html_escape($optical_value('contact_lens_diameter', '14.2')); ?>"></div>
            <div class="col-md-3 col-sm-6 form-group"><label>Replacement</label><select class="form-control" name="optical_contact_lens_replacement"><?php foreach (array('Daily', 'Bi-weekly', 'Monthly', 'Yearly') as $option) { ?><option <?php echo $optical_value('contact_lens_replacement', 'Monthly') === $option ? 'selected' : ''; ?>><?php echo $option; ?></option><?php } ?></select></div>
            <div class="clearfix"></div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group"><label>Validity (months)</label><select class="form-control" name="optical_validity_months"><?php foreach (array(3, 6, 12, 18, 24) as $months) { ?><option value="<?php echo $months; ?>" <?php echo (int) $optical_value('validity_months', 12) === $months ? 'selected' : ''; ?>><?php echo $months; ?> months</option><?php } ?></select></div>
            <div class="col-md-8 form-group"><label>Notes</label><textarea class="form-control" rows="2" name="optical_notes" placeholder="Additional notes..."><?php echo html_escape($optical_value('notes')); ?></textarea></div>
        </div>
    </div>
</div>
<script>
$(document).off('change.opticalPrescription', 'input[name="optical_prescription_type"]').on('change.opticalPrescription', 'input[name="optical_prescription_type"]', function () {
    var type = $(this).val();
    $('.optical-spectacle-details').toggle(type !== 'contact_lens');
    $('.optical-contact-lens-details').toggle(type !== 'spectacles');
}).trigger('change');
</script>
