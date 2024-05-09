<?php
require "process/connect.php";
if (!isset($_SESSION['auth_admin'])) {
    header("location: index.php");
    exit;
}
?>
<?php include 'process/formula.php' ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image/admin.ico">
    <title>SERVICES - ADMIN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

<body>
    <?php 
    $activePage = 'services'; 
    include 'nav.php';
    ?>
    <div></div>
    <div class="dashboard">
        <a href="wedding.html" class="card">
            <img class="wed" src="image/wedding.jpg">
            <p class="wedcard">Weddings</p>
            <!-- <span class="badge">3</span> -->
        </a>
        <a href="baptismal.php" class="card">
            <img class="bap" src="image/baptismal.jpg">
            <p class="bapcard">Baptismal</p>
            <?php if ($total_binyag > 0): ?>
                <span class="badge"><?php echo $total_binyag; ?></span>
            <?php endif; ?>
        </a>
        <a href="funeral.html" class="card">
            <img class="funeral" src="image/funeral.jpg">
            <p class="funeralcard">Funeral</p>
        </a>
        <a href="blessing.php" class="card">
            <img class="blessing" src="image/blessing.jpg">
            <p class="blessingcard">Blessing</p>
        </a>
        <a href="sickcall.html" class="card">
            <img class="sick" src="image/sickcall.jpg">
            <p class="sickcard">Sick Call</p>
        </a>
        <a href="others.html" class="card">
            <p class="sickcard" style="color:rgb(26, 26, 26)">Others</p>
        </a>
    </div>
    <div class="right">
        <div class="notif">
            <p class="heading">Notifications</p>
            <p class="description">We use cookies to ensure that we give you the best experience on our website.
                <br><a href="#">Read cookies policies</a>.
            </p>
            <div class="buttonContainer">
                <button class="acceptButton">Accept</button>
                <button class="declineButton">Decline</button>
            </div>
        </div>
        <br>
        <div class="notif">
            <p class="heading">Orders</p>
            <p class="description">We use cookies to ensure that we give you the best experience on our website.
                <br><a href="#">Read cookies policies</a>.
            </p>
            <div class="buttonContainer">
                <button class="acceptButton">Accept</button>
                <button class="declineButton">Decline</button>
            </div>
        </div>
    </div>
    </div>
</body>

</html>

<style>
.content {
    display: grid;
    grid-template-columns: 15% auto 20%;
}

.dashboard {
    padding: 70px 5px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 15px;
}

.card {
    box-sizing: border-box;
    position: relative;
    width: 380px;
    height: 230px;
    background: rgba(217, 217, 217, 0.58);
    border: 1px solid white;
    box-shadow: 12px 17px 51px rgba(0, 0, 0, 0.22);
    backdrop-filter: blur(6px);
    border-radius: 17px;
    text-align: center;
    align-items: center;
    cursor: pointer;
    transition: all 0.5s;
    display: flex;
    justify-content: center;
    user-select: none;
    font-weight: bolder;
    font-size: 20px;
    padding: 20px;
    color: black;
}

.wed,
.bap,
.funeral,
.blessing,
.sick {
    max-width: 100%;
    width: 370px;
    border-radius: 10px;
    height: 200px;
}

.wedcard,
.bapcard,
.funeralcard,
.blessingcard,
.sickcard {
    position: absolute;
    color: #f1f1f1;
    font-size: 30px;
}

.card:hover {
    border: 1px solid black;
    transform: scale(1.05);
}

.card:active {
    transform: scale(0.95) rotateZ(1.7deg);
}

.notif {
    width: 300px;
    height: 220px;
    background-color: rgb(255, 255, 255);
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px 30px;
    gap: 13px;
    position: relative;
    overflow: hidden;
    box-shadow: 2px 2px 20px rgba(0, 0, 0, 0.062);
}

.heading {
    font-size: 1.2em;
    font-weight: 800;
    color: rgb(26, 26, 26);
    text-align: left;
    align-items: start;
}

.description {
    text-align: center;
    font-size: 0.7em;
    font-weight: 600;
    color: rgb(99, 99, 99);
}

.buttonContainer {
    display: flex;
    gap: 20px;
    flex-direction: row;
}

.acceptButton {
    width: 80px;
    height: 30px;
    background-color: #086d48;
    transition-duration: .2s;
    border: none;
    color: rgb(241, 241, 241);
    cursor: pointer;
    font-weight: 600;
    border-radius: 20px;
    box-shadow: 0 4px 6px -1px #086d48, 0 2px 4px -1px #086d48;
    transition: all .6s ease;
}

.declineButton {
    width: 80px;
    height: 30px;
    background-color: #dadada;
    transition-duration: .2s;
    color: rgb(46, 46, 46);
    border: none;
    cursor: not-allowed;
    font-weight: 600;
    border-radius: 20px;
    box-shadow: 0 4px 6px -1px #bebdbd, 0 2px 4px -1px #bebdbd;
    transition: all .6s ease;
}

.declineButton:hover {
    background-color: #ebebeb;
    box-shadow: 0 10px 15px -3px #bebdbd, 0 4px 6px -2px #bebdbd;
    transition-duration: .2s;
}

.acceptButton:hover {
    background-color: #086d48;
    box-shadow: 0 10px 15px -3px #086d48, 0 4px 6px -2px #086d48;
    transition-duration: .2s;
}

.badge {
    position: absolute;
    top: -10px;
    right: -10px;
    padding: 10px 17px;
    border-radius: 50%;
    background-color: green;
    color: white;
}
</style>