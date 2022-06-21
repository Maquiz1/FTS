<?php error_reporting(E_ALL ^ E_NOTICE); ?>
<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
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
/*$d1=strtotime('7/1/2019');
$d2=strtotime('7/5/2019');
$r = $d2-$d1;
print_r($r/86400);*/
if ($_GET['id'] == 11) {
    $col1 = 0;
    $col2 = 12;
} else {
    $col1 = 2;
    $col2 = 10;
}
if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
        if (Input::get('edit_participant')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'project_id' => array(
                    'required' => true,
                ),
                'initial' => array(
                    'required' => true,
                ),
                'sensitization_one' => array(
                    'required' => true,
                ),
                'sensitization_two' => array(
                    'required' => true,
                ),
                'sensitization_no' => array(
                    'required' => true,
                ),
                'client_category' => array(
                    'required' => true,
                ),
                'fname' => array(
                    'required' => true,
                ),
                'mname' => array(
                    'required' => true,
                ),
                'lname' => array(
                    'required' => true,
                ),
                'dob' => array(
                    'required' => true,
                ),
                'gender' => array(
                    'required' => true,
                ),
                'phone1' => array(
                    'required' => true,
                    // 'unique' => 'details'
                ),
                'attend_school' => array(
                    'required' => true,
                ),
                'region' => array(
                    'required' => true,
                ),
                'district' => array(
                    'required' => true,
                ),
                'ward' => array(
                    'required' => true,
                ),
                'village' => array(
                    'required' => true,
                ),
                'hamlet' => array(
                    'required' => true,
                ),
                'duration' => array(
                    'required' => true,
                ),
                'willing_contact' => array(
                    'required' => true,
                ),
                'location' => array(
                    'required' => true,
                ),
                'status' => array(
                    'required' => true,
                )
            ));
            if ($validate->passed()) {
                try {
                    $dob_date = date('Y-m-d', strtotime(Input::get('dob')));
                    $user->updateRecord('details', array(
                        'project_name' => Input::get('project_id'),
                        'initial' => Input::get('initial'),
                        'sensitization_one' => Input::get('sensitization_one'),
                        'sensitization_two' => Input::get('sensitization_two'),
                        'sensitization_no' => Input::get('sensitization_no'),
                        'client_category' => Input::get('client_category'),
                        'fname' => Input::get('fname'),
                        'mname' => Input::get('mname'),
                        'lname' => Input::get('lname'),
                        'dob' => $dob_date,
                        'gender' => Input::get('gender'),
                        'phone1' => Input::get('phone1'),
                        'phone2' => Input::get('phone2'),
                        'attend_school' => Input::get('attend_school'),
                        'region' => Input::get('region'),
                        'district' => Input::get('district'),
                        'ward' => Input::get('ward'),
                        'village' => Input::get('village'),
                        'hamlet' => Input::get('hamlet'),
                        'duration' => Input::get('duration'),
                        'willing_contact' => Input::get('willing_contact'),
                        'location' => Input::get('location'),
                        'staff_id' => $user->data()->id,
                        'status' => Input::get('status'),
                        'reason' => Input::get('reason'),
                        'other_reason' => Input::get('other_reason'),
                        'death_date' => $death_date,
                        'details' => Input::get('details'),
                        'end_study' => Input::get('end_study'),
                    ), Input::get('id'));
                    $successMessage = 'Client Info Updated Successful';
                    // print_r($successMessage);
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
                            <h1 class="m-0">Terminated List</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard1.php">Home</a></li>
                                <li class="breadcrumb-item active">Terminated</li>
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
                                <h3 class="card-title">Current List of Terminated Subjects</h3>
                            </div>


                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SUBJECT ID</th>
                                            <th>INITIAL</th>   
                                            <th>Study</th>         
                                            <th>Status</th>
                                            <th>Reason</th>
                                            <th>Details</th>
                                            <th>Last Contact Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $y = 1;
                                        foreach ($override->getDataOrderByAsc('clients', 'study_id', 'status', 0) as $client) {
                                            $lastVisit = $override->getlastRow('visit', 'client_id', $client['id'], 'id') ?>
                                            <tr>
                                                <td><?= $client['study_id'] ?></td>
                                                <td><?= $client['initials'] ?></td>
                                                <td><?= $override->get('study', 'id', $client['project_id'])[0]['study_code'] ?></td>
                                                <td>
                                                    <?php
                                                    if ($client['current_status'] == 2) {
                                                    ?><div class="btn-group btn-group-xs"><button class="btn btn-success">completed</button></div><?php
                                                                                                                                                } elseif ($client['current_status'] == 3) {
                                                                                                                                                    ?>

                                                        <div class="btn-group btn-group-xs"><button class="btn btn-danger">Not completed</button></div>

                                                    <?php
                                                                                                                                                }
                                                    ?>
                                                </td>
                                                <td><?= $client['reason'] ?></td>
                                                <td><?= $client['details'] ?></td>
                                                <td></td>                                                
                                            <?php
                                            $y++;
                                        }
                                            ?>


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
                                    </tbody>
                                    <tr>
                                        <th>SUBJECT ID</th>
                                        <th>INITIAL</th>
                                        <th>Study</th>  
                                        <th>Status</th>
                                        <th>Reason</th>
                                        <th>Details</th>
                                        <th>Last Contact Date</th>
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
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],

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