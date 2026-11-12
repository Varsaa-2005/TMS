<html>
<!--HOD and faculties my tasks-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            cursor: pointer;
        }

        table {
            text-align: center;
            width: 100%;
            border-collapse: collapse;
            color: black;
            font-size: medium;
        }

        .headrow {
            color: white;
            font-weight: bold;
            background: linear-gradient(to right, rgb(28, 159, 28), rgb(1, 119, 159));
        }

        #task_table tbody tr:nth-child(even) {
            background-color: rgb(224, 224, 253);
        }

        #task_table tbody tr:nth-child(odd) {
            background-color: white;
        }

        td,
        th {
            text-align: center;
            border: 1px solid white !important;
            padding: 5px;
        }

        .status_btn {
            display: inline-block;
            background-color: orange;
            padding: 5px;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: transform 0.1s ease, background-color 0.2s ease;
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

        .cmp {
            border:none;
            margin: 2px;
            border-radius: 5px;
            margin-right: 8px;
            transition: transform 0.5s;
        }

        .cmp:hover {
            transform: scale(1.2);
        }

        .fwd {
            border:none;
            margin: 2px;
            border-radius: 5px;
            transition: transform 0.5s;
        }

        .fwd:hover {
            transform: scale(1.2);
        }

        .req {
            border:none;
            margin: 2px;
            border-radius: 5px;
            transition: transform 0.5s;
            margin-right: 8px;
        }

        .req:hover {
            transform: scale(1.2);
        }

        .btns {
            margin-top: 10px;
            display: none;
            justify-content: center;
        }

        .mod {
            /* keep element in DOM for animation */
            display: block;
            visibility: hidden;
            pointer-events: none;
            height: auto;
            position: fixed;
            width: 400px;
            z-index: 3005 !important;
            /* above everything */
            border-radius: 10px;
            border: 1px solid white;
            top: 50%;
            left: 50%;
            transform: translateX(-50%) translateY(-50%) scale(0.9);
            background-color: white;
            color: black;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
            opacity: 0;
            transition: opacity 0.25s ease, transform 0.25s ease;
        }

        .mod.show {
            visibility: visible;
            pointer-events: auto;
            opacity: 1;
            transform: translateX(-50%) translateY(-50%) scale(1);
        }

        .backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 3000;
            /* just below custom modals */
            display: none;
        }

        /* ensure any bootstrap modal (if used) is above all */
        .modal {
            z-index: 4000 !important;
            border-radius: 0px;
        }

        .modal-backdrop {
            z-index: 3990 !important;
        }

        .topdiv {
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
            background: linear-gradient(to right, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 15px 20px;
            border-bottom: none;
        }

        .close {
            float: right;
            color: black;
            right: 2px;
            border-radius: 50%;
            background-color: white;
            font-size: large;
        }

        .fwd_mod_btn {
            position: relative;
            border-color: white;
            color: black;
            background-color: #4E65FF;
            padding: 10px;
            left: 75%;
            border-radius: 10px;
        }

        .sub_mod_btn {
            position: relative;
            border-color: white;
            color: black;
            background-color: rgb(4, 163, 4);
            padding: 10px;
            left: 75%;
            border-radius: 10px;
        }

        .div_pos {
            padding: 20px;
            background-color: white;
            position: fixed;
            top: 40%;
            height: auto;
            border-color: rgb(250, 248, 248);
            border-style: solid;
            border-width: 2px;
            border-radius: 10px;
        }

        #fwdmod .modal-header {
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
        }
        #submod .modal-header {
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
        }
        #reqmod .modal-header {
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
        }
    </style>
</head>

<body>
    <!--Table -->
    <div class="table-responsive" id="tab_task">
        <table id="task_table">
            <thead>
                <tr class="headrow">
                    <th>S.No</th>
                    <th>Assigned_by</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Priority</th>
                    <th>Assigned_date</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
        
            </tbody>
        </table>
    </div>

    <!-- Forward Modal  -->
    <div class="modal fade" id="fwdmod" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-share-square me-2"></i>Forward To
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="fwdform">
                        <input type="hidden" id="t_id">
                        <input type="hidden" id="t_assid">

                        <label for="dept" class="form-label">Choose the department</label>
                        <select name="dept" id="dept" class="form-select" required>
                            <option disabled selected value>--Select department--</option>
                        </select>

                        <br>

                        <label for="ph_fac_fwd" class="form-label">Choose faculty</label>
                        <select name="ph_fac_fwd_name" id="ph_fac_fwd" class="form-select" required>
                            <option disabled selected value>--Select Faculty--</option>
                        </select>

                        <br>

                        <!-- NEW FIELD FOR REASON ADDED HERE -->
                        <label for="fwd_reason" class="form-label">Reason behind task forwarding</label>
                        <textarea id="fwd_reason" name="fwd_reason" class="form-control" rows="3" required
                            placeholder="Enter the reason"></textarea>

                        <br>
                        <!-- END NEW FIELD -->

                        <!-- Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Forward</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <!--complete form-->
    <div class="modal fade" id="submod" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle me-2"></i>Submit
                        completed task
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="subform">
                        <input type="hidden" name="sub_taskid" id="sub_taskid">
                        <input type="hidden" name="sub_taskassid" id="sub_taskassid">

                        <label for="proof" class="form-label">Submit the
                            proof of Completion</label>
                        <input type="file" name="proof" id="proof" class="form-control">

                        <br>

                        <label for="exp" class="form-label">Description of the work completed</label>
                        <textarea class="form-control" name="exp" id="exp" required style="height:70px;"></textarea>

                        <br>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Request deadline extension form-->
    <div class="modal fade" id="reqmod" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-clock me-2"></i>Request for
                        Extension
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="reqform">
                        <input type="hidden" name="dl_taskid" id="dl_taskid">
                        <input type="hidden" name="dl_taskassid" id="dl_taskassid">

                        <label for="deadline" class="form-label">Select
                            deadline</label>
                        <input type="date" style="width:350px;" name="deadline" id="deadline_date" class="form-control">

                        <br>

                        <label for="reason" class="form-label">Reason</label>
                        <input type="text" style="width:350px; height:70px;" name="reason" id="reason"
                            class="form-control" required>

                        <br>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary">Request</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="backdrop" id="modalBackdrop"></div>
</body>

<script>

    let table;
    
    $(document).ready(function () {
        $.post('backend/api.php', { action: 'notify_deadline' }, function(res){
                console.log("Reminder Mail Status:", res);
            }, 'json');

            // set min date for extension
        setDateMinToToday('#deadline_date');
        
        table = $('#task_table').DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            searching: true,
            ordering: false,
            info: true
        });
        loadtasks(table);

        $.post('backend/api.php', {action:'filterdept'}, function(res){
            const deplist = $('#dept');
            deplist.find('option:not(:first)').remove();
            res.forEach(r => {
                deplist.append(`<option value="${r.deptshort}">${r.dname}</option>`);
            });
        })
    });

    // Function to close modals
    function closeModal(modalId) {
        $('#' + modalId).removeClass('show');
        $('#modalBackdrop').hide();
    }

    // Close any open custom modal when backdrop is clicked
    $('#modalBackdrop').on('click', function () {
        ['fwdmod', 'submod', 'reqmod'].forEach(function (id) { $('#' + id).removeClass('show'); });
        $(this).hide();
    });

    //set today is the minimum date that can be selected in deadline 
    function setDateMinToToday(selector) {
        const t = new Date();               // Current date-time
        const y = t.getFullYear();          // Year (2025)
        const m = String(t.getMonth() + 1).padStart(2, '0'); // Month (01–12)
        const d = String(t.getDate()).padStart(2, '0');      // Day (01–31)
        const today = `${y}-${m}-${d}`;     // Format YYYY-MM-DD
        $(selector).attr('min', today);     // Set HTML <input type="date"> min value
    }

    // Set min on page load
    setDateMinToToday('#deadline_date');

    // Validate on change
    $(document).on('change', '#deadline_date', function () {
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

    function loadtasks(table) {
        $.post('backend/api.php', { action: 'readtasks'}, function (res) {

            table.clear();

            res.forEach((r, i) => { // taken as list of lists,i is index and r is a row
                let html = '';
                let action_html = '';
                if (r.status == 'Assigned') {
                    html = `<a class='status_btn' id='acc_btn' style='background-color: blue; color:white;' onclick="accepttask(${r.task_assignment_id})">Accept</a>`;
                }
                else if (r.status.toLowerCase() == 'completed') {
                    html = `<a class='status_btn_inactive'  style='background-color:green;color:white;'>${r.status}</a>`;
                    action_html = `<a class='status_btn' id='feed_btn' style='background-color: blue; color:white;' onclick="showremark(${r.task_assignment_id})">Remarks</a>`;
                }
                else if (r.status == 'Overdue') {
                    html = `<a class='status_btn_inactive' style='background-color:red;color:white;' onclick="showbtns(this)">${r.status}</a>`;
                }
                else if (r.status == 'Pending' && (r.action == 'Reassigned' || r.action == 'Submitted')) {
                    html = `<a class='status_btn_inactive' style='background-color:orange;color:white;'>${r.status}</a>`;
                }
                else if (r.action == 'task forwarded') {
                    html = `<a class='status_btn_inactive' style='background-color:orange; color:white;'>Closed</a>`;
                }
                else if (r.status == 'Pending' && r.action == 'Submission rejected') {
                    html = `<a class='status_btn' style='background-color:orange;color:white;' onclick="showbtns(this)">${r.status}</a>
                <div class="btns">
                    <button onclick="opensubmodal(${r.task_assignment_id}, ${r.task_id})" class="cmp" title="Mark as Completed"><span style='font-size:larger;'>✅</span></button>
                    <button class="req" onclick="reqdl(${r.task_assignment_id}, ${r.task_id})" title='deadline extension request'><span style='font-size:larger;'>🙋‍♂️</span></button>
                    <button class="fwd" onclick='fwd(${r.task_assignment_id}, ${r.task_id})' title = 'Forward task'><span style='font-size:larger;'>➡️</span></button>
                </div>`;
                    action_html = `<a class='status_btn' id='feed_btn' style='background-color: blue; color:white;' onclick="showremark(${r.task_assignment_id})">Remarks</a>`;
                }
                else if (r.status == 'Pending') {
                    html = `<a class='status_btn' style='background-color:orange;color:white;' onclick="showbtns(this)">${r.status}</a>
                <div class="btns">
                    <button onclick="opensubmodal(${r.task_assignment_id}, ${r.task_id})" class="cmp" title="Mark as Completed"><span style='font-size:larger;'>✅</span></button>
                    <button class="req" onclick="reqdl(${r.task_assignment_id}, ${r.task_id})" title='deadline extension request'><span style='font-size:larger;'>🙋‍♂️</span></button>
                    <button class="fwd" onclick='fwd(${r.task_assignment_id}, ${r.task_id})' title = 'Forward task'><span style='font-size:larger;'>➡️</span></button>
                </div>`;
                }
                else {
                    html = `<a class='status_btn_inactive' onclick="showbtns(this)">${r.status}</a>`
                }
                table.row.add([
                    i + 1,
                    r.name,
                    r.task_title,
                    r.task_description,
                    r.Priority,
                    r.start_date,
                    r.deadline,
                    html,
                    (action_html !== '' ? r.action + ' ' + action_html : r.action)
                ]);
            });
            table.draw();
        }, 'json');
    }

    //open modals for request deadline
    function reqdl(taskassid, taskid) {
        $('#dl_taskid').val(taskid);
        $('#dl_taskassid').val(taskassid);
        const modalEle = $('#reqmod')[0];
        document.body.appendChild(modalEle); // ensure it's in <body>
        bootstrap.Modal.getOrCreateInstance(modalEle).show();
    }


    //open modal for forwarding
    function fwd(taskassid, taskid) {
        $('#t_id').val(taskid);
        $('#t_assid').val(taskassid);
        const modalEle = $('#fwdmod')[0];
        document.body.appendChild(modalEle); // ensure it's in <body>
        bootstrap.Modal.getOrCreateInstance(modalEle).show();
    }

    //open modal for submitting completion 
    function opensubmodal(taskassid, taskid) {
        $('#sub_taskid').val(taskid);
        $('#sub_taskassid').val(taskassid);
        const modalEle = $('#submod')[0];
        document.body.appendChild(modalEle); // ensure it's in <body>
        bootstrap.Modal.getOrCreateInstance(modalEle).show();
    }

    //accept the task aasigned
    function accepttask(taskassid) {
        console.log("HI");
        $.post('backend/api.php', { action: 'accept_task', tid: taskassid }, function (res) {
            Swal.fire({
                title: "Accept Task",
                text: "Task assigned is accepted",
                icon: "info"
            });
            loadtasks(table);
        }, "json");
    }

    //dropdown change
    $('#dept').on('change', function () {
        if ($('#dept').val() != '') {
            $.post('backend/api.php', { action: 'filterfaculty', dept: $('#dept').val() }, function (res) {
                const facultyname = $('#ph_fac_fwd');
                facultyname.find('option:not(:first)').remove();
                res.forEach(r => {
                    facultyname.append(`<option value="${r.user_id}">${r.name}</option>`);
                });
            }, "json");
        }
    });

    //forward form submit
    $('#fwdform').on('submit', function (e) {
        e.preventDefault();
        $.post('backend/api.php', { action: 'forward_request', taskId: $('#t_id').val(), taskAssId: $('#t_assid').val(), name: $('#ph_fac_fwd').val(), f_reason: $('#fwd_reason').val() }, function (res) {
            if (res.response === 'success') {
                Swal.fire({
                    title: "Task Forwarding",
                    text: "Task forwarding request sent",
                    icon: "success"
                });
                loadtasks(table);
            } else {
                Swal.fire({
                    title: "Error",
                    text: res.message || "Unknown error occurred.",
                    icon: "error"
                });
            }
            $('#fwdmod').modal('hide');
        }, 'json');
    });

   function forwardTaskAfterApproval(requestId, taskId, taskAssignmentId, forwardToName) {
        $.post('backend/api.php', {
            action: 'forward',
            fwdreqid: requestId,
            taskId: taskId,
            taskAssId: taskAssignmentId,
            name: forwardToName
        }, function(res) {
            if (res.response === 'success') {
                Swal.fire({
                    title: "Success!",
                    text: res.message,
                    icon: "success"
                });
                // Reload tasks
                loadAssignedTasks(tableAssign);
            } else {
                Swal.fire({
                    title: "Error",
                    text: res.message || "Failed to forward task",
                    icon: "error"
                });
            }
        }, 'json').fail(function(xhr, status, error) {
            Swal.fire({
                title: "Error",
                text: "Request failed: " + error,
                icon: "error"
            });
        });
    }

    //request deadline form submit
    $('#reqform').on('submit', function (e) {
        e.preventDefault();

        const taskid = $('#dl_taskid').val();
        const taskassid = $('#dl_taskassid').val();

        $.post('backend/api.php', { action: 'requestdeadline', taskId: taskid, taskAssId: taskassid, Extdl: $('#deadline_date').val(), reason: $('#reason').val() }, function (res) {
            console.log(res);

            if (res.response === 'success') {
                Swal.fire({
                    title: "Deadline Requested",
                    text: "Request for deadline extension is submitted",
                    icon: "info"
                });
                loadtasks(table);
            } else {
                Swal.fire({
                    title: "Error",
                    text: res.message || "Unknown error occurred.",
                    icon: "error"
                });
            }
            $('#reqmod').modal('hide');
        }, 'json')
            .fail(function (xhr, status, error) {
                console.error("AJAX error:", status, error);
                Swal.fire({
                    title: "Request Failed",
                    text: "Could not reach the server or invalid response.",
                    icon: "error"
                });
            });
    });

    // Function to send completion email notification
    function sendCompletionEmail(email, title, staff) {
        console.log('sendCompletionEmail called with:', {email: email, title: title, staff: staff});
        $.ajax({
            url: 'backend/api.php',
            type: 'POST',
            data: {
                action: 'notify_completion',
                email: email,
                title: title,
                staff: staff
            },
            dataType: 'json',
            success: function (res) {
                console.log('notify_completion response:', res);
                if (res.success) {
                    console.log('✅ Completion email sent successfully to:', email);
                } else {
                    console.error('❌ Failed to send completion email:', res.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('❌ Error sending completion email:', error, xhr.responseText);
            }
        });
    }

    $('#subform').on('submit', function (e) {
        e.preventDefault();
        var formdata = new FormData(this);
        formdata.append("action", "submitproof")
        $.ajax({
            url: 'backend/api.php',
            type: 'POST',
            data: formdata,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                console.log('Submit proof response:', res);
                Swal.fire({
                    title: "Submitted proof",
                    text: res.message,
                    icon: "success"
                });
                loadtasks(table);
                // Call sendCompletionEmail if email, title, and staff are available
                if (res.email && res.title && res.staff) {
                    console.log('Calling sendCompletionEmail with:', res.email, res.title, res.staff);
                    sendCompletionEmail(res.email, res.title, res.staff);
                } else {
                    console.warn('Email notification skipped - missing data:', {
                        email: res.email,
                        title: res.title,
                        staff: res.staff
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Submit proof error:', error, xhr.responseText);
            }
        });
        $('#submod').modal('hide');
    });

    //show remarks
    function showremark(rtai) {
        $.post('backend/api.php', { action: 'getremarks', rtai: rtai }, function (res) {
            let feedback = res.feedback ? res.feedback : "No remarks available.";
            Swal.fire({
                title: 'Remarks / Feedback Given',
                text: feedback,
                width: "400px"
            });
        }, 'json');
    }

    //show all buttons on clicking pending
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
</script>

</html>