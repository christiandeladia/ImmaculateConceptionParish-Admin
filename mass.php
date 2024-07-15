<?php
require "process/connect.php";
if (!isset($_SESSION['auth_admin'])) {
    header("location: index.php");
    exit;
}
?>


<?php
if (isset($_SESSION['auth_login'])) {
    $auth = $_SESSION['auth_login'];
    $auth_full_name = $auth['first_name'] . $auth['last_name'];
}
$result = mysqli_query($conn, "SELECT * FROM mass ORDER BY id DESC LIMIT 1");

if (!$result) {
    die("Error in query: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
?>

<?php include 'process/formula.php';?>

<?php
function getMassData()
{
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%M %d, %Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM mass";
    $inventory = [];
    $reference_id = uniqid();
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
$inventory = getMassData();

function getMassCounts()
{
    global $pdo;
    $query = "SELECT status_id, COUNT(*) AS count FROM mass GROUP BY status_id";
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

$massCounts = getMassCounts();

// Initialize counts
$total_mass = $total_mass_approve = $total_mass_complete = $total_mass_decline = 0;

// Process the counts
foreach ($massCounts as $count) {
    switch ($count['status_id']) {
        case 1:
            $total_mass = $count['count'];
            break;
        case 2:
            $total_mass_approve = $count['count'];
            break;
        case 3:
            $total_mass_complete = $count['count'];
            break;
        case 4:
            $total_mass_decline = $count['count'];
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
    <title>Mass | Admin</title>
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
</style>
<body>
    <?php $activePage = 'services';include 'nav.php';?>
    <div></div>
    <div class="product">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Mass</li>
                <li class="breadcrumb-item active">Application Form</li>
            </ol>
        </nav>
        <!-- header -->
        <div class=".container-fluid mt-4 card mb-2 bg-light shadow" style=" margin: 0 3%">
            <div class="card-body">
                <section class="p-1 z-depth-1">
                    <h1 class="text-center font-weight-bold mb-4">Mass</h1>
                    <div class="form">
                        <ul class="tab-group">
                            <li class="tab-left active"><a href="#ApplicationForm">Application Form</a></li>
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
                            <?php if ($total_mass > 0): ?>
                            <span class="badge badge-primary rounded-circle p-2"><?php echo $total_mass; ?></span>
                            <?php endif;?>
                        </a>

                        <a class="nav-item nav-link" id="nav-Approved-tab" data-toggle="tab" href="#nav-Approved"
                            role="tab" aria-controls="nav-Approved" aria-selected="false">Approved
                            <?php if ($total_mass_approve > 0): ?>
                            <span
                                class="badge badge-primary rounded-circle p-2"><?php echo $total_mass_approve; ?></span>
                            <?php endif;?></a>

                        <a class="nav-item nav-link" id="nav-completed-tab" data-toggle="tab" href="#nav-completed"
                            role="tab" aria-controls="nav-completed" aria-selected="false">Completed
                            <?php if ($total_mass_complete > 0): ?>
                            <span
                                class="badge badge-primary rounded-circle p-2"><?php echo $total_mass_complete; ?></span>
                            <?php endif;?>
                        </a>

                        <a class="nav-item nav-link" id="nav-decline-tab" data-toggle="tab" href="#nav-decline"
                            role="tab" aria-controls="nav-decline" aria-selected="false">Decline
                            <?php if ($total_mass_decline > 0): ?>
                            <span
                                class="badge badge-primary rounded-circle p-2"><?php echo $total_mass_decline; ?></span>
                            <?php endif;?>
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
                                    <th>Purpose</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Date Applied</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($inventory as $item) {
                                if ($item['status_id'] == 1) {?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $item['reference_id']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['purpose']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['name']; ?></td>
                                    <td class="text-center align-middle">
                                        <?php echo date('F j, Y', strtotime($item['date'])); ?></td>
                                    <td class="text-center align-middle">
                                        <?php echo date('h:m a', strtotime($item['time'])); ?></td>
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
                                                        <label for="purpose">Purpose:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?=$item["purpose"]?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="name">Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?=$item["name"]?>" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="date">Date:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?=date('M d, Y', strtotime($item["date"]))?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="date">Time:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?=date('h:m a', strtotime($item["time"]))?>"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="psa_cenomar_photocopy_groom">Payment:</label>
                                                        <?php
                                                        $url = $item["reciept"];
                                                        $hiddenValue = str_repeat("Reciept", strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $item["reciept"] ?>">View</button>
                                                            </div>
                                                        </div>
                                                        <div class="file-path" id="reciept"
                                                            style="display: none;">
                                                            <?= $item["reciept"] ?>
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
                                                    onclick="sendApprovalEmailAndApprove(<?php echo $item['id']; ?>)">
                                                    Approved And Send Email
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                                <?php
                                }?>

                            </tbody>
                        </table>
                    </div>

                    <?php foreach ($inventory as $item) {?>
                    <div class="modal fade" id="declineModal_<?php echo $item['id']; ?>" tabindex="-1"
                        role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 1000px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">
                                        REASON OF DECLINING
                                        (ID:
                                        <?php echo $item['reference_id']; ?>)</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" action="mass_decline.php">
                                    <div class="modal-body">
                                        <div class="form-row">
                                            <div class="form-group col-md">
                                                <label for="reason">Reason:</label>
                                                <select class="form-control"
                                                    id="reason_<?php echo $item['id']; ?>" name="reason">
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
                                                <textarea rows="10" cols="50" class="form-control"
                                                    name="remarks"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success"
                                            onclick="declineMass(<?php echo $item['id']; ?>)">OK</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php }?>
       

                    <!-- APPROVE TAB -->
                    <div class="tab-pane fade" id="nav-Approved" role="tabpanel" aria-labelledby="nav-Approved-tab">
                        <br>
                        <!-- Button to trigger the modal -->
                        <a href="forms/mass_pdf.php">
                            <button type="button" class="btn btn-primary">
                                Generate
                            </button>
                        </a>
                        <!-- APPROVED TABLE -->
                        <table id="dataTableApprove" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>Reference ID</th>
                                    <th>Purpose</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Date Applied</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inventory as $item) {
                            if ($item['status_id'] == 2) {?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $item['reference_id']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['purpose']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['name']; ?></td>
                                    <td class="text-center align-middle">
                                        <?php echo date('F j, Y', strtotime($item['date'])); ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php echo date('h:m a', strtotime($item['time'])); ?>
                                    </td>
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
                                                        <label for="purpose">Purpose:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?=$item["purpose"]?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="name">Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?=$item["name"]?>" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="date">Date:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?=date('M d, Y', strtotime($item["date"]))?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="date">Time:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?=date('h:m a', strtotime($item["time"]))?>"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="psa_cenomar_photocopy_groom">Payment:</label>
                                                        <?php
                                                        $url = $item["reciept"];
                                                        $hiddenValue = str_repeat("Reciept", strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $item["reciept"] ?>">View</button>
                                                            </div>
                                                        </div>
                                                        <div class="file-path" id="reciept"
                                                            style="display: none;">
                                                            <?= $item["reciept"] ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-success"
                                                    onclick="sendcompleteEmailAndComplete(<?php echo $item['id']; ?>)">
                                                    Complete And Send Email
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                                <?php
}?>

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
                                    <th>Purpose</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Date Applied</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inventory as $item) {
    if ($item['status_id'] == 3) {?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $item['reference_id']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['purpose']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['name']; ?></td>
                                    <td class="text-center align-middle">
                                        <?php echo date('F j, Y', strtotime($item['date'])); ?></td>
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
                                                        <label for="purpose">Purpose:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?=$item["purpose"]?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="name">Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?=$item["name"]?>" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="date">Date:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?=date('M d, Y', strtotime($item["date"]))?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="date">Time:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?=date('h:m a', strtotime($item["time"]))?>"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="psa_cenomar_photocopy_groom">Payment:</label>
                                                        <?php
                                                        $url = $item["reciept"];
                                                        $hiddenValue = str_repeat("Reciept", strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $item["reciept"] ?>">View</button>
                                                            </div>
                                                        </div>
                                                        <div class="file-path" id="reciept"
                                                            style="display: none;">
                                                            <?= $item["reciept"] ?>
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
                                <?php }?>

                                <?php
}?>
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
                                    <th>Purpose</th>
                                    <th>Name</th>
                                    <th>Reason</th>
                                    <th>Remarks</th>
                                    <th>Date Applied</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inventory as $item) {
                                    if ($item['status_id'] == 4) {?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $item['reference_id']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['purpose']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['name']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['reason']; ?></td>
                                    <td class="text-center align-middle"><?php echo $item['remarks']; ?></td>
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
                                                <p style="color: red;">Decline because of <?php echo $item['reason']; ?>
                                                </p>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="purpose">Purpose:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?=$item["purpose"]?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="name">Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?=$item["name"]?>" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="date">Date:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?=date('M d, Y', strtotime($item["date"]))?>"
                                                            disabled>
                                                    </div>
                                                    <div class="form-group col-md">
                                                        <label for="date">Time:</label>
                                                        <input type="text" class="form-control"
                                                            value="<?=date('h:m a', strtotime($item["time"]))?>"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md">
                                                        <label for="psa_cenomar_photocopy_groom">Payment:</label>
                                                        <?php
                                                        $url = $item["reciept"];
                                                        $hiddenValue = str_repeat("Reciept", strlen(1));
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="<?= $hiddenValue ?>" disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary view-btn"
                                                                    data-url="<?= $item["reciept"] ?>">View</button>
                                                            </div>
                                                        </div>
                                                        <div class="file-path" id="reciept"
                                                            style="display: none;">
                                                            <?= $item["reciept"] ?>
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
                                <?php }?>
                                <?php }?>
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
<!-- APPRVED JS -->
<script>
function sendApprovalEmailAndApprove(itemId) {
    if (confirm('Are you sure you want to approve the application?')) {
        sendApprovalEmail(itemId);
    }
}

function sendApprovalEmail(itemId) {
    $.ajax({
        type: 'GET',
        url: 'send_mass_approve.php',
        data: {
            id: itemId
        },
        success: function(response) {
            console.log('Email sent:', response);
            alert('Approval email has been sent!');
            approveMass(itemId); // Call approveWedding function after sending the email
        },
        error: function(xhr, status, error) {
            console.error('Failed to send email:', status, error);
            alert('Failed to send approval email. Please try again.');
        }
    });
}

function approveMass(itemId) {
    $.ajax({
        type: 'POST',
        url: 'mass_approve.php',
        data: {
            itemId: itemId
        },
        success: function(response) {
            console.log('AJAX Success:', response);
            if (response.trim() === 'success') {
                alert('The application approved successfully!');
                window.location.href = 'mass.php?id=' + itemId;
            } else {
                alert('Failed to approve the application. Please try again.');
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
function declineMass(dataId) {
    console.log('Item ID:', dataId);
    $.ajax({
        type: 'POST',
        url: 'mass_decline.php',
        data: {
            dataId: dataId,
            reason: $('#reason_' + dataId).val(),
            remarks: $('textarea[name="remarks"]').val()
        },
        success: function(response) {
            console.log('AJAX Success:', response);
            if (response.trim() === 'success') {
                alert('The application declined successfully!');
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

function sendDeclineEmail(dataId) {
    $.ajax({
        type: 'GET',
        url: 'send_mass_decline.php',
        data: {
            id: dataId
        },
        success: function(response) {
            console.log('Email sent:', response);
            alert('Decline email has been sent!'); // Call completeMass function after sending the email
        },
        error: function(xhr, status, error) {
            console.error('Failed to send email:', status, error);
            alert('Failed to send completion email. Please try again.');
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
        url: 'send_mass_complete.php',
        data: {
            id: itemId
        },
        success: function(response) {
            console.log('Email sent:', response);
            alert('Completion email has been sent!');
            completeMass(itemId); // Call completeMass function after sending the email
        },
        error: function(xhr, status, error) {
            console.error('Failed to send email:', status, error);
            alert('Failed to send completion email. Please try again.');
        }
    });
}

function completeMass(itemId) {
    $.ajax({
        type: 'POST',
        url: 'mass_complete.php',
        data: {
            itemId: itemId
        },
        success: function(response) {
            console.log('AJAX Success:', response);
            if (response.trim() === 'success') {
                alert('The application marked as complete successfully!');
                window.location.href = 'mass.php?id=' + itemId;
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
.nav-fill>.nav-link,
.nav-fill .nav-item {
    flex: none !important;
    text-align: center;
    width: 200px !important;
}
</style>

</html>