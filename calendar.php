<?php
require "process/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $services = mysqli_real_escape_string($conn, $_POST['services']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $reference_id = 'Admin';
    $status = '1';

    // Insert into database
    $schedule_sql = "INSERT INTO schedule (reference_id, date, time, services, status) 
                     VALUES (?, ?, ?, ?, ?)";
    $schedule_stmt = mysqli_prepare($conn, $schedule_sql);
    
    if ($schedule_stmt) {
        mysqli_stmt_bind_param($schedule_stmt, "sssss", $reference_id, $date, $time, $services, $status);
        $schedule_checkResult = mysqli_stmt_execute($schedule_stmt);
        mysqli_stmt_close($schedule_stmt);
        
        if ($schedule_checkResult) {
            header("Location: calendar.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error in preparing SQL statement: " . mysqli_error($conn);
    }
}

// Fetch schedules for Admin
$fetch_sql = "SELECT * FROM schedule WHERE reference_id = 'Admin' ORDER BY id DESC LIMIT 4";
$fetch_result = mysqli_query($conn, $fetch_sql);

?>

<?php include 'process/formula.php';?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image/admin.ico">
    <title>Calendar | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js"></script>
</head>
<body>
    <?php 
    $activePage = 'calendar'; 
    include 'nav.php';
    ?>
    <br />
    <br />
    <div class="container">
        <div id="calendar"></div>
    </div>

    <div class="form">
        <form action="calendar.php" method="POST" onsubmit="return confirmSubmit()">
            <div class="title">Add Schedule</div>

            <div class="field">
                <div class="label">Title</div>
                <input type="text" class="form-control" name="services" required>
            </div>

            <div class="field">
                <div class="label">Date</div>
                <?php
                    date_default_timezone_set('Asia/Manila');
                    $currentDate = date('Y-m-d');
                ?>
                <input type="date" class="form-control" name="date" id="date" min="<?php echo $currentDate; ?>" required
                    onchange="fetchSchedule()">
            </div>

            <div class="field">
                <div class="label">Time</div>
                <select class="select" name="time" id="time" required>
                    <option value="" disabled selected hidden>Select a time</option>
                    <option value="6:00 AM">6:00 AM</option>
                    <option value="7:00 AM">7:00 AM</option>
                    <option value="8:00 AM">8:00 AM</option>
                    <option value="9:00 AM">9:00 AM</option>
                    <option value="10:00 AM">10:00 AM</option>
                    <option value="11:00 AM">11:00 AM</option>
                    <option value="1:00 PM">1:00 PM</option>
                    <option value="2:00 PM">2:00 PM</option>
                    <option value="3:00 PM">3:00 PM</option>
                </select>
            </div>
            <div class="field btns">
                <button class="submit" type="submit">Add</button>
            </div>
        </form>
        <fieldset>
            <hr>
            <div class="title">Schedule</div>
            <?php while($row = mysqli_fetch_assoc($fetch_result)): ?>
            <br>
            <div class="sched" data-id="<?= $row['id'] ?>">
                <p><?= htmlspecialchars($row["services"]) ?> - <?= date('M d, Y', strtotime($row["date"])) ?>
                    <?= date('h:i a', strtotime($row["time"])) ?></p>
                <span class="delete-schedule"><i class="fas fa-trash-alt"></i></span>
            </div>
            <?php endwhile; ?>
        </fieldset>
    </div>

    <script>
    $(document).ready(function() {
        $('.delete-schedule').on('click', function() {
            if (confirm("Are you sure you want to delete this schedule?")) {
                var schedDiv = $(this).closest('.sched');
                var id = schedDiv.data('id');

                $.ajax({
                    url: 'calendar_delete.php',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        alert(response);
                        schedDiv.remove();
                        location.reload(); // Reload the page
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + xhr.responseText);
                    }
                });
            }
        });
    });


    function confirmSubmit() {
        return confirm("Are you sure that you'll include this in the schedule?");
    }

    function fetchSchedule() {
        var selectedDate = document.getElementById('date').value;
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var schedule = JSON.parse(this.responseText);
                var selectTime = document.getElementById('time');

                for (var i = 0; i < selectTime.options.length; i++) {
                    selectTime.options[i].disabled = false;
                }

                for (var i = 0; i < schedule.length; i++) {
                    var item = schedule[i];
                    if (item.date === selectedDate && item.time !== "other") {
                        var option = selectTime.querySelector("option[value='" + item.time + "']");
                        if (option) {
                            option.disabled = true;
                        }
                    }
                }
            }
        };
        xhr.open("GET", "calendarSchedule.php?date=" + selectedDate, true);
        xhr.send();
    }
    </script>

    <script>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            editable: true,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: 'calendar_schedule.php',
            eventRender: function(event, element) {
                switch (event.title) {
                    case 'Wedding':
                        element.css('background-color', 'rgb(217 150 150)');
                        break;
                    case 'Baptism':
                        element.css('background-color', 'rgb(255 255 151)');
                        break;
                    case 'Sickcall':
                        element.css('background-color', 'rgb(246 135 235)');
                        break;
                    case 'Blessing':
                        element.css('background-color', 'rgb(184 184 246)');
                        break;
                    case 'Funeral':
                        element.css('background-color', '#f5daaabd');
                        break;
                    default:
                        element.css('background-color', 'rgb(148 230 148)');
                }
            }
        });
    });
    </script>
</body>

</html>

<style>
a.fc-day-grid-event.fc-h-event.fc-event.fc-start.fc-end.fc-draggable {
    border: none;
    font-size: 15px;
    border-radius: 20px;
    padding: 5px;
    margin: 3px 10px;
}

.container {
    width: 58%;
    height: 800px;
    background-color: white;
    margin: 2.2rem 6rem;
    box-shadow: 0 0 3rem rgba(0, 0, 0, 0.3);
    border-radius: 0.8rem;
    padding: 20px;
}

.sched {
    background-color: rgb(148 230 148);
    padding: 10px 10px 1px 10px;
    border-radius: 15px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

.sched span {
    cursor: pointer;
    color: #a81111;
}

.form {
    height: 800px;
    width: 30%;
    background-color: white;
    z-index: 1;
    left: 64%;
    position: absolute;
    margin: 2.2rem 6rem;
    box-shadow: 0 0 3rem rgba(0, 0, 0, 0.3);
    border-radius: 0.8rem;
    padding: 20px;
}

.title {
    text-align: center;
    font-size: 25px;
    font-weight: 500;
}

.field {
    width: 100%;
    height: 45px;
    margin: 33px 0;
    display: flex;
    position: relative;
}

.label {
    position: absolute;
    top: -30px;
    font-weight: 500;
}


.form .field input {
    box-sizing: border-box;
    height: 100%;
    width: 100%;
    border: 1px solid #d3d3d3;
    border-radius: 5px;
    padding-left: 15px;
    margin: 0 1px;
    font-size: 18px;
    transition: border-color 150ms ease;
}

.selectform {
    width: 99.90%;
    /* height: 100%; */
}

.select {
    box-sizing: border-box;
    height: 100%;
    width: 100%;
    border: 1px solid #d3d3d3;
    border-radius: 5px;
    padding: 10px;
    margin: 0 1px;
    font-size: 18px;
    transition: border-color 150ms ease;
}

.submit {
    /* margin-top: 20px !important; */
    width: 100%;
    height: calc(100% + 5px);
    border: none;
    padding: 5px;
    background: green;
    border-radius: 10px;
    color: #fff;
    cursor: pointer;
    font-size: 18px;
    font-weight: 500;
    letter-spacing: 1px;
    text-transform: uppercase;
    transition: 0.5s ease;
}
</style>