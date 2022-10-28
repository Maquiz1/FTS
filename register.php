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
        if (Input::get('Register')) {
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
                // 'phone1' => array(
                //     'required' => true,
                //     // 'unique' => 'details'
                // ),
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
                $dob_date = date('Y-m-d', strtotime(Input::get('dob')));
                $intwr_date = date('Y-m-d', strtotime(Input::get('interviewer_date')));
                $rvwr_date = date('Y-m-d', strtotime(Input::get('reviewer_date')));
                $death_date = date('Y-m-d', strtotime(Input::get('death_date')));

                $details = $override->selectData3('details', 'sensitization_no', Input::get('sensitization_no'), 'project_name', Input::get('project_id'))[0];
                $phone = $override->selectData1('details', 'phone', Input::get('phone1'))[0];
                if ($details) {
                    $errorMessage = 'Please re-check Sensitization number For That Study, Already Registered!';
                } else {
                    try {
                        $user->createRecord('details', array(
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
                            'phone' => Input::get('phone1'),
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
                        ));
                        $successMessage = 'Client Registered Successful';
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
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

    <link rel="stylesheet" href="http://code.jquery.com/ui/1.8.3/themes/base/jquery-ui.css" />
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.8.3/jquery-ui.js"></script>
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
                                <li class="breadcrumb-item active">Register Form</li>
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
                            <h4>Register <small>Subjetcs</small></h4>
                        </div>
                    </div>
                    <!-- ./row -->
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <div class="card card-primary card-tabs">
                                <div class="card-header p-0 pt-1">
                                    <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                                        <!-- <li class="pt-2 px-3">
                                            <h3 class="card-title">Register Form</h3>
                                        </li> -->
                                        <li class="nav-item">
                                            <a class="nav-link active" id="tab1" data-toggle="pill" href="#tab-1" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Study Details</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tab2" data-toggle="pill" href="#tab-2" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Demographic</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tab3" data-toggle="pill" href="#tab-3" role="tab" aria-controls="custom-tabs-two-messages" aria-selected="false">Status</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <form method="post">
                                        <div class="tab-content" id="custom-tabs-two-tabContent">
                                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">

                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>STUDY NAME:</label>
                                                            <select class="form-control" id="project_id" name="project_id" value="" required>
                                                                <option value="">Select</option>
                                                                <?php foreach ($override->getData('study') as $group) { ?>
                                                                    <option value="<?= $group['name'] ?>"><?= $group['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>SENSITIZATION NUMBER:</label>
                                                            <input type="text" class="form-control" name="sensitization_no" class="sensitization_no" pattern="\d*" minlength="3" maxlength="3" value="" required />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>SENSITIZATION ONE:</label>
                                                            <select id="sensitization_one" name="sensitization_one" class="form-control" value="" required>
                                                                <option value="">Select</option>
                                                                <?php foreach ($override->getData('yes_no_na') as $lt) { ?>
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
                                                            <label>SENSITIZATION TWO:</label>
                                                            <select id="sensitization_two" name="sensitization_two" class="form-control" value="" required>
                                                                <option value="">Select</option>
                                                                <?php foreach ($override->getData('yes_no_na') as $lt) { ?>
                                                                    <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Category</label>
                                                            <select id="client_category" name="client_category" class="form-control" value="" required>
                                                                <option value="">Select</option>
                                                                <?php foreach ($override->getData('client_category') as $lt) { ?>
                                                                    <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>INITIAL</label>
                                                            <input type="text" name="initial" class="form-control" minlength="3" maxlength="3" value="" required="" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>First Name</label>
                                                            <input type="text" name="fname" class="form-control" value="" required="" />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Midle Name:</label>
                                                            <input type="text" name="mname" class="form-control" value="" required="" />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Last name:</label>
                                                            <input type="text" name="lname" class="form-control" required="" value="" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- DATE OF BIRTH  -->
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>DATE OF BIRTH</label>
                                                            <div class="col-md-10">
                                                                <input type="date" class="form-control fas fa-calendar input-prefix" name="dob" id="dob" value="" required="" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Child attending school?:</label>
                                                            <select id="attend_school" name="attend_school" class="form-control" value="" required>
                                                                <option value="">Select</option>
                                                                <?php foreach ($override->getData('yes_no_na') as $lt) { ?>
                                                                    <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>GENDER:</label>
                                                            <select id="gender" name="gender" class="form-control" value="" required>
                                                                <option value="">Select</option>
                                                                <?php foreach ($override->getData('gender') as $gender) { ?>
                                                                    <option value="<?= $gender['name'] ?>"><?= $gender['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- DATE OF BIRTH  -->


                                                <!-- DATE OF BIRTH  -->
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Phone:</label>
                                                            <input type="text" name="phone1" class="form-control" pattern="\d*" minlength="10" maxlength="10" value="" required />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Phone2:</label>
                                                            <input type="text" name="phone2" class="form-control" pattern="\d*" minlength="10" maxlength="10" value="" />
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="back-cont mt-4">
                                                    <a class="btn btn-primary continue" data-toggle="pill" id="tab2" href="#tab-2" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">
                                                        NEXT
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                                                <div class="row">

                                                    <div class="col-sm-6">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>REGION:</label>
                                                            <select id="region" name="region" class="form-control" value="" required>
                                                                <option value="">Select</option>
                                                                <?php foreach ($override->getData('region') as $lt) { ?>
                                                                    <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>DISTRICT:</label>
                                                            <select id="district" name="district" class="form-control" value="" required>
                                                                <option value="">Select</option>
                                                                <?php foreach ($override->getData('district') as $lt) { ?>
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
                                                            <label>WARD:</label>
                                                            <select id="ward" name="ward" class="form-control" value="" required>
                                                                <option value="">Select</option>
                                                                <?php foreach ($override->getData('ward') as $lt) { ?>
                                                                    <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>VILLAGE:</label>
                                                            <input type="text" name="village" id="village" class="form-control" value="" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Hamlet / Kitongoji:</label>
                                                            <input type="text" name="hamlet" id="hamlet" class="form-control" value="" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="back-cont mt-4">
                                                    <a class="btn btn-primary continue" data-toggle="pill" id="tab2" href="#tab-2" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">
                                                        PREVIOUS
                                                    </a>
                                                    <a class="btn btn-primary continue" data-toggle="pill" id="tab3" href="#tab-3" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">
                                                        NEXT
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="tab-3" role="tabpanel" aria-labelledby="custom-tabs-two-messages-tab">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>For Bagamoyo residents, please specify the intended duration of stay in Bagamoyo:</label>
                                                            <select id="duration" name="duration" class="form-control" value="" required>
                                                                <option value="">Select</option>
                                                                <?php foreach ($override->getData('duration') as $lt) { ?>
                                                                    <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Is the participant willing to be contacted for the next sensitization meeting?:</label>
                                                            <select id="willing_contact" name="willing_contact" class="form-control" value="" required>
                                                                <option value="">Select</option>
                                                                <?php foreach ($override->getData('yes_no') as $lt) { ?>
                                                                    <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <!-- textarea -->
                                                        <div class="form-group">
                                                            <label>Briefly describe participant residential location in relation to the nearest famous neighborhoods:
                                                            </label>
                                                            <textarea name="location" id="location" cols="50%" rows="3" value="" required></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Status:</label>
                                                            <select id="status" name="status" class="form-control" value="" required>
                                                                <option value="">Select</option>
                                                                <?php foreach ($override->getData('status') as $lt) { ?>
                                                                    <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4 box1">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Reason:</label>
                                                            <select id="reason" name="reason" value="" class="form-control">
                                                                <option value="">Select</option>
                                                                <?php foreach ($override->getData('end_study_reason') as $lt) { ?>
                                                                    <option value="<?= $lt['reason'] ?>"><?= $lt['reason'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4 box2">
                                                        <!-- textarea -->
                                                        <div class="form-group">
                                                            <label>Other reason Details:Location(SPECIFY Place of a Participant)</label>
                                                            <textarea name="other_reason" id="other_reason" cols="50%" rows="3" value=""></textarea>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="modal-footer justify-content-between">
                                                    <a class="btn btn-primary continue" data-toggle="pill" id="tab2" href="#tab-2" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">
                                                        PREVIOUS
                                                    </a>
                                                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                                                    <input type="hidden" name="id" value="<?= $staff['id'] ?>">
                                                    <input type="submit" name="Register" value="Submit" class="btn btn-success btn-clean">
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

    <!-- ✅ load jQuery UI ✅ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
</body>


<script type="text/javascript">
    $(document).ready(function() {
        var currentTab = 0;
        $(function() {
            $("#custom-tabs-two-tabContent").tabs({
                select: function(e, i) {
                    currentTab = i.index;
                }
            });
        });
        $(document).on('click', '.btnNext', function() {

            var tabs = $('#custom-tabs-two-tabContent').tabs();
            var c = $('#custom-tabs-two-tabContent').tabs("length");
            currentTab = currentTab == (c - 1) ? currentTab : (currentTab + 1);
            tabs.tabs('select', currentTab);
            $("#btnPrevious").show();
            if (currentTab == (c - 1)) {
                $("#btnNext").hide();
            } else {
                $("#btnNext").show();
            }
        });
        $(document).on('click', '.btnPrevious', function() {
            var tabs = $('#custom-tabs-two-tabContent').tabs();
            var c = $('#custom-tabs-two-tabContent').tabs("length");
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

        $('#status').change(function() {
            var getUid = $(this).val();
            $('#fl_wait').show();
            if (getUid == 'Screening Failure' || getUid == 'Not Enrolled' || getUid == 'Other') {
                $(".box1").show();
                $(".box2").show();
            } else {
                $(".box1").hide();
                $(".box2").hide();
            }

        });
    });
</script>

</html>