<?php
require_once "./backend/session_init.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$dept = $_SESSION['dept'];
?>

<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Conflict-Free Meeting Module</title>

  <!-- ✅ Bootstrap + Icons + DataTables -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

  <style>
    body {
      background: #f8f9fc;
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial;
    }

    .btn {
      border-radius: 8px;
      border: none;
    }

    /* ✅ Main gradient button (like Request Meeting) */
    .btn-main {
      background: linear-gradient(to right, #2a38cc, #4a56ff);
      color: white !important;
    }
   
.btn-main:hover {
  opacity: 0.9;
  transform: translateY(-1px);
}


    /* ✅ Action buttons inside tables */
    .btn-complete {
      background-color: #28a745 !important;
      color: white !important;
    }

    .btn-edit {
      background-color: #ffc107 !important;
      color: black !important;
    }

    .btn-delete {
      background-color: #dc3545 !important;
      color: white !important;
    }

    /* Ensure hover works too */
    .btn-complete:hover {
      background-color: #218838 !important;
    }

    .btn-edit:hover {
      background-color: #e0a800 !important;
    }

    .btn-delete:hover {
      background-color: #c82333 !important;
    }

    table.dataTable thead tr {
      background: linear-gradient(135deg, #3e8e40ff, #0d7fddff) !important;
      color: white !important;
      text-align: center;
    }

    table.dataTable thead th {
      background: transparent !important;
    }

    .meeting-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: 0.3s ease;
      margin-bottom: 30px;
      padding: 15px;
    }

    .modal-header {
      background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
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
    /* Time slot styles (copied from HOD to match look & feel) */
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
    #myRequestMeetingModal .modal-header {
        border-top-left-radius: 8px !important;
        border-top-right-radius: 8px !important;
    }
    .header-gradient{
      color:white;
    }
  </style>
</head>

<body>
  <div class="container-fluid py-4">

    <!-- ✅ Request Meeting Button -->
    <div class="d-flex justify-content-end mb-4 gap-3">
      <button id="myOpenRequestBtn" class="btn btn-main d-flex align-items-center gap-2 text-white">
        <i class="fas fa-user-tie"></i> Request Meeting
      </button>
    </div>

    <!-- ✅ Tabs for Meetings -->
    <div class="meeting-card">
  <ul class="nav nav-tabs mb-3" id="meetingTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="scheduled-tab" data-bs-toggle="tab" data-bs-target="#scheduled"
            type="button" role="tab">Scheduled Meetings</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="requested-tab" data-bs-toggle="tab" data-bs-target="#requested" type="button"
            role="tab">Requested Meetings</button>
        </li>
      </ul>

      <div class="tab-content">
        <!--  Scheduled Meetings Table -->
        <div class="tab-pane fade show active" id="scheduled" role="tabpanel">
            <table id="scheduledMeetingsTable" class="display table table-striped" style="width:100%">
              <thead >
                <tr class="header-gradient">
                  <th style='color:white;'>Assigned by</th>
                  <th style='color:white;'>Title</th>
                  <th style='color:white;'>Date</th>
                  <th style='color:white;'>Time</th>
                </tr>
              </thead>
              <tbody id="scheduledMeetingsBody">
                <!-- Data will be populated here via JavaScript -->

              </tbody>
            </table>
          </div>

        <!-- Requested Meetings Table -->
        <div class="tab-pane fade" id="requested" role="tabpanel">
            <table id="requestedMeetingsTable" class="display table table-striped" style="width:100%">
              <thead>
                <tr class="header-gradient">
                  <th style='color:white;'>Requested To</th>
                  <th style='color:white;'>Staff</th>
                  <th style='color:white;'>Purpose</th>
                  <th style='color:white;'>Preferred Date & Time</th>
                  <th style='color:white;'>Status</th>
                </tr>
              </thead>
              <tbody id="requestedMeetingsBody">
                <!-- Data will be populated here via JavaScript -->
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>

  <!-- ✅ Request Meeting Modal (slot-based like HOD/PH) -->
  <div class="modal fade" id="myRequestMeetingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Request Principal Meeting</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- ✅ Request Meeting Form -->
        <form id="requestMeetingForm">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Purpose</label>
              <input type="text" id="purpose" name="purpose" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Request To</label>
              <select class="form-select" id="facultyPrincipalDropdown" required>
                <option disabled selected value="">Select</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Select Date</label>
              <div class="d-flex gap-2">
                <input type="date" class="form-control" id="meeting_date" name="meeting_date" required>
                <button type="button" class="btn btn-primary" id="viewSlotsBtn">View Available Slots</button>
              </div>
            </div>

            <div class="mb-3" id="timeSlotsContainer" style="display:none;">
              <label class="form-label">Available Time Slots</label>
              <div class="d-flex flex-wrap" id="timeSlots"></div>
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

  <!-- ✅ Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- faculty slot JS -->
  

  <script>
    $(document).ready(function() {
      // Remove other jQuery version if exists
      if ($.fn.jquery !== '3.7.1') {
        var otherJquery = jQuery.noConflict(true);
      }

      // ✅ Initialize Scheduled Meetings Table
      const scheduledTable = $('#scheduledMeetingsTable').DataTable({
        pageLength: 5,
        "columns": [
          { "data": "assigned_by" },
          { "data": "title" },
          { "data": "schedule_date" },
          { 
            "data": "start_time",
            "render": function(data, type, row) {
              // Remove the 'T' prefix from time
              return data ? data.replace('T', '') : '-';
            }
          }
        ]
      });

      // ✅ Open Modal
      $('#myOpenRequestBtn').on('click', function() {
        $('#myRequestMeetingModal').appendTo('body').modal('show');
      });

      // Load Officials list into dropdown (Principal / ED / CHRO)
      function loadOfficialsDropdown() {
        $.getJSON('backend/backend_combined.php?route=getOfficials')
          .done(function(resp) {
            if (resp && resp.success && Array.isArray(resp.data)) {
              const dd = $('#facultyPrincipalDropdown');
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

      // ✅ Load Scheduled Meetings (fetch meetings assigned to logged-in faculty)
      function loadScheduledMeetings() {
        $.getJSON('backend/backend_combined.php?route=fetchAssignedMeetings')
          .done(function(resp) {
            if (!resp.success) {
              console.error('Failed to load assigned meetings:', resp);
              $('#scheduledMeetingsBody').html('<tr><td colspan="5" class="text-center">No scheduled meetings found.</td></tr>');
              return;
            }
            scheduledTable.clear();
            resp.data.forEach(m => {
              scheduledTable.row.add({
                assigned_by: m.assigned_by || '-',
                title: m.title || '-',
                schedule_date: m.schedule_date || '-',
                start_time: m.start_time || '-'
              });
            });
            scheduledTable.draw();
          })
          .fail(function(jqXHR, textStatus, error) {
            console.error('Failed to load scheduled meetings:', error);
            $('#scheduledMeetingsBody').html('<tr><td colspan="5" class="text-center">No scheduled meetings found.</td></tr>');
          });
      }
      loadScheduledMeetings();

        // ✅ Request Meeting Form Submission (Replaced Section)
      document.getElementById('requestMeetingForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Get values from form
        const purpose = document.getElementById('purpose').value.trim();
        // Use selected_time for preferred date & time
        const preferredTime = document.getElementById('selected_time').value;
        // requested_to selected by user
        const requestedTo = document.getElementById('facultyPrincipalDropdown').value || 'P001';

        // Validation
        if (!purpose || !preferredTime || !requestedTo) {
          Swal.fire({
            icon: 'warning',
            title: 'All fields required',
            text: 'Please fill all fields and select a time slot.'
          });
          return;
        }

        const formData = {
          request_by: <?php echo json_encode($user_id); ?>,
          requested_to: requestedTo,
          purpose: purpose,
          preferred_time: preferredTime
        };

        try {
          const res = await fetch('backend/backend_combined.php?route=requestMeeting', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
          });

          const data = await res.json();
          console.log('Server Response:', data);

          if (data.success) {
            Swal.fire({
              icon: 'success',
              title: 'Meeting Requested',
              timer: 1500,
              showConfirmButton: false
            });

            $('#myRequestMeetingModal').modal('hide');
            document.getElementById('requestMeetingForm').reset();

            if (typeof window.loadRequestedMeetings === 'function') {
              window.loadRequestedMeetings();
            }
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: data.message || 'Failed to submit meeting request.'
            });
          }

        } catch (err) {
          console.error('Fetch error:', err);
          Swal.fire({
            icon: 'error',
            title: 'Network Error',
            text: 'Failed to submit request: ' + err.message
          });
        }
      });
    });

    //request meeting js
    $(document).ready(function () {
    // Debug load
    console.log('request_meeting_fac.js loaded');

    $('#myOpenRequestBtn').on('click', function() {
        $('#myRequestMeetingModal').appendTo('body').modal('show');
        $('#timeSlotsContainer').hide();
        $('#selected_time').val('');
    });

    $('#viewSlotsBtn').on('click', function(e) {
        e.preventDefault();
        const selectedDate = $('#meeting_date').val();
        if (!selectedDate) {
            Swal.fire({ icon: 'warning', title: 'Select Date', text: 'Please select a date first' });
            return;
        }

        const now = new Date();
        const selectedDay = new Date(selectedDate);
        selectedDay.setHours(0,0,0,0);
        now.setHours(0,0,0,0);
        if (selectedDay < now) {
            Swal.fire({ icon: 'warning', title: 'Invalid Date', text: 'Cannot request for past date' });
            return;
        }

        $('#viewSlotsBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

        const requestedTo = $('#facultyPrincipalDropdown').val();
        if (!requestedTo) {
          Swal.fire({ icon: 'warning', title: 'Select Official', text: 'Please select Request To (Principal/ED/CHRO)' });
          $('#viewSlotsBtn').prop('disabled', false).html('View Available Slots');
          return;
        }

        $.ajax({
          url: 'backend/backend_combined.php?route=getAvailableSlots_fac&date=' + selectedDate + '&requested_to=' + encodeURIComponent(requestedTo),
            method: 'GET',
            success: function(resp) {
                $('#viewSlotsBtn').prop('disabled', false).html('View Available Slots');
                if (resp.success) {
                  // Backend returns slots under `data` for this route; support both `slots` and `data` for compatibility
                  const slotsArr = resp.slots || resp.data || [];
                  const slotsHtml = (slotsArr || []).map(s => `\n                        <div class="time-slot ${s.available ? 'available' : 'booked'}" data-start="${s.start}" data-end="${s.end}">${s.start} - ${s.end}</div>\n                    `).join('');
                  $('#timeSlots').html(slotsHtml);
                  $('#timeSlotsContainer').show();

                  // bind click handlers to available slots
                  $('#timeSlots').off('click', '.time-slot.available').on('click', '.time-slot.available', function() {
                    $('.time-slot').removeClass('selected');
                    $(this).addClass('selected');
                    const start = $(this).data('start');
                    const date = $('#meeting_date').val();
                    $('#selected_time').val(`${date} ${start}`);
                  });
                } else {
                  Swal.fire({ icon: 'error', title: 'Error', text: resp.message || 'Failed to load slots' });
                }
            },
            error: function() {
                $('#viewSlotsBtn').prop('disabled', false).html('View Available Slots');
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to fetch slots' });
            }
        });
    });

    function validateMeetingDateTime(dateStr, timeStr) {
        const selectedDateTime = new Date(`${dateStr} ${timeStr}`);
        const now = new Date();
        if (selectedDateTime < now) {
            Swal.fire({ icon: 'warning', title: 'Invalid Time', text: 'Cannot request past date/time' });
            return false;
        }
        const diffMs = selectedDateTime - now;
        const diffHours = diffMs / (1000*60*60);
        if (diffHours < 3) {
            Swal.fire({ icon: 'warning', title: 'Too Soon', text: 'Requests must be made at least 3 hours ahead' });
            return false;
        }
        return true;
    }

    $('#requestMeetingForm').on('submit', function(e) {
        e.preventDefault();
        if (!$('#selected_time').val()) {
            Swal.fire({ icon: 'warning', title: 'Select Time Slot', text: 'Please choose a slot' });
            return;
        }
        const [dateStr, timeStr] = $('#selected_time').val().split(' ');
        if (!validateMeetingDateTime(dateStr, timeStr)) return;

      const requestedTo = $('#facultyPrincipalDropdown').val() || 'P001';

      const formData = {
        requested_to: requestedTo,
        purpose: $('#purpose').val(),
        request_time: $('#selected_time').val()
      };

        $.ajax({
            url: 'backend/backend_combined.php?route=submitMeetingRequest',
            type: 'POST',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            success: function(resp) {
                if (resp.success) {
                    Swal.fire({ icon: 'success', title: 'Requested', timer: 1500, showConfirmButton: false });
                    $('#myRequestMeetingModal').modal('hide');
                    $('#requestMeetingForm')[0].reset();
                    $('#timeSlotsContainer').hide();
                    // reload lists if available
                    if (typeof loadScheduledMeetings === 'function') loadScheduledMeetings();
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: resp.message || 'Failed to request' });
                }
            },
            error: function(err) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Server error' });
            }
        });
    });
});


//script for requested tables
$(document).ready(function() {
    const requestedDT = $('#requestedMeetingsTable').DataTable({
        pageLength: 5,
        language: { emptyTable: "No meeting requests" },
        order: [[3, 'desc']] // order by date & time
    });

    window.loadRequestedMeetings = function() {
      console.log('Loading requested meetings...');
      $.ajax({
        url: 'backend/backend_combined.php?route=requestMeeting',
        method: 'GET',
        data: { fetchRequests: 1 },
        dataType: 'json',
        success: function(response) {
          console.log('Received requested meetings response:', response);
          requestedDT.clear();

          if (response.success && response.data && response.data.length > 0) {
            response.data.forEach(request => {
              const badgeClass =
                request.status === 'Pending' ? 'bg-warning text-dark' :
                request.status === 'Approved' ? 'bg-success' : 'bg-danger';

              // Fix requested_to and preferred date/time display
              let requestedTo = request.requested_to || request.to || request.to_user || '-';
              let preferredTime = request.preferred_time || request.request_time || request.preferred_datetime || '-';

              requestedDT.row.add([
                escapeHtml(requestedTo),
                escapeHtml(request.users ?? request.staff ?? '-'),
                escapeHtml(request.purpose ?? '-'),
                escapeHtml(preferredTime),
                `<span class="badge ${badgeClass}">${escapeHtml(request.status ?? '-')}</span>`
              ]);
            });
          }

          requestedDT.draw();
        },
        error: function(xhr, status, error) {
          console.error('Error loading meetings:', error);
        }
      });
    }

    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    loadRequestedMeetings();
    setInterval(loadRequestedMeetings, 30000);
});

  </script>
</body>

</html>
