<?php
  	require_once "process/connect.php";
    $is_admin_logged_in = isset($_SESSION['auth_admin']);
    if ( isset($_SESSION['auth_admin']) ) {
    
    function getCountUser() {
        global $pdo; 
        $sql = "SELECT COUNT(*) as totalCount FROM `login`";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['totalCount'];
    }
    $total_users = getCountUser();  

    function getProductTotal() {
        global $pdo; 
        $sql = "SELECT COUNT(*) as totalProducts FROM `inventory`";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['totalProducts'];
    }
    $total_product = getProductTotal();
    
    function getCount() {
        global $pdo; 
        $sql = "SELECT COUNT(*) as totalCount FROM `orders`";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['totalCount'];
    }
    $total_process = getCount();  
    function getCounts() {
        global $pdo; 
        $sql = "SELECT COUNT(DISTINCT group_order) as totalCount FROM `orders` WHERE status = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['totalCount'];
    }
    $total_ship = getCounts();
    
    
    function getOrderTotal() {
        global $pdo; 
        $sql = "SELECT SUM(product_quantity) as totalOrders FROM `orders`";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['totalOrders'];
    }
    
    $total_order = getOrderTotal();
    

    function getSalesTotal() {
        global $pdo;
        $sql = "SELECT SUM(product_price * product_quantity) AS total_sales FROM `orders` WHERE status = 4;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['total_sales'];
    }
    
    $total_sales = getSalesTotal();
    
    
    function getOrders() {
        global $pdo;
        $query = "SELECT *, DATE_FORMAT(date_added, '%M %d, %Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM orders";
        $inventory = [];
        $statement = $pdo->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    $inventory = getOrders();

    function getBaptismalTotal() {
        global $pdo; 
        $sql = "SELECT COUNT(*) as totalCount FROM `binyag` WHERE status_id = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['totalCount'];
    }
    $binyag = getBaptismalTotal();

    function getBaptismalCertTotal() {
        global $pdo; 
        $sql = "SELECT COUNT(*) as totalCount FROM `binyag_request_certificate` WHERE status_id = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['totalCount'];
    }
    $cert = getBaptismalCertTotal();
    $total_binyag = $binyag + $cert;


    function getBlessingTotal() {
        global $pdo; 
        $sql = "SELECT COUNT(*) as totalCount FROM `blessing` WHERE status_id = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['totalCount'];
    }
    $total_blessing = getBlessingTotal();  

    function getSickCallTotal() {
        global $pdo; 
        $sql = "SELECT COUNT(*) as totalCount FROM `sickcall` WHERE status_id = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['totalCount'];
    }
    $total_sickcall = getSickCallTotal();   


    function getMassTotal() {
        global $pdo; 
        $sql = "SELECT COUNT(*) as totalCount FROM `mass` WHERE status_id = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['totalCount'];
    }
    $total_mass = getMassTotal();  


    function getFuneralTotal() {
        global $pdo; 
        $sql = "SELECT COUNT(*) as totalCount FROM `funeral` WHERE status_id = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['totalCount'];
    }
    $total_funeral = getFuneralTotal();  

    

    function getWeddingTotal() {
        global $pdo; 
        $sql = "SELECT COUNT(*) as totalCount FROM `wedding` WHERE status_id = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['totalCount'];
    }
    $total_wedding = getWeddingTotal();   

    
    function getBinyagRequestCertificate() {
        global $pdo; 
        $sql = "SELECT COUNT(*) as totalCount FROM `binyag_request_certificate`";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['totalCount'];
    }
    $total_binyag_request_certificate = getBinyagRequestCertificate();  


    function getNotifMassTotal() {
        global $pdo; 
        $sql = "SELECT COUNT(*) as total_notif_mass FROM `notification` WHERE status= 'unread' ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total_notif_mass'];
    }
    $total_notif_mass = getNotifMassTotal();
        }
?>