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
        .assignto {
            --bs-table-bg: transparent;
            --bs-table-color: white;
            background: linear-gradient(135deg, #4CAF50, #2196F3) !important;
            color: inherit;
            text-align: center;
            font-size: 0.9em;
            font-weight: 600;
        }

        /* ---------- Modal Styling ---------- */
        /* Fix z-index & modal stacking */
        .modal-backdrop {
            z-index: 2000 !important;
            background-color: rgba(0, 0, 0, 0.55) !important;
            backdrop-filter: blur(3px);
        }

        .modal {
            z-index: 2100 !important;
        }

        /* ---------- Modal Header ---------- */
        .modal-header {
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
            color: #fff;
            border-bottom: none;
            padding: 20px 24px;
        }

        /* ---------- Modal Title ---------- */
        .modal-title {
            font-size: 1.35rem;
            font-weight: 500;
        }

        /* ---------- Modal Body ---------- */
        .modal-body {
            padding: 24px;
            background: #fff;
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        /* ---------- Modal Footer ---------- */
        .modal-footer {
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
            border-top: none;
            background: #f7f7f9;
        }

        /* ---------- Buttons inside Modals ---------- */
        .modal-footer .btn {
            border-radius: 10px;
            font-weight: 500;
            padding: 8px 18px;
        }

        /* ---------- SweetAlert (for safety) ---------- */
        .swal2-container {
            z-index: 2000 !important;
        }

        /* ---------- Optional: Smooth open animation ---------- */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: translateY(-30px);
        }

        .modal.show .modal-dialog {
            transform: translateY(0);
        }

        .add {
            display: flex;
            flex-direction: row;
            justify-content: end;
            align-items: center;
            margin-right: 70px;
        }

        .buttonclass {
            display: flex;
        }

        /* Button styling to match your first sample */
        .btn-primary {
            border-radius: 8px;
            background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
            color: white;
            border: none;
        }

        .reassign {
            background-color: rgb(4, 161, 4);
            color: white;
            padding: 3px;
            margin: 2px;
            width: 70px;
            border-radius: 5px;
            margin-right: 8px;
            transition: transform 0.5s;
        }

        .reassign:hover {
            transform: scale(1.2);
        }

        .redo {
            background-color: orange;
            color: white;
            padding: 3px;
            margin: 2px;
            width: 70px;
            border-radius: 5px;
            transition: transform 0.5s;
            margin-right: 8px;
        }

        .redo:hover {
            transform: scale(1.2);
        }

        .btns {
            margin-top: 10px;
            display: none;
            justify-content: center;
        }

        .status_btn_inactive {
            display: inline-block;
               padding: 4px 12px;
            color: white;
            text-decoration: none;
               border-radius: 20px;
               font-size: 0.8em;
               border: none;
               cursor: default;
            margin-bottom: 10px;
        }

           .status_btn {
               display: inline-block;
               padding: 4px 12px;
               color: white;
               text-decoration: none;
               border-radius: 20px;
               font-size: 0.8em;
               border: none;
               cursor: pointer;
               margin-bottom: 10px;
               transition: transform 0.2s ease, background-color 0.2s ease;
           }

        .status_btn:hover {
            transform: scale(1.1);
               background-color: darkred;
        }

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

        .gradient-header {
            --bs-table-bg: transparent;
            --bs-table-color: white;
            background: linear-gradient(135deg, #4CAF50, #2196F3) !important;

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
        #meeting {
            background: #f8f9fc;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial;
            padding: 20px;
        }

        #meeting .cfm-btn {
            border-radius: 8px;
            background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
            color: white;
            border: none;
        }

        #meeting table.dataTable thead tr {
            background: linear-gradient(135deg, #3e8e40ff, #0d7fddff) !important;
            color: white !important;
            text-align: center;
        }

        #meeting table.dataTable thead th {
            background: transparent !important;
            color: white !important;
            font-weight: 600;
            text-align: center !important;
            padding: 12px !important;
            border-bottom: none !important;
            white-space: nowrap;
        }

        #meeting .cfm-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: 0.3s ease;
            margin-bottom: 30px;
            padding: 15px;
        }

        #meeting .cfm-modal-header {
            background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
            color: #fff;
            border-bottom: none;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            padding: 20px 24px;
        }

        #meeting .modal {
            z-index: 2000 !important;
        }

        #meeting .modal-backdrop {
            z-index: 1050 !important;
        }

        /* Buttons inside Meeting Tab */
        #meeting .btn-gradient-primary {
            background: linear-gradient(45deg, #007bff, #00a8ff);
            color: #fff !important;
            border-radius: 8px;
            border: none;
        }

        #meeting .btn-gradient-primary:hover {
            background: linear-gradient(45deg, #00a8ff, #007bff);
        }

        /* DataTable styling for meeting tab */
        #meeting table.dataTable tbody tr {
            transition: all 0.25s ease-in-out;
        }

        #meeting table.dataTable tbody tr:hover {
            background: #f5f7fa !important;
            transform: scale(1.01);
        }

        #meeting table.dataTable td {
            vertical-align: middle;
            text-align: center;
            padding: 10px 8px !important;
        }

        #meeting .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px !important;
            padding: 5px 10px !important;
            margin: 0 2px;
            background: #e9ecef;
            transition: background 0.2s ease;
        }

        #meeting .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #007bff;
            color: white !important;
        }

        #meeting .dataTables_wrapper .dataTables_filter input {
            border-radius: 6px !important;
            border: 1px solid #ddd !important;
            padding: 6px 10px !important;
            margin-left: 8px;
        }

        /* Badge styling */
        #meeting .badge-pending {
            background-color: #ffc107;
            color: #000;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        #meeting .badge-approved {
            background-color: #28a745;
            color: #fff;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        #meeting .badge-rejected {
            background-color: #dc3545;
            color: #fff;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Time slot styles */
        .time-slot {
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
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
        }

        /* Ensure SweetAlert2 appears above Bootstrap modals */
        .swal2-container {
            z-index: 3000 !important;
        }

        .swal2-backdrop-show {
            z-index: 2999 !important;
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

                    <li class="nav-item" role="presentation" style="display:none">
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
                        <?php include "./assigningTSomeone_chairman.php"; ?>
                    </div>

                    <div class="tab-pane fade" id="assigned" role="tabpanel" aria-labelledby="assigned-tab">
                        <?php include "./assigntome.php"; ?>
                    </div>


                    <!-- Meeting -->
                    <div class="tab-pane fade" id="meeting" role="tabpanel" aria-labelledby="meeting-tab">
                        <div class="container-fluid py-4">
                            <!-- Buttons -->
                            <div class="d-flex justify-content-end mb-4 gap-3">
                                <button id="cfmOpenAddBtn" class="btn btn-gradient-primary d-flex align-items-center gap-2">
                                    <i class="fas fa-calendar-plus"></i> Add Meeting
                                </button>
                            </div>

                            <!-- ✅ Tabs for Meetings -->
                            <div class="cfm-card">
                                <ul class="nav nav-tabs mb-3" id="meetingTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="scheduled-tab" data-bs-toggle="tab" data-bs-target="#scheduled"
                                            type="button" role="tab" aria-controls="scheduled" aria-selected="true">Scheduled Meetings</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="approve-reject-tab" data-bs-toggle="tab"
                                            data-bs-target="#approve-reject" type="button" role="tab" aria-controls="approve-reject" aria-selected="false">Approve/Reject Meetings</button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <!-- Scheduled Meetings Table -->
                                    <div class="tab-pane fade show active" id="scheduled" role="tabpanel" aria-labelledby="scheduled-tab">
                                        <table id="cfmScheduledMeetingsTable" class="table table-striped table-hover" style="width:100%">
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
                                    <div class="tab-pane fade" id="approve-reject" role="tabpanel" aria-labelledby="approve-reject-tab">
                                        <table id="cfmApproveRejectTable" class="table table-striped table-hover" style="width:100%">
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
                                </div>
                            </div>
                        </div>

                        <!-- ✅ Add Meeting Modal -->
                        <div class="modal fade" id="cfmAddMeetingModal" tabindex="-1" aria-labelledby="cfmModalTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header cfm-modal-header">
                                        <h5 class="modal-title" id="cfmModalTitle">Schedule Staff Meeting</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form id="cfmAddMeetingForm">
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
                                                <select class="form-select" id="cfmMeetingAssignType" required>
                                                    <option value="">-- Select Type --</option>
                                                    <option value="CHRO">CHRO</option>
                                                    <option value="ED">ED</option>
                                                    <option value="Principal">Principal</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 d-none" id="cfmMeetingDeptSection">
                                                <label class="form-label">Select Person</label>
                                                <select class="form-select" id="cfmMeetingDeptDropdown">
                                                    <option value="">-- Select --</option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="staff" id="cfmStaffDropdown" value="">
                                            <div class="mb-3">
                                                <label class="form-label">Date & Time</label>
                                                <input id='mdatetime' type="datetime-local" class="form-control" name="datetime" required>
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
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" id="cfmSubmitMeetingBtn">Schedule Meeting</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Report -->
                    <div class="tab-pane fade" id="report" role="tabpanel" aria-labelledby="report-tab">
                        <?php include "./ch_report.php"; ?>
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


        //meeting script

        $(document).ready(function () {

            // Initialize DataTables only if not already initialized
            let scheduledTable, approveRejectTable, assignedTasksTable;

            function initializeDataTables() {
                // Initialize scheduled meetings table
                if (!$.fn.DataTable.isDataTable('#cfmScheduledMeetingsTable')) {
                    scheduledTable = $('#cfmScheduledMeetingsTable').DataTable({
                        pageLength: 5,
                        language: { emptyTable: "No scheduled meetings" }
                    });
                } else {
                    scheduledTable = $('#cfmScheduledMeetingsTable').DataTable();
                }

                // Initialize approve/reject table
                if (!$.fn.DataTable.isDataTable('#cfmApproveRejectTable')) {
                    approveRejectTable = $('#cfmApproveRejectTable').DataTable({
                        pageLength: 5,
                        language: { emptyTable: "No pending meeting requests" }
                    });
                } else {
                    approveRejectTable = $('#cfmApproveRejectTable').DataTable();
                }

                // Initialize assigned tasks table
                if (!$.fn.DataTable.isDataTable('#parentTable')) {
                    assignedTasksTable = $('#parentTable').DataTable({
                        pageLength: 10,
                        order: [[0, 'asc']],
                        language: { emptyTable: "No assigned tasks" }
                    });
                } else {
                    assignedTasksTable = $('#parentTable').DataTable();
                }
            }

            // Initialize tables on document ready
            initializeDataTables();

            // Load assigned tasks on page load
            // Note: loadAssignedTasks() is defined in assigningTSomeone_chairman.php
            // Call it after the table is initialized
            setTimeout(function() {
                if (typeof loadAssignedTasks === 'function') {
                    loadAssignedTasks(assignedTasksTable);
                }
            }, 100);

            // Get base URL dynamically
            function getBaseUrl() {
                return 'backend/backend_combined.php';
            }

            // Store current editing meeting ID
            let currentEditId = null;

            // Participant selector handlers
            const cfmMeetingAssignType = $('#cfmMeetingAssignType');
            const cfmMeetingDeptSection = $('#cfmMeetingDeptSection');
            const cfmMeetingDeptDropdown = $('#cfmMeetingDeptDropdown');

            function resetParticipantSelectors() {
                cfmMeetingAssignType.val('');
                cfmMeetingDeptDropdown.val('').empty().append('<option value="">-- Select --</option>');
                cfmMeetingDeptSection.addClass('d-none');
            }

            function populatePersonByRole(role) {
                cfmMeetingDeptDropdown.empty().append('<option value="">-- Select --</option>');
                $.post('backend/api.php', { action: 'filterhod', role: role }, function(res) {
                    if (Array.isArray(res) && res.length > 0) {
                        res.forEach(r => {
                            cfmMeetingDeptDropdown.append(`<option value="${r.name}">${r.name} (${r.dept})</option>`);
                        });
                        cfmMeetingDeptSection.removeClass('d-none');
                    } else {
                        cfmMeetingDeptDropdown.append('<option value="">No persons found for this role</option>');
                    }
                }, 'json').fail(function(err) {
                    console.error('Role filter failed', err);
                    cfmMeetingDeptDropdown.append('<option value="">Error loading persons</option>');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Loading',
                        text: 'Failed to load persons for this role. Please try again.'
                    });
                });
            }

            cfmMeetingAssignType.on('change', function() {
                const type = $(this).val();
                cfmMeetingDeptDropdown.empty().append('<option value="">-- Select --</option>');
                cfmMeetingDeptSection.addClass('d-none');

                if (type) {
                    populatePersonByRole(type);
                }
            });

            cfmMeetingDeptDropdown.on('change', function() {
                // Just update the selection, no additional logic needed
            });

            // Open Add Meeting Modal
            $('#cfmOpenAddBtn').on('click', function () {
                currentEditId = null;
                $('#cfmAddMeetingModal').appendTo('body').modal('show');
                $('#cfmModalTitle').text('Schedule Staff Meeting');
                $('#cfmSubmitMeetingBtn').html('Schedule Meeting');
                $('#cfmAddMeetingForm')[0].reset();
                resetParticipantSelectors();
            });

            // Load meetings with Scheduled and Approve/Reject tabs
            function loadMeetings() {
                // Fetch all meetings scheduled by this user (chairman)
                $.getJSON(getBaseUrl() + '?route=addMeeting&fetchMeetings=1', function (meetings) {
                    scheduledTable.clear();
                    if (Array.isArray(meetings) && meetings.length > 0) {
                        meetings.forEach(function (m) {
                            const actions = `
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="cfm-btn btn-sm btn-warning cfm-notify-btn" 
                                            data-email="${m.email || ''}"
                                            data-title="${m.title || ''}" 
                                            data-datetime="${m.datetime || ''}"
                                            data-staff="${m.staff || ''}"
                                            data-location="${m.location || ''}"
                                            data-agenda="${m.agenda || ''}">
                                            <i class="fas fa-bell"></i> Notify
                                        </button>
                                    </div>
                                `;
                            scheduledTable.row.add([
                                m.title || '-',
                                m.staff || '-',
                                m.datetime || '-',
                                m.location || '-',
                                actions
                            ]);
                        });
                    }
                    scheduledTable.draw();
                });

                // Load appointment requests for Approve/Reject tab
                $.ajax({
                    url: getBaseUrl() + '?route=requestMeeting&fetchAppointmentRequests=1',
                    dataType: 'json',
                    success: function(response) {
                        approveRejectTable.clear();
                        if (response.success && Array.isArray(response.data) && response.data.length > 0) {
                            response.data.forEach(req => {
                                const statusBadge = req.status === 'Pending' ?
                                    `<span class="badge badge-pending">${req.status}</span>` :
                                    req.status === 'Approved' ?
                                    `<span class="badge badge-approved">${req.status}</span>` :
                                    `<span class="badge badge-rejected">${req.status}</span>`;

                                const actionButtons = req.status === 'Pending' ?
                                    `<div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-success btn-approve" data-id="${req.request_id}">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-reject" data-id="${req.request_id}">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>` :
                                    `<span class="text-muted">No action</span>`;

                                approveRejectTable.row.add([
                                    req.requester_name || req.request_by || 'Unknown',
                                    req.request_time ? new Date(req.request_time).toLocaleString() : 'N/A',
                                    req.purpose || 'N/A',
                                    statusBadge,
                                    actionButtons
                                ]);
                            });
                        }
                        approveRejectTable.draw();
                    },
                    error: function(xhr, status, error) {
                        console.error('Failed to load appointment requests:', error);
                        approveRejectTable.clear().draw();
                    }
                });
            }

            loadMeetings();

            // ✅ Add Meeting
            $(document).on('submit', '#cfmAddMeetingForm', function (e) {
                e.preventDefault();
                
                // Validate required fields
                const selectedPerson = cfmMeetingDeptDropdown.val();
                if (!selectedPerson) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Select Person',
                        text: 'Please select a person to schedule the meeting with'
                    });
                    return;
                }

                const editId = $(this).data('edit-id');
                const url = editId
                    ? getBaseUrl() + '?route=addMeeting&updateMeeting=1'
                    : getBaseUrl() + '?route=addMeeting';

                // Build data object - send role as staff and person name as fac_name
                const formData = {
                    title: $(this).find('[name="title"]').val(),
                    agenda: $(this).find('[name="agenda"]').val(),
                    staff: cfmMeetingAssignType.val(), // Send the role (CHRO, ED, Principal)
                    fac_name: selectedPerson, // Send the selected person name
                    datetime: $(this).find('[name="datetime"]').val(),
                    mode: $(this).find('[name="mode"]').val(),
                    location: $(this).find('[name="location"]').val()
                };
                if (editId) {
                    formData.calendar_id = editId;
                }

                console.log('Form Data:', formData); // Debug log

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: editId ? 'Meeting Updated' : 'Meeting Scheduled',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            $('#cfmAddMeetingModal').modal('hide');
                            $('#cfmAddMeetingForm')[0].reset();
                            resetParticipantSelectors();
                            loadMeetings();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to schedule meeting'
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Server response:', xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to communicate with server. Please check your network or server logs.'
                        });
                        console.error('Ajax error:', error);
                    }
                });
            });

            // ✅ Notify Button - send email notification
            $(document).on('click', '.cfm-notify-btn', function () {
                const btn = $(this);
                const email = btn.data('email');
                const title = btn.data('title');
                const datetime = btn.data('datetime');
                const staff = btn.data('staff');
                const venue = btn.data('location') || '';
                const agenda = btn.data('agenda') || '';

                if (!email) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Email not available',
                        text: 'No email address found for the selected staff.'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Send Notification?',
                    html: `Send meeting notification email to:<br><strong>${staff}</strong><br>${email}`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-paper-plane"></i> Yes, Send Email',
                    confirmButtonColor: '#28a745',
                    cancelButtonText: 'Cancel'
                }).then(res => {
                    if (res.isConfirmed) {
                        btn.prop('disabled', true)
                            .html('<i class="fas fa-spinner fa-spin"></i> Sending...');

                        $.ajax({
                            url: getBaseUrl(),
                            type: 'POST',
                            data: {
                                route: 'notifyMeeting',
                                email: email,
                                title: title,
                                datetime: datetime,
                                staff: staff,
                                venue: venue,
                                agenda: agenda
                            },
                            dataType: 'json',
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Email Sent!',
                                        text: response.message || 'Meeting notification sent successfully',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                    btn.removeClass('btn-warning')
                                        .addClass('btn-success')
                                        .html('<i class="fas fa-check-circle"></i> Notified')
                                        .prop('disabled', true);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Failed',
                                        text: response.message || 'Failed to send notification'
                                    });
                                    btn.prop('disabled', false)
                                        .html('<i class="fas fa-bell"></i> Notify');
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Notify error:', error, xhr.responseText);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Connection Error',
                                    text: 'Failed to send notification. Please try again.'
                                });
                                btn.prop('disabled', false)
                                    .html('<i class="fas fa-bell"></i> Notify');
                            }
                        });
                    }
                });
            });

            // Approve Appointment Request
            $(document).on('click', '.btn-approve', function() {
                const requestId = $(this).data('id');

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
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Approved!',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                    loadMeetings();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message || 'Failed to approve request'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
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
            $(document).on('click', '.btn-reject', function() {
                const requestId = $(this).data('id');

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
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Rejected!',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                    loadMeetings();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message || 'Failed to reject request'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
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

            // Handle Review Button Click
            $(document).on('click', '.review-btn', function () {
                const taskId = $(this).data('task-id');
                const assignmentId = $(this).data('assignment-id');
                const taskTitle = $(this).data('title');

                $.ajax({
                    url: 'backend/api.php',
                    type: 'POST',
                    data: {
                        action: 'getTaskProof',
                        assignment_id: assignmentId
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $('#reviewTaskTitle').text(taskTitle);
                            $('#reviewExplanation').text(response.explanation || 'No explanation provided');

                            if (response.proof) {
                                $('#reviewProofLink').attr('href', response.proof).show();
                                $('#reviewProofPreview').attr('src', response.proof).show();
                            } else {
                                $('#reviewProofLink').hide();
                                $('#reviewProofPreview').hide();
                            }

                            $('#reviewTaskId').val(taskId);
                            $('#reviewAssignmentId').val(assignmentId);
                            $('#reviewModal').modal('show');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to load task details'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load task details'
                        });
                    }
                });
            });

            // Handle Accept Task
            $('#acceptTaskBtn').on('click', function () {
                const assignmentId = $('#reviewAssignmentId').val();
                const remarks = $('#reviewRemarks').val().trim();

                if (!remarks) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Remarks Required',
                        text: 'Please provide feedback or remarks before accepting the task'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Accept Task?',
                    text: 'Mark this task as completed?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Accept',
                    confirmButtonColor: '#28a745'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'backend/api.php',
                            type: 'POST',
                            data: {
                                action: 'acceptTaskCompletion',
                                assignment_id: assignmentId,
                                remarks: remarks
                            },
                            dataType: 'json',
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Accepted',
                                        text: 'Task marked as completed with feedback'
                                    });
                                    $('#reviewModal').modal('hide');
                                    $('#reviewRemarks').val('');
                                    loadAssignedTasks();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message || 'Failed to accept task'
                                    });
                                }
                            },
                            error: function () {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to accept task'
                                });
                            }
                        });
                    }
                });
            });

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
            setDateMinToToday('#mdatetime');

        });
    </script>
    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">
                        <i class="fas fa-clipboard-check me-2"></i>Review Task Completion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="reviewTaskId">
                    <input type="hidden" id="reviewAssignmentId">

                    <div class="mb-3">
                        <h6 class="fw-bold">Task Title:</h6>
                        <p id="reviewTaskTitle"></p>
                    </div>

                    <div class="mb-3">
                        <h6 class="fw-bold">Explanation:</h6>
                        <p id="reviewExplanation"></p>
                    </div>

                    <div class="mb-3">
                        <h6 class="fw-bold">Proof of Completion:</h6>
                        <a id="reviewProofLink" href="#" target="_blank" class="btn btn-sm btn-info mb-2">
                            <i class="fas fa-download"></i> Download Proof
                        </a>
                        <div>
                            <img id="reviewProofPreview" src="" class="img-fluid"
                                style="max-height: 400px; display: none;" alt="Proof Preview">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reviewRemarks" class="form-label fw-bold">
                            <i class="fas fa-comment me-1"></i>Remarks/Feedback:
                        </label>
                        <textarea class="form-control" id="reviewRemarks" rows="3"
                            placeholder="Enter your feedback or remarks for the completed task..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="acceptTaskBtn">
                        <i class="fas fa-check me-2"></i>Accept & Complete
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>