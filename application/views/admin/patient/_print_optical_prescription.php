<?php if (!empty($optical_prescription)) {
    $optical = $optical_prescription;
    $coatings = !empty($optical['coatings']) ? json_decode($optical['coatings'], true) : array();
    if (!is_array($coatings)) {
        $coatings = array();
    }
?>
    <div class="divider mb-10 mt-10"></div>
    <table cellpadding="4" cellspacing="0" width="100%" style="border: 1px solid #ddd;">
        <tr>
            <th colspan="7" style="padding: 5px; text-align: left;">Optical Prescription (<?php echo html_escape(ucwords(str_replace('_', ' ', $optical['prescription_type']))); ?>)</th>
        </tr>
        <tr>
            <th style="padding: 4px;"></th><th>Sphere</th><th>Cylinder</th><th>Axis</th><th>Add</th><th>PD</th><th>Prism</th>
        </tr>
        <?php foreach (array('od' => 'OD (Right Eye)', 'os' => 'OS (Left Eye)') as $eye => $label) { ?>
            <tr>
                <th style="padding: 4px; text-align: left;"><?php echo $label; ?></th>
                <td><?php echo html_escape($optical[$eye . '_sphere']); ?></td>
                <td><?php echo html_escape($optical[$eye . '_cylinder']); ?></td>
                <td><?php echo html_escape($optical[$eye . '_axis']); ?>&deg;</td>
                <td><?php echo html_escape($optical[$eye . '_add']); ?></td>
                <td><?php echo html_escape($optical[$eye . '_pd']); ?></td>
                <td><?php echo html_escape($optical[$eye . '_prism']); ?></td>
            </tr>
        <?php } ?>
        <?php if ($optical['prescription_type'] !== 'contact_lens') { ?>
            <tr><td colspan="7" style="padding: 4px;"><b>Lens:</b> <?php echo html_escape($optical['lens_type']); ?> &nbsp; <b>Material:</b> <?php echo html_escape($optical['lens_material']); ?> &nbsp; <b>Frame:</b> <?php echo html_escape($optical['frame_type']); ?><?php if (!empty($coatings)) { ?> &nbsp; <b>Coatings:</b> <?php echo html_escape(implode(', ', $coatings)); ?><?php } ?></td></tr>
        <?php } ?>
        <?php if ($optical['prescription_type'] !== 'spectacles') { ?>
            <tr><td colspan="7" style="padding: 4px;"><b>Contact lens:</b> <?php echo html_escape($optical['contact_lens_brand']); ?> &nbsp; <b>Base curve:</b> <?php echo html_escape($optical['contact_lens_base_curve']); ?> &nbsp; <b>Diameter:</b> <?php echo html_escape($optical['contact_lens_diameter']); ?> &nbsp; <b>Replacement:</b> <?php echo html_escape($optical['contact_lens_replacement']); ?></td></tr>
        <?php } ?>
        <?php if (!empty($optical['notes'])) { ?><tr><td colspan="7" style="padding: 4px;"><b>Notes:</b> <?php echo nl2br(html_escape($optical['notes'])); ?></td></tr><?php } ?>
    </table>
<?php } ?>
