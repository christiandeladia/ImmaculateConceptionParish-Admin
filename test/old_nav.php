<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nav</title>
    <link rel="icon" type="image/x-icon" href="image/admin.ico">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
</head>
<style>

</style>
<body>

<div class="nav-header">
            <img class="logo" src="image/logo_white.png" />
        </div>
    <div class="content">
        
        <div class="sidebar">
        <div class="admin-profile">
          <img
            class="avatar"
            src="image/admin-avatar.png"
            width="60"
          />
          <p class="name">Admin</p>
        </div>
            <div>
                <a <?php if ($activePage === 'dashboard') echo 'class="active"'; ?> href="dashboard.php"><i class="fas fa-chart-bar"></i> Dashboard</a>
                <a <?php if ($activePage === 'products') echo 'class="active"'; ?> href="products.php"><i class="fas fa-box"></i> Products</a>
                <a <?php if ($activePage === 'orders') echo 'class="active"'; ?> href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
                <a <?php if ($activePage === 'services') echo 'class="active"'; ?> href="services.php"><i class="fas fa-church"></i> Services</a>
                <a <?php if ($activePage === 'certificates') echo 'class="active"'; ?> href="#about"><i class="fas fa-certificate"></i>Certificates</a>
                <a <?php if ($activePage === 'records') echo 'class="active"'; ?> href="#about"><i class="fas fa-book"></i> Record Book</a>
            </div>

            <div class="bottom">
            <a href="process/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>

</body>

</html>