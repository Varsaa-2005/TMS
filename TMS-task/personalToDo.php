<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//if (!isset($_SESSION['user_id'])) {
//  header("Location: .login_module/login.php");
//exit();
//}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$dept = $_SESSION['dept'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Task Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        body {
            font-family: sans-serif;
            background-color: #fff;
            padding: 20px;
            min-height: 100vh;
        }

        .calendar-container {
            max-width: 1000px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 14px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .calendar-header {
            background: linear-gradient(135deg, #4e73df, #36b9cc);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
        }

        .calendar-header h3 {
            font-weight: bold;
            font-size: 1.2rem;
            margin: 0;
        }

        .calendar-header button {
            background-color: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            border-radius: 6px;
            width: 35px;
            height: 35px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .calendar-header button:hover {
            background-color: rgba(255, 255, 255, 0.4);
        }

        .weekday-row,
        .day-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            padding: 10px 15px;
        }

        .weekday-row div {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
            font-size: 0.85rem;
        }

        .day {
            width: 100%;
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 10px;
            position: relative;
            cursor: pointer;
            font-weight: 600;
            color: #333;
            background: #fff;
            transition: 0.2s;
            font-size: 0.9rem;
            padding: 8px 4px;
        }

        .day.empty {
            background: transparent;
            border: none;
            cursor: default;
        }

        .day:hover {
            transform: scale(1.05);
            border-color: #4e73df;
        }

        .day.selected {
            border: 3px solid #6f42c1;
        }

        .day-number {
            margin-bottom: auto;
        }

        .priority-bar {
            width: 90%;
            height: 25px;
            display: flex;
            align-items: stretch;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .priority-segment {
            flex: 1;
            min-width: 5px;
            height: 100%;
        }

        .priority-segment.seg-red {
            background-color: #dc3545 !important;
        }

        .priority-segment.seg-yellow {
            background-color: #ffc107 !important;
        }

        .priority-segment.seg-cyan {
            background-color: #17a2b8 !important;
        }

        .priority-segment.seg-green {
            background-color: #28a745 !important;
        }

        .today {
            border: 3px solid #007bff;
            background-color: #e9f3ff !important;
        }

        .tasks-container {
            max-width: 1000px;
            margin: 30px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            padding: 25px;
        }

        .tasks-section h4 {
            font-weight: bold;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .priority-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            color: white;
            display: inline-block;
        }

        .badge-red {
            background-color: #dc3545 !important;
        }

        .badge-yellow {
            background-color: #ffc107 !important;
            color: #000 !important;
        }

        .badge-cyan {
            background-color: #17a2b8 !important;
        }

        .badge-green {
            background-color: #28a745 !important;
        }

        .priority-legend {
            display: flex;
            gap: 15px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 3px;
        }

        .legend-red {
            background-color: #dc3545;
        }

        .legend-yellow {
            background-color: #ffc107;
        }

        .legend-cyan {
            background-color: #17a2b8;
        }

        .legend-green {
            background-color: #28a745;
        }

        .modal {
            z-index: 99999 !important;
        }

        .modal-backdrop {
            z-index: 99998 !important;
        }

        #addTaskBtn:disabled {
            background-color: #ccc !important;
            border: none;
            cursor: not-allowed;
        }

        .modal-header.bg-info.text-white {
            border-radius: 0px;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .action-buttons .btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .badge.bg-warning {
            background-color: #ffc107 !important;
        }

        .badge.bg-success {
            background-color: #28a745 !important;
        }

        .assignto {
            --bs-table-bg: transparent;
            --bs-table-color: white;
            background: linear-gradient(135deg, #4CAF50, #2196F3) !important;
            color: inherit;
            text-align: center;
            font-size: 0.9em;
            font-weight: 600;
        }

        /* Bell icon in calendar cell */
        .meeting-icon {
            position: absolute;
            top: 4px;
            right: 6px;
            color: #3e453e;
            font-size: 1.8rem;
        }
    </style>

</head>

<body>

    <div class="calendar-container">
        <div class="calendar-header">
            <button id="prevMonth"><i class="fa fa-chevron-left"></i></button>
            <h3 id="calendarTitle"></h3>
            <button id="nextMonth"><i class="fa fa-chevron-right"></i></button>
        </div>
        <div class="weekday-row" id="weekdayRow"></div>
        <div class="day-grid" id="dayGrid"></div>

        <div class="priority-legend px-3 pb-3">
            <div class="legend-item">
                <div class="legend-color legend-red"></div>
                <span>Important & Immediate</span>
            </div>
            <div class="legend-item">
                <div class="legend-color legend-yellow"></div>
                <span>Important & Not Immediate</span>
            </div>
            <div class="legend-item">
                <div class="legend-color legend-cyan"></div>
                <span>Not Important & Immediate</span>
            </div>
            <div class="legend-item">
                <div class="legend-color legend-green"></div>
                <span>Not Important & Not Immediate</span>
            </div>
        </div>
    </div>

    <div class="tasks-container">
        <!-- Personal To-Do Section -->
        <div class="tasks-section">
            <h4 id="selectedDateTitle">
                <span><i class="fas fa-list-check me-2"></i>Personal Tasks for Today</span>
                <button class="btn btn-secondary btn-sm" id="addTaskBtn"><i class="fas fa-plus"></i> Add Task</button>
            </h4>
            <div class="table-responsive mt-3">
                <table id="taskDataTable" class="table table-bordered table-striped table-hover">
                    <thead class="gradient-header">
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Start</th>
                            <th>Deadline</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tasksTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="taskModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg">

                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Task</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="taskForm">
                        <input type="hidden" id="taskId">

                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input id="taskTitle" class="form-control" required placeholder="Enter Task Title">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea id="taskDescription" class="form-control" rows="3"
                                placeholder="Enter Task Description"></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Start</label>
                                <input type="date" id="taskStartDate" class="form-control" min="<?= date('Y-m-d'); ?>"
                                    required>
                            </div>
                            <div class="col">
                                <label class="form-label">Deadline</label>
                                <input type="date" id="taskDeadline" class="form-control" min="<?= date('Y-m-d'); ?>"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select id="taskPriority" class="form-select">
                                <option value="Important & Immediate">Important & Immediate</option>
                                <option value="Important & Not Immediate">Important & Not Immediate</option>
                                <option value="Not Important & Immediate">Not Important & Immediate</option>
                                <option value="Not Important & Not Immediate">Not Important & Not Immediate</option>
                            </select>
                        </div>

                        <div class="mb-3" id="statusField" style="display:none;">
                            <label class="form-label">Status</label>
                            <select id="taskStatus" class="form-select">
                                <option>Pending</option>
                                <option>Completed</option>
                            </select>
                        </div>

                        <!--  Replaced Save button with modal-footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="savebtnaddtask">Save</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="dateTasksModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Tasks on Selected Date</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h4>Personal Task</h4>
                    <table class="table table-striped table-bordered mb-0">
                        <thead class="gradient-header">
                            <tr>
                                
                                <th>Title</th>
                                <th>Description</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Start Date</th>
                            </tr>
                        </thead>
                        <tbody id="dateTasksBody">
                            <tr>
                                <td colspan="5" class="text-center text-muted">No tasks found</td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br>
                    <h4>Assigned Task</h4>
                    <table class="table table-striped table-bordered mb-0">
                        <thead class="gradient-header">
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Start Date</th>
                            </tr>
                        </thead>
                        <tbody id="assignedTasksBody">
                            <tr>
                                <td colspan="5" class="text-center text-muted">No tasks found</td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br>
                    <h4 id="sch_meet_head">Scheduled Meetings</h4>
                    <table class="table table-striped table-bordered mb-0">
                        <thead class="gradient-header">
                            <tr>
                               
                                <th>Title</th>
                                <th>Staff</th>
                                <th>Date & Time</th>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="sch_meetTasksBody">
                            <tr>
                                <td colspan="5" class="text-center text-muted">No meetings found</td>
                            </tr>
                            <br>
                        </tbody>
                    </table>
                    <br><br>
                    <h4 id="req_meet_head">Requested Meetings</h4>
                    <table class="table table-striped table-bordered mb-0">
                        <thead class="gradient-header">
                            <tr>
                                
                                <th>Description</th>
                                <th>Requested To</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="req_meetTasksBody">
                            <tr>
                                <td colspan="5" class="text-center text-muted">No meetings found</td>
                            </tr>
                            <br>
                        </tbody>
                    </table>
                    <br><br>
                    <h4 id="ass_meet_head">Assigned Meetings</h4>
                    <table class="table table-striped table-bordered mb-0">
                        <thead class="gradient-header">
                            <tr>
                                
                                <th>Title</th>
                                <th>Description</th>
                                <th>Date & Time</th>
                                <th>Location</th>
                                <th>Assigned By</th>
                            </tr>
                        </thead>
                        <tbody id="ass_meetTasksBody">
                            <tr>
                                <td colspan="5" class="text-center text-muted">No meetings found</td>
                            </tr>
                            <br>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let taskDataTable = null;

        $(document).ready(function () {

            taskDataTable = $('#taskDataTable').DataTable({
                pageLength: 10,
                order: [],
                searching: true,
                responsive: true,
                lengthMenu: [5, 10, 20, 50]
            });

        });


        $(function () {
            $('#taskModal').appendTo('body');
            $('#dateTasksModal').appendTo('body');

            const modal = new bootstrap.Modal('#taskModal');
            const dateModal = new bootstrap.Modal('#dateTasksModal');

            let selectedDate = new Date().toISOString().split('T')[0];
            let currentMonth = new Date().getMonth(),
                currentYear = new Date().getFullYear();

            const weekdays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            $("#weekdayRow").html(weekdays.map(d => `<div>${d}</div>`).join(''));

            function getPriorityColorClass(priority) {
                switch (priority) {
                    case "Important & Immediate":
                        return "seg-red";
                    case "Important & Not Immediate":
                        return "seg-yellow";
                    case "Not Important & Immediate":
                        return "seg-cyan";
                    case "Not Important & Not Immediate":
                        return "seg-green";
                    default:
                        return "seg-green";
                }
            }

            function getBadgeClass(priority) {
                switch (priority) {
                    case "Important & Immediate":
                        return "badge-red";
                    case "Important & Not Immediate":
                        return "badge-yellow";
                    case "Not Important & Immediate":
                        return "badge-cyan";
                    case "Not Important & Not Immediate":
                        return "badge-green";
                    default:
                        return "badge-green";
                }
            }
            let allMeetings = []; // Store all meetings globally

            // Fetch all meetings on page load
            function fetchAllMeetings() {
                return Promise.all([
                    $.get('backend/api.php?action=fetch_meetings'),
                    $.get('backend/api.php?action=fetch_requested_meetings'),
                    $.get('backend/api.php?action=fetch_assigned_meetings_calendar')
                ]);
            }

            // Check if date has any meetings
            function hasMeetingsOnDate(dateStr) {
                return allMeetings.some(meeting => meeting.date === dateStr);
            }

            // Get meetings for specific date
            function getMeetingsForDate(dateStr) {
                return allMeetings.filter(meeting => meeting.date === dateStr);
            }

            // Updated renderCalendar function with meeting indicator
            function renderCalendar() {
                const first = new Date(currentYear, currentMonth, 1);
                const last = new Date(currentYear, currentMonth + 1, 0);
                $("#calendarTitle").text(`${first.toLocaleString('default', { month: 'long' })} ${currentYear}`);
                let html = '';

                // Empty cells before month start
                for (let i = 0; i < first.getDay(); i++) html += '<div class="day empty"></div>';

                // Fetch both tasks and meetings
                Promise.all([
                    $.post('backend/api.php', { action: 'fetch' }, null, 'json'),
                    $.post('backend/api.php', { action: 'readtasks' }, null, 'json'),
                    fetchAllMeetings()
                ])
                    .then(([personalTasks, assignedTasks, meetingResults]) => {
                        // Store meetings globally
                        allMeetings = [
                            ...(meetingResults[0] || []), // scheduled meetings
                            ...(meetingResults[1] || []), // requested meetings
                            ...(meetingResults[2] || [])  // assigned meetings
                        ];

                        // Merge tasks
                        const allTasks = [
                            ...personalTasks.map(t => ({
                                title: t.title,
                                description: t.description,
                                start_date: t.start_date,
                                deadline: t.deadline,
                                priority: t.priority,
                                status: t.status,
                                type: "personal"
                            })),
                            ...assignedTasks.map(t => ({
                                title: t.task_title,
                                description: t.task_description,
                                start_date: t.start_date,
                                deadline: t.deadline,
                                priority: t.Priority,
                                status: t.status,
                                type: "assigned"
                            }))
                        ];

                        for (let d = 1; d <= last.getDate(); d++) {
                            const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                            const isToday = dateStr === new Date().toISOString().split('T')[0];

                            // Tasks on this date
                            const dayTasks = allTasks.filter(t => t.deadline === dateStr && t.status !== "Completed");

                            // Check for meetings
                            const hasMeetings = hasMeetingsOnDate(dateStr);

                            let priorityBar = '';
                            if (dayTasks.length > 0) {
                                const counts = { red: 0, yellow: 0, cyan: 0, green: 0 };

                                dayTasks.forEach(t => {
                                    switch (t.priority) {
                                        case "Important & Immediate": counts.red++; break;
                                        case "Important & Not Immediate": counts.yellow++; break;
                                        case "Not Important & Immediate": counts.cyan++; break;
                                        case "Not Important & Not Immediate": counts.green++; break;
                                    }
                                });

                                priorityBar = '<div class="priority-bar">';
                                Object.entries(counts).forEach(([color, count]) => {
                                    if (count > 0) {
                                        priorityBar += `<div class="priority-segment seg-${color}" 
                                         style="display: flex; align-items: center; justify-content: center; 
                                                color: white; font-size: 0.7rem; font-weight: 600;">
                                         ${count}
                                     </div>`;
                                    }
                                });
                                priorityBar += '</div>';
                            }

                            // Meeting bell icon
                            const meetingIcon = hasMeetings ? '<i class="fas fa-bell meeting-icon"></i>' : '';

                            html += `<div class="day ${isToday ? 'today' : ''} ${dateStr === selectedDate ? 'selected' : ''}"
                          data-date="${dateStr}"
                          data-tasks='${encodeURIComponent(JSON.stringify(dayTasks))}'>
                          ${meetingIcon}
                          <div class="day-number">${d}</div>
                          ${priorityBar}
                      </div>`;
                        }

                        $("#dayGrid").html(html);

                        // Click day -> open modal
                        $(".day").not(".empty").click(function () {
                            selectedDate = $(this).data('date');
                            renderCalendar();
                            loadTasks();

                            const today = new Date().toISOString().split('T')[0];
                            $("#addTaskBtn").prop("disabled", selectedDate < today);

                            const allDayTasks = JSON.parse(decodeURIComponent($(this).data('tasks')));
                            const personalTasks = allDayTasks.filter(t => t.type === 'personal');
                            const assignedTasks = allDayTasks.filter(t => t.type === 'assigned');

                            // Load meetings for this date
                            const dateMeetings = getMeetingsForDate(selectedDate);
                            const scheduledMeetings = dateMeetings.filter(m => m.type === 'scheduled');
                            const requestedMeetings = dateMeetings.filter(m => m.type === 'requested');
                            const assignedMeetings = dateMeetings.filter(m => m.type === 'assigned');

                            // Personal tasks table
                            const personalBody = $("#dateTasksBody").empty();
                            if (personalTasks.length) {
                                personalTasks.forEach(t => {
                                    const badgeClass = getBadgeClass(t.priority);
                                    personalBody.append(`<tr>
                        <td>${t.title}</td>
                        <td>${t.description}</td>
                        <td><span class="priority-badge ${badgeClass}"></span></td>
                        <td>${t.status}</td>
                        <td>${t.start_date}</td>
                    </tr>`);
                                });
                            } else {
                                personalBody.append(`<tr><td colspan="5" class="text-center text-muted">No personal tasks</td></tr>`);
                            }

                            // Assigned tasks table
                            const assignedBody = $("#assignedTasksBody").empty();
                            if (assignedTasks.length) {
                                assignedTasks.forEach(t => {
                                    const badgeClass = getBadgeClass(t.priority);
                                    assignedBody.append(`<tr>
                        <td>${t.title}</td>
                        <td>${t.description}</td>
                        <td><span class="priority-badge ${badgeClass}"></span></td>
                        <td>${t.status}</td>
                        <td>${t.start_date}</td>
                    </tr>`);
                                });
                            } else {
                                assignedBody.append(`<tr><td colspan="5" class="text-center text-muted">No assigned tasks</td></tr>`);
                            }

                            // Scheduled meetings table
                            const schMeetBody = $("#sch_meetTasksBody").empty();
                            if (scheduledMeetings.length) {
                                scheduledMeetings.forEach(m => {
                                    schMeetBody.append(`<tr>
                        <td>${m.title}</td>
                        <td>${m.assigned_by}</td>
                        <td>${m.time || 'TBD'}</td>
                        <td>${m.location}</td>
                        <td><button class="btn btn-sm btn-info view-meeting-details" data-meeting='${JSON.stringify(m)}'>
                            <i class="fas fa-eye"></i> View
                        </button></td>
                    </tr>`);
                                });
                                 $("#sch_meet_head").show();
    $("#sch_meetTasksBody").closest("table").show();
                            }  else{
                                
    $("#sch_meetTasksBody").closest("table").show(); // or hide if you prefer

    schMeetBody.append(`
        <tr>
            <td colspan="5" class="text-center text-muted">
                No meetings available
            </td>
        </tr>
    `); 
                            }
    //                         else {
    //                             schMeetBody.append(`<tr><td colspan="5" class="text-center text-muted">No meetings scheduled</td></tr>`);
    //                              $("#sch_meet_head").hide();
    // $("#sch_meetTasksBody").closest("table").hide();
    //                         }

                            // Requested meetings table
                            const reqMeetBody = $("#req_meetTasksBody").empty();
                            if (requestedMeetings.length) {
                                requestedMeetings.forEach(m => {
                                    const statusClass = m.status === 'Approved' ? 'success' :
                                        m.status === 'Rejected' ? 'danger' : 'warning';
                                    reqMeetBody.append(`<tr>
                        <td>${m.description}</td>
                        <td>${m.requested_to}</td>
                        <td>${m.datetime}</td>
                        <td><span class="badge bg-${statusClass}">${m.status}</span></td>
                    </tr>`);
                                });
                                $("#req_meet_head").show();
                                $("#req_meetTasksBody").closest('table').show();
                             } else{
                                
    $("#req_meetTasksBody").closest("table").show(); // or hide if you prefer

    reqMeetBody.append(`
        <tr>
            <td colspan="5" class="text-center text-muted">
                No meetings available
            </td>
        </tr>
    `); 
                            }
                            //  else {
                            //     reqMeetBody.append(`<tr><td colspan="4" class="text-center text-muted">No meeting requests</td></tr>`);
                            //     $("#req_meet_head").hide();
                            //     $("#req_meetTasksBody").closest('table').hide();
                            // }

                            // Assigned meetings table
                            const assMeetBody = $("#ass_meetTasksBody").empty();
                            if (assignedMeetings.length) {
                                assignedMeetings.forEach(m => {
                                    assMeetBody.append(`<tr>
                        <td>${m.title}</td>
                        <td>${m.description}</td>
                        <td>${m.datetime}</td>
                        <td>${m.location}</td>
                        <td>${m.assigned_by}</td>
                    </tr>`);
                                });
                                $("#ass_meet_head").show();
                                $("#ass_meetTasksBody").closest('table').show();
                            }
                                else{ $("#ass_meetTasksBody").closest("table").show(); // or hide if you prefer

    assMeetBody.append(`
        <tr>
            <td colspan="5" class="text-center text-muted">
                No meetings available
            </td>
        </tr>
    `); 
                                }      
                            //  else {
                            //     assMeetBody.append(`<tr><td colspan="5" class="text-center text-muted">No assigned meetings</td></tr>`);
                            //     $("#ass_meet_head").hide();
                            //     $("#ass_meetTasksBody").closest('table').hide();
                            // }

                            $(".modal-title", "#dateTasksModal").text(`Tasks & Meetings on ${selectedDate}`);
                            dateModal.show();
                        });
                    })
                    .catch(err => console.error("Error fetching data:", err));
            }
            



            // Load Personal Tasks
            function loadTasks() {

                $("#selectedDateTitle span").text("Personal Tasks – All Tasks");

                $.getJSON('backend/api.php?action=fetch', function (tasks) {

                    if (taskDataTable) taskDataTable.clear();

                    if (!tasks.length) {
                        taskDataTable.row.add([
                            "No tasks available", "-", "-", "-", "-", "-", "-"
                        ]).draw();
                        return;
                    }

                    // SORT → Pending first, Completed last
                    let pending = tasks.filter(t => t.status !== "Completed");
                    let completed = tasks.filter(t => t.status === "Completed");

                    pending.sort((a, b) => new Date(b.start_date) - new Date(a.start_date));
                    completed.sort((a, b) => new Date(b.start_date) - new Date(a.start_date));

                    const ordered = [...pending, ...completed];

                    ordered.forEach(t => {

                        const badgeClass = getBadgeClass(t.priority);

                        // ✔ Create STATUS BADGE here
                        let statusBadge = "";
                        if (t.status === "Completed") {
                            statusBadge = `<span class="badge bg-success">${t.status}</span>`;
                        } else {
                            statusBadge = `<span class="badge bg-warning text-dark">${t.status}</span>`;
                        }

                        // ACTION BUTTONS
                        let actions = `
                <div class="action-buttons d-flex gap-2">
                    <button class='btn btn-danger btn-sm delete' data-id='${t.id}'>
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;

                        if (t.status !== "Completed") {
                            actions = `
                    <div class="action-buttons d-flex gap-2">
                        <button class='btn btn-success btn-sm complete' data-id='${t.id}'>
                            <i class="fas fa-check"></i>
                        </button>

                        <button class='btn btn-warning btn-sm edit'
                            data-task='${encodeURIComponent(JSON.stringify(t))}'>
                            <i class="fas fa-edit"></i>
                        </button>

                        <button class='btn btn-danger btn-sm delete' data-id='${t.id}'>
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                        }

                        // ADD ROWS
                        taskDataTable.row.add([
                            t.title,
                            t.description,
                            t.start_date,
                            t.deadline,
                            `<span class="priority-badge ${badgeClass}"></span>`,
                            statusBadge, // ← FIXED
                            actions
                        ]);

                    });

                    taskDataTable.draw();
                });
            }





            $("#prevMonth").click(() => {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                renderCalendar();
            });

            $("#nextMonth").click(() => {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                renderCalendar();
            });

            // date restriction

            const today = new Date().toISOString().split('T')[0];
            $('#taskStartDate').attr('min', today);
            $('#taskDeadline').attr('min', today);
            $('#taskStartDate').on('change', function () {
                const startDate = this.value;
                $('#taskDeadline').attr('min', startDate);
                if ($('#taskDeadline').val() < startDate) {
                    $('#taskDeadline').val(startDate);
                }
            });

            $("#addTaskBtn").click(() => {
                $("#taskForm")[0].reset();
                $("#taskId").val('');
                $("#statusField").hide();
                $("#taskStartDate").val(selectedDate);
                $("#taskDeadline").val(selectedDate);
                $(".modal-title").text("Add Task");
                modal.show();
            });

            $(document).on("click", ".edit", function () {
                const t = JSON.parse(decodeURIComponent($(this).data('task')));
                $("#taskId").val(t.id);
                $("#taskTitle").val(t.title);
                $("#taskDescription").val(t.description);
                $("#taskStartDate").val(t.start_date);
                $("#taskDeadline").val(t.deadline);
                $("#taskPriority").val(t.priority);
                $("#taskStatus").val(t.status);
                $("#statusField").show();
                $(".modal-title").text("Edit Task");
                modal.show();
            });

            $("#savebtnaddtask").on('click', function (e) {
                e.preventDefault();
                const data = {
                    id: $("#taskId").val(),
                    title: $("#taskTitle").val(),
                    description: $("#taskDescription").val(),
                    start_date: $("#taskStartDate").val(),
                    deadline: $("#taskDeadline").val(),
                    priority: $("#taskPriority").val(),
                    status: $("#taskStatus").val() || 'Pending'
                };
                console.log(data);

                const action = data.id ? 'update' : 'insert';

                $.ajax({
                    url: 'backend/api.php?action=' + action,
                    method: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    success: function (response) {
                        console.log('Response:', response);
                        modal.hide();
                        renderCalendar();
                        loadTasks();
                        Swal.fire('Success', 'Task saved successfully!', 'success');
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        console.error('Response:', xhr.responseText);
                        Swal.fire('Error', 'Failed to save task: ' + error, 'error');
                    }
                });
            });

            $(document).on("click", ".complete", function () {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Mark Complete?',
                    text: 'Do you want to mark this task as completed?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes'
                }).then(res => {
                    if (res.isConfirmed) {
                        $.post('backend/api.php?action=complete', {
                            id
                        }, function () {
                            renderCalendar();
                            loadTasks();
                            Swal.fire('Completed', 'Task marked as complete!', 'success');
                        });
                    }
                });
            });

            $(document).on("click", ".delete", function () {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Delete Task?',
                    text: 'This cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete'
                }).then(res => {
                    if (res.isConfirmed) {
                        $.post('backend/api.php?action=delete', {
                            id
                        }, function () {
                            renderCalendar();
                            loadTasks();
                            Swal.fire('Deleted', 'Task removed.', 'success');
                        });
                    }
                });
            });

            renderCalendar();
            loadTasks();
        });

        // Add this new function to load assigned tasks
        function loadAssignedTasks(date) {
            const assignedBody = $("#assignedTasksBody").empty();

            $.post('backend/api.php', {
                action: 'readtasks'
            }, function (res) {
                if (!Array.isArray(res)) {
                    assignedBody.append(`<tr><td colspan="5" class="text-center text-muted">Error loading assigned tasks</td></tr>`);
                    return;
                }

                // Filter by date (either start_date or deadline matches selected date)
                const filtered = res.filter(t => t.start_date === date || t.deadline === date);

                if (filtered.length) {
                    filtered.forEach(t => {
                        // Apply same badge colors as personal tasks
                        let badgeClass = '';
                        switch (t.Priority) {
                            case "Important & Immediate":
                                badgeClass = "badge-red";
                                break;
                            case "Important & Not Immediate":
                                badgeClass = "badge-yellow";
                                break;
                            case "Not Important & Immediate":
                                badgeClass = "badge-cyan";
                                break;
                            case "Not Important & Not Immediate":
                                badgeClass = "badge-green";
                                break;
                            default:
                                badgeClass = "badge-green";
                        }

                        assignedBody.append(`
                    <tr>
                        <td>${t.task_title || 'N/A'}</td>
                        <td>${t.task_description || 'N/A'}</td>
                        <td><span class="priority-badge ${badgeClass}"></span></td>
                        <td>${t.status || 'N/A'}</td>
                        <td>${t.deadline || 'N/A'}</td>
                    </tr>
                `);
                    });
                } else {
                    assignedBody.append(`<tr><td colspan="5" class="text-center text-muted">No assigned tasks on this date</td></tr>`);
                }
            }, 'json').fail(function (jqXHR, textStatus, errorThrown) {
                console.error("Error loading assigned tasks:", textStatus, errorThrown);
                assignedBody.append(`<tr><td colspan="5" class="text-center text-muted">Error loading assigned tasks: ${textStatus}</td></tr>`);
            });
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

</body>

</html>