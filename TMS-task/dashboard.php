<?php
require_once  './backend/session_init.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$dept = $_SESSION['dept'];
?>


<html>
    <head>
        <Style>
            .para{
                width:350px;
                height:200px;
                display:flex;
                flex-direction:column;
                border-radius:10px;
                background-color:#39CFB8;
                text-align:center;
                margin-top:10px;
            }
            @keyframes beat {
                0%, 100% { transform: scale(1); }
                50%      { transform: scale(1.2); }
            }

            .text-beat {
                display: inline-block;
                animation: beat 1s infinite;
            }
        </Style>
    </head>

<body>

<div style="display: flex; gap: 20px;">
    <p class="para" id="box1" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
        <i class="fal fa-solid fa-check fa-2xl" style="color: #f7f7f7; margin-bottom:20px;"></i> 
        <span class="text-beat" style="color: #f1f2f3; font-weight: bold; font-size: 1.5rem;">Completed Task</span>
        <span id="completed-count" style="color: #f1f2f3; font-size: 2rem; font-weight: bold;">0</span>
    </p>
    <p class="para" style="background-color:#FF941A; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
        <i class="fa-solid fa-hourglass-half fa-2xl" style="color: #f1f2f3; margin-bottom:20px;"></i>
        <span class="text-beat" style="color: #f1f2f3; font-weight: bold; font-size: 1.5rem;">Task Pending</span>
        <span id="pending-count" style="color: #f1f2f3; font-size: 2rem; font-weight: bold;">0</span>
    </p>
    <p class="para" style="background-color:#E02C47; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
        <i class="fa-solid fa-credit-card fa-2xl" style="color: #f2f2f2; margin-bottom:20px;"></i>
        <span class="text-beat" style="color: #f1f2f3; font-weight: bold; font-size: 1.5rem;">Demerit</span>
        <span id="demerit-count" style="color: #f1f2f3; font-size: 2rem; font-weight: bold;">0</span>
    </p>
</div>

<script>
$.post('backend/api.php', { action: 'dashboard_cnt' }, function(data) {
    if (!data.error) {
        $('#completed-count').text(data.completed);
        $('#pending-count').text(data.pending);
        $('#demerit-count').text(data.demerit);
    } else {
        console.error(data.error);
    }
}, 'json').fail(function(err) {
    console.error('POST error:', err);
});

</script>

</body>
</html>