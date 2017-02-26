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
                    <a class="nav-link" href="categories.php">Category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="items.php">Items</a>
                </li>
            </ul>
        </nav>

        <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">

            <h2 style="margin-top: 10px;"><?php echo $company->company_name; ?></h2>
            <div class="row" id="succesMsg">
            </div>

            <hr>

            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="packageSelect">Package</label>
                        <select class="form-control" id="packageSelect">
                            <option>-</option>
                            <?php
                            $packages = $db->getAllCompanyPackages($company->company_id);
                            foreach ($packages as $package) {
                                ?>
                                <option value="<?php echo $package->package_id; ?>"
                                        )"><?php echo $package->package_name; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="categorySelect">Category</label>
                        <select class="form-control" id="categorySelect">
                        </select>
                    </div>
                </div>
            </div>
            <table class="table table-hover table-sm">
                <thead class="thead-inverse">
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Item Description</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="table_content">
                <tr onclick="showAddModal(getPackageCategoryId())" class="table-warning" style="cursor: pointer"
                    id="add">
                    <td>
                        <h6>Add new item</h6>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>


        </main>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_addItem">
                <div class="modal-body" style="padding: 7%">
                    <div id="i_name_group" class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Item Name</span>
                        <input type="text" id='item_name' name="item_name" class="form-control"
                               placeholder="E.g. Candy,Toys,Party-Poppers"
                               aria-describedby="basic-addon1">
                    </div>
                    <div id="i_description_group" name="i_description_group" class="form-group" style="margin-top: 5%">
                        <label for="item_description">Item Description</label>
                        <textarea class="form-control" id="item_description" name="item_description"
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

<div class="modal" id="deleteModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Do you really want to delete item?</p>
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
                <h5 class="modal-title">Edit Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../handler/editPackage.php" id="form_editItem" method="post" enctype="multipart/form-data">
                <div class="modal-body" style="padding: 7%">
                    <input type="text" id="item_id_edit" name="item_id_edit" class="form-control"
                           style="visibility: hidden" readonly placeholder="">
                    <div id="i_name_group_edit" class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Item Name</span>
                        <input type="text" id='item_name_edit' name="item_name_edit" class="form-control"
                               placeholder="E.g. Food, Personnel, Giveaways"
                               aria-describedby="basic-addon1">
                    </div>
                    <div id="i_description_group_edit" name="i_description_group_edit" class="form-group"
                         style="margin-top: 5%">
                        <label for="item_description">Item Description</label>
                        <textarea class="form-control" id="item_description_edit" name="item_description_edit"
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

    $(function () {
        $('#packageSelect').on('change', function () {
            $('#categorySelect').empty()
            $('tr[id^=item_row]').empty()

            var fd = new FormData();
            fd.append('package_id', $('#packageSelect').val());

            $.ajax({
                type: 'POST',
                url: '../handler/getCategoriesByPackage.php',
                data: fd,
                contentType: false,
                processData: false,
                success: function (data) {
                    var details = JSON.parse(data)
                    $('#categorySelect').append("<option>-</option>")
                    $.each(details, function (i, item) {
                        $('#categorySelect').append("<option value=" + item.category_id + ">" + item.category_name + "</option>")
                    });
                }
            });
        })

        $('#categorySelect').on('change', function () {
            $('tr[id^=item_row]').empty()
            var fd = new FormData();
            fd.append('category_id', $('#categorySelect').val())

            $.ajax({
                type: 'POST',
                url: '../handler/getItemsPerCategory.php',
                data: fd,
                contentType: false,
                processData: false,
                success: function (data) {
                    var details = JSON.parse(data)

                    if (details.length == 0) {
                        $('#add').before(
                            "<tr id='no_item_row' class='table-active' >" +
                            "<td><h6>No Items</h6></td>" +
                            "<td></td>" +
                            "<td></td>" +
                            "<td></td>" +
                            "</tr>")
                    }



                    $.each(details, function (i, item) {
                        $('#add').before(
                            "<tr id='item_row"+item.item_id+"'>" +
                            "<td>" + item.item_id + "</td>" +
                            "<td>" + item.item_name + "</td>" +
                            "<td>" + item.item_description + "</td>" +
                            "<td> " +
                            "<div class='dropdown'>" +
                            "<a class='btn btn-secondary dropdown-toggle' href='' id='dropdownMenuLink'data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Action </a> " +
                            "<div class='dropdown-menu' aria-labelledby='dropdownMenuLink'> " +
                            "<a class='dropdown-item' style='cursor: pointer' onclick='showEditModal("+item.category_id+","+item.item_id+")'>Edit</a> " +
                            "<a class='dropdown-item' style='cursor: pointer' " +
                            "onclick='showDeleteModal(" + item.item_id + ")'>Delete</a> " +
                            "</div> " +
                            "</div> " +
                            "</td> " +
                            "</tr>")
                    });

                }
            });
        })

        $('#btn_save').on('click', function () {

            check_input_arr = new Array();

            var i_name = $("#item_name").val()
            var i_description = $("#item_description").val()

            checkElement(i_name, $("#item_name"), $("#i_name_group"))
            checkElement(i_description, $("#item_description"), $("#i_description_group"))

            if (!check_input_arr.includes(false)) {

                var fd = new FormData();
                var other_data = $('#form_addItem').serializeArray();
                $.each(other_data, function (key, input) {
                    fd.append(input.name, input.value);
                });
                fd.append('category_id', $('#categorySelect').val())
                fd.append('package_id', $('#packageSelect').val())

                $.ajax({
                    type: 'POST',
                    url: '../handler/addItem.php',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#addModal').modal('hide');
                        var item = JSON.parse(data)

                        if (item.successDb) {
                            $('#succesMsg').append(
                                "<div class='col-sm-3 offset-9'> " +
                                "<div id='addedAlert' class='alert alert-success alert-dismissible fade show' role='alert'> " +
                                "<button type='button' class='close' data-dismiss='alert' aria-label='Close'> " +
                                "<span aria-hidden='tru'>&times;</span> " +
                                "</button> " +
                                "<strong>Success!</strong> Item added successfully." +
                                " </div>" +
                                " </div> "
                            )
                            $('#add').before(
                                "<tr id='item_row"+item.item_id.item_id +"'>" +
                                "<td>" + item.item_id.item_id + "</td>" +
                                "<td>" + item.item_name + "</td>" +
                                "<td>" + item.item_description + "</td>" +
                                "<td> " +
                                "<div class='dropdown'>" +
                                "<a class='btn btn-secondary dropdown-toggle' href='' id='dropdownMenuLink'data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Action </a> " +
                                "<div class='dropdown-menu' aria-labelledby='dropdownMenuLink'> " +
                                "<a class='dropdown-item' style='cursor: pointer' onclick='showEditModal("+item.category_id+","+item.item_id.item_id+")'>Edit</a> " +
                                "<a class='dropdown-item' style='cursor: pointer' " +
                                "onclick='showDeleteModal(" + item.item_id.item_id + ")'>Delete</a> " +
                                "</div> " +
                                "</div> " +
                                "</td> " +
                                "</tr>")
                        } else {
                            $('#succesMsg').append(
                                "<div class='col-sm-3 offset-9'> " +
                                "<div id='addedAlert' class='alert alert-danger alert-dismissible fade show' role='alert'> " +
                                "<button type='button' class='close' data-dismiss='alert' aria-label='Close'> " +
                                "<span aria-hidden='tru'>&times;</span> " +
                                "</button> " +
                                "<strong>Error!</strong> Error in DB." +
                                " </div>" +
                                " </div> "
                            )
                        }

                    }
                });
            }
        })


    })

    function getPackageCategoryId() {
        var x = new Array();
        var package_id = $('#packageSelect').val();
        var category_id = $('#categorySelect').val();


        x.push(package_id);
        x.push(category_id);

        return x;

    }

    function showAddModal(x) {
        if (checkAddParams(x)) {
            $('#addModal').modal({backdrop: 'static', keyboard: false})
        } else {
            alert('Choose package and category first');
        }
    }

    function showEditModal(category_id,item_id) {
        $('#editModal').modal({backdrop: 'static', keyboard: false})

        var fd = new FormData();
        fd.append('item_id',item_id)

        $.ajax({
            type: 'POST',
            url: '../handler/getItem.php',
            data: fd,
            contentType: false,
            processData: false,
            success: function (data) {
                var details = JSON.parse(data)
                $('#item_id_edit').val(details.item_id)
                $('#item_name_edit').val(details.item_name)
                $('textarea#item_description_edit').val(details.item_description)
            }
        });

        $('#btn_save_edit').on('click', function () {

            check_input_arr = new Array();

            var i_name = $("#item_name_edit").val()
            var i_description = $("#item_description_edit").val()

            checkElement(i_name, $("#item_name_edit"), $("#i_name_group_edit"))
            checkElement(i_description, $("#item_description_edit"), $("#i_description_group_edit"))

            if (!check_input_arr.includes(false)) {
                var fd = new FormData();
                var other_data = $('#form_editItem').serializeArray();
                $.each(other_data, function (key, input) {
                    fd.append(input.name, input.value);
                });
                fd.append('category_id', category_id);

                $.ajax({
                    type: 'POST',
                    url: '../handler/editItem.php',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#editModal').modal('toggle');
                        var details = JSON.parse(data)

                        $('tr[id^=item_row]').empty()

                        if (details.length == 0) {
                            $('#add').before(
                                "<tr id='no_item_row' class='table-active' >" +
                                "<td><h6>No Items</h6></td>" +
                                "<td></td>" +
                                "<td></td>" +
                                "<td></td>" +
                                "</tr>")
                        }

                        $.each(details.item, function (i, item) {
                            $('#add').before(
                                "<tr id='item_row"+item.item_id+"'>" +
                                "<td>" + item.item_id + "</td>" +
                                "<td>" + item.item_name + "</td>" +
                                "<td>" + item.item_description + "</td>" +
                                "<td> " +
                                "<div class='dropdown'>" +
                                "<a class='btn btn-secondary dropdown-toggle' href='' id='dropdownMenuLink'data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Action </a> " +
                                "<div class='dropdown-menu' aria-labelledby='dropdownMenuLink'> " +
                                "<a class='dropdown-item' style='cursor: pointer' onclick='showEditModal("+item.category_id+","+item.item_id+")'>Edit</a> " +
                                "<a class='dropdown-item' style='cursor: pointer' " +
                                "onclick='showDeleteModal(" + item.item_id + ")'>Delete</a> " +
                                "</div> " +
                                "</div> " +
                                "</td> " +
                                "</tr>")
                        });
                    }
                });
            }

        })

    }

    function showDeleteModal(x) {
        $('#deleteModal').modal({backdrop: 'static', keyboard: false})

        var fd = new FormData();
        fd.append('item_id', x);

        $('#deleteBtn').on('click', function () {
            $.ajax({
                type: 'POST',
                url: '../handler/deleteItem.php',
                data: fd,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#deleteModal').modal('hide');
                    if (data) {
                        $("#item_row"+x).remove()
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

    function checkAddParams(x) {
        if (x[0] == "-" || x[0] == null || x[1] == "-" || x[1] == null) {
            return false;
        }
        return true;
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
