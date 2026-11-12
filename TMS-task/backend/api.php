<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once "./session_init.php";

// Ensure user is logged in and role is PRINCIPAL
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}



$login_id = $_SESSION['user_id'];

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$dept = $_SESSION['dept'];
$role = $_SESSION['role'];

if(!isset($_SESSION['role_weight'])) {
    $sql = "SELECT rw.weight 
        FROM role_weight rw
        WHERE rw.role_name = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $role_weight = $row ? $row['weight'] : 0;
} else {
    $role_weight = $_SESSION['role_weight'];
}


$action = $_GET['action'] ?? $_POST['action'] ?? '';

$conn->query("
    UPDATE TaskAssignment 
    SET status = 'Overdue'
    WHERE status != 'Completed'
        AND action != 'Redo'
      AND deadline < CURDATE()
    ");


//after deadline extension
$conn->query("
    UPDATE TaskAssignment ta
    INNER JOIN Tasks t ON ta.task_id = t.task_id
    SET ta.status = 'Pending'
    WHERE ta.status = 'Overdue'
      AND t.deadline > CURDATE()
    ");



switch ($action) {
    case 'dashboard_cnt':
        $user_id = $_SESSION['user_id'];

        // Completed tasks count
        $sqlCompleted = "SELECT COUNT(*) as completed FROM TaskAssignment WHERE TRIM(assigned_to) = ? AND status = 'Completed'";
        $stmt = $conn->prepare($sqlCompleted);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $completed = $result->fetch_assoc()['completed'];

        // Pending tasks count
        $sqlPending = "SELECT COUNT(*) as pending FROM TaskAssignment WHERE TRIM(assigned_to) = ? AND status IN ('Pending','In Progress')";
        $stmt = $conn->prepare($sqlPending);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $pending = $result->fetch_assoc()['pending'];

        // Demerit points
        $sqlDemerit = "SELECT demerit_points FROM Users WHERE TRIM(user_id) = ?";
        $stmt = $conn->prepare($sqlDemerit);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $demerit = $result->fetch_assoc()['demerit_points'];

        echo json_encode([
            'completed' => (int) $completed,
            'pending' => (int) $pending,
            'demerit' => (int) $demerit
        ]);
        break;
    case 'readtasks':
        $sql = "
            SELECT ta.task_id, ta.parent_assig_id, u.name, t.task_title, t.task_description, t.start_date, ta.deadline, t.Priority, ta.status, ta.task_assignment_id, ta.action
            FROM Tasks t
            INNER JOIN TaskAssignment ta ON t.task_id = ta.task_id
            LEFT JOIN Users u on ta.parent_assig_id = u.user_id
            WHERE ta.assigned_to = ? 
            ORDER BY 
            CASE 
                WHEN ta.status = 'Assigned' THEN 1
                WHEN ta.status = 'Pending' THEN 2
                WHEN ta.status = 'Overdue' THEN 3
                WHEN ta.status = 'Completed' THEN 4
                ELSE 4
            END,
            updated_at DESC;
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $login_id);
        $stmt->execute();
        $res = $stmt->get_result();

        $tasks = [];
        while ($row = $res->fetch_assoc()) {
            $tasks[] = $row;
        }

        echo json_encode($tasks);
        break;


    // Add these cases to your existing switch statement in api.php

case 'fetch_meetings':
    $user_id = $_SESSION['user_id'];
    
    // Fetch scheduled meetings (assigned TO this user)
    $sql = "SELECT c.calendar_id, c.event_title, c.event_description, c.assigned_by,
                   u.name as assigned_by_name
            FROM Calendar c
            LEFT JOIN Users u ON c.assigned_by = u.user_id
            WHERE c.user_id = ?
            ORDER BY c.calendar_id DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $meetings = [];
    while ($row = $result->fetch_assoc()) {
        $desc = $row['event_description'] ?? '';
        
        // Parse date from description
        $meeting_date = '';
        if (preg_match('/Date \& Time:\s*(\d{4}-\d{2}-\d{2})/', $desc, $matches)) {
            $meeting_date = $matches[1];
        }
        
        // Parse other details
        $location = '';
        if (preg_match('/Location\/Link:\s*([^\n]+)/', $desc, $matches)) {
            $location = trim($matches[1]);
        }
        
        $agenda = '';
        if (preg_match('/Agenda:\s*([^\n]+)/', $desc, $matches)) {
            $agenda = trim($matches[1]);
        }
        
        $time = '';
        if (preg_match('/Date \& Time:\s*\d{4}-\d{2}-\d{2}\s+(.+)/', $desc, $matches)) {
            $time = trim($matches[1]);
        }
        
        $meetings[] = [
            'id' => $row['calendar_id'],
            'title' => $row['event_title'],
            'date' => $meeting_date,
            'time' => $time,
            'location' => $location,
            'agenda' => $agenda,
            'assigned_by' => $row['assigned_by_name'] ?? 'N/A',
            'type' => 'scheduled'
        ];
    }
    
    echo json_encode($meetings);
    break;

case 'fetch_requested_meetings':
    $user_id = $_SESSION['user_id'];
    
    // Fetch meeting requests BY this user
    $sql = "SELECT ra.request_id, ra.requested_to, ra.purpose, ra.request_time, ra.status,
                   u.name as requested_to_name, u.role as requested_to_role
            FROM Request_Appointment ra
            LEFT JOIN Users u ON ra.requested_to = u.user_id
            WHERE ra.request_by = ?
            ORDER BY ra.request_time DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = [
            'id' => $row['request_id'],
            'description' => $row['purpose'],
            'requested_to' => ($row['requested_to_name'] ?? 'Unknown') . ' (' . ($row['requested_to_role'] ?? 'N/A') . ')',
            'date' => date('Y-m-d', strtotime($row['request_time'])),
            'datetime' => date('Y-m-d H:i', strtotime($row['request_time'])),
            'status' => $row['status'],
            'type' => 'requested'
        ];
    }
    
    echo json_encode($requests);
    break;

case 'fetch_assigned_meetings_calendar':
    $user_id = $_SESSION['user_id'];
    
    // Fetch meetings assigned BY other users TO this user (from Chairman/Principal)
    $sql = "SELECT c.calendar_id, c.event_title, c.event_description, c.assigned_by,
                   u.name as assigned_by_name
            FROM Calendar c
            LEFT JOIN Users u ON c.assigned_by = u.user_id
            WHERE c.user_id = ? AND c.assigned_by != ?
            ORDER BY c.calendar_id DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $assigned = [];
    while ($row = $result->fetch_assoc()) {
        $desc = $row['event_description'] ?? '';
        
        $meeting_date = '';
        if (preg_match('/Date \& Time:\s*(\d{4}-\d{2}-\d{2})/', $desc, $matches)) {
            $meeting_date = $matches[1];
        }
        
        $location = '';
        if (preg_match('/Location\/Link:\s*([^\n]+)/', $desc, $matches)) {
            $location = trim($matches[1]);
        }
        
        $agenda = '';
        if (preg_match('/Agenda:\s*([^\n]+)/', $desc, $matches)) {
            $agenda = trim($matches[1]);
        }
        
        $time = '';
        if (preg_match('/Date \& Time:\s*\d{4}-\d{2}-\d{2}\s+(.+)/', $desc, $matches)) {
            $time = trim($matches[1]);
        }
        
        $assigned[] = [
            'id' => $row['calendar_id'],
            'title' => $row['event_title'],
            'description' => $agenda,
            'date' => $meeting_date,
            'datetime' => $meeting_date . ' ' . $time,
            'location' => $location,
            'assigned_by' => $row['assigned_by_name'] ?? 'N/A',
            'type' => 'assigned'
        ];
    }
    
    echo json_encode($assigned);
    break;

    case "accept_task":
        $sql = "UPDATE taskassignment SET status = 'Pending', action = 'In Progress' WHERE task_assignment_id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo json_encode(["message" => "Prepare failed: " . $conn->error]);
            break;
        }

        $stmt->bind_param('i', $_POST['tid']);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Task accepted"]);
        } else {
            echo json_encode(["message" => "Error occurred: " . $stmt->error]);
        }
        break;

    case 'filterport_staffs':
        $sql = "SELECT * FROM users WHERE LOWER(SUBSTRING_INDEX(add_role, '_', 1)) = LOWER(?) and user_id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $_POST['pfdept'], $login_id);
        $stmt->execute();
        $res = $stmt->get_result();
        echo json_encode($res->fetch_all(MYSQLI_ASSOC));
        break;

    case 'filterport':
        $sql = "SELECT DISTINCT SUBSTRING_INDEX(pname, '_', 1) as pname from portfolio";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        echo json_encode($res->fetch_all(MYSQLI_ASSOC));
        break;

    case 'filterdept':
        $sql = 'SELECT dname, deptshort from departments';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        echo json_encode($res->fetch_all(MYSQLI_ASSOC));
        break;

    case 'filterhod':
        $sql = 'SELECT user_id, name, dept from Users where role = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $_POST['role']);
        $stmt->execute();
        $res = $stmt->get_result();
        echo json_encode($res->fetch_all(MYSQLI_ASSOC));
        break;

    case 'filterfaculty':
        $sql = 'SELECT user_id, name, dept from Users u INNER JOIN role_weight rw on u.role = rw.role_name where (u.dept = ? or u.add_role = ?) and rw.weight >= ? and u.user_id != ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssis', $_POST['dept'], $_POST['dept'], $role_weight, $login_id);
        $stmt->execute();
        $res = $stmt->get_result();
        echo json_encode($res->fetch_all(MYSQLI_ASSOC));
        break;

    case 'forward':
        // Validate inputs
        if (empty($_POST['taskId']) || empty($_POST['taskAssId']) || empty($_POST['name']) || empty($_POST['fwdreqid'])) {
            echo json_encode(['response' => 'error', 'message' => 'Missing required parameters']);
            exit;
        }

        $task_id = $_POST['taskId'];
        $parent_assig_id = $login_id;

        // Verify the forward request is approved
        $checkApproved = "SELECT forward_to, status FROM forward_requests 
                        WHERE request_id = ? AND task_assignment_id = ?";
        $stmtCheck = $conn->prepare($checkApproved);
        $stmtCheck->bind_param('ii', $_POST['fwdreqid'], $_POST['taskAssId']);
        $stmtCheck->execute();
        $requestResult = $stmtCheck->get_result();
        $forwardRequest = $requestResult->fetch_assoc();
        $stmtCheck->close();

        if (!$forwardRequest) {
            echo json_encode(['response' => 'error', 'message' => 'Forward request not found']);
            exit;
        }

        if ($forwardRequest['status'] !== 'Approved') {
            echo json_encode(['response' => 'error', 'message' => 'Forward request has not been approved yet']);
            exit;
        }

        // Name already contains user_id
        $assigned_to = $_POST['name'];

        // Get deadline from original task assignment
        $dlQuery = "SELECT deadline FROM taskassignment WHERE task_assignment_id = ?";
        $stmt = $conn->prepare($dlQuery);
        $stmt->bind_param('i', $_POST['taskAssId']);
        $stmt->execute();
        $result = $stmt->get_result();
        $deadlineRow = $result->fetch_assoc();
        $stmt->close();

        if (!$deadlineRow) {
            echo json_encode(['response' => 'error', 'message' => 'Original task assignment not found']);
            exit;
        }

        $deadlineValue = $deadlineRow['deadline'];

        // Insert new forwarded task assignment
        $insertQuery = "INSERT INTO taskassignment (task_id, assigned_to, status, deadline, parent_assig_id, updated_at)
                        VALUES (?, ?, 'Assigned', ?, ?, NOW())";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('isss', $task_id, $assigned_to, $deadlineValue, $parent_assig_id);
        $success = $stmt->execute();

        if (!$success) {
            $error = $stmt->error;
            $stmt->close();
            echo json_encode(['response' => 'error', 'message' => 'Failed to forward task: ' . $error]);
            exit;
        }

        $fwd_id = $conn->insert_id;
        $stmt->close();

        // Update the action of the parent task assignment
        $sql = "UPDATE taskassignment SET action = 'Task Forwarded', status = 'Closed' WHERE task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $_POST['taskAssId']);
        $updateSuccess = $stmt->execute();
        $stmt->close();

        if (!$updateSuccess) {
            echo json_encode(['response' => 'error', 'message' => 'Task forwarded but failed to update parent task status']);
            exit;
        }

        // Update forward_requests with the new forwarded task ID
        $sql = "UPDATE forward_requests SET forwarded_task_id = ? WHERE request_id = ? AND task_assignment_id = ? AND status = 'Approved'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iii', $fwd_id, $_POST['fwdreqid'], $_POST['taskAssId']);
        $updateFwdSuccess = $stmt->execute();
        $stmt->close();

        if (!$updateFwdSuccess) {
            echo json_encode(['response' => 'error', 'message' => 'Task forwarded but failed to update forward request record']);
            exit;
        }
        // Fetch task title
        $titleQuery = "SELECT task_title FROM tasks WHERE task_id = ?";
        $stmtTitle = $conn->prepare($titleQuery);
        $stmtTitle->bind_param("i", $task_id);
        $stmtTitle->execute();
        $titleResult = $stmtTitle->get_result()->fetch_assoc();
        $task_title = $titleResult['task_title'];
        $stmtTitle->close();

        /* ----------------------------------------------------
        SEND EMAIL TO THE FACULTY (forward_to)
        ---------------------------------------------------- */
        /* SEND EMAIL (DEBUG ENABLED) */
        require './PHPMailer/src/Exception.php';
        require './PHPMailer/src/PHPMailer.php';
        require './PHPMailer/src/SMTP.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {

            $emailQuery = "SELECT email, name FROM users WHERE user_id = ?";
            $stmtEmail = $conn->prepare($emailQuery);
            $stmtEmail->bind_param("s", $assigned_to);
            $stmtEmail->execute();
            $emailResult = $stmtEmail->get_result()->fetch_assoc();

            $faculty_email = $emailResult['email'];
            $faculty_name  = $emailResult['name'];

            if ($faculty_email) {

                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;
                $mail->Username = "soundaryadhanas6155@gmail.com";
                $mail->Password = "blla luyi ipkh qccg";
                $mail->SMTPSecure = "tls";
                $mail->Port = 587;

                $mail->setFrom("soundaryadhanas6155@gmail.com", "Task Management System");
                $mail->addAddress($faculty_email, $faculty_name);

                $mail->isHTML(true);
                $mail->Subject = "Task Forwarded to You";
                $mail->Body = "
            <h3>Hello $faculty_name,</h3>
            <p>A task has been forwarded to you.</p>
            <p><b>Title:</b> $task_title</p>
            <p><b>Forwarded By:</b> $user_name</p>
            <p><b>Deadline:</b> $deadlineValue</p>
            <br>
            <p>Please log in to the KR connect to view and take action.</p>
        ";


                $mail->send();
                $mail_status = "Mail sent successfully";

            } else {
                $mail_status = "Faculty email not found in database";
            }

        } catch (Exception $e) {
            $mail_status = "Mailer Error: " . $mail->ErrorInfo;
        }



        // Return final JSON response
        echo json_encode([
            'response' => 'success',
            'message' => 'Task forwarded successfully to ' . $_POST['name'],
            'forwarded_task_id' => $fwd_id
        ]);
        exit;


    case 'forward_request':
        // Validate inputs first
        if (empty($_POST['name']) || empty($_POST['taskAssId']) || empty($_POST['taskId'])) {
            echo json_encode(['response' => 'error', 'message' => 'Missing required parameters']);
            exit;
        }

        $fwd_user_id = $_POST['name']; // directly using user_id
        
        // Check for pending tasks count
        $checkPending = "SELECT COUNT(*) AS pending_count 
                        FROM taskassignment 
                        WHERE assigned_to = ? AND status IN ('Pending', 'Assigned')";
        $stmtCheck = $conn->prepare($checkPending);
        $stmtCheck->bind_param("s", $fwd_user_id);
        $stmtCheck->execute();
        $pendingCount = $stmtCheck->get_result()->fetch_assoc()['pending_count'];
        $stmtCheck->close();

        if ($pendingCount >= 3) {
            echo json_encode(['response' => 'error', 'message' => 'Task cannot be assigned. They have 3 or more pending tasks.']);
            exit;
        }

        // Get parent assignment ID
        $assigneeQuery = "SELECT parent_assig_id FROM taskassignment WHERE task_assignment_id = ?";
        $stmt = $conn->prepare($assigneeQuery);
        $stmt->bind_param('i', $_POST['taskAssId']);
        $stmt->execute();
        $result = $stmt->get_result();
        $formerUser = $result->fetch_assoc();
        $stmt->close();

        if (!$formerUser) {
            echo json_encode(['response' => 'error', 'message' => 'Task assignment not found']);
            exit;
        }

        $task_ass_id = $_POST['taskAssId'];
        $parent_assig_id = $login_id;
        $former_id = $formerUser['parent_assig_id'];

        // Check if a  forward request already exists for this task
        $checkExisting = "SELECT request_id FROM forward_requests 
                        WHERE task_assignment_id = ? 
                        AND requested_by = ?";
        $stmtExist = $conn->prepare($checkExisting);
        $stmtExist->bind_param('is', $task_ass_id, $parent_assig_id);
        $stmtExist->execute();
        $existingResult = $stmtExist->get_result();

        if ($existingResult->num_rows > 0) {
            $stmtExist->close();
            echo json_encode(['response' => 'error', 'message' => 'A forwarding request for this task has been sent already']);
            exit;
        }
        $stmtExist->close();

        // Get the next available request_id manually
        $getMaxId = "SELECT COALESCE(MAX(request_id), 0) + 1 AS next_id FROM forward_requests";
        $resultMax = $conn->query($getMaxId);
        $nextId = $resultMax->fetch_assoc()['next_id'];

        // Insert forward request WITH the request_id
        $insertQuery = "INSERT INTO forward_requests(request_id, task_assignment_id, requested_by, requested_to, forward_to, created_at)
                        VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('iisss', $nextId, $task_ass_id, $parent_assig_id, $former_id, $fwd_user_id);
        $success = $stmt->execute();

        if (!$success) {
            $error = $stmt->error;
            $stmt->close();
            echo json_encode(['response' => 'error', 'message' => 'Failed to create forward request: ' . $error]);
            exit;
        }
        $stmt->close();

        // Update the action of the parent task assignment
        $sql = "UPDATE taskassignment SET action = 'Forwarding', fwd_reason = ? WHERE task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $_POST['f_reason'], $_POST['taskAssId']);
        $updateSuccess = $stmt->execute();
        $stmt->close();

        if (!$updateSuccess) {
            echo json_encode(['response' => 'error', 'message' => 'Forward request created but failed to update task status']);
            exit;
        }

        // Return final JSON response
        echo json_encode([
            'response' => 'success',
            'message' => 'Task forwarding request sent successfully'
        ]);
        exit;

    case 'requestdeadline':
        $sql = "INSERT into deadline_request (task_id, taskassignment_id, Reason, requested_deadline ,created_at) values(?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiss', $_POST['taskId'], $_POST['taskAssId'], $_POST['reason'], $_POST['Extdl']);
        if ($stmt->execute()) {
            echo json_encode([
                'response' => 'success',
                'message' => 'Request sent successfully'
            ]);
        } else {
            echo json_encode([
                'response' => 'error',
                'message' => 'Failed to forward task: ' . $stmt->error
            ]);
        }
        //change action to deadline requested
        $sql = "UPDATE taskassignment set action = 'deadline extension requested', deadline = ? where task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $_POST['Extdl'], $_POST['taskAssId']);
        $stmt->execute();

        $stmt->close();
        exit;

    case "submitproof":
        $target_folder = "../uploads/";
        $target_file = time() . '_' . basename($_FILES['proof']['name']);
        $target_name = $target_folder . $target_file;
        $db_path = "/TMS-task/uploads/" . $target_file;
        if (!move_uploaded_file($_FILES['proof']['tmp_name'], $target_name)) {
            echo json_encode(["message" => "failed to upload file"]);
            exit;
        }
        $stmt = $conn->prepare("UPDATE taskassignment SET proof= ? , explanation = ?, action = 'Submitted' where task_id = ? and task_assignment_id = ?");
        $stmt->bind_param("ssss", $db_path, $_POST['exp'], $_POST['sub_taskid'], $_POST['sub_taskassid']);
        if ($stmt->execute()) {
            // Fetch assignee email from parent_assig_id in taskassignment table, staff name (assigned_to), and task title
            $task_id = intval($_POST['sub_taskid']);
            $task_assignment_id = intval($_POST['sub_taskassid']);
            
            // Get email from parent_assig_id in taskassignment table (the person who assigned the task - HOD)
            // If parent_assig_id = 5, fetch email of user_id = 5 from Users table
            $fetchQuery = "SELECT ta.parent_assig_id, assigner.email, assigner.user_id as assigner_user_id, completer.name as staff_name, t.task_title
                          FROM taskassignment ta 
                          INNER JOIN tasks t ON ta.task_id = t.task_id 
                          INNER JOIN Users assigner ON ta.parent_assig_id = assigner.user_id 
                          INNER JOIN Users completer ON ta.assigned_to = completer.user_id 
                          WHERE ta.task_id = ? AND ta.task_assignment_id = ?";
            $fetchStmt = $conn->prepare($fetchQuery);
            if (!$fetchStmt) {
                error_log("Prepare failed for email query: " . $conn->error);
                echo json_encode(["message" => "Proof submitted"]);
                break;
            }
            $fetchStmt->bind_param("ii", $task_id, $task_assignment_id);
            $fetchStmt->execute();
            $fetchResult = $fetchStmt->get_result();
            $fetchData = $fetchResult->fetch_assoc();
            $fetchStmt->close();
            
            if ($fetchData && !empty($fetchData['email'])) {
                error_log("Email notification - parent_assig_id: " . $fetchData['parent_assig_id'] . ", user_id: " . $fetchData['assigner_user_id'] . ", email: " . $fetchData['email']);
                echo json_encode([
                    "message" => "Proof submitted",
                    "email" => trim($fetchData['email']),
                    "staff" => $fetchData['staff_name'],
                    "title" => $fetchData['task_title']
                ]);
            } else {
                // Debug: log if email is missing
                error_log("Email notification failed - task_id: $task_id, task_assignment_id: $task_assignment_id. Query result: " . ($fetchData ? print_r($fetchData, true) : 'NULL'));
                echo json_encode([
                    "message" => "Proof submitted",
                    "debug" => "Email not found in database for parent_assig_id"
                ]);
            }
        } else {
            echo json_encode(["message" => "Proof is not submitted"]);
        }

        break;

    case "notify_completion":
        $email = trim($_POST['email'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $staff = trim($_POST['staff'] ?? '');

        error_log("notify_completion: Received - email: '$email', title: '$title', staff: '$staff'");

        if (empty($email) || empty($title) || empty($staff)) {
            error_log("notify_completion: Missing parameters - email: " . ($email ?: 'empty') . ", title: " . ($title ?: 'empty') . ", staff: " . ($staff ?: 'empty'));
            echo json_encode(["success" => false, "message" => "Missing required parameters"]);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            error_log("notify_completion: Invalid email format - $email");
            echo json_encode(["success" => false, "message" => "Invalid email"]);
            exit;
        }

        require_once __DIR__ . '/PHPMailer/src/Exception.php';
        require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
        require_once __DIR__ . '/PHPMailer/src/SMTP.php';

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'soundaryadhanas6155@gmail.com';
            $mail->Password = 'blla luyi ipkh qccg';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->SMTPDebug = 0; // Set to 2 for verbose debugging

            $mail->setFrom('soundaryadhanas6155@gmail.com', 'Task Management System');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Task Completed";
            $mail->Body = "
                <h2>Task Completed</h2>
                <p><b>Title:</b> " . htmlspecialchars($title) . "</p>
                <p><b>Completed by:</b> " . htmlspecialchars($staff) . "</p>
                <p><b>Sent by:</b> Management team</p>
            ";
            $mail->AltBody = "Task Completed\nTitle: $title\nCompleted by: $staff\nSent by: Management team";

            $result = $mail->send();
            if ($result) {
                error_log("notify_completion: ✅ Email sent successfully to $email for task: $title");
                echo json_encode(["success" => true, "message" => "Email sent successfully to $email"]);
            } else {
                error_log("notify_completion: ❌ Email send() returned false. ErrorInfo: " . $mail->ErrorInfo);
                echo json_encode(["success" => false, "message" => "Failed to send email: {$mail->ErrorInfo}"]);
            }

        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("notify_completion: ❌ Exception - " . $e->getMessage() . " | ErrorInfo: " . $mail->ErrorInfo);
            echo json_encode(["success" => false, "message" => "Mailer Error: {$mail->ErrorInfo}"]);
        }
        break;

    case "readAssignedTasks":
        $sql = "SELECT t.task_id, ta.task_assignment_id, ta.assigned_to, u.name, t.task_title, t.task_description, t.start_date, ta.deadline, ta.status, ta.action, t.Priority
                FROM tasks t INNER JOIN taskassignment ta ON t.task_id = ta.task_id INNER JOIN Users u on ta.assigned_to = u.user_id WHERE t.assigned_by = ?
                ORDER BY 
                CASE 
                    WHEN ta.status = 'Assigned' THEN 1
                    WHEN ta.status = 'Pending' THEN 2
                    WHEN ta.status = 'Overdue' THEN 3
                    WHEN ta.status = 'Completed' THEN 4
                    ELSE 4
                END,
                updated_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $login_id);
        $stmt->execute();
        $res = $stmt->get_result();

        $tasks = [];
        while ($row = $res->fetch_assoc()) {
            $tasks[] = $row;
        }

        echo json_encode($tasks);
        break;
    case 'process_fwd_req':
        // Validate input
        if (empty($_POST['id'])) {
            echo json_encode(['response' => 'error', 'message' => 'Missing task assignment ID']);
            exit;
        }

        // Get forward request details
        $sql = "SELECT rb.name as requested_by, ft.name as forward_to, f.status , ta.fwd_reason
            FROM forward_requests f 
            INNER JOIN Users rb ON rb.user_id = f.requested_by 
            INNER JOIN Users ft ON ft.user_id = f.forward_to 
            INNER JOIN taskassignment ta ON f.task_assignment_id = ta.task_assignment_id
            WHERE f.task_assignment_id = ? AND f.requested_to = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $_POST['id'], $login_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $forwardRequest = $res->fetch_assoc();
        $stmt->close();

        if (!$forwardRequest) {
            echo json_encode(['response' => 'error', 'message' => 'Forward request not found or you are not authorized']);
            exit;
        }

        if ($forwardRequest['status'] !== 'Pending') {
            echo json_encode(['response' => 'error', 'message' => 'This forward request has already been ' . strtolower($forwardRequest['status'])]);
            exit;
        }

        // Return the forward request details
        echo json_encode($forwardRequest);
        exit;

    case 'approve_forward':
        // Validate input
        if (empty($_POST['id'])) {
            echo json_encode(['response' => 'error', 'message' => 'Missing task assignment ID']);
            exit;
        }

        // Check if the forward request exists and is pending
        $checkQuery = "SELECT request_id, status FROM forward_requests 
                   WHERE task_assignment_id = ? AND requested_to = ?";
        $stmtCheck = $conn->prepare($checkQuery);
        $stmtCheck->bind_param("is", $_POST['id'], $login_id);
        $stmtCheck->execute();
        $checkResult = $stmtCheck->get_result();
        $forwardRequest = $checkResult->fetch_assoc();
        $stmtCheck->close();

        if (!$forwardRequest) {
            echo json_encode(['response' => 'error', 'message' => 'Forward request not found or you are not authorized to approve it']);
            exit;
        }

        if ($forwardRequest['status'] !== 'Pending') {
            echo json_encode(['response' => 'error', 'message' => 'This forward request has already been ' . strtolower($forwardRequest['status'])]);
            exit;
        }

        // Update forward request status to Approved
        $sql = "UPDATE forward_requests SET status = 'Approved' WHERE task_assignment_id = ? AND requested_to = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $_POST['id'], $login_id);
        $updateSuccess = $stmt->execute();
        $stmt->close();

        if (!$updateSuccess) {
            echo json_encode(['response' => 'error', 'message' => 'Failed to approve forward request']);
            exit;
        }

        // Get approved forward request details
        $sql = "SELECT f.request_id, f.task_assignment_id, f.forward_to, ta.task_id 
            FROM forward_requests f 
            INNER JOIN taskassignment ta ON f.task_assignment_id = ta.task_assignment_id  
            WHERE f.task_assignment_id = ? AND f.status = 'Approved'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['id']);
        $stmt->execute();
        $res = $stmt->get_result();
        $approvedRequest = $res->fetch_assoc();
        $stmt->close();

        if (!$approvedRequest) {
            echo json_encode(['response' => 'error', 'message' => 'Failed to retrieve approved request details']);
            exit;
        }

        // Return success with request details (keeping your original structure for compatibility)
        echo json_encode($approvedRequest);
        exit;

    case 'reject_forward':
        $sql = "UPDATE taskassignment set action = 'task forwarding rejected' where task_assignment_id = ? ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['id']);
        $stmt->execute();
        $stmt->close();

        $sql = "UPDATE forward_requests set status = 'Rejected' where task_assignment_id = ? and requested_to = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $_POST['id'], $login_id);
        $stmt->execute();
        $stmt->close();
        
        echo json_encode(['response' => 'failed', 'message' => 'task forwarding rejected']);
        break;


    
    case 'assigntasks_p':
        // Handle fac parameter - can be array or comma-separated string
        $faculties = $_POST['fac'] ?? [];
        if (is_string($faculties)) {
            $faculties = array_filter(explode(',', $faculties));
        }
        if (!is_array($faculties)) {
            $faculties = [$faculties];
        }
        
        $title = $_POST['title'];
        $desc = $_POST['desc'];
        $start = $_POST['date'];
        $deadline = $_POST['deadline'];

        // 1. Insert task into tasks table
        $insertTask = "INSERT INTO tasks (assigned_by, task_title, task_description, start_date, deadline, status)
                    VALUES (?, ?, ?, ?, ?, 'Assigned')";
        $stmt = $conn->prepare($insertTask);
        $stmt->bind_param('sssss', $login_id, $title, $desc, $start, $deadline);

        if (!$stmt->execute()) {
            echo json_encode(['response' => 'error', 'message' => 'Failed to insert task']);
            exit;
        }
        $stmt->close();

        $task_id = $conn->insert_id;

        // 2. Prepare statements
        $insertAssign = "INSERT INTO taskassignment (task_id, assigned_to, parent_assig_id, status, deadline, updated_at, action)
                        VALUES (?, ?, ?, 'Assigned', ?, CURDATE(), 'In Progress')";
        $stmtAssign = $conn->prepare($insertAssign);

        $checkPending = "SELECT COUNT(*) AS pending_count 
                        FROM taskassignment 
                        WHERE assigned_to = ? AND status IN ('Pending', 'Assigned')";
        $stmtCheck = $conn->prepare($checkPending);

        $successCount = 0;
        $failedCount = 0;
        $failedUsers = [];
        $mail_status = [];   // ⭐ added

        foreach ($faculties as $faculty) {

            $ass_user_id = $faculty;

            // Check pending tasks
            $stmtCheck->bind_param("s", $ass_user_id);
            $stmtCheck->execute();
            $pendingCount = $stmtCheck->get_result()->fetch_assoc()['pending_count'];

            if ($pendingCount >= 3) {
                $failedCount++;
                $failedUsers[] = $faculty;
                continue;
            }

            // Assign task
            $stmtAssign->bind_param('isss', $task_id, $ass_user_id, $login_id, $deadline);
            if ($stmtAssign->execute()) {
                $successCount++;

                /* ----------------------------------------------------
                SEND EMAIL TO FACULTY
                ---------------------------------------------------- */
                require './PHPMailer/src/Exception.php';
                require './PHPMailer/src/PHPMailer.php';
                require './PHPMailer/src/SMTP.php';

                $mail = new PHPMailer\PHPMailer\PHPMailer(true);

                try {
                    // Get faculty email
                    $emailQuery = "SELECT email, name FROM users WHERE user_id = ?";
                    $stmtEmail = $conn->prepare($emailQuery);
                    $stmtEmail->bind_param("s", $ass_user_id);
                    $stmtEmail->execute();
                    $emailResult = $stmtEmail->get_result()->fetch_assoc();
                    $faculty_email = $emailResult['email'];
                    $faculty_name = $emailResult['name'];

                    if ($faculty_email) {
                        $mail->isSMTP();
                        $mail->Host = "smtp.gmail.com";
                        $mail->SMTPAuth = true;
                        $mail->Username = "soundaryadhanas6155@gmail.com";
                        $mail->Password = "blla luyi ipkh qccg";
                        $mail->SMTPSecure = "tls";
                        $mail->Port = 587;

                        $mail->setFrom("soundaryadhanas6155@gmail.com", "Task Management System");
                        $mail->addAddress($faculty_email, $faculty_name);

                        $mail->isHTML(true);
                        $mail->Subject = "New Task Assigned";
                        $mail->Body = "
                            <h3>Hello $faculty_name,</h3>
                            <p>You have been assigned a new task.</p>
                            <p><b>Title:</b> $title</p>
                            <p><b>Description:</b> $desc</p>
                            <p><b>Deadline:</b> $deadline</p>
                            <br>
                            <p>Please log in to the TMS portal to view more details.</p>
                        ";

                        if ($mail->send()) {
                            $mail_status[] = "$faculty_name → Mail sent successfully";
                        } else {
                            $mail_status[] = "$faculty_name → Mail failed";
                        }
                    }
                } catch (Exception $e) {
                    $mail_status[] = "$faculty_name → Mail error: " . $mail->ErrorInfo;
                }

            } else {
                $failedCount++;
                $failedUsers[] = $faculty . " (assignment failed)";
            }
        }

        $stmtAssign->close();
        $stmtCheck->close();

        // 3. Prepare response
        $responseMsg = "Task assigned to {$successCount} faculty member(s).";
        if (!empty($failedUsers)) {
            $responseMsg .= " Task could not be assigned to: " . implode(", ", $failedUsers) . ". They have pending tasks to be done.";
        }

        $responseStatus = ($successCount > 0) ? 'success' : 'Oops';

        // NOW returning mail_status to frontend
        echo json_encode([
            'response' => $responseStatus,
            'message' => $responseMsg,
            'mail_status' => $mail_status
        ]);

        break;


    // GET single task details by ID
    case "fetchrow":
        $sql = "SELECT ta.task_id, ta.task_assignment_id, ta.assigned_to, t.task_title, t.task_description, t.start_date, ta.deadline, u.name, u.dept
                FROM tasks t INNER JOIN taskassignment ta ON t.task_id = ta.task_id LEFT JOIN users u ON ta.assigned_to = u.user_id WHERE ta.task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['id']);
        $stmt->execute();
        $res = $stmt->get_result();
        echo json_encode($res->fetch_assoc());
        break;


    // EDIT task
    case "editTask":
        $sql = "UPDATE taskassignment ta INNER JOIN tasks t on ta.task_id = t.task_id
                SET t.task_title = ?, t.task_description = ?, t.start_date = ?, ta.deadline = ?
                WHERE ta.task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssi",
            $_POST['title'],
            $_POST['description'],
            $_POST['startdate'],
            $_POST['deadline'],
            $_POST['id']
        );
        $stmt->execute();
        echo json_encode(["response" => "success", "message" => "Task updated successfully"]);
        break;


    // DELETE task
    case "deletetask":
        // Count assignments
        $count = "SELECT COUNT(*) as cnt FROM taskassignment WHERE task_id = ?";
        $stmt = $conn->prepare($count);
        $stmt->bind_param('i', $_POST['taskid']);
        $stmt->execute();
        $result = $stmt->get_result();
        $res = $result->fetch_assoc();
        $stmt->close();

        // Delete from taskassignment
        $sql = "DELETE FROM taskassignment WHERE task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['taskAsid']);
        $stmt->execute();
        $stmt->close();  // OK

        // If only 1 assignment → delete task
        if ($res['cnt'] == 1) {
            $sql = "DELETE FROM tasks WHERE task_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $_POST['taskid']);
            $stmt->execute();
            $stmt->close();   // CLOSE ONLY HERE
        }

        echo json_encode([
            "response" => "success",
            "message" => "Task deleted successfully"
        ]);
        break;


    case 'getdlreq':
        $sql = "SELECT dr_id, taskassignment_id, requested_deadline, Reason FROM deadline_request WHERE taskassignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['tid']);
        $stmt->execute();
        $res = $stmt->get_result();
        echo json_encode($res->fetch_assoc());
        break;

    case 'getsub':
        $sql = "SELECT task_assignment_id, proof, explanation FROM taskassignment WHERE task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['tId']);
        $stmt->execute();
        $res = $stmt->get_result();
        header('Content-Type: application/json');
        echo json_encode($res->fetch_assoc());
        break;

    case "approvereqdl":
        $sql = "UPDATE deadline_request dr INNER JOIN taskassignment ta ON dr.taskassignment_id = ta.task_assignment_id SET dr.status = 'approved', ta.action = 'deadline extension approved', ta.deadline = ? WHERE dr.dr_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $_POST['extndl'], $_POST['drId']);

        if ($stmt->execute()) {
            echo json_encode(["response" => "success", "message" => "Deadline extension approved"]);
        } else {
            echo json_encode(["response" => "error", "message" => "Failed to approve request"]);
        }
        break;

    case "rejectreqdl":
        $sql = "UPDATE deadline_request dr INNER JOIN taskassignment ta ON dr.taskassignment_id = ta.task_assignment_id SET dr.status = 'approved', ta.action = 'deadline extension rejected' WHERE dr.dr_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['drId']); // Changed from taskId to drId

        if ($stmt->execute()) {
            echo json_encode(["response" => "success", "message" => "Deadline extension rejected"]);
        } else {
            echo json_encode(["response" => "error", "message" => "Failed to reject request"]);
        }
        break;

    case "approvesub":
        $sql = "UPDATE taskassignment  SET status = 'Completed', action = 'Submission approved', feedback = ? WHERE task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $_POST['feedback'], $_POST['tai']);

        if ($stmt->execute()) {
            echo json_encode(["response" => "success", "message" => "Submission approved"]);
        } else {
            echo json_encode(["response" => "error", "message" => "Failed to approve submission"]);
        }
        break;

    case "rejectsub":
        $sql = "UPDATE taskassignment  SET status = 'Pending', action = 'Submission rejected', feedback = ? WHERE task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $_POST['feedback'], $_POST['TaI']); // Changed from taskId to drId

        if ($stmt->execute()) {
            echo json_encode(["response" => "success", "message" => "Submission rejected"]);
        } else {
            echo json_encode(["response" => "error", "message" => "Failed to reject submission"]);
        }
        break;

    // Load proof/explanation for a specific task assignment (used by Chairman review modal)
    case 'getTaskProof':
        $assignment_id = isset($_POST['assignment_id']) ? intval($_POST['assignment_id']) : 0;
        if ($assignment_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid assignment id']);
            break;
        }

        $sql = "SELECT proof, explanation FROM taskassignment WHERE task_assignment_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $assignment_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        if ($row) {
            echo json_encode(['success' => true, 'proof' => $row['proof'], 'explanation' => $row['explanation']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Not found']);
        }
        break;

    // Chairman accepts a task completion (marks as completed and saves remarks)
    case 'acceptTaskCompletion':
        $assignment_id = isset($_POST['assignment_id']) ? intval($_POST['assignment_id']) : 0;
        $remarks = isset($_POST['remarks']) ? trim($_POST['remarks']) : '';

        if ($assignment_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid assignment id']);
            break;
        }

        $sql = "UPDATE taskassignment SET status = 'Completed', action = 'Submission approved', feedback = ? WHERE task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $remarks, $assignment_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Task accepted and marked completed']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update assignment']);
        }
        break;

    // Return list of officials that Chairman can assign to (PRINCIPAL, ED, CHRO)
    case 'filterchairman_targets':
        $sql = "SELECT user_id, fac_name, role FROM ofaculty WHERE role !='CHAIRMAN' ";
        $res = $conn->query($sql);
        $out = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $out[] = $row;
            }
        }
        echo json_encode($out);
        break;

    case 'getremarks':
        $sql = "SELECT task_assignment_id, status, feedback FROM taskassignment WHERE task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['rtai']);
        $stmt->execute();
        $res = $stmt->get_result();
        header('Content-Type: application/json');
        echo json_encode($res->fetch_assoc());
        break;

    case 'redo':
        $sql = "UPDATE taskassignment set status = 'Pending', deadline = ?, action = 'Redo' where task_assignment_id = ? and task_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $_POST['new_deadline'], $_POST['task_ass_id'], $_POST['task_id']);
        if ($stmt->execute()) {
            echo json_encode(['response' => 'success', 'message' => 'task is reassigned to the same person']);
        } else {
            echo json_encode(['response' => 'failed', 'message' => 'task is failed to be reassigned to the same person']);
        }
        $stmt->close();
        break;

    case 'reassign':
        $fac_id = $_POST['refac'];  // directly using user_id
        $deadline = $_POST['re_deadline'];

        // Insert new assignment
        $sql = "INSERT INTO taskassignment (task_id, assigned_to, parent_assig_id, status, deadline, updated_at, action)
                VALUES (?, ?, ?, 'Assigned', ?, CURDATE(), 'In Progress')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isss', $_POST['task_id'], $fac_id, $login_id, $deadline);
        $insertok = $stmt->execute();

        // Update old assignment
        $upquery = "UPDATE taskassignment SET action = 'Reassigned' WHERE task_assignment_id = ?";
        $upd = $conn->prepare($upquery);
        $upd->bind_param("i", $_POST['task_ass_id']);
        $updateok = $upd->execute();

        if ($insertok && $updateok) {
            echo json_encode(['response' => 'success', 'message' => "Task reassigned"]);
        } else {
            echo json_encode(['response' => 'error', 'message' => 'Task reassignment failed.']);
        }

        break;


    case 'fetch':
        $stmt = $conn->prepare("SELECT * FROM personal WHERE user_id = ? ORDER BY start_date ASC");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $tasks = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($tasks);
        break;

    /* -------------------------------------------------
       INSERT — Add a new personal task
    -------------------------------------------------- */
    case 'insert':
        $input = json_decode(file_get_contents("php://input"), true);

        $title = trim($input['title'] ?? '');
        $description = trim($input['description'] ?? '');
        $start = $input['start_date'] ?? '';
        $deadline = $input['deadline'] ?? '';
        $priority = $input['priority'] ?? 'Not Important & Not Immediate'; //  consistent format
        $status = $input['status'] ?? 'Pending';

        $stmt = $conn->prepare("
            INSERT INTO personal (title, description, start_date, deadline, priority, status, user_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssssss", $title, $description, $start, $deadline, $priority, $status, $user_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Task inserted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $stmt->error]);
        }
        break;

    /* -------------------------------------------------
       UPDATE — Edit existing task
    -------------------------------------------------- */
    case 'update':
        $input = json_decode(file_get_contents("php://input"), true);

        $id = (int) ($input['id'] ?? 0);
        $title = trim($input['title'] ?? '');
        $description = trim($input['description'] ?? '');
        $start = $input['start_date'] ?? '';
        $deadline = $input['deadline'] ?? '';
        $priority = $input['priority'] ?? 'Not Important & Not Immediate';
        $status = $input['status'] ?? 'Pending';

        $stmt = $conn->prepare("
            UPDATE personal 
            SET title=?, description=?, start_date=?, deadline=?, priority=?, status=?
            WHERE id=? AND user_id=?
        ");
        $stmt->bind_param("ssssssis", $title, $description, $start, $deadline, $priority, $status, $id, $user_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Task updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $stmt->error]);
        }
        break;

   case 'complete':
        $id = (int) ($_POST['id'] ?? 0);
        $status = "Completed";

        $stmt = $conn->prepare("UPDATE personal SET status=? WHERE id=? AND user_id=?");
        $stmt->bind_param("sis", $status, $id, $user_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Task marked completed']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update task']);
        }
        break;


    case 'delete':
        $id = (int) ($_POST['id'] ?? 0);

        $stmt = $conn->prepare("DELETE FROM personal WHERE id=? AND user_id=?");
        $stmt->bind_param("is", $id, $user_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Task deleted']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete task']);
        }
        break;

    case 'notify_deadline':

        $today   = date('Y-m-d');
        $runFile = __DIR__ . '/notify_deadline_last_run.txt';

        // Check if reminders already sent today
        $lastRunDate = is_file($runFile) ? trim(file_get_contents($runFile)) : '';

        if ($lastRunDate === $today) {
            echo json_encode([
                'response' => 'skip',
                'message'  => 'Deadline reminders already sent today.'
            ]);
            break;
        }

        $daysBefore = 1;

        $sql = "
            SELECT 
                ta.task_assignment_id,
                DATE(ta.deadline) AS deadline_date,
                ta.status,
                u.user_id,
                u.name,
                u.email,
                t.task_title,
                t.task_description
            FROM taskassignment ta
            INNER JOIN users u ON ta.assigned_to = u.user_id
            INNER JOIN tasks t ON ta.task_id = t.task_id
            WHERE 
                DATE(ta.deadline) = DATE_ADD(CURDATE(), INTERVAL ? DAY)
                AND ta.status IN ('Assigned', 'Pending', 'In Progress')
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $daysBefore);
        $stmt->execute();
        $res = $stmt->get_result();

        $sentCount   = 0;
        $failedCount = 0;
        $mail_status = [];
        $year        = date("Y");

        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $faculty_email = $row['email'];
                $faculty_name  = $row['name'];
                $task_title    = $row['task_title'];
                $deadline      = $row['deadline_date'];

                if (empty($faculty_email)) {
                    $failedCount++;
                    $mail_status[] = "{$faculty_name} → No email ID available";
                    continue;
                }

                $faculty_name_esc = htmlspecialchars($faculty_name, ENT_QUOTES, "UTF-8");
                $task_title_esc   = htmlspecialchars($task_title, ENT_QUOTES, "UTF-8");

                $subject = "Task Deadline Reminder: Due on {$deadline}";
                $body    = '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <style>
                        *{margin:0;padding:0;box-sizing:border-box;}
                        body{font-family:"Arial","Helvetica",sans-serif;background:#f8f9fa;color:#333;}
                        table{border-collapse:collapse;}
                    </style>
                </head>
                <body>
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <td>
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" 
                                    style="margin:20px auto;background:white;border-radius:12px;
                                        box-shadow:0 6px 12px rgba(0,0,0,0.08);overflow:hidden;">

                                    <!-- HEADER -->
                                    <tr>
                                        <td style="background:linear-gradient(135deg,#1e88e5 0%,#1565c0 100%);
                                                padding:30px;text-align:center;">
                                            <h1 style="color:white;margin:0;font-size:26px;">KR CONNECT</h1>
                                            <p style="color:#dbeafe;margin-top:8px;font-size:15px;">
                                                M. Kumarasamy College of Engineering
                                            </p>
                                        </td>
                                    </tr>

                                    <!-- STATUS SECTION -->
                                    <tr>
                                        <td style="background:#fff4e5;border-left:5px solid #f4b400;padding:25px;">
                                            <div style="text-align:center;">
                                                <span style="display:inline-block;background:#f4b400;color:#fff;
                                                            padding:7px 18px;border-radius:25px;font-size:13px;
                                                            font-weight:bold;margin-bottom:10px;">
                                                    UPCOMING DEADLINE
                                                </span>
                                                <p style="margin:0;font-size:20px;color:#e67e22;font-weight:600;">
                                                    Task Due Tomorrow
                                                </p>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- MAIN CONTENT -->
                                    <tr>
                                        <td style="padding:30px;">
                                            <p style="font-size:15px;color:#333;margin:0 0 15px 0;">
                                                Dear '. htmlspecialchars($faculty_name) .',
                                            </p>

                                            <p style="font-size:15px;color:#333;margin:0 0 15px 0;">
                                                This is a gentle reminder that the below task has its deadline tomorrow:
                                            </p>

                                            <div style="background:linear-gradient(135deg,#f8f9fa 0%,#e9ecef 100%);
                                                        border-radius:12px;padding:20px;border:1px solid #dee2e6;margin:20px 0;">
                                                <table style="width:100%;font-size:14px;color:#2c3e50;">
                                                    <tr>
                                                        <td style="width:35%;padding:10px;font-weight:600;background:#f1f3f5;
                                                                border:1px solid #dadfe4;">Task Title</td>
                                                        <td style="padding:10px;border:1px solid #dadfe4;">'. $task_title .'</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding:10px;font-weight:600;background:#f1f3f5;
                                                                border:1px solid #dadfe4;">Deadline</td>
                                                        <td style="padding:10px;border:1px solid #dadfe4;">'. $deadline .'</td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <div style="background:#fff3cd;border:2px solid #ffc107;border-radius:10px;
                                                        padding:15px;text-align:center;font-weight:bold;color:#795103;margin:25px 0;">
                                                ⚠ Please ensure to complete this task before the deadline.
                                            </div>

                                            <p style="font-size:15px;color:#333;margin:0 0 25px 0;">
                                                Thank you for your attention and commitment.
                                            </p>

                                            <div style="margin-top:35px;">
                                                <p style="margin:0 0 8px 0;">Regards,</p>
                                                <p style="margin:0;font-weight:600;color:#2c3e50;">Task Management System</p>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- FOOTER -->
                                    <tr>
                                        <td style="background:#2c3e50;padding:22px;text-align:center;">
                                            <p style="color:#bdc3c7;font-size:13px;margin:0;">This is an automated reminder.</p>
                                            <p style="color:#95a5a6;font-size:12px;margin:8px 0 0 0;">Please do not reply to this email.</p>
                                            <p style="color:#95a5a6;font-size:12px;margin:8px 0 0 0;">© '. date("Y") .' KR CONNECT – All Rights Reserved</p>
                                        </td>
                                    </tr>

                                </table>
                            </td>
                        </tr>
                    </table>
                </body>
                </html>';

                if (sendMailTMS($faculty_email, $faculty_name, $subject, $body)) {
                    $sentCount++;
                    $mail_status[] = "{$faculty_name} → Reminder mail sent successfully";
                } else {
                    $failedCount++;
                    $mail_status[] = "{$faculty_name} → Mail sending failed";
                }
            }
        } else {
            $mail_status[] = "No tasks with deadline in {$daysBefore} day(s).";
        }

        // mark as run for today only if at least one mail sent
        if ($sentCount > 0) {
            file_put_contents($runFile, $today);
        }

        echo json_encode([
            'response'    => 'success',
            'message'     => "Reminder mail process completed. Sent: {$sentCount}, Failed: {$failedCount}",
            'mail_status' => $mail_status
        ]);
        break;
        
    default:
        echo json_encode(["message" => "Invalid action"]);
}

//faculty backnd - report



?>