<style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .rpt-main-container {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 1600px;
            padding: 30px;
            border-radius: 8px;
        }


        /* .rpt-role-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            margin-top: 8px;
        }

        .rpt-role-principal {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .rpt-role-hod {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        } */

        .rpt-kpi-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .rpt-kpi-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 24px;
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            transition: transform 0.3s;
        }

        .rpt-kpi-card:hover {
            transform: translateY(-5px);
        }

        .rpt-kpi-card.rpt-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .rpt-kpi-card.rpt-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .rpt-kpi-card.rpt-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .rpt-kpi-card.rpt-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .rpt-kpi-title {
            font-size: 14px;
            opacity: 0.95;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .rpt-kpi-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .rpt-kpi-subtitle {
            font-size: 12px;
            opacity: 0.85;
        }

        .rpt-filter-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
        }

        .rpt-filter-section h5 {
            color: #495057;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .rpt-table-container {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .rpt-table-container h5 {
            color: #0d6efd;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }

        .rpt-table-container .table thead {
            background: linear-gradient(to right, rgb(28, 159, 28), rgb(1, 119, 159)) !important;
        }

        .rpt-table-container .table thead th {
            color: white !important;
            font-weight: bold !important;
            border: 1px solid white !important;
            padding: 12px;
            text-align: center;
            background: transparent !important;
        }

        .rpt-table-container .table tbody td {
            padding: 10px;
            vertical-align: middle;
        }

        .rptheadrow {
            background: linear-gradient(to right, rgb(28, 159, 28), rgb(1, 119, 159)) !important;
        }

        .rptheadrow th {
            color: white !important;
            font-weight: bold !important;
            text-align: center;
            border: 1px solid white !important;
            padding: 12px;
            background: transparent !important;
        }

        .rprheadrow {
            background: linear-gradient(to right, rgb(28, 159, 28), rgb(1, 119, 159)) !important;
        }

        .rprheadrow th {
            color: white !important;
            font-weight: bold !important;
            text-align: center;
            border: 1px solid white !important;
            padding: 12px;
            background: transparent !important;
        }

        .rpt-status-badge {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .rpt-status-completed {
            background: #d4edda;
            color: #155724;
        }

        .rpt-status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .rpt-status-overdue {
            background: #f8d7da;
            color: #721c24;
        }

        .rpt-status-inprogress {
            background: #d1ecf1;
            color: #0c5460;
        }

        .rpt-priority-high {
            color: #dc3545;
            font-weight: 600;
        }

        .rpt-priority-medium {
            color: #fd7e14;
            font-weight: 600;
        }

        .rpt-priority-low {
            color: #28a745;
            font-weight: 600;
        }

        .rpt-btn-export {
            margin-right: 10px;
            padding: 10px 20px;
            font-weight: 600;
            border-radius: 6px;
        }

        .rpt-locked-field {
            background-color: #e9ecef !important;
            cursor: not-allowed;
        }

        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 600;
            border: none;
            border-bottom: 3px solid transparent;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 3px solid #0d6efd;
            background: transparent;
        }

        /* Top Performers Showcase */
        .rpt-top-performers {
            margin-bottom: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 30px;
            position: relative;
            overflow: hidden;
        }

        .rpt-top-performers::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: rptRotate 20s linear infinite;
        }

        @keyframes rptRotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .rpt-performers-title {
            text-align: center;
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .rpt-performers-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            position: relative;
            z-index: 1;
        }

        .rpt-performer-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            position: relative;
            transform: translateY(0);
            transition: transform 0.3s ease;
        }

        .rpt-performer-card:hover {
            transform: translateY(-10px);
        }

        .rpt-performer-card:nth-child(1) {
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.4);
        }

        .rpt-performer-card:nth-child(2) {
            box-shadow: 0 10px 30px rgba(192, 192, 192, 0.4);
        }

        .rpt-performer-card:nth-child(3) {
            box-shadow: 0 10px 30px rgba(205, 127, 50, 0.4);
        }

        .rpt-rank-badge {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .rpt-rank-1 {
            background: linear-gradient(135deg, #FFD700, #FFA500);
        }

        .rpt-rank-2 {
            background: linear-gradient(135deg, #C0C0C0, #808080);
        }

        .rpt-rank-3 {
            background: linear-gradient(135deg, #CD7F32, #8B4513);
        }

        .rpt-performer-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 20px auto 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            font-weight: 700;
        }

        .rpt-performer-name {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .rpt-performer-score {
            font-size: 28px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }

        .rpt-performer-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .rpt-locked-field {
            background-color: #e9ecef !important;
            cursor: not-allowed !important;
            opacity: 0.7;
        }

        @media (max-width: 768px) {
            .rpt-performers-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .rpt-kpi-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <div class="rpt-main-container">

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4" id="rptReportTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="rpt-dept-tab" data-bs-toggle="tab"
                    data-bs-target="#rptDeptPerformance" type="button">
                    Department Performance Overview
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="rpt-faculty-tab" data-bs-toggle="tab"
                    data-bs-target="#rptFacultyEvaluation" type="button">
                    Faculty Evaluation
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="rpt-meeting-tab" data-bs-toggle="tab" data-bs-target="#rptMeetingReports"
                    type="button">
                    Meeting Reports
                </button>
            </li>
        </ul>

        <div class="tab-content" id="rptReportTabsContent">
            <!-- DEPARTMENT PERFORMANCE TAB -->
            <div class="tab-pane fade show active" id="rptDeptPerformance" role="tabpanel">
                
                <div class="rpt-top-performers" id="rptTopPerformersSection">
                    <h3 class="rpt-performers-title">🏆 Top Performers</h3>
                    <div class="rpt-performers-grid" id="rptTopPerformersGrid">
                        
                    </div>
                </div>
                <!-- Filters -->
                <div class="rpt-filter-section">
                    <h5>📋 Filters</h5>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Academic Year</label>
                            <select id="rptDeptYear" class="form-select">
                                <option value="2024-2025">2024-2025</option>
                                <option value="2023-2024">2023-2024</option>
                                <option value="2022-2023">2022-2023</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Department</label>
                            <select id="rptDeptFilter" class="form-select">
                                <option value="">All Departments</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Period Type</label>
                            <select id="rptDeptPeriodType" class="form-select">
                                <option value="all">All Time</option>
                                <option value="month">Monthly</option>
                                <option value="week">Weekly</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Month</label>
                            <select id="rptDeptMonth" class="form-select" disabled>
                                <option value="">Select Month</option>
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-primary w-100" onclick="rptLoadDepartmentData()">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                    <div class="row g-3 mt-2" id="rptDeptDateRange" style="display:none;">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Week Number</label>
                            <select id="rptDeptWeek" class="form-select">
                                <option value="">Select Week</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Start Date</label>
                            <input type="date" id="rptDeptStartDate" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">End Date</label>
                            <input type="date" id="rptDeptEndDate" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Department Task Summary Table -->
                <div class="rpt-table-container">
                    <h5>📊 Department Task Summary</h5>
                    <div class="table-responsive">
                        <table id="rptDeptTaskTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="rptheadrow">
                                    <th>Department</th>
                                    <th>Total Tasks</th>
                                    <th>Completed</th>
                                    <th>Pending</th>
                                    <th>Overdue</th>
                                    <th>Completion %</th>
                                    <th>On-Time %</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Department Performance Table -->
                <div class="rpt-table-container">
                    <h5>⭐ Department Performance Metrics</h5>
                    <div class="table-responsive">
                        <table id="rptDeptPerfTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="rptheadrow">
                                    <th>Department</th>
                                    <th>Avg Credit Score</th>
                                    <th>Avg Demerit Score</th>
                                    <th>Avg Completion %</th>
                                    <th>Avg On-Time %</th>
                                    <th>Best Faculty</th>
                                    <th>Worst Faculty</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Export Buttons -->
                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-danger rpt-btn-export" onclick="rptExportDeptPDF()">
                        📄 Export PDF
                    </button>
                    <button class="btn btn-success rpt-btn-export" onclick="rptExportDeptExcel()">
                        📊 Export Excel
                    </button>
                </div>
            </div>

            <!-- FACULTY EVALUATION TAB -->
            <div class="tab-pane fade" id="rptFacultyEvaluation" role="tabpanel">
                <!-- Filters -->
                <div class="rpt-filter-section">
                    <h5>📋 Filters</h5>
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Academic Year</label>
                            <select id="rptFacYear" class="form-select">
                                <option value="2024-2025">2024-2025</option>
                                <option value="2023-2024">2023-2024</option>
                                <option value="2022-2023">2022-2023</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Department</label>
                            <select id="rptFacDept" class="form-select">
                                <option value="">All Departments</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Faculty</label>
                            <select id="rptFacFilter" class="form-select">
                                <option value="">All Faculty</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Period</label>
                            <select id="rptFacPeriodType" class="form-select">
                                <option value="all">All Time</option>
                                <option value="month">Monthly</option>
                                <option value="week">Weekly</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Status</label>
                            <select id="rptFacStatus" class="form-select">
                                <option value="">All Status</option>
                                <option value="Completed">Completed</option>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Overdue">Overdue</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-primary w-100" onclick="rptLoadFacultyData()">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                    <div class="row g-3 mt-2" id="rptFacDateRange" style="display:none;">
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Month</label>
                            <select id="rptFacMonth" class="form-select">
                                <option value="">Select</option>
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Week</label>
                            <select id="rptFacWeek" class="form-select">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Start Date</label>
                            <input type="date" id="rptFacStartDate" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">End Date</label>
                            <input type="date" id="rptFacEndDate" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- KPI Cards -->
                <div class="rpt-kpi-section" id="rptFacultyKPIs">
                    <div class="rpt-kpi-card">
                        <div class="rpt-kpi-title">Credit Score</div>
                        <div class="rpt-kpi-value" id="rptKpiCredit">0</div>
                        <div class="rpt-kpi-subtitle">Total earned</div>
                    </div>
                    <div class="rpt-kpi-card rpt-danger">
                        <div class="rpt-kpi-title">Demerit Score</div>
                        <div class="rpt-kpi-value" id="rptKpiDemerit">0</div>
                        <div class="rpt-kpi-subtitle">Total penalties</div>
                    </div>
                    <div class="rpt-kpi-card rpt-success">
                        <div class="rpt-kpi-title">Completion Rate</div>
                        <div class="rpt-kpi-value" id="rptKpiCompletion">0%</div>
                        <div class="rpt-kpi-subtitle">Tasks completed</div>
                    </div>
                    <div class="rpt-kpi-card rpt-info">
                        <div class="rpt-kpi-title">On-Time %</div>
                        <div class="rpt-kpi-value" id="rptKpiOnTime">0%</div>
                        <div class="rpt-kpi-subtitle">Within deadline</div>
                    </div>
                    <div class="rpt-kpi-card rpt-warning">
                        <div class="rpt-kpi-title">Avg Delay</div>
                        <div class="rpt-kpi-value" id="rptKpiDelay">0</div>
                        <div class="rpt-kpi-subtitle">Days average</div>
                    </div>
                    <div class="rpt-kpi-card rpt-danger">
                        <div class="rpt-kpi-title">Overdue Tasks</div>
                        <div class="rpt-kpi-value" id="rptKpiOverdue">0</div>
                        <div class="rpt-kpi-subtitle">Currently overdue</div>
                    </div>
                </div>

                <!-- Faculty List Table -->
                <div class="rpt-table-container">
                    <h5>👥 Faculty Performance Summary</h5>
                    <div class="table-responsive">
                        <table id="rptFacListTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="rptheadrow">
                                    <th>Faculty Name</th>
                                    <th>Total Tasks</th>
                                    <th>Completed</th>
                                    <th>Pending</th>
                                    <th>Overdue</th>
                                    <th>Completion %</th>
                                    <th>On-Time %</th>
                                    <th>Credit Score</th>
                                    <th>Demerit Score</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Faculty Task Details Table -->
                <div class="rpt-table-container">
                    <h5>📋 Task Details</h5>
                    <div class="table-responsive">
                        <table id="rptFacTaskTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="rprheadrow">
                                    <th>Task ID</th>
                                    <th>Title</th>
                                    <th>Assigned By</th>
                                    <th>Assigned To</th>
                                    <th>Assigned Date</th>
                                    <th>Due Date</th>
                                    <th>Completed On</th>
                                    <th>Days Late</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Export Buttons -->
                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-danger rpt-btn-export" onclick="rptExportFacultyPDF()">
                        📄 Export PDF
                    </button>
                    <button class="btn btn-success rpt-btn-export" onclick="rptExportFacultyExcel()">
                        📊 Export Excel
                    </button>
                </div>
            </div>
            <!-- MEETING REPORTS TAB -->
            <div class="tab-pane fade" id="rptMeetingReports" role="tabpanel">
                <!-- Summary Cards -->
                <div class="rpt-kpi-section" id="rptMeetingKPIs">
                    <div class="rpt-kpi-card rpt-info">
                        <div class="rpt-kpi-title">Total Scheduled</div>
                        <div class="rpt-kpi-value" id="rptKpiTotalScheduled">0</div>
                        <div class="rpt-kpi-subtitle">Meetings scheduled</div>
                    </div>
                    <div class="rpt-kpi-card rpt-warning">
                        <div class="rpt-kpi-title">Pending Requests</div>
                        <div class="rpt-kpi-value" id="rptKpiPendingReq">0</div>
                        <div class="rpt-kpi-subtitle">Awaiting approval</div>
                    </div>
                    <div class="rpt-kpi-card rpt-success">
                        <div class="rpt-kpi-title">Approved Requests</div>
                        <div class="rpt-kpi-value" id="rptKpiApprovedReq">0</div>
                        <div class="rpt-kpi-subtitle">Requests approved</div>
                    </div>
                    <div class="rpt-kpi-card rpt-danger">
                        <div class="rpt-kpi-title">Rejected Requests</div>
                        <div class="rpt-kpi-value" id="rptKpiRejectedReq">0</div>
                        <div class="rpt-kpi-subtitle">Requests rejected</div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="rpt-filter-section">
                    <h5>📋 Filters</h5>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Academic Year</label>
                            <select id="rptMeetingYear" class="form-select">
                                <option value="2024-2025">2024-2025</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Period Type</label>
                            <select id="rptMeetingPeriodType" class="form-select">
                                <option value="all">All Time</option>
                                <option value="month">Monthly</option>
                                <option value="week">Weekly</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Month</label>
                            <select id="rptMeetingMonth" class="form-select" disabled>
                                <option value="">Select Month</option>
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Week</label>
                            <select id="rptMeetingWeek" class="form-select" disabled>
                                <option value="">Select Week</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-primary w-100" onclick="rptLoadMeetingData()">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                    <div class="row g-3 mt-2" id="rptMeetingDateRange" style="display:none;">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Start Date</label>
                            <input type="date" id="rptMeetingStartDate" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">End Date</label>
                            <input type="date" id="rptMeetingEndDate" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Scheduled Meetings Table -->
                <div class="rpt-table-container">
                    <h5>📅 Scheduled Meetings</h5>
                    <div class="table-responsive">
                        <table id="rptScheduledMeetingsTable" class="table table-striped table-bordered"
                            style="width:100%">
                            <thead>
                                <tr class="rptheadrow">
                                    <th>Title</th>
                                    <th>Agenda</th>
                                    <th>Date & Time</th>
                                    <th>Location/Mode</th>
                                    <th>Participants</th>
                                    <th>Organized By</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Requested Meetings Table -->
                <div class="rpt-table-container">
                    <h5>📨 Meeting Requests</h5>
                    <div class="table-responsive">
                        <table id="rptRequestedMeetingsTable" class="table table-striped table-bordered"
                            style="width:100%">
                            <thead>
                                <tr class="rptheadrow">
                                    <th>Requester</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Purpose</th>
                                    <th>Requested Time</th>
                                    <th>Status</th>

                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Export Buttons -->
                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-danger rpt-btn-export" onclick="rptExportMeetingPDF()">
                        📄 Export PDF
                    </button>

                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.1/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>

        let rptUserRole = '';
        let rptUserDept = '';
        let rptIsHOD = false;
        let rptCurrentAcademicYear = '';
        let rptPerformerType = 'department';

        $(document).ready(function () {
            rptGetUserInfo();
            rptSetupEventHandlers();
            rptGenerateWeekOptions();
        });

        function rptGetUserInfo() {
            $.post('backend/backend_combined.php?route=reportbackend', { action: 'getUserInfo' }, function (response) {
                if (response.status === 'success') {
                    rptUserRole = response.role;
                    rptUserDept = response.department;
                    rptIsHOD = response.isHOD;

                    // Set global variables for export functions
                    window.rptUserRole = response.role;
                    window.rptUserDept = response.department;

                    const badgeClass = rptIsHOD ? 'rpt-role-hod' : 'rpt-role-principal';
                    const roleText = rptIsHOD ? `HOD - ${rptUserDept}` : `${rptUserRole}`;
                    $('#rptRoleBadge').addClass(badgeClass).text(roleText);
// Set performer type based on role
            if (rptIsHOD) {
                rptPerformerType = 'faculty'; // HOD should always see faculty performers
            } else {
                rptPerformerType = 'department'; // Principal should always see department performers
            }
                    // Load academic years first, then departments, then data
                    rptLoadAcademicYears();
                    // Add this line at the end of the success callback
                    rptShowHideMeetingTab();
                }
            }, 'json');
        }
        // Add this in the $(document).ready(function() after rptGetUserInfo();
        function rptShowHideMeetingTab() {
            if (rptUserRole === 'PRINCIPAL' || rptUserRole === 'CHAIRMAN' || rptUserRole === 'ADMIN') {
                $('#rpt-meeting-tab').parent().show();
            } else {
                $('#rpt-meeting-tab').parent().hide();
            }
        }

        function rptLoadAcademicYears() {
            $.post('backend/backend_combined.php?route=reportbackend', { action: 'getAcademicYears' }, function (response) {
                if (response.status === 'success') {
                    rptCurrentAcademicYear = response.currentAcademicYear;

                    let options = '';
                    response.academicYears.forEach(year => {
                        const selected = year.isCurrent ? ' selected' : '';
                        const currentLabel = year.isCurrent ? ' (Current)' : '';
                        options += `<option value="${year.value}"${selected}>${year.label}${currentLabel}</option>`;
                    });

                    $('#rptDeptYear, #rptFacYear').html(options);

                    // Now load departments and initial data
                    rptLoadDepartments();
                    rptLoadDepartmentData();
                }
            }, 'json');
        }

        function rptLoadDepartments() {
            $.post('backend/backend_combined.php?route=reportbackend', { action: 'getDepartments' }, function (response) {
                if (response.status === 'success') {
                    let options = '<option value="">All Departments</option>';
                    response.departments.forEach(d => {
                        options += `<option value="${d}">${d}</option>`;
                    });

                    $('#rptDeptFilter, #rptFacDept').html(options);

                    if (response.locked) {
                        // For HOD: Set their department and disable dropdown
                        $('#rptDeptFilter, #rptFacDept')
                            .addClass('rpt-locked-field')
                            .prop('disabled', true);

                        if (rptIsHOD && rptUserDept) {
                            $('#rptFacDept').val(rptUserDept);
                            rptLoadFaculties(rptUserDept);

                            // Hide department filter for HOD in Department Performance tab
                            $('#rptDeptFilter').closest('.col-md-3').hide();
                        }
                    }
                }
            }, 'json');
        }

        function rptSetupEventHandlers() {
            // Department tab period type change
            $('#rptDeptPeriodType').change(function () {
                const type = $(this).val();
                $('#rptDeptMonth, #rptDeptWeek, #rptDeptStartDate, #rptDeptEndDate').prop('disabled', true);
                $('#rptDeptDateRange').hide();

                if (type === 'month') {
                    $('#rptDeptMonth').prop('disabled', false);
                } else if (type === 'week') {
                    $('#rptDeptDateRange').show();
                    $('#rptDeptWeek').prop('disabled', false);
                } else if (type === 'custom') {
                    $('#rptDeptDateRange').show();
                    $('#rptDeptStartDate, #rptDeptEndDate').prop('disabled', false);
                }
            });

            // Faculty tab period type change
            $('#rptFacPeriodType').change(function () {
                const type = $(this).val();
                $('#rptFacMonth, #rptFacWeek, #rptFacStartDate, #rptFacEndDate').prop('disabled', true);
                $('#rptFacDateRange').hide();

                if (type === 'month') {
                    $('#rptFacDateRange').show();
                    $('#rptFacMonth').prop('disabled', false);
                } else if (type === 'week') {
                    $('#rptFacDateRange').show();
                    $('#rptFacWeek').prop('disabled', false);
                } else if (type === 'custom') {
                    $('#rptFacDateRange').show();
                    $('#rptFacStartDate, #rptFacEndDate').prop('disabled', false);
                }
            });

            // Department change in faculty tab
            $('#rptFacDept').change(function () {
                rptLoadFaculties($(this).val());
            });

            // Faculty selection change
            $('#rptFacFilter').change(function () {
                if ($(this).val()) {
                    rptLoadFacultyData();
                }
            });
            // Meeting tab period type change
            $('#rptMeetingPeriodType').change(function () {
                const type = $(this).val();
                $('#rptMeetingMonth, #rptMeetingWeek, #rptMeetingStartDate, #rptMeetingEndDate').prop('disabled', true);
                $('#rptMeetingDateRange').hide();

                if (type === 'month') {
                    $('#rptMeetingMonth').prop('disabled', false);
                } else if (type === 'week') {
                    $('#rptMeetingDateRange').show();
                    $('#rptMeetingWeek').prop('disabled', false);
                } else if (type === 'custom') {
                    $('#rptMeetingDateRange').show();
                    $('#rptMeetingStartDate, #rptMeetingEndDate').prop('disabled', false);
                }
            });

            // Tab switch event for meeting
            $('#rpt-meeting-tab').on('shown.bs.tab', function () {
                rptLoadMeetingData();
            });

            // Tab switch event
            $('#rpt-faculty-tab').on('shown.bs.tab', function () {
    rptLoadFacultyData();
});
$('#rpt-dept-tab').on('shown.bs.tab', function () {
    rptLoadDepartmentData();
});
        }

        function rptLoadFaculties(dept) {
            $.post('backend/backend_combined.php?route=reportbackend', {
                action: 'getFacultyList',
                department: dept || (rptIsHOD ? rptUserDept : '')
            }, function (response) {
                if (response.status === 'success') {
                    let options = '<option value="">All Faculty</option>';
                    response.faculties.forEach(f => {
                        options += `<option value="${f.fac_id}">${f.fac_name}</option>`;
                    });
                    $('#rptFacFilter').html(options);
                }
            }, 'json');
        }

        function rptGenerateWeekOptions() {
            const currentYear = new Date().getFullYear();
            let weekOptions = '<option value="">Select Week</option>';

            for (let week = 1; week <= 52; week++) {
                const startDate = rptGetWeekStartDate(currentYear, week);
                const endDate = new Date(startDate);
                endDate.setDate(endDate.getDate() + 6);

                weekOptions.append(`<option value="${week}" data-start="${rptFormatDate(startDate)}" data-end="${rptFormatDate(endDate)}">
            Week ${week} (${rptFormatDate(startDate)} to ${rptFormatDate(endDate)})
        </option>`);
            }

            $('#rptDeptWeek, #rptFacWeek').html(weekOptions);
        }

        function rptGetWeekStartDate(year, week) {
            const jan1 = new Date(year, 0, 1);
            const dayOfWeek = jan1.getDay();
            const firstSaturday = dayOfWeek <= 6 ? 6 - dayOfWeek : 6;
            const weekStart = new Date(year, 0, 1 + firstSaturday + (week - 1) * 7);
            return weekStart;
        }

        function rptFormatDate(date) {
            return date.toISOString().split('T')[0];
        }

        function rptLoadDepartmentData() {
            const filters = {
                action: 'getDepartmentTaskSummary',
                year: $('#rptDeptYear').val(),
                department: $('#rptDeptFilter').val(),
                periodType: $('#rptDeptPeriodType').val(),
                month: $('#rptDeptMonth').val(),
                week: $('#rptDeptWeek').val(),
                startDate: $('#rptDeptStartDate').val(),
                endDate: $('#rptDeptEndDate').val()
            };

            $.post('backend/backend_combined.php?route=reportbackend', filters, function (response) {
                if (response.status === 'success') {
                    rptPopulateDeptTaskTable(response.taskSummary);
                }
            }, 'json');

            $.post('backend/backend_combined.php?route=reportbackend', { ...filters, action: 'getDepartmentPerformance' },
    function (response) {
        if (response.status === 'success') {
            rptPopulateDeptPerfTable(response.performance);
            
            // Only show department performers for Principal/Chairman/Admin
            // For HOD, load and show faculty performers instead
            if (rptIsHOD) {
                // Load faculty data for top performers display
                rptLoadTopPerformersForHOD();
            } else {
                rptDisplayTopPerformers(response.performance, true); // true = show departments
            }
        }
    }, 'json');
                
        }

        function rptLoadFacultyData() {
            const filters = {
                action: 'getFacultyPerformance',
                year: $('#rptFacYear').val(),
                department: $('#rptFacDept').val() || (rptIsHOD ? rptUserDept : ''),
                faculty: $('#rptFacFilter').val(),
                periodType: $('#rptFacPeriodType').val(),
                month: $('#rptFacMonth').val(),
                week: $('#rptFacWeek').val(),
                startDate: $('#rptFacStartDate').val(),
                endDate: $('#rptFacEndDate').val(),
                status: $('#rptFacStatus').val()
            };

            $.post('backend/backend_combined.php?route=reportbackend', filters, function (response) {
                if (response.status === 'success') {
                    rptUpdateFacultyKPIs(response.kpis);
                    rptPopulateFacultyListTable(response.facultyList);
                    
                }
            }, 'json');

            $.post('backend/backend_combined.php?route=reportbackend', { ...filters, action: 'getFacultyTasks' },
                function (response) {
                    if (response.status === 'success') {
                        rptPopulateFacultyTaskTable(response.tasks);
                    }
                }, 'json');
        }

        // New function to load top performers for HOD
function rptLoadTopPerformersForHOD() {
    const filters = {
        action: 'getFacultyPerformance',
        year: $('#rptDeptYear').val(), // Use dept tab year filter
        department: rptUserDept, // Always use HOD's department
        faculty: '',
        periodType: $('#rptDeptPeriodType').val(),
        month: $('#rptDeptMonth').val(),
        week: $('#rptDeptWeek').val(),
        startDate: $('#rptDeptStartDate').val(),
        endDate: $('#rptDeptEndDate').val(),
        status: ''
    };

    $.post('backend/backend_combined.php?route=reportbackend', filters, function (response) {
        if (response.status === 'success') {
            rptDisplayTopPerformers(response.facultyList, false); // false = show faculty
        }
    }, 'json');
}



        function rptPopulateDeptTaskTable(data) {
            if ($.fn.DataTable.isDataTable('#rptDeptTaskTable')) {
                $('#rptDeptTaskTable').DataTable().destroy();
            }

            $('#rptDeptTaskTable').DataTable({
                data: data,
                columns: [
                    { data: 'department' },
                    { data: 'total_tasks' },
                    { data: 'completed' },
                    { data: 'pending' },
                    { data: 'overdue' },
                    { data: 'completion_percent', render: (d) => (d || 0) + '%' },
                    { data: 'ontime_percent', render: (d) => (d || 0) + '%' }
                ],
                pageLength: 10,
                order: [[5, 'desc']]
            });
        }

        function rptPopulateDeptPerfTable(data) {
            if ($.fn.DataTable.isDataTable('#rptDeptPerfTable')) {
                $('#rptDeptPerfTable').DataTable().destroy();
            }

            $('#rptDeptPerfTable').DataTable({
                data: data,
                columns: [
                    { data: 'department' },
                    { data: 'avg_credit' },
                    { data: 'avg_demerit' },
                    { data: 'avg_completion', render: (d) => (d || 0) + '%' },
                    { data: 'avg_ontime', render: (d) => (d || 0) + '%' },
                    { data: 'best_faculty' },
                    { data: 'worst_faculty' }
                ],
                pageLength: 10,
                order: [[3, 'desc']]
            });
        }

        function rptUpdateFacultyKPIs(kpis) {
            $('#rptKpiCredit').text(kpis.credit_score || 0);
            $('#rptKpiDemerit').text(kpis.demerit_score || 0);
            $('#rptKpiCompletion').text((kpis.completion_rate || 0) + '%');
            $('#rptKpiOnTime').text((kpis.ontime_percent || 0) + '%');
            $('#rptKpiDelay').text(kpis.avg_delay || 0);
            $('#rptKpiOverdue').text(kpis.overdue_count || 0);
        }

        function rptPopulateFacultyListTable(data) {
            if ($.fn.DataTable.isDataTable('#rptFacListTable')) {
                $('#rptFacListTable').DataTable().destroy();
            }

            $('#rptFacListTable').DataTable({
                data: data,
                columns: [
                    { data: 'faculty_name' },
                    { data: 'total_tasks' },
                    { data: 'completed' },
                    { data: 'pending' },
                    { data: 'overdue' },
                    { data: 'completion_percent', render: (d) => (d || 0) + '%' },
                    { data: 'ontime_percent', render: (d) => (d || 0) + '%' },
                    { data: 'credit_score' },
                    { data: 'demerit_score' }
                ],
                pageLength: 10,
                order: [[5, 'desc']]
            });
        }

        function rptPopulateFacultyTaskTable(data) {
            if ($.fn.DataTable.isDataTable('#rptFacTaskTable')) {
                $('#rptFacTaskTable').DataTable().destroy();
            }

            $('#rptFacTaskTable').DataTable({
                data: data,
                columns: [
                    { data: 'task_id' },
                    { data: 'title' },
                    { data: 'assigned_by' },
                    { data: 'assigned_to' },
                    { data: 'assigned_date' },
                    { data: 'due_date' },
                    { data: 'completed_on' },
                    { data: 'days_late', render: (d) => d > 0 ? `<span class="text-danger">${d}</span>` : d },
                    {
                        data: 'priority', render: (d) => {
                            const cls = d.includes('Immediate') ? 'rpt-priority-high' :
                                d.includes('Important') ? 'rpt-priority-medium' : 'rpt-priority-low';
                            return `<span class="${cls}">${d}</span>`;
                        }
                    },
                    {
                        data: 'status', render: (d) => {
                            const cls = d === 'Completed' ? 'rpt-status-completed' :
                                d === 'Pending' ? 'rpt-status-pending' :
                                    d === 'Overdue' ? 'rpt-status-overdue' : 'rpt-status-inprogress';
                            return `<span class="rpt-status-badge ${cls}">${d}</span>`;
                        }
                    }
                ],
                pageLength: 10,
                order: [[4, 'desc']]
            });
        }

        function rptLoadMeetingData() {
            const filters = {
                action: 'getMeetingReports',
                year: $('#rptMeetingYear').val(),
                periodType: $('#rptMeetingPeriodType').val(),
                month: $('#rptMeetingMonth').val(),
                week: $('#rptMeetingWeek').val(),
                startDate: $('#rptMeetingStartDate').val(),
                endDate: $('#rptMeetingEndDate').val()
            };

            $.post('backend/backend_combined.php?route=reportbackend', filters, function (response) {
                if (response.status === 'success') {
                    rptUpdateMeetingKPIs(response.kpis);
                    rptPopulateScheduledMeetingsTable(response.scheduledMeetings);
                    rptPopulateRequestedMeetingsTable(response.requestedMeetings);
                }
            }, 'json');
        }

        function rptUpdateMeetingKPIs(kpis) {
            $('#rptKpiTotalScheduled').text(kpis.total_scheduled || 0);
            $('#rptKpiPendingReq').text(kpis.pending_requests || 0);
            $('#rptKpiApprovedReq').text(kpis.approved_requests || 0);
            $('#rptKpiRejectedReq').text(kpis.rejected_requests || 0);
        }

        function rptPopulateScheduledMeetingsTable(data) {
            if ($.fn.DataTable.isDataTable('#rptScheduledMeetingsTable')) {
                $('#rptScheduledMeetingsTable').DataTable().destroy();
            }

            $('#rptScheduledMeetingsTable').DataTable({
                data: data,
                columns: [
                    { data: 'title' },
                    { data: 'agenda' },
                    { data: 'datetime' },
                    { data: 'location' },
                    { data: 'participants' },
                    { data: 'organized_by' },
                    {
                        data: 'status',
                        render: (d) => {
                            const cls = d === 'Completed' ? 'rpt-status-completed' : 'rpt-status-pending';
                            return `<span class="rpt-status-badge ${cls}">${d}</span>`;
                        }
                    }
                ],
                pageLength: 10,
                order: [[2, 'desc']]
            });
        }

        function rptPopulateRequestedMeetingsTable(data) {
            if ($.fn.DataTable.isDataTable('#rptRequestedMeetingsTable')) {
                $('#rptRequestedMeetingsTable').DataTable().destroy();
            }

            $('#rptRequestedMeetingsTable').DataTable({
                data: data,
                columns: [
                    { data: 'requester' },
                    { data: 'department' },
                    { data: 'role' },
                    { data: 'purpose' },
                    { data: 'requested_time' },
                    {
                        data: 'status',
                        render: (d) => {
                            const cls = d === 'Approved' ? 'rpt-status-completed' :
                                d === 'Rejected' ? 'rpt-status-overdue' : 'rpt-status-pending';
                            return `<span class="rpt-status-badge ${cls}">${d}</span>`;
                        }
                    },

                ],
                pageLength: 10,
                order: [[4, 'desc']]
            });
        }





        //Export Functions


        function loadImageAsBase64(url, callback, errorCallback) {
            let img = new Image();
            img.crossOrigin = 'Anonymous';

            img.onload = function () {
                try {
                    let canvas = document.createElement('canvas');
                    canvas.width = this.width;
                    canvas.height = this.height;

                    let ctx = canvas.getContext('2d');
                    ctx.drawImage(this, 0, 0);

                    let dataURL = canvas.toDataURL('image/png');
                    callback(dataURL);
                } catch (error) {
                    console.error('Error converting image to base64:', error);
                    if (errorCallback) errorCallback(error);
                }
            };

            img.onerror = function (error) {
                console.error('Error loading image:', error);
                if (errorCallback) {
                    errorCallback(error);
                } else {
                    callback(null);
                }
            };

            img.src = url;
        }

        function rptExportDeptPDF() {
            if (typeof window.jspdf === 'undefined') {
                alert('jsPDF library is not loaded. Please include the jsPDF script.');
                return;
            }

            if (!$.fn.DataTable.isDataTable('#rptDeptTaskTable')) {
                alert('Department Task table is not initialized');
                return;
            }

            const rptUserRole = window.rptUserRole || 'Unknown';
            const rptUserDept = window.rptUserDept || 'All';

            loadImageAsBase64('image/mkce_logo2.png', function (collegeLogoBase64) {
                try {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF('landscape');
                    const pageWidth = doc.internal.pageSize.getWidth();
                    const pageHeight = doc.internal.pageSize.getHeight();

                    const taskTable = $('#rptDeptTaskTable').DataTable();
                    const taskData = taskTable.rows().data().toArray();

                    if (taskData.length === 0) {
                        alert('No data available to export');
                        return;
                    }

                    const headerHeight = 55;

                    // Add subtitle before first table
                    doc.setFontSize(11);
                    doc.setFont('helvetica', 'bold');
                    doc.setTextColor(0, 0, 0);
                    doc.text('Department Task Summary:', 14, headerHeight + 8);

                    doc.autoTable({
                        head: [['Department', 'Total', 'Completed', 'Pending', 'Overdue', 'Completion %', 'On-Time %']],
                        body: taskData.map(row => [
                            row.department || '',
                            row.total_tasks || 0,
                            row.completed || 0,
                            row.pending || 0,
                            row.overdue || 0,
                            (row.completion_percent || 0) + '%',
                            (row.ontime_percent || 0) + '%'
                        ]),
                        startY: headerHeight + 13,
                        margin: { top: headerHeight + 13 },
                        theme: 'grid',
                        headStyles: {
                            fillColor: [102, 126, 234],
                            textColor: [255, 255, 255],
                            fontStyle: 'bold'
                        },
                        didDrawPage: function (data) {
                            // White background for header area
                            doc.setFillColor(255, 255, 255);
                            doc.rect(0, 0, pageWidth, headerHeight, 'F');

                            // Border around header
                            doc.setDrawColor(0, 76, 153);
                            doc.setLineWidth(0.8);
                            doc.rect(5, 5, pageWidth - 10, headerHeight - 5);

                            // Add logo directly without background box
                            if (collegeLogoBase64) {
                                try {
                                    doc.addImage(collegeLogoBase64, 'PNG', 10, 10, 31, 26);
                                } catch (imgError) {
                                    console.error('Error adding image to PDF:', imgError);
                                }
                            }

                            // Line separator after logo
                            doc.setDrawColor(200, 200, 200);
                            doc.setLineWidth(0.3);
                            doc.line(10, 38, pageWidth - 10, 38);

                            // College name
                            doc.setTextColor(0, 0, 0);
                            doc.setFontSize(16);
                            doc.setFont('helvetica', 'bold');
                            doc.text('M.Kumarasamy College of Engineering, Karur-639 113', pageWidth / 2, 20, { align: 'center' });

                            // Subtitle
                            doc.setFontSize(10);
                            doc.setFont('helvetica', 'normal');
                            doc.setTextColor(100, 100, 100);
                            doc.text('(An Autonomous Institution Affiliated to Anna University, Chennai)', pageWidth / 2, 27, { align: 'center' });

                            // Blue bar with report title - matching image color
                            doc.setFillColor(0, 102, 204);
                            doc.rect(8, 42, pageWidth - 16, 10, 'F');
                            doc.setTextColor(255, 255, 255);
                            doc.setFontSize(13);
                            doc.setFont('helvetica', 'bold');
                            doc.text('DEPARTMENT EVALUATION REPORT', pageWidth / 2, 48.5, { align: 'center' });

                            // Metadata - top right
                            doc.setTextColor(100, 100, 100);
                            doc.setFontSize(8);
                            doc.setFont('helvetica', 'normal');
                            doc.text(`Report Date: ${new Date().toLocaleDateString()}`, pageWidth - 10, 10, { align: 'right' });
                            doc.text(`Department: ${rptUserDept}`, pageWidth - 10, 18, { align: 'right' });

                            // Page number at bottom center
                            doc.setFontSize(8);
                            doc.setTextColor(100, 100, 100);
                            const pageStr = 'Page ' + doc.internal.getNumberOfPages();
                            doc.text(pageStr, pageWidth / 2, pageHeight - 5, { align: 'center' });
                        }
                    });

                    // Add second table if exists
                    if ($.fn.DataTable.isDataTable('#rptDeptPerfTable')) {
                        const perfTable = $('#rptDeptPerfTable').DataTable();
                        const perfData = perfTable.rows().data().toArray();

                        if (perfData.length > 0) {
                            // Add subtitle before performance table
                            const startY = doc.lastAutoTable.finalY + 15;
                            doc.setFontSize(11);
                            doc.setFont('helvetica', 'bold');
                            doc.setTextColor(0, 0, 0);
                            doc.text('Department Performance Metrics:', 14, startY);

                            doc.autoTable({
                                head: [['Department', 'Avg Credit', 'Avg Demerit', 'Avg Completion %', 'Avg On-Time %', 'Best Faculty', 'Worst Faculty']],
                                body: perfData.map(row => [
                                    row.department || '',
                                    row.avg_credit || 0,
                                    row.avg_demerit || 0,
                                    (row.avg_completion || 0) + '%',
                                    (row.avg_ontime || 0) + '%',
                                    row.best_faculty || '-',
                                    row.worst_faculty || '-'
                                ]),
                                startY: startY + 5,
                                theme: 'grid',
                                headStyles: {
                                    fillColor: [102, 126, 234],
                                    textColor: [255, 255, 255],
                                    fontStyle: 'bold'
                                },
                                didDrawPage: function (data) {
                                    // Page number at bottom
                                    doc.setFontSize(8);
                                    doc.setTextColor(100, 100, 100);
                                    const pageStr = 'Page ' + doc.internal.getNumberOfPages();
                                    doc.text(pageStr, pageWidth / 2, pageHeight - 5, { align: 'center' });
                                }
                            });
                        }
                    }

                    doc.save('department_evaluation_report.pdf');

                } catch (error) {
                    console.error('Error generating PDF:', error);
                    alert('Error generating PDF. Please check the console for details.');
                }
            }, function (error) {
                console.warn('Failed to load logo, generating PDF without it');
                rptExportDeptPDF();
            });
        }

        function rptExportDeptExcel() {
            const taskTable = $('#rptDeptTaskTable').DataTable();
            const taskData = taskTable.rows().data().toArray();

            const perfTable = $('#rptDeptPerfTable').DataTable();
            const perfData = perfTable.rows().data().toArray();

            const rptUserRole = window.rptUserRole || 'Unknown';
            const rptUserDept = window.rptUserDept || 'All';

            // Create workbook
            const wb = XLSX.utils.book_new();

            // Sheet 1: Task Summary with styled header
            const ws1_data = [
                ['', '', 'M.Kumarasamy College of Engineering, Karur-639 113'],
                ['', '', '(An Autonomous Institution Affiliated to Anna University, Chennai)'],
                [''],
                ['', '', 'DEPARTMENT EVALUATION REPORT'],
                [''],
                [`Report Date: ${new Date().toLocaleDateString()}`, '', '', '', '', `Role: ${rptUserRole}`],
                [`Department: ${rptUserDept}`],
                [''],
                ['Department Task Summary'],
                ['Department', 'Total Tasks', 'Completed', 'Pending', 'Overdue', 'Completion %', 'On-Time %'],
                ...taskData.map(row => [
                    row.department, row.total_tasks, row.completed, row.pending,
                    row.overdue, row.completion_percent, row.ontime_percent
                ])
            ];

            const ws1 = XLSX.utils.aoa_to_sheet(ws1_data);

            // Merge cells for header
            ws1['!merges'] = [
                { s: { r: 0, c: 2 }, e: { r: 0, c: 6 } }, // College name
                { s: { r: 1, c: 2 }, e: { r: 1, c: 6 } }, // Subtitle
                { s: { r: 3, c: 2 }, e: { r: 3, c: 6 } }, // Report title
                { s: { r: 8, c: 0 }, e: { r: 8, c: 6 } }  // Section title
            ];

            // Column widths
            ws1['!cols'] = [
                { wch: 3 }, { wch: 3 }, { wch: 20 }, { wch: 12 }, { wch: 12 },
                { wch: 12 }, { wch: 15 }
            ];

            // Row heights
            ws1['!rows'] = [
                { hpt: 25 }, // Row 1
                { hpt: 20 }, // Row 2
                { hpt: 10 }, // Row 3
                { hpt: 25 }, // Row 4
                { hpt: 10 }, // Row 5
                { hpt: 18 }, // Row 6
                { hpt: 18 }, // Row 7
                { hpt: 10 }, // Row 8
                { hpt: 22 }  // Row 9 - Section title
            ];

            // Styling
            const headerStyle = {
                font: { name: 'Calibri', sz: 14, bold: true, color: { rgb: "000000" } },
                alignment: { horizontal: 'center', vertical: 'center' },
                fill: { fgColor: { rgb: "FFFFFF" } }
            };

            const subtitleStyle = {
                font: { name: 'Calibri', sz: 10, italic: true, color: { rgb: "666666" } },
                alignment: { horizontal: 'center', vertical: 'center' }
            };

            const titleStyle = {
                font: { name: 'Calibri', sz: 13, bold: true, color: { rgb: "FFFFFF" } },
                alignment: { horizontal: 'center', vertical: 'center' },
                fill: { fgColor: { rgb: "0066CC" } }
            };

            const sectionTitleStyle = {
                font: { name: 'Calibri', sz: 11, bold: true, color: { rgb: "000000" } },
                alignment: { horizontal: 'center', vertical: 'center' },
                fill: { fgColor: { rgb: "E7E6E6" } }
            };

            const tableHeaderStyle = {
                font: { name: 'Calibri', sz: 10, bold: true, color: { rgb: "FFFFFF" } },
                alignment: { horizontal: 'center', vertical: 'center' },
                fill: { fgColor: { rgb: "667EEA" } },
                border: {
                    top: { style: 'thin', color: { rgb: "000000" } },
                    bottom: { style: 'thin', color: { rgb: "000000" } },
                    left: { style: 'thin', color: { rgb: "000000" } },
                    right: { style: 'thin', color: { rgb: "000000" } }
                }
            };

            const dataStyle = {
                font: { name: 'Calibri', sz: 10 },
                alignment: { horizontal: 'center', vertical: 'center' },
                border: {
                    top: { style: 'thin', color: { rgb: "CCCCCC" } },
                    bottom: { style: 'thin', color: { rgb: "CCCCCC" } },
                    left: { style: 'thin', color: { rgb: "CCCCCC" } },
                    right: { style: 'thin', color: { rgb: "CCCCCC" } }
                }
            };

            // Apply styles
            if (!ws1['C1']) ws1['C1'] = { t: 's', v: '' };
            ws1['C1'].s = headerStyle;

            if (!ws1['C2']) ws1['C2'] = { t: 's', v: '' };
            ws1['C2'].s = subtitleStyle;

            if (!ws1['C4']) ws1['C4'] = { t: 's', v: '' };
            ws1['C4'].s = titleStyle;

            if (!ws1['A9']) ws1['A9'] = { t: 's', v: '' };
            ws1['A9'].s = sectionTitleStyle;

            // Apply table header styles
            for (let col = 0; col < 7; col++) {
                const cellRef = XLSX.utils.encode_cell({ r: 9, c: col });
                if (!ws1[cellRef]) ws1[cellRef] = { t: 's', v: '' };
                ws1[cellRef].s = tableHeaderStyle;
            }

            // Apply data styles
            for (let row = 10; row < 10 + taskData.length; row++) {
                for (let col = 0; col < 7; col++) {
                    const cellRef = XLSX.utils.encode_cell({ r: row, c: col });
                    if (ws1[cellRef]) {
                        ws1[cellRef].s = dataStyle;
                    }
                }
            }

            XLSX.utils.book_append_sheet(wb, ws1, 'Task Summary');

            // Sheet 2: Performance Metrics with styling
            const lastRow = 10 + taskData.length;
            const ws2_data = [
                ['Department Performance Metrics'],
                [''],
                ['Department', 'Avg Credit', 'Avg Demerit', 'Avg Completion %', 'Avg On-Time %', 'Best Faculty', 'Worst Faculty'],
                ...perfData.map(row => [
                    row.department, row.avg_credit, row.avg_demerit,
                    row.avg_completion, row.avg_ontime, row.best_faculty, row.worst_faculty
                ])
            ];

            const ws2 = XLSX.utils.aoa_to_sheet(ws2_data);

            ws2['!merges'] = [
                { s: { r: 0, c: 0 }, e: { r: 0, c: 6 } }
            ];

            ws2['!cols'] = [
                { wch: 20 }, { wch: 12 }, { wch: 12 }, { wch: 15 },
                { wch: 15 }, { wch: 20 }, { wch: 20 }
            ];

            ws2['!rows'] = [
                { hpt: 25 },
                { hpt: 10 }
            ];

            // Apply styles to sheet 2
            if (!ws2['A1']) ws2['A1'] = { t: 's', v: '' };
            ws2['A1'].s = sectionTitleStyle;

            // Table header
            for (let col = 0; col < 7; col++) {
                const cellRef = XLSX.utils.encode_cell({ r: 2, c: col });
                if (!ws2[cellRef]) ws2[cellRef] = { t: 's', v: '' };
                ws2[cellRef].s = tableHeaderStyle;
            }

            // Data rows
            for (let row = 3; row < 3 + perfData.length; row++) {
                for (let col = 0; col < 7; col++) {
                    const cellRef = XLSX.utils.encode_cell({ r: row, c: col });
                    if (ws2[cellRef]) {
                        ws2[cellRef].s = dataStyle;
                    }
                }
            }

            XLSX.utils.book_append_sheet(wb, ws2, 'Performance Metrics');

            XLSX.writeFile(wb, 'department_evaluation_report.xlsx');
        }


        function rptExportFacultyPDF() {
            if (typeof window.jspdf === 'undefined') {
                alert('jsPDF library is not loaded.');
                return;
            }

            const rptUserRole = window.rptUserRole || 'Unknown';
            const deptFilter = $('#rptFacDept').val() || 'All';

            loadImageAsBase64('image/mkce_logo2.png', function (collegeLogoBase64) {
                try {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF('landscape');
                    const pageWidth = doc.internal.pageSize.getWidth();
                    const pageHeight = doc.internal.pageSize.getHeight();

                    const facTable = $('#rptFacListTable').DataTable();
                    const facData = facTable.rows().data().toArray();

                    const headerHeight = 55;

                    // Add subtitle before first table
                    doc.setFontSize(11);
                    doc.setFont('helvetica', 'bold');
                    doc.setTextColor(0, 0, 0);
                    doc.text('Faculty Performance Summary:', 14, headerHeight + 8);

                    doc.autoTable({
                        head: [['Faculty', 'Total', 'Completed', 'Pending', 'Overdue', 'Completion %', 'On-Time %', 'Credit', 'Demerit']],
                        body: facData.map(row => [
                            row.faculty_name, row.total_tasks, row.completed, row.pending,
                            row.overdue, row.completion_percent + '%', row.ontime_percent + '%',
                            row.credit_score, row.demerit_score
                        ]),
                        startY: headerHeight + 13,
                        theme: 'grid',
                        headStyles: {
                            fillColor: [102, 126, 234],
                            textColor: [255, 255, 255],
                            fontStyle: 'bold'
                        },
                        didDrawPage: function (data) {
                            // Header design
                            doc.setFillColor(255, 255, 255);
                            doc.rect(0, 0, pageWidth, headerHeight, 'F');

                            doc.setDrawColor(0, 76, 153);
                            doc.setLineWidth(0.8);
                            doc.rect(5, 5, pageWidth - 10, headerHeight - 5);

                            // Add logo directly without background box
                            if (collegeLogoBase64) {
                                try {
                                    doc.addImage(collegeLogoBase64, 'PNG', 10, 10, 31, 26);
                                } catch (e) { }
                            }

                            // Line separator after logo
                            doc.setDrawColor(200, 200, 200);
                            doc.setLineWidth(0.3);
                            doc.line(10, 38, pageWidth - 10, 38);

                            doc.setTextColor(0, 0, 0);
                            doc.setFontSize(16);
                            doc.setFont('helvetica', 'bold');
                            doc.text('M.Kumarasamy College of Engineering, Karur-639 113', pageWidth / 2, 20, { align: 'center' });

                            doc.setFontSize(10);
                            doc.setFont('helvetica', 'normal');
                            doc.setTextColor(100, 100, 100);
                            doc.text('(An Autonomous Institution Affiliated to Anna University, Chennai)', pageWidth / 2, 27, { align: 'center' });

                            doc.setFillColor(0, 102, 204);
                            doc.rect(8, 42, pageWidth - 16, 10, 'F');
                            doc.setTextColor(255, 255, 255);
                            doc.setFontSize(13);
                            doc.setFont('helvetica', 'bold');
                            doc.text('FACULTY EVALUATION REPORT', pageWidth / 2, 48.5, { align: 'center' });

                            // Metadata - top right
                            doc.setTextColor(100, 100, 100);
                            doc.setFontSize(8);
                            doc.setFont('helvetica', 'normal');
                            doc.text(`Report Date: ${new Date().toLocaleDateString()}`, pageWidth - 10, 10, { align: 'right' });

                            doc.text(`Department: ${rptUserDept}`, pageWidth - 10, 18, { align: 'right' });
                            // Page number at bottom
                            doc.setFontSize(8);
                            const pageStr = 'Page ' + doc.internal.getNumberOfPages();
                            doc.text(pageStr, pageWidth / 2, pageHeight - 5, { align: 'center' });
                        }
                    });

                    // Add task details if available
                    const taskTable = $('#rptFacTaskTable').DataTable();
                    const taskData = taskTable.rows().data().toArray();

                    if (taskData.length > 0) {
                        // Add subtitle before task table
                        const startY = doc.lastAutoTable.finalY + 15;
                        doc.setFontSize(11);
                        doc.setFont('helvetica', 'bold');
                        doc.setTextColor(0, 0, 0);
                        doc.text('Task Details:', 14, startY);

                        doc.autoTable({
                            head: [['Task ID', 'Title', 'Assigned By', 'Assigned To', 'Assigned', 'Due', 'Completed', 'Late', 'Priority', 'Status']],
                            body: taskData.map(row => [
                                row.task_id, row.title.substring(0, 30), row.assigned_by, row.assigned_to,
                                row.assigned_date, row.due_date, row.completed_on || '-',
                                row.days_late, row.priority.substring(0, 20), row.status
                            ]),
                            startY: startY + 5,
                            theme: 'grid',
                            headStyles: {
                                fillColor: [102, 126, 234],
                                textColor: [255, 255, 255],
                                fontStyle: 'bold'
                            },
                            styles: { fontSize: 7 },
                            didDrawPage: function (data) {
                                // Page number at bottom
                                doc.setFontSize(8);
                                doc.setTextColor(100, 100, 100);
                                const pageStr = 'Page ' + doc.internal.getNumberOfPages();
                                doc.text(pageStr, pageWidth / 2, pageHeight - 5, { align: 'center' });
                            }
                        });
                    }

                    doc.save('faculty_evaluation_report.pdf');

                } catch (error) {
                    console.error('Error generating PDF:', error);
                    alert('Error generating PDF.');
                }
            });
        }

        function rptExportFacultyExcel() {
            const facTable = $('#rptFacListTable').DataTable();
            const facData = facTable.rows().data().toArray();

            const taskTable = $('#rptFacTaskTable').DataTable();
            const taskData = taskTable.rows().data().toArray();

            const rptUserRole = window.rptUserRole || 'Unknown';
            const deptFilter = $('#rptFacDept').val() || 'All';

            const wb = XLSX.utils.book_new();

            // Sheet 1: Faculty Summary
            const ws1_data = [
                ['M.Kumarasamy College of Engineering, Karur-639 113'],
                ['(An Autonomous Institution Affiliated to Anna University, Chennai)'],
                [''],
                ['FACULTY EVALUATION REPORT'],
                [''],
                [`Report Date: ${new Date().toLocaleDateString()}`],
                [''],
                ['Faculty Performance Summary'],
                ['Faculty Name', 'Total Tasks', 'Completed', 'Pending', 'Overdue', 'Completion %', 'On-Time %', 'Credit Score', 'Demerit Score'],
                ...facData.map(row => [
                    row.faculty_name, row.total_tasks, row.completed, row.pending,
                    row.overdue, row.completion_percent, row.ontime_percent,
                    row.credit_score, row.demerit_score
                ])
            ];

            const ws1 = XLSX.utils.aoa_to_sheet(ws1_data);

            ws1['!merges'] = [
                { s: { r: 0, c: 0 }, e: { r: 0, c: 8 } },
                { s: { r: 1, c: 0 }, e: { r: 1, c: 8 } },
                { s: { r: 3, c: 0 }, e: { r: 3, c: 8 } },
                { s: { r: 7, c: 0 }, e: { r: 7, c: 8 } }
            ];

            ws1['!cols'] = [
                { wch: 25 }, { wch: 12 }, { wch: 12 }, { wch: 10 },
                { wch: 10 }, { wch: 13 }, { wch: 13 }, { wch: 12 }, { wch: 12 }
            ];

            XLSX.utils.book_append_sheet(wb, ws1, 'Faculty Summary');

            // Sheet 2: Task Details
            const ws2_data = [
                ['Task Details'],
                [''],
                ['Task ID', 'Title', 'Assigned By', 'Assigned To', 'Assigned Date', 'Due Date', 'Completed On', 'Days Late', 'Priority', 'Status'],
                ...taskData.map(row => [
                    row.task_id, row.title, row.assigned_by, row.assigned_to, row.assigned_date,
                    row.due_date, row.completed_on || '-', row.days_late,
                    row.priority, row.status
                ])
            ];

            const ws2 = XLSX.utils.aoa_to_sheet(ws2_data);

            ws2['!merges'] = [
                { s: { r: 0, c: 0 }, e: { r: 0, c: 9 } }
            ];

            ws2['!cols'] = [
                { wch: 10 }, { wch: 30 }, { wch: 20 }, { wch: 20 },
                { wch: 15 }, { wch: 15 }, { wch: 15 }, { wch: 10 },
                { wch: 15 }, { wch: 12 }
            ];

            XLSX.utils.book_append_sheet(wb, ws2, 'Task Details');

            XLSX.writeFile(wb, 'faculty_evaluation_report.xlsx');
        }

        //Meting PDF exporting
        function rptExportMeetingPDF() {
            if (typeof window.jspdf === 'undefined') {
                alert('jsPDF library is not loaded.');
                return;
            }

            const rptUserRole = window.rptUserRole || 'Unknown';
            const deptFilter = $('#rptFacDept').val() || 'All';

            loadImageAsBase64('image/mkce_logo2.png', function (collegeLogoBase64) {
                try {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF('landscape');
                    const pageWidth = doc.internal.pageSize.getWidth();
                    const pageHeight = doc.internal.pageSize.getHeight();

                    const facTable = $('#rptScheduledMeetingsTable').DataTable();
                    const facData = facTable.rows().data().toArray();

                    const headerHeight = 55;

                    // Add subtitle before first table
                    doc.setFontSize(11);
                    doc.setFont('helvetica', 'bold');
                    doc.setTextColor(0, 0, 0);
                    doc.text('Meetings Summary:', 14, headerHeight + 8);

                    doc.autoTable({
                        head: [['Title', 'Agenda', 'Date & Time', 'Location/Mode', 'Participants', 'Organised By', 'Status']],
                        body: facData.map(row => [
                            row.title, row.agenda, row.datetime, row.location,
                            row.participants, row.organised_by
                        ]),
                        startY: headerHeight + 13,
                        theme: 'grid',
                        headStyles: {
                            fillColor: [102, 126, 234],
                            textColor: [255, 255, 255],
                            fontStyle: 'bold'
                        },
                        didDrawPage: function (data) {
                            // Header design
                            doc.setFillColor(255, 255, 255);
                            doc.rect(0, 0, pageWidth, headerHeight, 'F');

                            doc.setDrawColor(0, 76, 153);
                            doc.setLineWidth(0.8);
                            doc.rect(5, 5, pageWidth - 10, headerHeight - 5);

                            // Add logo directly without background box
                            if (collegeLogoBase64) {
                                try {
                                    doc.addImage(collegeLogoBase64, 'PNG', 10, 10, 31, 26);
                                } catch (e) { }
                            }

                            // Line separator after logo
                            doc.setDrawColor(200, 200, 200);
                            doc.setLineWidth(0.3);
                            doc.line(10, 38, pageWidth - 10, 38);

                            doc.setTextColor(0, 0, 0);
                            doc.setFontSize(16);
                            doc.setFont('helvetica', 'bold');
                            doc.text('M.Kumarasamy College of Engineering, Karur-639 113', pageWidth / 2, 20, { align: 'center' });

                            doc.setFontSize(10);
                            doc.setFont('helvetica', 'normal');
                            doc.setTextColor(100, 100, 100);
                            doc.text('(An Autonomous Institution Affiliated to Anna University, Chennai)', pageWidth / 2, 27, { align: 'center' });

                            doc.setFillColor(0, 102, 204);
                            doc.rect(8, 42, pageWidth - 16, 10, 'F');
                            doc.setTextColor(255, 255, 255);
                            doc.setFontSize(13);
                            doc.setFont('helvetica', 'bold');
                            doc.text('MEETINGS REPORT', pageWidth / 2, 48.5, { align: 'center' });

                            // Metadata - top right
                            doc.setTextColor(100, 100, 100);
                            doc.setFontSize(8);
                            doc.setFont('helvetica', 'normal');
                            doc.text(`Report Date: ${new Date().toLocaleDateString()}`, pageWidth - 10, 10, { align: 'right' });

                            doc.text(`Department: ${rptUserDept}`, pageWidth - 10, 18, { align: 'right' });
                            // Page number at bottom
                            doc.setFontSize(8);
                            const pageStr = 'Page ' + doc.internal.getNumberOfPages();
                            doc.text(pageStr, pageWidth / 2, pageHeight - 5, { align: 'center' });
                        }
                    });

                    // Add task details if available
                    const taskTable = $('#rptRequestedMeetingsTable').DataTable();
                    const taskData = taskTable.rows().data().toArray();

                    if (taskData.length > 0) {
                        // Add subtitle before task table
                        const startY = doc.lastAutoTable.finalY + 15;
                        doc.setFontSize(11);
                        doc.setFont('helvetica', 'bold');
                        doc.setTextColor(0, 0, 0);
                        doc.text('Requested Meetings:', 14, startY);

                        doc.autoTable({
                            head: [['Requester', 'Department', 'Role', 'Purpose', 'Requested Time', 'Status']],
                            body: taskData.map(row => [
                                row.requester, row.department, row.role, row.purpose,
                                row.requested_time, row.status
                            ]),
                            startY: startY + 5,
                            theme: 'grid',
                            headStyles: {
                                fillColor: [102, 126, 234],
                                textColor: [255, 255, 255],
                                fontStyle: 'bold'
                            },
                            styles: { fontSize: 7 },
                            didDrawPage: function (data) {
                                // Page number at bottom
                                doc.setFontSize(8);
                                doc.setTextColor(100, 100, 100);
                                const pageStr = 'Page ' + doc.internal.getNumberOfPages();
                                doc.text(pageStr, pageWidth / 2, pageHeight - 5, { align: 'center' });
                            }
                        });
                    }

                    doc.save('meeting_report.pdf');

                } catch (error) {
                    console.error('Error generating PDF:', error);
                    alert('Error generating PDF.');
                }
            });
            // Use tables: #rptScheduledMeetingsTable and #rptRequestedMeetingsTable
        }

      function rptDisplayTopPerformers(data, isDepartment) {
    const container = $('#rptTopPerformersGrid');
    container.empty();

    if (!data || data.length === 0) {
        $('#rptTopPerformersSection').hide();
        return;
    }

    $('#rptTopPerformersSection').show();

    // Sort by completion percentage and take top 3
    const sortKey = isDepartment ? 'avg_completion' : 'completion_percent';
    const topThree = data
        .sort((a, b) => (b[sortKey] || 0) - (a[sortKey] || 0))
        .slice(0, 3);

    topThree.forEach((item, index) => {
        const rank = index + 1;
        const name = isDepartment ? item.department : item.faculty_name;
        const initial = name ? name.charAt(0).toUpperCase() : '?';
        const score = isDepartment ? (item.avg_completion || 0) : (item.completion_percent || 0);
        
        const card = `
            <div class="rpt-performer-card">
                <div class="rpt-rank-badge rpt-rank-${rank}">${rank}</div>
                <div class="rpt-performer-avatar">${initial}</div>
                <div class="rpt-performer-name">${name}</div>
                <div class="rpt-performer-score">${score}%</div>
                <div class="rpt-performer-label">Completion Rate</div>
            </div>
        `;
        container.append(card);
    });
}


    </script>