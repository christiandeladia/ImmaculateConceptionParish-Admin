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
$result = mysqli_query($conn, "SELECT * FROM binyag_request_certificate ORDER BY id DESC LIMIT 1");

if (!$result) {
    die("Error in query: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
?>

<?php include 'process/formula.php';?>

<?php 
function getbinyag_request_certificateData() {
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%M %d, %Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM binyag_request_certificate";
    $inventory = [];
    $reference_id = uniqid();
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
$inventory = getbinyag_request_certificateData();

function getbinyag_request_certificateCounts() {
    global $pdo;
    $query = "SELECT status_id, COUNT(*) AS count FROM binyag_request_certificate GROUP BY status_id";
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

$binyag_request_certificateCounts = getbinyag_request_certificateCounts();

// Initialize counts
$total_binyag_request_certificate = $total_binyag_request_certificate_approve = $total_binyag_request_certificate_complete = $total_binyag_request_certificate_decline = 0;

// Process the counts
foreach ($binyag_request_certificateCounts as $count) {
    switch ($count['status_id']) {
        case 1:
            $total_binyag_request_certificate = $count['count'];
            break;
        case 2:
            $total_binyag_request_certificate_approve = $count['count'];
            break;
        case 3:
            $total_binyag_request_certificate_complete = $count['count'];
            break;
        case 4:
            $total_binyag_request_certificate_decline = $count['count'];
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
    <title>Baptismal Certificate | Admin</title>
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

/* style for navtabs */

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
    /* background: rgba(160, 179, 176, 0.25); */
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
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Baptismal Certificate</li>
                <li class="breadcrumb-item active">Application Form</li>
            </ol>
        </nav>
        <!-- header -->
        <div class=".container-fluid mt-4 card mb-2 bg-light shadow" style=" margin: 0 3%">
            <div class="card-body">
                <section class="p-1 z-depth-1">
                    <h1 class="text-center font-weight-bold mb-4">Baptismal Certificate</h1>
                    <div class="form">
                        <ul class="tab-group">
                            <li class="tab-left"><a href="baptismal.php">Application Form</a></li>
                            <li class="tab-right active"><a href="certificate_baptismal.php">Requested Certificate</a>
                            </li>
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
                            <?php if ($total_binyag_request_certificate > 0): ?>
                            <span
                                class="badge badge-primary rounded-circle p-2"><?php echo $total_binyag_request_certificate; ?></span>
                            <?php endif; ?>
                        </a>

                        <a class="nav-item nav-link" id="nav-Approved-tab" data-toggle="tab" href="#nav-Approved"
                            role="tab" aria-controls="nav-Approved" aria-selected="false">Approved
                            <?php if ($total_binyag_request_certificate_approve > 0): ?>
                            <span
                                class="badge badge-primary rounded-circle p-2"><?php echo $total_binyag_request_certificate_approve; ?></span>
                            <?php endif; ?></a>

                        <a class="nav-item nav-link" id="nav-completed-tab" data-toggle="tab" href="#nav-completed"
                            role="tab" aria-controls="nav-completed" aria-selected="false">Completed
                            <?php if ($total_binyag_request_certificate_complete > 0): ?>
                            <span
                                class="badge badge-primary rounded-circle p-2"><?php echo $total_binyag_request_certificate_complete; ?></span>
                            <?php endif; ?>
                        </a>

                        <a class="nav-item nav-link" id="nav-decline-tab" data-toggle="tab" href="#nav-decline"
                            role="tab" aria-controls="nav-decline" aria-selected="false">Decline
                            <?php if ($total_binyag_request_certificate_decline > 0): ?>
                            <span
                                class="badge badge-primary rounded-circle p-2"><?php echo $total_binyag_request_certificate_decline; ?></span>
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
                                    <th>Child's Fullname</th>
                                    <th>Months</th>
                                    <th>Birthplace</th>
                                    <th>Purpose</th>
                                    <th>Date Applied</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inventory as $item) { 
                            if ($item['status_id'] == 1) {
                                // Check if there's any record in the 'binyag' table with the same child's name and parent's last names
                                $childFirstName = $item['child_first_name'];
                                $motherMaidenLastname = $item['mother_maiden_lastname'];
                                $fatherLastname = $item['father_lastname'];
                                $godmother = $item['godmother'];
                                $godfather = $item['godfather'];
                                $status = 'Not Available';
                                
                                // Assuming $pdo is your database connection
                                $query = "SELECT * FROM binyag WHERE child_first_name = ? AND mother_maiden_lastname = ? AND father_lastname = ? AND status_id = 3";
                                $statement = $pdo->prepare($query);
                                $statement->execute([$childFirstName, $motherMaidenLastname, $fatherLastname]);
                                $existingRecord = $statement->fetch(PDO::FETCH_ASSOC);

                                if ($existingRecord) {
                                    $status = 'Available';
                                }
                                ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $item['reference_id']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['child_first_name']; ?>
                                        <?php echo $item['mother_maiden_lastname']; ?>
                                        <?php echo $item['father_lastname']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['birthdate']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['birthplace']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['purpose']; ?></td>
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
                                            class="badge badge-<?php echo $status == 'Available' ? 'success' : 'danger'; ?> rounded-pill p-2">
                                            <?php echo $status; ?>
                                        </span>
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
                                                        <label for="child_first_name">Child's Firstname:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["child_first_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_maiden_lastname">Mother's Maiden
                                                            Lastname:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["mother_maiden_lastname"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="father_lastname">Father's Lastname:</label>
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
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="father_fullname">Father's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["father_fullname"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_maidenname">Mother's Maiden Full
                                                            Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["mother_maidenname"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="purpose">Purpose:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["purpose"] ?>" disabled>
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
                                                <button type="submit" class="btn btn-success" data-toggle="modal"
                                                    data-target="#approveModal_<?php echo $item['id']; ?>">
                                                    Approve
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

                    <!-- APPROVE MODAL -->
                    <?php foreach ($inventory as $item) { 
                             if ($item['status_id'] == 1) { ?>
                    <div class="modal fade" id="approveModal_<?php echo $item['id']; ?>" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document"
                            style="max-width: 1000px; max-height: 600px;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">
                                        Request Baptismal Certificate
                                        (ID:
                                        <?php echo $item['reference_id']; ?>)</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" action="certificate_baptismal_approve.php">
                                    <div class="modal-body">
                                        <div class="form-row">
                                            <div class="form-group col-md">
                                                <label for="baptismal_date">Baptismal Date:</label>
                                                <input type="date" class="form-control" name="baptismal_date" required>
                                            </div>
                                            <div class="form-group col-md">
                                                <label for="baptized_by">Baptismal By:</label>
                                                <input type="text" class="form-control" name="baptized_by" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md">
                                                <label for="godfather">Godfather:</label>
                                                <input type="text" value="<?= $godfather; ?>" class="form-control" name="godfather" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md">
                                                <label for="godmother">Godmother:</label>
                                                <input type="text" value="<?= $godmother; ?>" class="form-control" name="godmother" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md">
                                                <label for="line_no">Line Number:</label>
                                                <input type="text" class="form-control" name="line_no" required>
                                            </div>
                                            <div class="form-group col-md">
                                                <label for="book_no">Book Number:</label>
                                                <input type="text" class="form-control" name="book_no" required>
                                            </div>
                                            <div class="form-group col-md">
                                                <label for="page_no">Page Number:</label>
                                                <input type="text" class="form-control" name="page_no" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md">
                                                <label for="issued">Issued:</label>
                                                <input type="text" value="<?= $item["purpose"] ?>" class="form-control" name="issued" required>
                                            </div>
                                            <div class="form-group col-md">
                                                <label for="fors">For:</label>
                                                <input type="text" class="form-control" name="fors" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success"
                                            onclick="approveBinyagRequestCertificate(<?php echo $item['id']; ?>)">OK</button>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php } ?>


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
                                <form method="POST" action="certificate_baptismal_decline.php">
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
                                                <textarea rows="10" cols="20" class="form-control"
                                                    name="remarks"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success"
                                            onclick="declineBinyagRequestCertificate(<?php echo $declineItem['id']; ?>)">OK</button>
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
                                    <th>Child's Fullname</th>
                                    <th>Months</th>
                                    <th>Birthplace</th>
                                    <th>Purpose</th>
                                    <th>Date Applied</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inventory as $item) { 
                             if ($item['status_id'] == 2) { ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $item['reference_id']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['child_first_name']; ?>
                                        <?php echo $item['mother_maiden_lastname']; ?>
                                        <?php echo $item['father_lastname']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['birthdate']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['birthplace']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['purpose']; ?></td>
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
                                        <button class="generate-button btn-sm btn-info btn-block mb-2"
                                            data-id="<?php echo $item['id']; ?>"><i class="fa fa-file"></i>
                                            Generate</button>
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
                                                    <label for="child_first_name">Child's Firstname:</label>
                                                    <input type="text" class="form-control"
                                                        value="<?= $item["child_first_name"] ?>" disabled>
                                                </div>
                                                <div class="form-group col-md">
                                                    <label for="mother_maiden_lastname">Mother's Maiden
                                                        Lastname:</label>
                                                    <input type="text" class="form-control"
                                                        value="<?= $item["mother_maiden_lastname"] ?>" disabled>
                                                </div>
                                                <div class="form-group col-md">
                                                    <label for="father_lastname">Father's Lastname:</label>
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
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="father_fullname">Father's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["father_fullname"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_maidenname">Mother's Maiden Full
                                                            Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["mother_maidenname"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="purpose">Purpose:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["purpose"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="baptismal_date">Baptismal Date:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["baptismal_date"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="baptized_by">Baptismal By:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["baptized_by"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="godfather">Godfather:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godfather"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godmother">Godmother:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["godmother"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="line_no">Line Number:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["line_no"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="book_no">Book Number:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["book_no"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="page_no">Page Number:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["page_no"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="issued">Issued:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["issued"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="fors">For:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $item["fors"] ?>" disabled>
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
                                    <th>Child's Fullname</th>
                                    <th>Months</th>
                                    <th>Birthplace</th>
                                    <th>Purpose</th>
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
                                        <?php echo $completeitem['child_first_name']; ?>
                                        <?php echo $completeitem['mother_maiden_lastname']; ?>
                                        <?php echo $completeitem['father_lastname']; ?></td>
                                    <td class="text-center align-middle"><?php echo $completeitem['birthdate']; ?></td>
                                    <td class="text-center align-middle"><?php echo $completeitem['birthplace']; ?>
                                    </td>
                                    <td class="text-center align-middle"><?php echo $completeitem['purpose']; ?></td>
                                    </td>
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
                                                        <label for="child_first_name">Child's Firstname:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["child_first_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_maiden_lastname">Mother's Maiden
                                                            Lastname:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["mother_maiden_lastname"] ?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="father_lastname">Father's Lastname:</label>
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
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="father_fullname">Father's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["father_fullname"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_maidenname">Mother's Maiden Full
                                                            Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["mother_maidenname"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="purpose">Purpose:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["purpose"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="baptismal_date">Baptismal Date:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["baptismal_date"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="baptized_by">Baptismal By:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["baptized_by"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="godfather">Godfather:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["godfather"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="godmother">Godmother:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["godmother"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="line_no">Line Number:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["line_no"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="book_no">Book Number:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["book_no"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="page_no">Page Number:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $completeitem["page_no"] ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Close
                                                </button>
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
                    <!-- DECLINE TAB -->
                    <div class="tab-pane fade" id="nav-decline" role="tabpanel" aria-labelledby="nav-decline-tab">
                        <table id="dataTableDecline" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>Reference ID</th>
                                    <th>Child's Fullname</th>
                                    <th>Months</th>
                                    <th>Birthplace</th>
                                    <th>Purpose</th>
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
                                    <td class="text-center align-middle"><?php echo $itemdecline['child_first_name']; ?>
                                        <?php echo $itemdecline['mother_maiden_lastname']; ?>
                                        <?php echo $itemdecline['father_lastname']; ?></td>
                                    <td class="text-center align-middle"><?php echo $itemdecline['birthdate']; ?></td>
                                    <td class="text-center align-middle"><?php echo $itemdecline['birthplace']; ?>
                                    </td>
                                    <td class="text-center align-middle"><?php echo $itemdecline['purpose']; ?></td>
                                    </td>
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
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="child_first_name">Child's Firstname:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["child_first_name"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_maiden_lastname">Mother's Maiden
                                                            Lastname:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["mother_maiden_lastname"] ?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="father_lastname">Father's Lastname:</label>
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
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="father_fullname">Father's Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["father_fullname"] ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="mother_maidenname">Mother's Maiden Full
                                                            Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["mother_maidenname"] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="purpose">Purpose:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $itemdecline["purpose"] ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const generateButtons = document.querySelectorAll('.generate-button');
        generateButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const id = button.getAttribute('data-id');
                window.location.href = 'forms/baptism-cert.php?id=' + id;
            });
        });
    });
    </script>
    <script>
    // JavaScript to hide the modal application when the approve or decline modal is shown
    $('#approveModal_<?php echo $item['id']; ?>').on('show.bs.modal', function(e) {
        $('#view_<?php echo $item['id']; ?>').modal('hide');
    });

    $('#declineModal_<?php echo $declineItem['id']; ?>').on('show.bs.modal', function(e) {
        $('#view_<?php echo $item['id']; ?>').modal('hide');
    });
    </script>
</body>
<script>
function approveBinyagRequestCertificate(itemId) {
    console.log('Item ID:', itemId);
    $.ajax({
        type: 'POST',
        url: 'certificate_baptismal_approve.php',
        data: {
            itemId: itemId,
            baptismal_date: $('input[name="baptismal_date"]').val(),
            baptized_by: $('input[name="baptized_by"]').val(),
            godfather: $('input[name="godfather"]').val(),
            godmother: $('input[name="godmother"]').val(),
            line_no: $('input[name="line_no"]').val(),
            book_no: $('input[name="book_no"]').val(),
            page_no: $('input[name="page_no"]').val(),
            issued: $('input[name="issued"]').val(),
            fors: $('input[name="fors"]').val()
        },
        success: function(response) {
            console.log('AJAX Success:', response);
            if (response.trim() === 'success') {
                alert('The application approved successfully!');
                sendApprovalEmail(itemId);
                location.reload();
            } else {
                alert('Failed to approve the application. Please try again.');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
        }
    });
}

function sendApprovalEmail(itemId) {
    $.ajax({
        type: 'GET',
        url: 'send_baptismal_cert_approve.php',
        data: {
            id: itemId
        },
        success: function(response) {
            console.log('Email sent:', response);
            alert('Approval email has been sent!');
            // Call approveWedding function after sending the email
        },
        error: function(xhr, status, error) {
            console.error('Failed to send email:', status, error);
            alert('Failed to send approval email. Please try again.');
        }
    });
}
</script>

<script>
function sendDeclineEmail(dataId) {
    $.ajax({
        type: 'GET',
        url: 'send_baptismal_cert_decline.php',
        data: {
            id: dataId
        },
        success: function(response) {
            console.log('Email sent:', response);
            alert('Decline email has been sent!'); // Call completeMass function after sending the email
        },
        error: function(xhr, status, error) {
            console.error('Failed to send email:', status, error);
            alert('Failed to send decline email. Please try again.');
        }
    });
}

function declineBinyagRequestCertificate(dataId) {
    console.log('Item ID:', dataId);
    $.ajax({
        type: 'POST',
        url: 'certificate_baptismal_decline.php',
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
function sendcompleteEmailAndComplete(itemId) {
    if (confirm('Are you sure you want to mark the application as complete?')) {
        sendCompletedEmail(itemId);
    }
}

function sendCompletedEmail(itemId) {
    $.ajax({
        type: 'GET',
        url: 'send_baptismal_cert_complete.php',
        data: {
            id: itemId
        },
        success: function(response) {
            console.log('Email sent:', response);
            alert('Completion email has been sent!');
            completeBinyagRequestCertificate(itemId); // Call completeMass function after sending the email
        },
        error: function(xhr, status, error) {
            console.error('Failed to send email:', status, error);
            alert('Failed to send completion email. Please try again.');
        }
    });
}

function completeBinyagRequestCertificate(itemId) {
    console.log('Item ID:', itemId);
    $.ajax({
        type: 'POST',
        url: 'certificate_baptismal_complete.php',
        data: {
            itemId: itemId
        },
        success: function(response) {
            console.log('AJAX Success:', response);
            if (response.trim() === 'success') {
                alert('The application completed successfully!');
                location.reload();
            } else {
                alert('Failed to completed the application. Please try again.');
                // alert('binyag_request_certificate completed successfully!');
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