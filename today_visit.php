<?php error_reporting(E_ALL ^ E_NOTICE); ?>
<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$user->scheduleUpdate();
$user->schedule();
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
        if (Input::get('appointment')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'visit_status' => array(
                    'required' => true,
                ),
            ));
            $getVisit = $override->get('clients', 'id', Input::get('client_id'));
            $getV = $override->getNews('visit', 'id', Input::get('id'), 'visit_date', date('Y-m-d'));
            if ($user->data()->position == 1) {
                $a_status = 'dm_status';
            } elseif ($user->data()->position == 5) {
                $a_status = 'sn_cl_status';
            } elseif ($user->data()->position == 6) {
                $a_status = 'status';
            } elseif ($user->data()->position == 12) {
                $a_status = 'dc_status';
            }
            if ($validate->passed()) {
                try {
                    if ($user->data()->position == 5) {
                        $user->updateRecord('visit', array(
                            $a_status => Input::get('visit_status'),
                            // 'status' => Input::get('visit_status'),
                            'staff_id' => $user->data()->id,
                            'initial2' => Input::get('s_id')
                        ), Input::get('v_id'));
                        $date = null;
                        // $visitCode = $getVisit[0]['visit_code'] + 1;
                        // if ($visitCode) {
                        //     $user->updateRecord('clients', array('visit_code' => $visitCode), Input::get('client_id'));
                        // }
                        $successMessage = 'Visit Added Successful';
                    } elseif ($user->data()->position == 6) {
                        $user->updateRecord('visit', array(
                            $a_status => Input::get('visit_status'),
                            // 'status' => Input::get('visit_status'),
                            'staff_id' => $user->data()->id,
                            'initial1' => Input::get('s_id')
                        ), Input::get('v_id'));
                        $date = null;
                        // $visitCode = $getVisit[0]['visit_code'] + 1;
                        // if ($visitCode) {
                        //     $user->updateRecord('clients', array('visit_code' => $visitCode), Input::get('client_id'));
                        // }
                        $successMessage = 'Visit Added Successful';
                    } elseif ($user->data()->position == 12) {
                        $user->updateRecord('visit', array(
                            $a_status => Input::get('visit_status'),
                            // 'status' => Input::get('visit_status'),
                            'staff_id' => $user->data()->id,
                            'initial3' => Input::get('s_id')
                        ), Input::get('v_id'));
                        $date = null;
                        // $visitCode = $getVisit[0]['visit_code'] + 1;
                        // if ($visitCode) {
                        //     $user->updateRecord('clients', array('visit_code' => $visitCode), Input::get('client_id'));
                        // }
                        $successMessage = 'Visit Added Successful';
                    } else {
                        if ((Input::get('sn') == 1 || Input::get('sn') == 2) && (Input::get('sn2') == 1 || Input::get('sn2') == 2) && (Input::get('sn3') == 1 || Input::get('sn3') == 2)) {
                            $user->updateRecord('visit', array(
                                $a_status => Input::get('visit_status'),
                                'staff_id' => $user->data()->id,
                                'initial4' => Input::get('s_id')
                            ), Input::get('v_id'));
                        } else {
                            $errorMessage = 'Patient must be attended by study nurse, clinician and Data Clerk first';
                        }
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('search')) {
            $link = 'info.php?id=7&cid=' . Input::get('study_id');
            Redirect::to($link);
        } elseif (Input::get('reschedule_visit')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'reschedule_id' => array(
                    'required' => true,
                ),
                'reschedule_date' => array(
                    'required' => true,
                ),
                'details' => array(
                    'required' => true,
                ),
                'reason' => array(
                    'required' => true,
                )
            ));
            if ($validate->passed()) {
                try {
                    $date = date('Y-m-d', strtotime(Input::get('reschedule_date')));
                    $user->updateRecord('visit', array(
                        'visit_date' => $date,
                        'details' => Input::get('details'),
                        'reason' => Input::get('reason')
                    ), Input::get('reschedule_id'));

                    $successMessage = 'Visit Re - Scheduled Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('reschedule_vaccine')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),
                'visit' => array(
                    'required' => true,
                ),
                'project_name' => array(
                    'required' => true,
                ),
                'group_name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
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
                    $successMessage = 'Reschedule Successful';
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


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IHI - KINGANI | List of Registered</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">

    <style>
        thead input {
            width: 100%;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
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
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Enrolled List</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard1.php">Home</a></li>
                                <li class="breadcrumb-item active">Enrolled</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
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
                    <div class="col-lg-12">
                        <!-- /.card -->


                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Current List of Enrolled Subjects</h3>
                            </div>


                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">STUDY ID</th>
                                            <th width="5%">STUDY NAME</th>
                                            <th width="3%">GROUP NAME</th>
                                            <th width="3%">VISIT CODE</th>
                                            <th width="3%">SCHEDLUE TYPE</th>
                                            <th width="3%">VISIT TYPE</th>
                                            <th width="5%">VISIT STATUS</th>
                                            <?php
                                            if ($user->data()->power == 1) {
                                            ?>
                                                <th width="5%">CLINICIAN STATUS</th>
                                                <th width="5%">DATACLERK STATUS</th>
                                                <th width="5%">DATAMANAGER STATUS</th>
                                                <th width="3%">PHONE NUMBER</th>
                                                <th width="3%">reschedule</th>
                                            <?php } ?>
                                            <?php
                                            if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
                                            ?>
                                                <th width="3%">Action</th>
                                                <th width="3%">Edit Visit</th>


                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php $x = 1;
                                        foreach ($override->get('visit', 'visit_date', date('Y-m-d')) as $data) {
                                            $client = $override->get('clients', 'id', $data['client_id'])[0];
                                            $lastVisit = $override->getlastRow('visit', 'client_id', $data['client_id'], 'visit_date');
                                            if ($client['status'] == 1) {
                                                //This need improvement so as to increase performance( find a way to just update it once)
                                                if ($data['status'] == 0) {
                                                    $user->updateRecord('visit', array('status' => 3), $data['id']);
                                                }
                                        ?>
                                                <tr>

                                                    <td>
                                                        <div class="btn-group btn-group-xs"><a href="info.php?id=6&cid=<?= $client['id']  ?>" class="btn btn-success btn-clean"><span class="icon-eye-open"></span> <?= $client['study_id'];  ?></a></div>
                                                    </td>
                                                    <td><?= $override->get('study', 'id', $client['project_id'])[0]['study_code'] ?></td>
                                                    <td><?= $override->get('patient_group', 'id', $client['pt_group'])[0]['name'] ?></td>
                                                    <td><?= $data['visit_code'] ?></td>


                                                    <td>

                                                        <?php if ($data['schedule'] == 'Scheduled') { ?>
                                                            <div class="btn-group btn-group-xs">
                                                                <button class="btn btn-info">
                                                                    <?= $data['schedule'] ?>
                                                                </button>
                                                            </div>

                                                        <?php } else { ?>
                                                            <div class="btn-group btn-group-xs">
                                                                <button class="btn btn-danger">
                                                                    <?= $data['schedule'] ?>
                                                                </button>
                                                            </div>
                                                        <?php }  ?>
                                                    </td>


                                                    <td>
                                                        <?php if ($data['visit_type'] == 'Clinic') { ?>
                                                            <div class="btn-group btn-group-xs">
                                                                <button class="btn btn-success">
                                                                    <?= $data['visit_type'] ?>
                                                                </button>
                                                            </div>

                                                        <?php } else { ?>
                                                            <div class="btn-group btn-group-xs">
                                                                <button class="btn btn-info">
                                                                    <?= $data['visit_type'] ?>
                                                                </button>
                                                            </div>
                                                        <?php }  ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-xs">
                                                            <?php if ($data['status'] == 3) { ?>&nbsp;
                                                            <button class="btn btn-warning">Pending</button>
                                                        <?php } elseif ($data['status'] == 1) { ?>
                                                            <button class="btn btn-success">Completed</button><button class="btn btn-info"><?= $data['initial1'] ?></button>
                                                        <?php } elseif ($data['status'] == 2) { ?>
                                                            <button class="btn btn-danger">Missed</button><button class="btn btn-info"><?= $data['initial1'] ?></button>
                                                        <?php } ?>
                                                        </div>
                                                    </td>

                                                    <?php
                                                    if ($user->data()->power == 1) {
                                                    ?>
                                                        <td>
                                                            <div class="btn-group btn-group-xs">
                                                                <?php
                                                                if ($data['sn_cl_status'] == 0) {
                                                                ?>&nbsp;
                                                                <button class="btn btn-warning">Pending</button>
                                                            <?php
                                                                } elseif ($data['sn_cl_status'] == 1) {
                                                            ?>
                                                                <button class="btn btn-success">Reviewed </button><button class="btn btn-info"><?= $data['initial2']
                                                                                                                                                ?></button>
                                                            <?php
                                                                } elseif ($data['sn_cl_status'] == 2) {
                                                            ?>
                                                                <button class="btn btn-danger">Missed</button><button class="btn btn-info"><?= $data['initial2']
                                                                                                                                            ?></button>
                                                            <?php }
                                                            ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-xs">
                                                                <?php if ($data['dc_status'] == 0) { ?>&nbsp;
                                                                <button class="btn btn-warning">Pending</button>
                                                            <?php } elseif ($data['dc_status'] == 1) { ?>
                                                                <button class="btn btn-success">Entered</button><button class="btn btn-info"><?= $data['initial3'] ?></button>
                                                            <?php } elseif ($data['dc_status'] == 2) { ?>
                                                                <button class="btn btn-danger">Missed</button><button class="btn btn-info"><?= $data['initial3'] ?></button>
                                                            <?php } ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-xs">
                                                                <?php if ($data['dm_status'] == 0) { ?>&nbsp;
                                                                <button class="btn btn-warning">Pending</button>
                                                            <?php } elseif ($data['dm_status'] == 1) { ?>
                                                                <button class="btn btn-success">Reviewed</button><button class="btn btn-info"><?= $data['initial4'] ?></button>
                                                            <?php } elseif ($data['dm_status'] == 2) { ?>
                                                                <button class="btn btn-danger">Missed</button><button class="btn btn-info"><?= $data['initial4'] ?></button>
                                                            <?php } ?>
                                                            </div>
                                                        </td>
                                                        <td><?= $client['phone_number'] ?></td>

                                                        <td>

                                                            <a href="#re_schedule_vaccine<?= $y ?>" data-toggle="modal" class="widget-icon" title="Re - Schedule Pre - vacc and Vaccination"><span class="icon-refresh"></span></a>
                                                        </td>

                                                    <?php }  ?>

                                                    <?php

                                                    if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
                                                    ?>


                                                        <td>
                                                            <a href="#appnt<?= $x ?>" data-toggle="modal" class="widget-icon" title="Add Visit"><span class="icon-share"></span></a>

                                                        </td>

                                                        <td>

                                                            <div><a href="#edit_visit<?= $x ?>" data-toggle="modal" class="widget-icon" title="Edit Scheduled and Un-scheduled Visit"><span class="icon-edit"></span></a></div>
                                                        </td>




                                                    <?php } ?>


                                                    <!-- Main content -->
                                                    <section class="content">
                                                        <div class="modal fade" id="edit_participant<?= $y ?>">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content bg-info">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">EDIT INFO</h4>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <form method="post">
                                                                        <div class="modal-body">

                                                                            <div class="row">
                                                                                <div class="col-sm-3">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>STUDY NAME:</label>
                                                                                        <select class="form-control" id="project_id" name="project_id" required>
                                                                                            <option value="<?= $staff['project_name']; ?>"><?= $staff['project_name']; ?></option>
                                                                                            <?php foreach ($override->getData('study') as $group) { ?>
                                                                                                <option value="<?= $group['name'] ?>"><?= $group['name'] ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>



                                                                                <div class="col-sm-3">
                                                                                    <div class="form-group">
                                                                                        <label>SENSITIZATION NO</label>
                                                                                        <input type="text" name="sensitization_no" class="form-control" value="<?= $staff['sensitization_no']; ?>" minlength="3" maxlength="3" required="" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-3">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>SENSITIZATION ONE:</label>
                                                                                        <select id="sensitization_one" name="sensitization_one" class="form-control" required>
                                                                                            <option value="<?= $staff['sensitization_one']; ?>"><?= $staff['sensitization_one']; ?></option>
                                                                                            <?php foreach ($override->getData('yes_no_na') as $lt) { ?>
                                                                                                <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-3">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>SENSITIZATION TWO:</label>
                                                                                        <select id="sensitization_two" name="sensitization_two" class="form-control" required>
                                                                                            <option value="<?= $staff['sensitization_two']; ?>"><?= $staff['sensitization_two']; ?></option>
                                                                                            <?php foreach ($override->getData('yes_no_na') as $lt) { ?>
                                                                                                <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                            <div class="row">

                                                                                <div class="col-sm-3">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>INITIAL</label>
                                                                                        <input type="text" name="initial" class="form-control" value="<?= $staff['initial']; ?>" minlength="3" maxlength="3" required="" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-3">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>First Name</label>
                                                                                        <input type="text" name="fname" class="form-control" value="<?= $staff['fname'] ?>" required="" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-3">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Midle Name:</label>
                                                                                        <input type="text" name="mname" class="form-control" value="<?= $staff['mname'] ?>" required="" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-3">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Last name:</label>
                                                                                        <input type="text" name="lname" class="form-control" value="<?= $staff['lname'] ?>" required="" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- DATE OF BIRTH  -->
                                                                            <div class="row">
                                                                                <div class="col-sm-3">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>DATE OF BIRTH</label>
                                                                                        <div class="col-md-10">
                                                                                            <input type="date" class="form-control fas fa-calendar input-prefix" value="<?= $staff['dob']; ?>" name="dob" id="dob" required="" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-3">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Child attending school?:</label>
                                                                                        <select id="attend_school" name="attend_school" class="form-control" required>
                                                                                            <option value="<?= $staff['attend_school']; ?>"><?= $staff['attend_school']; ?></option>
                                                                                            <?php foreach ($override->getData('yes_no_na') as $lt) { ?>
                                                                                                <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-3">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>GENDER:</label>
                                                                                        <select id="gender" name="gender" class="form-control" required>
                                                                                            <option value="<?= $staff['gender']; ?>"><?= $staff['gender']; ?></option>
                                                                                            <?php foreach ($override->getData('gender') as $gender) { ?>
                                                                                                <option value="<?= $gender['name'] ?>"><?= $gender['name'] ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-3">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Phone:</label>
                                                                                        <input type="text" name="phone1" class="form-control" value="<?= $staff['phone1']; ?>" pattern="\d*" minlength="10" maxlength="10" required="" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- DEMOGRAPHIC  -->
                                                                            <div class="row">
                                                                                <div class="col-sm-4">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Phone2:</label>
                                                                                        <input type="text" name="phone2" class="form-control" value="<?= $staff['phone2']; ?>" pattern="\d*" minlength="10" maxlength="10" required="" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-4">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>REGION:</label>
                                                                                        <select id="region" name="region" class="form-control" required>
                                                                                            <option value="<?= $staff['region']; ?>"><?= $staff['region']; ?></option>
                                                                                            <?php foreach ($override->getData('region') as $lt) { ?>
                                                                                                <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>DISTRICT:</label>
                                                                                        <select id="district" name="district" class="form-control" required>
                                                                                            <option value="<?= $staff['district']; ?>"><?= $staff['district']; ?></option>
                                                                                            <?php foreach ($override->getData('district') as $lt) { ?>
                                                                                                <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-sm-4">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>WARD:</label>
                                                                                        <select id="ward" name="ward" class="form-control" required>
                                                                                            <option value="<?= $staff['ward']; ?>"><?= $staff['ward']; ?></option>
                                                                                            <?php foreach ($override->getData('ward') as $lt) { ?>
                                                                                                <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>VILLAGE:</label>
                                                                                        <select id="village" name="village" class="form-control">
                                                                                            <option value="<?= $staff['village']; ?>"><?= $staff['village']; ?></option>
                                                                                            <?php foreach ($override->getData('village') as $lt) { ?>
                                                                                                <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Hamlet / Kitongoji:</label>
                                                                                        <select id="hamlet" name="hamlet" class="form-control" required>
                                                                                            <option value="<?= $staff['hamlet']; ?>"><?= $staff['hamlet']; ?></option>
                                                                                            <?php foreach ($override->getData('hamlet') as $lt) { ?>
                                                                                                <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>For Bagamoyo residents, please specify the intended duration of stay in Bagamoyo:</label>
                                                                                        <select id="duration" name="duration" class="form-control" required>
                                                                                            <option value="<?= $staff['duration']; ?>"><?= $staff['duration']; ?></option>
                                                                                            <?php foreach ($override->getData('duration') as $lt) { ?>
                                                                                                <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-6">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Is the participant willing to be contacted for the next sensitization meeting?:</label>
                                                                                        <select id="willing_contact" name="willing_contact" class="form-control" required>
                                                                                            <option value="<?= $staff['willing_contact']; ?>"><?= $staff['willing_contact']; ?></option>
                                                                                            <?php foreach ($override->getData('yes_no') as $lt) { ?>
                                                                                                <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">

                                                                                <div class="col-sm-4">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Briefly describe participant residential location in relation to the nearest famous neighborhoods:</label>
                                                                                        <select id="willing_contact" name="willing_contact" class="form-control" required>
                                                                                            <option value="<?= $staff['willing_contact']; ?>"><?= $staff['willing_contact']; ?></option>
                                                                                            <?php foreach ($override->getData('yes_no') as $lt) { ?>
                                                                                                <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-8">
                                                                                    <!-- textarea -->
                                                                                    <div class="form-group">
                                                                                        <label>Location(SPECIFY Place of a Participant)</label>
                                                                                        <textarea name="location" id="location" value="<?= $staff['location']; ?>" cols="60%" rows="3" required></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-sm-4">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Status:</label>
                                                                                        <select id="status" name="status" class="form-control" required>
                                                                                            <option value="<?= $staff['status']; ?>"><?= $staff['status']; ?></option>
                                                                                            <?php foreach ($override->getData('status') as $lt) { ?>
                                                                                                <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-4">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Reason:</label>
                                                                                        <select id="reason" name="reason" class="form-control">
                                                                                            <option value="<?= $staff['reason']; ?>"><?= $staff['reason']; ?></option>
                                                                                            <?php foreach ($override->getData('end_study_reason') as $lt) { ?>
                                                                                                <option value="<?= $lt['reason'] ?>"><?= $lt['reason'] ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-4">
                                                                                    <!-- textarea -->
                                                                                    <div class="form-group">
                                                                                        <label>Other reason Details:</label>
                                                                                        <textarea name="other_reason" id="other_reason" value="<?= $staff['other_reason']; ?>" cols="25%" rows="4"></textarea>
                                                                                    </div>
                                                                                </div>

                                                                            </div>

                                                                            <div class="modal-footer justify-content-between">
                                                                                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                                                                                <!-- <button type="button" class="btn btn-outline-light">Save changes</button> -->
                                                                                <input type="hidden" name="id" value="<?= $staff['id'] ?>">
                                                                                <input type="submit" name="edit_participant" value="Submit" class="btn btn-success btn-clean">
                                                                            </div>
                                                                    </form>

                                                                </div>
                                                                <!-- /.modal-content -->
                                                            </div>
                                                            <!-- /.modal-dialog -->
                                                        </div>
                                                        <!-- /.modal -->
                                                    </section>
                                                    <!-- /.content -->

                                            <?php }
                                            $x++;
                                        } ?>
                                    </tbody>
                                    <tr>
                                            <th width="5%">STUDY ID</th>
                                            <th width="5%">STUDY NAME</th>
                                            <th width="3%">GROUP NAME</th>
                                            <th width="3%">VISIT CODE</th>
                                            <th width="3%">SCHEDLUE TYPE</th>
                                            <th width="3%">VISIT TYPE</th>
                                            <th width="5%">VISIT STATUS</th>
                                            <?php
                                            if ($user->data()->power == 1) {
                                            ?>
                                                <th width="5%">CLINICIAN STATUS</th>
                                                <th width="5%">DATACLERK STATUS</th>
                                                <th width="5%">DATAMANAGER STATUS</th>
                                                <th width="3%">PHONE NUMBER</th>
                                                <th width="3%">reschedule</th>
                                            <?php } ?>
                                            <?php
                                            if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
                                            ?>
                                                <th width="3%">Action</th>
                                                <th width="3%">Edit Visit</th>


                                            <?php } ?>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- /.content-wrapper -->
    <?php include 'footerNav.php' ?>
    </div>
    <!-- ./wrapper -->


    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE -->
    <script src="dist/js/adminlte.js"></script>

    <!-- OPTIONAL SCRIPTS -->
    <script src="plugins/chart.js/Chart.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard3.js"></script>

    <!-- DataTables  & Plugins -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/jszip/jszip.min.js"></script>
    <script src="plugins/pdfmake/pdfmake.min.js"></script>
    <script src="plugins/pdfmake/vfs_fonts.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- AdminLTE App -->



    <script>
        $(document).ready(function() {
            // $("#example1").DataTable({
            //     "responsive": true,
            //     "lengthChange": false,
            //     "autoWidth": false,
            //     // "lBfrtip": true,
            //     "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            // }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });

            // var table = $('#example1').DataTable();

            // var filteredData = table
            //     .columns([0, 1])
            //     .data()
            //     .flatten()
            //     .filter(function(value, index) {
            //         return value > 20 ? true : false;
            //     });


            // Setup - add a text input to each footer cell
            $('#example1 thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#example1 thead');

            var table = $('#example1').DataTable({

                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "lBfrtip": true,
                // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "buttons": ["excel", "pdf"],

                orderCellsTop: true,
                fixedHeader: true,
                initComplete: function() {
                    var api = this.api();

                    // For each column
                    api
                        .columns()
                        .eq(0)
                        .each(function(colIdx) {
                            // Set the header cell to contain the input element
                            var cell = $('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                            var title = $(cell).text();
                            $(cell).html('<input type="text" placeholder="' + title + '" />');

                            // On every keypress in this input
                            $(
                                    'input',
                                    $('.filters th').eq($(api.column(colIdx).header()).index())
                                )
                                .off('keyup change')
                                .on('change', function(e) {
                                    // Get the search value
                                    $(this).attr('title', $(this).val());
                                    var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                    var cursorPosition = this.selectionStart;
                                    // Search the column for that value
                                    api
                                        .column(colIdx)
                                        .search(
                                            this.value != '' ?
                                            regexr.replace('{search}', '(((' + this.value + ')))') :
                                            '',
                                            this.value != '',
                                            this.value == ''
                                        )
                                        .draw();
                                })
                                .on('keyup', function(e) {
                                    e.stopPropagation();

                                    $(this).trigger('change');
                                    $(this)
                                        .focus()[0]
                                        .setSelectionRange(cursorPosition, cursorPosition);
                                });
                        });
                },
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
</body>

</html>