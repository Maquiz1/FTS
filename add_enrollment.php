<?php error_reporting(E_ALL ^ E_NOTICE); ?>
<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$pageError = null;
$successMessage = null;
$errorM = false;
$errorMessage = null;
$t_crf = 0;
$p_crf = 0;
$w_crf = 0;
$s_name = null;
$c_name = null;
$site = null;
$country = null;
$study_crf = null;
$data_limit = 10000;
$favicon = $override->get('images', 'cat', 1)[0];
$logo = $override->get('images', 'cat', 2)[0];

//modification remove all pilot crf have been removed/deleted from study crf
if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
        if (Input::get('add_visit')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'study_name' => array(
                    'required' => true,
                ),
                'client_id' => array(
                    'required' => true,
                ),
                'visit_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    if (!$override->get('visit', 'client_id', Input::get('client_id'))) {
                        if ((Input::get('study_name') == 'VAC080') and (Input::get('group') == 'Group 1A' || Input::get('group') == 'Group 2A')) {
                            $user->generateScheduleNotDelayedVac080(Input::get('study_name'), Input::get('client_id'), $date = date('Y-m-d', strtotime(Input::get('visit_date'))), 1, 'c', Input::get('participant_group'));
                        } elseif ((Input::get('study_name') == 'VAC080') and (Input::get('group') == 'Group 1B' || Input::get('group') == 'Group 2B' || Input::get('group') == 'Group 2C' || Input::get('group') == 'Group 2D')) {
                            $user->generateScheduleDelayedVac080(Input::get('study_name'), Input::get('client_id'), $date = date('Y-m-d', strtotime(Input::get('visit_date'))), 1, 'c', Input::get('participant_group'));
                        } elseif ((Input::get('study_name') == 'VAC082') and (Input::get('group') == 'Group 1A' || Input::get('group') == 'Group 1B' || Input::get('group') == 'Group 2A' || Input::get('group') == 'Group 2B' || Input::get('group') == 'Group 3A' || Input::get('group') == 'Group 3B')) {
                            $user->generateScheduleNotDelayedVac082(Input::get('study_name'), Input::get('client_id'), $date = date('Y-m-d', strtotime(Input::get('visit_date'))), 1, 'c', Input::get('participant_group'));
                        } elseif ((Input::get('study_name') == 'VAC082') and (Input::get('group') == 'Group 3C' || Input::get('group') == 'Group 4A' || Input::get('group') == 'Group 4B' || Input::get('group') == 'Group 4C')) {
                            $user->generateScheduleDelayedVac082(Input::get('study_name'), Input::get('client_id'), $date = date('Y-m-d', strtotime(Input::get('visit_date'))), 1, 'c', Input::get('participant_group'));
                        } elseif ((Input::get('study_name') == 'RAB002')) {
                            $user->generateScheduleRAB002(Input::get('study_name'), Input::get('client_id'), $date = date('Y-m-d', strtotime(Input::get('visit_date'))), 1, 'c', Input::get('participant_group'));
                        } elseif ((Input::get('study_name') == 'EBL08')) {
                            $user->generateScheduleEBL08(Input::get('study_name'), Input::get('client_id'), $date = date('Y-m-d', strtotime(Input::get('visit_date'))), 1, 'c', Input::get('participant_group'));
                        } elseif ((Input::get('study_name') == 'HELP-OFZ')) {
                            $user->generateScheduleHELP(Input::get('study_name'), Input::get('client_id'), $date = date('Y-m-d', strtotime(Input::get('visit_date'))), 1, 'c', Input::get('participant_group'));
                        }

                        $user->updateRecord('details', array(
                            'status' => 'Enrolled'
                        ), Input::get('participant_id'));

                        $user->updateRecord('clients', array(
                            'status' => 1
                        ), Input::get('participant_id'));

                        $successMessage = 'Schedules Added Successful';
                        print_r($successMessage);
                    } else {
                        $errorMessage = 'Patient Schedules already exist';
                    }
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

        <?php
        // require 'topBar.php'
        ?>

        <?php
        // require 'sideBar.php'
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">

                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>
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
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard1.php">Home</a></li>
                                <li class="breadcrumb-item active">Enroll</li>
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
                            <h4>Enroll <small>Subjetcs</small></h4>
                        </div>
                    </div>
                    <!-- ./row -->
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <div class="card card-primary card-tabs">
                                <div class="card-header p-0 pt-1">
                                    <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                                        <li class="pt-2 px-3">
                                            <h3 class="card-title">Form Title</h3>
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

                                                    <div class="col-sm-4">
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

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Group:</label>
                                                            <select class="form-control" id="group" name="group" required>
                                                                <option value="">Select Group</option>
                                                                <?php foreach ($override->getData('patient_group') as $group) { ?>
                                                                    <option value="<?= $group['id'] ?>"><?= $group['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Subjetc ID:</label>
                                                            <select name="client_id" id="client_id" class="form-control" style="width: 100%;" tabindex="-1">
                                                                <option value="">SELECT SUBJECT ID</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Visit Type:</label>
                                                            <select name="visit_code" id="visit_code" class="form-control" style="width: 100%;" tabindex="-1" required="">
                                                                <option value="">SELECT VISIT TYPE</option>
                                                                <option value="Clinic">Clinic</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Investigation Product:</label>
                                                            <input type="text" name="imp" class="datepicker form-control" value="" required />
                                                        </div>
                                                    </div>



                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>IMP DATE</label>
                                                            <input type="date" class="form-control fas fa-calendar input-prefix" name="visit_date" id="visit_date" required="" />
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                                                    <input type="hidden" name="participant_group" id="participant_group" class="form-control" value="" />
                                                    <input type="hidden" name="participant_id" id="participant_id" class="form-control" value="" />
                                                    <input type="submit" name="add_visit" value="ADD" class="btn btn-success">
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
        $('#study').change(function() {
            var studyID = $(this).val();
            $('#s1').hide();
            $('#waitS1').show();
            $.ajax({
                url: "process.php?content=visit",
                method: "GET",
                data: {
                    studyID: studyID
                },
                dataType: "text",
                success: function(data) {
                    $('#v_code').html(data);
                    $('#s1').show();
                    $('#waitS1').hide();
                }
            });
        });

        $('#project_name').change(function() {
            var study_name = $(this).val();
            // $('#fl_wait').show();
            $.ajax({
                url: "process.php?content=client_id",
                method: "GET",
                data: {
                    study_name: study_name
                },
                success: function(data) {
                    $('#client_id').html(data);
                    // $('#fl_wait').hide();
                    // console.log(data);
                }
            });

        });

        $('#client_id').change(function() {
            var client_id = $(this).val();
            // $('#fl_wait').show();
            $.ajax({
                url: "process.php?content=participant_id",
                method: "GET",
                data: {
                    client_id: client_id
                },
                dataType: "json",
                success: function(data) {
                    $('#participant_id').val(data.participant_id);
                    // $('#fl_wait').hide();
                    // console.log(data);
                    console.log(data.participant_id);
                }
            });

        });

        $('#group').change(function() {
            var patient_group_name = $(this).val();
            // $('#fl_wait').show();
            $.ajax({
                url: "process.php?content=participant_group_id",
                method: "GET",
                data: {
                    patient_group_name: patient_group_name
                },
                dataType: "json",
                success: function(data) {
                    console.log(data.participant_group_id);
                    $('#participant_group').val(data.participant_group_id);
                }
            });

        });

        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    });
</script>

</html>