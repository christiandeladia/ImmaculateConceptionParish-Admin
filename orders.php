<?php
require "process/connect.php";
if (!isset($_SESSION['auth_admin'])) {
    header("location: index.php");
    exit;
}
?>
<?php include 'process/formula.php';?>
<?php

    $is_admin_logged_in = isset($_SESSION['auth_admin']);
    if ( isset($_SESSION['auth_admin']) ) {
}
?>
<?php
    $query = "SELECT * FROM orders ORDER BY group_order";
    $result = mysqli_query($conn, $query);
    
    $combinedRows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $groupOrder = $row['group_order'];
        if (isset($combinedRows[$groupOrder])) {
            $combinedRows[$groupOrder]['image'][] = $row['product_image'];
            $combinedRows[$groupOrder]['product_name'][] = $row['product_name'];
            $combinedRows[$groupOrder]['product_price'][] = $row['product_price'];
            $combinedRows[$groupOrder]['product_quantity'][] = $row['product_quantity'];
            $combinedRows[$groupOrder]['id'][] = $row['id'];
            $combinedRows[$groupOrder]['order_username'][] = $row['order_username'];
            $combinedRows[$groupOrder]['order_address'][] = $row['order_address'];
            $combinedRows[$groupOrder]['order_phonenumber'][] = $row['order_phonenumber'];
            $combinedRows[$groupOrder]['date_added'][] = $row['date_added'];
            $combinedRows[$groupOrder]['status'][] = $row['status'];
            $combinedRows[$groupOrder]['trackingNumber'][] = $row['trackingNumber'];
            $combinedRows[$groupOrder]['reason'][] = $row['reason'];
            $combinedRows[$groupOrder]['grandtotal'][] = $row['grandtotal'];
        } else {
            $combinedRows[$groupOrder] = [
                'image' => [$row['product_image']],
                'product_name' => [$row['product_name']],
                'product_price' => [$row['product_price']],
                'product_quantity' => [$row['product_quantity']],
                'id' => [$row['id']],
                'order_username' => [$row['order_username']],
                'order_phonenumber' => [$row['order_phonenumber']],
                'order_address' => [$row['order_address']],
                'date_added' => [$row['date_added']],
                'status' => [$row['status']],
                'trackingNumber' => [$row['trackingNumber']],
                'reason' => [$row['reason']],
                'grandtotal' => [$row['grandtotal']]
            ];
        }
    }
?>
<?php
function countStatusOneOccurrences($combinedRows) {
    $countStatusOne = 0; // Initialize counter for status 1
    foreach ($combinedRows as $groupOrder => $data) {
        if ($data['status'][0] == 1) { // Check if status is 1
            $countStatusOne++; // Increment counter for status 1
        }
    }
    return $countStatusOne;
}
$totalStatusOne = countStatusOneOccurrences($combinedRows);
?>
<?php
function countStatusTwoOccurrences($combinedRows) {
    $countStatusTwo = 0; // Initialize counter for status 2
    foreach ($combinedRows as $groupOrder => $data) {
        if ($data['status'][0] == 2) { // Check if status is 2
            $countStatusTwo++; // Increment counter for status 2
        }
    }
    return $countStatusTwo;
}
$totalStatusTwo = countStatusTwoOccurrences($combinedRows);
?>

<?php
function countStatus3Occurrences($combinedRows) {
    $countStatus3 = 0; // Initialize counter for status 2
    foreach ($combinedRows as $groupOrder => $data) {
        if ($data['status'][0] == 3) { // Check if status is 2
            $countStatus3++; // Increment counter for status 2
        }
    }
    return $countStatus3;
}
$totalStatus3 = countStatus3Occurrences($combinedRows);
?>
<?php
function countStatus4Occurrences($combinedRows) {
    $countStatus4 = 0; // Initialize counter for status 2
    foreach ($combinedRows as $groupOrder => $data) {
        if ($data['status'][0] == 4) { // Check if status is 2
            $countStatus4++; // Increment counter for status 2
        }
    }
    return $countStatus4;
}
$totalStatus4 = countStatus4Occurrences($combinedRows);
?>
<?php
function countStatus5Occurrences($combinedRows) {
    $countStatus5 = 0; // Initialize counter for status 2
    foreach ($combinedRows as $groupOrder => $data) {
        if ($data['status'][0] == 5) { // Check if status is 2
            $countStatus5++; // Increment counter for status 2
        }
    }
    return $countStatus5;
}
$totalStatus5 = countStatus5Occurrences($combinedRows);
?>
<?php
$totalOrders = $totalStatusOne + $totalStatusTwo + $totalStatus3 + $totalStatus4;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image/admin.ico">
    <title>Orders | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>


</head>

<body>
    <?php 
    $activePage = 'orders'; 
    include 'nav.php';
    ?>
    <div></div>
    <div class="product">
        <div class=".container-fluid mt-4 card mb-2 bg-light shadow" style=" margin: 0 3%">
            <div class="card-body">

                <section class="p-1 z-depth-1">
                    <h3 class="text-center font-weight-bold mb-4">ORDERS</h3>

                    <div class="row d-flex justify-content-center">
                        <div class="col-md-6 col-lg-4 text-center">
                            <h4 class="h2 font-weight-normal mb-1">
                                <i class="fas fa-box text-success"></i>
                                <span class="d-inline-block count-up" data-from="0" data-to="100"
                                    data-time="2000"><?php echo $total_order; ?></span>
                            </h4>
                            <p class="font-weight-normal text-muted">Total Items Sold</p>
                        </div>

                        <div class="col-md-6 col-lg-4 text-center">
                            <h4 class="h2 font-weight-normal mb-1">
                                <i class="fas fa-coins text-info"></i>
                                <span class="d-inline-block count1" data-from="0" data-to="250" data-time="2000">₱
                                    <?php echo number_format($total_sales); ?></span>
                            </h4>
                            <p class="font-weight-normal text-muted">Total Sales</p>
                        </div>

                        <div class="col-md-6 col-lg-4 text-center">
                            <h4 class="h2 font-weight-normal mb-1">
                                <i class="fas fa-shopping-cart text-warning"></i>
                                <span class="d-inline-block count2" data-from="0" data-to="330"
                                    data-time="2000"><?php echo number_format($totalOrders); ?></span>
                            </h4>
                            <p class="font-weight-normal text-muted">Total Orders</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class=".container-fluid mt-4 card mb-2 bg-light shadow" style=" margin: 0 3%">
            <div class="card-body">

                <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-process-tab" data-toggle="tab" href="#nav-process"
                            role="tab" aria-controls="nav-process" aria-selected="true">
                            To Process
                            <?php if ($totalStatusOne > 0): ?>
                            <span class="badge badge-primary rounded-circle p-2"><?php echo $totalStatusOne; ?></span>
                            <?php endif; ?>
                        </a>
                        <a class="nav-item nav-link" id="nav-ship-tab" data-toggle="tab" href="#nav-ship" role="tab"
                            aria-controls="nav-ship" aria-selected="false">
                            To Ship
                            <?php if ($totalStatusTwo > 0): ?>
                            <span class="badge badge-primary rounded-circle p-2"><?php echo $totalStatusTwo; ?></span>
                            <?php endif; ?>
                        </a>
                        <a class="nav-item nav-link" id="nav-received-tab" data-toggle="tab" href="#nav-received"
                            role="tab" aria-controls="nav-received" aria-selected="false">To Receive
                            <?php if ($totalStatus3 > 0): ?>
                            <span class="badge badge-primary rounded-circle p-2"><?php echo $totalStatus3; ?></span>
                            <?php endif; ?>
                        </a>

                        <a class="nav-item nav-link" id="nav-completed-tab" data-toggle="tab" href="#nav-completed"
                            role="tab" aria-controls="nav-completed" aria-selected="false">Completed
                            <?php if ($totalStatus4 > 0): ?>
                            <span class="badge badge-warning rounded-circle p-2"><?php echo $totalStatus4; ?></span>
                            <?php endif; ?>
                        </a>
                        <a class="nav-item nav-link" id="nav-cancelled-tab" data-toggle="tab" href="#nav-cancelled"
                            role="tab" aria-controls="nav-cancelled" aria-selected="false">Cancelled
                            <?php if ($totalStatus5 > 0): ?>
                            <span class="badge badge-danger rounded-circle p-2"><?php echo $totalStatus5; ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
                </nav>

                <div class="tab-content custom-tab-content" id="nav-tabContent">

                    <!-- to process -->
                    <div class="tab-pane fade show active" id="nav-process" role="tabpanel"
                        aria-labelledby="nav-process-tab">
                        <br>
                        <table id="dataTableprocess" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Customer's Name</th>
                                    <th>Address</th>
                                    <th>Date Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($combinedRows as $groupOrder => $data) { ?>
                                <?php if ($data['status'][0] == 1) { ?>
                                <tr>
                                    <td><?php echo $groupOrder; ?></td>
                                    <td>
                                        <?php foreach ($data['image'] as $index => $image) {
                                            echo '<img src="image/' . $image . '" width="40" height="50" alt="' . $data['product_name'][$index] . '" style="margin: 2px;">';
                                        }; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($data['product_name'] as $productName) {
                                            echo $productName . '<br>';
                                        } ?>
                                    </td>
                                    <td>
                                        <?php foreach ($data['product_price'] as $product_price) {
                                            echo '₱' . number_format($product_price, 2) . '<br>';
                                        } ?>
                                    </td>
                                    <td>
                                        <?php foreach ($data['product_quantity'] as $product_quantity) {
                                            echo 'x' . $product_quantity . '<br>';
                                        } ?>
                                    </td>
                                    <td><?php echo $data['order_username'][0]; ?></td>
                                    <td><?php echo $data['order_address'][0]; ?></td>
                                    <td><?php echo $data['date_added'][0]; ?></td>
                                    <td>
                                        <button class="btn-sm btn-success btn-block mb-2 viewBtn" data-toggle="modal"
                                            data-target="#groupModal" data-group-order="<?php echo $groupOrder; ?>"><i
                                                class="fas fa-eye"></i> View</button>
                                        <button class="btn-sm btn-warning btn-block mb-2 approveBtn"
                                            data-group-order="<?php echo $groupOrder; ?>"><i class="fas fa-cube"></i>
                                            Approve</button>
                                        <button class="btn-sm btn-danger btn-block mb-2 cancelBtn" data-toggle="modal"
                                            data-target="#groupModalCancel-<?php echo $groupOrder; ?>"
                                            data-group-order="<?php echo $groupOrder; ?>"><i
                                                class="fas fa-times-circle"></i>
                                            Cancel</button>
                                    </td>
                                </tr>
                                <!-- cancel modal -->
                                <div class="modal fade" id="groupModalCancel-<?php echo $groupOrder; ?>" tabindex="-1"
                                    role="dialog" aria-labelledby="groupModalLabel-<?php echo $groupOrder; ?>"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="groupModalLabel-<?php echo $groupOrder; ?>">
                                                    Order Details: <?php echo $groupOrder; ?></h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="orders_cancelled.php">
                                                    <input type="hidden" id="groupOrder" name="group_order"
                                                        value="<?php echo $groupOrder; ?>">
                                                    <div class="form-group">
                                                        <label for="reason_<?php echo $groupOrder; ?>">Reason:</label>
                                                        <input type="text" class="form-control"
                                                            id="reason_<?php echo $groupOrder; ?>" name="reason">
                                                    </div>
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        style="float: right;"
                                                        onclick="cancelOrder('<?php echo $groupOrder; ?>')">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>

                    <!-- to ship -->
                    <div class="tab-pane fade" id="nav-ship" role="tabpanel" aria-labelledby="nav-ship-tab">
                        <table id="dataTableship" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Customer's Name</th>
                                    <th>Address</th>
                                    <th>Date Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($combinedRows as $groupOrder => $data) { ?>
                                <?php if ($data['status'][0] == 2) { ?>
                                <tr>
                                    <td><?php echo $groupOrder; ?></td>
                                    <td>
                                        <?php foreach ($data['image'] as $index => $image) {
                                            echo '<img src="image/' . $image . '" width="40" height="50" alt="' . $data['product_name'][$index] . '" style="margin: 2px;">';
                                        }; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($data['product_name'] as $productName) {
                                            echo $productName . '<br>';
                                        } ?>
                                    </td>
                                    <td>
                                        <?php foreach ($data['product_price'] as $product_price) {
                                            echo '₱' . number_format($product_price, 2) . '<br>';
                                        } ?>
                                    </td>
                                    <td>
                                        <?php foreach ($data['product_quantity'] as $product_quantity) {
                                            echo 'x' . $product_quantity . '<br>';
                                        } ?>
                                    </td>
                                    <td><?php echo $data['order_username'][0]; ?></td>
                                    <td><?php echo $data['order_address'][0]; ?></td>
                                    <td><?php echo $data['date_added'][0]; ?></td>
                                    <td>
                                        <button class="btn-sm btn-success btn-block mb-2 viewBtn" data-toggle="modal"
                                            data-target="#groupModal" data-group-order="<?php echo $groupOrder; ?>"><i
                                                class="fas fa-eye"></i> View</button>
                                        <button class="btn-sm btn-warning btn-block mb-2 shipBtn" data-toggle="modal"
                                            data-target="#groupModalShip-<?php echo $groupOrder; ?>"
                                            data-group-order="<?php echo $groupOrder; ?>"><i class="fas fa-cube"></i>
                                            Ship</button>
                                    </td>
                                </tr>
                                <div class="modal fade" id="groupModalShip-<?php echo $groupOrder; ?>" tabindex="-1"
                                    role="dialog" aria-labelledby="groupModalLabel-<?php echo $groupOrder; ?>"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="groupModalLabel-<?php echo $groupOrder; ?>">
                                                    Order Details: <?php echo $groupOrder; ?></h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="orders_receive.php">
                                                    <input type="hidden" id="groupOrder" name="group_order"
                                                        value="<?php echo $groupOrder; ?>">
                                                    <div class="form-group">
                                                        <label for="trackingNumber-<?php echo $groupOrder; ?>">Tracking
                                                            Number:</label>
                                                        <input type="text" class="form-control"
                                                            id="trackingNumber_<?php echo $groupOrder; ?>"
                                                            name="trackingNumber">
                                                    </div>
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        style="float: right;"
                                                        onclick="shipOrder('<?php echo $groupOrder; ?>')">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>

                    <!-- to received -->
                    <div class="tab-pane fade" id="nav-received" role="tabpanel" aria-labelledby="nav-received-tab">
                        <table id="dataTablereceived" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">

                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Customer's Name</th>
                                    <th>Address</th>
                                    <th>Date Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($combinedRows as $groupOrder => $data) { ?>
                                <?php if ($data['status'][0] == 3) { ?>
                                <tr>
                                    <td><?php echo $groupOrder; ?></td>
                                    <td>
                                        <?php foreach ($data['image'] as $index => $image) {
                                            echo '<img src="image/' . $image . '" width="40" height="50" alt="' . $data['product_name'][$index] . '" style="margin: 2px;">';
                                        }; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($data['product_name'] as $productName) {
                                            echo $productName . '<br>';
                                        } ?>
                                    </td>
                                    <td>
                                        <?php foreach ($data['product_price'] as $product_price) {
                                            echo '₱' . number_format($product_price, 2) . '<br>';
                                        } ?>
                                    </td>
                                    <td>
                                        <?php foreach ($data['product_quantity'] as $product_quantity) {
                                            echo 'x' . $product_quantity . '<br>';
                                        } ?>
                                    </td>
                                    <td><?php echo $data['order_username'][0]; ?></td>
                                    <td><?php echo $data['order_address'][0]; ?></td>
                                    <td><?php echo $data['date_added'][0]; ?></td>
                                    <td>
                                        <button class="btn-sm btn-success btn-block mb-2 viewBtn" data-toggle="modal"
                                            data-target="#groupModal" data-group-order="<?php echo $groupOrder; ?>"><i
                                                class="fas fa-eye"></i> View</button>
                                        <button class="btn-sm btn-warning btn-block mb-2 completeBtn"
                                            data-group-order="<?php echo $groupOrder; ?>"><i class="fas fa-cube"></i>
                                            Complete</button>
                                    </td>
                                </tr>

                                <?php } ?>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>

                    <!-- to complete -->
                    <div class="tab-pane fade" id="nav-completed" role="tabpanel" aria-labelledby="nav-completed-tab">
                        <table id="dataTablecompleted" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">

                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Customer's Name</th>
                                    <th>Address</th>
                                    <th>Date Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($combinedRows as $groupOrder => $data) { ?>
                                <?php if ($data['status'][0] == 4) { ?>
                                <tr>
                                    <td><?php echo $groupOrder; ?></td>
                                    <td>
                                        <?php foreach ($data['image'] as $index => $image) {
                                            echo '<img src="image/' . $image . '" width="40" height="50" alt="' . $data['product_name'][$index] . '" style="margin: 2px;">';
                                        }; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($data['product_name'] as $productName) {
                                            echo $productName . '<br>';
                                        } ?>
                                    </td>
                                    <td>
                                        <?php foreach ($data['product_price'] as $product_price) {
                                            echo '₱' . number_format($product_price, 2) . '<br>';
                                        } ?>
                                    </td>
                                    <td>
                                        <?php foreach ($data['product_quantity'] as $product_quantity) {
                                            echo 'x' . $product_quantity . '<br>';
                                        } ?>
                                    </td>
                                    <td><?php echo $data['order_username'][0]; ?></td>
                                    <td><?php echo $data['order_address'][0]; ?></td>
                                    <td><?php echo $data['date_added'][0]; ?></td>
                                    <td>
                                        <button class="btn-sm btn-success btn-block mb-2 viewBtn" data-toggle="modal"
                                            data-target="#groupModal" data-group-order="<?php echo $groupOrder; ?>"><i
                                                class="fas fa-eye"></i> View</button>

                                    </td>
                                </tr>

                                <?php } ?>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>

                    <!-- to cancel -->
                    <div class="tab-pane fade" id="nav-cancelled" role="tabpanel" aria-labelledby="nav-cancelled-tab">
                        <table id="dataTablecancelled" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">

                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Customer's Name</th>
                                    <th>Address</th>
                                    <th>Date Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($combinedRows as $groupOrder => $data) { ?>
                                <?php if ($data['status'][0] == 5) { ?>
                                <tr>
                                    <td><?php echo $groupOrder; ?></td>
                                    <td>
                                        <?php foreach ($data['image'] as $index => $image) {
                                            echo '<img src="image/' . $image . '" width="40" height="50" alt="' . $data['product_name'][$index] . '" style="margin: 2px;">';
                                        }; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($data['product_name'] as $productName) {
                                            echo $productName . '<br>';
                                        } ?>
                                    </td>
                                    <td>
                                        <?php foreach ($data['product_price'] as $product_price) {
                                            echo '₱' . number_format($product_price, 2) . '<br>';
                                        } ?>
                                    </td>
                                    <td>
                                        <?php foreach ($data['product_quantity'] as $product_quantity) {
                                            echo 'x' . $product_quantity . '<br>';
                                        } ?>
                                    </td>
                                    <td><?php echo $data['order_username'][0]; ?></td>
                                    <td><?php echo $data['order_address'][0]; ?></td>
                                    <td><?php echo $data['date_added'][0]; ?></td>
                                    <td>
                                        <button class="btn-sm btn-success btn-block mb-2 viewBtn" data-toggle="modal"
                                            data-target="#groupModal" data-group-order="<?php echo $groupOrder; ?>"><i
                                                class="fas fa-eye"></i> View</button>
                                    </td>
                                </tr>

                                <?php } ?>
                                <?php } ?>

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap Modal -->
    <div class="modal fade" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="groupModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="groupModalLabel">Order Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="groupModalBody">
                    <!-- Order details will be displayed here -->
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        $('.viewBtn').click(function() {
            var groupOrder = $(this).data('group-order');
            var modalBody = $('#groupModalBody');
            modalBody.empty();
            modalBody.append('<p><strong>Order ID:</strong> ' + groupOrder + '</p>');

            // Populate modal with other details
            <?php foreach ($combinedRows as $groupOrder => $data): ?>


            if (groupOrder == <?php echo json_encode($groupOrder); ?>) {
                if (<?php echo $data['status'][0]; ?> == 3 || <?php echo $data['status'][0]; ?> == 4) {
                    modalBody.append(
                        '<div class="customer-tracking">' +
                        '<p><strong> J&T Tracking Number:</strong> <?php echo $data['trackingNumber'][0]; ?></p>' +
                        '</div>'
                    );
                }
                if (<?php echo $data['status'][0]; ?> == 5) {
                    modalBody.append(
                        '<div class="customer-reason">' +
                        '<p><strong> CANCELLED!</strong></p>' +
                        '<p><strong> Reason:</strong> <?php echo $data['reason'][0]; ?></p>' +
                        '</div>'
                    );
                }
                modalBody.append(
                    '<div class="customer-details">' +
                    '<p><strong>Customer\'s Name:</strong> <?php echo $data['order_username'][0]; ?></p>' +
                    '<p><strong>Mobile Number:</strong> <?php echo $data['order_phonenumber'][0]; ?></p>' +
                    '<p><strong>Address:</strong> <?php echo $data['order_address'][0]; ?></p>' +
                    '</div>'
                );
                modalBody.append(
                    '<br><p><strong>Product Order:</strong></p><hr>'
                );
                modalBody.append(
                    '<table style="border-collapse: collapse; width: 100%; border: 1px solid #ddd;">'
                );
                <?php foreach ($data['image'] as $index => $image): ?>
                modalBody.append('<tr style="border: 1px solid #eee;">');
                modalBody.append(
                    '<td style="padding: 8px;"><img src="image/<?php echo $image; ?>" width="40" height="50" alt="<?php echo $data['product_name'][$index]; ?>"></td>'
                );
                modalBody.append(
                    '<td style="padding: 8px; width: 60%;"><?php echo $data['product_name'][$index]; ?></td>'
                );
                modalBody.append(
                    '<td style="padding: 8px; width: 80%; text-align: center;"><?php echo $data['product_quantity'][$index]; ?>x</td>'
                );
                modalBody.append(
                    '<td style="padding: 8px; width: 100%; text-align: center;">₱<?php echo number_format($data['product_price'][$index], 2); ?></td>'
                );
                modalBody.append('</tr>');
                <?php endforeach; ?>
                modalBody.append('</table>');

                modalBody.append(
                    '<hr><p style="text-align: right; font-size: 20px;">Total:<strong> ₱ <?php  echo number_format($data['grandtotal'][0], 2); ?></p> </strong>'
                );
            }
            <?php endforeach; ?>
        });
    });
    </script>
</body>
<script>
function cancelOrder(groupId) {
    console.log('Item ID:', groupId);
    $.ajax({
        type: 'POST',
        url: 'orders_cancelled.php',
        data: {
            groupId: groupId,
            reason: $('#reason_' + groupId).val(),
        },
        success: function(response) {
            console.log('AJAX Success:', response);
            if (response.trim() === 'success') {
                alert('Order cancelled successfully!');
                sendCancelEmail(groupId);
            } else {
                alert('Failed to cancelled the order. Please try again.');
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
        }
    });
}
function sendCancelEmail(groupId) {
    $.ajax({
        type: 'POST',
        url: 'send_order_cancel.php',
        data: {
            groupId: groupId
        },
        success: function(response) {
            console.log('Email sent:', response);
            alert('to Recieve email has been sent!');
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Failed to send email:', status, error);
            alert('Failed to send to recieve email. Please try again.');
        }
    });
}
</script>
<script>
$(document).ready(function() {
    $('.cancelBtn').click(function() {
        $('#groupModalShip').modal('show'); // Show the modal
    });
});
</script>

<script>
$(document).ready(function() {
    // Function to handle shipping an order
    function shipOrder(groupId) {
        $.ajax({
            type: 'POST',
            url: 'orders_completed.php',
            data: {
                groupId: groupId
            },
            success: function(response) {
                console.log('AJAX Success:', response);
                if (response.trim() === 'success') {
                    alert('The order has been marked as completed successfully!');
                    window.location.reload(); // Refresh the page
                } else {
                    alert('Failed to mark the order as completed. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                alert('An error occurred while processing your request. Please try again.');
            }
        });
    }

    // Event listener for ship button
    $(document).on('click', '.completeBtn', function() {
        var groupId = $(this).data('group-order');
        if (confirm("Are you sure you want to mark this order as completed?")) {
            shipOrder(groupId);
            sendCompleteEmail(groupId);
        }
    });
});
function sendCompleteEmail(groupId) {
    $.ajax({
        type: 'POST',
        url: 'send_order_complete.php',
        data: {
            groupId: groupId
        },
        success: function(response) {
            console.log('Email sent:', response);
            alert('Completed email has been sent!');
        },
        error: function(xhr, status, error) {
            console.error('Failed to send email:', status, error);
            alert('Failed to send completed email. Please try again.');
        }
    });
}
</script>
<script>
$(document).ready(function() {
    // Function to handle shipping an order
    function shipOrder(groupId) {
        $.ajax({
            type: 'POST',
            url: 'orders_ship.php',
            data: {
                groupId: groupId
            },
            success: function(response) {
                console.log('AJAX Success:', response);
                if (response.trim() === 'success') {
                    alert('The order has been marked as shipped successfully!');
                    window.location.reload(); // Refresh the page
                } else {
                    alert('Failed to mark the order as shipped. Please try again.');
                    
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                alert('An error occurred while processing your request. Please try again.');
            }
        });
    }

    // Event listener for ship button
    $(document).on('click', '.approveBtn', function() {
        var groupId = $(this).data('group-order');
        if (confirm("Are you sure you want to mark this order as shipped?")) {
            shipOrder(groupId);
            sendApprovalEmail(groupId);
        }
    });
});
function sendApprovalEmail(groupId) {
    $.ajax({
        type: 'POST',
        url: 'send_order_approve.php',
        data: {
            groupId: groupId
        },
        success: function(response) {
            console.log('Email sent:', response);
            alert('Approval email has been sent!');
        },
        error: function(xhr, status, error) {
            console.error('Failed to send email:', status, error);
            alert('Failed to send approval email. Please try again.');
        }
    });
}
</script>


<script>
$(document).ready(function() {
    // Initialize DataTables
    $('#dataTableprocess').DataTable();
    $('#dataTableship').DataTable();
    $('#dataTablereceived').DataTable();
    $('#dataTablecompleted').DataTable();
    $('#dataTablecancelled').DataTable();

    // Show modal form for adding new data
    $('#addBtn').click(function() {
        $('#modalTitle').text('Add Product');
        $('#dataForm')[0].reset();
        $('#dataModal').modal('show');
    });

    // Save data
    $('#saveBtn').click(function() {
        // Perform your save operation here
        // ...

        $('#dataModal').modal('hide');
    });

    // Edit data
    $(document).on('click', '.editBtn', function() {
        $('#modalTitle').text('Edit Data');
        var data = $(this).data('info');
        // Populate the form fields with data
        $('#id').val(data.id);
        $('#name').val(data.name);
        $('#email').val(data.email);
        $('#dataModal').modal('show');
    });

    // Delete data
    $(document).on('click', '.deleteBtn', function() {
        var data = $(this).data('info');
        // Perform your delete operation here
        // ...
    });
});
</script>
<script>
function shipOrder(groupId) {
    console.log('Item ID:', groupId);
    $.ajax({
        type: 'POST',
        url: 'orders_receive.php',
        data: {
            groupId: groupId,
            trackingNumber: $('#trackingNumber_' + groupId).val(),
        },
        success: function(response) {
            console.log('AJAX Success:', response);
            if (response.trim() === 'success') {
                alert('Order shipped successfully!');
                sendRecieveEmail(groupId);
            } else {
                alert('Failed to ship the order. Please try again.');
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
        }
    });
}
function sendRecieveEmail(groupId) {
    $.ajax({
        type: 'POST',
        url: 'send_order_recieve.php',
        data: {
            groupId: groupId
        },
        success: function(response) {
            console.log('Email sent:', response);
            alert('to Recieve email has been sent!');
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Failed to send email:', status, error);
            alert('Failed to send to recieve email. Please try again.');
        }
    });
}
</script>
<script>
$(document).ready(function() {
    $('.shipBtn').click(function() {
        $('#groupModalShip').modal('show'); // Show the modal
    });
});
</script>


</html>
<style>
.customer-details {
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 20px;
}

.customer-reason {
    background-color: rgb(254 242 242);
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 20px;
    color: #F87171;
}

.customer-tracking {
    background-color: rgb(240 253 244);
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 20px;
    color: rgb(22 101 52);
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
</style>