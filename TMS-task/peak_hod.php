<?php
require_once  './backend/session_init.php';
require_once 'db_connect.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$dept = $_SESSION['dept'];
$role = $_SESSION['role'];

// Fetch faculty from HOD's department only (using Users table)
$faculty_query = "SELECT user_id, name, dept FROM Users WHERE dept = ? AND role = 'FACULTY' ORDER BY name";
$stmt = mysqli_prepare($conn, $faculty_query);
mysqli_stmt_bind_param($stmt, "s", $dept);
mysqli_stmt_execute($stmt);
$faculty_result = mysqli_stmt_get_result($stmt);
?>


<html>

<head>
    <style>
        .modal-body {
            padding: 30px;
        }

        .filter-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .filter-section .form-label {
            color: white;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .filter-section .form-select {
            border: 2px solid rgba(255, 255, 255, 0.3);
            background-color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 12px;
        }

        .filter-section .form-select:focus {
            border-color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
        }

        .dept-display {
            color: white;
            font-weight: 600;
            font-size: 1.1em;
            margin-bottom: 15px;
        }

        .submitting_btn {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .submitting_btn .btn {
            padding: 10px 30px;
            font-weight: 600;
        }

        .faculty-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-top: 20px;
            display: none;
        }

        .no-tasks-message {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 1.1rem;
        }
    </style>
</head>

<body>
    <div class="modal-body">
        <div class="filter-section">
            <!-- Department Display (Read-only for HOD) -->
            <div class="dept-display">
                <i class="fas fa-building"></i> Department: <strong><?php echo htmlspecialchars($dept); ?></strong>
            </div>

            <div class="row g-3 justify-content-center">
                <div class="col-md-6">
                    <label class="form-label">Select Faculty</label>
                    <select id="facultyselect" class="form-select">
                        <option value="">Select Faculty</option>
                        <?php
                        while ($fac_row = mysqli_fetch_assoc($faculty_result)) {
                            echo "<option value='{$fac_row['user_id']}'>{$fac_row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="submitting_btn">
                    <button type="button" id="submitting_btn" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>

        <div id="facultyInfo" class="faculty-info">
            <i class="fas fa-user-circle"></i> Viewing Calendar for: <span id="selectedFacultyName">-</span>
        </div>

        <div id="noTasksMessage" class="no-tasks-message" style="display: block;">
            <i class="fas fa-calendar-times fa-3x mb-3"></i>
            <p>Please select a faculty member to view their personal todo calendar</p>
        </div>

        <!-- Container for loading the personal todo calendar view -->
        <div id="calendarViewContainer" style="display: none;"></div>
    </div>
</body>

<script>
    $(document).ready(function() {
        // Hide faculty info and show no tasks message initially
        $('#facultyInfo').hide();
        $('#noTasksMessage').show();

        // Handle faculty selection change
        $('#facultyselect').on('change', function() {
            var facultyId = $(this).val();
            console.log('Faculty selected:', facultyId);

            if (facultyId) {
                $('#noTasksMessage').hide();
            } else {
                $('#noTasksMessage').show();
                $('#facultyInfo').hide();
            }
        });

        // Handle Submit button click
        $('#submitting_btn').on('click', function() {
            var facultyId = $('#facultyselect').val();
            var facultyName = $('#facultyselect option:selected').text();

            if (!facultyId) {
                console.warn('No faculty selected');
                alert('Please select a faculty member');
                return;
            }

            // Show loading state
            $('#submitting_btn').prop('disabled', true).text('Loading...');

            // Update UI with selected faculty info
            $('#selectedFacultyName').text(facultyName);
            $('#facultyInfo').show();
            $('#noTasksMessage').hide();

            // Load the personal todo calendar view via AJAX
            $.ajax({
                url: 'personalToDo_hod.php',
                type: 'POST',
                data: { user_id: facultyId },
                success: function(response) {
                    $('#calendarViewContainer').html(response).show();
                    $('#submitting_btn').prop('disabled', false).text('Submit');
                    console.log('Calendar loaded for faculty:', facultyId, facultyName);
                },
                error: function(xhr, status, error) {
                    console.error('Error loading calendar:', error);
                    alert('Error loading calendar. Please try again.');
                    $('#submitting_btn').prop('disabled', false).text('Submit');
                    $('#calendarViewContainer').hide();
                }
            });
        });
    });
</script>

</html>