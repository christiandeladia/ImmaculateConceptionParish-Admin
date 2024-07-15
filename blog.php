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
        $sql = "SELECT COUNT(*) as totaladmin FROM `blog`";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['totaladmin'];
    }
    $total_admin = getadminTotal();
    
    function getInventory($status = 'active') {
        global $pdo;
        $sql = "SELECT * , DATE_FORMAT(date_added, '%M %d, %Y') AS date_component, TIME_FORMAT(date_added, '%h:%i %p') AS time_component FROM blog WHERE status = :status";
        $blog = [];
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':status', $status, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // For active administrators
    $blog = getInventory('active');
    
    // For inactive administrators
    $inactive_blog = getInventory('inactive');
}

function getActiveAdminCount() {
    global $pdo; 
    $sql = "SELECT COUNT(*) as total_active_admin FROM `blog` WHERE status = 'active'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['total_active_admin'];
}

function getInactiveAdminCount() {
    global $pdo; 
    $sql = "SELECT COUNT(*) as total_inactive_admin FROM `blog` WHERE status = 'inactive'";
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
    <title>Blog | Admin</title>
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
            $('#modalTitle').text('Publish New Blog');
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


.nav-fill>.nav-link,
.nav-fill .nav-item {
    flex: none !important;
    text-align: center;
    width: 200px !important;
}
</style>

<body>
    <?php 
    $activePage = 'blog'; 
    include 'nav.php';
    ?>
    <div></div>

    <!-- last update product -->
    <div class="admin_contatiner">

        <div class=".container-fluid mt-4 card mb-2 bg-light shadow" style=" margin: 0 3%">
            <div class="card-body">
                <h3 class="text-center font-weight-bold mb-4">BLOG</h3>
                <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-process-tab" data-toggle="tab" href="#nav-process"
                            role="tab" aria-controls="nav-process" aria-selected="true">
                            Active
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
                                    <th style="text-align: center; width: 5%;">Blog ID</th>
                                    <th style="text-align: center; width: 10%;">Date</th>
                                    <th style="text-align: center; width: 10%;">Image Cover</th>
                                    <th style="text-align: center; width: 10%;">Title</th>
                                    <th style="text-align: center; width: 35%;">Content</th>
                                    <th style="text-align: center; width: 10%;">Date Publish</th>
                                    <th style="text-align: center; width: 10%;">Status</th>
                                    <th style="text-align: center; width: 10%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($blog as $item) { ?>
                                <tr>
                                    <td class="text-center align-left">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="fw-bold mb-1"><?php echo $item['blog_id']; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-left"><?php echo $item['date']; ?></td>
                                    <td class="text-center align-left">
                                        <img src="<?php echo $item['image']; ?>" width="auto" height="60">
                                    </td>
                                    <td class="text-center align-left"><?php echo $item['title']; ?></td>
                                    <td class="text-center align-left"><?php echo substr($item['content'], 0, 200); ?></td>
                                    <td class="text-center align-left">
                                        <div>
                                            <span><?php echo $item['date_component']; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $item['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="text-center align-left">
                                        <?php if ($item['status'] == 'active'): ?>
                                        <span class="badge badge-success rounded-pill d-inline">Active</span>
                                        <?php else: ?>
                                        <span class="badge badge-danger rounded-pill d-inline">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-left">
                                        <?php if ($item['status'] == 'active'): ?>
                                        <!-- <?php if ($item['blog_id'] !== $_SESSION['auth_admin']['blog_id']): ?> -->
                                        <?php if ($item['status'] == 'active'): ?>
                                        <button class="btn-sm btn-warning btn-block deactivateBtn"
                                            data-blog-id="<?php echo $item['blog_id']; ?>">
                                            <i class="fa fa-minus-circle"></i> Deactivate
                                        </button>
                                        <?php else: ?>
                                        <button class="btn-sm btn-success btn-block activateBtn"
                                            data-blog-id="<?php echo $item['blog_id']; ?>">
                                            <i class="fa fa-check-circle"></i> Activate
                                        </button>
                                        <?php endif; ?>
                                        <button class="btn-sm btn-info btn-block mb-2" data-toggle="modal"
                                            data-target="#view_<?php echo $item['blog_id']; ?>">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </button>
                                        <button class="btn-sm btn-danger btn-block"
                                            onclick="deleteAdmin(<?php echo $item['blog_id']; ?>)">
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
                                    <th style="text-align: center; width: 5%;">Blog ID</th>
                                    <th style="text-align: center; width: 10%;">Date</th>
                                    <th style="text-align: center; width: 10%;">Image Cover</th>
                                    <th style="text-align: center; width: 10%;">Title</th>
                                    <th style="text-align: center; width: 35%;">Content</th>
                                    <th style="text-align: center; width: 10%;">Date Publish</th>
                                    <th style="text-align: center; width: 10%;">Status</th>
                                    <th style="text-align: center; width: 10%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inactive_blog as $deactiveblog) { ?>
                                <tr>
                                    <td class="text-center align-left">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="fw-bold mb-1"><?php echo $deactiveblog['blog_id']; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-left"><?php echo $deactiveblog['date']; ?></td>
                                    <td class="text-center align-left">
                                        <img src="<?php echo $deactiveblog['image']; ?>" width="auto" height="60">
                                    </td>
                                    <td class="text-center align-left"><?php echo $deactiveblog['title']; ?></td>
                                    <td class="text-center align-left"><?php echo $deactiveblog['content']; ?></td>
                                    <td class="text-center align-left">
                                        <div>
                                            <span><?php echo $deactiveblog['date_component']; ?></span>
                                            <p class="time text-muted mb-0">
                                                <?php echo $deactiveblog['time_component']; ?></span>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="text-center align-left">
                                        <?php if ($deactiveblog['status'] == 'active'): ?>
                                        <span class="badge badge-success rounded-pill d-inline">Active</span>
                                        <?php else: ?>
                                        <span class="badge badge-danger rounded-pill d-inline">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-left">
                                        <?php if ($deactiveblog['status'] == 'active'): ?>
                                        <button class="btn-sm btn-warning btn-block deactivateBtn"
                                            data-blog-id="<?php echo $deactiveblog['blog_id']; ?>">
                                            <i class="fa fa-minus-circle"></i> Deactivate
                                        </button>
                                        <?php else: ?>
                                        <button class="btn-sm btn-success btn-block activateBtn"
                                            data-blog-id="<?php echo $deactiveblog['blog_id']; ?>">
                                            <i class="fa fa-check-circle"></i> Activate
                                        </button>
                                        <?php endif; ?>
                                        <button class="btn-sm btn-danger btn-block"
                                            onclick="deleteAdmin(<?php echo $deactiveblog['blog_id']; ?>)">
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

    <!-- ADD Modal form -->
    <div id="adminModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTitle"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="blog/add.php" id="admin_dataForm" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="date">Date:</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="form-group">
                            <label for="image">Image Cover:</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div>
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="content">Content:</label>
                            <textarea class="form-control" id="content" name="content" required></textarea>
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


    <!-- EDIT Modal form -->
    <?php foreach ($blog as $item) { ?>
    <div id="view_<?php echo $item['blog_id']; ?>" class="modal fade">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editmodalTitle">Edit Blog</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="blog/update.php" id="dataForm" method="post">
                        <input type="hidden" id="blog_id" name="blog_id">
                        <div class="form-group">
                            <label for="image">Image Cover:</label><br>
                            <img src="<?php echo $item['image']; ?>" style="width: auto; height: 200px;"><br>
                            <span style="color: red;">(The Cover image is not allowed to change)</span>
                        </div>
                        <div class="form-group">
                            <label for="date">Date:</label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="<?php echo $item['date']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="<?php echo $item['title']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="content">Content:</label>
                            <textarea style="height:400px;" class="form-control" id="content" name="content" value=""
                                required><?php echo $item['content']; ?></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="updateBlog(<?php echo $item['blog_id']; ?>)">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <script>
    // Define the updateBlog function to handle saving form data
    function updateBlog(blogid) {
        // Get other form data
        var dateBlog = $('#view_' + blogid + ' #date').val();
        var titleBlog = $('#view_' + blogid + ' #title').val();
        var contentBlog = $('#view_' + blogid + ' #content').val();

        // Send AJAX request to update.php
        $.ajax({
            url: 'blog/update.php',
            method: 'POST',
            data: {
                blog_id: blogid,
                date: dateBlog,
                title: titleBlog,
                content: contentBlog
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
$(document).on('click', '.deactivateBtn', function() {
    var blogid = $(this).data('blog-id');
    if (confirm("Are you sure you want to deactivate this Blog?")) {
        $.ajax({
            url: 'blog/deactivate.php',
            method: 'POST',
            data: {
                blog_id: blogid
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
    var blogid = $(this).data('blog-id');
    if (confirm("Are you sure you want to activate this Blog?")) {
        $.ajax({
            url: 'blog/activate.php',
            method: 'POST',
            data: {
                blog_id: blogid
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
function deleteAdmin(blogid) {
    // Confirm before proceeding with deletion
    if (confirm("Are you sure you want to delete this Blog?")) {
        // Send an AJAX request to delete.php for deletion
        $.ajax({
            url: 'blog/delete.php',
            method: 'GET',
            data: {
                blog_id: blogid
            },
            success: function() {
                // Redirect to products.php after successful deletion
                window.location.href = 'blog/delete.php';
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