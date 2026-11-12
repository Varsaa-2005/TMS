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
    <title>Kr Connect</title>
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
        #conflictFreeMeeting {
            background: #f8f9fc;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial;
            padding: 20px;
        }

        #conflictFreeMeeting .cfm-btn {
            border-radius: 8px;
            background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
            color: white;
            border: none;
        }

        #conflictFreeMeeting table.dataTable thead tr {
            background: linear-gradient(135deg, #3e8e40ff, #0d7fddff) !important;
            color: white !important;
            text-align: center;
        }

        #conflictFreeMeeting table.dataTable thead th {
            background: transparent !important;
        }

        #conflictFreeMeeting .cfm-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: 0.3s ease;
            margin-bottom: 30px;
            padding: 15px;
        }

        #conflictFreeMeeting .cfm-modal-header {
            background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
            color: #fff;
            border-bottom: none;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            padding: 20px 24px;
        }

        #conflictFreeMeeting .modal {
            z-index: 2000 !important;
        }

        #conflictFreeMeeting         .modal-backdrop {
            z-index: 1050 !important;
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
                        <a class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" href="#dashboard" role="tab"
                            aria-controls="dashboard" aria-selected="true">
                            <span class="hidden-xs-down" style="font-size: 0.9em;font-family: 'Poppins', 'Open Sans', sans-serif;
font-weight: 600;color:blue;">
                                <i class="fas fa-chart-bar tab-icon"></i> Dashboard
                            </span>
                        </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link" id="personal-tab" data-bs-toggle="tab" href="#personal" role="tab"
                   aria-controls="personal" aria-selected="false">
                    <span class="hidden-xs-down" style="font-size: 0.9em;font-family: 'Poppins', 'Open Sans', sans-serif;
font-weight: 600;color:green;">
                        <i class="fas fa-list-check tab-icon"></i> Personal ToDo
                    </span>
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link" id="assignedtome-tab" data-bs-toggle="tab" href="#assignedtome" role="tab"
                   aria-controls="assignedtome" aria-selected="false">
                    <span class="hidden-xs-down" style="font-size: 0.9em;font-family: 'Poppins', 'Open Sans', sans-serif;
font-weight: 600;color:purple;">
                        <i class="fas fa-user-check tab-icon"></i> My Task
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
            <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                <?php include "./dashboard.php"; ?>
            </div>

           <!-- Personal-->
            <div class="tab-pane fade show" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                <?php include "./personalToDo.php"; ?>
            </div>

            <!-- Assign by Me -->
            <div class="tab-pane fade" id="assignedtome" role="tabpanel" aria-labelledby="assignedtome-tab">
                <?php include "./assigntome.php"; ?>
            </div>

            <!-- Assign to Someone -->
            <div class="tab-pane fade" id="assigning" role="tabpanel" aria-labelledby="assigning-tab">
                <?php include "./assigningTSomeone.php"; ?>
            </div>

            <!-- Meeting -->
            <div class="tab-pane fade" id="meeting" role="tabpanel" aria-labelledby="meeting-tab">
                <div id="conflictFreeMeeting" class="container-fluid">
                    <!-- Buttons -->
                    <div class="d-flex justify-content-end mb-4 gap-3">
                        <button id="cfmOpenAddBtn" class="cfm-btn d-flex align-items-center gap-2">
                            <i class="fas fa-calendar-plus"></i> Add Meeting
                        </button>
                        <button id="cfmOpenRequestBtn" class="cfm-btn d-flex align-items-center gap-2">
                            <i class="fas fa-user-tie"></i> Request Meeting
                        </button>
                    </div>

                    <!-- Tabs -->
                    <div class="cfm-card">
                        <ul class="nav nav-tabs mb-3" id="cfmMeetingTabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" id="cfmScheduledTab" data-bs-toggle="tab"
                                    data-bs-target="#cfmScheduled" type="button" role="tab">Scheduled Meetings</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="cfmRequestedTab" data-bs-toggle="tab" data-bs-target="#cfmRequested"
                                    type="button" role="tab">Requested Meetings</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="cfmPrincipalTab" data-bs-toggle="tab" data-bs-target="#cfmPrincipal"
                                    type="button" role="tab">Assigned Meetings</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Scheduled Meetings Table -->
                            <div class="tab-pane fade show active" id="cfmScheduled" role="tabpanel">
                                <table id="cfmScheduledMeetingsTable" class="display table table-striped" style="width:100%">
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

                            <!-- Requested Meetings Table -->
                            <div class="tab-pane fade" id="cfmRequested" role="tabpanel">
                                <table id="cfmRequestedMeetingsTable" class="display table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                                <th style="color:White;">Description</th>
                                                <th style="color:White;">Requested To</th>
                                            <th style="color:White;">Preferred Date & Time</th>
                                            <th style="color:White;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <!-- Meetings with Principal Table -->
                            <div class="tab-pane fade" id="cfmPrincipal" role="tabpanel">
                                <table id="cfmPrincipalMeetingsTable" class="display table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="color:White;">Title</th>
                                            <th style="color:White;">Description</th>
                                            <th style="color:White;">Date</th>
                                            <th style="color:White;">Start Time</th>
                                            <th style="color:White;">End Time</th>
                                            <th style="color:White;">Venue</th>
                                            <th style="color:White;">Assigned By</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Add Meeting Modal -->
                    <div class="modal fade" id="cfmAddMeetingModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="cfm-modal-header modal-header">
                                    <h5 class="modal-title">Schedule Staff Meeting</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                                            <label class="form-label">Choose Faculty</label>
                                            <select class="form-select" name="fac_name" id="fac_name" required>
                                                <option value="">-- Select Faculty --</option>
                                                <option value="Everyone">Everyone</option>
                                            </select>
                                            <small class="text-muted">Showing only faculty from your department.</small>
                                        </div>
                                        <input type="hidden" name="staff" id="cfmStaffDropdown" value="<?php echo htmlspecialchars($_SESSION['dept'] ?? ''); ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Date & Time</label>
                                            <input type="datetime-local" class="form-control" name="datetime" required>
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
                                        <button type="submit" class="btn btn-primary">Schedule Meeting</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Request Meeting Modal -->
                    <div class="modal fade" id="cfmRequestMeetingModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="cfm-modal-header modal-header">
                                    <h5 class="modal-title">Request Meeting</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form id="cfmRequestMeetingForm">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Request To</label>
                                            <select class="form-select" name="request_to" id="cfmPrincipalDropdown" required>
                                                <option disabled selected value="">Select</option>
                                                <!-- options populated dynamically -->
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Purpose</label>
                                            <textarea class="form-control" name="purpose" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Select Date</label>
                                            <div class="d-flex gap-2">
                                                <input type="date" class="form-control" id="meeting_date" name="meeting_date" required>
                                                <button type="button" class="btn btn-primary" id="viewSlotsBtn">
                                                    View Available Slots
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mb-3" id="timeSlotsContainer" style="display: none;">
                                            <label class="form-label">Available Time Slots</label>
                                            <div class="d-flex flex-wrap" id="timeSlots">
                                                <!-- Time slots will be populated here -->
                                            </div>
                                        </div>
                                        <input type="hidden" name="selected_time" id="selected_time">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit Request</button>
                                    </div>
                                </form>
                            </div>
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

        //meeting script
        $(document).ready(function () {

                // Initialize DataTables
                const scheduledTable = $('#cfmScheduledMeetingsTable').DataTable({
                    pageLength: 5,
                    language: { emptyTable: "No scheduled meetings" }
                });
                const requestedTable = $('#cfmRequestedMeetingsTable').DataTable({
                    pageLength: 5,
                    language: { emptyTable: "No meeting requests" }
                });
                const principalTable = $('#cfmPrincipalMeetingsTable').DataTable({
                    pageLength: 5,
                    language: { emptyTable: "No assigned meetings" }
                });

                // Load Officials for request dropdown
                function loadOfficialsDropdown() {
                    $.getJSON('backend/backend_combined.php?route=getOfficials')
                        .done(function(resp) {
                            if (resp && resp.success && Array.isArray(resp.data)) {
                                const dd = $('#cfmPrincipalDropdown');
                                dd.empty().append('<option disabled selected value="">Select</option>');
                                resp.data.forEach(function(u) {
                                    dd.append(`<option value="${u.user_id}">${u.name} (${u.role})</option>`);
                                });
                            }
                        })
                        .fail(function() {
                            console.error('Failed to load officials list');
                        });
                }
                loadOfficialsDropdown();

                // Open Add Meeting Modal
                const hodDepartment = <?php echo json_encode($_SESSION['dept'] ?? ''); ?>;

                function loadHodFacultyDropdown() {
                    const facultyDropdown = $('#fac_name');
                    facultyDropdown.empty().append('<option value="">-- Select Faculty --</option>');
                    facultyDropdown.append('<option value="Everyone">Everyone</option>');
                    if (!hodDepartment) {
                        return;
                    }
                    $.post('backend/api.php', { action: 'filterfaculty', dept: hodDepartment }, function(res) {
                        if (Array.isArray(res)) {
                            res.forEach(function(fac) {
                                facultyDropdown.append('<option value="' + fac.name + '">' + fac.name + '</option>');
                            });
                        }
                    }, 'json').fail(function(err) {
                        console.error('Faculty load failed for HOD', err);
                    });
                }

                // Open Add Meeting Modal
                $('#cfmOpenAddBtn').on('click', function () {
                    $('#cfmAddMeetingModal').appendTo('body').modal('show');
                    $('.modal-title', '#cfmAddMeetingModal').text('Schedule Staff Meeting');
                    $('#cfmAddMeetingForm')[0].reset();
                    $('#cfmAddMeetingForm').removeData('edit-id');
                    $('#cfmStaffDropdown').val(hodDepartment);
                    loadHodFacultyDropdown();
                });

                // Open Request Meeting Modal
                $('#cfmOpenRequestBtn').on('click', function () {
                    $('#cfmRequestMeetingModal').appendTo('body').modal('show');
                    $('#timeSlotsContainer').hide();
                    $('#selected_time').val('');
                });

                // Validate meeting date and time
                function validateMeetingDateTime(dateStr, timeStr) {
                    const selectedDateTime = new Date(`${dateStr} ${timeStr}`);
                    const now = new Date();

                    if (selectedDateTime < now) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Time',
                            text: '⚠️ Cannot request a meeting for a past date or time.'
                        });
                        return false;
                    }

                    const diffMs = selectedDateTime - now;
                    const diffHours = diffMs / (1000 * 60 * 60);

                    if (diffHours < 3) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Time',
                            text: '⚠️ Meeting requests must be submitted at least 3 hours before the scheduled time.'
                        });
                        return false;
                    }

                    return true;
                }

                // View Available Slots button click handler
                $('#viewSlotsBtn').on('click', function(e) {
                    e.preventDefault();
                    const selectedDate = $('#meeting_date').val();
                    
                    if (!selectedDate) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Select Date',
                            text: 'Please select a date first'
                        });
                        return;
                    }

                    // Validate selected date
                    const now = new Date();
                    const selectedDay = new Date(selectedDate);
                    selectedDay.setHours(0, 0, 0, 0);
                    now.setHours(0, 0, 0, 0);

                    if (selectedDay < now) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Date',
                            text: '⚠️ Cannot request a meeting for a past date.'
                        });
                        return;
                    }

                    // Show loading state
                    $('#viewSlotsBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

                    // Fetch available slots from the server
                    $.ajax({
                        url: `backend/backend_combined.php?route=getAvailableSlots&date=${selectedDate}`,
                        method: 'GET',
                        success: function(response) {
                        if (response.success) {
                            const slotsHtml = response.slots.map(slot => {
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

                            $('#timeSlots').html(slotsHtml);
                            $('#timeSlotsContainer').show();

                            // Time slot click handler
                            $('.time-slot.available').on('click', function() {
                                $('.time-slot').removeClass('selected');
                                $(this).addClass('selected');
                                
                                const start = $(this).data('start');
                                const end = $(this).data('end');
                                const date = $('#meeting_date').val();
                                
                                $('#selected_time').val(`${date} ${start}`);
                            });

                            // Reset button state
                            $('#viewSlotsBtn').prop('disabled', false).html('View Available Slots');
                        } else {
                            // Reset button and show error
                            $('#viewSlotsBtn').prop('disabled', false).html('View Available Slots');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to fetch available slots'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Reset button and show error
                        $('#viewSlotsBtn').prop('disabled', false).html('View Available Slots');
                        console.error('Ajax error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch available slots. Please try again.'
                        });
                    }
                });
                });

                // ✅ Load meetings with Notify + Edit buttons
                // Helper: render scheduled meetings into the scheduledTable
                function renderScheduledMeetings(data) {
                    // If the HOD-specific endpoint returned nothing, do not clear the already-populated table
                    if (!Array.isArray(data) || data.length === 0) {
                        return;
                    }

                    scheduledTable.clear();
                    data.forEach(m => {
                        const actions = `
                            <div class="d-flex justify-content-center gap-2">
                                <button class="cfm-btn btn-sm btn-warning cfm-notify-btn" 
                                    data-email="${m.email || ''}"
                                    data-title="${m.title || m.event_title || ''}"
                                    data-datetime="${m.datetime || m.schedule_date || ''}"
                                    data-staff="${m.staff_name || m.fac_name || ''}"
                                    data-venue="${m.location || m.venue || ''}"
                                    data-agenda="${m.agenda || m.description || ''}">
                                    <i class="fas fa-bell"></i> Notify
                                </button>
                            </div>`;

                        scheduledTable.row.add([
                            m.title || m.event_title || '-',
                            m.staff_name || m.fac_name || '-',
                            m.datetime || m.schedule_date || '-',
                            m.location || m.venue || '-',
                            actions
                        ]);
                    });
                    scheduledTable.draw();
                }

                // Helper: render requested meetings into requestedTable
                function renderRequestedMeetings(data) {
                    // Preserve existing requestedTable contents when HOD-specific endpoint returns no rows
                    if (!Array.isArray(data) || data.length === 0) {
                        return;
                    }

                    requestedTable.clear();
                    data.forEach(r => {
                        requestedTable.row.add([
                            r.event_description || r.purpose || '-',
                            r.requested_to || r.request_to || '-',
                            r.schedule_date || r.datetime || '-',
                            r.status || r.status || '-'
                        ]);
                    });
                    requestedTable.draw();
                }

                function loadMeetings() {
                    // Fetch all meetings scheduled by this user (portfolio head)
                    $.getJSON('backend/backend_combined.php?route=addMeeting&fetchMeetings=1', function (meetings) {
                        scheduledTable.clear();
                        if (Array.isArray(meetings) && meetings.length > 0) {
                            meetings.forEach(function(m) {
                                const actions = `
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="cfm-btn btn-sm btn-warning cfm-notify-btn" 
                                            data-email="${m.email || ''}"
                                            data-title="${m.title || ''}" 
                                            data-datetime="${m.datetime || ''}"
                                            data-staff="${m.staff || ''}"
                                            data-venue="${m.location || ''}"
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

                    // For "Scheduled Meetings" tab
$.getJSON('backend/backend_combined.php?route=addMeeting&fetchHodScheduled=1', function(data) {
    renderScheduledMeetings(data);
});


                    // Requested Meetings
                    $.getJSON('backend/backend_combined.php?route=requestMeeting&fetchRequests=1', function (response) {
                        requestedTable.clear();
                        if (response.success && response.data) {
                            response.data.forEach(req => {
                                requestedTable.row.add([
                                    req.purpose,
                                    req.requested_to || req.request_to || '-',
                                    req.datetime,
                                    req.status,

                                ]);
                            });
                        }
                        // For "Requested Meetings" tab
$.getJSON('backend/backend_combined.php?route=addMeeting&fetchHodRequests=1', function(data) {
    renderRequestedMeetings(data);
});
                        requestedTable.draw();
                        // Load meetings assigned by Principal to this HOD
                        $.ajax({
                            url: 'backend/backend_combined.php?route=principalMeetings',
                            type: 'GET',
                            success: function(resp) {
                                console.log('Principal meetings response:', resp); // Debug log
                                principalTable.clear();
                                    if (resp.success && Array.isArray(resp.data)) {
                                    resp.data.forEach(m => {
                                        let description = '';
                                        if (m.agenda) description = `Agenda: ${m.agenda}`;

                                        principalTable.row.add([
                                            m.event_title || '-',
                                            description || m.event_description || '-',
                                            m.schedule_date || '-',
                                            m.start_time || '-',
                                            m.end_time || '-',
                                            m.venue || '-',
                                            m.assigned_by || '-' 
                                        ]);
                                    });
                                }
                                principalTable.draw();
                            },
                            error: function(xhr, status, error) {
                                console.error('Principal meetings error:', error); // Debug log
                                console.error('Response:', xhr.responseText); // Debug log
                                principalTable.clear().draw();
                            }
                        });
                    });
                }

                loadMeetings();

                // ✅ Add Meeting
                $(document).on('submit', '#cfmAddMeetingForm', function (e) {
                    e.preventDefault();
                    const editId = $(this).data('edit-id');
                    const url = editId
                        ? 'backend/backend_combined.php?route=addMeeting&updateMeeting=1'
                        : 'backend/backend_combined.php?route=addMeeting';

                    // Build data object to match backend expectations
                    const formData = {
                        title: $(this).find('[name="title"]').val(),
                        agenda: $(this).find('[name="agenda"]').val(),
                        staff: $(this).find('#cfmStaffDropdown').val(), // department
                        fac_name: $(this).find('#fac_name').val(), // faculty name or 'Everyone'
                        datetime: $(this).find('[name="datetime"]').val(),
                        mode: $(this).find('[name="mode"]').val(),
                        location: $(this).find('[name="location"]').val()
                    };
                    if (editId) {
                        formData.calendar_id = editId;
                    }

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: editId ? 'Meeting Updated' : 'Meeting Scheduled',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                $('#cfmAddMeetingModal').modal('hide');
                                $('#cfmAddMeetingForm')[0].reset();
                                loadMeetings();
                            } else {
                                Swal.fire({ 
                                    icon: 'error', 
                                    title: 'Error', 
                                    text: response.message || 'Failed to schedule meeting'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({ 
                                icon: 'error', 
                                title: 'Error', 
                                text: 'Failed to communicate with server. Please check your network or server logs.'
                            });
                            console.error('Ajax error:', error); // Debugging log
                            console.error('Response:', xhr.responseText); // Debugging log
                        }
                    });
                });

                // Submit form
                $('#cfmRequestMeetingForm').on('submit', function (e) {
                    e.preventDefault();

                    if (!$('#selected_time').val()) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Select Time Slot',
                            text: 'Please select an available time slot'
                        });
                        return;
                    }

                    // Parse the selected date and time
                    const [dateStr, timeStr] = $('#selected_time').val().split(' ');
                    
                    // Validate meeting date and time
                    if (!validateMeetingDateTime(dateStr, timeStr)) {
                        return;
                    }

                    const formData = {
                        requested_to: $('#cfmPrincipalDropdown').val(),
                        purpose: $('[name="purpose"]').val(),
                        request_time: $('#selected_time').val()
                    };

                    $.ajax({
                        url: 'backend/backend_combined.php?route=submitMeetingRequest',
                        type: 'POST',
                        data: JSON.stringify(formData),
                        contentType: 'application/json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Request Submitted',
                                    text: 'Meeting request has been submitted successfully',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                $('#cfmRequestMeetingModal').modal('hide');
                                $('#cfmRequestMeetingForm')[0].reset();
                                $('#timeSlotsContainer').hide();
                                loadMeetings();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message || 'Failed to submit request'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to communicate with server'
                            });
                        }
                    });
                });

                // ✅ Notify Button
                // Replace the existing notify button click handler
                $(document).on('click', '.cfm-notify-btn', function () {
                    const btn = $(this);
                    const email = btn.data('email');
                    const title = btn.data('title');
                    const datetime = btn.data('datetime');
                    const staff = btn.data('staff');
                    const venue = btn.data('venue') || '';
                    const agenda = btn.data('agenda') || '';
                
                    Swal.fire({
                        title: 'Notify staff?',
                        text: 'Send meeting notification via email.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, notify'
                    }).then(res => {
                        if (res.isConfirmed) {
                            $.ajax({
                                url: 'backend/backend_combined.php?route=notifyMeeting',
                                type: 'POST',
                                data: {
                                    email: email,
                                    title: title,
                                    datetime: datetime,
                                    staff: staff,
                                    venue: venue,
                                    agenda: agenda
                                },
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire({
                                            toast: true,
                                            icon: 'success',
                                            title: 'Email notification sent',
                                            timer: 1500,
                                            showConfirmButton: false
                                        });
                                        btn.removeClass('btn-warning')
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
                                error: function() {
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

                // ✅ Edit Button — opens modal with existing meeting info
                $(document).on('click', '.btn-edit', function () {
                    const row = $(this).closest('tr');
                    const data = scheduledTable.row(row).data();
                    if (!data) return;

                    $('#cfmAddMeetingModal').appendTo('body').modal('show');
                    $('.modal-title', '#cfmAddMeetingModal').text('Edit Meeting');

                    $('#cfmAddMeetingForm [name="title"]').val(data[0]);
                    $('#cfmAddMeetingForm [name="agenda"]').val('');
                    $('#cfmAddMeetingForm [name="staff"]').val('');
                    $('#cfmAddMeetingForm [name="datetime"]').val('');
                    $('#cfmAddMeetingForm [name="mode"]').val('Online');
                    $('#cfmAddMeetingForm [name="location"]').val(data[3]);
                    $('#cfmAddMeetingForm').data('edit-id', $(this).data('id'));
                });


                // Remove duplicate/conflicting dropdown handler

            });
    </script>
</body>
</html>