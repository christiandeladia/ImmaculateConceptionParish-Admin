<?php
require "process/connect.php";
if (!isset($_SESSION['auth_admin'])) {
    header("location: index.php");
    exit;
}
?>
<?php
  	require_once "process/connect.php";
    $is_admin_logged_in = isset($_SESSION['auth_admin']);
    if ( isset($_SESSION['auth_admin']) ) {
    
    function getadminTotal() {
        global $pdo; 
        $sql = "SELECT COUNT(*) as totaladmin FROM `admin_login`";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['totaladmin'];
    }
    $total_admin = getadminTotal();
    
    function getInventory($status = 'active') {
        global $pdo;
        $sql = "SELECT *, DATE_FORMAT(date_added, '%d/%m/%Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM admin_login WHERE status = :status";
        $admin_login = [];
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':status', $status, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // For active administrators
    $admin_login = getInventory('active');
    
    // For inactive administrators
    $inactive_admin_login = getInventory('inactive');
}

function getActiveAdminCount() {
    global $pdo; 
    $sql = "SELECT COUNT(*) as total_active_admin FROM `admin_login` WHERE status = 'active'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['total_active_admin'];
}

function getInactiveAdminCount() {
    global $pdo; 
    $sql = "SELECT COUNT(*) as total_inactive_admin FROM `admin_login` WHERE status = 'inactive'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['total_inactive_admin'];
}

$total_active_admin = getActiveAdminCount();
$total_inactive_admin = getInactiveAdminCount();

?>

<?php include 'process/formula.php';?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image/admin.ico">
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Include jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Include Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Include DataTables plugin -->
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
            $('#modalTitle').text('Add New Admin');
            $('#admin_dataForm')[0].reset();
            $('#adminModal').modal('show');
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
/* Custom styles for Bootstrap Tables */
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

<body>
    <?php 
    $activePage = 'admin'; 
    include 'nav.php';
    ?>
    <div></div>

    <!-- last update product -->
    <div class="admin_contatiner">

        <div class=".container-fluid mt-4 card mb-2 bg-light shadow" style=" margin: 0 3%">
            <div class="card-body">
                <h3 class="text-center font-weight-bold mb-4">ADMIN</h3>
                <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-process-tab" data-toggle="tab" href="#nav-process"
                            role="tab" aria-controls="nav-process" aria-selected="true">
                            All
                            <?php if ($total_active_admin > 0): ?>
                            <span
                                class="badge badge-primary rounded-circle p-2"><?php echo $total_active_admin; ?></span>
                            <?php endif; ?>
                        </a>
                        <a class="nav-item nav-link" id="nav-ship-tab" data-toggle="tab" href="#nav-ship" role="tab"
                            aria-controls="nav-ship" aria-selected="false">
                            Inactive
                            <?php if ($total_inactive_admin > 0): ?>
                            <span
                                class="badge badge-primary rounded-circle p-2"><?php echo $total_inactive_admin; ?></span>
                            <?php endif; ?>
                        </a>
                    </div>

                </nav>
                <div class="tab-content custom-tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-process" role="tabpanel"
                        aria-labelledby="nav-process-tab">

                        <button type="button" class="btn btn-primary mb-2" id="addmodalbutton"><i
                                class="fas fa-plus"></i>
                            Create</button>
                        <br><br>

                        <table id="dataTableAll" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">

                            <thead>
                                <tr>
                                    <th>Admin ID</th>
                                    <th>Profile Image</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Mobile Number</th>
                                    <th>Username</th>
                                    <th>Date Added</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($admin_login as $admin) { ?>
                                <tr>
                                    <td class="text-center align-left">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="fw-bold mb-1"><?php echo $admin['admin_id']; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-left">
                                        <img src="<?php echo $admin['profile_image']; ?>" width="auto" height="60"
                                            style="border-radius: 50px;" alt="<?php echo $admin['firstname']; ?>">
                                    </td>
                                    <td class="text-center align-left"><?php echo $admin['firstname']; ?></td>
                                    <td class="text-center align-left"><?php echo $admin['lastname']; ?></td>
                                    <td class="text-center align-left"><?php echo $admin['email']; ?></td>
                                    <td class="text-center align-left"><?php echo $admin['mobile']; ?></td>
                                    <td class="text-center align-left"><?php echo $admin['username']; ?></td>
                                    <td class="text-center align-left">
                                        <div>
                                            <span><?php echo $admin['date_component']; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $admin['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="text-center align-left">
                                        <?php if ($admin['status'] == 'active'): ?>
                                        <span class="badge badge-success rounded-pill d-inline">Active</span>
                                        <?php else: ?>
                                        <span class="badge badge-danger rounded-pill d-inline">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-left">
                                        <?php if ($admin['status'] == 'active'): ?>
                                        <?php if ($admin['admin_id'] !== $_SESSION['auth_admin']['admin_id']): ?>
                                        <?php if ($admin['status'] == 'active'): ?>
                                        <button class="btn-sm btn-warning btn-block deactivateBtn"
                                            data-admin-id="<?php echo $admin['admin_id']; ?>">
                                            <i class="fa fa-minus-circle"></i> Deactivate
                                        </button>
                                        <?php else: ?>
                                        <button class="btn-sm btn-success btn-block activateBtn"
                                            data-admin-id="<?php echo $admin['admin_id']; ?>">
                                            <i class="fa fa-check-circle"></i> Activate
                                        </button>
                                        <?php endif; ?>
                                        <button class="btn-sm btn-danger btn-block"
                                            onclick="deleteAdmin(<?php echo $admin['admin_id']; ?>)">
                                            <i class="fas fa-ban"></i> Remove
                                        </button>
                                        <?php else: ?>
                                        <!-- Disable the button -->
                                        <button class="btn-sm btn-muted btn-block deactivateBtn"
                                            data-admin-id="<?php echo $admin['admin_id']; ?>" disabled>
                                            <i class="fa fa-minus-circle"></i> Deactivate
                                        </button>
                                        <button class="btn-sm btn-muted btn-block" disabled>
                                            <i class="fas fa-ban"></i> Remove
                                        </button>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- INACTIVE ADMIN -->
                    <div class="tab-pane fade" id="nav-ship" role="tabpanel" aria-labelledby="nav-ship-tab">
                        <br><br>

                        <table id="dataTableRemove" class="table table-striped table-responsive-lg" cellspacing="0"
                            width="100%">

                            <thead>
                                <tr>
                                    <th>Admin ID</th>
                                    <!-- <th>Profile Image</th> -->
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Mobile Number</th>
                                    <th>Username</th>
                                    <th>Date Removed</th>
                                    <th>Status</th>
                                    <th>Actions</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inactive_admin_login as $deactiveadmin) { ?>
                                <tr>
                                    <td class="text-center align-left">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="fw-bold mb-1"><?php echo $deactiveadmin['admin_id']; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-left"><?php echo $deactiveadmin['firstname']; ?></td>
                                    <td class="text-center align-left"><?php echo $deactiveadmin['lastname']; ?></td>
                                    <td class="text-center align-left"><?php echo $deactiveadmin['email']; ?></td>
                                    <td class="text-center align-left"><?php echo $deactiveadmin['mobile']; ?></td>
                                    <td class="text-center align-left"><?php echo $deactiveadmin['username']; ?></td>
                                    <td class="text-center align-left">
                                        <div>
                                            <span><?php echo $deactiveadmin['date_component']; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $deactiveadmin['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="text-center align-left">
                                        <?php if ($deactiveadmin['status'] == 'active'): ?>
                                        <span class="badge badge-success rounded-pill d-inline">Active</span>
                                        <?php else: ?>
                                        <span class="badge badge-danger rounded-pill d-inline">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-left">
                                        <?php if ($deactiveadmin['status'] == 'active'): ?>
                                        <button class="btn-sm btn-warning btn-block deactivateBtn"
                                            data-admin-id="<?php echo $deactiveadmin['admin_id']; ?>">
                                            <i class="fa fa-minus-circle"></i> Deactivate
                                        </button>
                                        <?php else: ?>
                                        <button class="btn-sm btn-success btn-block activateBtn"
                                            data-admin-id="<?php echo $deactiveadmin['admin_id']; ?>">
                                            <i class="fa fa-check-circle"></i> Activate
                                        </button>
                                        <?php endif; ?>
                                        <button class="btn-sm btn-danger btn-block"
                                            onclick="deleteAdmin(<?php echo $deactiveadmin['admin_id']; ?>)">
                                            <i class="fas fa-ban"></i> Remove
                                        </button>
                                    </td>

                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <!-- end of product -->

    <!-- Modal form -->
    <div id="adminModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTitle"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="admin/add.php" id="admin_dataForm" method="post" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="firstname">Profile Picture:</label>
                            <input type="file" class="form-control" name="profile_image" required>
                        </div>
                        <div class="form-group">
                            <label for="firstname">First Name:</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" required>
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last Name:</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" required></input>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="mobile">Mobile:</label>
                            <input type="number" class="form-control" id="mobile" name="mobile" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password:</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                required>
                        </div>
                        <!-- <input type="submit" name="submit" value="Sign Up"> -->
                        <div class="modal-footer">
                            <button type="submit" name="submit" class="btn btn-primary" id="add_adminBtn"><i
                                    class="fas fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

</body>
<script>
$(document).on('click', '.deactivateBtn', function() {
    var adminId = $(this).data('admin-id');
    if (confirm("Are you sure you want to deactivate this administrator?")) {
        $.ajax({
            url: 'admin/deactivate_admin.php',
            method: 'POST',
            data: {
                admin_id: adminId
            },
            success: function(response) {
                // Handle success, maybe refresh the page or update the UI
                console.log(response);
                // Reload the page or update UI as necessary
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                // Handle errors as needed
            }
        });
    }
});

$(document).on('click', '.activateBtn', function() {
    var adminId = $(this).data('admin-id');
    if (confirm("Are you sure you want to activate this administrator?")) {
        $.ajax({
            url: 'admin/activate_admin.php',
            method: 'POST',
            data: {
                admin_id: adminId
            },
            success: function(response) {
                // Handle success, maybe refresh the page or update the UI
                console.log(response);
                // Reload the page or update UI as necessary
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                // Handle errors as needed
            }
        });
    }
});
</script>



<script>
// Function to delete the product
function deleteAdmin(adminId) {
    // Confirm before proceeding with deletion
    if (confirm("Are you sure you want to delete this Admin?")) {
        // Send an AJAX request to delete.php for deletion
        $.ajax({
            url: 'admin/delete.php',
            method: 'GET',
            data: {
                admin_id: adminId
            },
            success: function() {
                // Redirect to products.php after successful deletion
                window.location.href = 'admin/delete.php';
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                // Handle errors as needed
            }
        });
    }
}
</script>

</html>