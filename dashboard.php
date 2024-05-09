<?php
require "process/connect.php";
if (!isset($_SESSION['auth_admin'])) {
    header("location: index.php");
    exit;
}


// Function to get best sellers from the database
function getBestSellers($pdo) {
    $query = "SELECT product_name, SUM(product_quantity) AS total_quantity 
              FROM orders 
              GROUP BY product_name 
              ORDER BY total_quantity DESC 
              LIMIT 3"; 

    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// Get best sellers data using PDO
$bestSellers = getBestSellers($pdo);
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



function getMassIData() {
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%d/%m/%Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM mass WHERE status_id = 2 ORDER BY date_added DESC";
    $massIntention = [];
    $reference_id = uniqid();
    $statement = $pdo->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as &$result) {
        $result['time_component'] = getTimeAgo($result['date_added']);
    }
    return $results;
}
$massIntention = getMassIData();


function getData() {
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%d/%m/%Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM notification ORDER BY date_added DESC";
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
$inventory = getData();

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

<?php 
function getMassData() {
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%d/%m/%Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM mass WHERE status_id = 3";
    $inventory = [];
    $reference_id = uniqid();
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
$mass = getMassData();
?>
<?php 
function getWeddingData() {
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%d/%m/%Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM wedding WHERE status_id = 3";
    $inventory = [];
    $reference_id = uniqid();
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
$wedding = getWeddingData();
?>
<?php 
    function getBaptismalData() {
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%d/%m/%Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM binyag WHERE status_id = 3";
    $Baptismal = [];
    $reference_id = uniqid();
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    $binyag = getBaptismalData();
    ?>

<?php 
    function getBlessingData() {
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%d/%m/%Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM blessing WHERE status_id = 3";
    $Blessing = [];
    $reference_id = uniqid();
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    $blessing = getBlessingData();
    ?>
<?php 
    function getFuneralData() {
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%d/%m/%Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM funeral WHERE status_id = 3";
    $complete = [];
    $reference_id = uniqid();
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    $funeral = getFuneralData();
    ?>
<?php 
    function getSickcallData() {
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%d/%m/%Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM sickcall WHERE status_id = 3";
    $complete = [];
    $reference_id = uniqid();
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    $sickcall = getSickcallData();
    ?>
<?php
    $total_wedding = count($wedding);
    $total_binyag = count($binyag);
    $total_funeral = count($funeral);
    $total_sickcall = count($sickcall);
    $total_mass = count($mass);
    $total_blessing = count($blessing);
    ?>
<?php
// Function to get data for last week
function getDataLastWeek($tableName) {
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%d/%m/%Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM $tableName WHERE WEEK(date_added) = WEEK(NOW()) - 1";
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
function getDatathisWeek($tableName) {
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%d/%m/%Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM $tableName WHERE WEEK(date_added) = WEEK(NOW())";
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
// Get data for last week for each service
$lastWeekWedding = getDataLastWeek("wedding");
$lastWeekBaptismal = getDataLastWeek("binyag");
$lastWeekFuneral = getDataLastWeek("funeral");
$lastWeekSickcall = getDataLastWeek("sickcall");
$lastWeekMass = getDataLastWeek("mass");
$lastWeekBlessing = getDataLastWeek("blessing");

// Count the number of applications for each service last week
$totalLastWeekWedding = count($lastWeekWedding);
$totalLastWeekBaptismal = count($lastWeekBaptismal);
$totalLastWeekFuneral = count($lastWeekFuneral);
$totalLastWeekSickcall = count($lastWeekSickcall);
$totalLastWeekMass = count($lastWeekMass);
$totalLastWeekBlessing = count($lastWeekBlessing);

$thisWeekWedding = getDatathisWeek("wedding");
$thisWeekBaptismal = getDatathisWeek("binyag");
$thisWeekFuneral = getDatathisWeek("funeral");
$thisWeekSickcall = getDatathisWeek("sickcall");
$thisWeekMass = getDatathisWeek("mass");
$thisWeekBlessing = getDatathisWeek("blessing");

$totalthisWeekWedding = count($thisWeekWedding);
$totalthisWeekBaptismal = count($thisWeekBaptismal);
$totalthisWeekFuneral = count($thisWeekFuneral);
$totalthisWeekSickcall = count($thisWeekSickcall);
$totalthisWeekMass = count($thisWeekMass);
$totalthisWeekBlessing = count($thisWeekBlessing);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="image/admin.ico">
    <title>Dashborad | Admin</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Include jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Include Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Include DataTables plugin -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
</head>
<?php 
    $activePage = 'dashboard'; 
    include 'nav.php';
    ?>

<body>
    <div></div>
    <link rel="stylesheet" href="style/dashboard.css">

    <div class="dashboardcontent">
        <main>
            <div class="grid-area-1">
                <div class="distance">
                    <section class="distance__section distance__cycling">
                        <p>₱ <?php echo number_format($total_sales); ?></p>
                        <h2>Sales</h2>
                        <img src="image/icons8-sales.gif" alt="">
                    </section>
                    <section class="distance__section distance__running">
                        <p><?php echo $total_product; ?></p>
                        <h2>Products</h2>
                        <img src="image/icons8-price-tag.gif" alt="">
                    </section>
                    <section class="distance__section distance__swimming">
                        <p><?php echo !empty($total_order) ? $total_order : '0'; ?></p>
                        <h2>Item Sold</h2>
                        <img src="image/icons8-cart.gif" alt="">
                    </section>

                </div>
                <section class="challenges">
                    <h2>Best Seller</h2>
                    <br>
                    <?php if (!empty($bestSellers)): ?>
                    <?php
        
                    $max_quantity = max(array_column($bestSellers, 'total_quantity'));

                    // Loop through best sellers and display them
                    foreach ($bestSellers as $index => $seller):
                        $product_name = $seller['product_name'];
                        $total_quantity = $seller['total_quantity'];
                        $width_percentage = ($max_quantity > 0) ? ($total_quantity / $max_quantity) * 100 : 0;
                        $bar_class = "challenges__bar challenges__bar--" . ($index + 1);
                        ?>
                    <div class="<?php echo $bar_class; ?>">
                        <div class="challenges__bar-fill" style="width: <?php echo $width_percentage; ?>%;"></div>
                    </div>
                    <p class="challenges__text"><?php echo $product_name . ' - ' . $total_quantity . ' sold'; ?></p>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p class="challenges__text"><br><br><br>No Orders found.<br><br><br><br></p>
                    <?php endif; ?>
                </section>



                <div class="grid-area-1-2">
                    <section class="recent">
                        <h2 style="font-size: 25px; padding: 5px 0 10px; 0">Record Book</h2>
                        <table cellspacing="0" cellpadding="0">
                            <tr>
                                <td><img src="image/baptismal_icon.png" alt=""></td>
                                <td>Baptismal</td>
                                <td><?php echo $total_binyag; ?></td>
                            </tr>
                            <tr>
                                <td><img src="image/funeral_icon.png" alt=""></td>
                                <td>Funeral</td>
                                <td><?php echo $total_funeral; ?></td>
                            </tr>
                            <tr>
                                <td><img src="image/wedding_icon.png" alt=""></td>
                                <td>Wedding</td>
                                <td><?php echo $total_wedding; ?></td>
                            </tr>
                            <tr>
                                <td><img src="image/blessing_icon.png" alt=""></td>
                                <td>Blessing</td>
                                <td><?php echo $total_blessing; ?></td>
                            </tr>
                            <tr>
                                <td><img src="image/mass_icon.png" alt=""></td>
                                <td>Mass</td>
                                <td><?php echo $total_mass; ?></td>
                            </tr>
                            <tr>
                                <td><img src="image/sickcall_icon.png" alt=""></td>
                                <td>Sickcall</td>
                                <td><?php echo $total_sickcall; ?></td>
                            </tr>
                        </table>
                        <a href="recordbook.php" style="padding-top: 15px;">View all &rarr;</a>
                    </section>

                </div>
            </div>

            <div class="grid-area-3">
                <section class="weekly">
                    <h2>Mass Intentions Schedule for this Week</h2>
                    <br>

                    <hr>
                    <?php
                    date_default_timezone_set('Asia/Manila');
                    $current_date = new DateTime('now');
                    $current_week_start = new DateTime('monday this week');
                    $current_week_end = new DateTime('sunday this week');
                    $events_this_week = array_filter($massIntention, function($item) use ($current_week_start, $current_week_end) {
                        $start_date = new DateTime($item['date_started']);
                        $end_date = new DateTime($item['date_ended']);
                        return $start_date >= $current_week_start && $end_date <= $current_week_end;
                    });
                    usort($events_this_week, function($a, $b) {
                        return strtotime($a['date_started']) - strtotime($b['date_started']);
                    });

                    if (empty($events_this_week)) {
                        echo "<p>No record found</p>";
                    } else {
                        ?>
                    <table cellspacing="0" cellpadding="0">
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Intention</th>
                            <th>Time</th>
                        </tr>
                        <?php
                        $counter = 0;
                        foreach ($events_this_week as $item) {
                            if ($counter >= 5) {
                                break; 
                            }

                            $formatted_date_started = date('M d, Y', strtotime($item['date_started']));
                            $formatted_date_ended = date('M d, Y', strtotime($item['date_ended']));
                            $day_of_week = date('l', strtotime($item['date_started']));

                            echo "<tr>";
                            echo "<td>{$formatted_date_started} - {$formatted_date_ended}</td>";
                            echo "<td>{$day_of_week}</td>";
                            echo "<td>{$item['purpose']}</td>";
                            echo "<td>{$item['time']}</td>";
                            echo "</tr>";

                            $counter++;
                        }
                        ?>
                    </table>
                    <?php } ?>
                </section>

                <section class="last-week">
                    <h2>Applied Services compared to last week</h2>
                    <br>
                    <div class="last-week__legends">
                        <span class="last-week__legend-last-week">⬤ Last Week</span>
                        <br>
                        <span class="last-week__legend-this-week">⬤ This Week</span>
                    </div>
                    <?php
                        $totalValues = [
                            $totalLastWeekWedding, $totalthisWeekWedding,
                            $totalLastWeekBaptismal, $totalthisWeekBaptismal,
                            $totalLastWeekFuneral, $totalthisWeekFuneral,
                            $totalLastWeekBlessing, $totalthisWeekBlessing,
                            $totalthisWeekSickcall, $totalLastWeekSickcall,
                            $totalLastWeekMass, $totalthisWeekMass
                        ];

                        $noDataFound = true;
                        foreach ($totalValues as $value) {
                            if (!empty($value)) {
                                $noDataFound = false;
                                break;
                            }
                        }

                        if ($noDataFound) {
                            echo "<p>No Data Found</p>";
                        } else {
                            ?>
                    <div class="last-week__chart">
                        <div class="last-week__bar last-week__bar--1"
                            style="height: <?php echo abs($totalLastWeekWedding) * 10; ?>px;">
                            <p><?php echo $totalLastWeekWedding != 0 ? $totalLastWeekWedding : ""; ?></p>
                        </div>
                        <div class="last-week__bar last-week__bar--6"
                            style="height: <?php echo abs($totalthisWeekWedding) * 10; ?>px;">
                            <p><?php echo $totalthisWeekWedding != 0 ? $totalthisWeekWedding : ""; ?></p>
                        </div>

                        <div class="last-week__bar last-week__bar--1"
                            style="height: <?php echo abs($totalLastWeekBaptismal) * 10; ?>px;">
                            <p><?php echo $totalLastWeekBaptismal != 0 ? $totalLastWeekBaptismal : ""; ?></p>
                        </div>
                        <div class="last-week__bar last-week__bar--6"
                            style="height: <?php echo abs($totalthisWeekBaptismal) * 10; ?>px;">
                            <p><?php echo $totalthisWeekBaptismal != 0 ? $totalthisWeekBaptismal : ""; ?></p>
                        </div>
                        <div class="last-week__bar last-week__bar--1"
                            style="height: <?php echo abs($totalLastWeekFuneral) * 10; ?>px;">
                            <p><?php echo $totalLastWeekFuneral != 0 ? $totalLastWeekFuneral : ""; ?></p>
                        </div>
                        <div class="last-week__bar last-week__bar--6"
                            style="height: <?php echo abs($totalthisWeekFuneral) * 10; ?>px;">
                            <p><?php echo $totalthisWeekFuneral != 0 ? $totalthisWeekFuneral : ""; ?></p>
                        </div>

                        <div class="last-week__bar last-week__bar--1"
                            style="height: <?php echo abs($totalLastWeekBlessing) * 10; ?>px;">
                            <p><?php echo $totalLastWeekBlessing != 0 ? $totalLastWeekBlessing : ""; ?></p>
                        </div>
                        <div class="last-week__bar last-week__bar--6"
                            style="height: <?php echo abs($totalthisWeekBlessing) * 10; ?>px;">
                            <p><?php echo $totalthisWeekBlessing != 0 ? $totalthisWeekBlessing : ""; ?></p>
                        </div>

                        <div class="last-week__bar last-week__bar--1"
                            style="height: <?php echo abs($totalthisWeekSickcall) * 10; ?>px;">
                            <p><?php echo $totalthisWeekSickcall != 0 ? $totalthisWeekSickcall : ""; ?></p>
                        </div>
                        <div class="last-week__bar last-week__bar--6"
                            style="height: <?php echo abs($totalLastWeekSickcall) * 10; ?>px;">
                            <p><?php echo $totalLastWeekSickcall != 0 ? $totalLastWeekSickcall : ""; ?></p>
                        </div>

                        <div class="last-week__bar last-week__bar--1"
                            style="height: <?php echo abs($totalLastWeekMass) * 10; ?>px;">
                            <p><?php echo $totalLastWeekMass != 0 ? $totalLastWeekMass : ""; ?></p>
                        </div>
                        <div class="last-week__bar last-week__bar--6"
                            style="height: <?php echo abs($totalthisWeekMass) * 10; ?>px;">
                            <p><?php echo $totalthisWeekMass != 0 ? $totalthisWeekMass : ""; ?></p>
                        </div>


                    </div>
                    <div class="last-week__labels">
                        <p>Wedding</p>
                        <p>Baptismal</p>
                        <p>Funeral</p>
                        <p>Blessing</p>
                        <p>Sickcall</p>
                        <p>Mass</p>
                    </div>
                    
                    <?php
                        }
                        ?>
                </section>


            </div>
        </main>
    </div>

    <div class="right">
        <div class="toast-panel">
            <?php 
            $unreadCount = 0; 
            foreach ($inventory as $item) {
                if ($unreadCount >= 2) {
                    break;
                }
                if ($item['status'] == 'unread') {
                    $service = $item['services'];
                    if (array_key_exists($service, $service_links)) {
                        $href = $service_links[$service];
                    } else {
                        $href = '';
                    }
                    if (stripos($service, 'Certificate') === false) {
                        $unreadCount++; // Increment unread count
                    ?>
            <div class="toast-item apply">
                <div class="toast apply">
                    <a href="<?php echo $href; ?>" class="close view-link" data-id="<?php echo $item['id']; ?>"
                        for="t-apply" class="close"></a>
                    <h3>Application for <?php echo $service; ?>!</h3>
                    <p><strong><?php echo $item["customer_name"] ?></strong> is applied for
                        <strong><?php echo $service; ?>. <br><span><?php echo $item['time_component']; ?></span>.
                    </p>
                </div>
            </div>
            <?php
            } else {
                $unreadCount++; // Increment unread count
                ?>
            <div class="toast-item request">
                <div class="toast request">
                    <a href="<?php echo $href; ?>" class="close view-link" data-id="<?php echo $item['id']; ?>"
                        for="t-request" class="close"></a>
                    <h3>Request for <?php echo $service; ?>!</h3>
                    <p><strong><?php echo $item["customer_name"] ?></strong> is requested for
                        <strong><?php echo $service; ?>. <br><span><?php echo $item['time_component']; ?></span>.
                    </p>
                </div>
            </div>

            <?php
                    }
                }
            } 
            ?>
        </div>
    </div>

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
    </div>
</body>

</html>

<style>
.content {
    grid-template-columns: 15% auto 15%;
}

.right {
    margin-top: 30px;
    margin-right: 10px;
}

@import url("https://fonts.googleapis.com/css2?family=Varela+Round&display=swap");

:root {
    --tr: all 0.5s ease 0s;
    --ch1: #05478a;
    --ch2: #0070e0;
    --cs1: #005e38;
    --cs2: #03a65a;
    --cw1: #c24914;
    --cw2: #fc8621;
    --ce1: #851d41;
    --ce2: #db3056;
}

.toast-panel {
    padding-top: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: var(--tr);
    /* margin-left: -30px; */
}

.toast-item {
    /*overflow: hidden;*/
    max-height: 20rem;
    transition: var(--tr);
    position: relative;
}


.toast {
    background: #fff;
    color: #f5f5f5;
    padding: 0.5rem 1rem 1rem 2rem;
    text-align: center;
    border-radius: 1rem;
    position: relative;
    font-weight: 300;
    margin: 1rem 0;
    text-align: left;
    width: 18rem;
    max-height: 130px;
    transition: var(--tr);
    opacity: 1;
    border: 0.15rem solid #fff2;
    box-shadow: 0 0 1.5rem 0 #1a1f4360;
}

.toast:before {
    content: "";
    position: absolute;
    width: 0.5rem;
    height: calc(100% - 1.5rem);
    top: 0.75rem;
    left: 0.5rem;
    z-index: 0;
    border-radius: 1rem;
    background: var(--clr);
}

.toast h3 {
    font-size: 19px;
    margin: 0;
    line-height: 1.6rem;
    font-weight: 600;
    position: relative;
    color: var(--clr);
}

.toast p {
    position: relative;
    font-size: 15px;
    z-index: 1;
    margin: 0.25rem 0 0;
    color: #595959;
    line-height: 1.2rem;
}

.toast span {
    position: relative;
    font-size: 12px;
    z-index: 1;
    margin: 0.25rem 0 0;
    color: #595959;
    line-height: 1rem;
}

.close {
    position: absolute;
    width: 2rem;
    height: 2rem;
    text-align: center;
    right: 1rem;
    cursor: pointer;
    border-radius: 100%;
    padding-left: 15px;
    /* padding-right: 5px; */
    border: none !important;
    color: #595959;
}

.close:after {
    position: absolute;
    font-family: "Varela Round", san-serif;
    width: 100%;
    height: 100%;
    font-size: 2rem;
    content: "→";
    border-radius: 20%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000;

}

.close:hover:after {
    padding-left: 5px;
    padding-right: 5px;
}

.toast-item.request {
    animation-delay: 2s;
}


.toast.apply {
    --bg: var(--ch1);
    --clr: var(--ch2);
    --brd: var(--ch3);
}

.icon-apply:after {
    content: "?";
}

.toast.request {
    --bg: var(--cs1);
    --clr: var(--cs2);
    --brd: var(--cs3);
}


.toast a {
    color: var(--clr);
}

.toast a:hover {
    color: var(--bg);
}

/ input[type="checkbox"] {
    display: none;
}
</style>