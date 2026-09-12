<?php include __DIR__ . '/partials/header.php'; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root {
        --ms-primary: #2563eb;
        --ms-primary-dark: #1d4ed8;
        --ms-success: #059669;
        --ms-warning: #d97706;
        --ms-danger: #dc2626;
        --ms-bg: #f8fafc;
        --ms-card-bg: #ffffff;
        --ms-text-main: #0f172a;
        --ms-text-muted: #64748b;
        --ms-border: #e2e8f0;
        --ms-radius: 14px;
        --ms-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.05), 0 2px 4px -2px rgba(15, 23, 42, 0.03);
        --ms-shadow-hover: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
    }

    .dashboard-container {
        padding: 32px;
        max-width: 1600px;
        margin: 0 auto;
        font-family: 'Inter', sans-serif;
        color: var(--ms-text-main);
        background: var(--ms-bg);
    }

    /* Executive Command Header */
    .exec-command-bar {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: var(--ms-radius);
        padding: 28px 36px;
        color: white;
        margin-bottom: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.2);
    }

    .exec-title h1 {
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 4px;
    }

    .exec-title p {
        font-size: 0.88rem;
        color: #94a3b8;
    }

    .exec-status-badge {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.15);
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(8px);
    }

    /* Quick Action Toolbar Bar */
    .shortcut-bar {
        display: flex;
        gap: 12px;
        margin-bottom: 32px;
        overflow-x: auto;
        padding-bottom: 6px;
    }

    .shortcut-btn {
        background: white;
        border: 1px solid var(--ms-border);
        padding: 10px 20px;
        border-radius: 10px;
        color: var(--ms-text-main);
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        white-space: nowrap;
        transition: all 0.2s ease;
        box-shadow: var(--ms-shadow);
    }

    .shortcut-btn i {
        color: var(--ms-primary);
        font-size: 0.95rem;
    }

    .shortcut-btn:hover {
        background: var(--ms-primary);
        color: white;
        border-color: var(--ms-primary);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
    }

    .shortcut-btn:hover i {
        color: white;
    }

    /* Executive Metrics Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: var(--ms-card-bg);
        border-radius: var(--ms-radius);
        padding: 24px 28px;
        box-shadow: var(--ms-shadow);
        border: 1px solid var(--ms-border);
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--ms-primary);
    }

    .stat-card.c-green::before { background: var(--ms-success); }
    .stat-card.c-red::before { background: var(--ms-danger); }
    .stat-card.c-orange::before { background: var(--ms-warning); }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--ms-shadow-hover);
        border-color: #cbd5e1;
    }

    .stat-header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .stat-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--ms-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .stat-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        background: #eff6ff;
        color: var(--ms-primary);
    }

    .stat-card.c-green .stat-icon-box { background: #ecfdf5; color: var(--ms-success); }
    .stat-card.c-red .stat-icon-box { background: #fef2f2; color: var(--ms-danger); }
    .stat-card.c-orange .stat-icon-box { background: #fffbeb; color: var(--ms-warning); }

    .stat-value {
        font-size: 2.1rem;
        font-weight: 800;
        color: var(--ms-text-main);
        letter-spacing: -0.03em;
        line-height: 1.1;
    }

    .stat-trend {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.78rem;
        font-weight: 600;
        margin-top: 14px;
    }

    .trend-up { color: var(--ms-success); }
    .trend-down { color: var(--ms-danger); }

    .stat-trend a {
        text-decoration: none;
        color: var(--ms-primary);
    }
    .stat-trend a:hover { text-decoration: underline; }

    /* Layout Grids for Charts & Tables */
    .charts-grid-top, .charts-grid-mid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        margin-bottom: 32px;
    }

    .charts-grid-mid {
        grid-template-columns: 1fr 1fr;
    }

    @media (max-width: 1024px) {
        .charts-grid-top, .charts-grid-mid {
            grid-template-columns: 1fr;
        }
    }

    .chart-card, .table-card {
        background: var(--ms-card-bg);
        border-radius: var(--ms-radius);
        padding: 24px 28px;
        box-shadow: var(--ms-shadow);
        border: 1px solid var(--ms-border);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 14px;
    }

    .chart-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--ms-text-main);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-filter select {
        padding: 6px 14px;
        border-radius: 8px;
        border: 1px solid var(--ms-border);
        color: var(--ms-text-main);
        font-size: 0.82rem;
        font-weight: 500;
        background: #f8fafc;
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .chart-filter select:focus {
        border-color: var(--ms-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    /* Professional Data Table Component */
    .table-card {
        display: flex;
        flex-direction: column;
        max-height: 400px;
        padding: 0;
        overflow: hidden;
    }

    .table-header {
        padding: 20px 28px;
        border-bottom: 1px solid var(--ms-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #ffffff;
    }

    .table-header-link {
        font-size: 0.85rem;
        color: var(--ms-primary);
        text-decoration: none;
        font-weight: 600;
    }

    .table-header-link:hover { text-decoration: underline; }

    .table-wrapper {
        overflow-y: auto;
        flex: 1;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table th {
        padding: 12px 20px;
        text-align: left;
        border-bottom: 1px solid var(--ms-border);
        font-size: 0.72rem;
        color: var(--ms-text-muted);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        background: #f8fafc;
        position: sticky;
        top: 0;
        z-index: 5;
    }

    .custom-table td {
        padding: 14px 20px;
        text-align: left;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
    }

    .custom-table tbody tr {
        transition: background 0.15s ease;
    }

    .custom-table tbody tr:hover {
        background: #f8fafc;
    }

    .btn-action {
        color: var(--ms-primary);
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        padding: 5px 14px;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s ease;
    }

    .btn-action:hover {
        background: var(--ms-primary);
        color: white;
        border-color: var(--ms-primary);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
</style>

<div class="dashboard-container">

    <!-- ===== EXECUTIVE COMMAND BANNER ===== -->
    <div class="exec-command-bar">
        <div class="exec-title">
            <h1>Multilines Solutions Operations Control</h1>
            <p>Enterprise ISP Billing, Subscriber Lifecycle, and Network Telemetry Dashboard</p>
        </div>
        <div class="exec-status-badge">
            <span style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; display: inline-block; box-shadow: 0 0 8px #10b981;"></span>
            Systems Nominal
        </div>
    </div>

    <!-- ===== ACTION SHORTCUT BAR ===== -->
    <div class="shortcut-bar">
        <a href="<?= url('customer/create') ?>" class="shortcut-btn"><i class="fas fa-user-plus"></i> New Customer</a>
        <a href="<?= url('customer/pending') ?>" class="shortcut-btn"><i class="fas fa-user-clock"></i> Pending Requests</a>
        <a href="<?= url('complain-list/create') ?>" class="shortcut-btn"><i class="fas fa-exclamation-circle"></i> New Complain</a>
        <a href="<?= url('report/collectionReport') ?>" class="shortcut-btn"><i class="fas fa-file-invoice-dollar"></i> Collections</a>
        <a href="<?= url('report/customerSummary') ?>" class="shortcut-btn"><i class="fas fa-chart-bar"></i> Customer Summary</a>
    </div>

    <!-- ===== STATS METRICS GRID ===== -->
    <div class="stats-grid">
        <div class="stat-card c-blue">
            <div class="stat-header-row">
                <div class="stat-label">Active Customers</div>
                <div class="stat-icon-box"><i class="fas fa-users"></i></div>
            </div>
            <div class="stat-value"><?= number_format($stats['active_count'] ?? 0) ?></div>
            <div class="stat-trend trend-up"><i class="fas fa-arrow-up"></i> Connected & Online</div>
        </div>

        <div class="stat-card c-green">
            <div class="stat-header-row">
                <div class="stat-label">This Month Collection</div>
                <div class="stat-icon-box"><i class="fas fa-wallet"></i></div>
            </div>
            <div class="stat-value">PKR <?= number_format($stats['total_revenue'] ?? 0) ?></div>
            <div class="stat-trend trend-up"><i class="fas fa-sync-alt"></i> Current Billing Cycle</div>
        </div>

        <div class="stat-card c-red">
            <div class="stat-header-row">
                <div class="stat-label">Total Due</div>
                <div class="stat-icon-box"><i class="fas fa-hand-holding-usd"></i></div>
            </div>
            <div class="stat-value">PKR <?= number_format($stats['total_due'] ?? 0) ?></div>
            <div class="stat-trend trend-down"><i class="fas fa-exclamation-triangle"></i> Outstanding Balances</div>
        </div>

        <div class="stat-card c-orange">
            <div class="stat-header-row">
                <div class="stat-label">Pending Tickets</div>
                <div class="stat-icon-box"><i class="fas fa-ticket-alt"></i></div>
            </div>
            <div class="stat-value"><?= isset($ticketData['Pending']) ? $ticketData['Pending'] : 0 ?></div>
            <div class="stat-trend">
                <a href="<?= url('complain-list') ?>">View Logged Issues &rarr;</a>
            </div>
        </div>
    </div>

    <!-- ===== CHARTS ROW 1: COLLECTION & TICKETS ===== -->
    <div class="charts-grid-top">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title"><i class="fas fa-chart-bar" style="color: var(--ms-primary);"></i> Collection Performance Analytics</div>
                <div class="chart-filter">
                    <select id="collectionFilter">
                        <option value="daily">Last 7 Days</option>
                        <option value="weekly">Last 30 Days</option>
                        <option value="month3">Last 3 Months</option>
                        <option value="month6">Last 6 Months</option>
                    </select>
                </div>
            </div>
            <div style="height: 250px;">
                <canvas id="collectionChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title"><i class="fas fa-chart-pie" style="color: var(--ms-warning);"></i> Support Ticket Status</div>
            </div>
            <div style="height: 250px; display: flex; justify-content: center; align-items: center;">
                <canvas id="ticketChart"></canvas>
            </div>
        </div>
    </div>

    <!-- ===== CHARTS ROW 2: REVENUE & PENDING CUSTOMERS ===== -->
    <div class="charts-grid-mid">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title"><i class="fas fa-chart-line" style="color: var(--ms-success);"></i> Revenue Growth Metrics</div>
                <div class="chart-filter">
                    <select id="revenueFilter">
                        <option value="6">Last 6 Months</option>
                        <option value="12">Last 1 Year</option>
                        <option value="24">Last 2 Years</option>
                    </select>
                </div>
            </div>
            <div style="height: 230px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="table-card">
            <div class="table-header">
                <div class="chart-title"><i class="fas fa-user-clock" style="color: var(--ms-danger);"></i> Pending Customer Queue</div>
                <a href="<?= url('customer/pending') ?>" class="table-header-link">View All &rarr;</a>
            </div>
            <div class="table-wrapper">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Subscriber Name</th>
                            <th>Date Logged</th>
                            <th>Service Area</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pendingCustomers)): ?>
                            <tr>
                                <td colspan="4" style="text-align:center; padding:45px; color:#94a3b8;">
                                    <i class="fas fa-check-circle" style="font-size: 1.5rem; color: #cbd5e1; margin-bottom: 8px; display: block;"></i>
                                    No pending customer requests found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pendingCustomers as $cust): ?>
                                <tr>
                                    <td>
                                        <div style="font-weight: 700; color: var(--ms-text-main);"><?= htmlspecialchars($cust['full_name']) ?></div>
                                        <div style="font-size: 0.75rem; color: var(--ms-text-muted);"><?= htmlspecialchars($cust['mobile_no']) ?></div>
                                    </td>
                                    <td><?= date('d M Y', strtotime($cust['created_at'])) ?></td>
                                    <td><span style="background: #f1f5f9; border: 1px solid var(--ms-border); padding: 3px 10px; border-radius: 6px; font-weight: 500; font-size: 0.78rem;"><?= htmlspecialchars($cust['area']) ?></span></td>
                                    <td style="text-align:right;">
                                        <a href="<?= url('customer/show/' . $cust['id']) ?>" class="btn-action">Review</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    // Data Injection
    const allRevenueLabels = <?= json_encode(array_keys($revenueTrend)) ?>;
    const allRevenueData = <?= json_encode(array_values($revenueTrend)) ?>;
    const ticketLabels = <?= json_encode(array_keys($ticketData)) ?>;
    const ticketValues = <?= json_encode(array_values($ticketData)) ?>;

    const dateLabels30 = <?= json_encode(array_keys($collDaily)) ?>;
    const dateValues30 = <?= json_encode(array_values($collDaily)) ?>;
    const monthLabels12 = <?= json_encode(array_keys($collMonthly)) ?>;
    const monthValues12 = <?= json_encode(array_values($collMonthly)) ?>;

    // 1. Collection Analytics Chart
    const ctxColl = document.getElementById('collectionChart').getContext('2d');
    const collChart = new Chart(ctxColl, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Collection (PKR)',
                data: [],
                backgroundColor: '#2563eb',
                borderRadius: 6,
                barThickness: 28
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2, 2], color: '#f1f5f9' }, ticks: { font: { size: 11, family: 'Inter' } } },
                x: { grid: { display: false }, ticks: { font: { size: 11, family: 'Inter' } } }
            }
        }
    });

    function updateCollChart(mode) {
        let labels = [], data = [];
        if (mode === 'daily') {
            labels = dateLabels30.slice(-7);
            data = dateValues30.slice(-7);
        } else if (mode === 'weekly') {
            labels = dateLabels30;
            data = dateValues30;
        } else if (mode === 'month3') {
            labels = monthLabels12.slice(-3);
            data = monthValues12.slice(-3);
        } else if (mode === 'month6') {
            labels = monthLabels12.slice(-6);
            data = monthValues12.slice(-6);
        }

        if (mode === 'daily' || mode === 'weekly') {
            labels = labels.map(d => new Date(d).toLocaleDateString('en-GB', { day: 'numeric', month: 'short' }));
        }

        collChart.data.labels = labels;
        collChart.data.datasets[0].data = data;
        collChart.update();
    }

    updateCollChart('daily');
    document.getElementById('collectionFilter').addEventListener('change', (e) => updateCollChart(e.target.value));

    // 2. Ticket Status Chart
    const ctxTicket = document.getElementById('ticketChart').getContext('2d');
    new Chart(ctxTicket, {
        type: 'doughnut',
        data: {
            labels: ticketLabels.length ? ticketLabels : ['No Data'],
            datasets: [{
                data: ticketValues.length ? ticketValues : [1],
                backgroundColor: ['#d97706', '#059669', '#dc2626', '#cbd5e1'],
                borderWidth: 0,
                hoverOffset: 6
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: { 
                legend: { 
                    position: 'right', 
                    labels: { usePointStyle: true, boxWidth: 8, font: { size: 11, family: 'Inter', weight: '500' } } 
                } 
            }
        }
    });

    // 3. Revenue Chart
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Revenue (PKR)',
                data: [],
                backgroundColor: 'rgba(5, 150, 105, 0.08)',
                borderColor: '#059669',
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#059669',
                pointBorderWidth: 2,
                pointRadius: 4,
                fill: true,
                tension: 0.35
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2, 2], color: '#f1f5f9' }, ticks: { font: { size: 11, family: 'Inter' } } },
                x: { grid: { display: false }, ticks: { font: { size: 11, family: 'Inter' } } }
            }
        }
    });

    function updateRevenueChart(months) {
        const start = Math.max(0, allRevenueLabels.length - months);
        revenueChart.data.labels = allRevenueLabels.slice(start);
        revenueChart.data.datasets[0].data = allRevenueData.slice(start);
        revenueChart.update();
    }

    updateRevenueChart(6);
    document.getElementById('revenueFilter').addEventListener('change', (e) => updateRevenueChart(parseInt(e.target.value)));
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>