<?php
$types = array('poag' => 'Primary Open-Angle', 'pacg' => 'Primary Angle-Closure', 'ntg' => 'Normal-Tension', 'secondary' => 'Secondary', 'congenital' => 'Congenital', 'suspect' => 'Glaucoma Suspect', 'oht' => 'Ocular Hypertension');
$statuses = array('stable' => 'Stable', 'suspected_progression' => 'Suspected Progression', 'definite_progression' => 'Definite Progression');
?>
<style>
.gl-stat{border:0;border-radius:5px;color:#fff}.gl-stat .box-body{padding:18px}.gl-stat-total{background:#536dfe}.gl-stat-good{background:#00a65a}.gl-stat-bad{background:#dd4b39}.gl-stat-high{background:#f39c12}.gl-stat-label{opacity:.85}.gl-stat-value{font-size:28px;font-weight:600}.gl-row{display:flex;align-items:center;gap:15px}.gl-avatar{width:44px;height:44px;border-radius:50%;background:#e8eaf6;color:#3f51b5;display:flex;align-items:center;justify-content:center;font-size:19px}.gl-badge{display:inline-block;padding:3px 8px;border-radius:3px;background:#e8eaf6;color:#3949ab;font-size:11px}.gl-status{background:#e8f5e9;color:#2e7d32}.gl-status.warn{background:#fff3e0;color:#ef6c00}.gl-status.bad{background:#ffebee;color:#c62828}.gl-mono{font-family:monospace}.gl-muted{color:#777}.gl-actions{white-space:nowrap}
</style>
<div class="content-wrapper">
    <section class="content-header"><h1><i class="fa fa-heartbeat"></i> Glaucoma Center <small>Glaucoma patient management and monitoring</small></h1></section>
    <section class="content">
        <?php echo $this->session->flashdata('msg'); ?>
        <div class="row">
            <?php $cards = array(array('Total Patients', $stats['total'], 'gl-stat-total', 'fa-users'), array('IOP Controlled', $stats['controlled'], 'gl-stat-good', 'fa-check-circle'), array('IOP Uncontrolled', $stats['uncontrolled'], 'gl-stat-bad', 'fa-exclamation-circle'), array('High IOP', $stats['high_iop'], 'gl-stat-high', 'fa-line-chart')); ?>
            <?php foreach ($cards as $card) { ?><div class="col-sm-3 col-xs-6"><div class="box gl-stat <?php echo $card[2]; ?>"><div class="box-body"><i class="fa <?php echo $card[3]; ?> pull-right fa-2x"></i><div class="gl-stat-label"><?php echo $card[0]; ?></div><div class="gl-stat-value"><?php echo (int) $card[1]; ?></div></div></div></div><?php } ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Glaucoma Records</h3><div class="box-tools"><a class="btn btn-primary btn-sm" href="<?php echo site_url('admin/glaucoma/create'); ?>"><i class="fa fa-plus"></i> New Record</a></div></div>
            <div class="box-body">
                <form method="get" class="row" style="margin-bottom:18px">
                    <div class="col-sm-5"><div class="input-group"><input class="form-control" name="search" value="<?php echo html_escape($search); ?>" placeholder="Search by patient name or ID..."><span class="input-group-btn"><button class="btn btn-default"><i class="fa fa-search"></i></button></span></div></div>
                    <div class="col-sm-3"><select class="form-control" name="type" onchange="this.form.submit()"><option value="">All Types</option><?php foreach ($types as $key => $label) { ?><option value="<?php echo $key; ?>" <?php echo $type === $key ? 'selected' : ''; ?>><?php echo $label; ?></option><?php } ?></select></div>
                    <div class="col-sm-3"><select class="form-control" name="status" onchange="this.form.submit()"><option value="">All Status</option><?php foreach ($statuses as $key => $label) { ?><option value="<?php echo $key; ?>" <?php echo $status === $key ? 'selected' : ''; ?>><?php echo $label; ?></option><?php } ?></select></div>
                </form>
                <?php if (!$records) { ?><div class="text-center gl-muted" style="padding:45px"><i class="fa fa-heartbeat fa-4x"></i><h4>No glaucoma records found</h4><p>Get started by creating a glaucoma patient record.</p><a class="btn btn-primary" href="<?php echo site_url('admin/glaucoma/create'); ?>"><i class="fa fa-plus"></i> New Record</a></div><?php } ?>
                <?php foreach ($records as $record) {
                    $uncontrolled = ($record['latest_iop_od'] !== null && $record['target_iop_od'] !== null && $record['latest_iop_od'] > $record['target_iop_od']) || ($record['latest_iop_os'] !== null && $record['target_iop_os'] !== null && $record['latest_iop_os'] > $record['target_iop_os']);
                    $status_class = $record['progression_status'] === 'definite_progression' ? 'bad' : ($record['progression_status'] === 'suspected_progression' ? 'warn' : ''); ?>
                    <div class="row" style="border-top:1px solid #eee;padding:15px 0">
                        <div class="col-md-4"><a href="<?php echo site_url('admin/glaucoma/view/' . $record['id']); ?>" class="gl-row"><span class="gl-avatar"><i class="fa fa-eye"></i></span><span><strong><?php echo html_escape($record['patient_name']); ?></strong><br><small class="gl-muted">ID: <?php echo (int) $record['patient_no']; ?></small><br><span class="gl-badge"><?php echo isset($types[$record['glaucoma_type']]) ? $types[$record['glaucoma_type']] : html_escape($record['glaucoma_type']); ?></span> <span class="gl-badge gl-status <?php echo $status_class; ?>"><?php echo isset($statuses[$record['progression_status']]) ? $statuses[$record['progression_status']] : html_escape($record['progression_status']); ?></span></span></a></div>
                        <div class="col-md-2 text-center"><small class="gl-muted">LAST IOP</small><div class="gl-mono <?php echo $uncontrolled ? 'text-red' : ''; ?>">OD: <?php echo $record['latest_iop_od'] !== null ? $record['latest_iop_od'] : '-'; ?> | OS: <?php echo $record['latest_iop_os'] !== null ? $record['latest_iop_os'] : '-'; ?></div></div>
                        <div class="col-md-2 text-center"><small class="gl-muted">TARGET</small><div class="gl-mono text-blue">OD: <?php echo $record['target_iop_od'] !== null ? $record['target_iop_od'] : '-'; ?> | OS: <?php echo $record['target_iop_os'] !== null ? $record['target_iop_os'] : '-'; ?></div></div>
                        <div class="col-md-1 text-center"><small class="gl-muted">DROPS</small><div><?php $meds = json_decode($record['medications_json'], true); echo is_array($meds) ? count($meds) : 0; ?></div></div>
                        <div class="col-md-2 text-center"><small class="gl-muted">NEXT VISIT</small><div><?php echo $record['next_visit'] ? date('d M Y', strtotime($record['next_visit'])) : 'Not scheduled'; ?></div></div>
                        <div class="col-md-1 text-right gl-actions"><a class="btn btn-default btn-xs" href="<?php echo site_url('admin/glaucoma/view/' . $record['id']); ?>"><i class="fa fa-eye"></i></a> <a class="btn btn-default btn-xs" href="<?php echo site_url('admin/glaucoma/edit/' . $record['id']); ?>"><i class="fa fa-pencil"></i></a></div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>
