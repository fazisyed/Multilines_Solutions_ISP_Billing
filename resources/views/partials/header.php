<?php
// Define path helper
$path = $data['path'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ISP Billing' ?> - Multilines Solutions</title>
    <link rel="icon" type="image/png" href="<?= asset('favicon.png') ?>">
    <link rel="stylesheet" href="<?= asset('css/style.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= asset('css/customer.css?v=' . time()) ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        :root {
            --ms-primary: #0066ff;
            --ms-primary-dark: #004ecc;
            --ms-border: #e2e8f0;
            --ms-bg: #f8fafc;
        }
        body {
            background-color: var(--ms-bg) !important;
            font-family: 'Inter', sans-serif;
            margin: 0;
            -webkit-font-smoothing: antialiased;
        }
        .top-nav {
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--ms-border);
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        }
        .nav-brand {
            display: flex;
            align-items: center;
            font-weight: 800;
            font-size: 1.05rem;
            color: var(--ms-primary);
            gap: 12px;
            text-decoration: none;
            letter-spacing: -0.01em;
        }
        .nav-brand img { 
            height: 38px; 
            object-fit: contain; 
        }
        .nav-menu {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0;
            padding: 0;
        }
        .nav-menu > li { 
            position: relative; 
        }
        .nav-menu li a, .nav-menu > li > a {
            color: #475569;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 8px 14px;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }
        .nav-menu li a:hover, .nav-menu li a.active {
            background: rgba(0, 102, 255, 0.08);
            color: var(--ms-primary);
        }
        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: #ffffff;
            border: 1px solid var(--ms-border);
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.1), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            padding: 8px;
            min-width: 210px;
            z-index: 1100;
            margin-top: 6px;
        }
        .dropdown:hover .dropdown-content { 
            display: block; 
        }
        .dropdown-content a {
            display: block !important;
            padding: 8px 12px !important;
            color: #475569 !important;
            font-size: 0.83rem !important;
            font-weight: 500 !important;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .dropdown-content a:hover { 
            background: #f8fafc; 
            color: var(--ms-primary); 
            padding-left: 16px !important;
        }
        .nav-profile { 
            position: relative; 
        }
        .profile-trigger {
            text-decoration: none;
            color: #0f172a;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 14px 5px 6px;
            border-radius: 50px;
            background: #f8fafc;
            border: 1px solid var(--ms-border);
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }
        .profile-trigger:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }
        .profile-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: #ffffff;
            border: 1px solid var(--ms-border);
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.1), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            padding: 8px;
            min-width: 210px;
            z-index: 1200;
            margin-top: 8px;
        }
        .nav-profile.active .profile-dropdown { 
            display: block; 
        }
        .profile-dropdown a {
            display: block !important;
            padding: 8px 12px !important;
            color: #475569 !important;
            font-size: 0.83rem !important;
            text-decoration: none !important;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.15s ease;
        }
        .profile-dropdown a:hover { 
            background: #f8fafc; 
            color: var(--ms-primary); 
        }
    </style>
</head>
<body>

<nav class="top-nav">
    <a href="<?= url('dashboard') ?>" class="nav-brand">
        <img src="<?= asset('images/logo.png') ?>" alt="Multilines Logo">
        <span>MULTILINES SOLUTIONS</span>
    </a>

    <ul class="nav-menu">
        <li><a href="<?= url('dashboard') ?>" class="<?= $path === '/dashboard' ? 'active' : '' ?>">Home</a></li>
        
        <li class="dropdown">
            <a href="#" class="<?= strpos($path, '/customer') !== false ? 'active' : '' ?>">Customer</a>
            <div class="dropdown-content">
                <a href="<?= url('customer/create') ?>">Create Customer</a>
                <a href="<?= url('customer') ?>">All Customers</a>
                <a href="<?= url('customer/pending') ?>">Pending Customers</a>
                <a href="<?= url('customer/recent') ?>">Recent Customer</a>
                <a href="<?= url('customer/search') ?>">Search Customer</a>
                <a href="<?= url('complain-list') ?>">Complain List</a>
            </div>
        </li>

        <li class="dropdown">
            <a href="#" class="<?= strpos($path, '/receipt') !== false ? 'active' : '' ?>">Receipt</a>
            <div class="dropdown-content">
                <a href="<?= url('receipt/area-wise') ?>">Area Wise Receipt</a>
                <a href="<?= url('receipt/customer-wise') ?>">Customer Wise Receipt</a>
                <a href="<?= url('receipt/updateBill') ?>">Update Bill</a>
            </div>
        </li>

        <li class="dropdown">
            <a href="#">Collection</a>
            <div class="dropdown-content">
                <a href="<?= url('collection/amount') ?>">Amount Collection</a>
                <a href="<?= url('collection/edit') ?>">Edit Collection</a>
                <a href="<?= url('report/collectionReport') ?>">Collection Report</a>
            </div>
        </li>

        <li class="dropdown">
            <a href="#">Report</a>
            <div class="dropdown-content">
                <a href="<?= url('report/dueList') ?>">Due List</a>
                <a href="<?= url('report/inactiveList') ?>">Inactive List</a>
                <a href="<?= url('report/customerSummary') ?>">Customer Summary</a>
            </div>
        </li>

        <li class="dropdown">
            <a href="#">Setup</a>
            <div class="dropdown-content">
                <a href="<?= url('setup/oltSetup') ?>">OLT Setup</a>
                <a href="<?= url('setup/addressSetup') ?>">Address Setup</a>
                <a href="<?= url('setup/column-preview') ?>">Column Preview Setup</a>
                <a href="<?= url('setup/customer-form') ?>">Customer Form Setup</a>
                <a href="<?= url('setup/print-preview') ?>">Print Preview Setup</a>
                <a href="<?= url('complain') ?>">Complain Setup</a>
                <a href="<?= url('employee') ?>">Employee Setup</a>
                <a href="<?= url('setup/package') ?>">Package Setup</a>
                <a href="<?= url('setup/paymentSettings') ?>">Payment Settings Setup</a>
                <a href="<?= url('setup/smsSetup') ?>">SMS Setup</a>
                <a href="<?= url('setup/whatsappSetup') ?>">WhatsApp Setup</a>
                <a href="<?= url('prefix') ?>">ID Prefix Setup</a>
            </div>
        </li>

        <li class="dropdown">
            <a href="#" class="<?= strpos($path, '/mikrotik') !== false ? 'active' : '' ?>">Mikrotik</a>
            <div class="dropdown-content">
                <a href="<?= url('mikrotik/routerConfig') ?>">Router Config</a>
                <a href="<?= url('mikrotik/userList') ?>">User List</a>
            </div>
        </li>

        <li class="dropdown">
            <a href="#" class="<?= strpos($path, '/reseller') !== false ? 'active' : '' ?>">Reseller</a>
            <div class="dropdown-content">
                <a href="<?= url('reseller/resellerList') ?>">Reseller List</a>
                <a href="<?= url('reseller/resellerPackage') ?>">Reseller Package</a>
                <a href="<?= url('reseller/resellerBalance') ?>">Reseller Balance</a>
                <a href="<?= url('reseller/resellerBalanceSummary') ?>">Reseller Balance Summary</a>
            </div>
        </li>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Super Admin'): ?>
            <li><a href="<?= url('user') ?>" class="<?= $path === '/users' ? 'active' : '' ?>">Users</a></li>
        <?php endif; ?>
    </ul>

    <div class="nav-profile dropdown" id="userDropdown">
        <a href="javascript:void(0)" class="profile-trigger" onclick="toggleDropdown(event)">
            <div style="width: 32px; height: 32px; background: var(--ms-primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700;">
                <?= strtoupper(substr($_SESSION['display_name'] ?? 'U', 0, 1)) ?>
            </div>
            <span><?= htmlspecialchars($_SESSION['display_name'] ?? 'User') ?></span>
        </a>
        <div class="profile-dropdown">
            <div style="padding: 10px 14px; border-bottom: 1px solid #f1f5f9; margin-bottom: 6px;">
                <strong style="display:block; font-size:0.88rem; color:#0f172a;"><?= htmlspecialchars($_SESSION['display_name'] ?? 'User') ?></strong>
                <span style="font-size:0.75rem; color:#64748b;"><?= htmlspecialchars($_SESSION['role'] ?? 'User') ?></span>
            </div>
            <a href="<?= url('user/profile') ?>">My Profile</a>
            <a href="<?= url('user/activity') ?>">Activity Logs</a>
            <a href="<?= url('user/changePassword') ?>">Change Password</a>
            <div style="height: 1px; background: #f1f5f9; margin: 6px 0;"></div>
            <a href="<?= url('auth/logout') ?>" style="color: #dc2626 !important;">Logout</a>
        </div>
    </div>

    <script>
        function toggleDropdown(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('userDropdown').classList.toggle('active');
        }
        document.addEventListener('click', function (e) {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });
    </script>
</nav>