<style>
.eye-stat{border-left:4px solid #3c8dbc}.eye-stat.warning{border-left-color:#f39c12}.eye-stat.danger{border-left-color:#dd4b39}.eye-value{font-size:28px;font-weight:600}.eye-muted{color:#777}.eye-actions{white-space:nowrap}
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-eye"></i> Eye Examinations <small>Ophthalmology records</small></h1>
    </section>
    <section class="content">
        <?php echo $this->session->flashdata('msg'); ?>
        <div class="row">
            <?php $cards = array(array('Today', $stats['today_count'], ''), array('This Week', $stats['week_count'], ''), array('High IOP', $stats['high_iop_count'], 'danger'), array('Total Records', $stats['total_count'], 'warning')); ?>
            <?php foreach ($cards as $card) { ?>
                <div class="col-sm-3 col-xs-6"><div class="box eye-stat <?php echo $card[2]; ?>"><div class="box-body"><div class="eye-muted"><?php echo $card[0]; ?></div><div class="eye-value"><?php echo $card[1]; ?></div></div></div></div>
            <?php } ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Examination Records</h3>
                <div class="box-tools"><a href="<?php echo site_url('admin/eyeexam/create'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> New Eye Exam</a></div>
            </div>
            <div class="box-body">
                <form method="get" class="row" style="margin-bottom:15px">
                    <div class="col-sm-5"><div class="input-group"><input class="form-control" name="search" value="<?php echo html_escape($search); ?>" placeholder="Search by patient, ID or complaint"><span class="input-group-btn"><button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button></span></div></div>
                </form>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead><tr><th>Date</th><th>Patient</th><th>Chief Complaint</th><th>BCVA (OD / OS)</th><th>IOP (OD / OS)</th><th>Doctor</th><th class="text-right">Actions</th></tr></thead>
                        <tbody>
                        <?php if (!$examinations) { ?><tr><td colspan="7" class="text-center eye-muted">No eye examinations found.</td></tr><?php } ?>
                        <?php foreach ($examinations as $exam) { ?>
                            <tr>
                                <td><?php echo html_escape(date('d M Y', strtotime($exam['exam_date']))); ?></td>
                                <td><strong><?php echo html_escape($exam['patient_name']); ?></strong><br><small class="eye-muted">Patient ID: <?php echo (int) $exam['patient_no']; ?></small></td>
                                <td><?php echo html_escape($exam['chief_complaint']); ?></td>
                                <td><?php echo html_escape(($exam['bcva_distance_od'] ?: '-') . ' / ' . ($exam['bcva_distance_os'] ?: '-')); ?></td>
                                <td><?php echo html_escape(($exam['iop_od'] !== null ? $exam['iop_od'] : '-') . ' / ' . ($exam['iop_os'] !== null ? $exam['iop_os'] : '-')); ?></td>
                                <td><?php echo html_escape(trim($exam['doctor_name'] . ' ' . $exam['doctor_surname'])); ?></td>
                                <td class="text-right eye-actions">
                                    <a class="btn btn-default btn-xs" href="<?php echo site_url('admin/eyeexam/view/' . $exam['id']); ?>" title="View"><i class="fa fa-eye"></i></a>
                                    <a class="btn btn-default btn-xs" href="<?php echo site_url('admin/eyeexam/edit/' . $exam['id']); ?>" title="Edit"><i class="fa fa-pencil"></i></a>
                                    <form method="post" action="<?php echo site_url('admin/eyeexam/delete/' . $exam['id']); ?>" style="display:inline" onsubmit="return confirm('Delete this eye examination?');">
                                        <?php echo $this->customlib->getCSRF(); ?><button class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
