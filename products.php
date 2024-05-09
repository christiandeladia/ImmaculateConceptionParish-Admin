<?php
require "process/connect.php";
if (!isset($_SESSION['auth_admin'])) {
    header("location: index.php");
    exit;
}
?>
<?php include 'process/formula.php';?>
<?php
  	require_once "process/connect.php";
    $is_admin_logged_in = isset($_SESSION['auth_admin']);
    if ( isset($_SESSION['auth_admin']) ) {
    function getProductsum() {
        global $pdo;
        $sql = "SELECT SUM(product_stock) AS total_stock FROM `inventory`;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['total_stock'];
    }
    $total_stock = getProductsum();
    
    function getInventory() {
        global $pdo;
        $query = "SELECT *, DATE_FORMAT(date_added, '%d/%m/%Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM inventory";
        $inventory = [];
        $statement = $pdo->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    $inventory = getInventory();

    function getOutOfStockCount()
    {
        global $pdo;
        $sql = "SELECT COUNT(*) AS out_of_stock_count FROM `inventory` WHERE status = 'Out of Stock'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['out_of_stock_count'];
    }
    $out_of_stock_count = getOutOfStockCount();
    
    function getUnlistedCount()
    {
        global $pdo;
        $sql = "SELECT COUNT(*) AS unlisted_count FROM `inventory` WHERE status = 'Unlisted'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['unlisted_count'];
    }
    $unlisted_count = getUnlistedCount();
    function getAvailableCount()
    {
        global $pdo;
        $sql = "SELECT COUNT(*) AS available_count FROM `inventory` WHERE status = 'Available'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['available_count'];
    }
    $available_count = getAvailableCount();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image/admin.ico">
    <title>Products | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>

</head>
<script>
$(document).ready(function() {
    // Initialize DataTables
    $('#alldataTable').DataTable();
    $('#outofstockdataTable').DataTable();
    $('#unlistdataTable').DataTable();
    $('#removedataTable').DataTable();

    // Show modal form for adding new data
    $('#addBtn').click(function() {
        $('#modalTitle').text('Add Product');
        $('#dataForm')[0].reset();
        $('#dataModal').modal('show');
    });

    $('#addCoupon').click(function() {
        $('#CouponmodalTitle').text('Add Coupon');
        $('#dataForm')[0].reset();
        $('#CoupondataModal').modal('show');
    });

    // Show modal form for edit data
    $('#editBtn').click(function() {
        $('#editmodalTitle').text('Edit Product');
        $('#dataForm')[0].reset();
        $('#editdataModal').modal('show');
    });


    // Save data
    $('#saveBtn').click(function() {
        // Perform your save operation here
        // ...

        $('#dataModal').modal('hide');
    });


    // Delete data
    $(document).on('click', '.deleteBtn', function() {
        var data = $(this).data('info');
        // Perform your delete operation here
        // ...
    });
});
</script>

<body>
    <?php 
    $activePage = 'products'; 
    include 'nav.php';
    ?>
    <div></div>
    <div class="product">
        <div class=".container-fluid mt-4 card mb-2 bg-light shadow" style=" margin: 0 3%">
            <div class="card-body">
                <section class="p-1 z-depth-1">
                    <h3 class="text-center font-weight-bold mb-4">PRODUCTS</h3>
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-6 col-lg-3 text-center">
                            <h4 class="h2 font-weight-normal mb-1">
                                <i class="fas fa-tags text-success"></i>
                                <span class="d-inline-block count-up" data-from="0" data-to="100"
                                    data-time="2000"><?php echo $total_product; ?></span>
                            </h4>
                            <p class="font-weight-normal text-muted">Total Products</p>
                        </div>
                        <div class="col-md-6 col-lg-3 text-center">
                            <h4 class="h2 font-weight-normal mb-1">
                                <i class="fas fa-archive text-info"></i>
                                <span class="d-inline-block count1" data-from="0" data-to="250"
                                    data-time="2000"><?php echo $total_stock; ?></span>
                            </h4>
                            <p class="font-weight-normal text-muted">Total Stocks</p>
                        </div>

                        <div class="col-md-6 col-lg-3 text-center">
                            <h4 class="h2 font-weight-normal mb-1">
                                <i class="fas fa-eye-slash text-warning"></i>
                                <span class="d-inline-block count2" data-from="0" data-to="330"
                                    data-time="2000"><?php echo $unlisted_count; ?></span>
                            </h4>
                            <p class="font-weight-normal text-muted">Unlisted</p>
                        </div>
                        <div class="col-md-6 col-lg-3 text-center">
                            <h4 class="h2 font-weight-normal mb-1">
                                <i class="fas fa-ban text-danger"></i>
                                <span class="d-inline-block count3" data-from="0" data-to="430"
                                    data-time="2000"><?php echo $out_of_stock_count; ?></span>
                            </h4>
                            <p class="font-weight-normal text-muted">Out of Stock</p>
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
                            All
                            <?php if ($total_product > 0): ?> <span
                                class="badge badge-info rounded-circle p-2"><?php echo $total_product; ?></span>
                            <?php endif; ?>
                        </a>
                        <a class="nav-item nav-link" id="nav-available-tab" data-toggle="tab" href="#nav-available"
                            role="tab" aria-controls="nav-available" aria-selected="false">Available
                            <?php if ($available_count > 0): ?>
                            <span class="badge badge-success rounded-circle p-2"><?php echo $available_count; ?></span>
                            <?php endif; ?>
                        </a>
                        <a class="nav-item nav-link" id="nav-outofstock-tab" data-toggle="tab" href="#nav-outofstock"
                            role="tab" aria-controls="nav-outofstock" aria-selected="false">
                            Out Of Stock
                            <?php if ($out_of_stock_count > 0): ?>
                            <span
                                class="badge badge-danger rounded-circle p-2"><?php echo $out_of_stock_count; ?></span>
                            <?php endif; ?>
                        </a>
                        <a class="nav-item nav-link" id="nav-unlisted-tab" data-toggle="tab" href="#nav-unlisted"
                            role="tab" aria-controls="nav-unlisted" aria-selected="false">
                            Unlisted
                            <?php if ($unlisted_count > 0): ?>
                            <span class="badge badge-warning rounded-circle p-2"><?php echo $unlisted_count; ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
                </nav>
                <div class="tab-content custom-tab-content" id="nav-tabContent">
                    <!-- ALL -->
                    <div class="tab-pane fade show active" id="nav-process" role="tabpanel"
                        aria-labelledby="nav-process-tab">
                        <!-- Add button -->
                        <button type="button" class="btn btn-primary mb-2" id="addBtn"><i class="fas fa-plus"></i>
                            Add</button>
                        <br><br>
                        <div id="modalContainer"></div>
                        <!-- <script src="update.js"></script> -->
                        <table id="alldataTable" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>image</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Dimension</th>
                                    <th>Stock</th>
                                    <th>Date Added</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inventory as $item) { ?>
                                <tr>
                                    <td class="text-center align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <p class="fw-bold mb-1"><?php echo $item['product_id']; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <img src="image/<?php echo $item['product_image']; ?>" width="60" height="80"
                                            alt="<?php echo $item['product_name']; ?>">
                                    </td>
                                    <td class="text-center align-middle">
                                        <p class="fw-bold fw-normal mb-1"><?php echo $item['product_name']; ?></p>
                                    </td>
                                    <td class="text-center align-middle"><?php echo $item['product_description']; ?>
                                    </td>
                                    <td class="text-center align-middle">₱
                                        <?php echo number_format($item['product_price'], 2); ?></td>
                                    <td class="text-center align-middle"><?php echo $item['product_dimension']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['product_stock']; ?></td>
                                    <td class="text-center align-middle">
                                        <div class="">
                                            <span class=""><?php echo $item['date_component']; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $item['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span
                                            class="badge badge-<?php echo $item['status'] == 'Available' ? 'success' : ($item['status'] == 'Out of Stock' ? 'danger' : 'warning'); ?> rounded-pill d-inline"
                                            id="status_<?php echo $item['product_id']; ?>">
                                            <?php echo $item['status']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn-sm btn-info btn-block mb-2" data-toggle="modal"
                                            data-target="#view_<?php echo $item['product_id']; ?>">
                                            <i class="fas fa-pencil-alt"></i> Update
                                        </button>
                                        <?php if ($item['status'] == 'Available'): ?>
                                        <button class="btn-sm btn-warning btn-block"
                                            onclick="unlistProduct(<?php echo $item['product_id']; ?>)">
                                            <i class="fas fa-eye-slash"></i> Unlist
                                        </button>
                                        <?php elseif ($item['status'] == 'Out of Stock'): ?>
                                        <button class="btn-sm btn-warning btn-block"
                                            onclick="unlistProduct(<?php echo $item['product_id']; ?>)">
                                            <i class="fas fa-eye-slash"></i> Unlist
                                        </button>
                                        <?php else: ?>
                                        <button class="btn-sm btn-success btn-block"
                                            onclick="listProduct(<?php echo $item['product_id']; ?>)">
                                            <i class="fas fa-eye"></i> List
                                        </button>
                                        <?php endif; ?>
                                        <button class="btn-sm btn-danger btn-block"
                                            onclick="deleteProduct(<?php echo $item['product_id']; ?>)">
                                            <i class="fa fa-trash"></i> Remove
                                        </button>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- AVAILABLE -->
                    <div class="tab-pane fade" id="nav-available" role="tabpanel" aria-labelledby="nav-available-tab">
                        <table id="availabledataTable" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Dimension</th>
                                    <th>Stock</th>
                                    <th>Date Added</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inventory as $item) { ?>
                                <?php if ($item['status'] == 'Available') { ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $item['product_id']; ?></td>
                                    <td class="text-center align-middle"><img
                                            src="image/<?php echo $item['product_image']; ?>" width="60" height="80"
                                            alt="<?php echo $item['product_name']; ?>"></td>
                                    <td class="text-center align-middle"><?php echo $item['product_name']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['product_description']; ?>
                                    </td>
                                    <td class="text-center align-middle">₱
                                        <?php echo number_format($item['product_price'], 2); ?></td>
                                    <td class="text-center align-middle"><?php echo $item['product_dimension']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['product_stock']; ?></td>
                                    <td class="text-center align-middle">
                                        <?php echo $item['date_component'] . ' ' . $item['time_component']; ?></td>
                                    <td class="text-center align-middle">
                                        <span
                                            class="badge badge-<?php echo $item['status'] == 'Available' ? 'success' : ($item['status'] == 'Out of Stock' ? 'danger' : 'warning'); ?> rounded-pill d-inline"
                                            id="status_<?php echo $item['product_id']; ?>">
                                            <?php echo $item['status']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn-sm btn-info btn-block mb-2" data-toggle="modal"
                                            data-target="#view_<?php echo $item['product_id']; ?>">
                                            <i class="fas fa-pencil-alt"></i> Update
                                        </button>

                                        <?php if ($item['status'] == 'Available'): ?>
                                        <button class="btn-sm btn-warning btn-block"
                                            onclick="unlistProduct(<?php echo $item['product_id']; ?>)">
                                            <i class="fas fa-eye-slash"></i> Unlist
                                        </button>
                                        <?php elseif ($item['status'] == 'Out of Stock'): ?>
                                        <button class="btn-sm btn-warning btn-block"
                                            onclick="unlistProduct(<?php echo $item['product_id']; ?>)">
                                            <i class="fas fa-eye-slash"></i> Unlist
                                        </button>
                                        <?php else: ?>
                                        <button class="btn-sm btn-success btn-block"
                                            onclick="listProduct(<?php echo $item['product_id']; ?>)">
                                            <i class="fas fa-eye"></i> List
                                        </button>
                                        <?php endif; ?>
                                        <button class="btn-sm btn-danger btn-block"
                                            onclick="deleteProduct(<?php echo $item['product_id']; ?>)">
                                            <i class="fa fa-trash"></i> Remove
                                        </button>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- OUT OF STOCK -->
                    <div class="tab-pane fade" id="nav-outofstock" role="tabpanel" aria-labelledby="nav-outofstock-tab">
                        <table id="outofstockdataTable" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Dimension</th>
                                    <th>Stock</th>
                                    <th>Date Added</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inventory as $item) { ?>
                                <?php if ($item['status'] == 'Out of Stock') { ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $item['product_id']; ?></td>
                                    <td class="text-center align-middle"><img
                                            src="image/<?php echo $item['product_image']; ?>" width="60" height="80"
                                            alt="<?php echo $item['product_name']; ?>"></td>
                                    <td class="text-center align-middle"><?php echo $item['product_name']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['product_description']; ?>
                                    </td>
                                    <td class="text-center align-middle">₱
                                        <?php echo number_format($item['product_price'], 2); ?></td>
                                    <td class="text-center align-middle"><?php echo $item['product_dimension']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['product_stock']; ?></td>
                                    <td class="text-center align-middle">
                                        <?php echo $item['date_component'] . ' ' . $item['time_component']; ?></td>
                                    <td class="text-center align-middle">
                                        <span
                                            class="badge badge-<?php echo $item['status'] == 'Available' ? 'success' : ($item['status'] == 'Out of Stock' ? 'danger' : 'warning'); ?> rounded-pill d-inline"
                                            id="status_<?php echo $item['product_id']; ?>">
                                            <?php echo $item['status']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn-sm btn-info btn-block mb-2" data-toggle="modal"
                                            data-target="#view_<?php echo $item['product_id']; ?>">
                                            <i class="fas fa-pencil-alt"></i> Update
                                        </button>
                                        <?php if ($item['status'] == 'Available'): ?>
                                        <button class="btn-sm btn-warning btn-block"
                                            onclick="unlistProduct(<?php echo $item['product_id']; ?>)">
                                            <i class="fas fa-eye-slash"></i> Unlist
                                        </button>
                                        <?php elseif ($item['status'] == 'Out of Stock'): ?>
                                        <button class="btn-sm btn-warning btn-block"
                                            onclick="unlistProduct(<?php echo $item['product_id']; ?>)">
                                            <i class="fas fa-eye-slash"></i> Unlist
                                        </button>
                                        <?php else: ?>
                                        <button class="btn-sm btn-success btn-block"
                                            onclick="listProduct(<?php echo $item['product_id']; ?>)">
                                            <i class="fas fa-eye"></i> List
                                        </button>
                                        <?php endif; ?>
                                        <button class="btn-sm btn-danger btn-block"
                                            onclick="deleteProduct(<?php echo $item['product_id']; ?>)">
                                            <i class="fa fa-trash"></i> Remove
                                        </button>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- UNLISTED -->
                    <div class="tab-pane fade" id="nav-unlisted" role="tabpanel" aria-labelledby="nav-unlisted-tab">
                        <table id="unlistdataTable" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Dimension</th>
                                    <th>Stock</th>
                                    <th>Date Added</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inventory as $item) { ?>
                                <?php if ($item['status'] == 'Unlisted') { ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $item['product_id']; ?></td>
                                    <td class="text-center align-middle"><img
                                            src="image/<?php echo $item['product_image']; ?>" width="60" height="80"
                                            alt="<?php echo $item['product_name']; ?>"></td>
                                    <td class="text-center align-middle"><?php echo $item['product_name']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['product_description']; ?>
                                    </td>
                                    <td class="text-center align-middle">₱
                                        <?php echo number_format($item['product_price'], 2); ?></td>
                                    <td class="text-center align-middle"><?php echo $item['product_dimension']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['product_stock']; ?></td>
                                    <td class="text-center align-middle">
                                        <?php echo $item['date_component'] . ' ' . $item['time_component']; ?></td>
                                    <td class="text-center align-middle">
                                        <span
                                            class="badge badge-<?php echo $item['status'] == 'Available' ? 'success' : ($item['status'] == 'Out of Stock' ? 'danger' : 'warning'); ?> rounded-pill d-inline"
                                            id="status_<?php echo $item['product_id']; ?>">
                                            <?php echo $item['status']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn-sm btn-info btn-block mb-2" data-toggle="modal"
                                            data-target="#view_<?php echo $item['product_id']; ?>">
                                            <i class="fas fa-pencil-alt"></i> Update
                                        </button>
                                        <?php if ($item['status'] == 'Available'): ?>
                                        <button class="btn-sm btn-warning btn-block"
                                            onclick="unlistProduct(<?php echo $item['product_id']; ?>)">
                                            <i class="fas fa-eye-slash"></i> Unlist
                                        </button>
                                        <?php elseif ($item['status'] == 'Out of Stock'): ?>
                                        <button class="btn-sm btn-warning btn-block"
                                            onclick="unlistProduct(<?php echo $item['product_id']; ?>)">
                                            <i class="fas fa-eye-slash"></i> Unlist
                                        </button>
                                        <?php else: ?>
                                        <button class="btn-sm btn-success btn-block"
                                            onclick="listProduct(<?php echo $item['product_id']; ?>)">
                                            <i class="fas fa-eye"></i> List
                                        </button>

                                        <?php endif; ?>
                                        <button class="btn-sm btn-danger btn-block"
                                            onclick="deleteProduct(<?php echo $item['product_id']; ?>)"> <i
                                                class="fa fa-trash"></i> Remove
                                        </button>
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

    <!-- ADD Modal form -->
    <div id="dataModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTitle"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="product/add.php" id="dataForm" method="post">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label for="product_name">Product Name:</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                        </div>
                        <div class="form-group">
                            <label for="product_price">Price:</label>
                            <input type="number" class="form-control" id="product_price" name="product_price" required>
                        </div>
                        <div class="form-group">
                            <label for="product_description">Description:</label>
                            <textarea class="form-control" id="product_description" name="product_description"
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="product_dimension">Dimension:</label>
                            <input type="text" class="form-control" id="product_dimension" name="product_dimension"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="product_stock">Stock:</label>
                            <input type="number" class="form-control" id="product_stock" name="product_stock" required>
                        </div>
                        <div class="form-group">
                            <label for="product_image">Image:</label>
                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right"
                                title="Choose an image file: (JPEG, PNG, GIF)"></i>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="product_image" name="product_image"
                                    accept="image/*" required>
                                <label class="custom-file-label" id="fileLabel" for="product_image">Choose file</label>
                            </div>
                            <small class="form-text text-muted">(image size must be 1290 x 1454)</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveBtn"><i class="fas fa-save"></i> Save</button>
                </div>
            </div>
        </div>
    </div>


    <script>
    // Function to open the add modal
    function openAddModal() {
        $('#addModal').modal('show');
    }

    // Function to add a new product
    function addProduct() {
        // Retrieve form data
        var formData = new FormData($('#addForm')[0]);

        $.ajax({
            url: 'product/add.php',
            method: 'POST',
            data: {
                product_name: $('#product_name').val(),
                product_price: $('#product_price').val(),
                product_dimension: $('#product_dimension').val(),
                product_description: $('#product_description').val(),
                product_stock: $('#product_stock').val(),
                // product_image: $('#product_image').val()
            },
            success: function(response) {
                window.location.href = 'product/add.php';
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    </script>


    <!-- COUPON Modal form -->
    <div id="CoupondataModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="CouponmodalTitle">Add Coupon</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="product/addCoupon.php" id="dataForm" method="post">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label for="coupon_code">Coupon Code:</label>
                            <input type="text" class="form-control" id="coupon_code" name="coupon_code" required>
                        </div>
                        <div class="form-group">
                            <label for="coupon_amount">Coupon Amount:</label>
                            <input type="number" class="form-control" id="coupon_amount" name="coupon_amount" required>
                        </div>
                        <div class="form-group">
                            <label for="usage_quantity">Usage Quantity:</label>
                            <input type="number" class="form-control" id="usage_quantity" name="usage_quantity"
                                required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveCoupoBtn"><i class="fas fa-save"></i> Save</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    // Function to open the add modal
    function openAddCouponModal() {
        $('#addCouponModal').modal('show');
    }

    // Function to add a new product
    function addProduct() {
        // Retrieve form data
        var formData = new FormData($('#addCouponForm')[0]);

        $.ajax({
            url: 'product/addCoupon.php',
            method: 'POST',
            data: {
                coupon_code = $('#view_' + coupon_id + ' #coupon_code').val();
                coupon_amount = $('#view_' + coupon_id + ' #coupon_amount').val();
                usage_quantity = $('#view_' + coupon_id + ' #usage_quantity').val();
            },
            success: function(response) {
                window.location.href = 'product/addCoupon.php';
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    </script>
    

    <!-- EDIT Modal form -->
    <?php foreach ($inventory as $item) { ?>
    <div id="view_<?php echo $item['product_id']; ?>" class="modal fade">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editmodalTitle">Edit Product</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="product/update.php" id="dataForm" method="post">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label for="product_name">Product Name:</label>
                            <input type="text" class="form-control" id="product_name" name="product_name"
                                value="<?php echo $item['product_name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="product_price">Price:</label>
                            <input type="number" class="form-control" id="product_price" name="product_price"
                                value="<?php echo $item['product_price']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="product_description">Description:</label>
                            <textarea class="form-control" id="product_description" name="product_description" value=""
                                required><?php echo $item['product_description']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="product_dimension">Dimension:</label>
                            <input type="text" class="form-control" id="product_dimension" name="product_dimension"
                                value="<?php echo $item['product_dimension']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="product_stock">Stock:</label>
                            <input type="number" class="form-control" id="product_stock" name="product_stock"
                                value="<?php echo $item['product_stock']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="product_image">Image:</label>
                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right"
                                title="Choose an image file: (JPEG, PNG, GIF)"></i>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="product_image" name="product_image"
                                    accept="image/*">
                                <label class="custom-file-label" id="fileLabel" for="product_image">
                                    <?php echo $item['product_image']; ?>
                                </label>
                            </div>
                            <small class="form-text text-muted">(image size must be 1290 x 1454)</small>
                            <input type="hidden" name="existing_image" value="<?php echo $item['product_image']; ?>">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success"
                        onclick="updateProduct(<?php echo $item['product_id']; ?>)">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <script>
    // Define the updateProduct function to handle saving form data
    function updateProduct(productId) {
        // Get other form data
        var productName = $('#view_' + productId + ' #product_name').val();
        var productPrice = $('#view_' + productId + ' #product_price').val();
        var productDescription = $('#view_' + productId + ' #product_description').val();
        var productDimension = $('#view_' + productId + ' #product_dimension').val();
        var productStock = $('#view_' + productId + ' #product_stock').val();

        // Send AJAX request to update.php
        $.ajax({
            url: 'product/update.php',
            method: 'POST',
            data: {
                product_id: productId,
                product_name: productName,
                product_price: productPrice,
                product_description: productDescription,
                product_dimension: productDimension,
                product_stock: productStock
            },
            success: function(response) {
                location.reload(); // Reload the page after successful update
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    </script>
</body>

<script>
// to run the save button outside the form
document.getElementById('saveCouponBtn').addEventListener('click', function() {
    document.getElementById('dataForm').submit();
});
</script>
<script>
// to run the save button outside the form
document.getElementById('saveCouponBtn').addEventListener('click', function() {
    document.getElementById('dataForm').submit();
});
</script>
<script>
// to display selected file name in the file input
document.getElementById('product_image').addEventListener('change', function(e) {
    var fileName = e.target.files[0].name;
    document.getElementById('fileLabel').innerText = fileName;
});
</script>

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
<script>
function deleteProduct(productId) {
    if (confirm("Are you sure you want to delete this product?")) {
        $.ajax({
            url: 'product/delete.php',
            method: 'GET',
            data: {
                product_id: productId
            },
            success: function() {
                window.location.href = 'product/delete.php';
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
}
</script>
<script>
function unlistProduct(productId) {
    if (confirm("Are you sure you want to unlist this product?")) {
        $.ajax({
            url: 'product/unlist.php', // Update with the correct endpoint
            method: 'POST',
            data: {
                product_id: productId
            },
            success: function(response) {
                location.reload();
                $('#status_' + productId).text('Unlisted');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
}
</script>
<script>
function listProduct(productId) {
    if (confirm("Are you sure you want to list this product?")) {
        $.ajax({
            url: 'product/list.php', // Update with the correct endpoint
            method: 'POST',
            data: {
                product_id: productId
            },
            success: function(response) {
                location.reload();
                $('#status_' + productId).text('Available');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
}
</script>
<script>
$(document).ready(function() {
    $('#saveEditBtn').click(function() {
        var productId = $('#editProductId').val();
        var productName = $('#editProductName').val();
        var productPrice = $('#editProductPrice').val();
        var productDescription = $('#editProductDescription').val();
        var productDimension = $('#editProductDimension').val();
        var productStock = $('#editProductStock').val();
        $.ajax({
            url: 'product/update.php',
            method: 'POST',
            data: {
                product_id: productId,
                product_name: productName,
                product_price: productPrice,
                product_description: productDescription,
                product_dimension: productDimension,
                product_stock: productStock
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});
</script>
<script>
$(document).ready(function() {
    $('#alldataTable').DataTable();
    $('#outofstockdataTable').DataTable();
    $('#unlistdataTable').DataTable();
    $('#availabledataTable').DataTable();

    $(document).on('click', '.deleteBtn', function() {
        var data = $(this).data('info');
    });
});
</script>



</html>