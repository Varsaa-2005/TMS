
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}
require_once  '../backend/session_init.php';
include(__DIR__ . '/../db_connect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['Userid'] ?? '';
    $password = $_POST['pass'] ?? '';
    $roleType = strtolower($_POST['type'] ?? '');
    $selected_dept = $_POST['selected_dept'] ?? null;

    $stmt = $conn->prepare("SELECT * FROM Users WHERE user_id = ? AND password = ?");
    $stmt->bind_param("ss", $user_id, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = strtoupper($user['role']);
        $_SESSION['dept'] = $user['dept'];
        $_SESSION['ddept'] = $user['ddept'];
        
        $sql = "SELECT 1 FROM portfolio WHERE fac_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $is_portfolio = true;
        } else {
            $is_portfolio = false;
        }
        
        if($_SESSION['role'] === 'CHAIRMAN') {
            header("Location: ../chairman.php");
        }
        else if($_SESSION['role'] === 'PRINCIPAL') {
            header("Location: ../principal.php");
        }
        else if($_SESSION['role'] === 'ED') {
            header("Location: ../ED.php");
        }
        else if($_SESSION['role'] === 'CHRO') {
            header("Location: ../CHRO.php");
        }
        else if($_SESSION['role'] === 'HOD') {
            header("Location: ../hod.php");
        }
        else if($_SESSION['role'] === 'FACULTY' && $is_portfolio === false) {
            header("Location: ../faculty.php");
        } 
        else if($is_portfolio === true) {
            $_SESSION['role_weight'] = 2;

            $sql = "SELECT pname FROM portfolio WHERE fac_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $portfolio = $result->fetch_assoc();

            $_SESSION['role'] = $portfolio['pname']; //now their role is portfolio head not faculty anymore
            //$_SESSION['role'] = $user['add_role'];

            $parts = explode('_', $portfolio['pname']);
            $first_part = $parts[0]; //extract the specifc dept of portfolio heads like jap/iqac
            $_SESSION['pf_dept'] = $first_part;

            header("Location: ../portfolio_head.php");
        }
        else{
            echo "<script>alert('Role not recognized'); window.location.href='login.php';</script>";
        }
        
        exit;
    } else {
        echo "<script>alert('Invalid credentials'); window.location.href='login.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
