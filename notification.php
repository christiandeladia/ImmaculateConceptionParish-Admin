<?php
require "process/connect.php";
if (!isset($_SESSION['auth_admin'])) {
    header("location: index.php");
    exit;
}
?>

<?php
if (isset($_SESSION['auth_login'])) {
    $auth = $_SESSION['auth_login'];
    $auth_full_name = $auth['first_name'] . $auth['last_name'];
}
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 10;
$start = ($page - 1) * $limit;

$result = mysqli_query($conn, "SELECT * FROM notification ORDER BY id DESC LIMIT $start, $limit");

if (!$result) {
    die("Error in query: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
?>

<?php include 'process/formula.php';?>

<?php 
date_default_timezone_set('Asia/Manila'); // Set the timezone to Philippines

function getTimeAgo($timestamp) {
    $current_time = time();
    $time_diff = $current_time - strtotime($timestamp);
    $minutes = round($time_diff / 60);
    $hours = round($minutes / 60);
    $days = round($hours / 24);
    $weeks = round($days / 7);
    $months = round($days / 30);
    $years = round($days / 365);

    if ($minutes <= 1) {
        return "Just now";
    } elseif ($minutes < 60) {
        return $minutes == 1 ? "1 min ago" : "$minutes mins ago";
    } elseif ($hours < 24) {
        return $hours == 1 ? "1 hour ago" : "$hours hours ago";
    } elseif ($days < 7) {
        return $days == 1 ? "1 day ago" : "$days days ago";
    } elseif ($weeks < 4.3) {
        return $weeks == 1 ? "1 week ago" : "$weeks weeks ago";
    } elseif ($months < 12) {
        return $months == 1 ? "1 month ago" : "$months months ago";
    } else {
        return $years == 1 ? "1 year ago" : "$years years ago";
    }
}

function getMassData() {
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%M %d, %Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM notification ORDER BY date_added DESC";
    $inventory = [];
    $reference_id = uniqid();
    $statement = $pdo->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as &$result) {
        $result['time_component'] = getTimeAgo($result['date_added']);
    }

    return $results;
}
$inventory = getMassData();

$service_links = array(
    'Wedding' => 'wedding.php',
    'Mass' => 'mass.php',
    'Baptismal' => 'baptismal.php',
    'Funeral' => 'funeral.php',
    'Sick Call' => 'sickcall.php',
    'Blessing' => 'blessing.php',
    'Baptismal Certificate' => 'certificate_baptismal.php'
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image/admin.ico">
    <title>Notification | Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

</head>

<body>
    <?php $activePage = 'services'; include 'nav.php';?>
    <div></div>
    <div class="product">
        <div class=".container-fluid mt-4 card mb-2 bg-light shadow" style=" margin: 0 3%">
            <div class="card-body">
                <section class="p-1 z-depth-1">
                    <h1 class="text-center font-weight-bold mb-4">NOTIFICATION</h1>
                </section>
            </div>
        </div>
        <div class=".container-fluid mt-4 card mb-2 bg-light shadow" style=" margin: 0 3%">

            <div class="notificationContainer">
                <?php if (!empty($inventory)) { ?>
                <button id="mark-all-unread-btn" class="mark btn btn-success" style="align-self: right;"><i
                        class="fa fa-check"></i> Mark All as Unread</button>
                <?php } ?>
                <?php if (empty($inventory)) { ?>
                <div class="no-notification">
                    <i class="fa fa-bell"></i>
                    <p class="no-notification-text">No notifications</p>

                </div>
                <?php } else { ?>
                <?php foreach ($inventory as $item) {?>
                <main>
                    <div>
                        <div class="notificationCard <?php echo ($item['status'] == 'unread') ? 'unread' : 'read'; ?>">
                            <div class="description">
                                <?php 
                    $service = $item['services'];
                    // Check if the service exists in the mapping array
                    if (array_key_exists($service, $service_links)) {
                        $href = $service_links[$service]; // Get the corresponding PHP file
                    } else {
                        $href = ''; // Set default value for href if service is not found
                    }
                    ?>
                                <?php if (strpos($service, 'Certificate') === false) { ?>
                                <strong>New Application for <?php echo $service; ?></strong>
                                <p>
                                    <b><?php echo $item["customer_name"] ?></b> has applied for
                                    <b><?php echo $service; ?></b> with
                                    a reference number of (<?php echo $item['reference_id']; ?>).
                                    Please see the request! <a href="<?php echo $href; ?>" class="view-link"
                                        data-id="<?php echo $item['id']; ?>"
                                        style="color: #0c8628; background-color: #fff;"> <b>&nbsp;View here&nbsp;</b> </a>
                                    </span>
                                </p>
                                <?php } else { ?>
                                <strong>New Request of <?php echo $service; ?></strong>
                                <p>
                                    <b><?php echo $item["customer_name"] ?></b> has requested for
                                    <b><?php echo $service; ?></b> with
                                    a reference number of (<?php echo $item['reference_id']; ?>).
                                    Please see the request!
                                    <a href="<?php echo $href; ?>" class="view-link"
                                        data-id="<?php echo $item['id']; ?>"
                                        style="color: #0c8628; background-color: #fff;"> <b>&nbsp;View here&nbsp;</b>
                                    </a>
                                    </span>
                                </p>
                                <?php } ?>

                                <p id="notif-time">
                                    <?php echo $item['time_component']; ?></p>
                            </div>

                        </div>

                    </div>

                </main>
                <hr>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
    </div>
</body>

</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $(".view-link").click(function(event) {
        event.preventDefault(); // Prevent default link behavior

        // Get the ID of the notification
        var notificationId = $(this).data("id");

        // Get the href attribute of the clicked link
        var href = $(this).attr('href');

        // Send AJAX request to update status
        $.ajax({
            url: "notification_status.php", // Change this to your PHP file to update status
            method: "POST",
            data: {
                id: notificationId
            },
            success: function(response) {
                // Update the style of the clicked notification
                $(event.target).closest('.status').removeClass('unread').addClass(
                    'read');

                // Navigate to the href specified in the link
                window.location.href = href;
            },
            error: function(xhr, status, error) {
                console.error("Error updating status:", error);
            }
        });
    });
});
</script>
<script>
$(document).ready(function() {
    $("#mark-all-unread-btn").click(function() {
        $.ajax({
            url: "notification_unread.php", // PHP script to handle marking all as unread
            method: "POST",
            success: function(response) {
                // Reload the page or update the notifications as needed
                location.reload(); // You may replace this with your own logic
            },
            error: function(xhr, status, error) {
                console.error("Error marking all as unread:", error);
            }
        });
    });
});
</script>
<style>
.container {
    display: flex;
    justify-content: center;
    /* background-color: #f1f1f1; */
    width: 100%;
    /* height: 100vh; */
}

.notificationContainer {
    /* font-family: 'Outfit',Arial,sans-serif; */
    font-size: 18px;
    background-color: #fff;
    width: 1500px;
    margin: 30px;
    padding: 1rem 1rem;
    border-radius: 1rem;
    height: 600px;
    /* border: solid 1px black; */
    overflow: auto;
}

.notificationContainer strong {
    font-size: 20px;
    padding-bottom: 10px;
}

header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
}

.mark {
    padding: 10px;
    font-size: 18px;
    border-radius: 1rem;
    margin-bottom: 5px;
    margin-left: 86.5%;
    background-color: #086d48;
}

.notificationHeader {
    display: flex;
    align-items: center;
}

.num-of-notif {
    background-color: green;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    width: 30px;
    height: 30px;
    border-radius: 0.5rem;
    margin-left: 10px;
}

#mark-as-read {
    color: gray;
    cursor: pointer;
    transition: 0.6s ease;
}

#mark-as-read:hover {
    color: black;
}

main {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.notificationCard {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 1rem;
}

.notificationCard img {
    width: 50px;
}

.notificationCard .description {
    margin-left: 10px;
    display: flex;
    justify-content: space-between;
    flex-direction: column;
}

.unread {
    background-color: #28a745bd;
    color: black;
}

img {
    border-radius: 30px;
}
</style>
<style>
.custom-tab-content {
    background-color: #fff;
    padding: 20px;
    border: 1px solid #dee2e6;
    border-top: none;
}

.nav-fill {

    >.nav-link,
    .nav-item {
        border: 1px #dee2e6 solid;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }
}

.nav-link:active,
.nav-item.show .nav-link {
    color: black;
    background-color: $nav-tabs-link-active-bg;
    border-color: $nav-tabs-link-active-border-color;

}

a {
    color: #495057;
}

.nav-links {
    border-top-left-radius: 1.5rem;
    border-top-right-radius: 0.5rem;
}

li a {
    text-decoration: none;
    color: #1ab188;
    transition: .5s ease;
}

li a:hover {
    color: #179b77;
}

.form {
    background: rgba(19, 35, 47, 0.9);
    padding: 10px;
    max-width: 95%;
    margin: 0 auto 0 auto;
    border-radius: 50px;
    box-shadow: 0 4px 10px 4px rgba(19, 35, 47, 0.3);
}

.tab-group {
    list-style: none;
    padding: 0;
    margin: 0 auto;
    width: 99%
}

.tab-group:after {
    content: "";
    display: table;
    clear: both;
}

.tab-group li a {
    border-radius: 40px;
    display: block;
    text-decoration: none;
    padding: 15px;
    color: #a0b3b0;
    font-size: 20px;
    float: left;
    width: 50%;
    text-align: center;
    cursor: pointer;
    transition: .5s ease;
}

.tab-group .active a {
    background: #1ab188;
    color: #ffffff;
}

tr {
    text-align: center !important;
}

.no-notification {
    text-align: center;
    margin-top: 14%;
    /* Adjust as needed */
}

.no-notification p.no-notification-text {
    font-size: 24px;
    /* Adjust font size as needed */
    color: gray;
    /* Set font color to gray */
}

.no-notification i {
    font-size: 30px;
    /* Adjust font size as needed */
    color: gray;
    /* Set font color to gray */
}
</style>