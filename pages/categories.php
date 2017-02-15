<!DOCTYPE html>
<?php
include "../handler/DBHandler.php";
define('IMG_DIR', 'http://eventcoordinator.000webhostapp.com/images/');
$db = new DbHandler();
session_start();
if (!isset($_SESSION["company"])) {
    header("Location: signin.php");
    exit();
} else {
    $company = $_SESSION["company"];
}
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>My Event</title>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"
          integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <style type="text/css">
        /*
     * Base structure
     */
        /* Move down content because we have a fixed navbar that is 50px tall */
        body {
            padding-top: 50px;
        }

        /*
         * Typography
         */
        h1 {
            margin-bottom: 20px;
            padding-bottom: 9px;
            border-bottom: 1px solid #eee;
        }

        /*
         * Sidebar
         */
        .sidebar {
            position: fixed;
            top: 51px;
            bottom: 0;
            left: 0;
            z-index: 1000;
            padding: 20px;
            overflow-x: hidden;
            overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
            border-right: 1px solid #eee;
        }

        th{
            font-size: 80%;
            padding: 0px;
            text-align: center;
        }

        td{

            font-size: 80%;
            text-align: center;
        }


        /* Sidebar navigation */
        .sidebar {
            padding-left: 0;
            padding-right: 0;
        }

        .sidebar .nav {
            margin-bottom: 20px;
        }

        .sidebar .nav-item {
            width: 100%;
        }

        .sidebar .nav-item + .nav-item {
            margin-left: 0;
        }

        .sidebar .nav-link {
            border-radius: 0;
        }

        /*
         * Dashboard
         */
        /* Placeholders */
        .placeholders {
            padding-bottom: 3rem;
        }

        .placeholder img {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        #yourElement {
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            height: 232px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-toggleable-md navbar-inverse fixed-top bg-inverse">
    <a class="navbar-brand" href="overview.php">My Event</a>
    <button class="navbar-toggler navbar-toggler-right hidden-lg-up" type="button" data-toggle="collapse"
            data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse hidden-lg-up" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto ">
            <li class="nav-item active">
                <a class="nav-link" href="../handler/signout.php" aria-haspopup="true" aria-expanded="false"
                   onclick="return signout_dialog()">
                    Sign Out
                </a>
            </li>
        </ul>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <nav class="col-sm-3 col-md-2 hidden-xs-down bg-faded sidebar">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="overview.php">Overview</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="packages.php">Packages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="categories.php">Category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="items.php">Items</a>
                </li>
            </ul>
        </nav>
        <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
            <h2 style="margin-top: 10px;"><?php echo $company->company_name; ?></h2>
            <hr>
            <div class="row">
                <?php
                if (isset($_SESSION['action'])) {
                    if ($_SESSION['action']) { ?>
                        <div class="col-sm-3 offset-9">
                            <div id="addedAlert" class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Success!</strong> Category added successfully.
                            </div>
                        </div>
                        <?php
                        unset($_SESSION['action']);
                    } else {
                        ?>
                        <div class="col-sm-3 offset-9">
                            <div id="addedAlert" class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Error!</strong> DB saving error.
                            </div>
                        </div>
                        <?php
                        unset($_SESSION['action']);
                    }
                }
                ?>


            </div>
            <?php
            $packages = $db->getAllCompanyPackages($company->company_id);
            $x = 0;
            foreach ($packages as $package) {
                ?>
                <h5 style="margin-top: 1%"><strong>Package: </strong> <?php echo $package->package_name; ?></h5>
                <table class="table table-hover table-sm" style="margin-top: 2%">
                    <thead class="thead-inverse">
                    <tr>
                        <th>Category ID</th>
                        <th>Category Name</th>
                        <th>Category Description</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $categories = $db->getAllCategoryPerPackage($package->package_id);
                    $x = 0;
                    foreach ($categories as $category) {
                        ?>
                        <tr>
                            <td><?php echo $category->category_id; ?></td>
                            <td><?php echo $category->category_name; ?></td>
                            <td>
                                <small><?php echo $category->category_description; ?></small>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-secondary dropdown-toggle" href="" id="dropdownMenuLink"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item"
                                           onclick="showEditModal(<?php echo $category->category_id; ?>)"
                                           href="#">Edit</a>
                                        <a class="dropdown-item"
                                           onclick="showDeleteModal(<?php echo $category->category_id; ?>)"
                                           href="#">Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php
                    } ?>
                    <tr class="table-warning" style="cursor: pointer"
                        onclick="showAddModal(<?php echo $package->package_id; ?>)">
                        <td>
                            <h6>Add new category</h6>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
                <hr style="margin-top: 3%; margin-bottom: 2%">
                <?php
            }
            $x++;
            ?>
        </main>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_addPackage">
                <div class="modal-body" style="padding: 7%">
                    <div id="c_name_group" class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Category Name</span>
                        <input type="text" id='category_name' name="category_name" class="form-control"
                               placeholder="E.g. Food, Personnel, Giveaways"
                               aria-describedby="basic-addon1">
                    </div>
                    <div id="c_description_group" name="c_description_group" class="form-group" style="margin-top: 5%">
                        <label for="category_description">Category Description</label>
                        <textarea class="form-control" id="category_description" name="category_description"
                                  rows="3"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" style="cursor: pointer" id="btn_save" class="btn btn-primary">Save changes
                    </button>
                    <button type="button" style="cursor: pointer" class="btn btn-secondary" data-dismiss="modal">Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Do you really want to delete category?</p>
            </div>
            <div class="modal-footer">
                <button type="button" style="cursor: pointer" id="deleteBtn" class="btn btn-danger">Delete</button>
                <button type="button" style="cursor: pointer" class="btn btn-secondary" data-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../handler/editPackage.php" id="form_editPackage" method="post" enctype="multipart/form-data">
                <div class="modal-body" style="padding: 7%">
                    <input type="text" id="category_id_edit" name="category_id_edit" class="form-control"
                           style="visibility: hidden" readonly placeholder="">
                    <div id="c_name_group_edit" class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Category Name</span>
                        <input type="text" id='category_name_edit' name="category_name_edit" class="form-control"
                               placeholder="E.g. Food, Personnel, Giveaways"
                               aria-describedby="basic-addon1">
                    </div>
                    <div id="c_description_group_edit" name="c_description_group_edit" class="form-group"
                         style="margin-top: 5%">
                        <label for="category_description">Category Description</label>
                        <textarea class="form-control" id="category_description_edit" name="category_description_edit"
                                  rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" style="cursor: pointer" id="btn_save_edit" class="btn btn-primary">Save
                        changes
                    </button>
                    <button type="button" style="cursor: pointer" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"
        integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n"
        crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
        integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"
        integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn"
        crossorigin="anonymous"></script>
<script>
    var check_input_arr = new Array();

    function showAddModal(x) {
        $('#addModal').modal({backdrop: 'static', keyboard: false})

        $('#btn_save').on('click', function () {

            check_input_arr = new Array();

            var c_name = $("#category_name").val()
            var c_description = $("#category_description").val()

            checkElement(c_name, $("#category_name"), $("#c_name_group"))
            checkElement(c_description, $("#category_description"), $("#c_description_group"))

            if (!check_input_arr.includes(false)) {
                var fd = new FormData();
                var other_data = $('#form_addPackage').serializeArray();
                $.each(other_data, function (key, input) {
                    fd.append(input.name, input.value);
                });
                fd.append('package_id', x);

                $.ajax({
                    type: 'POST',
                    url: '../handler/addCategory.php',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#addModal').modal('toggle');
                        location.reload();
                    }
                });
            }

        })

    }

    function showEditModal(x) {
        $('#editModal').modal({backdrop: 'static', keyboard: false})

        var package_id;

        var fd = new FormData();
        fd.append('category_id', x);

        $.ajax({
            type: 'POST',
            url: '../handler/getCategory.php',
            data: fd,
            contentType: false,
            processData: false,
            success: function (data) {
                var details = JSON.parse(data)
                $('#category_id_edit').val(details.category_id)
                $('#category_name_edit').val(details.category_name)
                $('textarea#category_description_edit').val(details.category_description)
                package_id = details.package_id
            }
        });

        $('#btn_save_edit').on('click', function () {

            check_input_arr = new Array();

            var c_name = $("#category_name_edit").val()
            var c_description = $("#category_description_edit").val()

            checkElement(c_name, $("#category_name_edit"), $("#c_name_group_edit"))
            checkElement(c_description, $("#category_description_edit"), $("#c_description_group_edit"))

            if (!check_input_arr.includes(false)) {
                var fd = new FormData();
                var other_data = $('#form_editPackage').serializeArray();
                $.each(other_data, function (key, input) {
                    fd.append(input.name, input.value);
                });
                fd.append('category_id', x);
                fd.append('package_id', package_id);

                $.ajax({
                    type: 'POST',
                    url: '../handler/editCategory.php',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#editModal').modal('toggle');
                        location.reload();
                    }
                });
            }

        })

    }

    function showDeleteModal(x) {
        $('#deleteModal').modal({backdrop: 'static', keyboard: false})

        var fd = new FormData();
        fd.append('category_id', x);


        $('#deleteBtn').on('click', function () {
            $.ajax({
                type: 'POST',
                url: '../handler/deleteCategory.php',
                data: fd,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#deleteModal').modal('toggle');
                    if (data) {
                        location.reload();
                    }
                }
            });
        })
    }

    function checkElement(value, input, group) {
        if (!value) {
            $(group).addClass("has-danger")
            $(input).addClass("form-control-danger")
            check_input_arr.push(false)
        } else {
            $(group).removeClass("has-danger")
            $(input).removeClass("form-control-danger")
            check_input_arr.push(true)
        }
    }


    function signout_dialog() {
        var r = confirm("Do you really want to sign out?");
        if (r == false) {
            return false
        }
    }

</script>
</body>
</html>
