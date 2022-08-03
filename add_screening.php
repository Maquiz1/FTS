<?php error_reporting(E_ALL ^ E_NOTICE); ?>
<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$pageError = null;
$successMessage = null;
$errorM = false;
$errorMessage = null;
$email = new Email();
$random = new Random();
$countries = null;
$checkError = false;
$date = null;
$favicon = $override->get('images', 'cat', 1)[0];
$logo = $override->get('images', 'cat', 2)[0];
if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
        if (Input::get('add_client')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'full_name' => array(
                    'required' => true,
                ),
                'study_id' => array(
                    'required' => true,
                    'unique' => 'clients',
                    'min' => 4,
                ),
                'pt_initials' => array(
                    'required' => true,
                    'max' => 3,
                ),
                'phone_number' => array(
                    'unique' => 'clients'
                ),
                'screening_date' => array(
                    'required' => true,
                ),
                'group' => array(
                    'required' => true,
                ),
                'project_name' => array(
                    'required' => true,
                ),
                'screening_gender' => array(
                    'required' => true,
                ),
                'screening_dob' => array(
                    'required' => true,
                )
            ));
            if ($validate->passed()) {
                $s_date = date('Y-m-d', strtotime(Input::get('screening_date')));
                $s_dob = date('Y-m-d', strtotime(Input::get('screening_dob')));
                try {
                    $user->createRecord('clients', array(
                        'project_id' => Input::get('project_name_id'),
                        'project_name' => Input::get('project_name'),
                        'study_id' => Input::get('study_id'),
                        'fname' => Input::get('fname'),
                        'mname' => Input::get('mname'),
                        'lname' => Input::get('lname'),
                        'initials' => Input::get('pt_initials'),
                        'phone_number' => Input::get('phone_number'),
                        'phone_number2' => Input::get('phone_number2'),
                        'status' => 2,
                        'staff_id' => $user->data()->id,
                        'screening_date' => $s_date,
                        'pt_group' => Input::get('group'),
                        'dob' => $s_dob,
                        'gender' => Input::get('screening_gender'),
                        'reason' => '',
                        'details' => '',
                        'visit_cat' => 0,
                        'participant_id' => Input::get('full_name')
                    ));

                    $user->updateRecord('details', array(
                        'status' => 'On Screening'
                    ), Input::get('full_name'));

                    $user->updateRecord('clients', array(
                        'status' => '2'
                    ), Input::get('participant_id'));

                    $successMessage = 'Client Screened Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
    }
} else {
    Redirect::to('index.php');
}
?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IHI - KINGANI | Register</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">


        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div>

        <!-- Navbar -->
        <?php include 'topNav.php' ?>
        <?php include 'sideNav.php' ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">


            <?php if ($errorMessage) { ?>
                <div class="block">
                    <div class="alert alert-danger">
                        <b>Error!</b> <?= $errorMessage ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                </div>
            <?php } elseif ($pageError) { ?>
                <div class="block col-md-12">
                    <div class="alert alert-danger">
                        <b>Error!</b> <?php foreach ($pageError as $error) {
                                            echo $error . ' , ';
                                        } ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                </div>
            <?php } elseif ($successMessage) { ?>
                <div class="block">
                    <div class="alert alert-success">
                        <b>Success!</b> <?= $successMessage ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                </div>
            <?php } ?>
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>
                                Register
                                <small>new</small>
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard1.php">Home</a></li>
                                <li class="breadcrumb-item active">List</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <h4>Register Volunteer <small>Subjetcs</small></h4>
                        </div>
                    </div>
                    <!-- ./row -->
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <div class="card card-primary card-tabs">
                                <div class="card-header p-0 pt-1">
                                    <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                                        <li class="pt-2 px-3">
                                            <h3 class="card-title">Card Title</h3>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Study Details</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <form method="post">
                                        <div class="tab-content" id="custom-tabs-two-tabContent">

                                            <div class="tab-pane fade show active" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">

                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>CLIENT ID:</label>
                                                            <input type="text" name="study_id" id="study_id" class="form-control" value="" required="" />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>SCREENING DATE</label>
                                                            <input type="date" class="form-control fas fa-calendar input-prefix" name="screening_date" id="screening_date" required="" />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>STUDY NAME:</label>
                                                            <select class="form-control" id="project_name" name="project_name" required>
                                                                <option value="">SELECT STUDY</option>
                                                                <?php foreach ($override->getData('study') as $group) { ?>
                                                                    <option value="<?= $group['name'] ?>"><?= $group['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>SGroup:</label>
                                                            <select class="form-control" id="group" name="group" required>
                                                                <option value="">Select Group</option>
                                                                <?php foreach ($override->getData('patient_group') as $group) { ?>
                                                                    <option value="<?= $group['id'] ?>"><?= $group['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">

                                                    <div class="col-sm-3">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Full Name:</label>
                                                            <select class="form-control" id="full_name" name="full_name" required>
                                                                <option value="">Select Name</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>INITIAL</label>
                                                            <input type="text" name="pt_initials" id="pt_initials" class="form-control" minlength="3" maxlength="3" required="" />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Gender</label>
                                                            <input type="text" name="screening_gender" id="screening_gender" class="form-control" value="" required="" />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Birth Date</label>
                                                            <input type="date" class="form-control fas fa-calendar input-prefix" name="screening_dob" id="screening_dob" required="" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">                                                 

                                                    <div class="col-sm-6">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Phone1</label>
                                                            <input type="text" name="phone_number" id="phone_number" class="form-control" value="" required="" />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Phone2</label>
                                                            <input type="text" name="phone_number2" id="phone_number2" class="form-control" value="" />
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                                                    <!-- <button type="button" class="btn btn-outline-light">Save changes</button> -->
                                                    <input type="hidden" name="fname" id="fname" class="form-control" value="" />
                                                    <input type="hidden" name="mname" id="mname" class="form-control" value="" />
                                                    <input type="hidden" name="lname" id="lname" class="form-control" value="" />
                                                    <input type="hidden" name="project_name_id" id="project_name_id" class="form-control" value="" />
                                                    <input type="submit" name="add_client" value="ADD" class="btn btn-success btn-clean">
                                                </div>
                                            </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include 'footerNav.php' ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
</body>








<script>
    $(document).ready(function() {
        $('#study_id').change(function() {
            var studyID = $(this).val();
            $('#s').hide();
            $('#waitS').show();
            $.ajax({
                url: "process.php?content=visit",
                method: "GET",
                data: {
                    studyID: studyID
                },
                dataType: "text",
                success: function(data) {
                    $('#visit_code').html(data);
                    $('#s').show();
                    $('#waitS').hide();
                }
            });
        });
        $('#country').change(function() {
            var site = $(this).val();
            $('#st').hide();
            $('#waitSty').show();
            $.ajax({
                url: "process.php?content=site",
                method: "GET",
                data: {
                    site: site
                },
                dataType: "text",
                success: function(data) {
                    $('#site').html(data);
                    $('#st').show();
                    $('#waitSty').hide();
                }
            });
        });

        $('#region_id').change(function() {
            var regionId = $(this).val();
            $('#rg').hide();
            $('#waitdst').show();
            $.ajax({
                url: "process.php?content=district",
                method: "GET",
                data: {
                    regionId: regionId
                },
                dataType: "text",
                success: function(data) {
                    $('#district_id').html(data);
                    $('#rg').show();
                    $('#waitdst').hide();
                }
            });
        });

        $('#project_name').change(function() {
            var project_name = $(this).val();
            $.ajax({
                url: "process.php?content=full_name",
                method: "GET",
                data: {
                    project_name: project_name
                },
                dataType: "text",
                success: function(data) {
                    $('#full_name').html(data);
                }
            });
        });

        $('#project_name').change(function() {
            var project_name = $(this).val();
            $.ajax({
                url: "process.php?content=project_id",
                method: "GET",
                data: {
                    project_name: project_name
                },
                dataType: "json",
                success: function(data) {
                    $('#project_name_id').val(data.project_id);
                }
            });
        });


        $('#full_name').change(function() {
            var full_name_id = $(this).val();
            $.ajax({
                url: "process.php?content=details",
                method: "GET",
                data: {
                    full_name_id: full_name_id
                },
                dataType: "json",
                success: function(data) {
                    $('#pt_initials').val(data.initial);
                    $('#screening_gender').val(data.gender);
                    $('#screening_dob').val(data.dob);
                    $('#phone_number').val(data.phone1);
                    $('#phone_number2').val(data.phone2);
                    $('#fname').val(data.fname);
                    $('#mname').val(data.mname);
                    $('#lname').val(data.lname);
                    $('#rg').show();
                    $('#waitdst').hide();
                }
            });
        });

        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }


        var currentTab = 0;
        $(function() {
            $("#custom-tabs-two-tabContent").tabs({
                select: function(e, i) {
                    currentTab = i.index;
                }
            });
        });
        $("#btnNext").live("click", function() {
            var tabs = $('#tabs').tabs();
            var c = $('#tabs').tabs("length");
            currentTab = currentTab == (c - 1) ? currentTab : (currentTab + 1);
            tabs.tabs('select', currentTab);
            $("#btnPrevious").show();
            if (currentTab == (c - 1)) {
                $("#btnNext").hide();
            } else {
                $("#btnNext").show();
            }
        });
        $("#btnPrevious").live("click", function() {
            var tabs = $('#tabs').tabs();
            var c = $('#tabs').tabs("length");
            currentTab = currentTab == 0 ? currentTab : (currentTab - 1);
            tabs.tabs('select', currentTab);
            if (currentTab == 0) {
                $("#btnNext").show();
                $("#btnPrevious").hide();
            }
            if (currentTab < (c - 1)) {
                $("#btnNext").show();
            }
        });
    });

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

</html>