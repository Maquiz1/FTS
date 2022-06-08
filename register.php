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
                'phone1' => array(
                    'required' => true,
                    'unique' => 'details'
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
                $dob_date = date('Y-m-d', strtotime(Input::get('dob')));
                $intwr_date = date('Y-m-d', strtotime(Input::get('interviewer_date')));
                $rvwr_date = date('Y-m-d', strtotime(Input::get('reviewer_date')));
                $death_date = date('Y-m-d', strtotime(Input::get('death_date')));
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
                    ));

                    $successMessage = 'Client Registered Successful';
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
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="dashboard1.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="register.php" class="nav-link">Volunteer</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light"><span><?= $country[0]['short_code'] ?></span>
                    <?= $override->get('position', 'id', $user->data()->position)[0]['name'] ?></span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">

                        <?php if ($user->data()->picture) { ?>
                            <img src="<?= $user->data()->picture ?>" class="img-thumbnail img-circle" width="90" height="90" />
                        <?php } else { ?>
                            <img src="assets/users/blank.png" class="img-thumbnail img-circle" />
                        <?php } ?>
                        <!-- <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><span><?= $user->data()->firstname ?></span>
                            <?= $user->data()->lastname ?></a>
                    </div>
                </div>

                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                            with font-awesome or any other icon font library -->
                        <li class="nav-item menu-open">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="dashboard1.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>View Summary</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="dashboard.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>View Today Visit</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <!-- <a href="info.php?id=14" class="nav-link"> -->
                                    <a href="dashboard3.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>MANAGE VOLUNTIER</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item menu-open">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Visit Confirmation
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="dashboard1.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Add Screening</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Add Enrollment</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_unschedule.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Add Un-Schedule</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item menu-open">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Search
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="search.php?id=searchSchedule" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Search Schedule</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

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
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-profile" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Demographic</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-two-messages-tab" data-toggle="pill" href="#custom-tabs-two-messages" role="tab" aria-controls="custom-tabs-two-messages" aria-selected="false">Status</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-two-settings-tab" data-toggle="pill" href="#custom-tabs-two-settings" role="tab" aria-controls="custom-tabs-two-settings" aria-selected="false">Submit</a>
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
                                                            <select class="form-control" id="project_id" name="project_id" required>
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
                                                            <input type="text" class="form-control" name="sensitization_no" class="sensitization_no" pattern="\d*" minlength="3" maxlength="3" required="" />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>SENSITIZATION ONE:</label>
                                                            <select id="sensitization_one" name="sensitization_one" class="form-control" required>
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
                                                            <select id="sensitization_two" name="sensitization_two" class="form-control" required>
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
                                                            <select id="client_category" name="client_category" class="form-control" required>
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
                                                            <input type="text" name="initial" class="form-control" minlength="3" maxlength="3" required="" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>First Name</label>
                                                            <input type="text" name="fname" class="form-control" required="" />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Midle Name:</label>
                                                            <input type="text" name="mname" class="form-control" required="" />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Last name:</label>
                                                            <input type="text" name="lname" class="form-control" required="" />
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
                                                                <input type="date" class="form-control fas fa-calendar input-prefix" name="dob" id="dob" required="" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Child attending school?:</label>
                                                            <select id="attend_school" name="attend_school" class="form-control" required>
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
                                                            <select id="gender" name="gender" class="form-control" required>
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
                                                            <input type="text" name="phone1" class="form-control" pattern="\d*" minlength="10" maxlength="10" required="" />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Phone2:</label>
                                                            <input type="text" name="phone2" class="form-control" pattern="\d*" minlength="10" maxlength="10" />
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="back-cont mt-4">
                                                    <a class="btn btn-primary continue">
                                                        <input type="button" id="btnNext" value="Next" class="btn btn-primary" />
                                                    </a>
                                                </div>
                                            </div>




                                            <div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>GENDER</label>
                                                            <select id="gender" name="gender" class="form-control" required>
                                                                <option value="">Select</option>
                                                                <?php foreach ($override->getData('gender') as $gender) { ?>
                                                                    <option value="<?= $gender['name'] ?>"><?= $gender['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>REGION:</label>
                                                            <select id="region" name="region" class="form-control" required>
                                                                <option value="">Select</option>
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
                                                                <option value="">Select</option>
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
                                                                <option value="">Select</option>
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
                                                                <option value="">Select</option>
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
                                                                <option value="">Select</option>
                                                                <?php foreach ($override->getData('hamlet') as $lt) { ?>
                                                                    <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="back-cont mt-4">
                                                    <a class="btn btn-primary continue">
                                                        <input type="button" id="btnPrevious" value="Previous" class="btn btn-primary" />
                                                    </a>
                                                    <a class="btn btn-primary continue">
                                                        <input type="button" id="btnNext" value="Next" class="btn btn-primary" />
                                                    </a>
                                                </div>
                                            </div>



                                            <div class="tab-pane fade" id="custom-tabs-two-messages" role="tabpanel" aria-labelledby="custom-tabs-two-messages-tab">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>For Bagamoyo residents, please specify the intended duration of stay in Bagamoyo:</label>
                                                            <select id="duration" name="duration" class="form-control" required>
                                                                <option value="">Select</option>
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
                                                                <option value="">Select</option>
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
                                                                <option value="">Select</option>
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
                                                            <textarea name="location" id="location" cols="60%" rows="3" required></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <!-- select -->
                                                        <div class="form-group">
                                                            <label>Status:</label>
                                                            <select id="status" name="status" class="form-control" required>
                                                                <option value="">Select</option>
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
                                                                <option value="">Select</option>
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
                                                            <textarea name="other_reason" id="other_reason" cols="25%" rows="4"></textarea>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="back-cont mt-4">
                                                    <a class="btn btn-primary continue">
                                                        <input type="button" id="btnPrevious" value="Previous" class="btn btn-primary" />
                                                    </a>
                                                    <a class="btn btn-primary continue">
                                                        <input type="button" id="btnNext" value="Next" class="btn btn-primary" />
                                                    </a>
                                                </div>
                                            </div>




                                            <div class="tab-pane fade" id="custom-tabs-two-settings" role="tabpanel" aria-labelledby="custom-tabs-two-settings-tab">
                                                <div class="modal-footer justify-content-between">
                                                    <a class="btn btn-primary continue">
                                                        <input type="button" id="btnPrevious" value="Previous" />
                                                    </a>
                                                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                                                    <!-- <button type="button" class="btn btn-outline-light">Save changes</button> -->
                                                    <input type="hidden" name="id" value="<?= $staff['id'] ?>">
                                                    <input type="submit" name="Register" value="Submit" class="btn btn-success btn-clean">
                                                </div>
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