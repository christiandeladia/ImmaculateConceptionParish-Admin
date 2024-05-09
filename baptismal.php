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
$result = mysqli_query($conn, "SELECT * FROM binyag ORDER BY id DESC LIMIT 1");

if (!$result) {
    die("Error in query: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
?>

<?php include 'process/formula.php';?>

<?php 
function getbinyagData() {
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%d/%m/%Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM binyag";
    $inventory = [];
    $reference_id = uniqid();
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
$inventory = getbinyagData();

function getbinyagCounts() {
    global $pdo;
    $query = "SELECT status_id, COUNT(*) AS count FROM binyag GROUP BY status_id";
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

$binyagCounts = getbinyagCounts();

// Initialize counts
$total_binyag = $total_binyag_approve = $total_binyag_complete = $total_binyag_decline = 0;

// Process the counts
foreach ($binyagCounts as $count) {
    switch ($count['status_id']) {
        case 1:
            $total_binyag = $count['count'];
            break;
        case 2:
            $total_binyag_approve = $count['count'];
            break;
        case 3:
            $total_binyag_complete = $count['count'];
            break;
        case 4:
            $total_binyag_decline = $count['count'];
            break;
        default:
            break;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image/admin.ico">
    <title>Baptismal | Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>

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
</style>

<body>
    <?php $activePage = 'services'; include 'nav.php';?>
    <div></div>
    <div class="product">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Baptismal</li>
                <li class="breadcrumb-item active">Application Form</li>
            </ol>
        </nav>
        <!-- header -->
        <div class=".container-fluid mt-4 card mb-2 bg-light shadow" style=" margin: 0 3%">
            <div class="card-body">
                <section class="p-1 z-depth-1">
                    <h1 class="text-center font-weight-bold mb-4">Baptismal</h1>
                    <div class="form">
                        <ul class="tab-group">
                            <li class="tab-left active"><a href="#ApplicationForm">Application Form</a></li>
                            <li class="tab-right"><a href="certificate_baptismal.php">Requested Certificate</a></li>
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
                        <a class="nav-item nav-link active" id="nav-process-tab" data-toggle="tab" href="#nav-process"
                            role="tab" aria-controls="nav-process" aria-selected="true">
                            To Process
                            <?php if ($total_binyag > 0): ?>
                            <span class="badge badge-primary rounded-circle p-2"><?php echo $total_binyag; ?></span>
                            <?php endif; ?>
                        </a>

                        <a class="nav-item nav-link" id="nav-Approved-tab" data-toggle="tab" href="#nav-Approved"
                            role="tab" aria-controls="nav-Approved" aria-selected="false">Approved
                            <?php if ($total_binyag_approve > 0): ?>
                            <span
                                class="badge badge-primary rounded-circle p-2"><?php echo $total_binyag_approve; ?></span>
                            <?php endif; ?></a>

                        <a class="nav-item nav-link" id="nav-completed-tab" data-toggle="tab" href="#nav-completed"
                            role="tab" aria-controls="nav-completed" aria-selected="false">Completed
                            <?php if ($total_binyag_complete > 0): ?>
                            <span
                                class="badge badge-primary rounded-circle p-2"><?php echo $total_binyag_complete; ?></span>
                            <?php endif; ?>
                        </a>

                        <a class="nav-item nav-link" id="nav-decline-tab" data-toggle="tab" href="#nav-decline"
                            role="tab" aria-controls="nav-decline" aria-selected="false">Decline
                            <?php if ($total_binyag_decline > 0): ?>
                            <span
                                class="badge badge-primary rounded-circle p-2"><?php echo $total_binyag_decline; ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
                </nav>
                <!-- TAB  CONTENT -->
                <div class="tab-content custom-tab-content" id="nav-tabContent">

                    <!-- PROCESS TAB___________________________________________________________________________________ -->
                    <div class="tab-pane fade show active" id="nav-process" role="tabpanel"
                        aria-labelledby="nav-process-tab">
                        <br>
                        <!-- PROCESS TABLE -->
                        <table id="dataTableProcess" class="table table-striped table-responsive-lg" cellspacing="0"
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
                            <?php foreach ($inventory as $item) { 
                             if ($item['status_id'] == 1) { ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $item['reference_id']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['father_lastname']; ?>,
                                        <?php echo $item['child_first_name']; ?>
                                        <?php echo $item['mother_maiden_lastname']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['months']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['current_address']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['birthplace']; ?></td>
                                    <td class="text-center align-middle">
                                        <div class="">
                                            <span class=""><?php echo $item['date_component']; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $item['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn-sm btn-success btn-block mb-2" data-toggle="modal"
                                            data-target="#view_<?php echo $item['id']; ?>">
                                            <i class="fas fa-eye"></i>View
                                        </button>
                                    </td>
                                </tr>

                                <!-- MODAL APPLICATION -->
                                <div class="modal fade" id="view_<?php echo $item['id']; ?>" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document"
                                        style="max-width: 1000px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Application Form (ID:
                                                    <?php echo $item['reference_id']; ?>)</h5>
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
                                                            value="<?= $item["child_first_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_maiden_lastname">Mother's Maiden Last
                                                            Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["mother_maiden_lastname"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="father_lastname">Father's Last Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["father_lastname"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="birthdate">Birthdate:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["birthdate"] ?>" disabled>
                                                    </div>
                                                    
                                                    <div class="form-group col-md">
                                                        <label for="birthplace">Birthplace:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["birthplace"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="months">Months:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["months"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="baptismal_date">Baptismal Date:</label>
                                                        <input type="text" class="form-control"
                                                        value="<?= date("F j, Y", strtotime($item["baptismal_date"])) ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="baptismal_time">Baptismal Time:</label>
                                                        <input type="text" class="form-control"
                                                        value="<?= date("h:i A", strtotime($item["baptismal_time"])) ?>" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="current_address">Current Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["current_address"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="father_name">Father's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["father_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="father_origin_place">Father's Origin Place:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["father_origin_place"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_maiden_fullname">Mother's Maiden Full
                                                            Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["mother_maiden_fullname"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_origin_place">Mother's Origin Place:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["mother_origin_place"] ?>" disabled>
                                                    </div>
                                                </div>
                                             
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="marriage">Marriage:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["marriage"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="marriage_location">Marriage Location:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["marriage_location"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="godfather">Godfather:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godfather"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godfather_age">Godfather's Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godfather_age"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godfather_religion">Godfather's Religion:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godfather_religion"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godfather_address">Godfather's Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godfather_address"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="godmother">Godmother:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godmother"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godmother_age">Godmother's Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godmother_age"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godmother_religion">Godmother's Religion:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godmother_religion"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godmother_address">Godmother's Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godmother_address"] ?>" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="client_name">Client's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["client_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="client_relationship">Client's Relationship:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["client_relationship"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="client_contact_number">Client's Contact
                                                            Number:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["client_contact_number"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="psa_cenomar_photocopy_groom">Copy of Birth
                                                            Certificate:</label>
                                                        <?php
                                                        $url = $item["copy_birth_certificate"];
                                                        $hiddenValue = str_repeat("Birth Certificate", strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $item["copy_birth_certificate"] ?>">View</button>
                                                            </div>
                                                        </div>
                                                        <div class="file-path" id="psa_cenomar_photocopy_groom_path"
                                                            style="display: none;">
                                                            <?= $item["copy_birth_certificate"] ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="copy_marriage_certificate">Copy of Marriage
                                                            Certificate:</label>
                                                        <?php
                                                        $url = $item["copy_marriage_certificate"];
                                                        $hiddenValue = str_repeat("Marriage Certificate", strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $item["copy_marriage_certificate"] ?>">View</button>
                                                            </div>
                                                        </div>
                                                        <div class="file-path" id="psa_cenomar_photocopy_groom_path"
                                                            style="display: none;">
                                                            <?= $item["copy_marriage_certificate"] ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <!-- PROCESS DECLINE BUTTON -->
                                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                                    data-target="#declineModal_<?php echo $item['id']; ?>">
                                                    Decline
                                                </button>
                                                <!-- PROCESS APPROVE BUTTON -->
                                                <button type="button" class="btn btn-success"
                                                    onclick="sendapproveEmailAndApprove(<?php echo $item['id']; ?>)">
                                                    Approve and Send Email
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

                    <!-- MODAL DECLINE -->
                    <?php foreach ($inventory as $declineItem) { 
                             if ($declineItem['status_id'] == 1) { ?>
                    <div class="modal fade" id="declineModal_<?php echo $declineItem['id']; ?>" tabindex="-1"
                        role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 1000px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">
                                        REASON OF DECLINING
                                        (ID:
                                        <?php echo $declineItem['reference_id']; ?>)</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" action="baptismal_decline.php">
                                    <div class="modal-body">
                                        <div class="form-row">
                                            <div class="form-group col-md">
                                                <label for="reason">Reason:</label>
                                                <select class="form-control"
                                                    id="reason_<?php echo $declineItem['id']; ?>" name="reason">
                                                    <option value="Incomplete or Inaccurate Information">
                                                        Incomplete or Inaccurate Information
                                                    </option>
                                                    <option value="Documentation Issues">
                                                        Documentation Issues</option>
                                                    <option value="Scheduling Conflicts">
                                                        Scheduling Conflicts</option>
                                                    <option value="Failure to Comply with Church Policies">
                                                        Failure to Comply with Church
                                                        Policies
                                                    </option>
                                                    <option value="Issues with the Location or Venue">
                                                        Issues with the Location or Venue
                                                    </option>
                                                    <option value="Concerns about the Purpose or Intent">
                                                        Concerns about the Purpose or Intent
                                                    </option>
                                                    <option value="Overlapping Requests or Capacity Issues">
                                                        Overlapping Requests or Capacity
                                                        Issues
                                                    </option>
                                                    <option value="Unresolved Issues from Previous Interactions">
                                                        Unresolved Issues from Previous
                                                        Interactions</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md">
                                                <label for="remarks">Remarks:</label>
                                                <textarea rows="24" cols="50" class="form-control"
                                                    name="remarks"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success"
                                            onclick="declineBinyag(<?php echo $declineItem['id']; ?>)">OK</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php } ?>


                    <!-- APPROVE TAB -->
                    <div class="tab-pane fade" id="nav-Approved" role="tabpanel" aria-labelledby="nav-Approved-tab">
                        <br>
                        <!-- APPROVED TABLE -->
                        <table id="dataTableApprove" class="table table-striped table-responsive-lg" cellspacing="0"
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
                            <?php foreach ($inventory as $item) { 
                             if ($item['status_id'] == 2) { ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $item['reference_id']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['father_lastname']; ?>,
                                        <?php echo $item['child_first_name']; ?>
                                        <?php echo $item['mother_maiden_lastname']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['months']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['current_address']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['birthplace']; ?></td>
                                    <td class="text-center align-middle">
                                        <div class="">
                                            <span class=""><?php echo $item['date_component']; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $item['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn-sm btn-success btn-block mb-2" data-toggle="modal"
                                            data-target="#view_<?php echo $item['id']; ?>">
                                            <i class="fas fa-eye"></i>View
                                        </button>
                                    </td>
                                </tr>

                                <!-- MODAL APPLICATION -->
                                <div class="modal fade" id="view_<?php echo $item['id']; ?>" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document"
                                        style="max-width: 1000px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Application Form (ID:
                                                    <?php echo $item['reference_id']; ?>)</h5>
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
                                                            value="<?= $item["child_first_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_maiden_lastname">Mother's Maiden Last
                                                            Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["mother_maiden_lastname"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="father_lastname">Father's Last Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["father_lastname"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="birthdate">Birthdate:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["birthdate"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="birthplace">Birthplace:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["birthplace"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="months">Months:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["months"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="baptismal_date">Baptismal Date:</label>
                                                        <input type="text" class="form-control"
                                                        value="<?= date("F j, Y", strtotime($item["baptismal_date"])) ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="baptismal_time">Baptismal Time:</label>
                                                        <input type="text" class="form-control"
                                                        value="<?= date("h:i A", strtotime($item["baptismal_time"])) ?>" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="current_address">Current Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["current_address"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="father_name">Father's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["father_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="father_origin_place">Father's Origin Place:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["father_origin_place"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="mother_maiden_fullname">Mother's Maiden Full
                                                            Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["mother_maiden_fullname"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_origin_place">Mother's Origin Place:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["mother_origin_place"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="marriage">Marriage:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["marriage"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="marriage_location">Marriage Location:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["marriage_location"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="godfather">Godfather:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godfather"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godfather_age">Godfather's Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godfather_age"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godfather_religion">Godfather's Religion:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godfather_religion"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godfather_address">Godfather's Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godfather_address"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="godmother">Godmother:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godmother"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godmother_age">Godmother's Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godmother_age"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godmother_religion">Godmother's Religion:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godmother_religion"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godmother_address">Godmother's Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godmother_address"] ?>" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="client_name">Client's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["client_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="client_relationship">Client's Relationship:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["client_relationship"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="client_contact_number">Client's Contact
                                                            Number:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["client_contact_number"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="psa_cenomar_photocopy_groom">Copy of Birth
                                                            Certificate:</label>
                                                        <?php
                                                        $url = $item["copy_birth_certificate"];
                                                        $hiddenValue = str_repeat("Birth Certificate", strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $item["copy_birth_certificate"] ?>">View</button>
                                                            </div>
                                                        </div>
                                                        <div class="file-path" id="psa_cenomar_photocopy_groom_path"
                                                            style="display: none;">
                                                            <?= $item["copy_birth_certificate"] ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="copy_marriage_certificate">Copy of Marriage
                                                            Certificate:</label>
                                                        <?php
                                                        $url = $item["copy_marriage_certificate"];
                                                        $hiddenValue = str_repeat("Marriage Certificate", strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $item["copy_marriage_certificate"] ?>">View</button>
                                                            </div>
                                                        </div>
                                                        <div class="file-path" id="psa_cenomar_photocopy_groom_path"
                                                            style="display: none;">
                                                            <?= $item["copy_marriage_certificate"] ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-success"
                                                    onclick="sendcompleteEmailAndComplete(<?php echo $item['id']; ?>)">
                                                    Complete and Send Email
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

                    <!-- COMPLETE TAB -->
                    <div class="tab-pane fade" id="nav-completed" role="tabpanel" aria-labelledby="nav-completed-tab">
                        <br>
                        <!-- COMPLETE TABLE -->
                        <table id="dataTableComplete" class="table table-striped table-responsive-lg" cellspacing="0"
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
                            <?php foreach ($inventory as $completeitem) { 
                             if ($completeitem['status_id'] == 3) { ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $completeitem['reference_id']; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php echo $completeitem['father_lastname']; ?>,
                                        <?php echo $completeitem['child_first_name']; ?>
                                        <?php echo $completeitem['mother_maiden_lastname']; ?></td>
                                    <td class="text-center align-middle"><?php echo $completeitem['months']; ?></td>
                                    <td class="text-center align-middle"><?php echo $completeitem['current_address']; ?>
                                    </td>
                                    <td class="text-center align-middle"><?php echo $completeitem['birthplace']; ?></td>
                                    <td class="text-center align-middle">
                                        <div class="">
                                            <span class=""><?php echo $completeitem['date_component']; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $completeitem['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn-sm btn-success btn-block mb-2" data-toggle="modal"
                                            data-target="#view_<?php echo $completeitem['id']; ?>">
                                            <i class="fas fa-eye"></i>View
                                        </button>

                                    </td>
                                </tr>

                                <!-- MODAL APPLICATION -->
                                <div class="modal fade" id="view_<?php echo $completeitem['id']; ?>" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document"
                                        style="max-width: 1000px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Application Form (ID:
                                                    <?php echo $completeitem['reference_id']; ?>)</h5>
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
                                                            value="<?= $completeitem["child_first_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_maiden_lastname">Mother's Maiden Last
                                                            Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["mother_maiden_lastname"] ?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="father_lastname">Father's Last Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["father_lastname"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="birthdate">Birthdate:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["birthdate"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="birthplace">Birthplace:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["birthplace"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="months">Months:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["months"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="baptismal_date">Baptismal Date:</label>
                                                        <input type="text" class="form-control"
                                                        value="<?= date("F j, Y", strtotime($completeitem["baptismal_date"])) ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="baptismal_time">Baptismal Time:</label>
                                                        <input type="text" class="form-control"
                                                        value="<?= date("h:i A", strtotime($completeitem["baptismal_time"])) ?>" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="current_address">Current Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["current_address"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="father_name">Father's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["father_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="father_origin_place">Father's Origin Place:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["father_origin_place"] ?>"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="mother_maiden_fullname">Mother's Maiden Full
                                                            Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["mother_maiden_fullname"] ?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_origin_place">Mother's Origin Place:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["mother_origin_place"] ?>"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="marriage">Marriage:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["marriage"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="marriage_location">Marriage Location:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["marriage_location"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="godfather">Godfather:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["godfather"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godfather_age">Godfather's Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["godfather_age"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godfather_religion">Godfather's Religion:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["godfather_religion"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godfather_address">Godfather's Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["godfather_address"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="godmother">Godmother:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["godmother"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godmother_age">Godmother's Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["godmother_age"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godmother_religion">Godmother's Religion:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["godmother_religion"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godmother_address">Godmother's Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["godmother_address"] ?>" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="client_name">Client's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["client_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="client_relationship">Client's Relationship:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["client_relationship"] ?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="client_contact_number">Client's Contact
                                                            Number:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["client_contact_number"] ?>"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="psa_cenomar_photocopy_groom">Copy of Birth
                                                            Certificate:</label>
                                                        <?php
                                                        $url = $completeitem["copy_birth_certificate"];
                                                        $hiddenValue = str_repeat("Birth Certificate", strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $completeitem["copy_birth_certificate"] ?>">View</button>
                                                            </div>
                                                        </div>
                                                        <div class="file-path" id="psa_cenomar_photocopy_groom_path"
                                                            style="display: none;">
                                                            <?= $completeitem["copy_birth_certificate"] ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="copy_marriage_certificate">Copy of Marriage
                                                            Certificate:</label>
                                                        <?php
                                                        $url = $completeitem["copy_marriage_certificate"];
                                                        $hiddenValue = str_repeat("Marriage Certificate", strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $completeitem["copy_marriage_certificate"] ?>">View</button>
                                                            </div>
                                                        </div>
                                                        <div class="file-path" id="psa_cenomar_photocopy_groom_path"
                                                            style="display: none;">
                                                            <?= $completeitem["copy_marriage_certificate"] ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                                    OK
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
                    <!-- DECLINE TAB -->
                    <div class="tab-pane fade" id="nav-decline" role="tabpanel" aria-labelledby="nav-decline-tab">
                        <table id="dataTableDecline" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>Reference ID</th>
                                    <th>Child's Name</th>
                                    <th>Months</th>
                                    <th>Reason</th>
                                    <th>Remarks</th>
                                    <th>Date Applied</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($inventory as $itemdecline) { 
                             if ($itemdecline['status_id'] == 4) { ?>
                                <tr>

                                    <td class="text-center align-middle"><?php echo $itemdecline['reference_id']; ?>
                                    </td>
                                    <td class="text-center align-middle"><?php echo $itemdecline['father_lastname']; ?>,
                                        <?php echo $itemdecline['child_first_name']; ?>
                                        <?php echo $itemdecline['mother_maiden_lastname']; ?></td>
                                    <td class="text-center align-middle"><?php echo $itemdecline['months']; ?></td>
                                    </td>
                                    <td class="text-center align-middle"><?php echo $itemdecline['reason']; ?></td>
                                    <td class="text-center align-middle"><?php echo $itemdecline['remarks']; ?></td>
                                    <td class="text-center align-middle">
                                        <div class="">
                                            <span class=""><?php echo $itemdecline['date_component']; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $itemdecline['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn-sm btn-success btn-block mb-2" data-toggle="modal"
                                            data-target="#view_<?php echo $itemdecline['id']; ?>">
                                            <i class="fas fa-eye"></i>View
                                        </button>
                                    </td>
                                </tr>

                                <!-- MODAL APPLICATION -->
                                <div class="modal fade" id="view_<?php echo $itemdecline['id']; ?>" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document"
                                        style="max-width: 1000px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Application Form (ID:
                                                    <?php echo $itemdecline['reference_id']; ?>)</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                            <p style="color: red;">Decline because of <?php echo $itemdecline['reason']; ?></p>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="child_first_name">Child's First Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["child_first_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_maiden_lastname">Mother's Maiden Last
                                                            Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["mother_maiden_lastname"] ?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="father_lastname">Father's Last Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["father_lastname"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="birthdate">Birthdate:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["birthdate"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="birthplace">Birthplace:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["birthplace"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="months">Months:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["months"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="baptismal_date">Baptismal Date:</label>
                                                        <input type="text" class="form-control"
                                                        value="<?= date("F j, Y", strtotime($itemdecline["baptismal_date"])) ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="baptismal_time">Baptismal Time:</label>
                                                        <input type="text" class="form-control"
                                                        value="<?= date("h:i A", strtotime($itemdecline["baptismal_time"])) ?>" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="current_address">Current Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["current_address"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="father_name">Father's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["father_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="father_origin_place">Father's Origin Place:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["father_origin_place"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="mother_maiden_fullname">Mother's Maiden Full
                                                            Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["mother_maiden_fullname"] ?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_origin_place">Mother's Origin Place:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["mother_origin_place"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="marriage">Marriage:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["marriage"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="marriage_location">Marriage Location:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["marriage_location"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="godfather">Godfather:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["godfather"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godfather_age">Godfather's Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["godfather_age"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godfather_religion">Godfather's Religion:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["godfather_religion"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godfather_address">Godfather's Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["godfather_address"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="godmother">Godmother:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["godmother"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godmother_age">Godmother's Age:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["godmother_age"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godmother_religion">Godmother's Religion:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["godmother_religion"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godmother_address">Godmother's Address:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["godmother_address"] ?>" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="client_name">Client's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["client_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="client_relationship">Client's Relationship:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["client_relationship"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="client_contact_number">Client's Contact
                                                            Number:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["client_contact_number"] ?>"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="psa_cenomar_photocopy_groom">Copy of Birth
                                                            Certificate:</label>
                                                        <?php
                                                        $url = $itemdecline["copy_birth_certificate"];
                                                        $hiddenValue = str_repeat("Birth Certificate", strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $itemdecline["copy_birth_certificate"] ?>">View</button>
                                                            </div>
                                                        </div>
                                                        <div class="file-path" id="psa_cenomar_photocopy_groom_path"
                                                            style="display: none;">
                                                            <?= $itemdecline["copy_birth_certificate"] ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="copy_marriage_certificate">Copy of Marriage
                                                            Certificate:</label>
                                                        <?php
                                                        $url = $itemdecline["copy_marriage_certificate"];
                                                        $hiddenValue = str_repeat("Marriage Certificate", strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $itemdecline["copy_marriage_certificate"] ?>">View</button>
                                                            </div>
                                                        </div>
                                                        <div class="file-path" id="psa_cenomar_photocopy_groom_path"
                                                            style="display: none;">
                                                            <?= $itemdecline["copy_marriage_certificate"] ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                                    OK
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

        <!-- <span class="close">&times;</span> -->
        <img class="modal_img" id="modalImage">
    </div>

</body>

<!-- APPRVED JS -->
<script>
function sendapproveEmailAndApprove(itemId) {
    if (confirm('Are you sure you want to mark the application as approve?')) {
        sendApproveEmail(itemId);
    }
}

function sendApproveEmail(itemId) {
    $.ajax({
        type: 'GET',
        url: 'send_baptismal_approve.php',
        data: {
            id: itemId
        },
        success: function(response) {
            console.log('Email sent:', response);
            alert('Approval email has been sent!');
            ApproveBinyag(itemId); // Call completeMass function after sending the email
        },
        error: function(xhr, status, error) {
            console.error('Failed to send email:', status, error);
            alert('Failed to send approval email. Please try again.');
        }
    });
}

function ApproveBinyag(itemId) {
    $.ajax({
        type: 'POST',
        url: 'baptismal_approve.php',
        data: {
            itemId: itemId
        },
        success: function(response) {
            console.log('AJAX Success:', response);
            if (response.trim() === 'success') {
                alert('The application marked as approve successfully!');
                window.location.href = 'baptismal.php?id=' + itemId;
            } else {
                alert('Failed to mark the application as Approve. Please try again.');
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
        }
    });
}
</script>
<script>
function declineBinyag(dataId) {
    console.log('Item ID:', dataId);
    $.ajax({
        type: 'POST',
        url: 'baptismal_decline.php',
        data: {
            dataId: dataId,
            reason: $('#reason_' + dataId).val(),
            remarks: $('textarea[name="remarks"]').val()
        },
        success: function(response) {
            console.log('AJAX Success:', response);
            if (response.trim() === 'success') {
                alert('The application declined successfully!');
                alert('Please Visit Decline tab For Sending Decline Email Thank You');
                sendDeclineEmail(dataId);
                location.reload();
            } else {
                alert('Failed to decline the application. Please try again.');
                // alert('binyag declined successfully!');
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
        }
    });
}
function sendDeclineEmail(dataId) {
    $.ajax({
        type: 'GET',
        url: 'send_baptismal_decline.php',
        data: {
            id: dataId
        },
        success: function(response) {
            console.log('Email sent:', response);
            alert('Decline email has been sent!'); // Call completeMass function after sending the email
        },
        error: function(xhr, status, error) {
            console.error('Failed to send email:', status, error);
            alert('Failed to send Decline email. Please try again.');
        }
    });
}
</script>

<script>
function sendcompleteEmailAndComplete(itemId) {
    if (confirm('Are you sure you want to mark the application as complete?')) {
        sendCompletedEmail(itemId);
    }
}

function sendCompletedEmail(itemId) {
    $.ajax({
        type: 'GET',
        url: 'send_baptismal_complete.php',
        data: {
            id: itemId
        },
        success: function(response) {
            console.log('Email sent:', response);
            alert('Completion email has been sent!');
            completeBinyag(itemId); // Call completeMass function after sending the email
        },
        error: function(xhr, status, error) {
            console.error('Failed to send email:', status, error);
            alert('Failed to send completion email. Please try again.');
        }
    });
}

function completeBinyag(itemId) {
    $.ajax({
        type: 'POST',
        url: 'baptismal_complete.php',
        data: {
            itemId: itemId
        },
        success: function(response) {
            console.log('AJAX Success:', response);
            if (response.trim() === 'success') {
                alert('The application marked as complete successfully!');
                window.location.href = 'baptismal.php?id=' + itemId;
            } else {
                alert('Failed to mark the application as complete. Please try again.');
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
        }
    });
}
</script>

<!-- MODAL JS -->

<script>
$(document).ready(function() {
    $('#dataTableProcess').DataTable();
    $('#dataTableApprove').DataTable();
    $('#dataTableComplete').DataTable();
    $('#dataTableDecline').DataTable();

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
<!-- OTHERS JS -->
<script>
$('#reason').change(function() {
    var selectedValue = $(this).val();

    if (selectedValue === 'Others') {
        $('#otherReasonContainer').show();
    } else {
        $('#otherReasonContainer').hide();
    }
});
</script>

</html>


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