<?php
require "process/connect.php";
if (!isset($_SESSION['auth_admin'])) {
    header("location: index.php");
    exit;
}
?>


<?php
    if ( isset($_SESSION['auth_login']) ) {
		$auth = $_SESSION['auth_login'];
        $auth_full_name = $auth['first_name'] . $auth['last_name'];
}
$result = mysqli_query($conn, "SELECT * FROM wedding_banns ORDER BY id DESC LIMIT 1");

if (!$result) {
    die("Error in query: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
?>

<?php include 'process/formula.php';?>

<?php

function geWeddingBannsTotal() {
    global $pdo; 
    $sql = "SELECT COUNT(*) as total_wedding_banns FROM `wedding_banns`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['total_wedding_banns'];
}
$total_wedding_banns = geWeddingBannsTotal();

function getInventory($status = 'ongoing') {
    global $pdo;
    $sql = "SELECT *, DATE_FORMAT(date_added, '%d/%m/%Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM wedding_banns WHERE status = :status";
    $wedding_banns = [];
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':status', $status, PDO::PARAM_STR);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// For ongoing administrators
$wedding_banns = getInventory('ongoing');

// For ended administrators
$ended_wedding_banns = getInventory('ended');

function getongoingAdminCount() {
    global $pdo; 
    $sql = "SELECT COUNT(*) as total_ongoing_wedding_banns FROM `wedding_banns` WHERE status = 'ongoing'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['total_ongoing_wedding_banns'];
}

function getendedAdminCount() {
    global $pdo; 
    $sql = "SELECT COUNT(*) as total_ended_wedding_banns FROM `wedding_banns` WHERE status = 'ended'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['total_ended_wedding_banns'];
}

$total_ongoing_wedding_banns = getongoingAdminCount();
$total_ended_wedding_banns = getendedAdminCount();

?>


<?php 
    function getWeddingBannsData() {
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%d/%m/%Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM wedding_banns";
    $decline = [];
    $reference_id = uniqid();
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    $wedding_banns = getWeddingBannsData();
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image/admin.ico">
    <title>Wedding Banns | Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>


    <script>
    $(document).ready(function() {
        // Initialize DataTables
        // $('#dataTable').DataTable();
        $('#dataTableAll').DataTable();
        $('#dataTableRemove').DataTable();

        // Show modal form for adding new data
        $('#addmodalbutton').click(function() {
            $('#modalTitle').text('Publish New Wedding Banns');
            $('#admin_dataForm')[0].reset();
            $('#weddingbannsModal').modal('show');
        });

        // Save data
        $('#add_adminBtn').click(function() {
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
</head>
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

.nav-fill>.nav-link,
.nav-fill .nav-item {
    flex: none !important;
    text-align: center;
    width: 200px !important;
}
</style>

<body>
    <?php $activePage = 'services'; include 'nav.php';?>
    <div></div>
    <div class="product">
        <!-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Wedding</li>
                <li class="breadcrumb-item active">Application Form</li>
            </ol>
        </nav> -->
        <!-- header -->
        <div class=".container-fluid mt-4 card mb-2 bg-light shadow" style=" margin: 0 3%">
            <div class="card-body">
                <section class="p-1 z-depth-1">
                    <h1 class="text-center font-weight-bold mb-4">Wedding</h1>
                    <div class="form">
                        <ul class="tab-group">
                            <li class="tab-left "><a href="wedding.php">Application Form</a></li>
                            <!-- <li class="tab-right"><a href="certificate_wedding.php">Requested Certificate</a></li> -->
                            <li class="tab-right active"><a href="wedding_banns.php">Wedding Banns</a></li>
                        </ul>
                    </div>
                </section>
            </div>
        </div>
        <!-- TAB -->
        <div class=".container-fluid mt-4 card mb-2 bg-light shadow" style=" margin: 0 3%">
            <div class="card-body">
                <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">

                        <a class="nav-item nav-link active" id="nav-Approved-tab" data-toggle="tab" href="#nav-Approved"
                            role="tab" aria-controls="nav-Approved" aria-selected="true">Ongoing
                            <?php if ($total_ongoing_wedding_banns > 0): ?>
                            <span
                                class="badge badge-primary rounded-circle p-2"><?php echo $total_ongoing_wedding_banns; ?></span>
                            <?php endif; ?></a>

                        <a class="nav-item nav-link" id="nav-completed-tab" data-toggle="tab" href="#nav-completed"
                            role="tab" aria-controls="nav-completed" aria-selected="false">Ended
                            <?php if ($total_ended_wedding_banns > 0): ?>
                            <span
                                class="badge badge-primary rounded-circle p-2"><?php echo $total_ended_wedding_banns; ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
                </nav>
                <!-- TAB  CONTENT -->

                <div class="tab-content custom-tab-content" id="nav-tabContent">
                    <!-- ONGOING TAB -->
                    <div class="tab-pane fade show active" id="nav-Approved" role="tabpanel"
                        aria-labelledby="nav-Approved-tab">
                        <br>

                        <button type="button" class="btn btn-primary mb-2" id="addmodalbutton"><i
                                class="fas fa-plus"></i>
                            Create</button>
                        <br><br>

                        <!-- ONGOING TABLE -->
                        <table id="dataTableApprove" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>Reference ID</th>
                                    <th>Groom's Name</th>
                                    <th>Bride's Name</th>
                                    <th>Date Started</th>
                                    <th>Date End</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($wedding_banns as $data) { ?>
                                <?php if ($data['status'] == 'ongoing'): ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $data['reference_id']; ?></td>
                                    <td class="text-center align-middle"><?php echo $data['groom_name']; ?></td>
                                    <td class="text-center align-middle"><?php echo $data['bride_name']; ?></td>
                                    <td class="text-center align-middle">
                                        <div class="">
                                            <span class=""><?php echo $data['date_component']; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $data['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="">
                                            <?php 
                                        $end_date = date('d/m/Y', strtotime($data['date_component'] . ' - 58 days'));
                                        ?>
                                            <span class=""><?php echo $end_date; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $data['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>



                                    <td class="text-center align-left">
                                        <?php if ($data['status'] == 'ongoing'): ?>
                                        <span class="badge badge-success rounded-pill d-inline">Ongoing</span>
                                        <?php else: ?>
                                        <span class="badge badge-danger rounded-pill d-inline">Ended</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-left">
                                        <?php if ($data['status'] == 'ongoing'): ?>
                                        <button class="btn-sm btn-warning btn-block endedbutton"
                                            data-status="<?php echo $data['id']; ?>">
                                            <i class="fa fa-minus-circle"></i> Ended
                                        </button>
                                        <?php else: ?>
                                        <button class="btn-sm btn-success btn-block ongoingbutton"
                                            data-status="<?php echo $data['id']; ?>">
                                            <i class="fa fa-check-circle"></i> Ongoing
                                        </button>
                                        <?php endif; ?>
                                        <button class="btn-sm btn-success btn-block mb-2" data-toggle="modal"
                                            data-target="#view_<?php echo $data['id']; ?>">
                                            <i class="fas fa-eye"></i>View
                                        </button>
                                    </td>
                                </tr>

                                <!-- MODAL APPLICATION -->
                                <div class="modal fade" id="view_<?php echo $data['id']; ?>" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document"
                                        style="max-width: 1000px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Application Form (ID:
                                                    <?php echo $data['reference_id']; ?>)</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="date_marriage">Date of Marriage:</label>
                                                            <input type="date" class="form-control" id="date_marriage"
                                                                name="date_marriage" value="<?= $data["date_marriage"] ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="place_marriage">Place of Marriage:</label>
                                                            <input type="text" class="form-control" id="place_marriage"
                                                                name="place_marriage" value="<?= $data["place_marriage"] ?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="groom_name">Groom's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data["groom_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="groom_age">Groom's Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data["groom_age"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="groom_father_name">Groom's Father Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data["groom_father_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="groom_mother_name">Groom's Mother Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data["groom_mother_name"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="bride_name">Bride's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data["bride_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="bride_age">Bride's Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data["bride_age"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="bride_father_name">Bride's Father Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data["bride_father_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="bride_mother_name">Bride's Mother Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data["bride_mother_name"] ?>" disabled>
                                                    </div>
                                                </div>



                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <?php
                                                        $url = $data["id_picture_groom"];
                                                        $hiddenValue = str_repeat('ID Picture (Groom):', strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $data["id_picture_groom"] ?>">View</button>
                                                            </div>
                                                            <div class="file-path" id="id_picture_groom_path"
                                                                style="display: none;">
                                                                <?= $data["id_picture_groom"] ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md">
                                                        <?php
                                                        $url = $data["id_picture_bride"];
                                                        $hiddenValue = str_repeat('ID Picture (Bride):', strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $data["id_picture_bride"] ?>">View</button>
                                                            </div>
                                                            <div class="file-path" id="id_picture_bride_path"
                                                                style="display: none;">
                                                                <?= $data["id_picture_bride"] ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                                    Ok
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php endif; ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- ENDED TAB -->
                    <div class="tab-pane fade" id="nav-completed" role="tabpanel" aria-labelledby="nav-completed-tab">
                        <br>
                        <!-- ENDED TABLE -->
                        <table id="dataTableComplete" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>Reference ID</th>
                                    <th>Groom's Name</th>
                                    <th>Bride's Name</th>
                                    <th>Date Started</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($wedding_banns as $data) { ?>
                                <?php if ($data['status'] == 'ended'): ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $data['reference_id']; ?></td>
                                    <td class="text-center align-middle"><?php echo $data['groom_name']; ?></td>
                                    <td class="text-center align-middle"><?php echo $data['bride_name']; ?></td>
                                    <td class="text-center align-middle">
                                        <div class="">
                                            <span class=""><?php echo $data['date_component']; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $data['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>


                                    <td class="text-center align-left">
                                        <?php if ($data['status'] == 'ongoing'): ?>
                                        <span class="badge badge-success rounded-pill d-inline">Ongoing</span>
                                        <?php else: ?>
                                        <span class="badge badge-danger rounded-pill d-inline">Ended</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-left">
                                        <?php if ($data['status'] == 'ongoing'): ?>
                                        <button class="btn-sm btn-warning btn-block endedbutton"
                                            data-status="<?php echo $data['id']; ?>">
                                            <i class="fa fa-minus-circle"></i> Ended
                                        </button>
                                        <?php else: ?>
                                        <!-- <button class="btn-sm btn-warning btn-block ongoingbutton"
                                            data-status="<?php echo $data['id']; ?>">
                                            <i class="fa fa-check-circle"></i> Ongoing
                                        </button> -->
                                        <?php endif; ?>
                                        <button class="btn-sm btn-success btn-block mb-2" data-toggle="modal"
                                            data-target="#view_<?php echo $data['id']; ?>">
                                            <i class="fas fa-eye"></i>View
                                        </button>
                                    </td>
                                </tr>

                                <!-- MODAL APPLICATION -->
                                <div class="modal fade" id="view_<?php echo $data['id']; ?>" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document"
                                        style="max-width: 1000px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Application Form (ID:
                                                    <?php echo $data['reference_id']; ?>)</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="groom_name">Groom's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data["groom_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="groom_age">Groom's Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data["groom_age"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="groom_father_name">Groom's Father Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data["groom_father_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="groom_mother_name">Groom's Mother Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data["groom_mother_name"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="bride_name">Bride's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data["bride_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="bride_age">Bride's Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data["bride_age"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="bride_father_name">Bride's Father Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data["bride_father_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="bride_mother_name">Bride's Mother Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data["bride_mother_name"] ?>" disabled>
                                                    </div>
                                                </div>



                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <?php
                                                        $url = $data["id_picture_groom"];
                                                        $hiddenValue = str_repeat('ID Picture (Groom):', strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $data["id_picture_groom"] ?>">View</button>
                                                            </div>
                                                            <div class="file-path" id="id_picture_groom_path"
                                                                style="display: none;">
                                                                <?= $data["id_picture_groom"] ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md">
                                                        <?php
                                                        $url = $data["id_picture_bride"];
                                                        $hiddenValue = str_repeat('ID Picture (Bride):', strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $data["id_picture_bride"] ?>">View</button>
                                                            </div>
                                                            <div class="file-path" id="id_picture_bride_path"
                                                                style="display: none;">
                                                                <?= $data["id_picture_bride"] ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                                    Ok
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php endif; ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="imageModal" class="modal_pic">
        <img class="modal_img" id="modalImage">
    </div>


    <!-- ADD Modal form -->
    <div id="weddingbannsModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTitle"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="wedding_banns/add.php" id="admin_dataForm" method="post"
                        enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_marriage">Date of Marriage:</label>
                                    <input type="date" class="form-control" id="date_marriage" name="date_marriage"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="place">
                                    <label for="place_marriage">Place of Marriage:</label>
                                    <select class="form-control" name="place_marriage" required
                                        onchange="checkOther(this)">
                                        <option value="Immaculate Conception Parish Pandi">Immaculate Conception Parish
                                            Pandi</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div id="other_place_container" style="display: none;" class="form-group">
                                    <label for="other_place">Other Place:</label>
                                    <input type="text" class="form-control" id="place_marriage" name="place_marriage">
                                </div>
                            </div>
                            <script>
                            function checkOther(select) {
                                var other = document.getElementById('other_place_container');
                                var place = document.getElementById('place');

                                if (select.value === 'other') {
                                    other.style.display = 'block';
                                    place.style.display = 'none';
                                } else {
                                    other.style.display = 'none';
                                    place.style.display = 'block';
                                }
                            }
                            </script>
                        </div><br>

                        <div class="row" style="border-top: 1px solid #ccc;">
                            <div class="col-md-6">
                                <br>
                                <div class="form-group">
                                    <label for="id_picture_groom">Groom's ID Picture:</label>
                                    <input type="file" class="form-control" id="id_picture_groom"
                                        name="id_picture_groom" required>
                                </div>
                                <div class="form-group">
                                    <label for="groom_name">Groom's Fullname:</label>
                                    <input type="text" class="form-control" id="groom_name" name="groom_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="groom_age">Groom's Age:</label>
                                    <input type="text" class="form-control" id="groom_age" name="groom_age" required>
                                </div>
                                <div class="form-group">
                                    <label for="groom_father_name">Groom's Father Fullname:</label>
                                    <input type="text" class="form-control" id="groom_father_name"
                                        name="groom_father_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="groom_mother_name">Groom's Mother Fullname:</label>
                                    <input type="text" class="form-control" id="groom_mother_name"
                                        name="groom_mother_name" required>
                                </div>
                            </div>
                            <div class="col-md-6" style="border-left: 1px solid #ccc;">
                                <br>
                                <div class="form-group">
                                    <label for="id_picture_bride">Bride's ID Picture:</label>
                                    <input type="file" class="form-control" id="id_picture_bride"
                                        name="id_picture_bride" required>
                                </div>
                                <div class="form-group">
                                    <label for="bride_name">Bride's Fullname:</label>
                                    <input type="text" class="form-control" id="bride_name" name="bride_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="bride_age">Bride's Age:</label>
                                    <input type="text" class="form-control" id="bride_age" name="bride_age" required>
                                </div>
                                <div class="form-group">
                                    <label for="bride_father_name">Bride's Father Fullname:</label>
                                    <input type="text" class="form-control" id="bride_father_name"
                                        name="bride_father_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="bride_mother_name">Bride's Mother Fullname:</label>
                                    <input type="text" class="form-control" id="bride_mother_name"
                                        name="bride_mother_name" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="submit" class="btn btn-primary" id="add_adminBtn"><i
                                    class="fas fa-upload"></i> Publish</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



</body>
<script>
$(document).on('click', '.endedbutton', function() {
    var id = $(this).data('status');
    if (confirm("Are you sure you want to ended this banns? This will automatically ended in 30days.")) {
        $.ajax({
            url: 'wedding_banns_ended.php',
            method: 'POST',
            data: {
                id: id
            },
            success: function(response) {
                console.log(response);
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
});

$(document).on('click', '.ongoingbutton', function() {
    var id = $(this).data('status');
    if (confirm("Are you sure you want to change the status of banns?")) {
        $.ajax({
            url: 'wedding_banns_ongoing.php',
            method: 'POST',
            data: {
                id: id
            },
            success: function(response) {
                console.log(response);
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
});
</script>


<!-- MODAL JS -->
<script>
$(document).ready(function() {
    $('#dataTableApprove').DataTable();
    $('#dataTableComplete').DataTable();


    $('#addBtn').click(function() {
        $('#modalTitle').text('Add Product');
        $('#dataForm')[0].reset();
        $('#dataModal').modal('show');
    });

    $('#saveBtn').click(function() {

        $('#dataModal').modal('hide');
    });

    $(document).on('click', '.editBtn', function() {
        $('#modalTitle').text('Edit Data');
        var data = $(this).data('info');
        $('#id').val(data.id);
        $('#name').val(data.name);
        $('#email').val(data.email);
        $('#dataModal').modal('show');
    });

    $(document).on('click', '.deleteBtn', function() {
        var data = $(this).data('info');

    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Get the select element
    var placeSelect = document.getElementById("place_of_marriage");
    // Get the container for the "Other Place" input
    var otherPlaceContainer = document.getElementById("other_place_container");
    // Get the input field for "Other Place"
    var otherPlaceInput = document.getElementById("other_place");

    // Add event listener to the select element
    placeSelect.addEventListener("change", function() {
        // If the selected value is "others", show the input field
        if (placeSelect.value === "others") {
            otherPlaceContainer.style.display = "block";
            // Make the "Other Place" input field required
            otherPlaceInput.required = true;
        } else {
            // If the selected value is not "others", hide the input field
            otherPlaceContainer.style.display = "none";
            // Make the "Other Place" input field not required
            otherPlaceInput.required = false;
        }
    });
});
</script>

<!-- OTHERS JS -->


</html>

<style>
/* Center modal vertically and horizontally */
.modal_pic {
    display: none;
    /* Hide modal by default */
    position: fixed;
    z-index: 1500;
    padding-top: 100px;
    /* Location of the modal */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0, 0, 0);
    /* Fallback color */
    background-color: rgba(0, 0, 0, 0.9);
    /* Black w/ opacity */
}

.modal_img {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 800px;
}

.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}

.close:hover,
.close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}
</style>