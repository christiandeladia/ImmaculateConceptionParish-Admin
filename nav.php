<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nav</title>
    <link rel="icon" type="image/x-icon" href="image/favicon.ico">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

</head>
<?php
require "process/connect.php";

$admin_id = $_SESSION['auth_admin']['admin_id'];

$sql = "SELECT * FROM admin_login WHERE admin_id = $admin_id";

$result = mysqli_query($conn, $sql);

if ($result) {
    $admin_data = mysqli_fetch_assoc($result);
    
    $admin_id = $admin_data['admin_id'];
    $profile_image = $admin_data['profile_image'];
    $firstname = $admin_data['firstname'];
    $lastname = $admin_data['lastname'];
    $email = $admin_data['email'];
    $mobile = $admin_data['mobile'];
    $status = $admin_data['status'];
    mysqli_free_result($result);
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>

<body>

    <div class="nav-header">
        <img class="logo" src="image/admin_logo.png" />
        <div class="topnav-right">
            <a href="notification.php" class="inbox-btn" title="Notifications">
                <i class="fas fa-bell"></i>
                <?php if ($total_notif_mass > 0): ?>
                <span class="msg-count">
                    <span><?php echo $total_notif_mass; ?></span>
                </span>
                <?php endif; ?>
            </a>

            <div class="profile" <?php echo !$is_admin_logged_in ? "style='display: none;'" : ""; ?>>

                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
                    integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY4Nl+M1I1S7LQCG5tN2cFZJm" crossorigin="anonymous">

                <div class="profile-container">

                    <div class="img-box">
                        <!-- <img src="https://i.postimg.cc/BvNYhMHS/user-img.jpg" alt="some user image"> -->
                        <img src="<?php echo $profile_image; ?>" alt="some user image">

                    </div>
                    <span class="dropdown-icon"> <i class="fas fa-angle-down"> </i></span>
                </div>

            </div>
            <div class="menu">
                <ul>

                    <li><a href=" " data-toggle="modal" data-target="#modalprofile"><i
                                class="fas fa-user"></i>&nbsp;Profile</a></li>
                    <!-- <li><a href="#"><i class="fas fa-cogs"></i>&nbsp;Settings</a></li> -->
                    <li><a href="admin.php"><i class="fas fa-plus"></i>&nbsp;Add Admin</a></li>
                    <li><a href="process/logout.php" style="color:red;"><i class="fas fa-sign-out-alt"></i>&nbsp;Sign
                            Out</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="content">

        <div class="sidebar">
            <div class="admin-profile">
                <img class="avatar" src="<?php echo $profile_image; ?> " width="auto" height="60"
                    style="border-radius: 50%; " />
                <p class="name"><?php echo $firstname . ' ' . $lastname; ?></p>
                <!-- Display other admin data here -->
            </div>

            <a <?php if ($activePage === 'dashboard') echo 'class="active"'; ?> href="dashboard.php"><i
                    class="fas fa-chart-bar"></i> Dashboard</a>
            <a <?php if ($activePage === 'products') echo 'class="active"'; ?> href="products.php"><i
                    class="fas fa-box"></i> Products
            </a>
            <a <?php if ($activePage === 'orders') echo 'class="active"'; ?> href="orders.php"><i
                    class="fas fa-shopping-cart"></i> Orders <?php if ($total_order > 0): ?>
                <span class="badge badge-danger rounded-circle"><?php echo $total_order; ?></span>
                <?php endif; ?></a>
            <div class="services-dropdown">
                <a href="#"><i class="fas fa-church"></i> Services &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; <i
                        class="fas fa-caret-down caret-down"></i></a>
                <div class="services-dropdown-content">
                    <a <?php if ($activePage === 'wedding') echo 'class="active"'; ?> href="wedding.php"> Wedding
                        <?php if ($total_wedding > 0): ?>
                        <span class="badge badge-danger rounded-circle"><?php echo $total_wedding; ?></span>
                        <?php endif; ?></a>
                    <a <?php if ($activePage === 'mass') echo 'class="active"'; ?> href="mass.php">Mass
                        <?php $total_mass = getMassTotal(); ?>
                        <?php if ($total_mass > 0): ?>
                        <span class="badge badge-danger rounded-circle"><?php echo $total_mass; ?></span>
                        <?php endif; ?>
                    </a>
                    <a <?php if ($activePage === 'funeral') echo 'class="active"'; ?> href="funeral.php"> Funeral
                        <?php if ($total_funeral > 0): ?>
                        <span class="badge badge-danger rounded-circle"><?php echo $total_funeral; ?></span>
                        <?php endif; ?></a>
                    <a <?php if ($activePage === 'baptismal') echo 'class="active"'; ?> href="baptismal.php"> Baptismal
                        <?php if ($total_binyag > 0): ?>
                        <span class="badge badge-danger rounded-circle"><?php echo $total_binyag; ?></span>
                        <?php endif; ?></a>
                    <a <?php if ($activePage === 'sickcall') echo 'class="active"'; ?> href="sickcall.php"> Sick Call
                        <?php if ($total_sickcall > 0): ?>
                        <span class="badge badge-danger rounded-circle"><?php echo $total_sickcall; ?></span>
                        <?php endif; ?></a>
                    <a <?php if ($activePage === 'blessing') echo 'class="active"'; ?> href="blessing.php"> Blessing
                        <?php if ($total_blessing > 0): ?>
                        <span class="badge badge-danger rounded-circle"><?php echo $total_blessing; ?></span>
                        <?php endif; ?></a>
                </div>
            </div>

            <a <?php if ($activePage === 'records') echo 'class="active"'; ?> href="recordbook.php"><i
                    class="fas fa-book"></i>
                Record Book</a>
            <a <?php if ($activePage === 'admin') echo 'class="active"'; ?> href="admin.php"><i
                    class="fas fa-users"></i>
                Admin</a>

        </div>

        <!--Modal: Login with Avatar Form-->
        <div class="modal fade" id="modalprofile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-m" role="document">
                <!--Content-->
                <div class="admin_profile testimonial-admin_profile">
                    <!--Background color-->
                    <div class="admin_profile-up danger-color"></div>
                    <!--Avatar-->
                    <div class="avatar mx-auto white">
                        <img src="<?php echo $profile_image; ?>" class="rounded-circle img-fluid p-2" width="200">
                    </div>

                    <div class="admin_profile-body">
                        <div class="text-center">
                            <?php if ($status == 'active'): ?>
                            <span class="badge badge-success rounded-pill d-inline">Active</span>
                            <?php else: ?>
                            <span class="badge badge-danger rounded-pill d-inline">Inactive</span>
                            <?php endif; ?>
                        </div>

                        <!--Name-->
                        <h2 class="font-weight-bold mb-4 text-center"><?php echo $firstname . ' ' . $lastname; ?></h2>
                        <hr>
                        <dl class="row">
                            <dt class="col-sm-3 fs-4"><i class="fas fa-envelope"></i></dt>
                            <dd class="col-sm-9 fs-4"><?php echo $email; ?></dd>

                            <dt class="col-sm-3 fs-4"><i class="fas fa-phone-alt"></i></dt>
                            <dd class="col-sm-9 fs-4"><?php echo $mobile; ?></dd>
                        </dl>

                        <!-- <button type="button" class="btn btn-primary mb-2" id="changePasswordBtn">Change Password</button> -->
                    </div>
                </div>
                <!--/.Content-->
            </div>
        </div>

</body>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var servicesDropdown = document.querySelector('.services-dropdown');

    servicesDropdown.addEventListener('click', function() {
        var dropdownContent = this.querySelector('.services-dropdown-content');
        var servicesLink = this.querySelector('a');

        // Close other dropdowns and remove 'active' class
        document.querySelectorAll('.services-dropdown-content').forEach(function(content) {
            if (content !== dropdownContent) {
                content.style.display = 'none';
            }
        });

        document.querySelectorAll('.services-dropdown a').forEach(function(link) {
            if (link !== servicesLink) {
                link.classList.remove('active');
            }
        });

        // Toggle display and 'active' class for the clicked section
        dropdownContent.style.display = (dropdownContent.style.display === 'block') ? 'none' : 'block';
        servicesLink.classList.toggle('active');
    });
});
</script>

<script>
let profile = document.querySelector('.profile');
let menu = document.querySelector('.menu');

profile.onmouseover = function() {
    menu.classList.add('active');
}

profile.onmouseout = function() {
    menu.classList.remove('active');
}

menu.onmouseover = function() {
    menu.classList.add('active');
}

menu.onmouseout = function() {
    menu.classList.remove('active');
}
</script>

<!-- script for profile first and lastletter -->
<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> -->
<script>
$(document).ready(function() {
    var firstName = '<?php echo $admin_id; ?>';
    var lastName = '<?php echo $username; ?>';
    var initials = firstName.charAt(0) + lastName.charAt(0);
    $('#profileImage').text(initials);
});
</script>

</html>

<style>
body {
    margin: 0;
    font-size: 100% ! important;

}
/* notif button */
.inbox-btn {
        width: 47px;
        height: 47px;
        border-radius: 50%;
        border: none;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.082);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 10px;
        position: relative;
        background-color: #464646;
        cursor: pointer;
        transition: all 0.3s;
    }

    .inbox-btn svg path {
        fill: white;
    }

    .inbox-btn svg {
        height: 17px;
        transition: all 0.3s;
        font-size: 20px;
    }

    .inbox-btn .fas {
        color: white;
        height: 17px;
        transition: all 0.3s;
        font-size: 20px;
    }

    .msg-count {
        position: absolute;
        top: -5px;
        right: -5px;
        /* background-color: rgb(255, 255, 255); */
        background-color: #dc3545;
        color: #fff;
        border-radius: 50%;
        font-size: 0.7em;
        /* color: rgb(0, 0, 0); */
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
.nav-header {
    display: flex;
    background-color: #212529;
    border-bottom: 1px solid #e7e7e7;
    width: 100%;
    position: fixed;
    top: 0;
    z-index: 1000;
}

.logo {
    padding: 5px 10px;
    width: 10%;
    margin-left: 20px;
}

.content {
    display: grid;
    margin-top: 0;
    grid-template-columns: 12% auto;
}

.sidebar {
    display: flex;
    margin: 0;
    padding: 0;
    width: 12%;
    background-color: #212529;
    position: fixed;
    height: 100%;
    flex-direction: column;
    text-align: left;
    font-size: 20px;
    border-right: 1px solid #e7e7e7;
}

.sidebar a {
    display: block;
    color: #fff;
    padding: 16px;
    text-decoration: none;
    width: 105%;
    overflow: visible;
    border-radius: 0 10px 10px 0;
}

.sidebar a.active {
    background-color: #086d48;
    color: white;
}

.sidebar .bottom {
    display: flex;
    flex-direction: column;
    height: 40%;
    margin-bottom: 10px;
    justify-content: flex-end;
}

.sidebar a:hover:not(.active) {
    background-color: #797878;
    color: white;
}

.sidebar a i.fas {
    padding-right: 10px;
    font-size: 20px;
}

.admin-profile {
    align-items: center;
    gap: 10px;
}


.admin-profile .name {
    text-align: center;
    font-weight: 500;
    white-space: nowrap;
    margin: 10px auto 20px auto;
    color: #fff;
}

.services-dropdown {
    position: relative;
    display: inline-block;
}

.services-dropdown-content {
    display: none;
    position: static;
    background-color: #fff;
    /* background-color: #212529; */
    min-width: 160px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
    /* border-radius: 0 10px 10px 0; */
    left: 100%;
    top: 0;
}

.services-dropdown-content a {
    color: black;
}

.dropdown-toggle {
    cursor: pointer;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #212529;
    min-width: 160px;

    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
    border-radius: 0 0 10px 10px;
    /* Adjust the border-radius as needed */
}

.dropdown:hover .dropdown-content {
    display: block;
}

.caret-down {
    color: ;
    /* You can replace 'red' with your desired color */
    text-align: right;
}


.navs {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 80px;
    background: #fff;
    box-shadow: 0 10px 20px rgba(0, 0, 0, .2);
    padding: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* menu toggle */

.menu-toggle {
    position: relative;
    width: 40px;
    height: 40px;
    cursor: not-allowed;
    display: flex;
    justify-content: center;
    align-items: center;
}

.menu-toggle::before {
    content: '';
    position: absolute;
    width: 24px;
    height: 4px;
    background: #000;
    box-shadow: 0 8px 0 #000,
        0 -8px 0 #000;
}

/* profile menu */

.profile {
    position: relative;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 7px;
    cursor: pointer;
    text-align: end;
    width: 50px;
    /* margin-left: 20px; */
    margin-right: 20px;
    max-height: 40px;
}

.profile h3 {
    text-align: end;
    line-height: 1;
    margin-bottom: -10px;
    font-weight: 600;
    color: #FFF;
    font-size: 16px;
}

.profile p {
    line-height: 1;
    font-size: 12px;
    opacity: .6;
    color: #FFF;
}

.profile .img-box {
    position: relative;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    overflow: hidden;
}

.profile .img-box img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* menu (the right one) */


.menu {
    position: absolute;
    top: calc(100% + 24px);
    right: 40px;
    width: 200px;
    min-height: 100px;
    background: #212529;
    box-shadow: 0 10px 20px rgba(0, 0, 0, .2);
    opacity: 0;
    transform: translateY(-10px);
    visibility: hidden;
    transition: 300ms;
}


.menu::before {
    content: '';
    position: absolute;
    top: -10px;
    right: 14px;
    width: 20px;
    height: 20px;
    background: #212529;
    transform: rotate(45deg);
    z-index: -1;
}

.menu.active {
    opacity: 1;
    transform: translateY(0);
    visibility: visible;
}

/* menu links */

.menu ul {
    position: relative;
    display: flex;
    flex-direction: column;
    z-index: 10;
    background: #212529;
    padding: 0;
}

.menu li:not(:last-child) .fas {
    color: rgb(0, 128, 0);
}

.menu li:last-child {
    border-top: 1px solid rgba(0, 0, 0, 0.3);

}

.menu ul li {
    list-style: none;
}

.menu ul li a:hover {
    color: black;
    background: #eee;
    cursor: pointer;
}

.menu ul li a {
    text-decoration: none;
    color: #fff;
    display: flex;
    align-items: center;
    padding: 15px 20px;
    gap: 6px;
}

.menu ul li a i {
    font-size: 1.2em;
}

/* #profileImage {
width: 48px;
height: 48px;
border-radius: 50%;
background: rgb(37, 141, 54);
font-size: 18px;
color: #fff;
text-align: center;
line-height: 48px; 
font-weight: bold; 
} */
.profile-container {
    position: relative;
}

#profileImage {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #002d0a;
    font-size: 18px;
    color: #fff;
    text-align: center;
    line-height: 48px;
    font-weight: bold;
    cursor: pointer;
    position: relative;
    z-index: 1;
}

.dropdown-icon {
    width: 16px;
    /* Set the same size as the height for a perfect circle */
    height: 16px;
    /* Set the same size as the width for a perfect circle */
    line-height: 16px;
    /* Set the same value as the height for centering the icon */
    position: absolute;
    bottom: 0;
    right: 0;
    z-index: 2;
    border-radius: 50%;
    background: #333;
    /* Adjust the background color as needed */
    display: flex;
    justify-content: center;
    align-items: center;
}

.dropdown-icon i {
    color: #fff;
    /* Adjust the color of the dropdown icon as needed */
    font-size: 14px;
    /* Adjust the font size of the dropdown icon as needed */
}



/* Add these styles to align the logo and profile */
.nav-header {
    display: flex;
    justify-content: space-between;
    /* Aligns items to the leftmost and rightmost */
    align-items: center;
    background-color: #212529;
    border-bottom: 1px solid #e7e7e7;
    width: 100%;
    position: fixed;
    top: 0;
    z-index: 1000;
}

.logo {
    padding: 5px 10px;
    width: 200px;
    /* Set to 'auto' for flexible width */
    margin-left: 20px;
}

.topnav-right {
    display: flex;
    align-items: center;
    gap: 20px;
    /* Adjust the gap between logo and profile */
    margin-right: 20px;
}

.notification {
    color: white;
    font-size: 20px;
    margin-right: -20px;
    text-decoration: none;
}

.notification:link {
    text-decoration: none;
}

.notification:visited {
    text-decoration: none;
}

.notification:hover {
    text-decoration: none;
    color: white;
}

.notification:active {
    text-decoration: none;
}



.admin_profile.testimonial-admin_profile {
    background-color: white;
    padding: 0px;
    width: 600px;
    height: 450px;
    border-radius: 20px;
    display: grid;
    align-self: center;
    justify-content: space-evenly;
    font-size: 20px;
}

img.rounded-circle.img-fluid.p-2 {
    padding: 0;
}

.avatar.mx-auto.white {
    border: 3px solid green;
    border-radius: 50%;
    display: grid;
    align-self: center;
    justify-content: space-evenly;
}
</style>