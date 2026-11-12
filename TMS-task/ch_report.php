<style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .ch-main-container {
            max-width: 1600px;
            margin: 20px auto;
            padding: 30px;
        }

        .ch-page-header {
            margin-bottom: 40px;
            text-align: center;
        }

        .ch-page-title {
            font-size: 42px;
            font-weight: 700;
            background: linear-gradient(135deg, #080241, #1800ce, #3527BB);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .ch-page-subtitle {
            color: #6c757d;
            font-size: 16px;
        }

        /* Executive Cards */
        .ch-executive-cards {
            display: grid;
            grid-template-columns: 300px 300px 300px;
            gap: 30px;
            margin-bottom: 50px;
        }

        .ch-executive-card {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            padding: 35px;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 3px solid transparent;
            color: white;
        }

        .ch-executive-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .ch-executive-card.active {
            border-color: rgb(168, 167, 157);
            box-shadow: 0 20px 50px rgba(70, 69, 67, 0.4);
        }

        .ch-executive-card.principal {
            background: linear-gradient(135deg, rgba(243, 22, 51, 0.94), rgb(207, 51, 90));
        }

        .ch-executive-card.chro {
            background: linear-gradient(135deg, rgba(1, 139, 8, 0.93), rgb(86, 158, 102));
        }

        .ch-executive-card.ed {
            background: linear-gradient(135deg, #3527BB, rgb(82, 125, 219));
        }

        .ch-executive-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .ch-executive-role {
            background: rgba(255, 255, 255, 0.25);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 15px;
        }

        .ch-executive-name {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        /* Department Carousel */
        .ch-carousel-section {
            margin: 50px 0;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .ch-carousel-header {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ch-carousel-container {
            position: relative;
            overflow-x: auto;
            overflow-y: hidden;
            padding: 10px 0;
            cursor: grab;
            /* Custom scrollbar styling */
            scrollbar-width: thin;
            scrollbar-color: #5f52db #e9ecef;
        }

        .ch-carousel-container:active {
            cursor: grabbing;
        }

        .ch-carousel-container::-webkit-scrollbar {
            height: 8px;
        }

        .ch-carousel-container::-webkit-scrollbar-track {
            background: #e9ecef;
            border-radius: 10px;
        }

        .ch-carousel-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #5f52db, #9388f0);
            border-radius: 10px;
        }

        .ch-carousel-container::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #9388f0, #5f52db);
        }

        .ch-carousel-wrapper {
            display: flex;
            gap: 20px;
            width: max-content;
            padding: 5px;
        }

        .ch-dept-card {
            flex: 0 0 250px;
            color: white;
            padding: 25px;
            border-radius: 15px;
            cursor: pointer;
            text-align: center;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            user-select: none;
        }

        /* Department Colors – 20 Departments */
        .ch-dept-card:nth-child(1) {
            background: linear-gradient(135deg, #5f52db, #9388f0);
            box-shadow: 0 5px 15px rgba(95, 82, 219, 0.3);
        }

        .ch-dept-card:nth-child(2) {
            background: linear-gradient(135deg, #FF6B6B, #FF8E53);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }

        .ch-dept-card:nth-child(3) {
            background: linear-gradient(135deg, #4CAF50, #66BB6A);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .ch-dept-card:nth-child(4) {
            background: linear-gradient(135deg, #2196F3, #42A5F5);
            box-shadow: 0 5px 15px rgba(33, 150, 243, 0.3);
        }

        .ch-dept-card:nth-child(5) {
            background: linear-gradient(135deg, #FF9800, #FFB74D);
            box-shadow: 0 5px 15px rgba(255, 152, 0, 0.3);
        }

        .ch-dept-card:nth-child(6) {
            background: linear-gradient(135deg, #9C27B0, #BA68C8);
            box-shadow: 0 5px 15px rgba(156, 39, 176, 0.3);
        }

        .ch-dept-card:nth-child(7) {
            background: linear-gradient(135deg, #00BCD4, #26C6DA);
            box-shadow: 0 5px 15px rgba(0, 188, 212, 0.3);
        }

        .ch-dept-card:nth-child(8) {
            background: linear-gradient(135deg, #E91E63, #F06292);
            box-shadow: 0 5px 15px rgba(233, 30, 99, 0.3);
        }

        .ch-dept-card:nth-child(9) {
            background: linear-gradient(135deg, #795548, #A1887F);
            box-shadow: 0 5px 15px rgba(121, 85, 72, 0.3);
        }

        .ch-dept-card:nth-child(10) {
            background: linear-gradient(135deg, #607D8B, #90A4AE);
            box-shadow: 0 5px 15px rgba(96, 125, 139, 0.3);
        }

        .ch-dept-card:nth-child(11) {
            background: linear-gradient(135deg, #3F51B5, #7986CB);
            box-shadow: 0 5px 15px rgba(63, 81, 181, 0.3);
        }

        .ch-dept-card:nth-child(12) {
            background: linear-gradient(135deg, #009688, #4DB6AC);
            box-shadow: 0 5px 15px rgba(0, 150, 136, 0.3);
        }

        .ch-dept-card:nth-child(13) {
            background: linear-gradient(135deg, #673AB7, #9575CD);
            box-shadow: 0 5px 15px rgba(103, 58, 183, 0.3);
        }

        .ch-dept-card:nth-child(14) {
            background: linear-gradient(135deg, #F44336, #EF5350);
            box-shadow: 0 5px 15px rgba(244, 67, 54, 0.3);
        }

        .ch-dept-card:nth-child(15) {
            background: linear-gradient(135deg, rgb(224, 87, 201), rgb(207, 71, 173));
            box-shadow: 0 5px 15px rgba(205, 220, 57, 0.3);
        }

        .ch-dept-card:nth-child(16) {
            background: linear-gradient(135deg, #03A9F4, #4FC3F7);
            box-shadow: 0 5px 15px rgba(3, 169, 244, 0.3);
        }

        .ch-dept-card:nth-child(17) {
            background: linear-gradient(135deg, #8BC34A, #AED581);
            box-shadow: 0 5px 15px rgba(139, 195, 74, 0.3);
        }

        .ch-dept-card:nth-child(18) {
            background: linear-gradient(135deg, #FF5722, #FF8A65);
            box-shadow: 0 5px 15px rgba(255, 87, 34, 0.3);
        }

        .ch-dept-card:nth-child(19) {
            background: linear-gradient(135deg, #B71C1C, #E57373);
            box-shadow: 0 5px 15px rgba(183, 28, 28, 0.3);
        }

        .ch-dept-card:nth-child(20) {
            background: linear-gradient(135deg, #1A237E, #5C6BC0);
            box-shadow: 0 5px 15px rgba(26, 35, 126, 0.3);
        }

        .ch-dept-card:hover {
            transform: translateY(-5px);
            filter: brightness(1.1);
        }

        .ch-dept-card.active {
            border: 3px solidrgb(114, 113, 110);
            box-shadow: 0 10px 30px rgba(80, 79, 75, 0.5);
        }

        /* KPI Cards */
        .ch-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .ch-kpi-card {
            background: linear-gradient(135deg, #5f52db 0%, #9388f0 100%);
            /* border-radius: 15px; */
            padding: 25px;
            color: white;
            box-shadow: 0 5px 20px rgba(95, 82, 219, 0.3);
            transition: transform 0.3s ease;
        }

        .ch-kpi-card:hover {
            transform: translateY(-5px);
        }

        .ch-kpi-card.success {
            background: linear-gradient(135deg, #27c490, #6dd5a9);
        }

        .ch-kpi-card.warning {
            background: linear-gradient(135deg, #c77619, #e49433);
        }

        .ch-kpi-card.info {
            background: linear-gradient(135deg, #7c3f9e, #a065c4);
        }

        .ch-kpi-card.danger {
            background: linear-gradient(135deg, #ff5544, #ff8866);
        }

        .ch-kpi-title {
            font-size: 14px;
            opacity: 0.95;
            margin-bottom: 10px;
        }

        .ch-kpi-value {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        /* Report Sections */
        .ch-report-section {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .ch-section-title {
            font-size: 22px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Table Styling */
        .ch-table thead {
            --bs-table-bg: transparent;
            --bs-table-color: white;
            background: linear-gradient(135deg, #4CAF50, #2196F3) !important;
            color: inherit;
        }

        .ch-table thead th {
            color: white !important;
            font-weight: 600 !important;
            border: 1px solid white !important;
            text-align: center;
            font-size: 0.9em;
            padding: 15px !important;
        }

        .ch-table tbody td {
            padding: 12px;
            vertical-align: middle;
            text-align: center;
        }

        .ch-status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .ch-status-completed {
            background: #d4edda;
            color: #155724;
        }

        .ch-status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .ch-status-overdue {
            background: #f8d7da;
            color: #721c24;
        }

        .ch-status-inprogress {
            background: #d1ecf1;
            color: #0c5460;
        }

        .ch-priority-high {
            color: #dc3545;
            font-weight: 700;
        }

        .ch-priority-medium {
            color: #fd7e14;
            font-weight: 600;
        }

        .ch-priority-low {
            color: #28a745;
            font-weight: 500;
        }

        .ch-export-btn {
            background: linear-gradient(135deg, #5f52db, #9388f0);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(95, 82, 219, 0.3);
        }

        .ch-export-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(95, 82, 219, 0.5);
        }

        .hidden {
            display: none;
        }
    </style>
    <div class="ch-main-container">

        <!-- Executive Cards -->
        <div class="ch-executive-cards" id="chExecutiveCards">
            <div class="ch-executive-card ed" data-exec="ED" data-name="ED">
                <div class="ch-executive-icon"><i class="fas fa-user-graduate"></i></div>
                <div class="ch-executive-role">Executive Director</div>
                <h3 class="ch-executive-name">Dr.S.Kuppusamy</h3>
                <p style="opacity: 0.9; margin: 0;">Click to view details</p>
            </div>

            <div class="ch-executive-card chro" data-exec="CHRO" data-name="CHRO">
                <div class="ch-executive-icon"><i class="fas fa-users-cog"></i></div>
                <div class="ch-executive-role">Chief HR Officer</div>
                <h3 class="ch-executive-name">Dr. Mohan Parthasarathy</h3>
                <p style="opacity: 0.9; margin: 0;">Click to view details</p>
            </div>
            <div class="ch-executive-card principal" data-exec="PRINCIPAL" data-name="Principal">
                <div class="ch-executive-icon"><i class="fas fa-user-tie"></i></div>
                <div class="ch-executive-role">Principal</div>
                <h3 class="ch-executive-name">Dr. B.S. Murugan</h3>
                <p style="opacity: 0.9; margin: 0;">Click to view details</p>
            </div>
        </div>

        <!-- Executive Report Section -->
        <div id="chExecutiveReportSection" class="hidden">
            <h2 class="ch-section-title" id="chExecutiveReportTitle">
                <i class="fas fa-chart-line"></i> Executive Report
            </h2>

            <!-- KPIs -->
            <div class="ch-kpi-grid" id="chExecutiveKPIs"></div>

            <!-- Tasks Table -->
            <div class="ch-report-section">
                <h3 class="ch-section-title">
                    <i class="fas fa-list-check"></i> Task Details
                </h3>
                <div class="table-responsive">
                    <table id="chExecutiveTasksTable" class="table table-striped table-bordered ch-table"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Task ID</th>
                                <th>Title</th>
                                <th>Start Date</th>
                                <th>Deadline</th>
                                <th>Completed</th>
                                <th>Priority</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <button class="ch-export-btn mt-3" onclick="chExportExecutivePDF()">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
            </div>
        </div>

        <!-- Department Carousel -->
        <div class="ch-carousel-section">
            <h2 class="ch-carousel-header">
                <i class="fas fa-building"></i> Department Performance
            </h2>
            <div class="ch-carousel-container" id="chCarouselContainer">
                <div class="ch-carousel-wrapper" id="chCarouselWrapper"></div>
            </div>
        </div>

        <!-- Department Report Section -->
        <div id="chDepartmentReportSection">
            <h2 class="ch-section-title" id="chDepartmentReportTitle">
                <i class="fas fa-building-columns"></i> Department Report
            </h2>

            <!-- Filters -->
            <div class="ch-report-section">
                <h5><i class="fas fa-filter"></i> Filters</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Academic Year</label>
                        <select id="chYear" class="form-select">
                            <option value="">Loading...</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Period Type</label>
                        <select id="chPeriodType" class="form-select">
                            <option value="all">All Time</option>
                            <option value="month">Monthly</option>
                            <option value="week">Weekly</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Month</label>
                        <select id="chMonth" class="form-select" disabled>
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
                        <select id="chWeek" class="form-select" disabled>
                            <option value="">Select Week</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-primary w-100" onclick="chApplyFilters()">
                            <i class="fas fa-check"></i> Apply Filters
                        </button>
                    </div>
                </div>
                <div class="row g-3 mt-2" id="chDateRange" style="display:none;">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Start Date</label>
                        <input type="date" id="chStartDate" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">End Date</label>
                        <input type="date" id="chEndDate" class="form-control">
                    </div>
                </div>
            </div>


            <!-- Department KPIs -->
            <div class="ch-kpi-grid" id="chDepartmentKPIs"></div>

            <!-- Faculty Summary -->
            <div class="ch-report-section">
                <h3 class="ch-section-title">
                    <i class="fas fa-users"></i> Faculty Performance
                </h3>
                <div class="table-responsive">
                    <table id="chFacultyTable" class="table table-striped table-bordered ch-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Faculty Name</th>
                                <th>Total Tasks</th>
                                <th>Completed</th>
                                <th>Pending</th>
                                <th>Overdue</th>
                                <th>Completion %</th>
                                <th>Credit Score</th>
                                <th>Demerit Score</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- Department Tasks -->
            <div class="ch-report-section">
                <h3 class="ch-section-title">
                    <i class="fas fa-tasks"></i> Task Summary
                </h3>
                <div class="table-responsive">
                    <table id="chDepartmentTasksTable" class="table table-striped table-bordered ch-table"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Task ID</th>
                                <th>Title</th>
                                <th>Assigned To</th>
                                <th>Start Date</th>
                                <th>Deadline</th>
                                <th>Priority</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <button class="ch-export-btn mt-3" onclick="chExportDepartmentPDF()">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.1/jspdf.plugin.autotable.min.js"></script>

    <script>
        let chCurrentExecutive = null;
        let chCurrentDepartment = null;
        let chDepartments = [];
        let chExecutiveTasksTable = null;
        let chDepartmentTasksTable = null;
        let chFacultyTable = null;
        let chCurrentAcademicYear = '';

        $(document).ready(function () {
            chLoadAcademicYears();
            chLoadDepartments();
            chSetupExecutiveCards();
            chSetupFilterListeners();

            // Initialize empty tables
            chExecutiveTasksTable = $('#chExecutiveTasksTable').DataTable({
                pageLength: 10,
                order: [[2, 'desc']],
                data: []
            });

            chDepartmentTasksTable = $('#chDepartmentTasksTable').DataTable({
                pageLength: 10,
                order: [[3, 'desc']],
                data: []
            });

            chFacultyTable = $('#chFacultyTable').DataTable({
                pageLength: 10,
                order: [[5, 'desc']],
                data: []
            });

            // Load all departments data by default
            chLoadAllDepartmentsData();
        });

        function chLoadAcademicYears() {
            $.post('backend/backend_combined.php?route=reportbackend', {
                action: 'getAcademicYears'
            }, function (response) {
                if (response.status === 'success') {
                    chCurrentAcademicYear = response.currentAcademicYear;

                    let options = '';
                    response.academicYears.forEach(year => {
                        const selected = year.isCurrent ? ' selected' : '';
                        const currentLabel = year.isCurrent ? ' (Current)' : '';
                        options += `<option value="${year.value}"${selected}>${year.label}${currentLabel}</option>`;
                    });

                    $('#chYear').html(options);
                    chGenerateWeekOptions();
                }
            }, 'json');
        }

        function chGenerateWeekOptions() {
            const year = $('#chYear').val();
            if (!year) return;

            const yearParts = year.split('-');
            const startYear = parseInt(yearParts[0]);

            let weekOptions = '<option value="">Select Week</option>';

            // Generate weeks from August of start year to July of next year
            const academicStart = new Date(startYear, 7, 1); // August 1st
            const academicEnd = new Date(parseInt(yearParts[1]), 6, 31); // July 31st next year

            let weekNum = 1;
            let currentDate = new Date(academicStart);

            // Find first Saturday
            while (currentDate.getDay() !== 6) {
                currentDate.setDate(currentDate.getDate() + 1);
            }

            while (currentDate <= academicEnd) {
                const weekEnd = new Date(currentDate);
                weekEnd.setDate(weekEnd.getDate() + 6);

                if (weekEnd > academicEnd) break;

                weekOptions += `<option value="${weekNum}" data-start="${chFormatDate(currentDate)}" data-end="${chFormatDate(weekEnd)}">
                Week ${weekNum} (${chFormatDate(currentDate)} to ${chFormatDate(weekEnd)})
            </option>`;

                currentDate.setDate(currentDate.getDate() + 7);
                weekNum++;
            }

            $('#chWeek').html(weekOptions);
        }

        function chFormatDate(date) {
            const d = new Date(date);
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function chSetupFilterListeners() {
            $('#chPeriodType').change(function () {
                const type = $(this).val();

                $('#chMonth, #chWeek, #chStartDate, #chEndDate').prop('disabled', true);
                $('#chDateRange').hide();

                if (type === 'month') {
                    $('#chMonth').prop('disabled', false);
                } else if (type === 'week') {
                    $('#chWeek').prop('disabled', false);
                } else if (type === 'custom') {
                    $('#chStartDate, #chEndDate').prop('disabled', false);
                    $('#chDateRange').show();
                }
            });

            $('#chYear').change(function () {
                chGenerateWeekOptions();
            });
        }

        function chApplyFilters() {
            if (chCurrentDepartment) {
                chSelectDepartment(chCurrentDepartment);
            } else {
                // Load all departments data
                chLoadAllDepartmentsData();
            }
        }

        function chGetFilterData() {
            const filters = {
                year: $('#chYear').val() || chCurrentAcademicYear,
                periodType: $('#chPeriodType').val()
            };

            if (filters.periodType === 'month') {
                filters.month = $('#chMonth').val();
            } else if (filters.periodType === 'week') {
                filters.week = $('#chWeek').val();
            } else if (filters.periodType === 'custom') {
                filters.startDate = $('#chStartDate').val();
                filters.endDate = $('#chEndDate').val();
            }

            return filters;
        }

        function chLoadAllDepartmentsData() {
            const filters = chGetFilterData();

            // Clear department-specific KPIs when showing all departments
            $('#chDepartmentKPIs').html('');
            $('#chDepartmentReportTitle').html(`<i class="fas fa-building-columns"></i> All Departments Report`);

            $.post('backend/backend_combined.php?route=reportbackend', {
                action: 'getFacultyPerformance',
                ...filters
            }, function (response) {
                if (response.status === 'success') {
                    chDisplayFacultyTable(response.facultyList);
                }
            }, 'json');

            $.post('backend/backend_combined.php?route=reportbackend', {
                action: 'getFacultyTasks',
                ...filters
            }, function (response) {
                if (response.status === 'success') {
                    chDisplayDepartmentTasks(response.tasks);
                }
            }, 'json');
        }

        function chLoadDepartments() {
            $.post('backend/backend_combined.php?route=reportbackend', {
                action: 'getDepartments'
            }, function (response) {
                if (response.status === 'success') {
                    chDepartments = response.departments;

                    const wrapper = $('#chCarouselWrapper');
                    chDepartments.forEach(dept => {
                        wrapper.append(`
                        <div class="ch-dept-card" onclick="chSelectDepartment('${dept}')">
                            <i class="fas fa-building mb-2" style="font-size: 24px;"></i>
                            <div>${dept}</div>
                        </div>
                    `);
                    });

                    // Enable smooth horizontal scrolling with mouse wheel and drag
                    chEnableScrollFeatures();
                }
            }, 'json');
        }

        function chEnableScrollFeatures() {
            const container = document.getElementById('chCarouselContainer');

            // Mouse wheel horizontal scroll
            container.addEventListener('wheel', function (e) {
                if (e.deltaY !== 0) {
                    e.preventDefault();
                    container.scrollLeft += e.deltaY;
                }
            });

            // Drag to scroll
            let isDown = false;
            let startX;
            let scrollLeft;

            container.addEventListener('mousedown', (e) => {
                isDown = true;
                container.style.cursor = 'grabbing';
                startX = e.pageX - container.offsetLeft;
                scrollLeft = container.scrollLeft;
            });

            container.addEventListener('mouseleave', () => {
                isDown = false;
                container.style.cursor = 'grab';
            });

            container.addEventListener('mouseup', () => {
                isDown = false;
                container.style.cursor = 'grab';
            });

            container.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - container.offsetLeft;
                const walk = (x - startX) * 2; // Scroll speed multiplier
                container.scrollLeft = scrollLeft - walk;
            });
        }
        document.addEventListener("click", function (event) {
    // These must match your container IDs
    const deptSection = document.getElementById("chDepartmentReportSection");
    const carousel = document.getElementById("chCarouselContainer");

    // Do nothing if user clicks INSIDE either component
    const clickInsideDept = deptSection && deptSection.contains(event.target);
    const clickInsideCarousel = carousel && carousel.contains(event.target);

    if (clickInsideDept || clickInsideCarousel) {
        return;
    }

    // If a department is currently selected → clear it
    if (chCurrentDepartment !== null) {
        chCurrentDepartment = null;

        // Remove active highlight from department cards
        $('.ch-dept-card').removeClass('active');

        // Reload full department data
        chLoadAllDepartmentsData();
    }
});


        function chSetupExecutiveCards() {
            $('.ch-executive-card').click(function () {
                $('.ch-executive-card').removeClass('active');
                $(this).addClass('active');

                const execRole = $(this).data('exec');
                const execName = $(this).data('name');

                chLoadExecutiveReport(execRole, execName);
            });
        }

        function chLoadExecutiveReport(role, name) {
    chCurrentExecutive = { role, name };
    chCurrentDepartment = null;

    $('#chExecutiveReportSection').removeClass('hidden');
    $('#chExecutiveReportTitle').html(`<i class="fas fa-chart-line"></i> ${name} Performance Report`);

    $('.ch-dept-card').removeClass('active');

    // Show loading indicator
    $('#chExecutiveKPIs').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x"></i><p>Loading...</p></div>');
    $('#chExecutiveTasksTable tbody').html('');

    $.ajax({
        url: 'backend/backend_combined.php?route=reportbackend',
        type: 'POST',
        data: {
            action: 'getExecutiveSummary',
            executive: role
        },
        dataType: 'json',
        success: function (response) {
            console.log('Response:', response); // Debug log
            if (response.status === 'success') {
                chDisplayExecutiveKPIs(response.kpis);
                chDisplayExecutiveTasks(response.tasks);
            } else {
                alert('Error: ' + (response.message || 'Unknown error'));
                $('#chExecutiveKPIs').html('<div class="alert alert-danger">' + response.message + '</div>');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', {
                status: status,
                error: error,
                responseText: xhr.responseText
            });
            alert('Failed to load executive report. Check console for details.');
            $('#chExecutiveKPIs').html('<div class="alert alert-danger">Failed to load data</div>');
        }
    });
}
        function chDisplayExecutiveKPIs(kpis) {
            const kpiHTML = `
            <div class="ch-kpi-card">
                <div class="ch-kpi-title">Total Tasks</div>
                <div class="ch-kpi-value">${kpis.total_tasks || 0}</div>
            </div>
            <div class="ch-kpi-card success">
                <div class="ch-kpi-title">Completed</div>
                <div class="ch-kpi-value">${kpis.completed || 0}</div>
            </div>
            <div class="ch-kpi-card warning">
                <div class="ch-kpi-title">Pending</div>
                <div class="ch-kpi-value">${kpis.pending || 0}</div>
            </div>
            <div class="ch-kpi-card danger">
                <div class="ch-kpi-title">Overdue</div>
                <div class="ch-kpi-value">${kpis.overdue || 0}</div>
            </div>
            <div class="ch-kpi-card info">
                <div class="ch-kpi-title">Completion Rate</div>
                <div class="ch-kpi-value">${kpis.completion_rate || 0}%</div>
            </div>
            <div class="ch-kpi-card success">
                <div class="ch-kpi-title">On-Time %</div>
                <div class="ch-kpi-value">${kpis.ontime_percent || 0}%</div>
            </div>
        `;
            $('#chExecutiveKPIs').html(kpiHTML);
        }

        function chDisplayExecutiveTasks(tasks) {
            chExecutiveTasksTable.clear();
            tasks.forEach(task => {
                const priorityClass = task.priority && task.priority.includes('Immediate') ? 'ch-priority-high' :
                    task.priority && task.priority.includes('Important') ? 'ch-priority-medium' : 'ch-priority-low';
                const statusClass = task.status === 'Completed' ? 'ch-status-completed' :
                    task.status === 'Pending' ? 'ch-status-pending' :
                        task.status === 'Overdue' ? 'ch-status-overdue' : 'ch-status-inprogress';

                chExecutiveTasksTable.row.add([
                    task.task_id,
                    task.title,
                    task.start_date,
                    task.deadline,
                    task.completed_date || '-',
                    `<span class="${priorityClass}">${task.priority || 'Normal'}</span>`,
                    `<span class="ch-status-badge ${statusClass}">${task.status || 'Pending'}</span>`
                ]);
            });
            chExecutiveTasksTable.draw();
        }

        function chSelectDepartment(dept) {
            chCurrentDepartment = dept;
            chCurrentExecutive = null;

            $('.ch-dept-card').removeClass('active');
            $('.ch-dept-card').each(function () {
                if ($(this).text().includes(dept)) {
                    $(this).addClass('active');
                }
            });
            $('.ch-executive-card').removeClass('active');

            $('#chExecutiveReportSection').addClass('hidden');
            // $('#chDepartmentReportSection').removeClass('hidden');
            $('#chDepartmentReportTitle').html(`<i class="fas fa-building-columns"></i> ${dept} Department Report`);

            const filters = chGetFilterData();
            filters.department = dept;

            $.post('backend/backend_combined.php?route=reportbackend', {
                action: 'getDepartmentTaskSummary',
                ...filters
            }, function (response) {
                if (response.status === 'success' && response.taskSummary.length > 0) {
                    const data = response.taskSummary[0];
                    chDisplayDepartmentKPIs(data);
                }
            }, 'json');

            $.post('backend/backend_combined.php?route=reportbackend', {
                action: 'getFacultyPerformance',
                ...filters
            }, function (response) {
                if (response.status === 'success') {
                    chDisplayFacultyTable(response.facultyList);
                }
            }, 'json');

            $.post('backend/backend_combined.php?route=reportbackend', {
                action: 'getFacultyTasks',
                ...filters
            }, function (response) {
                if (response.status === 'success') {
                    chDisplayDepartmentTasks(response.tasks);
                }
            }, 'json');
        }

        function chDisplayDepartmentKPIs(kpis) {
            const kpiHTML = `
            <div class="ch-kpi-card">
                <div class="ch-kpi-title">Total Tasks</div>
                <div class="ch-kpi-value">${kpis.total_tasks || 0}</div>
            </div>
            <div class="ch-kpi-card success">
                <div class="ch-kpi-title">Completed</div>
                <div class="ch-kpi-value">${kpis.completed || 0}</div>
            </div>
            <div class="ch-kpi-card warning">
                <div class="ch-kpi-title">Pending</div>
                <div class="ch-kpi-value">${kpis.pending || 0}</div>
            </div>
            <div class="ch-kpi-card danger">
                <div class="ch-kpi-title">Overdue</div>
                <div class="ch-kpi-value">${kpis.overdue || 0}</div>
            </div>
            <div class="ch-kpi-card info">
                <div class="ch-kpi-title">Completion Rate</div>
                <div class="ch-kpi-value">${kpis.completion_percent || 0}%</div>
            </div>
            <div class="ch-kpi-card success">
                <div class="ch-kpi-title">On-Time %</div>
                <div class="ch-kpi-value">${kpis.ontime_percent || 0}%</div>
            </div>
        `;
            $('#chDepartmentKPIs').html(kpiHTML);
        }

        function chDisplayFacultyTable(faculty) {
            chFacultyTable.clear();
            faculty.forEach(f => {
                chFacultyTable.row.add([
                    f.faculty_name,
                    f.total_tasks,
                    f.completed,
                    f.pending,
                    f.overdue,
                    (f.completion_percent || 0) + '%',
                    f.credit_score,
                    f.demerit_score
                ]);
            });
            chFacultyTable.draw();
        }

        function chDisplayDepartmentTasks(tasks) {
            chDepartmentTasksTable.clear();
            tasks.forEach(task => {
                const priorityClass = task.priority && task.priority.includes('Immediate') ? 'ch-priority-high' :
                    task.priority && task.priority.includes('Important') ? 'ch-priority-medium' : 'ch-priority-low';
                const statusClass = task.status === 'Completed' ? 'ch-status-completed' :
                    task.status === 'Pending' ? 'ch-status-pending' :
                        task.status === 'Overdue' ? 'ch-status-overdue' : 'ch-status-inprogress';

                chDepartmentTasksTable.row.add([
                    task.task_id,
                    task.title,
                    task.assigned_to,
                    task.assigned_date,
                    task.due_date,
                    `<span class="${priorityClass}">${task.priority || 'Normal'}</span>`,
                    `<span class="ch-status-badge ${statusClass}">${task.status || 'Pending'}</span>`
                ]);
            });
            chDepartmentTasksTable.draw();
        }

        function loadImageAsBase64(url, successCallback, errorCallback) {
    const img = new Image();
    img.crossOrigin = 'Anonymous';
    
    img.onload = function() {
        const canvas = document.createElement('canvas');
        canvas.width = img.width;
        canvas.height = img.height;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0);
        try {
            const base64 = canvas.toDataURL('image/png');
            successCallback(base64);
        } catch (e) {
            console.error('Canvas to base64 conversion failed:', e);
            if (errorCallback) errorCallback(e);
        }
    };
    
    img.onerror = function(e) {
        console.error('Image load failed:', e);
        if (errorCallback) errorCallback(e);
    };
    
    img.src = url;
}

function chExportExecutivePDF() {
    if (typeof window.jspdf === 'undefined') {
        alert('PDF library not loaded');
        return;
    }

    if (!chCurrentExecutive) {
        alert('No executive selected');
        return;
    }

    const title = chCurrentExecutive.name || 'Executive';
    const role = chCurrentExecutive.role || '';

    loadImageAsBase64('image/mkce_logo2.png', function (collegeLogoBase64) {
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape');
            const pageWidth = doc.internal.pageSize.getWidth();
            const pageHeight = doc.internal.pageSize.getHeight();
            const headerHeight = 55;

            // Function to draw header on each page
            function drawHeader(doc) {
                // White background for header
                doc.setFillColor(255, 255, 255);
                doc.rect(0, 0, pageWidth, headerHeight, 'F');

                // Border around header
                doc.setDrawColor(0, 76, 153);
                doc.setLineWidth(0.8);
                doc.rect(5, 5, pageWidth - 10, headerHeight - 5);

                // Add logo
                if (collegeLogoBase64) {
                    try {
                        doc.addImage(collegeLogoBase64, 'PNG', 10, 10, 31, 26);
                    } catch (e) {
                        console.error('Logo error:', e);
                    }
                }

                // Line separator
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

                // Blue bar with title
                doc.setFillColor(0, 102, 204);
                doc.rect(8, 42, pageWidth - 16, 10, 'F');
                doc.setTextColor(255, 255, 255);
                doc.setFontSize(13);
                doc.setFont('helvetica', 'bold');
                doc.text(`${title.toUpperCase()} PERFORMANCE REPORT`, pageWidth / 2, 48.5, { align: 'center' });

                // Metadata - top right
                doc.setTextColor(100, 100, 100);
                doc.setFontSize(8);
                doc.setFont('helvetica', 'normal');
                doc.text(`Report Date: ${new Date().toLocaleDateString()}`, pageWidth - 10, 10, { align: 'right' });
                doc.text(`Role: ${title}`, pageWidth - 10, 18, { align: 'right' });
            }

            // Draw initial header
            drawHeader(doc);

            // Get KPI data
            const kpis = [];
            $('#chExecutiveKPIs .ch-kpi-card').each(function () {
                const label = $(this).find('.ch-kpi-title').text();
                const value = $(this).find('.ch-kpi-value').text();
                kpis.push([label, value]);
            });

            // Add subtitle before KPI table
            doc.setFontSize(11);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(0, 0, 0);
            doc.text('Performance Metrics:', 14, headerHeight + 8);

            // Add KPIs table
            doc.autoTable({
                head: [['Metric', 'Value']],
                body: kpis,
                startY: headerHeight + 13,
                theme: 'grid',
                headStyles: {
                    fillColor: [102, 126, 234],
                    textColor: [255, 255, 255],
                    fontStyle: 'bold'
                },
                didDrawPage: function (data) {
                    if (data.pageNumber > 1) {
                        drawHeader(doc);
                    }
                    // Page number
                    doc.setFontSize(8);
                    doc.setTextColor(100, 100, 100);
                    const pageStr = 'Page ' + doc.internal.getNumberOfPages();
                    doc.text(pageStr, pageWidth / 2, pageHeight - 5, { align: 'center' });
                }
            });

            // Add tasks table
            let startY = doc.lastAutoTable.finalY + 15;
            
            // Check if we need a new page
            if (startY > pageHeight - 50) {
                doc.addPage();
                drawHeader(doc);
                startY = headerHeight + 8;
            }
            
            doc.setFontSize(11);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(0, 0, 0);
            doc.text('Task Details:', 14, startY);

            const taskData = chExecutiveTasksTable.rows().data().toArray().map(row => [
                row[0], 
                row[1].substring(0, 40), // Truncate long titles
                row[2], 
                row[3], 
                row[4],
                $(row[5]).text(), 
                $(row[6]).text()
            ]);

            doc.autoTable({
                head: [['Task ID', 'Title', 'Start Date', 'Deadline', 'Completed', 'Priority', 'Status']],
                body: taskData,
                startY: startY + 5,
                theme: 'grid',
                headStyles: {
                    fillColor: [102, 126, 234],
                    textColor: [255, 255, 255],
                    fontStyle: 'bold'
                },
                styles: { fontSize: 8 },
                didDrawPage: function (data) {
                    if (data.pageNumber > 1) {
                        drawHeader(doc);
                    }
                    // Page number
                    doc.setFontSize(8);
                    doc.setTextColor(100, 100, 100);
                    const pageStr = 'Page ' + doc.internal.getNumberOfPages();
                    doc.text(pageStr, pageWidth / 2, pageHeight - 5, { align: 'center' });
                }
            });

            doc.save(`${title.toLowerCase().replace(/\s+/g, '-')}-report.pdf`);

        } catch (error) {
            console.error('Error generating PDF:', error);
            alert('Error generating PDF: ' + error.message);
        }
    }, function (error) {
        console.warn('Failed to load logo, generating PDF without it');
    });
}

function chExportExecutivePDF() {
    if (typeof window.jspdf === 'undefined') {
        alert('PDF library not loaded');
        return;
    }

    if (!chCurrentExecutive) {
        alert('No executive selected');
        return;
    }

    const title = chCurrentExecutive.name || 'Executive';
    const role = chCurrentExecutive.role || '';

    loadImageAsBase64('image/mkce_logo2.png', function (collegeLogoBase64) {
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape');
            const pageWidth = doc.internal.pageSize.getWidth();
            const pageHeight = doc.internal.pageSize.getHeight();
            const headerHeight = 55;

            // Draw header only once at the beginning
            // White background for header
            doc.setFillColor(255, 255, 255);
            doc.rect(0, 0, pageWidth, headerHeight, 'F');

            // Border around header
            doc.setDrawColor(0, 76, 153);
            doc.setLineWidth(0.8);
            doc.rect(5, 5, pageWidth - 10, headerHeight - 5);

            // Add logo
            if (collegeLogoBase64) {
                try {
                    doc.addImage(collegeLogoBase64, 'PNG', 10, 10, 31, 26);
                } catch (e) {
                    console.error('Logo error:', e);
                }
            }

            // Line separator
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

            // Blue bar with title
            doc.setFillColor(0, 102, 204);
            doc.rect(8, 42, pageWidth - 16, 10, 'F');
            doc.setTextColor(255, 255, 255);
            doc.setFontSize(13);
            doc.setFont('helvetica', 'bold');
            doc.text(`${title.toUpperCase()} PERFORMANCE REPORT`, pageWidth / 2, 48.5, { align: 'center' });

            // Metadata - top right
            doc.setTextColor(100, 100, 100);
            doc.setFontSize(8);
            doc.setFont('helvetica', 'normal');
            doc.text(`Report Date: ${new Date().toLocaleDateString()}`, pageWidth - 10, 10, { align: 'right' });
            doc.text(`Role: ${title}`, pageWidth - 10, 18, { align: 'right' });

            // Get KPI data
            const kpis = [];
            $('#chExecutiveKPIs .ch-kpi-card').each(function () {
                const label = $(this).find('.ch-kpi-title').text();
                const value = $(this).find('.ch-kpi-value').text();
                kpis.push([label, value]);
            });

            // Add subtitle before KPI table
            doc.setFontSize(11);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(0, 0, 0);
            doc.text('Performance Metrics:', 14, headerHeight + 8);

            // Add KPIs table
            doc.autoTable({
                head: [['Metric', 'Value']],
                body: kpis,
                startY: headerHeight + 13,
                theme: 'grid',
                headStyles: {
                    fillColor: [102, 126, 234],
                    textColor: [255, 255, 255],
                    fontStyle: 'bold'
                },
                didDrawPage: function (data) {
                    // Page number only
                    doc.setFontSize(8);
                    doc.setTextColor(100, 100, 100);
                    const pageStr = 'Page ' + doc.internal.getNumberOfPages();
                    doc.text(pageStr, pageWidth / 2, pageHeight - 5, { align: 'center' });
                }
            });

            // Add tasks table
            let startY = doc.lastAutoTable.finalY + 15;
            
            doc.setFontSize(11);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(0, 0, 0);
            doc.text('Task Details:', 14, startY);

            const taskData = chExecutiveTasksTable.rows().data().toArray().map(row => [
                row[0], 
                row[1].substring(0, 40), // Truncate long titles
                row[2], 
                row[3], 
                row[4],
                $(row[5]).text(), 
                $(row[6]).text()
            ]);

            doc.autoTable({
                head: [['Task ID', 'Title', 'Start Date', 'Deadline', 'Completed', 'Priority', 'Status']],
                body: taskData,
                startY: startY + 5,
                theme: 'grid',
                headStyles: {
                    fillColor: [102, 126, 234],
                    textColor: [255, 255, 255],
                    fontStyle: 'bold'
                },
                styles: { fontSize: 8 },
                didDrawPage: function (data) {
                    // Page number only
                    doc.setFontSize(8);
                    doc.setTextColor(100, 100, 100);
                    const pageStr = 'Page ' + doc.internal.getNumberOfPages();
                    doc.text(pageStr, pageWidth / 2, pageHeight - 5, { align: 'center' });
                }
            });

            doc.save(`${title.toLowerCase().replace(/\s+/g, '-')}-report.pdf`);

        } catch (error) {
            console.error('Error generating PDF:', error);
            alert('Error generating PDF: ' + error.message);
        }
    }, function (error) {
        console.warn('Failed to load logo, generating PDF without it');
    });
}

function chExportDepartmentPDF() {
    if (typeof window.jspdf === 'undefined') {
        alert('PDF library not loaded');
        return;
    }

    const dept = chCurrentDepartment || 'All Departments';

    loadImageAsBase64('image/mkce_logo2.png', function (collegeLogoBase64) {
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape');
            const pageWidth = doc.internal.pageSize.getWidth();
            const pageHeight = doc.internal.pageSize.getHeight();
            const headerHeight = 55;

            // Draw header only once at the beginning
            // White background for header
            doc.setFillColor(255, 255, 255);
            doc.rect(0, 0, pageWidth, headerHeight, 'F');

            // Border around header
            doc.setDrawColor(0, 76, 153);
            doc.setLineWidth(0.8);
            doc.rect(5, 5, pageWidth - 10, headerHeight - 5);

            // Add logo
            if (collegeLogoBase64) {
                try {
                    doc.addImage(collegeLogoBase64, 'PNG', 10, 10, 31, 26);
                } catch (e) {
                    console.error('Logo error:', e);
                }
            }

            // Line separator
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

            // Blue bar with title
            doc.setFillColor(0, 102, 204);
            doc.rect(8, 42, pageWidth - 16, 10, 'F');
            doc.setTextColor(255, 255, 255);
            doc.setFontSize(13);
            doc.setFont('helvetica', 'bold');
            doc.text(`${dept.toUpperCase()} DEPARTMENT REPORT`, pageWidth / 2, 48.5, { align: 'center' });

            // Metadata - top right
            doc.setTextColor(100, 100, 100);
            doc.setFontSize(8);
            doc.setFont('helvetica', 'normal');
            doc.text(`Report Date: ${new Date().toLocaleDateString()}`, pageWidth - 10, 10, { align: 'right' });
            doc.text(`Department: ${dept}`, pageWidth - 10, 18, { align: 'right' });

            // Add Faculty Performance table (removed KPIs section)
            let startY = headerHeight + 8;
            
            doc.setFontSize(11);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(0, 0, 0);
            doc.text('Faculty Performance:', 14, startY);

            const facultyData = chFacultyTable.rows().data().toArray().map(row => [
                row[0], row[1], row[2], row[3], row[4], row[5], row[6], row[7]
            ]);

            doc.autoTable({
                head: [['Faculty Name', 'Total', 'Completed', 'Pending', 'Overdue', 'Completion %', 'Credit', 'Demerit']],
                body: facultyData,
                startY: startY + 5,
                theme: 'grid',
                headStyles: {
                    fillColor: [102, 126, 234],
                    textColor: [255, 255, 255],
                    fontStyle: 'bold'
                },
                styles: { fontSize: 8 },
                didDrawPage: function (data) {
                    // Page number only
                    doc.setFontSize(8);
                    doc.setTextColor(100, 100, 100);
                    const pageStr = 'Page ' + doc.internal.getNumberOfPages();
                    doc.text(pageStr, pageWidth / 2, pageHeight - 5, { align: 'center' });
                }
            });

            // Add Department Tasks table
            startY = doc.lastAutoTable.finalY + 15;

            doc.setFontSize(11);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(0, 0, 0);
            doc.text('Department Tasks:', 14, startY);

            const taskData = chDepartmentTasksTable.rows().data().toArray().map(row => [
                row[0], row[1].substring(0, 40), row[2], row[3], row[4],
                $(row[5]).text(), $(row[6]).text()
            ]);

            doc.autoTable({
                head: [['Task ID', 'Title', 'Assigned To', 'Start', 'Deadline', 'Priority', 'Status']],
                body: taskData,
                startY: startY + 5,
                theme: 'grid',
                headStyles: {
                    fillColor: [102, 126, 234],
                    textColor: [255, 255, 255],
                    fontStyle: 'bold'
                },
                styles: { fontSize: 8 },
                didDrawPage: function (data) {
                    // Page number only
                    doc.setFontSize(8);
                    doc.setTextColor(100, 100, 100);
                    const pageStr = 'Page ' + doc.internal.getNumberOfPages();
                    doc.text(pageStr, pageWidth / 2, pageHeight - 5, { align: 'center' });
                }
            });

            doc.save(`${dept.toLowerCase().replace(/\s+/g, '-')}-department-report.pdf`);

        } catch (error) {
            console.error('Error generating PDF:', error);
            alert('Error generating PDF: ' + error.message);
        }
    }, function (error) {
        console.warn('Failed to load logo, generating PDF without it');
    });
}
    </script>