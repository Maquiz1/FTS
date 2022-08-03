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
$email = new Email();
$random = new Random();
$countries = null;
$checkError = false;
$date = null;
//modification remove all pilot crf have been removed/deleted from study crf
if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
        if (Input::get('add_staff')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'firstname' => array(
                    'required' => true,
                    'min' => 3,
                ),
                'lastname' => array(
                    'required' => true,
                    'min' => 3,
                ),
                'country_id' => array(
                    'required' => true,
                ),
                'site_id' => array(
                    'required' => true,
                ),
                'position' => array(
                    'required' => true,
                ),
                'username' => array(
                    'required' => true,
                    'unique' => 'staff'
                ),
                'phone_number' => array(
                    'required' => true,
                    'unique' => 'staff'
                ),
                'email_address' => array(
                    'required' => true,
                    'unique' => 'staff'
                ),
            ));
            if ($validate->passed()) {
                $salt = $random->get_rand_alphanumeric(32);
                $password = '123456';
                switch (Input::get('position')) {
                    case 1:
                        $accessLevel = 1;
                        break;
                    case 2:
                        $accessLevel = 1;
                        break;
                    case 3:
                        $accessLevel = 1;
                        break;
                    default:
                        $accessLevel = 4;
                }
                try {
                    $user->createRecord('staff', array(
                        'firstname' => Input::get('firstname'),
                        'lastname' => Input::get('lastname'),
                        'position' => Input::get('position'),
                        'username' => Input::get('username'),
                        'password' => Hash::make($password, $salt),
                        'salt' => $salt,
                        'reg_date' => date('Y-m-d'),
                        'access_level' => $accessLevel,
                        'phone_number' => Input::get('phone_number'),
                        'email_address' => Input::get('email_address'),
                        'c_id' => Input::get('country_id'),
                        's_id' => Input::get('site_id'),
                        'status' => 1,
                        'pswd' => 0,
                        'last_login' => '',
                        'picture' => '',
                        'token' => '',
                        'power' => 0,
                        'count' => 0,
                        'staff_id' => $user->data()->id
                    ));
                    $email->sendEmail(Input::get('email_address'), Input::get('firstname'), Input::get('username'), $password, 'Account Creation');
                    $successMessage = 'Staff Registered Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_ward')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'ward_name' => array(
                    'required' => true,
                ),
                'region_id' => array(
                    'required' => true,
                ),
                'district_id' => array(
                    'required' => true,
                ),
                'short_code' => array(
                    'required' => true,
                    'min' => 3,
                )
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('ward', array(
                        'name' => Input::get('ward_name'),
                        'region_id' => Input::get('region_id'),
                        'district_id' => Input::get('district_id'),
                        'short_code' => Input::get('short_code'),
                        'status' => 1
                    ));
                    $successMessage = 'Ward Registered Successful';
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
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#add_staff" data-toggle="modal" class="nav-link">ADD STAFF</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#add_ward" data-toggle="modal" class="nav-link">ADD WARD</a>
        </li>
        <!-- <li><a href="#add_staff" data-toggle="modal" data-backdrop="static" data-keyboard="false"><span class="icon-user"></span>&nbsp;&nbsp;</a></li>
        <li><a href="#add_ward" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD WARD</a></li> -->

        <!-- <li class="nav-item d-none d-sm-inline-block">
                    <a href="index3.html" class="nav-link">Visit Confirmation</a>
                </li>

                <li class="nav-item d-none d-sm-inline-block">
                    <a href="index3.html" class="nav-link">management</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="index3.html" class="nav-link">View Records</a>
                </li> -->
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

<div class="modal" id="add_staff" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">NEW STAFF</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">First Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="firstname" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Last Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="lastname" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Country:</div>
                            <div class="col-md-10">
                                <select class="form-control" id="country" name="country_id" required="">
                                    <option value="">Select Country</option>
                                    <?php if ($user->data()->access_level == 1 || $user->data()->access_level == 2 || $user->data()->access_level == 3) {
                                        $countries = $override->get('country', 'status', 1);
                                    } elseif ($user->data()->access_level == 4) {
                                        $countries = $override->getNews('country', 'id', $user->data()->c_id, 'status', 1);
                                    }
                                    foreach ($countries as $country) { ?>
                                        <option value="<?= $country['id'] ?>"><?= $country['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div id="waitSty" style="display:none;" class="col-md-offset-5 col-md-1"><img src='img/owl/spinner-mini.gif' width="12" height="12" /><br>Loading..</div>
                        <div class="form-row" id="st">
                            <div class="col-md-2">Site:</div>
                            <div class="col-md-10">
                                <select class="form-control" id="site" name="site_id" required="">
                                    <option value="">Select Site</option>
                                    <?php foreach ($override->getData('site') as $site) { ?>
                                        <option value="<?= $site['id'] ?>"><?= $site['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Position:</div>
                            <div class="col-md-10">
                                <select class="form-control" name="position" required="">
                                    <!-- you need to properly manage positions -->
                                    <option value="">Select Position</option>
                                    <?php foreach ($override->getData('position') as $position) {
                                        if ($user->data()->access_level == 1 && $user->data()->power == 1) { ?>
                                            <option value="<?= $position['id'] ?>"><?= $position['name'] ?></option>
                                        <?php } elseif ($user->data()->access_level == 1 && $position['name'] != 'Principle Investigator') { ?>
                                            <option value="<?= $position['id'] ?>"><?= $position['name'] ?></option>
                                        <?php } elseif ($user->data()->access_level == 2 || $user->data()->access_level == 3 && $position['name'] != 'Coordinator' && $position['name'] != 'Principle Investigator') { ?>
                                            <option value="<?= $position['id'] ?>"><?= $position['name'] ?></option>
                                        <?php } elseif ($user->data()->access_level == 4 && $position['name'] != 'Coordinator' && $position['name'] != 'Principle Investigator' && $position['name'] != 'Data Manager' /*&& $position['name'] !='Country Coordinator'*/) { ?>
                                            <option value="<?= $position['id'] ?>"><?= $position['name'] ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Username:</div>
                            <div class="col-md-10">
                                <input type="text" name="username" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Phone:</div>
                            <div class="col-md-10">
                                <input type="text" name="phone_number" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Email:</div>
                            <div class="col-md-10">
                                <input type="text" name="email_address" class="form-control" value="" required="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_staff" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- WARD  -->
<div class="modal" id="add_ward" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD WARD</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">

                        <div class="form-row" id="rg">
                            <div class="col-md-2">REGION:</div>
                            <div class="col-md-10">
                                <select class="form-control" id="region_id" name="region_id" required>
                                    <option value="">SELECT</option>
                                    <?php foreach ($override->getData('region') as $group) { ?>
                                        <option value="<?= $group['id'] ?>"><?= $group['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div id="waitdst" style="display:none;" class="col-md-offset-5 col-md-1"><img src='img/owl/spinner-mini.gif' width="12" height="12" /><br>Loading..</div>
                        <div class="form-row" id="rg">
                            <div class="col-md-2">DISTRICT:</div>
                            <div class="col-md-10">
                                <select class="form-control" id="district_id" name="district_id" required>
                                    <option value="">SELECT</option>
                                    <?php foreach ($override->getData('district') as $group) { ?>
                                        <option value="<?= $group['id'] ?>"><?= $group['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-2">Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="ward_name" id="ward_name" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Short Code:</div>
                            <div class="col-md-10">
                                <input type="text" name="short_code" id="short_code" class="form-control" value="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_ward" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>