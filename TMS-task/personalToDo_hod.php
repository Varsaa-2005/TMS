<?php
require_once "backend/session_init.php";

// Get the user_id from POST
$view_user_id = $_POST['user_id'] ?? null;

if (!$view_user_id) {
    echo '<div class="alert alert-danger">User ID is required</div>';
    exit;
}

// Get user name
$stmt = $conn->prepare("SELECT name FROM users WHERE user_id = ?");
$stmt->bind_param("s", $view_user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$view_user_name = $user_data['name'] ?? 'Unknown User';
?>

<style>
    .view-calendar-container {
        max-width: 1000px;
        margin: 20px auto;
        background-color: #ffffff;
        border-radius: 14px;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .view-calendar-header {
        background: linear-gradient(135deg, #4e73df, #36b9cc);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
    }

    .view-calendar-header h3 {
        font-weight: bold;
        font-size: 1.2rem;
        margin: 0;
    }

    .view-calendar-header button {
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

    .view-calendar-header button:hover {
        background-color: rgba(255, 255, 255, 0.4);
    }

    .view-weekday-row,
    .view-day-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        padding: 10px 15px;
    }

    .view-weekday-row div {
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
        font-size: 0.85rem;
    }

    .view-day {
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

    .view-day.empty {
        background: transparent;
        border: none;
        cursor: default;
    }

    .view-day:hover:not(.empty) {
        transform: scale(1.05);
        border-color: #4e73df;
    }

    .view-day.selected {
        border: 3px solid #6f42c1;
    }

    .view-day.today {
        border: 3px solid #007bff;
        background-color: #e9f3ff !important;
    }

    .view-day-number {
        margin-bottom: auto;
    }

    .view-priority-bar {
        width: 90%;
        height: 25px;
        display: flex;
        align-items: stretch;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .view-priority-segment {
        flex: 1;
        min-width: 5px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .view-seg-red {
        background-color: #dc3545 !important;
    }

    .view-seg-yellow {
        background-color: #ffc107 !important;
        color: #000 !important;
    }

    .view-seg-cyan {
        background-color: #17a2b8 !important;
    }

    .view-seg-green {
        background-color: #28a745 !important;
    }

    .view-tasks-container {
        max-width: 1000px;
        margin: 30px auto;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
        padding: 25px;
    }

    .view-tasks-section h4 {
        font-weight: bold;
        color: #333;
    }

    .view-priority-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: 600;
        color: white;
        display: inline-block;
    }

    .view-badge-red {
        background-color: #dc3545 !important;
    }

    .view-badge-yellow {
        background-color: #ffc107 !important;
        color: #000 !important;
    }

    .view-badge-cyan {
        background-color: #17a2b8 !important;
    }

    .view-badge-green {
        background-color: #28a745 !important;
    }

    .view-priority-legend {
        display: flex;
        gap: 15px;
        margin-top: 15px;
        flex-wrap: wrap;
    }

    .view-legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
    }

    .view-legend-color {
        width: 20px;
        height: 20px;
        border-radius: 3px;
    }

    .view-legend-red {
        background-color: #dc3545;
    }

    .view-legend-yellow {
        background-color: #ffc107;
    }

    .view-legend-cyan {
        background-color: #17a2b8;
    }

    .view-legend-green {
        background-color: #28a745;
    }

    .view-gradient-header {
        background: linear-gradient(135deg, #28a745, #8dc6ce) !important;
        color: white !important;
    }

    .view-gradient-header th {
        color: white !important;
        background: transparent !important;
    }

    .view-user-name {
        text-align: center;
        font-size: 1.3rem;
        font-weight: bold;
        color: #4e73df;
        margin-bottom: 20px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
    }
</style>

<div class="view-user-name">
    <i class="fas fa-user me-2"></i>Viewing Personal Tasks of: <?= htmlspecialchars($view_user_name) ?>
</div>

<div class="view-calendar-container">
    <div class="view-calendar-header">
        <button id="viewPrevMonth"><i class="fa fa-chevron-left"></i></button>
        <h3 id="viewCalendarTitle"></h3>
        <button id="viewNextMonth"><i class="fa fa-chevron-right"></i></button>
    </div>
    <div class="view-weekday-row" id="viewWeekdayRow"></div>
    <div class="view-day-grid" id="viewDayGrid"></div>

    <div class="view-priority-legend px-3 pb-3">
        <div class="view-legend-item">
            <div class="view-legend-color view-legend-red"></div>
            <span>Important & Immediate</span>
        </div>
        <div class="view-legend-item">
            <div class="view-legend-color view-legend-yellow"></div>
            <span>Important & Not Immediate</span>
        </div>
        <div class="view-legend-item">
            <div class="view-legend-color view-legend-cyan"></div>
            <span>Not Important & Immediate</span>
        </div>
        <div class="view-legend-item">
            <div class="view-legend-color view-legend-green"></div>
            <span>Not Important & Not Immediate</span>
        </div>
    </div>
</div>

<div class="view-tasks-container">
    <div class="view-tasks-section">
        <h4 id="viewSelectedDateTitle">
            <span><i class="fas fa-list-check me-2"></i>Personal Tasks - All Tasks</span>
        </h4>
        <div class="table-responsive mt-3">
            <table id="viewTaskDataTable" class="table table-bordered table-striped table-hover">
                <thead class="view-gradient-header">
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Start</th>
                        <th>Deadline</th>
                        <th>Priority</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="viewTasksTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for date tasks -->
<div class="modal fade" id="viewDateTasksModal" tabindex="-1" data-bs-backdrop="false" data-bs-keyboard="true"
    style="z-index: 9999;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Tasks on Selected Date</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <h4>Personal Tasks</h4>
                <table class="table table-striped table-bordered mb-0">
                    <thead class="view-gradient-header">
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Start Date</th>
                        </tr>
                    </thead>
                    <tbody id="viewDateTasksBody">
                        <tr>
                            <td colspan="5" class="text-center text-muted">No tasks found</td>
                        </tr>
                    </tbody>
                </table>
                <br><br>
                <h4>Assigned Tasks</h4>
                <table class="table table-striped table-bordered mb-0">
                    <thead class="view-gradient-header">
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Start Date</th>
                        </tr>
                    </thead>
                    <tbody id="viewAssignedTasksBody">
                        <tr>
                            <td colspan="5" class="text-center text-muted">No tasks found</td>
                        </tr>
                    </tbody>
                </table>
                <br><br>
                <h4>Scheduled Meetings</h4>
                <table class="table table-striped table-bordered mb-0">
                    <thead class="view-gradient-header">
                        <tr>
                            <th>Title</th>
                            <th>Staff</th>
                            <th>Date & Time</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody id="viewSchMeetBody">
                        <tr>
                            <td colspan="4" class="text-center text-muted">No meetings found</td>
                        </tr>
                    </tbody>
                </table>
                <br><br>
                <h4>Requested Meetings</h4>
                <table class="table table-striped table-bordered mb-0">
                    <thead class="view-gradient-header">
                        <tr>
                            <th>Description</th>
                            <th>Requested To</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="viewReqMeetBody">
                        <tr>
                            <td colspan="4" class="text-center text-muted">No meetings found</td>
                        </tr>
                    </tbody>
                </table>
                <br><br>
                <h4>Assigned Meetings</h4>
                <table class="table table-striped table-bordered mb-0">
                    <thead class="view-gradient-header">
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Date & Time</th>
                            <th>Location</th>
                            <th>Assigned By</th>
                        </tr>
                    </thead>
                    <tbody id="viewAssMeetBody">
                        <tr>
                            <td colspan="5" class="text-center text-muted">No meetings found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const viewUserId = '<?= $view_user_id ?>';
        let viewAllTasks = [];
        let viewAssignedTasks = [];
        let viewAllMeetings = { scheduled: [], requested: [], assigned: [] };
        let viewSelectedDate = new Date().toISOString().split('T')[0];
        let viewCurrentMonth = new Date().getMonth();
        let viewCurrentYear = new Date().getFullYear();
        let viewTaskDataTable = null;

        const weekdays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        $("#viewWeekdayRow").html(weekdays.map(d => `<div>${d}</div>`).join(''));

        // Create single modal instance to avoid backdrop accumulation
        let viewDateModal = null;
        const modalEl = document.getElementById('viewDateTasksModal');

        // Move modal to document body to prevent z-index issues when loaded in nested divs
        if (modalEl && modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }

        // Remove any existing backdrops on page load
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';

        modalEl.addEventListener('hidden.bs.modal', function () {
            // Remove any leftover backdrops
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });

        modalEl.addEventListener('shown.bs.modal', function () {
            // Ensure modal is on top and no backdrop interference
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            modalEl.style.zIndex = '9999';
        });

        modalEl.addEventListener('show.bs.modal', function () {
            // Prevent backdrop creation before modal shows
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        });

        function viewGetBadgeClass(priority) {
            switch (priority) {
                case "Important & Immediate": return "view-badge-red";
                case "Important & Not Immediate": return "view-badge-yellow";
                case "Not Important & Immediate": return "view-badge-cyan";
                case "Not Important & Not Immediate": return "view-badge-green";
                default: return "view-badge-green";
            }
        }

        // Check if date has meetings
        function viewHasMeetingsOnDate(dateStr) {
            const all = [...viewAllMeetings.scheduled, ...viewAllMeetings.requested, ...viewAllMeetings.assigned];
            return all.some(m => m.date === dateStr);
        }

        function viewRenderCalendar() {
            const first = new Date(viewCurrentYear, viewCurrentMonth, 1);
            const last = new Date(viewCurrentYear, viewCurrentMonth + 1, 0);
            $("#viewCalendarTitle").text(`${first.toLocaleString('default', { month: 'long' })} ${viewCurrentYear}`);
            let html = '';

            for (let i = 0; i < first.getDay(); i++) html += '<div class="view-day empty"></div>';

            // Combine personal and assigned tasks for calendar display
            const combinedTasks = [
                ...viewAllTasks.map(t => ({ ...t, type: 'personal' })),
                ...viewAssignedTasks.map(t => ({ ...t, type: 'assigned' }))
            ];

            for (let d = 1; d <= last.getDate(); d++) {
                const dateStr = `${viewCurrentYear}-${String(viewCurrentMonth + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                const isToday = dateStr === new Date().toISOString().split('T')[0];

                const dayTasks = combinedTasks.filter(t => t.deadline === dateStr && t.status !== "Completed");
                const hasMeetings = viewHasMeetingsOnDate(dateStr);

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

                    priorityBar = '<div class="view-priority-bar">';
                    Object.entries(counts).forEach(([color, count]) => {
                        if (count > 0) {
                            priorityBar += `<div class="view-priority-segment view-seg-${color}">${count}</div>`;
                        }
                    });
                    priorityBar += '</div>';
                }

                const meetingIcon = hasMeetings ? '<i class="fas fa-bell" style="position:absolute;top:2px;right:2px;color:#ffc107;font-size:0.7rem;"></i>' : '';

                html += `<div class="view-day ${isToday ? 'today' : ''} ${dateStr === viewSelectedDate ? 'selected' : ''}" data-date="${dateStr}" style="position:relative;">
                ${meetingIcon}
                <div class="view-day-number">${d}</div>
                ${priorityBar}
            </div>`;
            }

            $("#viewDayGrid").html(html);

            $(".view-day").not(".empty").click(function () {
                viewSelectedDate = $(this).data('date');
                viewRenderCalendar();

                // Personal tasks for this date
                const personalTasks = viewAllTasks.filter(t => t.deadline === viewSelectedDate);
                const personalBody = $("#viewDateTasksBody").empty();
                if (personalTasks.length) {
                    personalTasks.forEach(t => {
                        const badgeClass = viewGetBadgeClass(t.priority);
                        personalBody.append(`<tr>
                            <td>${t.title}</td>
                            <td>${t.description || '-'}</td>
                            <td><span class="view-priority-badge ${badgeClass}">${t.priority}</span></td>
                            <td>${t.status}</td>
                            <td>${t.start_date || '-'}</td>
                        </tr>`);
                    });
                } else {
                    personalBody.append(`<tr><td colspan="5" class="text-center text-muted">No personal tasks on this date</td></tr>`);
                }

                // Assigned tasks for this date
                const assignedTasksForDate = viewAssignedTasks.filter(t => t.deadline === viewSelectedDate);
                const assignedBody = $("#viewAssignedTasksBody").empty();
                if (assignedTasksForDate.length) {
                    assignedTasksForDate.forEach(t => {
                        const badgeClass = viewGetBadgeClass(t.priority);
                        assignedBody.append(`<tr>
                            <td>${t.title}</td>
                            <td>${t.description || '-'}</td>
                            <td><span class="view-priority-badge ${badgeClass}">${t.priority}</span></td>
                            <td>${t.status}</td>
                            <td>${t.start_date || '-'}</td>
                        </tr>`);
                    });
                } else {
                    assignedBody.append(`<tr><td colspan="5" class="text-center text-muted">No assigned tasks on this date</td></tr>`);
                }

                // Scheduled meetings for this date
                const scheduledMeetings = viewAllMeetings.scheduled.filter(m => m.date === viewSelectedDate);
                const schMeetBody = $("#viewSchMeetBody").empty();
                if (scheduledMeetings.length) {
                    scheduledMeetings.forEach(m => {
                        schMeetBody.append(`<tr>
                            <td>${m.title || '-'}</td>
                            <td>${m.assigned_by || '-'}</td>
                            <td>${m.time || m.datetime || '-'}</td>
                            <td>${m.location || '-'}</td>
                        </tr>`);
                    });
                } else {
                    schMeetBody.append(`<tr><td colspan="4" class="text-center text-muted">No scheduled meetings on this date</td></tr>`);
                }

                // Requested meetings for this date
                const requestedMeetings = viewAllMeetings.requested.filter(m => m.date === viewSelectedDate);
                const reqMeetBody = $("#viewReqMeetBody").empty();
                if (requestedMeetings.length) {
                    requestedMeetings.forEach(m => {
                        const statusClass = m.status === 'Approved' ? 'success' : m.status === 'Rejected' ? 'danger' : 'warning';
                        reqMeetBody.append(`<tr>
                            <td>${m.description || '-'}</td>
                            <td>${m.requested_to || '-'}</td>
                            <td>${m.datetime || '-'}</td>
                            <td><span class="badge bg-${statusClass}">${m.status || 'Pending'}</span></td>
                        </tr>`);
                    });
                } else {
                    reqMeetBody.append(`<tr><td colspan="4" class="text-center text-muted">No requested meetings on this date</td></tr>`);
                }

                // Assigned meetings for this date
                const assignedMeetings = viewAllMeetings.assigned.filter(m => m.date === viewSelectedDate);
                const assMeetBody = $("#viewAssMeetBody").empty();
                if (assignedMeetings.length) {
                    assignedMeetings.forEach(m => {
                        assMeetBody.append(`<tr>
                            <td>${m.title || '-'}</td>
                            <td>${m.description || '-'}</td>
                            <td>${m.datetime || '-'}</td>
                            <td>${m.location || '-'}</td>
                            <td>${m.assigned_by || '-'}</td>
                        </tr>`);
                    });
                } else {
                    assMeetBody.append(`<tr><td colspan="5" class="text-center text-muted">No assigned meetings on this date</td></tr>`);
                }

                $(".modal-title", "#viewDateTasksModal").text(`Tasks & Meetings on ${viewSelectedDate}`);

                // Use single modal instance with explicit backdrop: false
                if (!viewDateModal) {
                    viewDateModal = new bootstrap.Modal(modalEl, {
                        backdrop: false,
                        keyboard: true
                    });
                }

                // Remove any backdrop before showing
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');

                viewDateModal.show();
            });
        }

        function viewLoadTasksTable() {
            if (viewTaskDataTable) {
                viewTaskDataTable.destroy();
                viewTaskDataTable = null;
            }

            const tbody = $("#viewTasksTableBody").empty();

            if (!viewAllTasks.length) {
                tbody.append(`<tr>
                    <td>-</td>
                    <td>No tasks found</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>`);
            } else {
                let pending = viewAllTasks.filter(t => t.status !== "Completed");
                let completed = viewAllTasks.filter(t => t.status === "Completed");
                let sorted = [...pending, ...completed];

                sorted.forEach(task => {
                    const badgeClass = viewGetBadgeClass(task.priority);
                    tbody.append(`<tr>
                    <td>${task.title || '-'}</td>
                    <td>${task.description || '-'}</td>
                    <td>${task.start_date || '-'}</td>
                    <td>${task.deadline || '-'}</td>
                    <td><span class="view-priority-badge ${badgeClass}">${task.priority || '-'}</span></td>
                    <td>${task.status || '-'}</td>
                </tr>`);
                });
            }

            viewTaskDataTable = $('#viewTaskDataTable').DataTable({
                pageLength: 10,
                order: [],
                searching: true,
                responsive: true,
                lengthMenu: [5, 10, 20, 50]
            });
        }

        $("#viewPrevMonth").click(function () {
            viewCurrentMonth--;
            if (viewCurrentMonth < 0) {
                viewCurrentMonth = 11;
                viewCurrentYear--;
            }
            viewRenderCalendar();
        });

        $("#viewNextMonth").click(function () {
            viewCurrentMonth++;
            if (viewCurrentMonth > 11) {
                viewCurrentMonth = 0;
                viewCurrentYear++;
            }
            viewRenderCalendar();
        });

        // Fetch all data for this user (personal tasks, assigned tasks, and meetings)
        function fetchAllData() {
            // Fetch personal tasks
            $.post("backend/api.php", { action: "fetch_user_personal_tasks", user_id: viewUserId }, function (response) {
                if (response.status === "success") {
                    viewAllTasks = response.data || [];
                    viewRenderCalendar();
                    viewLoadTasksTable();
                } else {
                    $("#viewTasksTableBody").html('<tr><td colspan="6" class="text-center text-danger">Error loading tasks</td></tr>');
                }
            }, 'json');

            // Fetch assigned tasks
            $.post("backend/api.php", { action: "fetch_user_assigned_tasks", user_id: viewUserId }, function (response) {
                if (response.status === "success") {
                    // Normalize field names to match personal tasks structure
                    viewAssignedTasks = (response.data || []).map(t => ({
                        title: t.task_title,
                        description: t.task_description,
                        start_date: t.start_date,
                        deadline: t.deadline,
                        priority: t.Priority,
                        status: t.status,
                        assigned_by: t.name
                    }));
                    viewRenderCalendar();
                }
            }, 'json');

            // Fetch meetings
            $.post("backend/api.php", { action: "fetch_user_meetings", user_id: viewUserId }, function (response) {
                if (response.status === "success") {
                    viewAllMeetings = {
                        scheduled: response.scheduled || [],
                        requested: response.requested || [],
                        assigned: response.assigned || []
                    };
                    viewRenderCalendar();
                }
            }, 'json');
        }

        fetchAllData();
    })();
</script>
