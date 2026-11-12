
<?php
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once __DIR__ . '/../db_connect.php';

$route = get_backend_route();

if (!$route) {
    echo json_encode(['success' => false, 'message' => 'Backend route not specified']);
    exit;
}

switch ($route) {
    case 'addMeeting':
        handle_add_meeting($conn);
        break;
    case 'fetchAssignedMeetings':
        handle_fetch_assigned_meetings($conn);
        break;
    case 'getAvailableSlots':
        handle_get_available_slots($conn);
        break;
    case 'getAvailableSlots_fac':
        handle_get_available_slots_fac($conn);
        break;
    case 'notifyMeeting':
        handle_notify_meeting();
        break;
    case 'principalMeetings':
        handle_principal_meetings($conn);
        break;
    case 'getOfficials':
        handle_get_officials($conn);
        break;
    case 'reportbackend':
        handle_report_backend();
        break;
    case 'requestMeeting':
        handle_request_meeting($conn);
        break;
    case 'submitMeetingRequest':
        handle_submit_meeting_request($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid backend route']);
        exit;
}

function get_backend_route(): ?string
{
    if (defined('BACKEND_ROUTE')) {
        return BACKEND_ROUTE;
    }

    if (!empty($_REQUEST['route'])) {
        return $_REQUEST['route'];
    }

    if (!empty($_REQUEST['endpoint'])) {
        return $_REQUEST['endpoint'];
    }

    return null;
}

function handle_add_meeting($conn): void
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "DB Connection failed: " . $conn->connect_error
        ]);
        exit;
    }

    $ph_user_id = $_SESSION['user_id'];
    $action = $_POST['action'] ?? '';
    error_log("=== MEETING API CALL ===");
    error_log("POST Data: " . print_r($_POST, true));
    error_log("GET Data: " . print_r($_GET, true));

    if ($action == 'filterfaculty') {
        $sql = 'SELECT name from Users where dept = ? OR add_role = ?';
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
            exit;
        }

        if (!$stmt->bind_param('ss', $_POST['dept'], $_POST['dept'])) {
            echo json_encode(['success' => false, 'message' => 'Bind failed: ' . $stmt->error]);
            exit;
        }

        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
            exit;
        }

        $res = $stmt->get_result();
        if (!$res) {
            echo json_encode(['success' => false, 'message' => 'Get result failed: ' . $stmt->error]);
            exit;
        }

        echo json_encode($res->fetch_all(MYSQLI_ASSOC));
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && (!isset($_POST['action']) || $_POST['action'] != 'filterfaculty')) {
        $title = $_POST['title'];
        $agenda = $_POST['agenda'];
        $dept = $_POST['staff'];
        $fac = $_POST['fac_name'];
        $datetime = $_POST['datetime'];
        $mode = $_POST['mode'];
        $location = $_POST['location'];

        $event_title = $title . " (" . $mode . ")";
        $event_description = "Agenda: $agenda\nLocation/Link: $location\nDate & Time: $datetime";

        if ($fac === "Everyone") {
            $sql = "INSERT INTO Calendar (task_id, user_id, assigned_by, event_title, event_description)
                    SELECT NULL, u.user_id, ?, ?, ?
                    FROM Users u
                    WHERE u.add_role LIKE CONCAT('%', ?, '%')";
            $stmt2 = $conn->prepare($sql);
            if ($stmt2) {
                $stmt2->bind_param('ssss', $ph_user_id, $event_title, $event_description, $dept);
                $ok = $stmt2->execute();
                $stmt2->close();
                if ($ok) {
                    echo json_encode(["success" => true]);
                } else {
                    echo json_encode(["success" => false, "message" => $conn->error]);
                }
            } else {
                echo json_encode(["success" => false, "message" => $conn->error]);
            }
        } else {
            $userid = "SELECT user_id from Users where name = ?";
            $stmt = $conn->prepare($userid);
            $stmt->bind_param('s', $fac);
            $stmt->execute();
            $result= $stmt->get_result();
            $res = $result->fetch_assoc();

            $sql = "INSERT INTO Calendar (task_id, user_id, assigned_by, event_title, event_description)
                    VALUES (NULL, '{$res['user_id']}', '{$ph_user_id}', '$event_title', '$event_description')";

            if (mysqli_query($conn, $sql)) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "message" => mysqli_error($conn)]);
            }
        }

        mysqli_close($conn);
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET['fetchMeetings']) && !isset($_GET['fetchChairmanMeetings'])) {
        $dept_query = "SELECT dept FROM Users WHERE user_id = '$ph_user_id'";
        $dept_result = mysqli_query($conn, $dept_query);
        $department = '';

        if ($dept_result && mysqli_num_rows($dept_result) > 0) {
            $department = mysqli_fetch_assoc($dept_result)['dept'];
        }

        $sql = "SELECT user_id, name FROM Users WHERE dept = '$department' AND role = 'Staff'";
        $result = mysqli_query($conn, $sql);
        $staffs = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $staffs[] = $row;
        }

        echo json_encode($staffs);
        mysqli_close($conn);
        exit;
    }

    if (isset($_GET['fetchMeetings'])) {
        $dept_query = "SELECT dept FROM Users WHERE user_id = ?";
        $dept_stmt = $conn->prepare($dept_query);
        $department = '';
        if ($dept_stmt) {
            $dept_stmt->bind_param('s', $ph_user_id);
            $dept_stmt->execute();
            $dept_res = $dept_stmt->get_result();
            $department = $dept_res->fetch_assoc()['dept'] ?? '';
            $dept_stmt->close();
        }

        // Return meetings where the user is the ASSIGNER (created by this user)
        // Meetings assigned TO this user appear only in Chairman/Requested tabs
        $sql = "
            SELECT 
                u.name AS staff_name,
                u.email AS staff_email,
                c.event_title,
                c.event_description
            FROM Calendar c
            JOIN Users u ON c.user_id = u.user_id
            WHERE c.assigned_by = ?
            ORDER BY c.task_id DESC
        ";

        $stmt = $conn->prepare($sql);
        $meetings = [];
        if ($stmt) {
            $stmt->bind_param('s', $ph_user_id);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $desc = array_map('trim', explode("\n", $row['event_description']));
                    $agenda = str_replace('Agenda: ', '', $desc[0] ?? '-');
                    $location = str_replace('Location/Link: ', '', $desc[1] ?? '-');
                    $datetime = str_replace('Date & Time: ', '', $desc[2] ?? '-');
                    $formatted_datetime = $datetime;
                    $timestamp = strtotime($datetime);
                    if ($timestamp !== false) {
                        $formatted_datetime = date("d M Y, h:i A", $timestamp);
                    }

                    $meetings[] = [
                        "title" => $row['event_title'],
                        "staff" => $row['staff_name'],
                        "agenda" => $agenda,
                        "location" => $location,
                        "datetime" => $formatted_datetime,
                        "email" => $row['staff_email'] ?? ''
                    ];
                }
            }
            $stmt->close();
        }

        echo json_encode($meetings);
        mysqli_close($conn);
        exit;
    }

    // For HOD/Portfolio: fetch meetings scheduled by this user (alias of fetchMeetings)
    if (isset($_GET['fetchHodScheduled'])) {
        $dept_query = "SELECT dept FROM Users WHERE user_id = '$ph_user_id'";
        $dept_result = mysqli_query($conn, $dept_query);
        $department = mysqli_fetch_assoc($dept_result)['dept'] ?? '';

        $sql = "
            SELECT 
                u.name AS staff_name,
                u.email AS staff_email,
                c.event_title,
                c.event_description
            FROM Calendar c
            JOIN Users u ON c.user_id = u.user_id
            WHERE c.assigned_by = '$ph_user_id'
            ORDER BY c.task_id DESC
        ";

        $result = mysqli_query($conn, $sql);
        $meetings = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $desc = array_map('trim', explode("\n", $row['event_description']));
            $agenda = str_replace('Agenda: ', '', $desc[0] ?? '-');
            $location = str_replace('Location/Link: ', '', $desc[1] ?? '-');
            $datetime = str_replace('Date & Time: ', '', $desc[2] ?? '-');
            $timestamp = strtotime($datetime);
            $formatted_datetime = $datetime;
            if ($timestamp !== false) {
                $formatted_datetime = date("d M Y, h:i A", $timestamp);
            }

            $meetings[] = [
                "title" => $row['event_title'],
                "staff" => $row['staff_name'],
                "agenda" => $agenda,
                "location" => $location,
                "datetime" => $formatted_datetime,
                "email" => $row['staff_email'] ?? ''
            ];
        }

        echo json_encode($meetings);
        mysqli_close($conn);
        exit;
    }

    // For HOD/Portfolio: fetch meeting requests targeted to this user (incoming requests)
    if (isset($_GET['fetchHodRequests'])) {
        // Get current user's id, role and add_role to match legacy stored values
        $userStmt = $conn->prepare("SELECT user_id, role, add_role, name, dept FROM Users WHERE user_id = ? LIMIT 1");
        $role = '';
        $add_role = '';
        if ($userStmt) {
            $userStmt->bind_param('s', $ph_user_id);
            $userStmt->execute();
            $ures = $userStmt->get_result();
            if ($ures && $ures->num_rows > 0) {
                $urow = $ures->fetch_assoc();
                $role = $urow['role'] ?? '';
                $add_role = $urow['add_role'] ?? '';
            }
            $userStmt->close();
        }

        // Prepare IN list (user_id, role, add_role) — filter out empty values
        $candidates = array_values(array_unique(array_filter([$ph_user_id, $role, $add_role], function($v){ return $v !== null && $v !== ''; })));

        if (empty($candidates)) {
            echo json_encode([]);
            exit;
        }

        $placeholders = implode(',', array_fill(0, count($candidates), '?'));
        $sql = "SELECT ra.request_id, ra.request_by, ra.requested_to, ra.purpose, ra.request_time, ra.status,
                       u.name AS requester_name, u.dept AS requester_dept
                FROM Request_Appointment ra
                LEFT JOIN Users u ON ra.request_by = u.user_id
                WHERE ra.requested_to IN ($placeholders)
                ORDER BY ra.request_time DESC";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode([]);
            exit;
        }

        // bind parameters dynamically
        $types = str_repeat('s', count($candidates));
        $stmt->bind_param($types, ...$candidates);
        if (!$stmt->execute()) {
            echo json_encode([]);
            exit;
        }

        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'request_id' => $row['request_id'],
                'request_by' => $row['request_by'],
                'requester_name' => $row['requester_name'] ?? $row['request_by'],
                'requester_dept' => $row['requester_dept'] ?? '-',
                'purpose' => $row['purpose'],
                'request_time' => $row['request_time'],
                'status' => $row['status']
            ];
        }

        echo json_encode($data);
        exit;
    }

    if (isset($_GET['updateMeeting'])) {
        $calendar_id = $_POST['calendar_id'];
        $title = trim($_POST['title']);
        $agenda = trim($_POST['agenda']);
        $datetime = trim($_POST['datetime']);
        $mode = trim($_POST['mode']);
        $location = trim($_POST['location']);

        $event_title = $title . " (" . $mode . ")";

        $existing_query = "SELECT event_description FROM Calendar WHERE calendar_id = '$calendar_id'";
        $existing_result = mysqli_query($conn, $existing_query);
        $participants_info = "\nParticipants: All Staff";

        if ($existing_result && mysqli_num_rows($existing_result) > 0) {
            $existing_data = mysqli_fetch_assoc($existing_result);
            $existing_desc = $existing_data['event_description'];

            $desc_lines = explode("\n", $existing_desc);
            foreach ($desc_lines as $line) {
                if (strpos(trim($line), 'Participants:') === 0) {
                    $participants_info = "\n" . trim($line);
                    break;
                }
            }
        }

        $event_description = "Agenda: $agenda\nLocation/Link: $location\nDate & Time: $datetime\nMode: $mode" . $participants_info;

        $sql = "UPDATE Calendar SET event_title = '$event_title', event_description = '$event_description' WHERE calendar_id = '$calendar_id'";

        if (mysqli_query($conn, $sql)) {
            echo json_encode(["success" => true, "message" => "Meeting updated successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error: " . mysqli_error($conn)]);
        }
        exit;
    }

    // Fetch meetings scheduled by Chairman for a specific Principal
    if (isset($_GET['fetchChairmanMeetings'])) {
        error_log("API REACHED");

        $ph_user_id = $_SESSION['user_id'];

        $sql = "
            SELECT 
                c.calendar_id,
                c.event_title,
                c.event_description,
                c.assigned_by,
                u.name AS chairman_name
            FROM calendar c
            JOIN Users u ON c.assigned_by = u.user_id
            WHERE c.user_id = ?
            ORDER BY c.calendar_id DESC
        ";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
            exit;
        }

        $stmt->bind_param('s', $ph_user_id);
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Failed to fetch meetings']);
            exit;
        }

        $result = $stmt->get_result();
        $meetings = [];

        while ($row = $result->fetch_assoc()) {
            // Parse event description
            $desc = array_map('trim', explode("\n", $row['event_description'] ?? ''));
            $agenda = str_replace('Agenda: ', '', $desc[0] ?? 'N/A');
            $location = str_replace('Location/Link: ', '', $desc[1] ?? 'N/A');
            $datetime = str_replace('Date & Time: ', '', $desc[2] ?? 'N/A');

            $meetings[] = [
                'calendar_id' => $row['calendar_id'],
                'title' => $row['event_title'],
                'agenda' => $agenda,
                'location' => $location,
                'datetime' => $datetime,
                'mode' => 'N/A'
            ];
        }

        echo json_encode(['success' => true, 'data' => $meetings]);
        exit;
    }
}

function handle_fetch_assigned_meetings($conn): void
{
    header('Content-Type: application/json');
    ini_set('display_errors', 0);
    error_reporting(0);

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'FACULTY') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    $ph_user_id = $_SESSION['user_id'];

    $fallbackSql = "SELECT calendar_id, event_title, event_description, assigned_by
            FROM Calendar 
            WHERE user_id = ?
            ORDER BY calendar_id DESC";

    $stmt = $conn->prepare($fallbackSql);
    if (!$stmt) {
        $dberr = $conn->error ?? 'unknown';
        echo json_encode(['success' => false, 'message' => 'DB prepare failed', 'db_error' => $dberr, 'attempted' => 'fallback']);
        exit;
    }
    $stmt->bind_param('s', $ph_user_id);
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'DB execute failed']);
        exit;
    }
    $res = $stmt->get_result();

    $rows = [];
    $assignedByIds = [];
    while ($r = $res->fetch_assoc()) {
        $desc = isset($r['event_description']) ? explode("\n", $r['event_description']) : [];
        $agenda = '';
        foreach ($desc as $line) {
            if (stripos(trim($line), 'Agenda:') === 0) {
                $agenda = trim(str_ireplace('Agenda:', '', $line));
            }
        }

        $desc = $r['event_description'] ?? '';

        $schedule_date = '';
        $start_time = '';
        $end_time = '';
        $venue = '-';

        if (preg_match('/Date \& Time:\s*(.*)/i', $desc, $m)) {
            $dt = trim($m[1]);
            if (preg_match('/(\d{4}-\d{2}-\d{2})\s*(.*)/', $dt, $mm)) {
                $schedule_date = $mm[1];
                $times = trim($mm[2]);
                if (strpos($times, '-') !== false) {
                    [$s, $e] = array_map('trim', explode('-', $times, 2));
                    $start_time = $s;
                    $end_time = $e;
                } else {
                    $start_time = $times;
                }
            }
        }

        if (preg_match('/Location\/Link:\s*(.*)/i', $desc, $m) || 
            preg_match('/Location:\s*(.*)/i', $desc, $m)) {
            $venue = trim($m[1]);
        }

        $rows[] = [
            'id' => $r['calendar_id'],
            'title' => $r['event_title'],
            'description' => $agenda ?: $r['event_description'],
            'schedule_date' => $schedule_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'venue' => $venue,
            'assigned_by' => $r['assigned_by']
        ];
        if (!empty($r['assigned_by'])) {
            $assignedByIds[] = $r['assigned_by'];
        }
    }

    $assignedByMap = [];
    $assignedByIds = array_values(array_unique($assignedByIds));
    if (!empty($assignedByIds)) {
        $sanitized = array_map('intval', $assignedByIds);
        $inList = implode(',', $sanitized);

        $userTables = ['Users', 'users'];
        foreach ($userTables as $tbl) {
            $nameQuery = "SELECT user_id, name FROM {$tbl} WHERE user_id IN ({$inList})";
            $uStmt = $conn->prepare($nameQuery);
            if (!$uStmt) {
                continue;
            }
            if (!$uStmt->execute()) {
                continue;
            }
            $ures = $uStmt->get_result();
            while ($ur = $ures->fetch_assoc()) {
                $assignedByMap[$ur['user_id']] = $ur['name'];
            }
            if (!empty($assignedByMap)) break;
        }
    }

    foreach ($rows as &$row) {
        $ab = $row['assigned_by'];
        if ($ab !== null && $ab !== '' && isset($assignedByMap[$ab])) {
            $row['assigned_by'] = $assignedByMap[$ab];
        }
    }

    echo json_encode(['success' => true, 'data' => $rows]);
    exit;
}

function handle_get_available_slots($conn): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['date'])) {
        $date = $_GET['date'];
        $requestedTo = $_GET['requested_to'] ?? 'P001';

        $query = "SELECT request_time 
                  FROM Request_Appointment 
                  WHERE DATE(request_time) = ? 
                  AND requested_to = ? 
                  AND status IN ('Approved', 'Pending')";
                  
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $date, $requestedTo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $bookedSlots = [];
        while ($row = $result->fetch_assoc()) {
            $time = date('H:i', strtotime($row['request_time']));
            $end_time = date('H:i', strtotime($row['request_time'] . ' +1 hour'));
            $bookedSlots[] = [
                'start' => $time,
                'end' => $end_time
            ];
        }
        
        $slots = [];
        $start = strtotime('08:45');
        $end = strtotime('17:00');
        $currentTime = new DateTime();
        
        while ($start < $end) {
            $slotEnd = strtotime('+30 minutes', $start);
            $slotDateTime = new DateTime($date . ' ' . date('H:i', $start));
            
            $interval = $currentTime->diff($slotDateTime);
            $hoursDiff = ($interval->days * 24) + $interval->h + ($interval->i / 60);
            
            $slot = [
                'start' => date('H:i', $start),
                'end' => date('H:i', $slotEnd),
                'available' => true
            ];
            
            if ($slotDateTime < $currentTime || ($hoursDiff < 3 && $interval->invert == 0)) {
                $slot['available'] = false;
            }
            
            foreach ($bookedSlots as $booked) {
                $bookedStart = strtotime($booked['start']);
                $bookedEnd = strtotime($booked['end']);
                
                if ($start < $bookedEnd && $slotEnd > $bookedStart) {
                    $slot['available'] = false;
                    break;
                }
            }
            
            $slots[] = $slot;
            $start = $slotEnd;
        }
        
        echo json_encode(['success' => true, 'slots' => $slots]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }
    exit;
}

function handle_get_available_slots_fac($conn): void
{
    header('Content-Type: application/json');
    ini_set('display_errors', 0);
    error_reporting(0);

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Authentication required']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['date'])) {
        $date = $_GET['date'];

        $requestedTo = $_GET['requested_to'] ?? 'P001';

        // If requestedTo is 'CHAIRMAN', resolve it to actual Chairman's user_id
        if ($requestedTo === 'CHAIRMAN') {
            $chairmanQuery = $conn->prepare("SELECT user_id FROM Users WHERE add_role LIKE '%CHAIRMAN%' LIMIT 1");
            if ($chairmanQuery && $chairmanQuery->execute()) {
                $result = $chairmanQuery->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $requestedTo = $row['user_id'];
                } else {
                    echo json_encode(['success' => false, 'message' => 'Chairman not found']);
                    exit;
                }
            }
        }

        $query = "SELECT request_time FROM Request_Appointment WHERE DATE(request_time) = ? AND requested_to = ? AND status IN ('Approved','Pending')";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'DB prepare failed']);
            exit;
        }
        $stmt->bind_param('ss', $date, $requestedTo);
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'DB execute failed']);
            exit;
        }
        $res = $stmt->get_result();

        $booked = [];
        while ($r = $res->fetch_assoc()) {
            $start = date('H:i', strtotime($r['request_time']));
            $end = date('H:i', strtotime($r['request_time'] . ' +1 hour'));
            $booked[] = ['start' => $start, 'end' => $end];
        }

        $slots = [];
        $slotStart = strtotime('08:45');
        $slotEndLimit = strtotime('17:00');
        $now = new DateTime();

        while ($slotStart < $slotEndLimit) {
            $slotEnd = strtotime('+30 minutes', $slotStart);
            $slotDateTime = DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . date('H:i', $slotStart));

            $interval = $now->diff($slotDateTime);
            $hoursDiff = ($interval->days * 24) + $interval->h + ($interval->i / 60);

            $slot = [
                'start' => date('H:i', $slotStart),
                'end' => date('H:i', $slotEnd),
                'time' => date('H:i', $slotStart),
                'available' => true
            ];

            if ($slotDateTime < $now || ($hoursDiff < 3 && $interval->invert == 0)) {
                $slot['available'] = false;
            }

            foreach ($booked as $b) {
                $bStart = strtotime($b['start']);
                $bEnd = strtotime($b['end']);
                if ($slotStart < $bEnd && $slotEnd > $bStart) {
                    $slot['available'] = false;
                    break;
                }
            }

            $slots[] = $slot;
            $slotStart = $slotEnd;
        }

        echo json_encode(['success' => true, 'data' => $slots]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

function handle_notify_meeting(): void
{
    header('Content-Type: application/json');

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $email = trim($_POST['email'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $datetime = trim($_POST['datetime'] ?? '');
        $staff = trim($_POST['staff'] ?? '');
        $venue = trim($_POST['venue'] ?? '-');
        $agenda = trim($_POST['agenda'] ?? '');

        if (empty($email) || empty($title) || empty($datetime) || empty($staff)) {
            echo json_encode(["success"=>false,"message"=>"❌ Missing parameters"]);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["success"=>false,"message"=>"❌ Invalid email"]);
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

            $mail->setFrom('soundaryadhanas6155@gmail.com', 'MKCE Meeting System');
            $mail->addAddress($email, $staff);

            $mail->isHTML(true);
            $mail->Subject = "Meeting Notice - $title";

            $timestamp = strtotime($datetime);
            $formatted_meeting_date = $timestamp ? date('F d, Y', $timestamp) : $datetime;
            $formatted_meeting_time = $timestamp ? date('h:i A', $timestamp) : $datetime;
            $notice_generated_date = date('F d, Y');

            $sender_name = $_SESSION['name'] ?? 'Management Team';
            $sender_role = $_SESSION['role'] ?? 'Meeting Coordinator';
            $sender_dept = $_SESSION['dept'] ?? '';

            $title_html = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
            $staff_html = htmlspecialchars($staff, ENT_QUOTES, 'UTF-8');
            $venue_html = htmlspecialchars($venue !== '' ? $venue : '-', ENT_QUOTES, 'UTF-8');
            $agenda_html = nl2br(htmlspecialchars($agenda, ENT_QUOTES, 'UTF-8'));
            $sender_name_html = htmlspecialchars($sender_name, ENT_QUOTES, 'UTF-8');
            $sender_role_html = htmlspecialchars($sender_role, ENT_QUOTES, 'UTF-8');
            $sender_dept_html = htmlspecialchars($sender_dept, ENT_QUOTES, 'UTF-8');

            $from_line_html = "{$sender_name_html}, {$sender_role_html}";
            if ($sender_dept_html !== '') {
                $from_line_html .= " - {$sender_dept_html}";
            }

            $signature_role_line_html = $sender_role_html;
            if ($sender_dept_html !== '') {
                $signature_role_line_html .= ", {$sender_dept_html} Department";
            }

            $agenda_section = '';
            if (!empty($agenda)) {
                $agenda_section = "
                                                <div style='border-radius: 12px; border-left: 5px solid #1e88e5; background: #f1f8ff; padding: 18px; margin: 20px 0;'>
                                                    <div style='font-weight: bold; margin-bottom: 10px; color: #1e3a5f;'>Agenda</div>
                                                    <div style='font-size:14px; line-height:1.8; color:#324960;'>{$agenda_html}</div>
                                                </div>";
            }

            $statusLabel = 'Scheduled Meeting';
            $statusColor = '#1e88e5';
            $statusBg = '#e3f2fd';
            $statusBorder = '#1e88e5';
            $currentYear = date('Y');

            $mail->Body = <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style>
                    *{margin:0;padding:0;box-sizing:border-box;}
                    body{font-family:'Arial','Helvetica',sans-serif;background:#f8f9fa;color:#333;}
                    table{border-collapse:collapse;}
                </style>
            </head>
            <body>
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin:20px auto;background:white;border-radius:12px;box-shadow:0 6px 12px rgba(0,0,0,0.08);overflow:hidden;">
                                <tr>
                                    <td style="background:linear-gradient(135deg,#1e88e5 0%,#1565c0 100%);padding:30px;text-align:center;">
                                        <h1 style="color:white;margin:0;font-size:26px;">KR CONNECT</h1>
                                        <p style="color:#dbeafe;margin-top:8px;font-size:15px;">M. Kumarasamy College of Engineering</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background:{$statusBg};border-left:5px solid {$statusBorder};padding:25px;">
                                        <div style="text-align:center;">
                                            <span style="display:inline-block;background:{$statusColor};color:#fff;padding:7px 18px;border-radius:25px;font-size:13px;font-weight:bold;margin-bottom:10px;">{$statusLabel}</span>
                                            <p style="margin:0;font-size:20px;color:{$statusColor};font-weight:600;">Meeting Status Update</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:30px;">
                                        <p style="font-size:15px;color:#333;margin:0 0 20px 0;">Date: <strong>{$notice_generated_date}</strong></p>
                                        <p style="font-size:15px;color:#333;margin:0 0 20px 0;">To: <strong>{$staff_html}</strong></p>
                                        <p style="font-size:15px;color:#333;margin:0 0 25px 0;">From: <strong>{$from_line_html}</strong></p>
                                        <p style="font-size:15px;color:#333;margin:0 0 20px 0;">We are pleased to inform you about the following meeting details:</p>
                                        <div style="background:linear-gradient(135deg,#f8f9fa 0%,#e9ecef 100%);border-radius:15px;padding:22px;border:2px solid #dee2e6;margin:25px 0;">
                                            <div style="text-align:center;margin-bottom:15px;">
                                                <span style="background:#6c757d;color:#fff;padding:6px 15px;border-radius:20px;font-size:13px;">MEETING DETAILS</span>
                                            </div>
                                            <table style="width:100%;font-size:14px;color:#2c3e50;">
                                                <tr>
                                                    <td style="width:35%;padding:10px;font-weight:600;background:#f1f3f5;border:1px solid #dadfe4;">Meeting Title</td>
                                                    <td style="padding:10px;border:1px solid #dadfe4;">{$title_html}</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:10px;font-weight:600;background:#f1f3f5;border:1px solid #dadfe4;">Meeting Date</td>
                                                    <td style="padding:10px;border:1px solid #dadfe4;">{$formatted_meeting_date}</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:10px;font-weight:600;background:#f1f3f5;border:1px solid #dadfe4;">Time</td>
                                                    <td style="padding:10px;border:1px solid #dadfe4;">{$formatted_meeting_time}</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:10px;font-weight:600;background:#f1f3f5;border:1px solid #dadfe4;">Location</td>
                                                    <td style="padding:10px;border:1px solid #dadfe4;">{$venue_html}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        {$agenda_section}
                                        <div style="background:#fff3cd;border:2px solid #ffc107;border-radius:10px;padding:15px;text-align:center;font-weight:bold;color:#795103;margin:25px 0;">
                                            ⚠ Your attendance is mandatory
                                        </div>
                                        <p style="font-size:15px;color:#333;margin:0 0 15px 0;">Please ensure that you review any pre-read material shared and arrive on time.</p>
                                        <p style="font-size:15px;color:#333;margin:0 0 25px 0;">We appreciate your cooperation and look forward to your valuable participation.</p>
                                        <div style="margin-top:35px;">
                                            <p style="margin:0 0 8px 0;">Sincerely,</p>
                                            <p style="margin:0;font-weight:600;color:#2c3e50;">{$sender_name_html}</p>
                                            <p style="margin:3px 0 0 0;color:#5a6c7d;font-size:14px;">{$signature_role_line_html}</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background:#2c3e50;padding:22px;text-align:center;">
                                    <p style="color:#bdc3c7;font-size:13px;margin:0;">This is an automated notification from the MKCE Meeting System.</p>
                                    <p style="color:#95a5a6;font-size:12px;margin:8px 0 0 0;">Please do not reply to this email.</p>
                                    <p style="color:#95a5a6;font-size:12px;margin:8px 0 0 0;">&copy; {$currentYear} M.Kumarasamy College of Engineering - All Rights Reserved</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </body>
            </html>
            HTML;

            $mail->AltBody = "Meeting Notice\n\n"
                . "Date: {$notice_generated_date}\n"
                . "To: {$staff}\n"
                . "From: {$sender_name}, {$sender_role}" . (!empty($sender_dept) ? " - {$sender_dept}" : "") . "\n\n"
                . "Meeting Title: {$title}\n"
                . "Meeting Date: {$formatted_meeting_date}\n"
                . "Time: {$formatted_meeting_time}\n"
                . "Location: {$venue}\n"
                . (!empty($agenda) ? "\nAgenda:\n{$agenda}\n" : "")
                . "\nYour attendance is mandatory.\n\n"
                . "Sincerely,\n{$sender_name}\n{$sender_role}";

            $mail->send();
            echo json_encode(["success"=>true,"message"=>"✅ Email sent successfully"]);

        } catch (\PHPMailer\PHPMailer\Exception $e) {
            echo json_encode(["success"=>false,"message"=>"❌ Mailer Error: {$mail->ErrorInfo}"]);
        }

    } else {
        echo json_encode(["success"=>false,"message"=>"❌ Invalid request"]);
    }
    exit;
}

function handle_principal_meetings($conn): void
{
    header('Content-Type: application/json');

    $hod_user_id = $_SESSION['user_id'];

    $hod_name = '';
    $stmt = $conn->prepare("SELECT name FROM Users WHERE user_id = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param('s', $hod_user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $hod_name = $res->fetch_assoc()['name'];
        }
        $stmt->close();
    }

    // Include meetings assigned by Principal, ED and CHRO (also return assigner name)
    $query = "SELECT c.calendar_id, c.task_id, c.user_id, c.event_title, 
              c.event_description, c.assigned_by, uassign.name AS assigned_by_name
              FROM calendar c
              JOIN Users uassign ON c.assigned_by = uassign.user_id
              WHERE c.user_id = ?
              AND uassign.role IN ('PRINCIPAL','ED','CHRO')
              ORDER BY c.calendar_id DESC";
              
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]);
        exit;
    }

    $stmt->bind_param('s', $hod_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $meetings = [];
    while ($row = $result->fetch_assoc()) {
        $desc = $row['event_description'] ?? '';

        $venue = '-';
        if (preg_match('/Location\/Link:\s*(.*)/i', $desc, $m)) {
            $venue = trim($m[1]);
        } elseif (preg_match('/Location:\s*(.*)/i', $desc, $m)) {
            $venue = trim($m[1]);
        }

        $schedule_date = '';
        $start_time = '';
        $end_time = '';

        if (preg_match('/Date \& Time:\s*(.*)/i', $desc, $m)) {
            $dt = trim($m[1]);
            $ts = strtotime($dt);
            if ($ts !== false) {
                $schedule_date = date('Y-m-d', $ts);
                $start_time = date('H:i', $ts);
            } else {
                if (preg_match('/(\d{4}-\d{2}-\d{2})\s*(.*)/', $dt, $mm)) {
                    $schedule_date = $mm[1];
                    $times = trim($mm[2]);
                    if (strpos($times, '-') !== false) {
                        [$s, $e] = array_map('trim', explode('-', $times, 2));
                        $start_time = $s;
                        $end_time = $e;
                    } else {
                        $start_time = $times;
                    }
                } else {
                    $schedule_date = $dt;
                }
            }
        }

        $agenda = '';
        if (preg_match('/Agenda:\s*(.*?)(?:\n|$)/i', $desc, $m)) {
            $agenda = trim($m[1]);
        }

        $meetings[] = [
            'calendar_id' => $row['calendar_id'],
            'task_id' => $row['task_id'],
            'event_title' => $row['event_title'],
            'event_description' => $row['event_description'],
            'schedule_date' => $schedule_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'venue' => $venue,
            'agenda' => $agenda,
            'assigned_by' => isset($row['assigned_by_name']) && $row['assigned_by_name'] ? $row['assigned_by_name'] : $row['assigned_by']
        ];
    }

    $stmt->close();
    $conn->close();

    echo json_encode(["success" => true, "data" => $meetings]);
    exit;
}

function handle_get_officials($conn): void
{
    header('Content-Type: application/json');

    // Check if CHAIRMAN should be included (via query param or POST data)
    $includeChairman = isset($_GET['include_chairman']) || isset($_POST['include_chairman']);
    
    if ($includeChairman) {
        $inList = "'PRINCIPAL','ED','CHRO','CHAIRMAN'";
    } else {
        $inList = "'PRINCIPAL','ED','CHRO'";
    }

    $sql = "SELECT user_id, name, role FROM Users WHERE role IN ({$inList}) ORDER BY FIELD(role, 'PRINCIPAL','ED','CHRO','CHAIRMAN')";
    $res = mysqli_query($conn, $sql);
    $out = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $out[] = [
                'user_id' => $row['user_id'],
                'name' => $row['name'],
                'role' => $row['role']
            ];
        }
    }

    echo json_encode(['success' => true, 'data' => $out]);
    exit;
}

function handle_report_backend(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    header('Content-Type: application/json');
    error_reporting(E_ALL);
    ini_set('display_errors', 0);

    // Use the existing MySQLi connection from db_connect.php
    global $conn;
    
    if (!isset($conn) || $conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . ($conn->connect_error ?? 'Connection not available')]);
        exit;
    }

    $userId = $_SESSION['user_id'] ?? null;
    $userRole = $_SESSION['role'] ?? null;
    $userDept = $_SESSION['department'] ?? null;

    if (!$userId) {
        echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
        exit;
    }

    if (!$userDept || !$userRole) {
        $stmt = $conn->prepare("SELECT department, role FROM faculty WHERE fac_id = ?");
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $userRole = $user['role'] ?? 'FACULTY';
        $userDept = $user['department'] ?? '';
    }

    $isHOD = ($userRole === 'HOD');
    $isPrincipal = in_array($userRole, ['PRINCIPAL', 'CHAIRMAN', 'ADMIN']);

    $action = $_POST['action'] ?? $_GET['action'] ?? '';

    switch ($action) {
        case 'getUserInfo':
            report_get_user_info($userRole, $userDept, $isHOD);
            break;
        case 'getAcademicYears':
            report_get_academic_years($conn);
            break;
        case 'getDepartments':
            report_get_departments($conn, $isHOD, $userDept);
            break;
        case 'getFacultyList':
            report_get_faculty_list($conn, $isHOD, $userDept);
            break;
        case 'getDepartmentTaskSummary':
            report_get_department_task_summary($conn, $isHOD, $userDept);
            break;
        case 'getDepartmentPerformance':
            report_get_department_performance($conn, $isHOD, $userDept);
            break;
        case 'getFacultyPerformance':
            report_get_faculty_performance($conn, $isHOD, $userDept);
            break;
        case 'getFacultyTasks':
            report_get_faculty_tasks($conn, $isHOD, $userDept);
            break;
        case 'getExecutiveSummary':
            handle_executive_summary($conn);
            $conn->close();
            break; 
        case 'getMeetingReports':
            report_get_meeting_reports($conn, $isPrincipal);
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
}
function report_build_meeting_filters(): array
{
    $calendarWhere = '';
    $requestWhere = '';
    $params = [];

    $periodType = $_POST['periodType'] ?? 'all';
    $year = $_POST['year'] ?? '';
    $month = $_POST['month'] ?? '';
    $week = $_POST['week'] ?? '';
    $startDate = $_POST['startDate'] ?? '';
    $endDate = $_POST['endDate'] ?? '';

    if ($periodType === 'month' && !empty($month) && !empty($year)) {
        $yearParts = explode('-', $year);
        $monthNum = intval($month);
        $yearNum = ($monthNum >= 8) ? $yearParts[0] : $yearParts[1];

        $calendarWhere .= " AND YEAR(STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(c.event_description, 'Date & Time: ', -1), '\n', 1), '%Y-%m-%d')) = :year 
                           AND MONTH(STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(c.event_description, 'Date & Time: ', -1), '\n', 1), '%Y-%m-%d')) = :month";
        $requestWhere .= " AND YEAR(ra.request_time) = :year AND MONTH(ra.request_time) = :month";
        $params[':year'] = $yearNum;
        $params[':month'] = $monthNum;

    } elseif ($periodType === 'week' && !empty($week) && !empty($year)) {
        $yearParts = explode('-', $year);
        $yearNum = $yearParts[0];
        $weekNum = intval($week);

        $jan1 = strtotime("$yearNum-01-01");
        $dayOfWeek = date('w', $jan1);
        $firstSaturday = $dayOfWeek <= 6 ? (6 - $dayOfWeek) : 0;
        $weekStart = strtotime("+$firstSaturday days +" . ($weekNum - 1) * 7 . " days", $jan1);
        $weekEnd = strtotime("+6 days", $weekStart);

        $calendarWhere .= " AND STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(c.event_description, 'Date & Time: ', -1), '\n', 1), '%Y-%m-%d') BETWEEN :weekStart AND :weekEnd";
        $requestWhere .= " AND DATE(ra.request_time) BETWEEN :weekStart AND :weekEnd";
        $params[':weekStart'] = date('Y-m-d', $weekStart);
        $params[':weekEnd'] = date('Y-m-d', $weekEnd);

    } elseif ($periodType === 'custom' && !empty($startDate) && !empty($endDate)) {
        $calendarWhere .= " AND STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(c.event_description, 'Date & Time: ', -1), '\n', 1), '%Y-%m-%d') BETWEEN :startDate AND :endDate";
        $requestWhere .= " AND DATE(ra.request_time) BETWEEN :startDate AND :endDate";
        $params[':startDate'] = $startDate;
        $params[':endDate'] = $endDate;
    }

    return [
        'calendar_where' => $calendarWhere,
        'request_where' => $requestWhere,
        'params' => $params
    ];
}
function report_get_meeting_reports($pdo, $isPrincipal): void
{
    if (!$isPrincipal) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        return;
    }

    try {
        $filters = report_build_meeting_filters();

        // Get KPIs
        $kpiSql = "SELECT 
                    COUNT(DISTINCT c.calendar_id) as total_scheduled,
                    (SELECT COUNT(*) FROM request_appointment WHERE status = 'Pending' {$filters['request_where']}) as pending_requests,
                    (SELECT COUNT(*) FROM request_appointment WHERE status = 'Approved' {$filters['request_where']}) as approved_requests,
                    (SELECT COUNT(*) FROM request_appointment WHERE status = 'Rejected' {$filters['request_where']}) as rejected_requests
                   FROM calendar c
                   WHERE c.assigned_by = 'P001' {$filters['calendar_where']}";

        $stmt = $pdo->prepare($kpiSql);
        $stmt->execute($filters['params']);
        $kpis = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get scheduled meetings
        $scheduledSql = "SELECT 
                c.event_title as title,
                c.event_description,
                u.name as organized_by
            FROM calendar c
            LEFT JOIN users u ON c.assigned_by = u.user_id
            WHERE c.assigned_by = 'P001' {$filters['calendar_where']}
            ORDER BY c.calendar_id DESC";

        $stmt = $pdo->prepare($scheduledSql);
        $stmt->execute($filters['params']);
        $scheduled = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $scheduledMeetings = array_map(function ($row) {
            $desc = $row['event_description'] ?? '';
            $lines = explode("\n", $desc);

            $agenda = '-';
            $location = '-';
            $datetime = '-';
            $participants = '-';

            foreach ($lines as $line) {
                if (stripos($line, 'Agenda:') === 0) {
                    $agenda = trim(str_ireplace('Agenda:', '', $line));
                } elseif (stripos($line, 'Location') === 0) {
                    $location = trim(preg_replace('/^Location[\/Link]:\s/i', '', $line));
                } elseif (stripos($line, 'Date & Time:') === 0) {
                    $datetime = trim(str_ireplace('Date & Time:', '', $line));
                } elseif (stripos($line, 'Participants:') === 0) {
                    $participants = trim(str_ireplace('Participants:', '', $line));
                }
            }

            return [
                'title' => $row['title'],
                'agenda' => $agenda,
                'datetime' => $datetime,
                'location' => $location,
                'participants' => $participants,
                'organized_by' => $row['organized_by'] ?? 'Principal',
                'status' => 'Scheduled'
            ];
        }, $scheduled);

        // Get requested meetings
        $requestedSql = "SELECT 
                ra.request_id,
                ra.request_by,
                ra.purpose,
                ra.request_time as requested_time,
                ra.status,
                u.name as requester,
                u.dept as department,
                u.role
            FROM request_appointment ra
            LEFT JOIN users u ON ra.request_by = u.user_id
            WHERE ra.requested_to = 'P001' {$filters['request_where']}
            ORDER BY ra.request_time DESC";

        $stmt = $pdo->prepare($requestedSql);
        $stmt->execute($filters['params']);
        $requestedMeetings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'kpis' => $kpis,
            'scheduledMeetings' => $scheduledMeetings,
            'requestedMeetings' => $requestedMeetings
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}


function handle_executive_summary($conn): void
{
    header('Content-Type: application/json');

    $executive = $_POST['executive'] ?? '';

    if (empty($executive)) {
        echo json_encode(['status' => 'error', 'message' => 'Executive role required']);
        exit;
    }

    // Map executive roles to their user_ids
    $roleMapping = [
        'PRINCIPAL' => 'P001',
        'CHRO' => 'CHRO',
        'ED' => 'ED'
    ];

    $userId = $roleMapping[$executive] ?? null;

    if (!$userId) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid executive role']);
        exit;
    }

    // Get tasks assigned BY this executive
    $sql = "SELECT 
                t.task_id,
                t.task_title as title,
                t.start_date,
                t.deadline,
                t.completed_date,
                t.Priority as priority,
                t.status as task_status,
                ta.status as assignment_status,
                ta.updated_at
            FROM tasks t
            LEFT JOIN taskassignment ta ON t.task_id = ta.task_id
            WHERE ta.assigned_to = ?
            ORDER BY t.start_date DESC";

    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'SQL prepare failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param('s', $userId);
    
    if (!$stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Query execution failed: ' . $stmt->error]);
        exit;
    }

    $result = $stmt->get_result();

    $tasks = [];
    $total = 0;
    $completed = 0;
    $pending = 0;
    $overdue = 0;
    $ontime = 0;

    while ($row = $result->fetch_assoc()) {
        $total++;
        
        // Determine status - prefer assignment status over task status
        $status = $row['assignment_status'] ?: $row['task_status'] ?: 'Pending';
        
        // Determine completed date
        $completedDate = $row['updated_at'] ?: $row['completed_date'];
        
        if ($status === 'Completed') {
            $completed++;
            if ($completedDate && $completedDate <= $row['deadline']) {
                $ontime++;
            }
        } elseif ($status === 'Pending' || $status === 'Assigned') {
            $pending++;
        } elseif ($status === 'Overdue') {
            $overdue++;
        }

        $tasks[] = [
            'task_id' => $row['task_id'],
            'title' => $row['title'],
            'start_date' => $row['start_date'],
            'deadline' => $row['deadline'],
            'completed_date' => $completedDate,
            'priority' => $row['priority'] ?: 'Normal',
            'status' => $status
        ];
    }

    $stmt->close();

    $completion_rate = $total > 0 ? round(($completed / $total) * 100, 1) : 0;
    $ontime_percent = $completed > 0 ? round(($ontime / $completed) * 100, 1) : 0;

    $kpis = [
        'total_tasks' => $total,
        'completed' => $completed,
        'pending' => $pending,
        'overdue' => $overdue,
        'completion_rate' => $completion_rate,
        'ontime_percent' => $ontime_percent
    ];

    echo json_encode([
        'status' => 'success',
        'kpis' => $kpis,
        'tasks' => $tasks
    ]);
    exit;
}

function report_get_user_info($role, $dept, $isHOD): void
{
    echo json_encode([
        'status' => 'success',
        'role' => $role,
        'department' => $dept,
        'isHOD' => $isHOD
    ]);
}

function report_get_academic_years($conn): void
{
    $result = $conn->query("
        SELECT 
            MIN(YEAR(start_date)) as min_year,
            MAX(YEAR(start_date)) as max_year
        FROM Tasks
        WHERE start_date IS NOT NULL
    ");
    
    if ($result) {
        $row = $result->fetch_assoc();

        $minYear = $row['min_year'] ?? date('Y');
        $maxYear = $row['max_year'] ?? date('Y');

        $academicYears = [];
        $currentDate = new DateTime();
        $currentYear = (int) $currentDate->format('Y');
        $currentMonth = (int) $currentDate->format('m');

        if ($currentMonth >= 8) {
            $currentAcademicYear = $currentYear . '-' . ($currentYear + 1);
        } else {
            $currentAcademicYear = ($currentYear - 1) . '-' . $currentYear;
        }

        $startYear = min($minYear, $currentYear - 5);
        $endYear = max($maxYear, $currentYear + 2);

        for ($year = $endYear; $year >= $startYear; $year--) {
            $academicYear = $year . '-' . ($year + 1);
            $academicYears[] = [
                'value' => $academicYear,
                'label' => $academicYear,
                'isCurrent' => ($academicYear === $currentAcademicYear)
            ];
        }

        echo json_encode([
            'status' => 'success',
            'academicYears' => $academicYears,
            'currentAcademicYear' => $currentAcademicYear
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
}

function report_get_departments($pdo, $isHOD, $userDept): void
{
    try {
        if ($isHOD) {
            echo json_encode([
                'status' => 'success',
                'departments' => [$userDept],
                'locked' => true
            ]);
            return;
        }

        $stmt = $pdo->query("SELECT DISTINCT deptshort FROM departments WHERE deptshort IS NOT NULL AND deptshort != '' ORDER BY deptshort");
        $departments = $stmt->fetchAll(PDO::FETCH_COLUMN);

        echo json_encode([
            'status' => 'success',
            'departments' => $departments,
            'locked' => false
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function report_get_faculty_list($pdo, $isHOD, $userDept): void
{
    try {
        $dept = $_POST['department'] ?? '';

        if ($isHOD) {
            $dept = trim($userDept);
        }

        $sql = "SELECT f.fac_id, f.fac_name, f.department
                FROM faculty f
                WHERE 1=1 AND role!='PRINCIPAL'";
        $params = [];

        if (!empty($dept)) {
            $sql .= " AND TRIM(f.department) = ?";
            $params[] = $dept;
        }

        $sql .= " ORDER BY f.fac_name";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $faculties = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'faculties' => $faculties
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function report_get_department_task_summary($pdo, $isHOD, $userDept): void
{
    try {
        $filters = report_build_filters($isHOD, $userDept);

        $sql = "SELECT 
                    f.department,
                    COUNT(DISTINCT t.task_id) as total_tasks,
                    SUM(CASE WHEN ta.status = 'Completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN ta.status = 'Pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN ta.status = 'Overdue' THEN 1 ELSE 0 END) as overdue,
                    ROUND(
                        (SUM(CASE WHEN ta.status = 'Completed' THEN 1 ELSE 0 END) / 
                        COUNT(DISTINCT t.task_id)) * 100, 1
                    ) as completion_percent,
                    ROUND(
                        (SUM(CASE WHEN ta.status = 'Completed' AND ta.updated_at <= t.deadline THEN 1 ELSE 0 END) / 
                        NULLIF(SUM(CASE WHEN ta.status = 'Completed' THEN 1 ELSE 0 END), 0)) * 100, 1
                    ) as ontime_percent
                FROM Tasks t
                INNER JOIN TaskAssignment ta ON t.task_id = ta.task_id
                INNER JOIN faculty f ON ta.assigned_to = f.fac_id
                WHERE 1=1 {$filters['where']}
                GROUP BY f.department
                ORDER BY completion_percent DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($filters['params']);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'taskSummary' => $data
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function report_get_department_performance($pdo, $isHOD, $userDept): void
{
    try {
        $filters = report_build_filters($isHOD, $userDept);

        $sql = "SELECT 
                    f.department,
                    ROUND(AVG(COALESCE(cp.penalty_point, 0)), 2) as avg_credit,
                    ROUND(AVG(COALESCE(cp2.penalty_point, 0)), 2) as avg_demerit,
                    ROUND(
                        AVG(
                            (SELECT COUNT(*) FROM TaskAssignment ta2 
                             WHERE ta2.assigned_to = f.fac_id AND ta2.status = 'Completed') /
                            NULLIF((SELECT COUNT(*) FROM TaskAssignment ta3 
                             WHERE ta3.assigned_to = f.fac_id), 0) * 100
                        ), 1
                    ) as avg_completion,
                    ROUND(
                        AVG(
                            (SELECT COUNT(*) FROM TaskAssignment ta4 
                             INNER JOIN Tasks t4 ON ta4.task_id = t4.task_id
                             WHERE ta4.assigned_to = f.fac_id 
                             AND ta4.status = 'Completed' 
                             AND ta4.updated_at <= t4.deadline) /
                            NULLIF((SELECT COUNT(*) FROM TaskAssignment ta5 
                             WHERE ta5.assigned_to = f.fac_id AND ta5.status = 'Completed'), 0) * 100
                        ), 1
                    ) as avg_ontime,
                    (SELECT f2.fac_name 
                     FROM faculty f2
                     LEFT JOIN TaskAssignment ta6 ON f2.fac_id = ta6.assigned_to
                     WHERE f2.department = f.department
                     GROUP BY f2.fac_id
                     ORDER BY (SUM(CASE WHEN ta6.status = 'Completed' THEN 1 ELSE 0 END) / 
                               NULLIF(COUNT(ta6.task_assignment_id), 0)) DESC
                     LIMIT 1) as best_faculty,
                    (SELECT f3.fac_name 
                     FROM faculty f3
                     LEFT JOIN TaskAssignment ta7 ON f3.fac_id = ta7.assigned_to
                     WHERE f3.department = f.department
                     GROUP BY f3.fac_id
                     ORDER BY (SUM(CASE WHEN ta7.status = 'Completed' THEN 1 ELSE 0 END) / 
                               NULLIF(COUNT(ta7.task_assignment_id), 0)) ASC
                     LIMIT 1) as worst_faculty
                FROM faculty f
                LEFT JOIN TaskAssignment ta ON f.fac_id = ta.assigned_to
                LEFT JOIN Tasks t ON ta.task_id = t.task_id
                LEFT JOIN CreditPenalty cp ON f.fac_id = cp.user_id AND cp.penalty_point > 0
                LEFT JOIN CreditPenalty cp2 ON f.fac_id = cp2.user_id AND cp2.penalty_point < 0
                WHERE 1=1 {$filters['where']}
                GROUP BY f.department
                ORDER BY avg_completion DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($filters['params']);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as &$row) {
            $row['avg_credit'] = $row['avg_credit'] ?? 0;
            $row['avg_demerit'] = abs($row['avg_demerit'] ?? 0);
            $row['avg_completion'] = $row['avg_completion'] ?? 0;
            $row['avg_ontime'] = $row['avg_ontime'] ?? 0;
            $row['best_faculty'] = $row['best_faculty'] ?? 'N/A';
            $row['worst_faculty'] = $row['worst_faculty'] ?? 'N/A';
        }

        echo json_encode([
            'status' => 'success',
            'performance' => $data
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function report_get_faculty_performance($pdo, $isHOD, $userDept): void
{
    try {
        $filters = report_build_filters($isHOD, $userDept);
        $faculty = $_POST['faculty'] ?? '';

        $sql = "SELECT 
                    f.fac_id,
                    f.fac_name as faculty_name,
                    COUNT(DISTINCT ta.task_assignment_id) as total_tasks,
                    SUM(CASE WHEN ta.status = 'Completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN ta.status = 'Pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN ta.status = 'Overdue' THEN 1 ELSE 0 END) as overdue,
                    ROUND(
                        (SUM(CASE WHEN ta.status = 'Completed' THEN 1 ELSE 0 END) / 
                        NULLIF(COUNT(DISTINCT ta.task_assignment_id), 0)) * 100, 1
                    ) as completion_percent,
                    ROUND(
                        (SUM(CASE WHEN ta.status = 'Completed' AND ta.updated_at <= t.deadline THEN 1 ELSE 0 END) / 
                        NULLIF(SUM(CASE WHEN ta.status = 'Completed' THEN 1 ELSE 0 END), 0)) * 100, 1
                    ) as ontime_percent,
                    COALESCE(SUM(CASE WHEN cp.penalty_point > 0 THEN cp.penalty_point ELSE 0 END), 0) as credit_score,
                    COALESCE(ABS(SUM(CASE WHEN cp.penalty_point < 0 THEN cp.penalty_point ELSE 0 END)), 0) as demerit_score
                FROM faculty f
                LEFT JOIN TaskAssignment ta ON f.fac_id = ta.assigned_to
                LEFT JOIN Tasks t ON ta.task_id = t.task_id
                LEFT JOIN CreditPenalty cp ON f.fac_id = cp.user_id AND ta.task_id = cp.task_id
                WHERE 1=1 {$filters['where']}";

        if (!empty($faculty)) {
            $sql .= " AND f.fac_id = :faculty";
            $filters['params'][':faculty'] = $faculty;
        }

        $sql .= " GROUP BY f.fac_id, f.fac_name
                  ORDER BY completion_percent DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($filters['params']);
        $facultyList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $kpis = [
            'credit_score' => 0,
            'demerit_score' => 0,
            'completion_rate' => 0,
            'ontime_percent' => 0,
            'avg_delay' => 0,
            'overdue_count' => 0
        ];

        if (!empty($facultyList)) {
            $totalCredit = array_sum(array_column($facultyList, 'credit_score'));
            $totalDemerit = array_sum(array_column($facultyList, 'demerit_score'));
            $avgCompletion = array_sum(array_column($facultyList, 'completion_percent')) / count($facultyList);
            $avgOntime = array_sum(array_column($facultyList, 'ontime_percent')) / count($facultyList);
            $totalOverdue = array_sum(array_column($facultyList, 'overdue'));

            $delaySql = "SELECT AVG(DATEDIFF(ta.updated_at, t.deadline)) as avg_delay
                        FROM TaskAssignment ta
                        INNER JOIN Tasks t ON ta.task_id = t.task_id
                        INNER JOIN faculty f ON ta.assigned_to = f.fac_id
                        WHERE ta.status = 'Completed' 
                        AND ta.updated_at > t.deadline
                        {$filters['where']}";

            if (!empty($faculty)) {
                $delaySql .= " AND f.fac_id = :faculty";
            }

            $stmt = $pdo->prepare($delaySql);
            $stmt->execute($filters['params']);
            $delayResult = $stmt->fetch(PDO::FETCH_ASSOC);

            $kpis = [
                'credit_score' => $totalCredit,
                'demerit_score' => $totalDemerit,
                'completion_rate' => round($avgCompletion, 1),
                'ontime_percent' => round($avgOntime, 1),
                'avg_delay' => round($delayResult['avg_delay'] ?? 0, 1),
                'overdue_count' => $totalOverdue
            ];
        }

        echo json_encode([
            'status' => 'success',
            'facultyList' => $facultyList,
            'kpis' => $kpis
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function report_get_faculty_tasks($pdo, $isHOD, $userDept): void
{
    try {
        $filters = report_build_filters($isHOD, $userDept);
        $faculty = $_POST['faculty'] ?? '';
        $status = $_POST['status'] ?? '';

        $sql = "SELECT 
            t.task_id,
            t.task_title AS title,
            fa.fac_name AS assigned_by,
            f.fac_name AS assigned_to,
            t.start_date AS assigned_date,
            t.deadline AS due_date,
            ta.updated_at AS completed_on,
            CASE 
                WHEN ta.status = 'Completed' AND ta.updated_at > t.deadline 
                THEN DATEDIFF(ta.updated_at, t.deadline)
                WHEN ta.status != 'Completed' AND CURDATE() > t.deadline
                THEN DATEDIFF(CURDATE(), t.deadline)
                ELSE 0
            END AS days_late,
            t.Priority AS priority,
            ta.status
        FROM Tasks t
        INNER JOIN TaskAssignment ta ON t.task_id = ta.task_id
        INNER JOIN faculty f ON ta.assigned_to = f.fac_id
        INNER JOIN faculty fa ON t.assigned_by = fa.fac_id
        WHERE 1=1 {$filters['where']}";

        if (!empty($faculty)) {
            $sql .= " AND f.fac_id = :faculty";
            $filters['params'][':faculty'] = $faculty;
        }

        if (!empty($status)) {
            $sql .= " AND ta.status = :status";
            $filters['params'][':status'] = $status;
        }

        $sql .= " ORDER BY t.start_date DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($filters['params']);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tasks as &$task) {
            $task['completed_on'] = $task['completed_on'] ?? '-';
            $task['days_late'] = max(0, $task['days_late']);
        }

        echo json_encode([
            'status' => 'success',
            'tasks' => $tasks
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function report_build_filters($isHOD, $userDept): array
{
    global $isChairman;
    $where = '';
    $params = [];

    // Role-based restriction
    if ($isHOD && !$isChairman) {
        $where .= " AND TRIM(f.department) = :userDept";
        $params[':userDept'] = trim($userDept);
    } else {
        $dept = $_POST['department'] ?? '';
        if (!empty($dept)) {
            $where .= " AND TRIM(f.department) = :dept";
            $params[':dept'] = trim($dept);
        }
    }

    // Period filters
    $periodType = $_POST['periodType'] ?? 'all';
    $year = $_POST['year'] ?? '';
    $month = $_POST['month'] ?? '';
    $week = $_POST['week'] ?? '';
    $startDate = $_POST['startDate'] ?? '';
    $endDate = $_POST['endDate'] ?? '';

    if ($periodType === 'month' && !empty($month) && !empty($year)) {
        $yearParts = explode('-', $year);  // e.g., "2025-2026" becomes [2025, 2026]
        $monthNum = intval($month);

        // CORRECT Academic Year Logic:
        // Academic year 2025-2026 means:
        // - Aug-Dec 2025 (months 8-12 of first year)
        // - Jan-Jul 2026 (months 1-7 of second year)

        if ($monthNum >= 8) {
            // Aug-Dec: Use FIRST year (2025)
            $yearNum = $yearParts[0];
        } else {
            // Jan-Jul: Use SECOND year (2026)
            $yearNum = $yearParts[1];
        }

        $where .= " AND YEAR(t.start_date) = :year AND MONTH(t.start_date) = :month";
        $params[':year'] = $yearNum;
        $params[':month'] = $monthNum;

    } elseif ($periodType === 'week' && !empty($week) && !empty($year)) {
        $yearParts = explode('-', $year);
        $yearNum = $yearParts[0];
        $weekNum = intval($week);

        $jan1 = strtotime("$yearNum-01-01");
        $dayOfWeek = date('w', $jan1);
        $firstSaturday = $dayOfWeek <= 6 ? (6 - $dayOfWeek) : 0;
        $weekStart = strtotime("+$firstSaturday days +" . ($weekNum - 1) * 7 . " days", $jan1);
        $weekEnd = strtotime("+6 days", $weekStart);

        $where .= " AND t.start_date BETWEEN :weekStart AND :weekEnd";
        $params[':weekStart'] = date('Y-m-d', $weekStart);
        $params[':weekEnd'] = date('Y-m-d', $weekEnd);

    } elseif ($periodType === 'custom' && !empty($startDate) && !empty($endDate)) {
        $where .= " AND t.start_date BETWEEN :startDate AND :endDate";
        $params[':startDate'] = $startDate;
        $params[':endDate'] = $endDate;
    }

    return [
        'where' => $where,
        'params' => $params
    ];
}

function handle_request_meeting($conn): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header('Content-Type: application/json');

    $currentUserId = $_SESSION['user_id'];

    $userQuery = $conn->prepare("SELECT name, dept, role FROM Users WHERE user_id = ?");
    $userQuery->bind_param("s", $currentUserId);
    $userQuery->execute();
    $userResult = $userQuery->get_result();

    if ($userResult->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid User ID']);
        exit;
    }

    $userData = $userResult->fetch_assoc();
    $staffName = $userData['name'];
    $staffDept = $userData['dept'];
    $staffRole = $userData['role'];

    // Principal / admin view: fetch meeting requests awaiting decision
    if (isset($_GET['fetchAppointmentRequests'])) {
        // Allow Principal, Chairman, Admin, ED and CHRO to fetch requests
        if (!in_array($staffRole, ['PRINCIPAL', 'CHAIRMAN', 'ADMIN', 'ED', 'CHRO'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $sql = "SELECT ra.request_id, ra.request_by, ra.requested_to, ra.purpose, ra.request_time, ra.status,
                       u.name AS requester_name, u.dept AS requester_dept
                FROM Request_Appointment ra
                LEFT JOIN Users u ON ra.request_by = u.user_id
                WHERE ra.requested_to = ?
                ORDER BY ra.request_time DESC";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
            exit;
        }
        $stmt->bind_param('s', $currentUserId);
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Failed to fetch requests']);
            exit;
        }
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'request_id' => $row['request_id'],
                'request_by' => $row['request_by'],
                'requester_name' => $row['requester_name'] ?? $row['request_by'],
                'requester_dept' => $row['requester_dept'] ?? '-',
                'purpose' => $row['purpose'],
                'request_time' => $row['request_time'],
                'status' => $row['status']
            ];
        }

        echo json_encode(['success' => true, 'data' => $data]);
        exit;
    }

    // Principal actions: approve or reject requests
    if (isset($_POST['approveRequest']) || isset($_POST['rejectRequest'])) {
        // Allow Principal, Chairman, Admin, ED and CHRO to approve/reject
        if (!in_array($staffRole, ['PRINCIPAL', 'CHAIRMAN', 'ADMIN', 'ED', 'CHRO'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        $requestId = intval($_POST['request_id'] ?? 0);
        if ($requestId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid request id']);
            exit;
        }
        $newStatus = isset($_POST['approveRequest']) ? 'Approved' : 'Rejected';
        $stmt = $conn->prepare("UPDATE Request_Appointment SET status = ? WHERE request_id = ?");
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare update']);
            exit;
        }
        $stmt->bind_param('si', $newStatus, $requestId);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => "Request {$newStatus}"]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update request']);
        }
        exit;
    }

    if (isset($_GET['fetchRequests'])) {
        $query = $conn->prepare("SELECT * FROM Request_Appointment WHERE request_by = ? ORDER BY request_time DESC");
        $query->bind_param("s", $currentUserId);
        $query->execute();
        $result = $query->get_result();

        $requests = [];
        while ($row = $result->fetch_assoc()) {
            $officialName = "-";
            $officialId = $row['requested_to'] ?? $row['request_to'] ?? null;
            if (!empty($officialId)) {
                $getOfficial = $conn->prepare("SELECT name, role FROM Users WHERE user_id = ?");
                $getOfficial->bind_param("s", $officialId);
                $getOfficial->execute();
                $offResult = $getOfficial->get_result();
                if ($offResult->num_rows > 0) {
                    $official = $offResult->fetch_assoc();
                    $officialName = "{$official['name']} ({$official['role']})";
                }
            }

            $requests[] = [
                'purpose' => $row['purpose'],
                'staff' => "$staffName ($staffDept)",
                'requested_to' => $officialName,
                'request_to' => $officialName,
                'preferred_time' => $row['request_time'] ? date('d M Y, h:i A', strtotime($row['request_time'])) : '-',
                'datetime' => $row['request_time'] ? date('d M Y, h:i A', strtotime($row['request_time'])) : '-',
                'status' => $row['status'] ?? 'Pending'
            ];
        }

        echo json_encode(['success' => true, 'data' => $requests]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $purpose = $input['purpose'] ?? ($_POST['purpose'] ?? '');
        $requestTo = $input['requested_to'] ?? $input['request_to'] ?? $_POST['requested_to'] ?? $_POST['request_to'] ?? 'P001';
        $preferredTime = $input['preferred_time'] ?? $input['request_time'] ?? $_POST['preferred_time'] ?? $_POST['request_time'] ?? null;

        if (!$preferredTime || !$purpose) {
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            exit;
        }

        // If requestTo is 'CHAIRMAN', resolve it to the actual Chairman's user_id
        if ($requestTo === 'CHAIRMAN') {
            $chairmanQuery = $conn->prepare("SELECT user_id FROM Users WHERE add_role LIKE '%CHAIRMAN%' LIMIT 1");
            if ($chairmanQuery && $chairmanQuery->execute()) {
                $result = $chairmanQuery->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $requestTo = $row['user_id'];
                } else {
                    echo json_encode(['success' => false, 'message' => 'Chairman not found']);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to resolve Chairman']);
                exit;
            }
        }

        $insert = $conn->prepare("INSERT INTO Request_Appointment (request_by, requested_to, purpose, request_time, status) VALUES (?, ?, ?, ?, 'Pending')");
        $insert->bind_param("ssss", $currentUserId, $requestTo, $purpose, $preferredTime);

        if ($insert->execute()) {
            echo json_encode(['success' => true, 'message' => 'Meeting request sent.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database insert failed.']);
        }
        exit;
    }

    // Principal/Staff view: fetch their own meeting requests (especially to Chairman)
    if (isset($_GET['fetchPrincipalRequests'])) {
        $sql = "SELECT ra.request_id, ra.request_by, ra.requested_to, ra.purpose, ra.request_time, ra.status,
                       u.name AS requested_to_name, u.role AS requested_to_role
                FROM Request_Appointment ra
                LEFT JOIN Users u ON ra.requested_to = u.user_id
                WHERE ra.request_by = ?
                ORDER BY ra.request_time DESC";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
            exit;
        }
        $stmt->bind_param('s', $currentUserId);
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Failed to fetch requests']);
            exit;
        }
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'request_id' => $row['request_id'],
                'purpose' => $row['purpose'],
                'request_time' => $row['request_time'],
                'status' => $row['status'],
                'requested_to_name' => $row['requested_to_name'] ?? $row['requested_to'],
                'requested_to_role' => $row['requested_to_role'] ?? '-'
            ];
        }

        echo json_encode(['success' => true, 'data' => $data]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

function handle_submit_meeting_request($conn): void
{
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        error_log('Request Data: ' . print_r($data, true));
        if (!$conn) {
            error_log('Database Connection Error: ' . mysqli_connect_error());
        }

        $request_by = $_SESSION['user_id'] ?? null;
        $requested_to = $data['requested_to'] ?? '';
        $purpose = $data['purpose'] ?? '';
        $request_time = $data['request_time'] ?? '';

        if (!$request_by || !$requested_to || !$purpose || !$request_time) {
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            exit;
        }

        $currentTime = new DateTime();
        $meetingDateTime = new DateTime($request_time);

        if ($meetingDateTime < $currentTime) {
            echo json_encode([
                'success' => false, 
                'message' => '⚠️ Cannot request a meeting for a past date or time.'
            ]);
            exit;
        }

        $interval = $currentTime->diff($meetingDateTime);
        $hoursDiff = ($interval->days * 24) + $interval->h + ($interval->i / 60);

        if ($hoursDiff < 3 && $interval->invert == 0) {
            echo json_encode([
                'success' => false, 
                'message' => '⚠️ Meeting requests must be submitted at least 3 hours before the scheduled time.'
            ]);
            exit;
        }

        $datetime = date('Y-m-d H:i:s', strtotime($request_time));

        $query = "INSERT INTO Request_Appointment (request_by, requested_to, purpose, request_time, status) 
                 VALUES (?, ?, ?, ?, 'Pending')";
                 
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $request_by, $requested_to, $purpose, $datetime);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Meeting request submitted successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to submit meeting request: ' . $conn->error]);
        }
        
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    }
    exit;
}

