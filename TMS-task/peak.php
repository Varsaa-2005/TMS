<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
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
        .aligndesign {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 50%;
            width: 50%;
        }

        .form-labe {
            display: flex;
            align-items: center;
            align-self: space-around;
        }

        .form-selec {
            width: 200%;

        }

        #submitting_btn {

            display: flex;
            margin-left: 46%;
            margin-top: 30px;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
    </style>

</head>

<body>
    <div class="modal-body">
        <div class="filter-section">
            <div class="row g-3 justify-content-center">
                <div class="col-md-3">
                    <label class="form-label">Select Department</label>
                    <select id="departfetch" class="form-select">
                        <option value="">Select Departments</option>
                        <option value="CSE">CSE</option>
                        <option value="EEE">EEE</option>
                        <option value="ECE">ECE</option>
                        <option value="IT">IT</option>
                        <option value="MECH">MECH</option>
                        <option value="CIVIL">CIVIL</option>
                        <option value="AI">AI</option>

                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Select Faculty</label>
                    <select id="facultyselect" class="form-select">
                        <option value="">Select Faculty</option>
                    </select>
                </div>

            </div>
        </div>
        <button type="button" id="submitting_btn" class="btn btn-primary">Submit</button>
        <div id="userContent" style="margin-top:20px;"></div>


    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $("#departfetch").on("change", function () {
            var departfecthed = $(this).val();
            $("#facultyselect").empty();
            $("#facultyselect").append('<option value="">Select Faculty</option>');

            $.post("backend/api.php", { action: "facultynames", departfecthed: departfecthed }, function (data) {
                if (data.status == "success") {
                    console.log(data);

                    data.data.forEach(element => {
                        $("#facultyselect").append('<option value="' + element['user_id'] + '">' + element['name'] + '</option>');
                    });
                }
            })
        })
        $('#submitting_btn').on('click', function () {

            var facultyId = $('#facultyselect').val();

            if (!facultyId) {
                alert("Please select a faculty");
                return;
            }

            console.log("Selected user_id:", facultyId);

            $("#userContent").html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading personal tasks...</p></div>');

            $.post("personalToDo_view.php", { user_id: facultyId }, function (response) {
                $("#userContent").html(response);
            }).fail(function (error) {
                console.error(error);
                $("#userContent").html('<div class="alert alert-danger">Error loading personal tasks</div>');
            });

        });

    </script>
</body>

</html>