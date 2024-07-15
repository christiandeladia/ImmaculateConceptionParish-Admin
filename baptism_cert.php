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
function getBaptismal() {
    global $pdo;
    $query = "SELECT *, DATE_FORMAT(date_added, '%M %d, %Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM binyag_request_certificate WHERE status_id = 2";
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
$inventory = getBaptismal();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image/admin.ico">
    <title>BAPTISMAL - ADMIN</title>
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
</style>



<body>
    <?php 
$activePage = 'services'; 
include 'nav.php';
?>
    <div></div>
    <div class="product">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Baptismal</li>
                <li class="breadcrumb-item active">Requested Certificate</li>
            </ol>
        </nav>


        <div class=".container-fluid mt-4 card mb-2 bg-light shadow" style=" margin: 0 3%">
            <div class="card-body">
                <section class="p-1 z-depth-1">
                    <h1 class="text-center font-weight-bold mb-4">Baptismal</h1>
                    <div class="form">
                        <ul class="tab-group">
                            <li class="tab-left"><a href="baptismal.php">Application Form</a></li>
                            <li class="tab-right active"><a href="baptism_cert.php">Requested Certificate</a></li>
                        </ul>
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
                            <?php if ($total_baptismalrequest > 0): ?>
                            <span
                                class="badge badge-primary rounded-circle p-2"><?php echo $total_baptismalrequest; ?></span>
                            <?php endif; ?>
                        </a>
                        <a class="nav-item nav-link" id="nav-Approved-tab" data-toggle="tab" href="#nav-Approved"
                            role="tab" aria-controls="nav-Approved" aria-selected="false">Approved</a>
                        <a class="nav-item nav-link" id="nav-completed-tab" data-toggle="tab" href="#nav-completed"
                            role="tab" aria-controls="nav-completed" aria-selected="false">Completed</a>
                        <a class="nav-item nav-link" id="nav-decline-tab" data-toggle="tab" href="#nav-decline"
                            role="tab" aria-controls="nav-decline" aria-selected="false">Decline</a>
                    </div>
                </nav>


                <div class="tab-pane fade show active" id="nav-process" role="tabpanel"
                    aria-labelledby="nav-process-tab">
                    <br>
                    <table id="dataTable" class="table table-striped table-responsive-lg" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Reference ID</th>
                                <th>Full Name</th>
                                <th>Birthdate</th>
                                <th>Birthplace</th>
                                <th>Father's Full Name</th>
                                <th>Mother's Maiden Name</th>
                                <th>Purpose</th>
                                
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inventory as $item) { ?>
                            <tr>
                                <td class="text-center align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="">
                                            <p class="fw-bold mb-1"><?php echo $item['id']; ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center align-middle"><?php echo $item['reference_id']; ?></td>
                                <td class="text-center align-middle"><?php echo $item['fullname']; ?></td>
                                <td class="text-center align-middle"><?php echo $item['birthdate']; ?></td>
                                <td class="text-center align-middle"><?php echo $item['birthplace']; ?></td>
                                <td class="text-center align-middle"><?php echo $item['father_fullname']; ?></td>
                                <td class="text-center align-middle"><?php echo $item['mother_maidenname']; ?></td>
                                <td class="text-center align-middle"><?php echo $item['purpose']; ?></td>
                                <td class="text-center align-middle"><?php echo $item['date_added']; ?></td>
                      
                                <td class="text-center align-middle">
                                    <button class="generate-button"
                                        data-id="<?php echo $item['id']; ?>">Generate</button>
                                </td>
                            </tr>
                            </tr>
                            <!-- MODAL APPLICATION -->
                            <?php require 'forms/_generator.php'; ?>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <p>This is a placeholder for modal content.</p>
                        </div>
                    </div>
                    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                    <script>
                    var modal = document.getElementById("myModal");
                    var span = document.getElementsByClassName("close")[0];

                    $(".openModalBtn").click(function() {
                        const id = $(this).attr('data-id');

                        fetch(`forms/baptism-cert.php?id=${ id }`).then(response => response.text()).then(
                            data => {
                                modal.innerHTML = data;
                                modal.style.display = "block";
                                _initPDFScript();
                            }).catch(error => {
                            console.log('Error fetching content:', error);
                        });
                    })

                    span.onclick = function() {
                        modal.style.display = "none";
                    }

                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.style.display = "none";
                        }
                    }

                    function closeModal() {
                        modal.style.display = "none";
                    }
                    </script>



                    <div class="tab-pane fade" id="nav-Approved" role="tabpanel" aria-labelledby="nav-Approved-tab">
                    </div>
                    <div class="tab-pane fade" id="nav-received" role="tabpanel" aria-labelledby="nav-received-tab">...3
                    </div>
                    <div class="tab-pane fade" id="nav-completed" role="tabpanel" aria-labelledby="nav-completed-tab">
                        ...4
                    </div>
                    <div class="tab-pane fade" id="nav-cancelled" role="tabpanel" aria-labelledby="nav-cancelled-tab">
                        ...5
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

</body>

</html>