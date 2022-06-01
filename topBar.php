<?php error_reporting(E_ALL ^ E_NOTICE); ?>
<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
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
                        'status' => 1,
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

                    $successMessage = 'Client Added Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_staff')) {
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
        } elseif (Input::get('add_gender')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'gender_name' => array(
                    'required' => true,
                    // 'min' => 2,
                )
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('gender', array(
                        'gender_name' => Input::get('gender_name')
                    ));
                    $successMessage = 'Gender Registered Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_country')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'country_name' => array(
                    'required' => true,
                ),
                'short_code' => array(
                    'required' => true,
                    'min' => 2,
                )
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('country', array(
                        'name' => Input::get('country_name'),
                        'short_code' => Input::get('short_code'),
                        'status' => 1
                    ));
                    $successMessage = 'Country Registered Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_region')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'region_name' => array(
                    'required' => true,
                ),
                'short_code' => array(
                    'required' => true,
                    'min' => 3,
                )
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('region', array(
                        'name' => Input::get('region_name'),
                        'short_code' => Input::get('short_code'),
                        'status' => 1
                    ));
                    $successMessage = 'Region Registered Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_district')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'district_name' => array(
                    'required' => true,
                ),
                'region_id' => array(
                    'required' => true,
                ),
                'short_code' => array(
                    'required' => true,
                    'min' => 3,
                )
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('district', array(
                        'name' => Input::get('district_name'),
                        'region_id' => Input::get('region_id'),
                        'short_code' => Input::get('short_code'),
                        'status' => 1
                    ));
                    $successMessage = 'District Registered Successful';
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
        } elseif (Input::get('add_village')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'village_name' => array(
                    'required' => true,
                ),
                'region_id' => array(
                    'required' => true,
                ),
                'district_id' => array(
                    'required' => true,
                ),
                'ward_id' => array(
                    'required' => true,
                ),
                'short_code' => array(
                    'required' => true,
                    'min' => 3,
                )
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('village', array(
                        'region_id' => Input::get('region_id'),
                        'district_id' => Input::get('district_id'),
                        'ward_id' => Input::get('ward_id'),
                        'name' => Input::get('village_name'),
                        'short_code' => Input::get('short_code'),
                        'status' => 1
                    ));
                    $successMessage = 'Village Registered Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_hamlet')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'hamlet_name' => array(
                    'required' => true,
                ),
                'region_id' => array(
                    'required' => true,
                ),
                'district_id' => array(
                    'required' => true,
                ),
                'ward_id' => array(
                    'required' => true,
                ),
                'village_id' => array(
                    'required' => true,
                ),
                'short_code' => array(
                    'required' => true,
                    'min' => 3,
                )
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('hamlet', array(
                        'region_id' => Input::get('region_id'),
                        'district_id' => Input::get('district_id'),
                        'ward_id' => Input::get('ward_id'),
                        'village_id' => Input::get('village_id'),
                        'name' => Input::get('hamlet_name'),
                        'short_code' => Input::get('short_code'),
                        'status' => 1
                    ));
                    $successMessage = 'Hamlet Registered Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_study')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
                'study_code' => array(
                    'required' => true,
                    'min' => 2,
                )
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('study', array(
                        'name' => Input::get('name'),
                        'study_code' => Input::get('study_code'),
                        'sample_size' => Input::get('sample_size'),
                        'duration' => Input::get('duration'),
                        'start_date' => Input::get('start_date'),
                        'end_date' => Input::get('end_date'),
                        'details' => Input::get('details'),
                    ));
                    $successMessage = 'Study Registered Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('end_reason')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'reason' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('end_study_reason', array(
                        'reason' => Input::get('reason'),
                    ));
                    $successMessage = 'Reason Registered Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_occupation')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                )
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('occupation', array(
                        'name' => Input::get('name'),
                        'name_english' => Input::get('name_english'),
                    ));
                    $successMessage = 'Occupation Registered Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_site')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'site_name' => array(
                    'required' => true,
                ),
                'short_code' => array(
                    'required' => true,
                    'min' => 2,
                ),
                'country_id' => array(
                    'required' => true,
                ),

            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('site', array(
                        'name' => Input::get('site_name'),
                        'short_code' => Input::get('short_code'),
                        'c_id' => Input::get('country_id'),
                        'status' => 1
                    ));
                    $successMessage = 'Site Registered Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('search_schedule')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'from_date' => array(
                    'required' => true,
                ),
                'to_date' => array(
                    'required' => true,
                )
            ));
            if ($validate->passed()) {
                try {
                    $link = 'info.php?id=12&from=' . $date = date('Y-m-d', strtotime(Input::get('from_date'))) . '&to=' . $date = date('Y-m-d', strtotime(Input::get('to_date')))  . '&project_id=' . Input::get('project_id');
                    Redirect::to($link);
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_pt_group')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'group_name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('patient_group', array(
                        'name' => Input::get('group_name'),
                    ));
                    $successMessage = 'Group Added Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_image')) {
            // $attachment_file = Input::get('pic');
            if (!empty($_FILES['image']["tmp_name"])) {
                $attach_file = $_FILES['image']['type'];
                if ($attach_file == "image/jpeg" || $attach_file == "image/jpg" || $attach_file == "image/png" || $attach_file == "image/gif" || $attach_file == "image/ico") {
                    $folderName = 'img/project/';
                    $attachment_file = $folderName . basename($_FILES['image']['name']);
                    if (move_uploaded_file($_FILES['image']["tmp_name"], $attachment_file)) {
                        $file = true;
                    } else { {
                            $errorM1 = true;
                            $errorMessage = 'Your Image Not Uploaded ,';
                        }
                    }
                } else {
                    $errorM1 = true;
                    $errorMessage = 'None supported file format';
                } //not supported format
                if ($errorM1 == false) {
                    try {
                        $user->createRecord('images', array(
                            'location' => $attachment_file,
                            'project_id' => Input::get('project'),
                            'cat' => Input::get('image_cat'),
                            'status' => 1
                        ));
                        $successMessage = 'Your Image Uploaded successfully';
                    } catch (Exception $e) {
                        $e->getMessage();
                    }
                }
            } else {
                $errorMessage = 'You have not select any image to upload';
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



<nav class="navbar brb" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-reorder"></span>
        </button>
        <a class="navbar-brand" href="index.php"><img src="<?php if ($logo) {
                                                                echo $logo['location'];
                                                            } else {
                                                                echo 'img/nimrLogo.png';
                                                            } ?>" class="img-thumbnail img-circle" /></a>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
            <li class="active">
                <a href="index.php">
                    <span class="icon-home"></span> dashboard
                </a>
            </li>

            <li class="dropdown active">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-group"></span>&nbsp;&nbsp; VOLUNTIER</a>
                <ul class="dropdown-menu">
                    <li><a href="register.php"><span class="icon-bookmark"></span>&nbsp;&nbsp;REGISTER</a></li>                    
                </ul>
            </li>

            <li class="dropdown active">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-group"></span>&nbsp;&nbsp; VISIT CONFIRMATION</a>
                <ul class="dropdown-menu">
                    <?php
                    if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
                    ?>

                        <li class="">
                            <a href="#add_client" data-toggle="modal" data-backdrop="static" data-keyboard="false"><span class="icon-plus-sign"></span> &nbsp;&nbsp;Add Screening</a>
                        </li>
                        <li class="">
                            <!--<a href="#add_visit" data-toggle="modal" data-backdrop="static" data-keyboard="false" ><span class="icon-bookmark"></span> Add Visit</a>-->
                            <a href="add.php"><span class="icon-bookmark"></span>&nbsp;&nbsp; Add Enrollment</a>

                        </li>
                        <li class="">
                            <!--<a href="#add_visit" data-toggle="modal" data-backdrop="static" data-keyboard="false" ><span class="icon-bookmark"></span> Add Visit</a>-->

                            <a href="add_unschedule.php"><span class="icon-bookmark"></span>&nbsp;&nbsp; Add Un - Scheduled Visit</a>
                        </li>

                    <?php } ?>
                </ul>
            </li>


            <li class="dropdown active">
                <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-file-alt"></span> STUDY </a> -->
                <ul class="dropdown-menu">
                    <?php foreach ($override->getData('study') as $study) { ?>

                        <li class="list-group-item d-flex justify-content-between align-items-center active">
                            <a href="study.php?sid=<?= $study['id'] ?>"><?= $study['name'] ?></a>
                            <span class="badge badge-secondary badge-pill"><?= $override->getCount('visit', 'visit_date', date('Y-m-d')); ?></span>
                        </li>
                    <?php } ?>
                </ul>
            </li>
            <!-- <li class=""> -->
            <!--<a href="#add_visit" data-toggle="modal" data-backdrop="static" data-keyboard="false" ><span class="icon-bookmark"></span> Add Visit</a>-->
            <!-- <a href="info.php?id=15"><span class="icon-bookmark"></span> Enter Days</a> -->

            <!-- </li> -->
            <li class="">
                <a href="#searchSchedule" data-toggle="modal"><span class="icon-search"></span>&nbsp;&nbsp; Search Schedule </a>
            </li>
            <?php
            if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
            ?>


                <?php if ($user->data()->access_level == 1 || $user->data()->access_level == 2 || $user->data()->access_level == 3) { ?>
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-gear"></span>&nbsp;&nbsp; MANAGEMENT</a>
                        <ul class="dropdown-menu">
                            <li><a href="#add_staff" data-toggle="modal" data-backdrop="static" data-keyboard="false"><span class="icon-user"></span>&nbsp;&nbsp;ADD STAFF</a></li>
                            <li><a href="info.php?id=8"><span class="icon-gear"></span>&nbsp;&nbsp; MANAGE STAFF</a></li>
                            <li><a href="info.php?id=14"><span class="icon-gear"></span>&nbsp;&nbsp;MANAGE VOLUNTIER</a></li>
                            <li class="">
                                <a href="profile.php">
                                    <span class="icon-user"></span>&nbsp;&nbsp; Profile
                                </a>
                            </li>

                            <!-- <li class=""> -->
                            <!-- Button trigger modal -->
                            <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_country">
                                    ADD COUNTRY
                                </button>
                            </li> -->
                            <!-- <li><a href="#add_country" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD COUNTRY</a></li> -->

                            <!-- <li><a href="#add_region" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD REGION</a></li> -->
                            <!-- <li class=""> -->
                            <!-- Button trigger modal -->
                            <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#dd_region">
                                    ADD REGION
                                </button>
                            </li> -->
                            <!-- <li><a href="#add_district" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD DISTRICT</a></li> -->
                            <!-- <li class=""> -->
                            <!-- Button trigger modal -->
                            <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_district">
                                    ADD DISTRICT
                                </button>
                            </li> -->
                            <!-- <li><a href="#add_ward" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD WARD</a></li>
                            <li><a href="#add_village" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD VILLAGE</a></li>
                            <li><a href="#add_occupation" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD Occupation</a></li>
                            <li><a href="#add_hamlet" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD HAMLET</a></li> -->
                            <!-- <li><a href="#add_site" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD SITE</a></li> -->
                            <!-- <li><a href="#add_gender" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD GENDER</a></li> -->
                            <!-- <li><a href="#add_images" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD LOGO</a></li>
                            <li><a href="#add_project" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD STUDY</a></li>
                            <li><a href="#add_pt_group" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD PATIENT GROUP</a></li>
                            <li><a href="#end_study_reason" data-toggle="modal" data-backdrop="static" data-keyboard="false">END OF STUDY REASON</a></li> -->
                            <li><a href="info.php?id=9">&nbsp;&nbsp;MANAGE SITE / COUNTRIES / END STUDY / GROUPS / STUDY</a></li>
                        </ul>
                    </li>
                <?php } elseif ($user->data()->access_level == 4) { ?>
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-user"></span> &nbsp;&nbsp;STAFF</a>
                        <ul class="dropdown-menu">
                            <li><a href="#add_staff" data-toggle="modal" data-backdrop="static" data-keyboard="false"><span class="icon-user"></span>&nbsp;&nbsp;ADD STAFF</a></li>
                            <li><a href="info.php?id=1"><span class="icon-gear"></span>&nbsp;&nbsp;MANAGE STAFF</a></li>
                        </ul>
                    </li>
                <?php } ?>
        </ul>

    <?php } ?>




    <form class="navbar-form navbar-right" role="search">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="search..." />
        </div>
    </form>


    </div>
</nav>




<!-- ADD COUNTRY -->
<div class="modal fade" id="add_country" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ADD COUNTRY</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="modal-body clearfix">
                        <div class="controls">
                            <div class="form-row">
                                <div class="col-md-2">Name:</div>
                                <div class="col-md-10">
                                    <input type="text" name="country_name" class="form-control" value="" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-2">Short Code:</div>
                                <div class="col-md-10">
                                    <input type="text" name="short_code" class="form-control" value="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" name="add_country" value="ADD" class="btn btn-success btn-clean">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="add_region" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_client" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD SCREENING</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">CLIENT ID:</div>
                            <div class="col-md-10">
                                <input type="text" name="study_id" id="study_id" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">SCREENING DATE:</div>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                    <input type="text" name="screening_date" class="datepicker form-control" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="form-row" id="st">
                            <div class="col-md-2">STUDY NAME:</div>
                            <div class="col-md-10">
                                <select class="form-control" id="project_name" name="project_name" required>
                                    <option value="">SELECT STUDY</option>
                                    <?php foreach ($override->getData('study') as $group) { ?>
                                        <option value="<?= $group['name'] ?>"><?= $group['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row" id="st">
                            <div class="col-md-2">Group:</div>
                            <div class="col-md-10">
                                <select class="form-control" id="group" name="group" required>
                                    <option value="">Select Group</option>
                                    <?php foreach ($override->getData('patient_group') as $group) { ?>
                                        <option value="<?= $group['id'] ?>"><?= $group['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-2">Full Name:</div>
                            <div class="col-md-10">
                                <select class="form-control" id="full_name" name="full_name" required>
                                    <option value="">Select Name</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">CLIENT INITIALS:</div>
                            <div class="col-md-10">
                                <input type="text" name="pt_initials" id="pt_initials" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">GENDER:</div>
                            <div class="col-md-10">
                                <input type="text" name="screening_gender" id="screening_gender" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">BIRTH:</div>
                            <div class="col-md-10">
                                <input type="text" name="screening_dob" id="screening_dob" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">PHONE1:</div>
                            <div class="col-md-10">
                                <input type="text" name="phone_number" id="phone_number" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">PHONE2:</div>
                            <div class="col-md-10">
                                <input type="text" name="phone_number2" id="phone_number2" class="form-control" value="" />
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="pull-right col-md-3">
                            <input type="hidden" name="fname" id="fname" class="form-control" value="" />
                            <input type="hidden" name="mname" id="mname" class="form-control" value="" />
                            <input type="hidden" name="lname" id="lname" class="form-control" value="" />
                            <input type="hidden" name="project_name_id" id="project_name_id" class="form-control" value="" />
                            <input type="submit" name="add_client" value="ADD" class="btn btn-success btn-clean">
                        </div>
                        <div class="pull-right col-md-2">
                            <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>


<div class="modal" id="add_visit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD NEW VISIT</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">STUDY ID:</div>
                            <div class="col-md-10">
                                <select name="study_id" id="study_id" class="select2" style="width: 100%;" tabindex="-1">
                                    <option value="">Select study ID</option>
                                    <?php foreach ($override->getData('clients') as $client) { ?>
                                        <option value="<?= $client['id'] ?>"><?= $client['study_id'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div id="waitS" style="display:none;" class="col-md-offset-5 col-md-1"><img src='img/owl/spinner-mini.gif' width="12" height="12" /><br>Loading..</div>
                        <div class="form-row" id="s">
                            <div class="col-md-2">VISIT CODE:</div>
                            <div class="col-md-10" id="visit_code">
                                <input type="hidden" name="visit_code" class="form-control" value="0" required="" />
                                <input type="number" name="visit_code" class="form-control" value="0" disabled />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">VISIT DATE:</div>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                    <input type="text" name="last_visit" class="datepicker form-control" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">NEXT VISIT:</div>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                    <input type="text" name="nxt_visit" class="datepicker form-control" value="" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_visit" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


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

<div class="modal" id="add_site" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">NEW SITE</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="site_name" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Short Code:</div>
                            <div class="col-md-10">
                                <input type="text" name="short_code" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Country:</div>
                            <div class="col-md-10">
                                <select class="form-control" name="country_id" required="">
                                    <option value="">Select Country</option>
                                    <?php if ($user->data()->access_level == 1 || $user->data()->access_level == 2) {
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
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_site" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ADD GENDER -->
<div class="modal" id="add_gender" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD GENDER</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="gender_name" class="form-control" value="" required="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_gender" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal" id="end_study_reason" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">END OF STUDY REASON</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">Reason:</div>
                            <div class="col-md-10">
                                <textarea name="reason" rows="4" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="end_reason" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal" id="searchSchedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">SEARCH SCHEDULE</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row" id="st">
                            <div class="col-md-2">Project:</div>
                            <div class="input-group">
                                <div class="input-group-addon"></div>
                                <select class="form-control" id="project_id" name="project_id" required>
                                    <option value="ALL">ALL STUDIES</option>
                                    <?php foreach ($override->getData('study') as $group) { ?>
                                        <option value="<?= $group['name'] ?>"><?= $group['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">From:</div>
                            <div class="input-group">
                                <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                <input type="text" name="from_date" class="datepicker form-control" value="" required />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">To:</div>
                            <div class="input-group">
                                <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                <input type="text" name="to_date" class="datepicker form-control" value="" required />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="search_schedule" id="search_schedule" value="Search" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal" id="add_pt_group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD GROUP</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="group_name" class="form-control" value="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_pt_group" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal" id="add_project" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD NEW STUDY</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-3">STUDY NAME:</div>
                            <div class="col-md-8">
                                <input type="text" name="name" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3">STUDY CODE:</div>
                            <div class="col-md-8">
                                <input type="text" name="study_code" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3">STUDY DURATION:</div>
                            <div class="col-md-8">
                                <input type="number" name="duration" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3">SAMPLE SIZE:</div>
                            <div class="col-md-8">
                                <input type="number" name="sample_size" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3">START DATE:</div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                    <input type="text" name="start_date" class="datepicker form-control" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3">END DATE:</div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                    <input type="text" name="end_date" class="datepicker form-control" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3">Details:</div>
                            <div class="col-md-8">
                                <textarea name="details" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_study" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal" id="add_images" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD IMAGES</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">Image:</div>
                            <div class="col-md-10">
                                <div class="input-group file">
                                    <input type="text" class="form-control" value="" />
                                    <input type="file" name="image" required />
                                    <span class="input-group-btn">
                                        <button class="btn" type="button">Browse</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Project:</div>
                            <div class="col-md-10">
                                <select class="form-control" name="project" required="">
                                    <option value="">Select Project</option>
                                    <?php foreach ($override->getData('study') as $study) { ?>
                                        <option value="<?= $study['id'] ?>"><?= $study['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Image Purpose:</div>
                            <div class="col-md-10">
                                <select class="form-control" name="image_cat" required="">
                                    <option value="">Select Purpose</option>
                                    <option value="1">Favicon</option>
                                    <option value="2">Logo</option>
                                    <option value="3">Image</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_image" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="add_region" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="Wadd_region8" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD REGION</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="region_name" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Short Code:</div>
                            <div class="col-md-10">
                                <input type="text" name="short_code" class="form-control" value="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_region" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ADD DISTRICT -->
<div class="modal" id="add_district" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD DISTRICT</h4>
                </div>
                <div class="modal-body clearfix">

                    <div class="form-row" id="st">
                        <div class="col-md-2">REGION NAME:</div>
                        <div class="col-md-10">
                            <select class="form-control" id="region_id" name="region_id" required>
                                <option value="">SELECT</option>
                                <?php foreach ($override->getData('region') as $group) { ?>
                                    <option value="<?= $group['id'] ?>"><?= $group['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="district_name" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Short Code:</div>
                            <div class="col-md-10">
                                <input type="text" name="short_code" class="form-control" value="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_district" value="ADD" class="btn btn-success btn-clean">
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

<!-- ADD VILLAGE  -->
<div class="modal" id="add_village" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD VILLAGE</h4>
                </div>

                <div class="modal-body clearfix">

                    <div class="form-row" id="rg2">
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

                    <div id="waitrg2" style="display:none;" class="col-md-offset-5 col-md-1"><img src='img/owl/spinner-mini.gif' width="12" height="12" /><br>Loading..</div>
                    <div class="form-row" id="st">
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
                    <div id="waitrg3" style="display:none;" class="col-md-offset-5 col-md-1"><img src='img/owl/spinner-mini.gif' width="12" height="12" /><br>Loading..</div>
                    <div class="form-row" id="st">
                        <div class="col-md-2">WARD NAME:</div>
                        <div class="col-md-10">
                            <select class="form-control" id="ward_id" name="ward_id" required>
                                <option value="">SELECT</option>
                                <?php foreach ($override->getData('ward') as $ward) { ?>
                                    <option value="<?= $ward['id'] ?>"><?= $ward['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">Village Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="village_name" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Short Code:</div>
                            <div class="col-md-10">
                                <input type="text" name="short_code" class="form-control" value="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_village" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ADD HAMLET  -->
<div class="modal" id="add_hamlet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD HAMLET</h4>
                </div>
                <div class="modal-body clearfix">

                    <div class="form-row" id="st">
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

                    <div id="waitSty" style="display:none;" class="col-md-offset-5 col-md-1"><img src='img/owl/spinner-mini.gif' width="12" height="12" /><br>Loading..</div>
                    <div class="form-row" id="st">
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
                    <div id="waitSty2" style="display:none;" class="col-md-offset-5 col-md-1"><img src='img/owl/spinner-mini.gif' width="12" height="12" /><br>Loading..</div>
                    <div class="form-row" id="st">
                        <div class="col-md-2">WARD NAME:</div>
                        <div class="col-md-10">
                            <select class="form-control" id="ward_id" name="ward_id" required>
                                <option value="">SELECT</option>
                                <?php foreach ($override->getData('ward') as $ward) { ?>
                                    <option value="<?= $ward['id'] ?>"><?= $ward['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div id="waitSty3" style="display:none;" class="col-md-offset-5 col-md-1"><img src='img/owl/spinner-mini.gif' width="12" height="12" /><br>Loading..</div>
                    <div class="form-row" id="st">
                        <div class="col-md-2">Village Name:</div>
                        <div class="col-md-10">
                            <select class="form-control" id="village_id" name="village_id" required>
                                <option value="">SELECT</option>
                                <?php foreach ($override->getData('village') as $ward) { ?>
                                    <option value="<?= $ward['id'] ?>"><?= $ward['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="hamlet_name" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Short Code:</div>
                            <div class="col-md-10">
                                <input type="text" name="short_code" class="form-control" value="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_hamlet" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- OCCUPATION  -->
<div class="modal" id="add_occupation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD OCCUPATION</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">

                        <div class="form-row">
                            <div class="col-md-2">Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="name" class="form-control" value="" required />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Englis Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="name_english" class="form-control" value="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_occupation" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="add_voluntier1" class="modal fade" style="background-color: white;" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-plus"></i>Product Details</h4>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                <form role="form" method="POST" action="" id="dispense_form">
                    <input type="hidden" name="__token" value="">
                    <div class="form-group">
                        <label class="control-label">For Bagamoyo residents, please specify the intended duration of stay in Bagamoyo</label>
                        <input type="text" name="dispense_name" id="dispense_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Total Product Quantinty Available</label>
                        <input type="text" name="total_quantity" id="total_quantity" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+" readonly>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Available For Dispense</label>
                        <input type="text" name="dispense_quantity" id="dispense_quantity" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+" readonly>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Add Amount To Dispense</label>
                        <input type="text" name="add_dispense" id="add_dispense" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+">
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="dispense_id" id="dispense_id" />
                        <input type="hidden" name="dispense_btn_action" id="dispense_btn_action" />
                        <input type="submit" name="dispense_action" id="dispense_action" class="btn btn-info" value="Update" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


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
    });
</script>