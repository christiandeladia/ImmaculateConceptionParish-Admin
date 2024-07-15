




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



function getMassIData() {
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%M %d, %Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM mass WHERE status_id = 2 ORDER BY date_added DESC";
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
    $query = "SELECT *, DATE_FORMAT(date_added, '%M %d, %Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM mass WHERE status_id = 3";
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
    $query = "SELECT *, DATE_FORMAT(date_added, '%M %d, %Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM wedding WHERE status_id = 3";
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
    $query = "SELECT *, DATE_FORMAT(date_added, '%M %d, %Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM binyag WHERE status_id = 3";
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
    $query = "SELECT *, DATE_FORMAT(date_added, '%M %d, %Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM blessing WHERE status_id = 3";
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
    $query = "SELECT *, DATE_FORMAT(date_added, '%M %d, %Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM funeral WHERE status_id = 3";
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
    $query = "SELECT *, DATE_FORMAT(date_added, '%M %d, %Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM sickcall WHERE status_id = 3";
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
    $query = "SELECT *, DATE_FORMAT(date_added, '%M %d, %Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM $tableName WHERE WEEK(date_added) = WEEK(NOW()) - 1";
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
function getDatathisWeek($tableName) {
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%M %d, %Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM $tableName WHERE WEEK(date_added) = WEEK(NOW())";
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
<link rel="stylesheet" href="style/dashboard.css">
<div class="dashboardContent">
<div class="content">
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
                    <p><?php echo $total_order; ?></p>
                    <h2>New Orders</h2>
                    <img src="image/icons8-cart.gif" alt="">
                </section>
            </div>

            <section class="weekly">
                <h2>Mass Intentions Schedule for this Week</h2>
                <table cellspacing="0" cellpadding="0">
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Intention</th>
                        <th>Time</th>
                    </tr>
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
            </section>


            <div class="grid-area-1-2">
                <section class="recent">
                    <h2>Record Book</h2>
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
                    <a href="recordbook.php">View all &rarr;</a>
                </section>

            </div>
        </div>

        <div class="grid-area-3">
            <section class="last-week">
                <h2>Applied Services compared to last week</h2>
                <div class="last-week__chart">

                    <div class="last-week__bar last-week__bar--1"
                        style="height: <?php echo abs($totalLastWeekWedding) * 10; ?>px;">
                        <p><?php echo $totalLastWeekWedding; ?></p>
                    </div>
                    <div class="last-week__bar last-week__bar--2"
                        style="height: <?php echo abs($totalthisWeekWedding) * 10; ?>px;">
                        <p><?php echo $totalthisWeekWedding; ?></p>
                    </div>

                    <div class="last-week__bar last-week__bar--3"
                        style="height: <?php echo abs($totalLastWeekBaptismal) * 10; ?>px;">
                        <p><?php echo $totalLastWeekBaptismal; ?></p>
                    </div>
                    <div class="last-week__bar last-week__bar--4"
                        style="height: <?php echo abs($totalthisWeekBaptismal) * 10; ?>px;">
                        <p><?php echo $totalthisWeekBaptismal; ?></p>
                    </div>
                    <div class="last-week__bar last-week__bar--5"
                        style="height: <?php echo abs($totalLastWeekFuneral) * 10; ?>px;">
                        <p><?php echo $totalLastWeekFuneral; ?></p>
                    </div>
                    <div class="last-week__bar last-week__bar--6"
                        style="height: <?php echo abs($totalthisWeekFuneral) * 10; ?>px;">
                        <p><?php echo $totalthisWeekFuneral; ?></p>
                    </div>

                    <div class="last-week__bar last-week__bar--1"
                        style="height: <?php echo abs($totalLastWeekBlessing) * 10; ?>px;">
                        <p><?php echo $totalLastWeekBlessing; ?></p>
                    </div>
                    <div class="last-week__bar last-week__bar--2"
                        style="height: <?php echo abs($totalthisWeekBlessing) * 10; ?>px;">
                        <p><?php echo $totalthisWeekBlessing; ?></p>
                    </div>

                    <div class="last-week__bar last-week__bar--3"
                        style="height: <?php echo abs($totalthisWeekSickcall) * 10; ?>px;">
                        <p><?php echo $totalthisWeekSickcall; ?></p>
                    </div>
                    <div class="last-week__bar last-week__bar--4"
                        style="height: <?php echo abs($totalLastWeekSickcall) * 10; ?>px;">
                        <p><?php echo $totalLastWeekSickcall; ?></p>
                    </div>

                    <div class="last-week__bar last-week__bar--5"
                        style="height: <?php echo abs($totalLastWeekMass) * 10; ?>px;">
                        <p><?php echo $totalLastWeekMass; ?></p>
                    </div>
                    <div class="last-week__bar last-week__bar--6"
                        style="height: <?php echo abs($totalthisWeekMass) * 10; ?>px;">
                        <p><?php echo $totalthisWeekMass; ?></p>
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
            </section>

            <section class="challenges">
                <h2>Best Seller</h2>
                <?php
                
                $query = "SELECT product_name, SUM(product_quantity) AS total_quantity 
                        FROM orders 
                        GROUP BY product_name 
                        ORDER BY total_quantity DESC 
                        LIMIT 3"; 

                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    $max_quantity = 0;

                    while ($row = mysqli_fetch_assoc($result)) {
                        $total_quantity = $row['total_quantity'];
                        if ($total_quantity > $max_quantity) {
                            $max_quantity = $total_quantity;
                        }
                    }

                    mysqli_data_seek($result, 0);

                    $counter = 1;

                    while ($row = mysqli_fetch_assoc($result)) {
                        $product_name = $row['product_name'];
                        $total_quantity = $row['total_quantity'];
                        $width_percentage = ($max_quantity > 0) ? ($total_quantity / $max_quantity) * 100 : 0;
                        $bar_class = "challenges__bar challenges__bar--" . $counter;
                        echo '<div class="' . $bar_class . '">';
                        echo '<div class="challenges__bar-fill" style="width: ' . $width_percentage . '%;"></div>'; // Add a div for the bar fill
                        echo '</div>';
                        echo '<p class="challenges__text">' . $product_name . ' - ' . $total_quantity . ' sold</p>';
                        $counter++;
                    }
                } else {
                    echo '<p class="challenges__text">No best sellers found.</p>';
                }
                ?>

            </section>
        </div>
    </main>
</div>
</div>
</div>