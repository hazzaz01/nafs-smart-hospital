<?php
$counts = array('total' => 0, 'available' => 0, 'occupied' => 0, 'unused' => 0);
$bedsByGroup = array();
foreach ((array) $bedlist as $bed) {
    $status = $bed['is_active'] === 'yes' ? 'available' : ($bed['is_active'] === 'unused' ? 'unused' : 'occupied');
    $bed['_clinical_status'] = $status;
    $bedsByGroup[$bed['bedgroupid']][] = $bed;
    $counts['total']++;
    $counts[$status]++;
}
$occupancy = $counts['total'] ? round(($counts['occupied'] / $counts['total']) * 100) : 0;
?>
<div class="bsm-summary">
    <div class="bsm-stat"><div class="bsm-stat-ic total"><i class="fas fa-bed"></i></div><div class="bsm-stat-text"><span class="bsm-count"><?php echo $counts['total']; ?></span><span class="bsm-label">Total</span></div></div>
    <div class="bsm-stat"><div class="bsm-stat-ic avail"><i class="fas fa-check-circle"></i></div><div class="bsm-stat-text"><span class="bsm-count bsm-count-green"><?php echo $counts['available']; ?></span><span class="bsm-label">Available</span></div></div>
    <div class="bsm-stat"><div class="bsm-stat-ic occup"><i class="fas fa-user"></i></div><div class="bsm-stat-text"><span class="bsm-count bsm-count-red"><?php echo $counts['occupied']; ?></span><span class="bsm-label">Occupied</span></div></div>
    <div class="bsm-stat"><div class="bsm-stat-ic unused"><i class="fas fa-minus-circle"></i></div><div class="bsm-stat-text"><span class="bsm-count"><?php echo $counts['unused']; ?></span><span class="bsm-label">Unused</span></div></div>
    <div class="bsm-gauge"><svg width="52" height="52" viewBox="0 0 36 36" aria-hidden="true"><circle class="bsm-gauge-ring-bg" cx="18" cy="18" r="15.9"></circle><circle class="bsm-gauge-ring-fill" cx="18" cy="18" r="15.9" style="stroke-dasharray:<?php echo $occupancy; ?> <?php echo 100 - $occupancy; ?>"></circle></svg><div class="bsm-gauge-info"><span class="bsm-gauge-pct"><?php echo $occupancy; ?>%</span><span class="bsm-gauge-lbl">Occupied</span></div></div>
</div>

<div class="bsm-filterbar">
    <label class="bsm-search"><i class="fas fa-search"></i><input type="text" class="bsm-search-input" placeholder="Search…" autocomplete="off"></label>
    <div class="bsm-fbtns">
        <button type="button" class="bsm-fbtn f-all active" data-filter="all">All <span class="bsm-fbtn-badge"><?php echo $counts['total']; ?></span></button>
        <button type="button" class="bsm-fbtn f-avail" data-filter="available">Available <span class="bsm-fbtn-badge"><?php echo $counts['available']; ?></span></button>
        <button type="button" class="bsm-fbtn f-occup" data-filter="occupied">Occupied <span class="bsm-fbtn-badge"><?php echo $counts['occupied']; ?></span></button>
        <button type="button" class="bsm-fbtn f-unused" data-filter="unused">Unused <span class="bsm-fbtn-badge"><?php echo $counts['unused']; ?></span></button>
    </div>
</div>

<div class="bsm-body">
    <?php foreach ((array) $floor_list as $floor) {
        $floorGroups = array();
        $floorBedCount = 0;
        foreach ((array) $bedgroup_list as $bedgroup) {
            if ($bedgroup['fid'] == $floor['id']) {
                $floorGroups[] = $bedgroup;
                $floorBedCount += isset($bedsByGroup[$bedgroup['id']]) ? count($bedsByGroup[$bedgroup['id']]) : 0;
            }
        }
        if (empty($floorGroups)) continue;
    ?>
        <section class="bsm-floor">
            <div class="bsm-floor-title"><i class="fas fa-layer-group"></i><?php echo html_escape($floor['name']); ?><span class="bsm-floor-badge"><?php echo count($floorGroups); ?> Wards · <?php echo $floorBedCount; ?> Beds</span></div>
            <div class="bsm-wards">
                <?php foreach ($floorGroups as $bedgroup) {
                    $groupBeds = isset($bedsByGroup[$bedgroup['id']]) ? $bedsByGroup[$bedgroup['id']] : array();
                    $groupCounts = array('available' => 0, 'occupied' => 0, 'unused' => 0);
                    foreach ($groupBeds as $groupBed) $groupCounts[$groupBed['_clinical_status']]++;
                ?>
                    <div class="bsm-ward">
                        <div class="bsm-ward-header" style="border-left-color:<?php echo html_escape($bedgroup['color']); ?>"><span class="bsm-ward-name"><?php echo html_escape($bedgroup['name']); ?></span><div class="bsm-ward-pills"><span class="bsm-ward-pill pa"><i class="fas fa-check"></i><?php echo $groupCounts['available']; ?></span><span class="bsm-ward-pill po"><i class="fas fa-user"></i><?php echo $groupCounts['occupied']; ?></span><span class="bsm-ward-pill pt"><?php echo count($groupBeds); ?></span></div></div>
                        <div class="bsm-beds">
                            <?php foreach ($groupBeds as $bed) {
                                $status = $bed['_clinical_status'];
                                $isOccupied = $status === 'occupied';
                                $url = $isOccupied && !empty($bed['ipd_details_id']) ? base_url('admin/patient/ipdprofile/' . $bed['ipd_details_id']) : base_url('admin/patient/ipdsearch/' . $bed['id'] . '/' . $bedgroup['id']);
                                $hasPatientData = $isOccupied && !empty($bed['patient_name']);
                                $patientName = $hasPatientData ? $bed['patient_name'] : ($isOccupied ? 'Demo Patient' : '');
                                $stateLabel = $status === 'available' ? 'Available' : ($status === 'occupied' ? 'Occupied' : 'Unused');
                                $tooltipText = $status === 'available' ? 'Available — click to admit a patient' : ($status === 'unused' ? 'Unused — currently unavailable' : 'Occupied — hover for patient details');
                                $patientId = !empty($bed['patient_unique_id']) ? $bed['patient_unique_id'] : 'DEMO-' . str_pad($bed['id'], 4, '0', STR_PAD_LEFT);
                                $phone = !empty($bed['mobileno']) ? $bed['mobileno'] : '+880 1712 345678';
                                $gender = !empty($bed['gender']) ? $this->lang->line(strtolower($bed['gender'])) : 'Female';
                                $guardian = !empty($bed['guardian_name']) ? $bed['guardian_name'] : 'Alex Morgan';
                                $consultant = trim((isset($bed['staff']) ? $bed['staff'] : '') . ' ' . (isset($bed['surname']) ? $bed['surname'] : '')) ?: 'Dr. Sarah Ahmed';
                                $detail = '<strong>' . html_escape($patientName) . '</strong><hr style="margin:5px 0"><small><b>' . $this->lang->line('bed_no') . ':</b> ' . html_escape($bed['name']) . '<br><b>' . $this->lang->line('patient_id') . ':</b> ' . html_escape($patientId) . '<br><b>' . $this->lang->line('phone') . ':</b> ' . html_escape($phone) . '<br><b>' . $this->lang->line('gender') . ':</b> ' . html_escape($gender) . '<br><b>' . $this->lang->line('guardian_name') . ':</b> ' . html_escape($guardian) . '<br><b>' . $this->lang->line('consultant') . ':</b> ' . html_escape($consultant) . '</small>' . ($hasPatientData ? '' : '<div class="bsm-demo-note">Demo patient details</div>');
                            ?>
                                <a href="<?php echo $url; ?>" class="bsm-bed bsm-<?php echo $status; ?><?php echo $isOccupied ? ' beddetail_popover' : ' bedstatus-tooltip'; ?>" data-bsm-status="<?php echo $status; ?>" data-bsm-search="<?php echo html_escape(strtolower($bed['name'] . ' ' . $patientName . ' ' . $bedgroup['name'] . ' ' . $floor['name'])); ?>"<?php if ($isOccupied) { ?> data-toggle="popover" data-placement="top" data-html="true" data-content="<?php echo html_escape($detail); ?>"<?php } else { ?> data-toggle="tooltip" title="<?php echo html_escape($tooltipText); ?>"<?php } ?>><i class="fas fa-bed"></i><span class="bsm-bed-no"><?php echo html_escape($bed['name']); ?></span><span class="bsm-patient"><?php echo html_escape($patientName); ?></span><span class="bsm-bed-state"><?php echo $stateLabel; ?></span></a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
    <?php } ?>
</div>

<div class="bsm-legend"><span class="bsm-legend-item"><i class="bsm-legend-dot la"></i>Available — click to admit</span><span class="bsm-legend-item"><i class="bsm-legend-dot lo"></i>Occupied — click to view IPD profile</span><span class="bsm-legend-item"><i class="bsm-legend-dot lu"></i>Unused</span><span class="bsm-legend-tip"><i class="fa fa-info-circle"></i> Hover occupied bed for patient details</span></div>

<script>
(function () {
    var $modal = $('#bed');
    var $body = $modal.find('#ajaxbedstatus');
    $body.find('.beddetail_popover').popover({container: '#bed', trigger: 'hover focus'});
    $body.find('.bedstatus-tooltip').tooltip({container: '#bed'});
    function applyBedFilter() {
        var status = $body.find('.bsm-fbtn.active').data('filter') || 'all';
        var query = ($body.find('.bsm-search-input').val() || '').toLowerCase();
        $body.find('.bsm-bed').each(function () {
            var $bed = $(this);
            var statusMatch = status === 'all' || $bed.data('bsm-status') === status;
            var textMatch = !query || String($bed.data('bsm-search')).indexOf(query) !== -1;
            $bed.toggle(statusMatch && textMatch);
        });
        $body.find('.bsm-ward').each(function () { $(this).toggle($(this).find('.bsm-bed:visible').length > 0); });
        $body.find('.bsm-floor').each(function () { $(this).toggle($(this).find('.bsm-ward:visible').length > 0); });
    }
    $body.off('click.bsm', '.bsm-fbtn').on('click.bsm', '.bsm-fbtn', function () { $body.find('.bsm-fbtn').removeClass('active'); $(this).addClass('active'); applyBedFilter(); });
    $body.off('input.bsm', '.bsm-search-input').on('input.bsm', '.bsm-search-input', applyBedFilter);
})();
</script>
