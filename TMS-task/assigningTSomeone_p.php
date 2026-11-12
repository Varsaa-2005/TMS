<!DOCTYPE html>
<html lang="en">
<!--Portfolios assigning task to someone-->
<head>
    <meta charset="UTF-8">
    <title>Assign to Someone</title>

    <!-- ✅ Bootstrap & jQuery -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ✅ SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ✅ DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- ✅ Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ---------- AssignTo Table Style ---------- */
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
            background-color: orange;
            padding: 5px;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .status_btn:hover {
            transform: scale(1.1);
            background-color: darkorange;
        }

        #assigntomodal .modal-header {
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
        }
        #editmodal .modal-header {
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
        }
        #deadlineRequestModal .modal-header {
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
        }
        #ViewSubmissionModal .modal-header {
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
        }
        #redomodal .modal-header {
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
        }
        #reassignmodal .modal-header {
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
        }
    </style>
</head>

<body>
    <div id="assign-by-me" class="container mt-4">
        <div class="assignbutton"
            style="display:flex;flex-direction:column;justify-content:flex-end;align-items:end;text-align:end;">
            <button id="taskAddBtn" class="btn btn-primary mb-2"
                style="display:flex;flex-direction:column;align-items:end;text-align:end;">
                Assign Task
            </button>
        </div>

        <!-- Assigned Tasks Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="parentTable">
                <thead>
                    <tr class="assignto">
                        <th>S.No</th>
                        <th>Assign_To</th>
                        <th>Task_Title</th>
                        <th>Task_Description</th>
                        <th>Priority</th>
                        <th>Assigned_Date</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="assignTo">
                    <!-- Dynamic rows will appear here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- ✅ Task Assignment Modal -->
    <div class="modal fade" id="assigntomodal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="assignModalLabel">
                        <i class="fas fa-tasks me-2"></i>Assign Task
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="taskForm">

                        <!-- Assign To (HOD / Faculty / Portfolio) -->
                        <div class="mb-3">
                            <label for="assignToType" class="form-label">
                                <i class="fas fa-user-tie me-1"></i>Assign To
                            </label>
                            <select class="form-select" id="assignToType" required>
                                <option value="">-- Select Type --</option>
                                <option value="hod">HOD</option>
                                <option value="faculty">Faculty</option>
                                <?php if (isset($_SESSION['pf_dept'])): ?>
                                    <option value="<?php echo htmlspecialchars($_SESSION['pf_dept']); ?>"><?php echo htmlspecialchars($_SESSION['pf_dept']); ?></option>
                                <?php else: ?>
                                    <option value="portfolio">Portfolio</option> 
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Faculty Dept (only if Faculty selected) -->
                        <div class="mb-3 d-none" id="facultyDeptSection">
                            <label>Select Department</label>
                            <select class="form-select" id="facultyDept">
                                <option value="">-- Select Department --</option>
                            </select>
                        </div>

                        <!-- Portfolio Category (only if Portfolio selected) -->
                        <div class="mb-3 d-none" id="portfolioCategorySection">
                            <label>Select Portfolio Category</label>
                            <select class="form-select" id="portfolioCategory">
                                <option value="">-- Select Category --</option>
                            </select>
                        </div>

                        <!-- Unified Faculty Dropdown -->
                        <div class="mb-3" id="finalFacultySection">
                            <label>Select Faculty</label>
                            <select class="form-select" id="facultyFinal" multiple required>
                                <option value="">-- Select Faculty --</option>
                            </select>
                        </div>

                        <!-- Priority -->
                        <div class="mb-3">
                            <label for="priority" class="form-label">
                                <i class="fas fa-flag me-1"></i>Priority
                            </label>
                            <select class="form-select" id="priority" required>
                                <option value="">-- Select Priority --</option>
                                <option value="Important & Immediate">Important & Immediate</option>
                                <option value="Not Important & Immediate">Not Important & Immediate</option>
                                <option value="Important & Not Immediate">Important & Not Immediate</option>
                                <option value="Not Important & Not Immediate">Not Important & Not Immediate</option>
                            </select>
                        </div>

                        <!-- Existing Form Fields -->
                        <div class="mb-3">
                            <label for="some" class="form-label">
                                <i class="fas fa-heading me-1"></i>Task Title
                            </label>
                            <input type="text" class="form-control" id="some" placeholder="Enter task title" required>
                        </div>

                        <div class="mb-3">
                            <label for="taskDesc" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Task Description
                            </label>
                            <textarea class="form-control" id="taskDesc" rows="3" placeholder="Enter task description"
                                required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="startdate" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Start Date
                            </label>
                            <input type="date" class="form-control" id="startdate" min="<?= date('Y-m-d'); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="taskdl" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Deadline
                            </label>
                            <input type="date" class="form-control" id="taskdl" min="<?= date('Y-m-d'); ?>" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Assign Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Edit Task Modal -->
    <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">
                        <i class="fas fa-tasks me-2"></i>Edit Task
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="EditForm">
                        <input type="hidden" id="editid" name="editid">
                        <div class="mb-3">
                            <label for="editDepartment" class="form-label">
                                <i class="fas fa-user me-1"></i>Select
                                Department
                            </label>
                            <select class="form-select" id="editDepartment" required>
                                <option value>-- Select department
                                    --</option>
                                <option value="CSE">CSE</option>
                                <option value="EEE">EEE</option>
                                <option value="ECE">ECE</option>
                                <option value="IT">IT</option>
                                <option value="MECH">MECH</option>
                                <option value="CIVIL">CIVIL</option>
                                <option value="AI">AI</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editFaculty" class="form-label">
                                <i class="fas fa-user me-1"></i>Assign To
                                FACULTY
                            </label>
                            <select class="form-select" id="editFaculty" required>
                                <option value>-- Select Faculty --</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editsome" class="form-label">
                                <i class="fas fa-heading me-1"></i>Task
                                Title
                            </label>
                            <input type="text" class="form-control" id="editsome" placeholder="Enter task title"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="edittaskDesc" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Task
                                Description
                            </label>
                            <textarea class="form-control" id="edittaskDesc" rows="3"
                                placeholder="Enter task description" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="editstartdate" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Start
                                Date
                            </label>
                            <input type="date" class="form-control" id="editstartdate" min="<?= date('Y-m-d'); ?>"required>
                        </div>

                        <div class="mb-3">
                            <label for="editdeadline" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Deadline
                            </label>
                            <input type="date" class="form-control" id="editdeadline" min="<?= date('Y-m-d'); ?>"required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Edit Task
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- ✅ Deadline Request Action Modal -->
    <div class="modal fade" id="deadlineRequestModal" tabindex="-1" aria-labelledby="deadlineRequestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="deadlineRequestModalLabel">
                        <i class="fas fa-clock me-2"></i>Deadline Extension
                        Request
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="deadlineRequestForm">
                        <input type="hidden" id="requestTaskId">
                        <input type="hidden" id="TAId">

                        <div class="mb-4">
                            <label for="requestDeadline" class="form-label">
                                <i class="fas fa-calendar-day me-1"></i>Requested
                                Deadline
                            </label>
                            <input type="date" class="form-control" id="requestDeadline" required>
                            <div class="form-text">Selected Deadline</div>
                        </div>

                        <div class="mb-4">
                            <label for="requestReason" class="form-label">
                                <i class="fas fa-comment-alt me-1"></i>Reason
                                for Extension
                            </label>
                            <textarea class="form-control" id="requestReason" rows="4"
                                placeholder="Enter the reason provided for deadline extension..." required></textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" id="rejectBtn">
                                <i class="fas fa-times me-2"></i>Reject
                            </button>
                            <button type="button" class="btn btn-primary" id="approveBtn">
                                <i class="fas fa-check me-2"></i>Approve
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- Submission proof-->
    <div class="modal fade" id="ViewSubmissionModal" tabindex="-1" aria-labelledby="ViewSubmissionModalLabel"
    aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="ViewSubmissionModalLabel">
                            <i class="fas fa-eye me-2"></i>View Task Submission
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Hidden IDs -->
                        <input type="hidden" id="view_ti">
                        <input type="hidden" id="view_tai">

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-align-left me-1"></i>Description of Work Completed:
                            </label>
                            <p id="view_explanation" class="form-control-plaintext border p-2 rounded bg-light">
                                <!-- Fetched explanation will appear here -->
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-file-image me-1"></i>Proof of Completion:
                            </label>
                            <div id="view_proof" class="border p-2 rounded bg-light text-center">
                                <!-- Proof (image/link/file) will appear here -->
                                <span class="text-muted">No proof uploaded</span>
                            </div>
                        </div>

                        <!--  NEW FEEDBACK FIELD ADDED HERE -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-comment-dots me-1"></i>Write your feedback / Reviews:
                            </label>
                            <textarea id="view_feedback" class="form-control" rows="3"
                                placeholder="Enter your feedback here"></textarea>
                        </div>
                        <!-- END NEW FIELD -->

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="rejsub">
                            <i class="fas fa-times me-2"></i>Reject
                        </button>
                        <button type="button" class="btn btn-primary" id="appsub">
                            <i class="fas fa-check me-2"></i>Approve
                        </button>
                    </div>

                </div>
            </div>
        </div>
    <!--redo deadline set modal-->
    <div class="modal fade" id="redomodal" tabindex="-1" aria-labelledby="redoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="redoModalLabel">
                        <i class="fas fa-tasks me-2"></i>Redo Task
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="redoForm">
                        <input type='hidden' id='redo_task_id'>
                        <input type='hidden' id='redo_task_ass_id'>
                        <div class="mb-3">
                            <label for="new_deadline" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Set New Deadline
                            </label>
                            <input type="date" class="form-control" id="new_deadline" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Redo Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reassign Task Modal -->
    <div class="modal fade" id="reassignmodal" tabindex="-1" aria-labelledby="reassignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="reassignModalLabel">
                        <i class="fas fa-tasks me-2"></i>ReAssign Task
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="reassignForm">
                        <input type="hidden" id="reassign_task_id">
                        <input type="hidden" id="reassign_task_ass_id">

                        <!-- Assign To (HOD / Faculty / Portfolio) -->
                        <div class="mb-3">
                            <label for="reassignAssignToType" class="form-label">
                                <i class="fas fa-user-tie me-1"></i>Re assign To
                            </label>
                            <select class="form-select" id="reassignAssignToType" required>
                                <option value="">-- Select Type --</option>
                                <option value="hod">HOD</option>
                                <option value="faculty">Faculty</option>
                                <option value="portfolio">Portfolio</option>
                            </select>
                        </div>

                        <!-- Department (same ID retained) -->
                        <div class="mb-3 d-none" id="reassignDeptSection">
                            <label>Select Department</label>
                            <select class="form-select" id="reassignToDepartment">
                                <option value="">-- Select Department --</option>
                            </select>
                        </div>

                        <!-- Portfolio Category (matching assign form, new wrapper but NO new IDs) -->
                        <div class="mb-3 d-none" id="reassignPortfolioSection">
                            <label>Select Portfolio Category</label>
                            <select class="form-select" id="reassignPortfolioCategory">
                                <option value="">-- Select Category --</option>
                            </select>
                        </div>

                        <!-- Unified Faculty Dropdown (Existing ID MUST remain) -->
                        <div class="mb-3" id="reassignFacultySection">
                            <label>Select Faculty</label>
                            <select class="form-select" id="reassignToFaculty" required>
                                <option value="">-- Select Faculty --</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="redeadline" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Deadline
                            </label>
                            <input type="date" class="form-control" id="redeadline" min="<?= date('Y-m-d'); ?>"required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>ReAssign Task
                            </button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <script>
        let tableAssign;

        $(document).ready(function () {
            // Initialize DataTable once
            tableAssign = $('#parentTable').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                searching: true,
                ordering: false,
                info: true
            });

            loadAssignedTasks(tableAssign);

            // Show assign task modal
            $('#taskAddBtn').on('click', function () {
                $('#some').val('');
                $('#facultyFinal').val('');
                $('#taskDesc').val('');
                $('#startdate').val('');
                $('#taskdl').val('');
                $('#assignToType').val('');
                $('#facultyDept').val('');
                $('#priority').val('');
                $('#portfolioCategory').val('');
                const modalEl = $('#assigntomodal')[0];
                document.body.appendChild(modalEl);
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            });

            // Assign task modal submission
            $('#taskForm').on('submit', function (e) {
                e.preventDefault();
                console.log("Form submitted");
                $.post('backend/api.php', {
                    action: 'assigntasks_p',
                    title: $('#some').val(),
                    fac: $('#facultyFinal').val(),
                    desc: $('#taskDesc').val(),
                    date: $('#startdate').val(),
                    deadline: $('#taskdl').val()
                }, function (res) {
                    if (res.response === 'success') {
                        Swal.fire({
                            title: "Task assigned",
                            text: res.message,
                            icon: "success"
                        });
                    } else {
                        Swal.fire({
                            title: "Oops",
                            text: res.message || "Unknown error occurred.",
                            icon: "error"
                        });
                    }

                    $('#assigntomodal').modal('hide');
                    loadAssignedTasks(tableAssign);
                }, "json");
            });

            //script for assign to modal

            // When Assign To changes
            $("#assignToType").on("change", function () {
                let type = $(this).val();

                // Hide all sections first
                $("#hodSection").addClass("d-none");
                $("#facultyDeptSection").addClass("d-none");
                $("#facultyListSection").addClass("d-none");
                $("#portfolioCategorySection").addClass("d-none");
                $("#portfolioFacultySection").addClass("d-none");

                if (type === "hod") {
                    $("#hodSection").removeClass("d-none");
                    $.post('backend/api.php', {action:'filterhod', role:'HOD'}, function(res){
                        const hlist = $('#facultyFinal');
                        hlist.find('option:not(:first)').remove();
                        res.forEach(r => {
                            hlist.append(`<option value="${r.user_id}">${r.name} (${r.dept})</option>`);
                        });
                    })
                }

                else if (type === "faculty") {
                    $("#facultyDeptSection").removeClass("d-none");
                    $.post('backend/api.php', {action:'filterdept'}, function(res){
                        const deptlist = $('#facultyDept');
                        deptlist.find('option:not(:first)').remove();
                        res.forEach(r => {
                            deptlist.append(`<option value="${r.deptshort}">${r.dname}</option>`);
                        });
                    })
                }

                else{
                    $.post('backend/api.php', {action:'filterport_staffs', pfdept: $('#assignToType').val()}, function(res){
                        const portlist = $('#facultyFinal');
                        portlist.find('option:not(:first)').remove();
                        res.forEach(r => {
                            portlist.append(`<option value="${r.user_id}">${r.name}</option>`);
                        });
                    })
                }
            });

            // When a department is selected → show faculty list
            $("#facultyDept").on("change", function () {
                $("#facultyListSection").removeClass("d-none");
                var facdept = $('#facultyDept').val();
                $.post('backend/api.php', {action:'filterfaculty', dept: facdept}, function(res){
                    const faclist = $('#facultyFinal');
                    faclist.find('option:not(:first)').remove();
                    res.forEach(r => {
                        faclist.append(`<option value="${r.user_id}">${r.name}</option>`);
                    });
                })
            });

            //script for reassign dropdown
            // When Assign To changes
            $("#reassignAssignToType").on("change", function () {
                let type = $(this).val();
                $("#reassignDeptSection").addClass("d-none");
                $("#reassignFacultySection").addClass("d-none");
                $("#reassignPortfolioSection").addClass("d-none");

                if (type === "hod") {
                    $("#reassignFacultySection").removeClass("d-none");
                    $.post('backend/api.php', {action:'filterhod', role:'HOD'}, function(res){
                        const rhlist = $('#reassignToFaculty');
                        rhlist.find('option:not(:first)').remove();
                        res.forEach(r => {
                            rhlist.append(`<option value="${r.user_id}">${r.name} (${r.dept})</option>`);
                        });
                    })
                }

                if (type === "faculty") {
                    $("#reassignDeptSection").removeClass("d-none");
                    $.post('backend/api.php', {action:'filterdept'}, function(res){
                        const rdeptlist = $('#reassignToDepartment');
                        rdeptlist.find('option:not(:first)').remove();
                        res.forEach(r => {
                            rdeptlist.append(`<option value="${r.deptshort}">${r.dname}</option>`);
                        });
                    })
                }

                if (type === "portfolio") {
                    $("#reassignPortfolioSection").removeClass("d-none");
                    $.post('backend/api.php', {action:'filterport'}, function(res){
                        const rportlist = $('#reassignPortfolioCategory');
                        rportlist.find('option:not(:first)').remove();
                        res.forEach(r => {
                            rportlist.append(`<option value="${r.pname}">${r.pname}</option>`);
                        });
                    })
                }
            });

            // When a department is selected → show faculty list
            $("#reassignToDepartment").on("change", function () {
                $("#reassignFacultySection").removeClass("d-none");
                var rfacdept = $('#reassignToDepartment').val();
                $.post('backend/api.php', {action:'filterfaculty', dept: rfacdept}, function(res){
                    const rfaclist = $('#reassignToFaculty');
                    rfaclist.find('option:not(:first)').remove();
                    res.forEach(r => {
                        rfaclist.append(`<option value="${r.user_id}">${r.name}</option>`);
                    });
                })
            });

            // When portfolio category selected → show faculty list
            $("#reassignPortfolioCategory").on("change", function () {
                $("#reassignFacultySection").removeClass("d-none");
                var rfacport = $('#reassignPortfolioCategory').val();
                $.post('backend/api.php', {action:'filterfaculty', dept: rfacport}, function(res){
                    const rplist = $('#reassignToFaculty');
                    rplist.find('option:not(:first)').remove();
                    res.forEach(r => {
                        rplist.append(`<option value="${r.user_id}">${r.name} (${r.dept})</option>`);
                    });
                })
            });

        });

        function setDateMinToToday(selector) {
            const t = new Date();               // Current date-time
            const y = t.getFullYear();          // Year (2025)
            const m = String(t.getMonth() + 1).padStart(2, '0'); // Month (01–12)
            const d = String(t.getDate()).padStart(2, '0');      // Day (01–31)

            const today = `${y}-${m}-${d}`;     // Format YYYY-MM-DD

            $(selector).attr('min', today);     // Set HTML <input type="date"> min value
        }


       // date restriction for assign

        const today = new Date().toISOString().split('T')[0];
        $('#startdate').attr('min', today);
        $('#taskdl').attr('min', today);
        $('#startdate').on('change', function() {
            const startDate = this.value;
            $('#taskdl').attr('min', startDate);
            if ($('#taskdl').val() < startDate) {
                $('#taskdl').val(startDate);
            }
        });

        
        // date restriction for edit assign

        const edittoday = new Date().toISOString().split('T')[0];
        $('#editstartdate').attr('min', edittoday);
        $('#editdeadline').attr('min', edittoday);
        $('#editstartdate').on('change', function() {
            const startDate = this.value;
            $('#editdeadline').attr('min', startDate);
            if ($('#editdeadline').val() < startDate) {
                $('#editdeadline').val(startDate);
            }
        });

        // date restriction for  re assign

        const reassigntoday = new Date().toISOString().split('T')[0];
        $('#restartdate').attr('min', reassigntoday);
        $('#redeadline').attr('min', reassigntoday);
        $('#restartdate').on('change', function() {
            const startDate = this.value;
            $('#redeadline').attr('min', startDate);
            if ($('#redeadline').val() < startDate) {
                $('#redeadline').val(startDate);
            }
        });


        // Set min on page load
        setDateMinToToday('#startdate, #taskdl, #editstartdate, #editdeadline, #restartdate, #redeadline, #new_deadline');

        // Also set min whenever a modal opens
        $('#assigntomodal, #editmodal, #reassignmodal, #redomodal').on('show.bs.modal', function () {
            setDateMinToToday('#startdate, #taskdl, #editstartdate, #editdeadline, #restartdate, #redeadline, #new_deadline');
        });

        // Validate on change
        $(document).on('change', '#startdate, #taskdl, #editstartdate, #editdeadline, #restartdate, #redeadline, #new_deadline', function () {
            const val = $(this).val();
            const min = $(this).attr('min');
            if (val && min && val < min) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Date',
                    text: 'Past dates are not allowed!'
                });
                $(this).val('');
            }
        });

        // Function to load assigned tasks
        function loadAssignedTasks(table) {
            $.post('backend/api.php', { action: 'readAssignedTasks' }, function (res) {
                table.clear();

                res.forEach((r, i) => {
                    let statusHtml = '';
                    let actionHtml = '';

                    if (r.status.toLowerCase() === 'completed') {
                        statusHtml = `<span class='status_btn_inactive' style='background-color:green; color:white; padding:4px 12px; border-radius:20px; font-size:0.8em;'>${r.status}</span>`;
                    }
                    else if (r.status.toLowerCase() === 'closed') {
                        statusHtml = `<span class='status_btn_inactive' style='background-color: orange; color:white; padding:4px 12px; border-radius:20px; font-size:0.8em;'>${r.status}</span>`;
                    }
                    else if (r.status.toLowerCase() === 'overdue' && r.action.toLowerCase() == 'reassigned') {
                        statusHtml = `<span class='status_btn' style='background-color:red; color:white; padding:4px 12px; border-radius:20px; font-size:0.8em;'>${r.status}</span>`;
                    }
                    else if (r.status.toLowerCase() === 'overdue' && r.action.toLowerCase() != 'reassigned') {
                        statusHtml = `<span class='status_btn' style='background-color:red; color:white; padding:4px 12px; border-radius:20px; font-size:0.8em;' onclick="showbtns(this)">${r.status}</span>
                            <div class="btns">
                                <button onclick="reassign(${r.task_assignment_id}, ${r.task_id})" class="reassign" title="Re assign">Re assign</button>
                                <button class="redo" onclick="redo(${r.task_assignment_id}, ${r.task_id})" title='Redo'>Redo</button>
                            </div>`;
                    }
                    else if (r.status.toLowerCase() === 'assigned') {
                        statusHtml = `<span class='status_btn_inactive' style='background-color:blue; color:white; padding:4px 12px; border-radius:20px; font-size:0.8em;'>${r.status}</span>`;
                    }
                    else {
                        statusHtml = `<span class='status_btn_inactive' style='background-color:orange; color:white; padding:4px 12px; border-radius:20px; font-size:0.8em;'>${r.status}</span>`;
                    }

                    if (r.action && r.action.toLowerCase() === 'deadline extension requested') {
                        actionHtml = `
                                <button class='btn btn-sm btn-warning deadline-request-btn' 
                                        onclick='showDeadlineRequestModal(${r.task_assignment_id})'
                                        data-task-id="${r.task_assignment_id}">
                                    <i class="fa-solid fa-clock me-1"></i>Review deadline Request
                                </button>`;
                    }
                    else if (r.action && r.action.toLowerCase() === 'submitted') {
                        actionHtml = `
                                <button class='btn btn-sm btn-success deadline-request-btn' 
                                        onclick='showSubmissionModal(${r.task_assignment_id})'
                                        data-task-id="${r.task_assignment_id}">
                                    <i class="fa-solid fa-clock me-1"></i>Review Submission
                                </button>
                            `;
                    }
                    else if (r.action && r.action.toLowerCase() === 'forwarding') {
                        actionHtml = `
                                <button class='btn btn-sm btn-warning deadline-request-btn' 
                                        onclick='showtaskfwdingmodal(${r.task_assignment_id})'
                                        data-task-id="${r.task_assignment_id}">
                                    <i class="fa-solid fa-clock me-1"></i>Task Forwarding
                                </button>
                            `;
                    }
                    else {
                        actionHtml = r.action || '';
                    }

                    if (r.status.toLowerCase() == 'assigned') {
                        statusHtml += `
                                <button class='btn btn-sm btn-primary ms-2 editBtn' 
                                        onclick='edittask(${r.task_assignment_id})'>
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button class='btn btn-sm btn-danger ms-1 deleteBtn' 
                                        onclick='deletetask(${r.task_assignment_id}, ${r.task_id})'>
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            `;
                    }

                    table.row.add([
                        i + 1,
                        r.name,
                        r.task_title,
                        r.task_description,
                        r.Priority,
                        r.start_date,
                        r.deadline,
                        statusHtml,
                        actionHtml
                    ]);
                });

                table.draw();
            }, 'json');
        }


        $('#editDepartment').on('change', function () {
            if ($('#editDepartment').val() != '') {
                $.post('backend/api.php', { action: 'filterfaculty', dept: $('#editDepartment').val() }, function (res) {
                    const facultyname = $('#editFaculty');
                    facultyname.find('option:not(:first)').remove();
                    res.forEach(r => {
                        facultyname.append(`<option value="${r.name}">${r.name}</option>`);
                    });
                }, "json");
            }
        });


        function redo(task_ass_id, task_id) {
            $('#redo_task_id').val(task_id);
            $('#redo_task_ass_id').val(task_ass_id);
            $('#new_deadline').val('');
            const modalEle = $('#redomodal')[0];
            document.body.appendChild(modalEle); // ensure it's in <body>
            bootstrap.Modal.getOrCreateInstance(modalEle).show();
        }

        $('#redoForm').on('submit', function (e) {
            e.preventDefault();
            $.post('backend/api.php', { action: 'redo', task_id: $('#redo_task_id').val(), task_ass_id: $('#redo_task_ass_id').val(), new_deadline: $('#new_deadline').val() }, function (res) {
                Swal.fire({
                    title: 'Redo task',
                    text: 'The task has been set to redo',
                    icon: 'success'
                });
                loadAssignedTasks(tableAssign);
            }, "json");
            $('#redomodal').modal('hide');
            loadAssignedTasks(tableAssign);
        });

        function reassign(task_ass_id, task_id) {
            $('#reassign_task_id').val(task_id);
            $('#reassign_task_ass_id').val(task_ass_id);
            $('#reassignToFaculty').val('');
            $('#redeadline').val('');
            $('#reassignAssignToType').val('');
            $('#reassignToDepartment').val('');
            $('#reassignPortfolioCategory').val('');
            const modalEle = $('#reassignmodal')[0];
            document.body.appendChild(modalEle); // ensure it's in <body>
            bootstrap.Modal.getOrCreateInstance(modalEle).show();
        }

        $('#reassignForm').on('submit', function (e) {
            e.preventDefault();
            console.log("hi");
            $.post('backend/api.php', { action: 'reassign', refac: $('#reassignToFaculty').val(), task_id: $('#reassign_task_id').val(), task_ass_id: $('#reassign_task_ass_id').val(), re_deadline: $('#redeadline').val() }, function (res) {
                Swal.fire({
                    title: 'Reassign task',
                    text: 'The task has been reassigned successfully !',
                    icon: 'success'
                });
                loadAssignedTasks(tableAssign);
            }, "json");
            $('#reassignmodal').modal('hide');
            loadAssignedTasks(tableAssign);
        });

        // Function to show deadline request modal
        function showDeadlineRequestModal(taskId) {
            const modalEl = $('#deadlineRequestModal')[0];
            document.body.appendChild(modalEl);

            $.post('backend/api.php', { action: 'getdlreq', tid: taskId }, function (res) {
                console.log(res);
                if (res) {
                    $('#requestTaskId').val(res.dr_id);
                    $('#requestDeadline').val(res.requested_deadline);
                    $('#requestReason').val(res.Reason);
                }

                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }, "json").fail(function (xhr, status, error) {
                console.error("Error fetching deadline request data:", error);
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            });
        }

        function showtaskfwdingmodal(tasid) {
            $.post('backend/api.php', { action: 'process_fwd_req', id: tasid }, function (res) {
                // Check for errors
                if (res.response === 'error') {
                    Swal.fire({
                        title: 'Error',
                        text: res.message,
                        icon: 'error'
                    });
                    return;
                }

                if (res) {
                    Swal.fire({
                        title: 'Forward task',
                        html: 'Shall the task assigned to <b>' + res.requested_by +
          '</b> be forwarded to <b>' + res.forward_to + '</b>?<br>' +
          '<b>Reason:</b> ' + res.fwd_reason,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, forward',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post('backend/api.php',
                                {
                                    action: 'approve_forward',
                                    id: tasid
                                }, function (r2) {
                                    // Check for errors in approval
                                    if (r2.response === 'error') {
                                        Swal.fire("Error", r2.message, "error");
                                        return;
                                    }

                                    if (r2 && r2.request_id) {
                                        forwardTaskAfterApproval(r2.request_id, r2.task_id, r2.task_assignment_id, r2.forward_to);
                                        Swal.fire("Forwarded!", "Task forwarded successfully", "success");
                                        // Reload tasks or refresh UI
                                        loadAssignedTasks(tableAssign);
                                    } else {
                                        Swal.fire("Error", "Failed to forward task", "error");
                                    }
                                }, "json").fail(function (xhr, status, error) {
                                    Swal.fire("Error", "Request failed: " + error, "error");
                                });
                        }
                        else {
                            $.post('backend/api.php', { action: 'reject_forward', id: tasid }, function (r2) {
                                if (r2.response === 'error') {
                                    Swal.fire("Error", r2.message, "error");
                                } else {
                                    Swal.fire({
                                        title: 'Forward Rejected',
                                        text: 'The task forward to ' + res.forward_to + ' has been rejected!',
                                        icon: 'info'
                                    });
                                    // Reload tasks or refresh UI
                                    loadAssignedTasks(tableAssign);
                                }
                            }, "json").fail(function (xhr, status, error) {
                                Swal.fire("Error", "Request failed: " + error, "error");
                            });
                        }
                    });
                }
            }, "json").fail(function (xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to load forward request: ' + error,
                    icon: 'error'
                });
            });
        }
        function showSubmissionModal(taskID) {
            const modalEl = $('#ViewSubmissionModal')[0];
            document.body.appendChild(modalEl);

            $.post('backend/api.php', { action: 'getsub', tId: taskID }, function (res) {

                if (res) {
                    $('#view_tai').val(taskID);
                    $('#view_explanation').html(res.explanation);

                    if (res.proof && res.proof.trim() !== '') {

                        let filePath = res.proof.trim();

                        // *********** IMPORTANT FIX ***********
                        // Convert Windows path -> Web URL
                        // Example:
                        // C:/tih-xampp/htdocs/TMSR/uploads/file.jpg
                        // becomes:
                        // /TMSR/uploads/file.jpg

                        filePath = filePath.replace(/^[A-Za-z]:[\\/]+tih-xampp[\\/]+htdocs/, '');
                        filePath = filePath.replace(/\\/g, '/'); // convert backslashes to URLs
                        filePath = "/" + filePath.replace(/^\/+/, ''); // Ensure starting slash

                        // Final URL to load in browser
                        const fileUrl = filePath;

                        // Get extension
                        const fileExt = fileUrl.split('.').pop().toLowerCase();

                        let proofHTML = '';

                        // ------- IMAGE PREVIEW -------
                        if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(fileExt)) {
                            proofHTML = `
                        <a href="${fileUrl}" target="_blank">
                            <img src="${fileUrl}" alt="Proof Image"
                                class="img-fluid rounded shadow-sm" style="max-height:300px;">
                        </a>
                        <div class="mt-2">
                            <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-primary">
                                View Full Image
                            </a>
                        </div>
                    `;
                        }

                        // ------- PDF PREVIEW -------
                        else if (fileExt === 'pdf') {
                            proofHTML = `
                        <iframe src="${fileUrl}" width="100%" height="400px"
                            class="border rounded"></iframe>
                        <div class="mt-2">
                            <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-primary">
                                Open PDF in new tab
                            </a>
                        </div>
                    `;
                        }

                        // ------- OTHER FILE TYPES -------
                        else {
                            proofHTML = `
                        <div class="p-3">
                            <i class="fas fa-file me-2"></i>
                            <span>File uploaded:</span>
                            <a href="${fileUrl}" target="_blank" class="ms-2 text-decoration-none">
                                Open / Download File
                            </a>
                        </div>
                    `;
                        }

                        $('#view_proof').html(proofHTML);

                    } else {
                        $('#view_proof').html('<span class="text-muted">No proof uploaded</span>');
                    }
                }

                bootstrap.Modal.getOrCreateInstance(modalEl).show();

            }, "json").fail(function (xhr, status, error) {
                console.error("Error fetching task submission data:", error);
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            });
        }


        $('#appsub').on('click', function () {

            const tai = $('#view_tai').val();
            $('#ViewSubmissionModal').modal('hide');


            Swal.fire({
                title: "Approve Submission?",
                text: "Are you sure you want to approve this submission?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, Approve",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('backend/api.php', {
                        action: 'approvesub',
                        tai: tai,
                        feedback: $('#view_feedback').val()
                    }, function (res) {
                        if (res.response === 'success') {
                            Swal.fire({
                                title: "Approved!",
                                text: "Submission approved successfully",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $('#ViewSubmissionModal').modal('hide');
                            loadAssignedTasks(tableAssign);
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: res.message || "Failed to approve request",
                                icon: "error"
                            });
                        }
                    }, "json").fail(function (xhr, status, error) {
                        Swal.fire({
                            title: "Error",
                            text: "Request failed: " + error,
                            icon: "error"
                        });
                    });
                }
            });
            loadAssignedTasks(tableAssign);
        });

        // Reject button handler - FIXED
        $('#rejsub').on('click', function () {
            const TaI = $('#view_tai').val();
            $('#ViewSubmissionModal').modal('hide');

            Swal.fire({
                title: "Reject Request?",
                text: "Are you sure you want to reject this submission?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Reject",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#dc3545"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('backend/api.php', {
                        action: 'rejectsub',
                        TaI: TaI, // Using drId consistently
                        feedback: $('#view_feedback').val()
                    }, function (res) {
                        if (res.response === 'success') {
                            Swal.fire({
                                title: "Rejected!",
                                text: "Submission of completed task rejected",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                            loadAssignedTasks(tableAssign);
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: res.message || "Failed to reject request",
                                icon: "error"
                            });
                        }
                    }, "json").fail(function (xhr, status, error) {
                        Swal.fire({
                            title: "Error",
                            text: "Request failed: " + error,
                            icon: "error"
                        });
                    });
                }
            });
            loadAssignedTasks(tableAssign);
        });


        // Close modal on escape key or backdrop click
        $('#deadlineRequestModal').on('hidden.bs.modal', function () {
            $('#deadlineRequestForm')[0].reset();
            $('#requestTaskId').val('');
        });

        // Delete task function
        function deletetask(taskAssId, taskId) {
        Swal.fire({
            title: 'Delete task',
            text: 'Do you really want to delete this task?',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'No',
            confirmButtonText: 'Yes, I want to delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('backend/api.php', {
                        action: 'deletetask',
                        taskAsid: taskAssId,
                        taskid: taskId
                    },
                    function(res) {

                        Swal.fire({
                            title: 'Delete task',
                            text: 'Task deleted successfully',
                            icon: 'success'
                        });

                        // REFRESH HERE AFTER DELETION IS COMPLETE
                        loadAssignedTasks(tableAssign);
                    },
                    "json"
                );
            }
        });
    }

        // Open edit modal
        function edittask(id) {
            const modalEl = $('#editmodal')[0];
            document.body.appendChild(modalEl);

            $.post('backend/api.php', { action: 'fetchrow', id: id }, function (res) {
                $("#editid").val(res.task_assignment_id);
                $("#editDepartment").val(res.dept?.trim()).prop('disabled', true);
                // Faculty - single value for this record
                let facultySelect = $("#editFaculty");
                facultySelect.empty(); // clear previous options
                if (res.name) {
                    facultySelect.append(`<option value="${res.name}">${res.name}</option>`);
                } else {
                    facultySelect.append('<option value="">-- No Faculty Assigned --</option>');
                }
                facultySelect.prop('disabled', true);
                $("#editsome").val(res.task_title);
                $("#edittaskDesc").val(res.task_description);
                $("#editstartdate").val(res.start_date);
                $("#editdeadline").val(res.deadline);
            }, "json");

            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }

        // Edit task function
        $("#EditForm").on("submit", function (e) {
            e.preventDefault();
            $.post("backend/api.php", {
                action: "editTask",
                id: $("#editid").val(),
                department: $("#editDepartment").val(),
                faculty: $("#editFaculty").val(),
                title: $("#editsome").val(),
                description: $("#edittaskDesc").val(),
                startdate: $("#editstartdate").val(),
                deadline: $("#editdeadline").val()
            }, function (res) {
                Swal.fire({
                    title: 'Update task',
                    text: 'Task updated successfully',
                    icon: 'success'
                })
                $('#editmodal').modal('hide');
                loadAssignedTasks(tableAssign);
            }, "json");
        });
        //open btns under overdue
        function showbtns(el) {
            document.querySelectorAll('.btns').forEach(m => m.style.display = 'none');
            const btns = el.nextElementSibling;
            btns.style.display = 'flex';
        }
        //close the modals on clicking anywhere outside the buttons
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.status_btn', '.btns').length) {
                $('.btns').hide();
            }
        });

        $('#rejectBtn').on('click', function () {
            const drId = $('#requestTaskId').val();
            $('#deadlineRequestModal').modal('hide');
            Swal.fire({
                title: "Reject Request?",
                text: "Are you sure you want to reject this deadline extension?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Reject",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#dc3545"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('backend/api.php', {
                        action: 'rejectreqdl',
                        drId: drId // Using drId consistently
                    }, function (res) {
                        if (res.response === 'success') {
                            Swal.fire({
                                title: "Rejected!",
                                text: "Deadline extension rejected",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                            loadAssignedTasks(tableAssign);
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: res.message || "Failed to reject request",
                                icon: "error"
                            });
                        }
                    }, "json").fail(function (xhr, status, error) {
                        Swal.fire({
                            title: "Error",
                            text: "Request failed: " + error,
                            icon: "error"
                        });
                    });
                }
            });


        });
        // Approve button handler
        $('#approveBtn').on('click', function (event) {
            event.preventDefault();
            const drId = $('#requestTaskId').val();
            $('#deadlineRequestModal').modal('hide');
            Swal.fire({
                title: "Approve Request?",
                text: "Are you sure you want to approve this deadline extension?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, Approve",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('backend/api.php', {
                        action: 'approvereqdl',
                        drId: drId,
                        extndl: $("#requestDeadline").val()
                    }, function (res) {
                        if (res.response === 'success') {
                            Swal.fire({
                                title: "Approved!",
                                text: "Deadline extension approved successfully",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                            loadAssignedTasks(tableAssign);
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: res.message || "Failed to approve request",
                                icon: "error"
                            });
                        }
                    }, "json").fail(function (xhr, status, error) {
                        Swal.fire({
                            title: "Error",
                            text: "Request failed: " + error,
                            icon: "error"
                        });
                    });
                }
            });
        });
    </script>
</body>

</html>