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
$result = mysqli_query($conn, "SELECT * FROM wedding ORDER BY id DESC LIMIT 1");

if (!$result) {
    die("Error in query: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
?>

<?php include 'process/formula.php';?>

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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image/admin.ico">
    <title>Record Book | Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>

</head>

<body>
    <?php 
    $activePage = 'records'; 
    include 'nav.php';
    ?>
    <div></div>
    <div class="product">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Record Book</li>
            </ol>
        </nav>
        <!-- header -->
        <div class=".container-fluid mt-4 card mb-2 bg-light shadow" style=" margin: 0 3%">
            <div class="card-body">
                <section class="p-1 z-depth-1">
                    <h1 class="text-center font-weight-bold mb-4">RECORD BOOK</h1>

                </section>
            </div>
        </div>
        <!-- TAB -->
        <div class=".container-fluid mt-4 card mb-2 bg-light shadow" style=" margin: 0 3%">
            <div class="card-body">
                <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-Wedding-tab" data-toggle="tab" href="#nav-Wedding"
                            role="tab" aria-controls="nav-Wedding" aria-selected="true">
                            Wedding
                            <?php if ($total_wedding > 0): ?>
                            <span class="badge badge-primary rounded-circle p-2"><?php echo $total_wedding; ?></span>
                            <?php endif; ?>
                        </a>

                        <a class="nav-item nav-link" id="nav-Baptismal-tab" data-toggle="tab" href="#nav-Baptismal"
                            role="tab" aria-controls="nav-Baptismal" aria-selected="false">Baptismal
                            <?php if ($total_binyag > 0): ?>
                            <span class="badge badge-primary rounded-circle p-2"><?php echo $total_binyag; ?></span>
                            <?php endif; ?>
                        </a>

                        <a class="nav-item nav-link" id="nav-Funeral-tab" data-toggle="tab" href="#nav-Funeral"
                            role="tab" aria-controls="nav-Funeral" aria-selected="false">Funeral
                            <?php if ($total_funeral > 0): ?>
                            <span class="badge badge-primary rounded-circle p-2"><?php echo $total_funeral; ?></span>
                            <?php endif; ?>
                        </a>

                        <a class="nav-item nav-link" id="nav-Sickcall-tab" data-toggle="tab" href="#nav-Sickcall"
                            role="tab" aria-controls="nav-Sickcall" aria-selected="false">Sickcall
                            <?php if ($total_sickcall > 0): ?>
                            <span class="badge badge-primary rounded-circle p-2"><?php echo $total_sickcall; ?></span>
                            <?php endif; ?>
                        </a>

                        <a class="nav-item nav-link" id="nav-Mass-tab" data-toggle="tab" href="#nav-Mass" role="tab"
                            aria-controls="nav-Mass" aria-selected="false">Mass
                            <?php if ($total_mass > 0): ?>
                            <span class="badge badge-primary rounded-circle p-2"><?php echo $total_mass; ?></span>
                            <?php endif; ?>
                        </a>

                        <a class="nav-item nav-link" id="nav-Blessing-tab" data-toggle="tab" href="#nav-Blessing"
                            role="tab" aria-controls="nav-Blessing" aria-selected="false">Blessing
                            <?php if ($total_blessing > 0): ?>
                            <span class="badge badge-primary rounded-circle p-2"><?php echo $total_blessing; ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
                </nav>
                <!-- TAB  CONTENT -->
                <div class="tab-content custom-tab-content" id="nav-tabContent">

                    <!-- Wedding TAB___________________________________________________________________________________ -->
                    <div class="tab-pane fade show active" id="nav-Wedding" role="tabpanel"
                        aria-labelledby="nav-Wedding-tab">
                        <br>
                        <!-- Wedding TABLE -->
                        <table id="dataTableWedding" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>Reference ID</th>
                                    <th>Groom's Name</th>
                                    <th>Age</th>
                                    <th>Bride's Name</th>
                                    <th>Age</th>
                                    <th>Date Applied</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($wedding as $completewedding) { ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $completewedding['reference_id']; ?>
                                    </td>
                                    <td class="text-center align-middle"><?php echo $completewedding['groom_name']; ?>
                                    </td>
                                    <td class="text-center align-middle"><?php echo $completewedding['groom_age']; ?>
                                    </td>
                                    <td class="text-center align-middle"><?php echo $completewedding['bride_name']; ?>
                                    </td>
                                    <td class="text-center align-middle"><?php echo $completewedding['bride_age']; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="">
                                            <span class=""><?php echo $completewedding['date_component']; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $completewedding['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn-sm btn-success btn-block mb-2" data-toggle="modal"
                                            data-target="#view_<?php echo $completewedding['id']; ?>">
                                            <i class="fas fa-eye"></i>View
                                        </button>
                                    </td>
                                </tr>

                                <!-- MODAL APPLICATION -->
                                <?php foreach ($wedding as $completewedding) { ?>
                                <div class="modal fade" id="view_<?php echo $completewedding['id']; ?>" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document"
                                        style="max-width: 1000px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Application Form (ID:
                                                    <?php echo $completewedding['reference_id']; ?>)</h5>
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
                                                            value="<?= $completewedding["groom_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="groom_age">Groom's Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completewedding["groom_age"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="groom_father_name">Groom's Father Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completewedding["groom_father_name"] ?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="groom_mother_name">Groom's Mother Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completewedding["groom_mother_name"] ?>"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="bride_name">Bride's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completewedding["bride_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="bride_age">Bride's Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completewedding["bride_age"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="bride_father_name">Bride's Father Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completewedding["bride_father_name"] ?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="bride_mother_name">Bride's Mother Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completewedding["bride_mother_name"] ?>"
                                                            disabled>
                                                    </div>
                                                </div>


                                                <h4>DOCUMENTS</h4>
                                                <hr>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <?php
                                                        $url = $completewedding["psa_cenomar_photocopy_groom"];
                                                        $hiddenValue = str_repeat('PSA Cenomar Photocopy (Groom):', strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $completewedding["psa_cenomar_photocopy_groom"] ?>">View</button>
                                                            </div>
                                                        </div>
                                                        <div class="file-path" id="psa_cenomar_photocopy_groom_path"
                                                            style="display: none;">
                                                            <?= $completewedding["psa_cenomar_photocopy_groom"] ?>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md">
                                                        <?php
                                                        $url = $completewedding["psa_cenomar_photocopy_bride"];
                                                        $hiddenValue = str_repeat('PSA Cenomar Photocopy (Bride):', strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $completewedding["psa_cenomar_photocopy_bride"] ?>">View</button>
                                                            </div>
                                                            <div class="file-path" id="psa_cenomar_photocopy_bride_path"
                                                                style="display: none;">
                                                                <?= $completewedding["psa_cenomar_photocopy_bride"] ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <?php
                                                        $url = $completewedding["baptismal_certificates_groom"];
                                                        $hiddenValue = str_repeat('Baptismal Certificates (Groom):', strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $completewedding["baptismal_certificates_groom"] ?>">View</button>
                                                            </div>
                                                            <div class="file-path"
                                                                id="baptismal_certificates_groom_path"
                                                                style="display: none;">
                                                                <?= $completewedding["baptismal_certificates_groom"] ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md">
                                                        <?php
                                                        $url = $completewedding["baptismal_certificates_bride"];
                                                        $hiddenValue = str_repeat('Baptismal Certificates (Bride):', strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $completeitem["baptismal_certificates_bride"] ?>">View</button>
                                                            </div>
                                                            <div class="file-path"
                                                                id="baptismal_certificates_bride_path"
                                                                style="display: none;">
                                                                <?= $completewedding["baptismal_certificates_bride"] ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <?php
                                                        $url = $completewedding["psa_birth_certificate_photocopy_groom"];
                                                        $hiddenValue = str_repeat('PSA Birth Certificate Photocopy (Groom):', strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $completewedding["psa_birth_certificate_photocopy_groom"] ?>">View</button>
                                                            </div>
                                                            <div class="file-path"
                                                                id="psa_birth_certificate_photocopy_groom_path"
                                                                style="display: none;">
                                                                <?= $completewedding["psa_birth_certificate_photocopy_groom"] ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md">
                                                        <?php
                                                        $url = $completewedding["psa_birth_certificate_photocopy_bride"];
                                                        $hiddenValue = str_repeat('PSA Birth Certificate Photocopy (Bride):', strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $completewedding["psa_birth_certificate_photocopy_bride"] ?>">View</button>
                                                            </div>
                                                            <div class="file-path"
                                                                id="psa_birth_certificate_photocopy_bride_path"
                                                                style="display: none;">
                                                                <?= $completewedding["psa_birth_certificate_photocopy_bride"] ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <?php
                                                        $url = $completewedding["id_picture_groom"];
                                                        $hiddenValue = str_repeat('ID Picture (Groom):', strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $completewedding["id_picture_groom"] ?>">View</button>
                                                            </div>
                                                            <div class="file-path" id="id_picture_groom_path"
                                                                style="display: none;">
                                                                <?= $completewedding["id_picture_groom"] ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md">
                                                        <?php
                                                        $url = $completewedding["id_picture_bride"];
                                                        $hiddenValue = str_repeat('ID Picture (Bride):', strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $completewedding["id_picture_bride"] ?>">View</button>
                                                            </div>
                                                            <div class="file-path" id="id_picture_bride_path"
                                                                style="display: none;">
                                                                <?= $completewedding["id_picture_bride"] ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <?php
                                                        $url = $completewedding["confirmation_certificates"];
                                                        $hiddenValue = str_repeat('Confirmation Certificates:', strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $completewedding["confirmation_certificates"] ?>">View</button>
                                                            </div>
                                                            <div class="file-path" id="confirmation_certificates_path"
                                                                style="display: none;">
                                                                <?= $completewedding["confirmation_certificates"] ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <?php
                                                        $url = $completewedding["computerized_name_of_sponsors"];
                                                        $hiddenValue = str_repeat('Computerized Name of Sponsors:', strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $completewedding["computerized_name_of_sponsors"] ?>">View</button>
                                                            </div>
                                                            <div class="file-path"
                                                                id="computerized_name_of_sponsors_path"
                                                                style="display: none;">
                                                                <?= $completewedding["computerized_name_of_sponsors"] ?>
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
                                    <?php } ?>

                                    <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Baptismal TAB___________________________________________________________________________________ -->
                    <div class="tab-pane fade show" id="nav-Baptismal" role="tabpanel"
                        aria-labelledby="nav-Baptismal-tab">
                        <br>
                        <!-- Baptismal TABLE -->
                        <table id="dataTableBaptismal" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>Reference ID</th>
                                    <th>Child's Name</th>
                                    <th>Months</th>
                                    <th>Address</th>
                                    <th>Birthplace</th>
                                    <th>Date Applied</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($binyag as $completebaptismal) { ?>
                                <tr>
                                    <td class="text-center align-middle">
                                        <?php echo $completebaptismal['reference_id']; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php echo $completebaptismal['father_lastname']; ?>,
                                        <?php echo $completebaptismal['child_first_name']; ?>
                                        <?php echo $completebaptismal['mother_maiden_lastname']; ?></td>
                                    <td class="text-center align-middle"><?php echo $completebaptismal['months']; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php echo $completebaptismal['complete_address']; ?>
                                    </td>
                                    <td class="text-center align-middle"><?php echo $completebaptismal['birthplace']; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="">
                                            <span class=""><?php echo $completebaptismal['date_component']; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $completebaptismal['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn-sm btn-success btn-block mb-2" data-toggle="modal"
                                            data-target="#view_<?php echo $completebaptismal['id']; ?>">
                                            <i class="fas fa-eye"></i>View
                                        </button>
                                    </td>
                                </tr>

                                <!-- MODAL APPLICATION -->
                                <?php foreach ($binyag as $completebaptismal) { ?>
                                <div class="modal fade" id="view_<?php echo $completebaptismal['id']; ?>" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document"
                                        style="max-width: 1000px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Application Form (ID:
                                                    <?php echo $completebaptismal['reference_id']; ?>)</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="child_first_name">Child's First Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["child_first_name"] ?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_maiden_lastname">Mother's Maiden Last
                                                            Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["mother_maiden_lastname"] ?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="father_lastname">Father's Last Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["father_lastname"] ?>"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="birthdate">Birthdate:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["birthdate"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="birthplace">Birthplace:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["birthplace"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="months">Months:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["months"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="baptismal_date">Baptismal Date:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["baptismal_date"] ?>"
                                                            disabled>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="complete_address">Current Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["complete_address"] ?>"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="father_name">Father's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["father_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="father_origin_place">Father's Origin Place:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["father_origin_place"] ?>"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="mother_maiden_fullname">Mother's Maiden Full
                                                            Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["mother_maiden_fullname"] ?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_origin_place">Mother's Origin Place:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["mother_origin_place"] ?>"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="marriage">Marriage:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["marriage"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="marriage_location">Marriage Location:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["marriage_location"] ?>"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="godfather">Godfather:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["godfather"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godfather_age">Godfather's Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["godfather_age"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godfather_religion">Godfather's Religion:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["godfather_religion"] ?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godfather_address">Godfather's Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["godfather_address"] ?>"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="godmother">Godmother:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["godmother"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godmother_age">Godmother's Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["godmother_age"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godmother_religion">Godmother's Religion:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["godmother_religion"] ?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godmother_address">Godmother's Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["godmother_address"] ?>"
                                                            disabled>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="client_name">Client's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["client_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="client_relationship">Client's Relationship:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["client_relationship"] ?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="client_contact_number">Client's Contact
                                                            Number:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completebaptismal["client_contact_number"] ?>"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="psa_cenomar_photocopy_groom">Copy of Birth
                                                            Certificate:</label>
                                                        <?php
                                                        $url = $completebaptismal["copy_birth_certificate"];
                                                        $hiddenValue = str_repeat("Birth Certificate", strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $completebaptismal["copy_birth_certificate"] ?>">View</button>
                                                            </div>
                                                        </div>
                                                        <div class="file-path" id="psa_cenomar_photocopy_groom_path"
                                                            style="display: none;">
                                                            <?= $completebaptismal["copy_birth_certificate"] ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="copy_marriage_certificate">Copy of Marriage
                                                            Certificate:</label>
                                                        <?php
                                                        $url = $completebaptismal["copy_marriage_certificate"];
                                                        $hiddenValue = str_repeat("Marriage Certificate", strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $completebaptismal["copy_marriage_certificate"] ?>">View</button>
                                                            </div>
                                                        </div>
                                                        <div class="file-path" id="psa_cenomar_photocopy_groom_path"
                                                            style="display: none;">
                                                            <?= $completebaptismal["copy_marriage_certificate"] ?>
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
                                <?php } ?>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Funeral TAB___________________________________________________________________________________ -->
                    <div class="tab-pane fade show" id="nav-Funeral" role="tabpanel" aria-labelledby="nav-Funeral-tab">
                        <br>
                        <!-- Funeral TABLE -->
                        <table id="dataTableFuneral" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>Reference ID</th>
                                    <th>Deceased Name</th>
                                    <th>Cause of Death</th>
                                    <th>Date of Death</th>
                                    <th>Age</th>
                                    <th>Date Applied</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($funeral as $completefuneral) { ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $completefuneral['reference_id']; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php echo $completefuneral['deceased_fullname']; ?></td>
                                    <td class="text-center align-middle">
                                        <?php echo $completefuneral['cause_of_death']; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php echo $completefuneral['date_of_death']; ?>
                                    </td>
                                    <td class="text-center align-middle"><?php echo $completefuneral['age']; ?></td>
                                    <td class="text-center align-middle">
                                        <div class="">
                                            <span class=""><?php echo $completefuneral['date_component']; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $completefuneral['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn-sm btn-success btn-block mb-2" data-toggle="modal"
                                            data-target="#view_<?php echo $completefuneral['id']; ?>">
                                            <i class="fas fa-eye"></i>View
                                        </button>
                                    </td>
                                </tr>

                                <!-- MODAL APPLICATION -->
                                <?php foreach ($funeral as $completefuneral) { ?>
                                <div class="modal fade" id="view_<?php echo $completefuneral['id']; ?>" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document"
                                        style="max-width: 1000px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Application Form (ID:
                                                    <?php echo $completefuneral['reference_id']; ?>)</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="deceased_fullname">Deceased Fullname:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["deceased_fullname"] ?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="age">Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["age"] ?>" disabled>
                                                    </div>

                                                    <div class="form-group col-md">
                                                        <label for="date_of_death">Date of Death:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["date_of_death"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="civil_status">Civil Status:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["civil_status"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="spouse_name">Spouse Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["spouse_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="number_of_child">Number of Child:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["number_of_child"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="mother_name">Mother's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["mother_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="father_name">Father's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["father_name"] ?>" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="complete_address">Current Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["complete_address"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="cause_of_death">Cause of Death:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["cause_of_death"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="has_sacrament">Has Sacrament:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["has_sacrament"] ?>" disabled>
                                                    </div>

                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="client_name">Client Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["client_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="relationship">Relationship:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["relationship"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="contact_number">Contact Number:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["contact_number"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="allowed_to_mass">Allowed to Mass:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["allowed_to_mass"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mass_time">Mass Time:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["mass_time"] ?>" disabled>
                                                    </div>

                                                    <div class="form-group col-md">
                                                        <label for="mass_date">Mass Date:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["mass_date"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mass_location">Mass Location:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["mass_location"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="burial_place">Burial Place:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completefuneral["burial_place"] ?>" disabled>
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
                                <?php } ?>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Sickcall TAB___________________________________________________________________________________ -->
                    <div class="tab-pane fade show" id="nav-Sickcall" role="tabpanel"
                        aria-labelledby="nav-Sickcall-tab">
                        <br>
                        <!-- Sickcall TABLE -->
                        <table id="dataTableSickcall" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>Reference ID</th>
                                    <th>Patients Name</th>
                                    <th>Illness</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Date Applied</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sickcall as $completesickcall) { ?>
                                <tr>
                                    <td class="text-center align-middle">
                                        <?php echo $completesickcall['reference_id']; ?></td>
                                    <td class="text-center align-middle">
                                        <?php echo $completesickcall['patients_name']; ?></td>
                                    <td class="text-center align-middle"><?php echo $completesickcall['illness']; ?>
                                    </td>
                                    <td class="text-center align-middle"><?php echo $completesickcall['date']; ?></td>
                                    <td class="text-center align-middle"><?php echo $completesickcall['time']; ?></td>
                                    <td class="text-center align-middle">
                                        <div class="">
                                            <span class=""><?php echo $completesickcall['date_component']; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $completesickcall['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn-sm btn-success btn-block mb-2" data-toggle="modal"
                                            data-target="#view_<?php echo $completesickcall['id']; ?>">
                                            <i class="fas fa-eye"></i>View
                                        </button>
                                    </td>
                                </tr>

                                <!-- MODAL APPLICATION -->
                                <?php foreach ($sickcall as $completesickcall) { ?>
                                <div class="modal fade" id="view_<?php echo $completesickcall['id']; ?>" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document"
                                        style="max-width: 1000px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Application Form (ID:
                                                    <?php echo $completesickcall['reference_id']; ?>)</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="patients_name">Patient's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completesickcall["patients_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="age">Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completesickcall["age"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="address">Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completesickcall["address"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="hospital">Hospital:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completesickcall["hospital"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="room_number">Room Number:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completesickcall["room_number"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="illness">Illness:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completesickcall["illness"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="can_eat">Can Eat:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completesickcall["can_eat"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="can_speak">Can Speak:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completesickcall["can_speak"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="contact_number">Contact Number:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completesickcall["contact_number"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="contact_person">Contact Person:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completesickcall["contact_person"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="date">Date:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completesickcall["date"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="time">Time:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completesickcall["time"] ?>" disabled>
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
                                <?php } ?>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mass TAB___________________________________________________________________________________ -->
                    <div class="tab-pane fade show" id="nav-Mass" role="tabpanel" aria-labelledby="nav-Mass-tab">
                        <br>
                        <!-- Mass TABLE -->
                        <table id="dataTableMass" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>Reference ID</th>
                                    <th>Purpose</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Date Applied</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mass as $completemass) { ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $completemass['reference_id']; ?>
                                    </td>
                                    <td class="text-center align-middle"><?php echo $completemass['purpose']; ?></td>
                                    <td class="text-center align-middle"><?php echo $completemass['name']; ?></td>
                                    <td class="text-center align-middle"><?php echo $completemass['date']; ?></td>
                                    <td class="text-center align-middle">
                                        <div class="">
                                            <span class=""><?php echo $completemass['date_component']; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $completemass['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn-sm btn-success btn-block mb-2" data-toggle="modal"
                                            data-target="#view_<?php echo $completemass['id']; ?>">
                                            <i class="fas fa-eye"></i>View
                                        </button>
                                    </td>
                                </tr>

                                <!-- MODAL APPLICATION -->
                                <?php foreach ($mass as $completemass) { ?>
                                <div class="modal fade" id="view_<?php echo $completemass['id']; ?>" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document"
                                        style="max-width: 1000px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Application Form (ID:
                                                    <?php echo $completemass['reference_id']; ?>)</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="purpose">Purpose:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completemass["purpose"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="name">Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completemass["name"] ?>" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="date">Date:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completemass["date"] ?>" disabled>
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
                                <?php } ?>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Blessing TAB___________________________________________________________________________________ -->
                    <div class="tab-pane fade show" id="nav-Blessing" role="tabpanel"
                        aria-labelledby="nav-Blessing-tab">
                        <br>
                        <!-- Blessing TABLE -->
                        <table id="dataTableBlessing" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>

                                    <th>Reference ID</th>
                                    <th>Place</th>
                                    <th>Complete Address</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Date Applied</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($blessing as $completeblessing) { ?>
                                <tr>
                                    <td class="text-center align-middle">
                                        <?php echo $completeblessing['reference_id']; ?>
                                    </td>
                                    <td class="text-center align-middle"><?php echo $completeblessing['place']; ?></td>
                                    <td class="text-center align-middle">
                                        <?php echo $completeblessing['complete_address']; ?></td>
                                    <td class="text-center align-middle"><?php echo $completeblessing['date']; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php echo $completeblessing['time']; ?></td>
                                    <td class="text-center align-middle">
                                        <div class="">
                                            <span class=""><?php echo $completeblessing['date_component']; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $completeblessing['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn-sm btn-success btn-block mb-2" data-toggle="modal"
                                            data-target="#view_<?php echo $completeblessing['id']; ?>">
                                            <i class="fas fa-eye"></i>View
                                        </button>
                                    </td>
                                </tr>

                                <!-- MODAL APPLICATION -->
                                <?php foreach ($blessing as $completeblessing) { ?>
                                <div class="modal fade" id="view_<?php echo $completeblessing['id']; ?>" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document"
                                        style="max-width: 1000px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Application Form (ID:
                                                    <?php echo $completeblessing['reference_id']; ?>)</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="place">Place:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeblessing["place"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="owner_name">Owner's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeblessing["owner_name"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="complete_address">Complete Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeblessing["complete_address"] ?>"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="contact_number">Contact Number:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeblessing["contact_number"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="contact_person">Contact Person:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeblessing["contact_person"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="date">Date:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeblessing["date"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="time">Time:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeblessing["time"] ?>" disabled>
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
                                <?php } ?>

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
</body>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var viewButtons = document.querySelectorAll('.view-btn');
    var modal = document.getElementById('imageModal');
    var modalImg = document.getElementById('modalImage');
    var closeModal = document.getElementsByClassName('close')[0];
    document.body.addEventListener('contextmenu', function(event) {
        event.preventDefault();
    });

    viewButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            var url = this.getAttribute('data-url');
            modal.style.display =
                'block'; // Display the modal
            modalImg.src = url; // Set the image source
        });
    });

    document.querySelector('.close').addEventListener('click', function() {
        document.getElementById('imageModal').style.display = 'none';
    });



    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            modal.style.display =
                'none'; // Hide the modal when clicked outside of it
        }
    });

    modal.addEventListener('contextmenu', function(event) {
        event
            .preventDefault(); // Prevent default right-click behavior
    });

    modalImg.addEventListener('contextmenu', function(event) {
        event
            .preventDefault(); // Prevent default right-click behavior
    });
});
</script>
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
<!-- MODAL JS -->
<script>
$(document).ready(function() {
    $('#dataTableWedding').DataTable();
    $('#dataTableBaptismal').DataTable();
    $('#dataTableFuneral').DataTable();
    $('#dataTableSickcall').DataTable();
    $('#dataTableMass').DataTable();
    $('#dataTableBlessing').DataTable();


});
</script>


</html>
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
    width: 100%;
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