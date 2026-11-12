<?php
require_once "./backend/session_init.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login_module/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$dept = $_SESSION['dept'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIC</title>
    <link rel="icon" type="image/png" sizes="32x32" href="image/icons/mkce_s.png">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-5/bootstrap-5.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --topbar-height: 60px;
            --footer-height: 60px;
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --dark-bg: #1a1c23;
            --light-bg: #f8f9fc;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Time slot styling */
        .time-slot {
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
            display: inline-block;
            min-width: 100px;
        }

        .time-slot.available {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .time-slot.booked {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .time-slot.available:hover {
            background-color: #b1dfbb;
            border-color: #9ed3a5;
        }

        .time-slot.selected {
            background-color: #007bff;
            border-color: #0056b3;
            color: white;
            font-weight: bold;
        }

        /* General Styles with Enhanced Typography */

        /* Content Area Styles */
        .content {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* Content Navigation */
        .content-nav {
            background: linear-gradient(45deg, #4e73df, #1cc88a);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .content-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
            overflow-x: auto;
        }

        .content-nav li a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .content-nav li a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar.collapsed+.content {
            margin-left: var(--sidebar-collapsed-width);
        }

        .breadcrumb-area {
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin: 20px;
            padding: 15px 20px;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: #224abe;
        }



        /* Table Styles */



        /* Ensure gradient header applies whether class is on <thead> or <tr> */
        .gradient-header,
        table.table thead.gradient-header,
        table.table tr.gradient-header,
        table.table thead .gradient-header,
        .table thead.gradient-header,
        .table tr.gradient-header {
            --bs-table-bg: transparent;
            --bs-table-color: white;
            background: linear-gradient(135deg, #4CAF50, #2196F3) !important;
            color: #fff !important;
            text-align: center;
            font-size: 0.9em;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }

            .sidebar.mobile-show {
                transform: translateX(0);
            }

            .topbar {
                left: 0 !important;
            }

            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            .mobile-overlay.show {
                display: block;
            }

            .content {
                margin-left: 0 !important;
            }

            .brand-logo {
                display: block;
            }

            .user-profile {
                margin-left: 0;
            }

            .sidebar .logo {
                justify-content: center;
            }

            .sidebar .menu-item span,
            .sidebar .has-submenu::after {
                display: block !important;
            }

            body.sidebar-open {
                overflow: hidden;
            }

            .footer {
                left: 0 !important;
            }

            .content-nav ul {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 5px;
            }

            .content-nav ul::-webkit-scrollbar {
                height: 4px;
            }

            .content-nav ul::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.3);
                border-radius: 2px;
            }
        }

        .container-fluid {
            padding: 20px;
        }


        /* loader */
        .loader-container {
            position: fixed;
            left: var(--sidebar-width);
            right: 0;
            top: var(--topbar-height);
            bottom: var(--footer-height);
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            /* Changed from 'none' to show by default */
            justify-content: center;
            align-items: center;
            z-index: 1000;
            transition: left 0.3s ease;
        }

        .sidebar.collapsed+.content .loader-container {
            left: var(--sidebar-collapsed-width);
        }

        @media (max-width: 768px) {
            .loader-container {
                left: 0;
            }
        }

        /* Hide loader when done */
        .loader-container.hide {
            display: none;
        }

        /* Loader Animation */
        .loader {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid var(--primary-color);
            border-right: 5px solid var(--success-color);
            border-bottom: 5px solid var(--primary-color);
            border-left: 5px solid var(--success-color);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .breadcrumb-area {
            background-image: linear-gradient(to top, #fff1eb 0%, #ace0f9 100%);
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin: 20px;
            padding: 15px 20px;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: #224abe;
        }

        .nav-tabs #dashboard-tab:hover,
        .nav-tabs #dashboard-tab:hover span,
        .nav-tabs #dashboard-tab:hover i {
            background-color: blue !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #dashboard-tab.active,
        .nav-tabs #dashboard-tab.active span,
        .nav-tabs #dashboard-tab.active i {
            background-color: blue !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #personal-tab:hover,
        .nav-tabs #personal-tab:hover span,
        .nav-tabs #personal-tab:hover i {
            background-color: green !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #personal-tab.active,
        .nav-tabs #personal-tab.active span,
        .nav-tabs #personal-tab.active i {
            background-color: green !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #assignedtome-tab:hover,
        .nav-tabs #assignedtome-tab:hover span,
        .nav-tabs #assignedtome-tab:hover i {
            background-color: violet !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #assignedtome-tab.active,
        .nav-tabs #assignedtome-tab.active span,
        .nav-tabs #assignedtome-tab.active i {
            background-color: violet !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #assigning-tab:hover,
        .nav-tabs #assigning-tab:hover span,
        .nav-tabs #assigning-tab:hover i {
            background-color: orange !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #assigning-tab.active,
        .nav-tabs #assigning-tab.active span,
        .nav-tabs #assigning-tab.active i {
            background-color: orange !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #meeting-tab:hover,
        .nav-tabs #meeting-tab:hover span,
        .nav-tabs #meeting-tab:hover i {
            background-color: #ea0e0eff !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #meeting-tab.active,
        .nav-tabs #meeting-tab.active span,
        .nav-tabs #meeting-tab.active i {
            background-color: #f01527ff !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #report-tab:hover,
        .nav-tabs #report-tab:hover span,
        .nav-tabs #report-tab:hover i {
            background-color: grey !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #report-tab.active,
        .nav-tabs #report-tab.active span,
        .nav-tabs #report-tab.active i {
            background-color: grey !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        /*style for meeting tab*/
        body {
            background: #f4f6f9;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            max-width: 1400px;
        }

        /* ====== BUTTONS ====== */
        .btn {
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
        }

        /* Gradient Variants */
        .btn-gradient-primary {
            background: linear-gradient(45deg, #007bff, #00a8ff);
            color: #fff !important;
        }

        .btn-gradient-primary:hover {
            background: linear-gradient(45deg, #00a8ff, #007bff);
        }

        .btn-gradient-warning {
            background: linear-gradient(45deg, #ffb100, #ffc107);
            color: #fff !important;
        }

        .btn-gradient-warning:hover {
            background: linear-gradient(45deg, #ffc107, #ffb100);
        }

        .btn-gradient-danger {
            background: linear-gradient(45deg, #dc3545, #ff4b5c);
            color: #fff !important;
        }

        .btn-gradient-danger:hover {
            background: linear-gradient(45deg, #ff4b5c, #dc3545);
        }

        .btn-gradient-success {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: #fff !important;
        }

        .btn-gradient-success:hover {
            background: linear-gradient(45deg, #20c997, #28a745);
        }

        /* ====== BADGES ====== */
        .badge-pending,
        .badge-approved,
        .badge-rejected {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            min-width: 80px;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }

        .badge-approved {
            background-color: #28a745;
            color: #fff;
        }

        .badge-rejected {
            background-color: #dc3545;
            color: #fff;
        }

        /* ====== TABS ====== */
        .nav-tabs .nav-link {
            border: none;
            font-weight: 600;
            color: #217faaff;
            padding: 10px 18px;
            transition: all 0.2s ease;
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(45deg, #fdfffdff);
            color: black !important;
            border-radius: 8px 8px 0 0;
        }

        .nav-tabs .nav-link:hover:not(.active) {
            background-color: #e9ecef;
            border-radius: 8px 8px 0 0;
        }

        /* ====== DATA TABLE ====== */
        table.dataTable {
            border-collapse: collapse !important;
            width: 100%;
        }

        /* ✅ Continuous Gradient Header Across Full Table */
        table.dataTable thead tr {
            background: linear-gradient(to right, #4caf50, #2196f3) !important;
        }

        table.dataTable thead th {
            background: transparent !important;
            /* Prevents gradient repetition */
            color: #fff !important;
            font-weight: 600;
            text-align: center !important;
            padding: 12px !important;
            border-bottom: none !important;
            white-space: nowrap;
        }

        /* Optional: rounded header edges for better aesthetics */
        table.dataTable thead tr:first-child th:first-child {
            border-top-left-radius: 8px;
        }

        table.dataTable thead tr:first-child th:last-child {
            border-top-right-radius: 8px;
        }


        table.dataTable tbody tr {

            transition: all 0.25s ease-in-out;
        }

        table.dataTable tbody tr:hover {
            background: #f5f7fa !important;
            transform: scale(1.01);
        }

        table.dataTable td {
            vertical-align: middle;
            text-align: center;
            padding: 10px 8px !important;
        }

        /* Pagination + Search */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px !important;
            padding: 5px 10px !important;
            margin: 0 2px;
            background: #e9ecef;
            transition: background 0.2s ease;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #007bff;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 6px !important;
            border: 1px solid #ddd !important;
            padding: 6px 10px !important;
            margin-left: 8px;
        }

        /* ====== CARD ====== */
        .meeting-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 15px 20px;
            margin-bottom: 30px;
        }

        /* ====== MODALS ====== */
        .modal-header {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            border-bottom: none;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            padding: 20px 24px;
        }

        .modal {
            z-index: 2000 !important;
        }

        .modal-backdrop {
            z-index: 1050 !important;
        }

        .btn-close-white {
            filter: invert(1);
        }

        /* ====== FORM FIELDS ====== */
        .form-label {
            font-weight: 500;
            margin-bottom: 6px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 8px 10px;
            transition: border-color 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.2);
            outline: none;
        }

        /* ====== UTILITIES ====== */
        .d-flex.gap-2 button {
            min-width: 80px;
        }

        #scheduledMeetingsTable tbody tr:nth-child(even) {
            background-color: rgb(224, 224, 253) !important;
        }

        #scheduledMeetingsTable tbody tr:nth-child(odd) {
            background-color: white !important;
        }

        #myAddMeetingModal .modal-header {
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="content">

        <div class="loader-container" id="loaderContainer">
            <div class="loader"></div>
        </div>

        <!-- Topbar -->
        <?php include 'topbar.php'; ?>

        <!-- Content Area -->
        <div class="container-fluid">
            <div class="custom-tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="personal-tab" data-bs-toggle="tab" href="#personal" role="tab"
                            aria-controls="personal" aria-selected="false">
                            <span class="hidden-xs-down" style="font-size: 0.9em;font-family: 'Poppins', 'Open Sans', sans-serif;
font-weight: 600;color:green;">
                                <i class="fas fa-list-check tab-icon"></i> Personal ToDo
                            </span>
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="assigned-tab" data-bs-toggle="tab" href="#assigned" role="tab"
                            aria-controls="assigned" aria-selected="false">
                            <span class="hidden-xs-down" style="font-size: 0.9em;font-family: 'Poppins', 'Open Sans', sans-serif;
font-weight: 600;color:orange;">
                                <i class="fas fa-user-tag tab-icon"></i> My Task
                            </span>
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="assigning-tab" data-bs-toggle="tab" href="#assigning" role="tab"
                            aria-controls="assigning" aria-selected="false">
                            <span class="hidden-xs-down" style="font-size: 0.9em;font-family: 'Poppins', 'Open Sans', sans-serif;
font-weight: 600;color:orange;">
                                <i class="fas fa-user-tag tab-icon"></i> Assigned Task
                            </span>
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="meeting-tab" data-bs-toggle="tab" href="#meeting" role="tab"
                            aria-controls="meeting" aria-selected="false">
                            <span class="hidden-xs-down" style="font-size: 0.9em;font-family: 'Poppins', 'Open Sans', sans-serif;
font-weight: 600;color:red;">
                                <i class="fas fa-handshake tab-icon"></i> Meeting
                            </span>
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="report-tab" data-bs-toggle="tab" href="#report" role="tab"
                            aria-controls="report" aria-selected="false">
                            <span class="hidden-xs-down" style="font-size: 0.9em;font-family: 'Poppins', 'Open Sans', sans-serif;
font-weight: 600;color:grey;">
                                <i class="fas fa-chart-line tab-icon"></i> Report and Analysis
                            </span>
                        </a>
                    </li>

                </ul>

                <div class="tab-content">

                    <!-- Personal-->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                        <?php include "./personalToDo.php"; ?>
                    </div>

                    <!-- Assign to Someone -->
                    <div class="tab-pane fade" id="assigning" role="tabpanel" aria-labelledby="assigning-tab">
                        <?php include "./assigningTSomeone_pr.php"; ?>
                    </div>

                    <div class="tab-pane fade" id="assigned" role="tabpanel" aria-labelledby="assigned-tab">
                        <?php include "./assigntome_p.php"; ?>
                    </div>


                    <!-- Meeting -->
                    <div class="tab-pane fade" id="meeting" role="tabpanel" aria-labelledby="meeting-tab">
                        <div class="container-fluid py-4">
                            <!-- Buttons -->
                            <div class="d-flex justify-content-end mb-4 gap-3">
                                <button id="myOpenAddBtn"
                                    class="btn btn-gradient-primary d-flex align-items-center gap-2">
                                    <i class="fas fa-calendar-plus"></i> Add Meeting
                                </button>
                                <button id="requestChairmanMeetingBtn"
                                    class="btn btn-gradient-primary d-flex align-items-center gap-2">
                                    <i class="fas fa-user-tie"></i> Request Meeting to Chairman
                                </button>
                            </div>

                            <!-- ✅ Tabs for Meetings -->
                            <div class="meeting-card">
                                <ul class="nav nav-tabs mb-3" id="meetingTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="scheduled-tab" data-bs-toggle="tab"
                                            data-bs-target="#scheduled" type="button" role="tab">Scheduled
                                            Meetings</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="approve-reject-tab" data-bs-toggle="tab"
                                            data-bs-target="#approve-reject" type="button" role="tab">Approve/Reject
                                            Meetings</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="requested-tab" data-bs-toggle="tab"
                                            data-bs-target="#requested" type="button" role="tab">Requested
                                            Meetings</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="chairman-tab" data-bs-toggle="tab"
                                            data-bs-target="#chairman" type="button" role="tab">Chairman
                                            Meetings</button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <!-- Scheduled Meetings Table -->
                                    <div class="tab-pane fade show active" id="scheduled" role="tabpanel">
                                        <table id="scheduledMeetingsTable" class="table table-striped table-hover"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Staff</th>
                                                    <th>Date & Time</th>
                                                    <th>Location</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <!-- Approve/Reject Meetings Table -->
                                    <div class="tab-pane fade" id="approve-reject" role="tabpanel">
                                        <table id="approveRejectTable" class="table table-striped table-hover"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Requested By</th>
                                                    <th>Request Time</th>
                                                    <th>Purpose</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <!-- Requested Meetings Table (Principal requests to Chairman) -->
                                    <div class="tab-pane fade" id="requested" role="tabpanel">
                                        <table id="requestedMeetingsTable" class="table table-striped table-hover"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Purpose</th>
                                                    <th>Requested Time</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <!-- Chairman Meetings Table (Meetings scheduled by Chairman for Principal) -->
                                    <div class="tab-pane fade" id="chairman" role="tabpanel">
                                        <table id="chairmanMeetingsTable" class="table table-striped table-hover"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Agenda</th>
                                                    <th>Date & Time</th>
                                                    <th>Location</th>

                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- ✅ Add Meeting Modal -->
                        <div class="modal fade" id="myAddMeetingModal" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTitle">Schedule Staff Meeting</h5>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>
                                    <form id="myAddMeetingForm">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Meeting Title</label>
                                                <input type="text" class="form-control" name="title" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Agenda</label>
                                                <textarea class="form-control" name="agenda" required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Participant Type</label>
                                                <select class="form-select" id="meetingAssignType" required>
                                                    <option value="">-- Select Type --</option>
                                                    <option value="hod">HOD</option>
                                                    <option value="portfolio">Portfolio</option>
                                                    <option value="faculty">Faculty</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 d-none" id="meetingDeptSection">
                                                <label class="form-label">Select Department</label>
                                                <select class="form-select" id="meetingDeptDropdown">
                                                    <option value="">-- Select Department --</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 d-none" id="meetingPortfolioSection">
                                                <label class="form-label">Select Portfolio Category</label>
                                                <select class="form-select" id="meetingPortfolioDropdown">
                                                    <option value="">-- Select Category --</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 d-none" id="meetingFacultySection">
                                                <label class="form-label">Select Participant</label>
                                                <select class="form-select" name="fac_name" id="meetingFacultyDropdown"
                                                    required>
                                                    <option value="">-- Select --</option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="staff" id="staffDropdown" value="">
                                            <div class="mb-3">
                                                <label class="form-label">Date & Time</label>
                                                <input  id='edmdatetime' type="datetime-local" class="form-control" name="datetime"
                                                    required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Mode</label>
                                                <select class="form-select" name="mode">
                                                    <option>Online</option>
                                                    <option>Offline</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Location / Link</label>
                                                <input type="text" class="form-control" name="location" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-gradient-danger"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-gradient-primary"
                                                id="submitMeetingBtn">Schedule
                                                Meeting</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Request Meeting to Chairman Modal -->
                        <div class="modal fade" id="requestChairmanMeetingModal" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Request Meeting to Chairman</h5>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>
                                    <form id="requestChairmanMeetingForm">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Purpose</label>
                                                <textarea class="form-control" name="purpose" required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Select Date</label>
                                                <div class="d-flex gap-2">
                                                    <input type="date" class="form-control" id="chairmanMeetingDate"
                                                        name="meeting_date" required>
                                                    <button type="button" class="btn btn-primary"
                                                        id="viewChairmanSlotsBtn">
                                                        View Available Slots
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mb-3" id="chairmanTimeSlotsContainer" style="display: none;">
                                                <label class="form-label">Available Time Slots</label>
                                                <div id="chairmanTimeSlots">
                                                    <!-- Time slots will be populated here -->
                                                </div>
                                            </div>
                                            <input type="hidden" name="selected_time" id="chairmanSelectedTime">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Submit Request</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Report -->
                    <div class="tab-pane fade" id="report" role="tabpanel" aria-labelledby="report-tab">
                        <?php include "./report.php"; ?>
                    </div>

                </div>
            </div>
        </div>


        <!-- Footer -->
        <?php include 'footer.php'; ?>
    </div>

    <script>
        // Ensure global loaderContainer is available and guarded against missing element
        if (!window.loaderContainer) {
            window.loaderContainer = document.getElementById('loaderContainer');
        }

        function showLoader() {
            if (window.loaderContainer) window.loaderContainer.classList.add('show');
        }

        function hideLoader() {
            if (window.loaderContainer) window.loaderContainer.classList.remove('show');
        }

        //    automatic loader
        document.addEventListener('DOMContentLoaded', function () {
            const contentWrapper = document.getElementById('contentWrapper');
            let loadingTimeout;

            function hideLoaderLocal() {
                loaderContainer.classList.add('hide');
                if (contentWrapper) contentWrapper.classList.add('show');
            }

            function showError() {
                console.error('Page load took too long or encountered an error');
                // You can add custom error handling here
            }

            // Set a maximum loading time (10 seconds)
            loadingTimeout = setTimeout(showError, 10000);

            // Hide loader when everything is loaded
            window.onload = function () {
                clearTimeout(loadingTimeout);

                // Add a small delay to ensure smooth transition
                setTimeout(hideLoaderLocal, 500);
            };

            // Error handling
            window.onerror = function (msg, url, lineNo, columnNo, error) {
                clearTimeout(loadingTimeout);
                showError();
                return false;
            };
        });

        // Toggle Sidebar
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const body = document.body;
        const mobileOverlay = document.getElementById('mobileOverlay');

        function toggleSidebar() {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('mobile-show');
                mobileOverlay.classList.toggle('show');
                body.classList.toggle('sidebar-open');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        }
        hamburger.addEventListener('click', toggleSidebar);
        mobileOverlay.addEventListener('click', toggleSidebar);
        // Toggle User Menu
        const userMenu = document.getElementById('userMenu');
        const dropdownMenu = userMenu.querySelector('.dropdown-menu');

        userMenu.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', () => {
            dropdownMenu.classList.remove('show');
        });

        // Toggle Submenu
        const menuItems = document.querySelectorAll('.has-submenu');
        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                const submenu = item.nextElementSibling;
                item.classList.toggle('active');
                submenu.classList.toggle('active');
            });
        });

        // Handle responsive behavior
        window.addEventListener('resize', () => {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('collapsed');
                sidebar.classList.remove('mobile-show');
                mobileOverlay.classList.remove('show');
                body.classList.remove('sidebar-open');
            } else {

                sidebar.style.transform = '';
                mobileOverlay.classList.remove('show');
                body.classList.remove('sidebar-open');
            }
        });

        // Dynamic loading for Assign to Someone tab
        document.addEventListener('DOMContentLoaded', function () {
            const assigningTab = document.getElementById('assigning-tab');
            let contentLoaded = false;

            if (assigningTab) {
                assigningTab.addEventListener('shown.bs.tab', function () {
                    if (!contentLoaded) {
                        const contentDiv = document.getElementById('assigning-content');
                        contentDiv.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>';

                        fetch('./assigningTSomeone.php')
                            .then(response => response.text())
                            .then(data => {
                                contentDiv.innerHTML = data;
                                contentLoaded = true;
                            })
                            .catch(error => {
                                contentDiv.innerHTML = '<div class="alert alert-danger">Error loading content</div>';
                            });
                    }
                });
            }
        });


        //script for meeting tab
        $(document).ready(function () {
            // Initialize DataTables with SIMPLE configuration
            const scheduledTable = $('#scheduledMeetingsTable').DataTable({
                pageLength: 5,
                language: {
                    emptyTable: "No scheduled meetings"
                }
            });

            // Initialize Approve/Reject Table
            const approveRejectTable = $('#approveRejectTable').DataTable({
                pageLength: 5,
                language: {
                    emptyTable: "No pending meeting requests"
                }
            });

            // Initialize Requested Meetings Table
            const requestedMeetingsTable = $.fn.DataTable.isDataTable('#requestedMeetingsTable')
                ? $('#requestedMeetingsTable').DataTable()
                : $('#requestedMeetingsTable').DataTable({
                    pageLength: 5,
                    language: {
                        emptyTable: "No meeting requests sent to Chairman"
                    }
                });

            // Initialize Chairman Meetings Table
            const chairmanMeetingsTable = $.fn.DataTable.isDataTable('#chairmanMeetingsTable')
                ? $('#chairmanMeetingsTable').DataTable()
                : $('#chairmanMeetingsTable').DataTable({
                    pageLength: 5,
                    language: {
                        emptyTable: "No meetings scheduled by Chairman"
                    }
                });
            // set min date todayyy to add meeting modal
            function setDateMinToToday(selector) {
                const t = new Date();

                const year = t.getFullYear();
                const month = String(t.getMonth() + 1).padStart(2, '0');
                const day = String(t.getDate()).padStart(2, '0');
                const hours = String(t.getHours()).padStart(2, '0');
                const minutes = String(t.getMinutes()).padStart(2, '0');

                // Format: YYYY-MM-DDTHH:MM
                const todayDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

                $(selector).attr('min', todayDateTime);
            }

            // For datetime-local input
            setDateMinToToday('#edmdatetime');

            function enforceFutureDateTime(selector, bufferMinutes = 1) {
                const pad = n => String(n).padStart(2, '0');
                const bufMs = () => bufferMinutes * 60 * 1000;

                function minStr() {
                    const now = new Date(Date.now() + bufMs());
                    return `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;
                }

                function parseLocal(v) {
                    if (!v) return null;
                    const [d, t = '00:00'] = v.split('T');
                    const [y, m, day] = d.split('-').map(Number);
                    const [hh, mm] = t.split(':').map(Number);
                    return new Date(y, (m || 1) - 1, day || 1, hh || 0, mm || 0, 0, 0);
                }

                function isValid(v) {
                    const dt = parseLocal(v);
                    return dt && !isNaN(dt.getTime()) && dt.getTime() >= (Date.now() + bufMs());
                }

                // set min once and every 30s
                function setMinAll() {
                    $(selector).attr('min', minStr());
                    $(selector).attr('step', 60);
                }
                setMinAll();
                const iv = setInterval(setMinAll, 30000);

                // handler
                function handler() {
                    const $el = $(this);
                    const v = $el.val();
                    if (!v) return;
                    if (!isValid(v)) {
                        // notify — use Swal if available, else alert
                        if (window.Swal && typeof Swal.fire === 'function') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid time',
                                text: 'Please select a future date & time',
                                toast: true,
                                position: 'top',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            alert('Please select a future date & time');
                        }
                        $el.val('');
                        setMinAll();
                    }
                }

                // attach
                $(document).on('input.enf change.enf blur.enf', selector, handler);

                // return small API
                return {
                    isValid: isValid,
                    refresh: setMinAll,
                    destroy: function () {
                        clearInterval(iv);
                        $(document).off('.enf');
                    }
                };
            }

            const dtGuard = enforceFutureDateTime('input[name="datetime"]', 1);
            const meetingAssignType = $('#meetingAssignType');
            const meetingDeptSection = $('#meetingDeptSection');
            const meetingDeptDropdown = $('#meetingDeptDropdown');
            const meetingPortfolioSection = $('#meetingPortfolioSection');
            const meetingPortfolioDropdown = $('#meetingPortfolioDropdown');
            const meetingFacultySection = $('#meetingFacultySection');
            const meetingFacultyDropdown = $('#meetingFacultyDropdown');
            const staffHiddenInput = $('#staffDropdown');

            let departmentsLoaded = false;
            let portfoliosLoaded = false;

            function resetParticipantSelectors() {
                meetingAssignType.val('');
                staffHiddenInput.val('');
                meetingDeptDropdown.val('');
                meetingPortfolioDropdown.val('');
                meetingFacultyDropdown.empty().append('<option value="">-- Select --</option>');
                meetingDeptSection.addClass('d-none');
                meetingPortfolioSection.addClass('d-none');
                meetingFacultySection.addClass('d-none');
            }

            function ensureDepartmentsLoaded() {
                if (departmentsLoaded) return;
                $.post('backend/api.php', { action: 'filterdept' }, function (res) {
                    if (Array.isArray(res)) {
                        res.forEach(r => {
                            meetingDeptDropdown.append(`<option value="${r.deptshort}">${r.dname}</option>`);
                        });
                        departmentsLoaded = true;
                    }
                }, 'json').fail(function (err) {
                    console.error('Department load failed', err);
                });
            }

            function ensurePortfoliosLoaded() {
                if (portfoliosLoaded) return;
                $.post('backend/api.php', { action: 'filterport' }, function (res) {
                    if (Array.isArray(res)) {
                        res.forEach(r => {
                            meetingPortfolioDropdown.append(`<option value="${r.pname}">${r.pname}</option>`);
                        });
                        portfoliosLoaded = true;
                    }
                }, 'json').fail(function (err) {
                    console.error('Portfolio load failed', err);
                });
            }

            function populateHodList() {
                meetingFacultyDropdown.empty().append('<option value="">-- Select --</option>');
                $.post('backend/api.php', { action: 'filterhod', role: 'HOD' }, function (res) {
                    if (Array.isArray(res)) {
                        res.forEach(r => {
                            meetingFacultyDropdown.append(`<option value="${r.name}">${r.name} (${r.dept})</option>`);
                        });
                        meetingFacultySection.removeClass('d-none');
                    }
                }, 'json').fail(function (err) {
                    console.error('HOD load failed', err);
                });
            }

            function populateFacultyByGroup(groupKey) {
                meetingFacultyDropdown.empty().append('<option value="">-- Select --</option>');
                
                $.post('backend/api.php', { action: 'filterfaculty', dept: groupKey }, function (res) {
                    if (Array.isArray(res)) {
                        res.forEach(r => {
                            meetingFacultyDropdown.append(`<option value="${r.name}">${r.name} (${r.dept})</option>`);
                        });
                        meetingFacultySection.removeClass('d-none');
                    }
                }, 'json').fail(function (err) {
                    console.error('Faculty load failed', err);
                });
            }

            meetingAssignType.on('change', function () {
                const type = $(this).val();
                staffHiddenInput.val('');
                meetingFacultyDropdown.empty().append('<option value="">-- Select --</option>');
                meetingDeptSection.addClass('d-none');
                meetingPortfolioSection.addClass('d-none');
                meetingFacultySection.addClass('d-none');

                if (type === 'hod') {
                    staffHiddenInput.val('HOD');
                    populateHodList();
                } else if (type === 'portfolio') {
                    ensurePortfoliosLoaded();
                    meetingPortfolioSection.removeClass('d-none');
                } else if (type === 'faculty') {
                    ensureDepartmentsLoaded();
                    meetingDeptSection.removeClass('d-none');
                }
            });

            meetingDeptDropdown.on('change', function () {
                const dept = $(this).val();
                staffHiddenInput.val(dept || '');
                if (dept) {
                    populateFacultyByGroup(dept, true);
                } else {
                    meetingFacultySection.addClass('d-none');
                }
            });

            meetingPortfolioDropdown.on('change', function () {
                const category = $(this).val();
                meetingFacultyDropdown.empty().append('<option value="">-- Select --</option>');
                $.post('backend/api.php', { action: 'filterport_staffs', pfdept: category }, function (res) {
                    if (Array.isArray(res)) {
                        res.forEach(r => {
                            meetingFacultyDropdown.append(`<option value="${r.name}">${r.name} (${r.dept})</option>`);
                        });
                        meetingFacultySection.removeClass('d-none');
                    }
                }, 'json').fail(function (err) {
                    console.error('Faculty load failed', err);
                });
            });
            // Get base URL dynamically - SIMPLE VERSION
            function getBaseUrl() {
                return 'backend/backend_combined.php';
            }

            // Store current editing meeting ID
            let currentEditId = null;

            // Open Add Meeting Modal
            $('#myOpenAddBtn').on('click', function () {
                currentEditId = null;
                $('#myAddMeetingModal').appendTo('body').modal('show');
                $('#modalTitle').text('Schedule Staff Meeting');
                $('#submitMeetingBtn').html('Schedule Meeting');
                $('#myAddMeetingForm')[0].reset();

                // Load staff list
                resetParticipantSelectors();
            });

            // Open Edit Meeting Modal
            function openEditMeeting(meetingId, meetingData) {
                currentEditId = meetingId;
                $('#myAddMeetingModal').appendTo('body').modal('show');
                $('#modalTitle').text('Edit Meeting');
                $('#submitMeetingBtn').html('Update Meeting');
                resetParticipantSelectors();

                // Parse meeting data and fill form
                const title = meetingData.title.replace(/ \(.*\)$/, '');
                const mode = meetingData.mode || 'Online';

                $('#myAddMeetingForm input[name="title"]').val(title);
                $('#myAddMeetingForm textarea[name="agenda"]').val(meetingData.agenda);
                $('#myAddMeetingForm input[name="datetime"]').val(meetingData.original_datetime);
                $('#myAddMeetingForm select[name="mode"]').val(mode);
                $('#myAddMeetingForm input[name="location"]').val(meetingData.location);

                $('#staffDropdown').val('Everyone');
            }

            // ✅ FIXED Load Meetings Function - Store meeting data properly
            function loadMeetings() {
                // Load scheduled meetings
                $.ajax({
                    url: getBaseUrl() + '?route=addMeeting&fetchMeetings=1',
                    dataType: 'json',
                    success: function (meetings) {
                        console.log('Meetings loaded:', meetings);
                        scheduledTable.clear();

                        if (Array.isArray(meetings) && meetings.length > 0) {
                            meetings.forEach(m => {
                                const actions = `
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-primary btn-sm cfm-notify-btn" 
                                        data-email="${m.email || m.staff_email || ''}"
                                        data-title="${m.title || m.event_title || ''}"
                                        data-datetime="${m.datetime || ''}"
                                        data-staff="${m.staff || m.staff_name || ''}"
                                        data-venue="${m.location || m.venue || ''}"
                                        data-agenda="${m.agenda || m.description || ''}">
                                        <i class="fas fa-bell"></i> Notify
                                    </button>
                                </div>`;
                                // Store the meeting data in DataTables row for easy access
                                const rowNode = scheduledTable.row.add([
                                    m.title || 'No Title',
                                    m.staff || 'No Staff',
                                    m.datetime || 'No Date',
                                    m.location || 'No Location',
                                    actions
                                ]).draw(false).node();

                                // Store the full meeting data in the row for editing
                                $(rowNode).data('meeting-data', m);
                            });
                        } else {
                            scheduledTable.draw();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Failed to load scheduled meetings:', error);
                        scheduledTable.clear().draw();
                    }
                });

                // Load appointment requests for approval/rejection
                loadAppointmentRequests();
            }

            // Load appointment requests from request_appointment table
            function loadAppointmentRequests() {
                $.ajax({
                    url: getBaseUrl() + '?route=requestMeeting&fetchAppointmentRequests=1',
                    dataType: 'json',
                    success: function (response) {
                        console.log('Appointment requests loaded:', response);
                        approveRejectTable.clear();
                        if (response.success && Array.isArray(response.data) && response.data.length >
                            0) {
                            response.data.forEach(req => {
                                const statusBadge = req.status === 'Pending' ?
                                    `<span class="badge badge-pending">${req.status}</span>` :
                                    req.status === 'Approved' ?
                                        `<span class="badge badge-approved">${req.status}</span>` :
                                        `<span class="badge badge-rejected">${req.status}</span>`;

                                const actionButtons = req.status === 'Pending' ?
                                    `<div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-gradient-success btn-approve" data-id="${req.request_id}">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button class="btn btn-sm btn-gradient-danger btn-reject" data-id="${req.request_id}">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </div>` :
                                    `<span class="text-muted">No action required</span>`;

                                approveRejectTable.row.add([
                                    req.requester_name || req.request_by || 'Unknown',
                                    req.request_time ? new Date(req.request_time)
                                        .toLocaleString() : 'N/A',
                                    req.purpose || 'N/A',
                                    statusBadge,
                                    actionButtons
                                ]);
                            });
                        }
                        approveRejectTable.draw();
                    },
                    error: function (xhr, status, error) {
                        console.error('Failed to load appointment requests:', error);
                        approveRejectTable.clear().draw();
                    }
                });
            }

            // Load Requested Meetings (Principal's requests to Chairman)
            function loadRequestedMeetings() {
                $.ajax({
                    url: getBaseUrl() + '?route=requestMeeting&fetchPrincipalRequests=1',
                    dataType: 'json',
                    success: function (response) {
                        console.log('Principal requests loaded:', response);

                        // Ensure table is initialized
                        if (!$.fn.DataTable.isDataTable('#requestedMeetingsTable')) {
                            $('#requestedMeetingsTable').DataTable({
                                pageLength: 5,
                                language: {
                                    emptyTable: "No meeting requests sent to Chairman"
                                }
                            });
                        }

                        const table = $('#requestedMeetingsTable').DataTable();
                        table.clear();

                        if (response.success && Array.isArray(response.data) && response.data.length > 0) {
                            response.data.forEach(req => {
                                const statusBadge = req.status === 'Pending' ?
                                    `<span class="badge badge-pending">${req.status}</span>` :
                                    req.status === 'Approved' ?
                                        `<span class="badge badge-approved">${req.status}</span>` :
                                        `<span class="badge badge-rejected">${req.status}</span>`;

                                const actionButtons = req.status === 'Pending' ?
                                    `<button class="btn btn-sm btn-gradient-danger btn-cancel-request" data-id="${req.request_id}" title="Cancel this request">
                                        <i class="fas fa-trash"></i> Cancel
                                    </button>` :
                                    `<span class="text-muted">-</span>`;

                                table.row.add([
                                    req.purpose || 'N/A',
                                    req.request_time ? new Date(req.request_time).toLocaleString() : 'N/A',
                                    statusBadge,
                                    actionButtons
                                ]);
                            });
                        }
                        table.draw();
                    },
                    error: function (xhr, status, error) {
                        console.error('Failed to load principal requests:', error, xhr.responseText);

                        if (!$.fn.DataTable.isDataTable('#requestedMeetingsTable')) {
                            $('#requestedMeetingsTable').DataTable({
                                pageLength: 5,
                                language: {
                                    emptyTable: "No meeting requests sent to Chairman"
                                }
                            });
                        }

                        const table = $('#requestedMeetingsTable').DataTable();
                        table.clear().draw();
                    }
                });
            }

            // Load Chairman Meetings (meetings scheduled by Chairman for this Principal)
            function loadChairmanMeetings() {
                // Primary request: endpoint that returns { success: true, data: [...] }
                $.ajax({
                    url: getBaseUrl() + '?route=addMeeting&fetchChairmanMeetings=1',
                    dataType: 'json',
                    success: function (response) {
                        console.log('Chairman meetings loaded (primary):', response);
                        processChairmanResponse(response);
                    },
                    error: function (xhr, status, error) {
                        console.error('Primary fetch failed, trying fallback:', error, xhr.responseText);
                        // Fallback: try principalMeetings endpoint and filter for CHAIRMAN assigners
                        $.ajax({
                            url: getBaseUrl() + '?route=principalMeetings',
                            dataType: 'json',
                            success: function (resp2) {
                                console.log('Chairman meetings loaded (fallback):', resp2);
                                // resp2 is expected { success: true, data: [...] }
                                // filter those where assigned_by looks like Chairman (role check done in backend for some handlers)
                                processChairmanResponse(resp2, true);
                            },
                            error: function (xhr2, s2, e2) {
                                console.error('Fallback also failed:', e2, xhr2.responseText);
                                // Ensure table exists and clear it
                                if (!$.fn.DataTable.isDataTable('#chairmanMeetingsTable')) {
                                    $('#chairmanMeetingsTable').DataTable({
                                        pageLength: 5,
                                        language: { emptyTable: "No meetings scheduled by Chairman" }
                                    });
                                }
                                $('#chairmanMeetingsTable').DataTable().clear().draw();
                            }
                        });
                    }
                });

                function processChairmanResponse(response, fallback = false) {
                    if (!$.fn.DataTable.isDataTable('#chairmanMeetingsTable')) {
                        $('#chairmanMeetingsTable').DataTable({
                            pageLength: 5,
                            language: { emptyTable: "No meetings scheduled by Chairman" }
                        });
                    }

                    const table = $('#chairmanMeetingsTable').DataTable();
                    table.clear();

                    // If backend returned a plain array (legacy), accept it
                    let meetings = [];
                    if (response && response.success === true && Array.isArray(response.data)) {
                        meetings = response.data;
                    } else if (Array.isArray(response)) {
                        meetings = response;
                    } else if (fallback && response && response.success === true && Array.isArray(response.data)) {
                        // If fallback returned meetings, use them but try to filter by assigned_by if available
                        meetings = response.data.filter(m => {
                            // If backend included assigned_by or assigned_by role, prefer that
                            if (m.assigned_by && typeof m.assigned_by === 'string') {
                                return /chairman/i.test(m.assigned_by) || /chairman/i.test(m.assigned_by_name || '');
                            }
                            // otherwise include and hope it's relevant
                            return true;
                        });
                    }

                    if (meetings.length > 0) {
                        meetings.forEach(meeting => {
                            table.row.add([
                                meeting.title || meeting.event_title || 'N/A',
                                meeting.agenda || meeting.event_description || 'N/A',
                                meeting.datetime ? new Date(meeting.datetime).toLocaleString() : (meeting.schedule_date ? meeting.schedule_date : 'N/A'),
                                meeting.location || meeting.venue || 'N/A',
                                meeting.mode || 'N/A'
                            ]);
                        });
                    }

                    table.draw();
                }
            }

            // Initial load
            loadMeetings();

            // Tab event handlers to load data when tabs are clicked
            $('#requested-tab').on('shown.bs.tab', function () {
                loadRequestedMeetings();
            });

            $('#chairman-tab').on('shown.bs.tab', function () {
                loadChairmanMeetings();
            });

            $('#approve-reject-tab').on('shown.bs.tab', function () {
                loadAppointmentRequests();
            });

            // Add/Edit Meeting Form
            $('#myAddMeetingForm').on('submit', function (e) {
                e.preventDefault();
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                submitBtn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Processing...');

                // Collect form data explicitly to ensure fields are present
                const formDataObj = {
                    title: $('input[name="title"]').val() || '',
                    agenda: $('textarea[name="agenda"]').val() || '',
                    staff: $('#staffDropdown').val() || 'Everyone',
                    fac_name: $('#meetingFacultyDropdown').val() || '',
                    datetime: $('input[name="datetime"]').val() || '',
                    mode: $('select[name="mode"]').val() || 'Online',
                    location: $('input[name="location"]').val() || '',
                    assigned_by: "<?php echo $user_id; ?>"
                };

                let url = getBaseUrl() + '?route=addMeeting';
                if (currentEditId) {
                    url += '&updateMeeting=1';
                    formDataObj.calendar_id = currentEditId;
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formDataObj,
                    dataType: 'json',
                    success: function (response) {
                        console.log('Server response:', response);
                        if (response && response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message || 'Meeting saved',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            $('#myAddMeetingModal').modal('hide');
                            $('#myAddMeetingForm')[0].reset();
                            loadMeetings();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: (response && response.message) || 'Failed to save meeting'
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', error);
                        console.error('Response:', xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Connection Error',
                            text: 'Failed to connect to server. See console for details.'
                        });
                    },
                    complete: function () {
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // ✅ FIXED Event Delegation for dynamic buttons - Edit
            $(document).on('click', '.btn-edit', function () {
                const meetingId = $(this).data('id');
                const row = $(this).closest('tr');
                const meetingData = $(row).data('meeting-data');

                if (meetingData) {
                    openEditMeeting(meetingId, meetingData);
                } else {
                    // Fallback: Try to get data from DataTables
                    const rowData = scheduledTable.row(row).data();
                    if (rowData) {
                        const meetingData = {
                            title: rowData[0],
                            staff: rowData[1],
                            datetime: rowData[2],
                            location: rowData[3],
                            agenda: 'Sample agenda', // You might need to adjust this
                            original_datetime: rowData[2], // Use the displayed datetime
                            mode: 'Online'
                        };
                        openEditMeeting(meetingId, meetingData);
                    }
                }
            });

            // ✅ Notify Button - using cfm-notify-btn class
            $(document).on('click', '.cfm-notify-btn', function () {
                const btn = $(this);
                const email = btn.data('email');
                const title = btn.data('title');
                const datetime = btn.data('datetime');
                const staff = btn.data('staff');
                const venue = btn.data('venue') || '';
                const agenda = btn.data('agenda') || '';

                if (!email || !title || !datetime || !staff) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Incomplete details',
                        text: 'Missing email or meeting details. Please reload the meetings list.'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Notify staff?',
                    text: 'Send meeting notification via email.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, notify'
                }).then(res => {
                    if (res.isConfirmed) {
                        $.ajax({
                            url: getBaseUrl() + '?route=notifyMeeting',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                email: email,
                                title: title,
                                datetime: datetime,
                                staff: staff,
                                venue: venue,
                                agenda: agenda
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        toast: true,
                                        icon: 'success',
                                        title: 'Email notification sent',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    btn.removeClass('btn-primary')
                                        .addClass('btn-success')
                                        .html('<i class="fas fa-check"></i> Notified')
                                        .prop('disabled', true);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message || 'Failed to send notification'
                                    });
                                }
                            },
                            error: function () {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to send notification'
                                });
                            }
                        });
                    }
                });
            });

            // Approve Appointment Request
            $(document).on('click', '.btn-approve', function () {
                const requestId = $(this).data('id');
                const btn = $(this);

                Swal.fire({
                    title: 'Approve Request?',
                    text: 'This will approve the meeting request.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Approve',
                    confirmButtonColor: '#28a745'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: getBaseUrl(),
                            type: 'POST',
                            data: {
                                route: 'requestMeeting',
                                approveRequest: 1,
                                request_id: requestId
                            },
                            dataType: 'json',
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Approved!',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                    loadAppointmentRequests();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message || 'Failed to approve request'
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Approve error:', error, xhr.responseText);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Connection Error',
                                    text: 'Failed to connect to server. Please try again.'
                                });
                            }
                        });
                    }
                });
            });

            // Reject Appointment Request
            $(document).on('click', '.btn-reject', function () {
                const requestId = $(this).data('id');
                const btn = $(this);

                Swal.fire({
                    title: 'Reject Request?',
                    text: 'This will reject the meeting request.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Reject',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: getBaseUrl(),
                            type: 'POST',
                            data: {
                                route: 'requestMeeting',
                                rejectRequest: 1,
                                request_id: requestId
                            },
                            dataType: 'json',
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Rejected!',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                    loadAppointmentRequests();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message || 'Failed to reject request'
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Reject error:', error, xhr.responseText);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Connection Error',
                                    text: 'Failed to connect to server. Please try again.'
                                });
                            }
                        });
                    }
                });
            });

            // Request Meeting to Chairman
            $('#requestChairmanMeetingBtn').on('click', function () {
                $('#requestChairmanMeetingModal').appendTo('body').modal('show');
                $('#chairmanTimeSlotsContainer').hide();
                $('#chairmanSelectedTime').val('');
            });

            // View Available Slots for Chairman
            $('#viewChairmanSlotsBtn').on('click', function (e) {
                e.preventDefault();
                const meetingDate = $('#chairmanMeetingDate').val();

                if (!meetingDate) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Please select a date',
                        toast: true,
                        position: 'top',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }

                $('#viewChairmanSlotsBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

                // Get Chairman's user_id - we need to use backend to fetch it
                // For now, we'll fetch Chairman's slots using a special ID
                $.getJSON('backend/backend_combined.php?route=getAvailableSlots_fac&requested_to=CHAIRMAN&date=' + encodeURIComponent(meetingDate))
                    .done(function (data) {
                        console.log('Available slots:', data);
                        if (data && data.success && Array.isArray(data.data)) {
                            const timeSlots = data.data;
                            const timeSlotContainer = $('#chairmanTimeSlots');
                            timeSlotContainer.empty();

                            if (timeSlots.length === 0) {
                                timeSlotContainer.html('<p class="text-muted">No available slots on this date</p>');
                            } else {
                                const slotsHtml = timeSlots.map(slot => {
                                    const className = slot.available ? 'available' : 'booked';
                                    const disabled = !slot.available ? 'disabled' : '';
                                    return `
                                        <div class="time-slot ${className}" ${disabled}
                                             data-start="${slot.start}" 
                                             data-end="${slot.end}">
                                            ${slot.start} - ${slot.end}
                                        </div>
                                    `;
                                }).join('');

                                timeSlotContainer.html(slotsHtml);

                                // Time slot click handler
                                $('.time-slot.available').on('click', function () {
                                    $('.time-slot').removeClass('selected');
                                    $(this).addClass('selected');

                                    const start = $(this).data('start');
                                    const end = $(this).data('end');
                                    const date = $('#chairmanMeetingDate').val();

                                    $('#chairmanSelectedTime').val(`${date} ${start}`);
                                });
                            }

                            $('#chairmanTimeSlotsContainer').show();
                            $('#viewChairmanSlotsBtn').prop('disabled', false).html('View Available Slots');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data?.message || 'Failed to load slots'
                            });
                            $('#viewChairmanSlotsBtn').prop('disabled', false).html('View Available Slots');
                        }
                    })
                    .fail(function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load available slots'
                        });
                        $('#viewChairmanSlotsBtn').prop('disabled', false).html('View Available Slots');
                    });
            });

            // Select time slot for Chairman meeting (OLD - NO LONGER NEEDED)
            // This is now handled in the viewChairmanSlotsBtn .done() callback

            // Submit meeting request to Chairman
            $('#requestChairmanMeetingForm').on('submit', function (e) {
                e.preventDefault();

                const purpose = $('textarea[name="purpose"]', this).val().trim();
                const meetingDate = $('#chairmanMeetingDate').val();
                const selectedTime = $('#chairmanSelectedTime').val();

                if (!purpose || !meetingDate || !selectedTime) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Form',
                        text: 'Please fill all fields and select a time slot'
                    });
                    return;
                }

                const preferredDateTime = selectedTime;

                $.ajax({
                    url: getBaseUrl(),
                    type: 'POST',
                    data: {
                        route: 'requestMeeting',
                        purpose: purpose,
                        requested_to: 'CHAIRMAN',
                        preferred_time: preferredDateTime
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Request Sent',
                                text: response.message || 'Your meeting request to Chairman has been sent',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $('#requestChairmanMeetingModal').modal('hide');
                            $('#requestChairmanMeetingForm')[0].reset();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to send request'
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Request error:', error, xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Connection Error',
                            text: 'Failed to connect to server. Please try again.'
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>