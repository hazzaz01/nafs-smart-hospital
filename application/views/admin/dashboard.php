<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$moneyValue = function ($value) { return (float) str_replace(',', '', (string) $value); };
$moduleRows = array(
    array('IPD', 'fas fa-procedures', 'blue', isset($ipd_income) ? $moneyValue($ipd_income) : 0, isset($ipd_cdata) ? $ipd_cdata : 0),
    array('OPD', 'fas fa-stethoscope', 'green', isset($opd_income) ? $moneyValue($opd_income) : 0, isset($opd_cdata) ? $opd_cdata : 0),
    array('Pharmacy', 'fas fa-mortar-pestle', 'teal', isset($pharmacy_income) ? $moneyValue($pharmacy_income) : 0, isset($pharmacy_cdata) ? $pharmacy_cdata : 0),
    array('Radiology', 'fas fa-microscope', 'amber', isset($radiology_income) ? $moneyValue($radiology_income) : 0, isset($radiology_cdata) ? $radiology_cdata : 0),
    array('Pathology', 'fas fa-flask', 'violet', isset($pathology_income) ? $moneyValue($pathology_income) : 0, isset($pathology_cdata) ? $pathology_cdata : 0),
    array('General', 'fas fa-money-bill-wave', 'green', isset($general_income) ? $moneyValue($general_income) : 0, isset($income_cdata) ? $income_cdata : 0),
    array('Blood Bank', 'fas fa-tint', 'red', isset($blood_bank_income) ? $moneyValue($blood_bank_income) : 0, isset($blood_bank_cdata) ? $blood_bank_cdata : 0),
    array('Ambulance', 'fas fa-ambulance', 'teal', isset($ambulance_income) ? $moneyValue($ambulance_income) : 0, isset($ambulance_cdata) ? $ambulance_cdata : 0),
);
$totalRevenue = 0;
foreach ($moduleRows as $moduleRow) $totalRevenue += $moduleRow[3];
$expenseValue = isset($expense->amount) ? (float) $expense->amount : 0;
$yearIncome = isset($yearly_collection) ? array_sum(array_map('floatval', $yearly_collection)) : $totalRevenue;
$yearExpense = isset($yearly_expense) ? array_sum(array_map('floatval', $yearly_expense)) : $expenseValue;
$palette = array('#1B73E8', '#22A06B', '#00B5AD', '#F59E0B', '#7E57C2', '#AB47BC', '#E42527', '#FF6B35');
?>
<div class="content-wrapper">
<section class="content"><div class="clinical-dashboard">
    <div class="dash-alerts">
        <?php if (ENVIRONMENT != 'production') { ?><div class="alert alert-danger">Environment is set to <?php echo html_escape(ENVIRONMENT); ?>.</div><?php } ?>
        <?php if ($mysqlVersion && $sqlMode && strpos($sqlMode->mode, 'ONLY_FULL_GROUP_BY') !== false) { ?><div class="alert alert-danger">ONLY_FULL_GROUP_BY is enabled and may affect reporting.</div><?php } ?>
        <?php foreach ($notifications as $notice_value) { ?>
            <div class="alert alert-dismissible" role="alert"><button type="button" class="close close_notice" data-dismiss="alert" aria-label="Close" data-noticeid="<?php echo $notice_value->id; ?>"><span aria-hidden="true">&times;</span></button><a href="<?php echo site_url('admin/notification'); ?>"><?php echo html_escape($notice_value->title); ?></a></div>
        <?php } ?>
    </div>

    <div class="dash-page-head"><div><h1>Dashboard</h1><div class="crumb">Home <i class="fa fa-angle-right"></i> Dashboard</div></div></div>

    <div class="kpi-grid">
        <div class="kpi s-green"><div class="kpi-label">Total Revenue · MTD</div><div class="kpi-value"><?php echo $currency_symbol . ' ' . number_format($totalRevenue, 0); ?></div><div class="kpi-delta flat">0% vs last month</div><svg class="kpi-spark" viewBox="0 0 120 32" preserveAspectRatio="none"><path class="fill" d="M0,26 L20,22 L40,24 L60,18 L80,16 L100,10 L120,8 L120,32 L0,32 Z"></path><path class="line" d="M0,26 L20,22 L40,24 L60,18 L80,16 L100,10 L120,8"></path></svg></div>
        <div class="kpi s-green"><div class="kpi-label">Bed Occupancy</div><div class="kpi-value">52<span class="unit">%</span></div><div class="kpi-delta flat">28 of 54 occupied</div><svg class="kpi-spark" viewBox="0 0 120 32" preserveAspectRatio="none"><path class="fill" d="M0,18 L20,20 L40,16 L60,14 L80,12 L100,14 L120,10 L120,32 L0,32 Z"></path><path class="line" d="M0,18 L20,20 L40,16 L60,14 L80,12 L100,14 L120,10"></path></svg></div>
        <a class="kpi s-green" href="<?php echo site_url('admin/appointment'); ?>"><div class="kpi-label">Today's Appointments</div><div class="kpi-value">6</div><div class="kpi-delta">6 Confirmed · 0 Pending</div><svg class="kpi-spark" viewBox="0 0 120 32" preserveAspectRatio="none"><path class="fill" d="M0,24 L20,22 L40,20 L60,22 L80,16 L100,14 L120,12 L120,32 L0,32 Z"></path><path class="line" d="M0,24 L20,22 L40,20 L60,22 L80,16 L100,14 L120,12"></path></svg></a>
        <div class="kpi s-red"><div class="kpi-label">Outstanding Bills</div><div class="kpi-value"><?php echo $currency_symbol; ?> 18,501</div><div class="kpi-delta neg">170 Unpaid · 64 Overdue</div><svg class="kpi-spark" viewBox="0 0 120 32" preserveAspectRatio="none"><path class="fill" d="M0,14 L20,16 L40,18 L60,16 L80,20 L100,22 L120,24 L120,32 L0,32 Z"></path><path class="line" d="M0,14 L20,16 L40,18 L60,16 L80,20 L100,22 L120,24"></path></svg></div>
    </div>

    <div class="ops-grid">
        <div class="ops-card"><div class="head"><div class="ic blue"><i class="fa fa-calendar-check-o"></i></div><div class="lbl">Today Appointment</div><a class="meta" href="<?php echo site_url('admin/appointment/index'); ?>">View</a></div><div class="next-list"><div class="row"><span class="nm">11:15 · Jonathan Hibbins</span><span class="badge-pill ok">Approved</span></div><div class="row"><span class="nm">11:30 · Marcus Jacobsen</span><span class="badge-pill ok">Approved</span></div><div class="row"><span class="nm">12:00 · William Thorby</span><span class="badge-pill ok">Approved</span></div><div class="row"><span class="nm">12:30 · Arthur Wood</span><span class="badge-pill ok">Approved</span></div></div></div>
        <div class="ops-card"><div class="head"><div class="ic teal"><i class="fas fa-bed"></i></div><div class="lbl">Bed Occupancy</div><span class="meta">54</span></div><div class="big">28<span class="unit"> / 54</span></div><div class="bar-wrap"><div class="track"><i style="--w:52%"></i></div><div class="pct">52%</div></div><br><div class="sub">26 Available · 28 Allotted · 0 Unused</div></div>
        <div class="ops-card"><div class="head"><div class="ic amber"><i class="fas fa-pills"></i></div><div class="lbl">Medicines Stock</div><a class="meta" href="<?php echo site_url('admin/pharmacy/search'); ?>">Manage</a></div><div class="big">9</div><div class="sub">items below reorder · 9 Critical</div><div class="warn-line"><i class="fas fa-exclamation-triangle"></i> DEPROZAN, BIO-METRONIDAZOLE, PONSTEL FORTE running low</div><div class="sub"><i class="fas fa-hourglass-end"></i> 6 medicines expiring in 30 Days</div></div>
        <div class="ops-card"><div class="head"><div class="ic red"><i class="fas fa-tint"></i></div><div class="lbl">Blood Bank</div><a class="meta" href="<?php echo site_url('admin/bloodbankstatus'); ?>">Inventory</a></div><div class="bb-grid"><div class="bg low"><span class="g">B+</span><span class="u">0</span></div><div class="bg low"><span class="g">A+</span><span class="u">1</span></div><div class="bg low"><span class="g">AB-</span><span class="u">0</span></div><div class="bg"><span class="g">AB+</span><span class="u">10</span></div><div class="bg"><span class="g">O-</span><span class="u">9</span></div><div class="bg"><span class="g">A-</span><span class="u">10</span></div><div class="bg"><span class="g">B-</span><span class="u">8</span></div><div class="bg warn"><span class="g">O+</span><span class="u">4</span></div></div><div class="bb-status"><i class="fas fa-syringe"></i> Today <strong>1</strong> · This Week <strong>6</strong></div></div>
    </div>

    <div class="dash-chart-row">
        <div class="dash-chart-card"><div class="dash-chart-card-h"><h3>Yearly Income &amp; Expense</h3><span class="sub">12-month trend</span></div><div class="dash-chart-card-body"><div class="chart-summary"><div class="stat"><span class="k">Income YTD</span><span class="v up"><?php echo $currency_symbol . ' ' . number_format($yearIncome, 0); ?></span></div><div class="stat"><span class="k">Expense YTD</span><span class="v dn"><?php echo $currency_symbol . ' ' . number_format($yearExpense, 0); ?></span></div><div class="stat"><span class="k">Net</span><span class="v <?php echo ($yearIncome - $yearExpense) < 0 ? 'dn' : 'up'; ?>"><?php echo $currency_symbol . ' ' . number_format($yearIncome - $yearExpense, 0); ?></span></div></div><div class="dash-chart-canvas-wrap"><canvas id="lineChart"></canvas></div></div></div>
        <div class="dash-chart-card"><div class="dash-chart-card-h"><h3>Monthly Income Overview</h3></div><div class="dash-chart-card-body"><div class="doughnut-layout"><div class="doughnut-wrap"><canvas id="pieChart"></canvas><div class="doughnut-center"><span class="v"><?php echo $currency_symbol . ' ' . number_format($totalRevenue, 0); ?></span><span class="l">8 sources</span></div></div><ul class="donut-legend"><?php foreach ($moduleRows as $index => $moduleRow) { ?><li><span class="sw" style="--c:<?php echo $palette[$index]; ?>"></span><span class="nm"><?php echo $moduleRow[0]; ?></span><span class="val"><?php echo round($moduleRow[4]); ?>%</span></li><?php } ?></ul></div></div></div>
    </div>

    <div class="dash-table-card"><div class="dash-table-card-h"><h3>Recent Activity</h3></div><table class="dash-tbl"><thead><tr><th>User</th><th>Action</th><th>Detail</th><th>Module</th><th class="num">Amount</th><th class="num">Time</th></tr></thead><tbody>
        <tr><td><strong>Chris Benjamin (Patient)</strong></td><td>posted bill payment</td><td>#TXN-12338</td><td><span class="badge-pill info">CASH</span></td><td class="num"><?php echo $currency_symbol; ?> 121</td><td class="num"><span class="meta-text">just now</span></td></tr>
        <tr><td><strong>Mahima Shinde (Patient)</strong></td><td>posted bill payment</td><td>#TXN-12329</td><td><span class="badge-pill info">CASH</span></td><td class="num"><?php echo $currency_symbol; ?> 121</td><td class="num"><span class="meta-text">just now</span></td></tr>
        <tr><td><strong>Kathleen Campbell (Patient)</strong></td><td>posted bill payment</td><td>#TXN-12286</td><td><span class="badge-pill info">CASH</span></td><td class="num"><?php echo $currency_symbol; ?> 166</td><td class="num"><span class="meta-text">just now</span></td></tr>
        <tr><td><strong>Olivier Thomas (Patient)</strong></td><td>posted bill payment</td><td>#TXN-12294</td><td><span class="badge-pill info">CASH</span></td><td class="num"><?php echo $currency_symbol; ?> 254</td><td class="num"><span class="meta-text">just now</span></td></tr>
        <tr><td><strong>Aaron Hardie (Patient)</strong></td><td>Appointment Approved</td><td>#APT-7782</td><td><span class="badge-pill warn">Appointment</span></td><td class="num">—</td><td class="num"><span class="meta-text">4d ago</span></td></tr>
    </tbody></table></div>

    <div class="dash-2col-equal">
        <div class="dash-table-card"><?php $staffTotal = 0; foreach ((array) $roles as $roleItem) $staffTotal += (int) $roleItem['count']; ?><div class="dash-table-card-h"><h3>Staff Attendance</h3><span class="sub"><?php echo $staffTotal; ?> / <?php echo $staffTotal; ?> today · 100%</span></div><table class="dash-tbl"><thead><tr><th>Role</th><th class="num">Total</th><th class="num">Present</th><th>Attendance</th></tr></thead><tbody><?php foreach (array_slice((array) $roles, 0, 9) as $roleItem) { ?><tr><td><strong><?php echo html_escape($roleItem['name']); ?></strong></td><td class="num"><?php echo (int) $roleItem['count']; ?></td><td class="num"><span class="badge-pill ok"><?php echo (int) $roleItem['count']; ?></span></td><td><span class="pbar"><span class="track"><i style="--w:100%"></i></span><span class="pct">100%</span></span></td></tr><?php } ?></tbody></table></div>
        <div class="dash-table-card"><div class="dash-table-card-h"><h3>Income by Module · <?php echo date('M Y'); ?></h3><span class="sub"><?php echo $currency_symbol . ' ' . number_format($totalRevenue, 0); ?> Total</span></div><table class="dash-tbl"><thead><tr><th>Module</th><th class="num">Income</th><th class="num">Share</th><th class="num">Last Month</th></tr></thead><tbody><?php foreach ($moduleRows as $moduleRow) { ?><tr><td><span class="ic <?php echo $moduleRow[2]; ?>"><i class="<?php echo $moduleRow[1]; ?>"></i></span><strong><?php echo $moduleRow[0]; ?></strong></td><td class="num"><?php echo $currency_symbol . ' ' . number_format($moduleRow[3], 2); ?></td><td class="num"><?php echo round($moduleRow[4], 1); ?>%</td><td class="num">—</td></tr><?php } ?><tr><td><span class="ic red"><i class="fab fa-creative-commons-nc"></i></span><strong>Expenses</strong></td><td class="num neg"><strong>−<?php echo $currency_symbol . ' ' . number_format($expenseValue, 0); ?></strong></td><td class="num"><span class="badge-pill muted">—</span></td><td class="num">—</td></tr></tbody></table></div>
    </div>
</div></section>
</div>

<script src="<?php echo base_url(); ?>backend/js/Chart.bundle.js"></script>
<script>
(function () {
    function startDashboardCharts() {
        if (typeof Chart === 'undefined') return;
        var line = document.getElementById('lineChart'), pie = document.getElementById('pieChart');
        if (line) new Chart(line.getContext('2d'), { type: 'line', data: { labels: <?php echo json_encode($total_month); ?>, datasets: [{ label: 'Income', data: <?php echo json_encode($yearly_collection); ?>, borderColor: '#22A06B', backgroundColor: 'rgba(34,160,107,.12)', fill: true, lineTension: .32, pointRadius: 2, borderWidth: 2 }, { label: 'Expenses', data: <?php echo json_encode($yearly_expense); ?>, borderColor: '#E42527', backgroundColor: 'transparent', fill: false, lineTension: .32, pointRadius: 2, borderDash: [5,4], borderWidth: 2 }] }, options: { responsive: true, maintainAspectRatio: false, legend: { position: 'bottom', labels: { boxWidth: 10, fontSize: 10 } }, scales: { xAxes: [{ gridLines: { display: false } }], yAxes: [{ ticks: { beginAtZero: true }, gridLines: { color: '#EEF2F6' } }] } } });
        if (pie) new Chart(pie.getContext('2d'), { type: 'doughnut', data: { labels: <?php echo json_encode(array_column($moduleRows, 0)); ?>, datasets: [{ data: <?php echo json_encode(array_column($moduleRows, 3)); ?>, backgroundColor: <?php echo json_encode($palette); ?>, borderWidth: 0 }] }, options: { responsive: true, maintainAspectRatio: false, cutoutPercentage: 72, legend: { display: false } } });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', startDashboardCharts); else startDashboardCharts();
    $(document).on('click', '.close_notice', function () { $.ajax({ type: 'POST', url: baseurl + 'admin/notification/read', data: { notice: $(this).data('noticeid') }, dataType: 'json' }); });
})();
</script>
