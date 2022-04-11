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
                'fname' => array(
                    'required' => true,
                ),
                'mname' => array(
                    'required' => true,
                ),
                'lname' => array(
                    'required' => true,
                ),
                'study_id' => array(
                    'required' => true,
                    'unique' => 'clients',
                    'min' => 6,
                ),
                'initials' => array(
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
                'project_id' => array(
                    'required' => true,
                ),
                // 'dob' => array(
                //     'required' => true,
                // ),
                'gender' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $s_date = date('Y-m-d', strtotime(Input::get('screening_date')));
                try {
                    $user->createRecord('clients', array(
                        'study_id' => Input::get('study_id'),
                        'fname' => Input::get('fname'),
                        'mname' => Input::get('mname'),
                        'lname' => Input::get('lname'),
                        'gender' => Input::get('gender'),
                        'dob' => Input::get('dob'),
                        'status' => 1,
                        'initials' => Input::get('initials'),
                        'phone_number' => Input::get('phone_number'),
                        'phone_number2' => Input::get('phone_number2'),
                        'screening_date' => $s_date,
                        'pt_group' => Input::get('group'),
                        'reason' => '',
                        'details' => '',
                        'visit_cat' => 0,
                        'project_id' => Input::get('project_id'),
                        'staff_id' => $user->data()->id
                    ));

                    $successMessage = 'Client Added Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        if (Input::get('register_voluntier')) {
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
                // 'year' => array(
                //     'required' => true,
                // ),
                'gender' => array(
                    'required' => true,
                ),
                // 'pregnant' => array(
                //     'required' => true,
                // ),
                // 'literate' => array(
                //     'required' => true,
                // ),
                // 'education' => array(
                //     'required' => true,
                // ),
                // 'marital' => array(
                //     'required' => true,
                // ),
                // 'occupation' => array(
                //     'required' => true,
                // ),
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
                ),
                // 'interviewer_initial' => array(
                //     'required' => true,
                // ),
                // 'interviewer_date' => array(
                //     'required' => true,
                // ),

                // 'reviewer_initial' => array(
                //     'required' => true,
                // ),
                // 'reviewer_date' => array(
                //     'required' => true,
                // ),
                // 'enrolled' => array(
                //     'required' => true,
                // ),
                // 'reason' => array(
                //     'required' => true,
                // )
                // 'date_death' => array(
                //     'required' => true,
                // ),
                // 'details' => array(
                //     'required' => true,
                // )
                // 'end_study' => array(
                //     'required' => true,
                // )
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
                        // 'year' => Input::get('year'),
                        // 'month' => Input::get('month'),
                        'gender' => Input::get('gender'),
                        // 'pregnant' => Input::get('pregnant'),
                        // 'literate' => Input::get('literate'),
                        // 'education' => Input::get('education'),
                        // 'marital' => Input::get('marital'),
                        // 'occupation' => Input::get('occupation'),
                        // 'other_occupation' => Input::get('other_occupation'),
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
                        // 'interviewer_initial' => Input::get('interviewer_initial'),
                        // 'interviewer_date' => $intwr_date,
                        // 'reviewer_initial' => Input::get('reviewer_initial'),
                        // 'reviewer_date' => $rvwr_date,
                        'staff_id' => $user->data()->id,
                        // 'enrolled' => Input::get('enrolled'),
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
                ),
                // 'name_english' => array(
                // 'required' => true,
                // ),
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
                ),
                // 'project_id' => array(
                //     'required' => true,
                // ),
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

            <?php
            if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
            ?>

                <li class="">
                    <a href="#add_client" data-toggle="modal" data-backdrop="static" data-keyboard="false"><span class="icon-plus-sign"></span> Add New Client</a>
                </li>
                <li class="">
                    <!--<a href="#add_visit" data-toggle="modal" data-backdrop="static" data-keyboard="false" ><span class="icon-bookmark"></span> Add Visit</a>-->
                    <a href="add.php"><span class="icon-bookmark"></span> Add Scheduled Visits</a>

                </li>
                <li class="">
                    <!--<a href="#add_visit" data-toggle="modal" data-backdrop="static" data-keyboard="false" ><span class="icon-bookmark"></span> Add Visit</a>-->

                    <a href="add_unschedule.php"><span class="icon-bookmark"></span> Add Un - Scheduled Visit</a>
                </li>

            <?php } ?>
            <li class="dropdown active">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-file-alt"></span> STUDY </a>
                <ul class="dropdown-menu">
                    <?php foreach ($override->getData('study') as $study) { ?>

                        <li class="list-group-item d-flex justify-content-between align-items-center active">
                            <a href="study.php?sid=<?= $study['id'] ?>"><?= $study['name'] ?></a>
                            <span class="badge badge-secondary badge-pill"><?= $override->getCount('visit', 'visit_date', date('Y-m-d')); ?></span>
                        </li>
                    <?php } ?>
                </ul>
            </li>
            <li class="">
                <a href="#searchSchedule" data-toggle="modal"><span class="icon-search"></span> Search Schedule </a>
            </li>
            <li class="">
                <a href="profile.php">
                    <span class="icon-user"></span> Profile
                </a>
            </li>

            <?php
            if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
            ?>


                <?php if ($user->data()->access_level == 1 || $user->data()->access_level == 2 || $user->data()->access_level == 3) { ?>
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-group"></span> STAFF</a>
                        <ul class="dropdown-menu">
                            <li><a href="#add_staff" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD STAFF</a></li>
                            <li><a href="info.php?id=8">MANAGE STAFF</a></li>
                        </ul>
                    </li>
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-gear"></span> MANAGEMENT</a>
                        <ul class="dropdown-menu">
                            <li><a href="#add_country" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD COUNTRY</a></li>
                            <li><a href="#add_region" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD REGION</a></li>
                            <li><a href="#add_district" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD DISTRICT</a></li>
                            <li><a href="#add_ward" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD WARD</a></li>
                            <li><a href="#add_village" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD VILLAGE</a></li>
                            <li><a href="#add_occupation" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD Occupation</a></li>
                            <li><a href="#add_hamlet" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD HAMLET</a></li>
                            <li><a href="#add_site" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD SITE</a></li>
                            <li><a href="#add_gender" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD GENDER</a></li>
                            <li><a href="#add_images" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD LOGO</a></li>
                            <li><a href="#add_project" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD STUDY</a></li>
                            <li><a href="#add_pt_group" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD PATIENT GROUP</a></li>
                            <li><a href="#end_study_reason" data-toggle="modal" data-backdrop="static" data-keyboard="false">END OF STUDY REASON</a></li>
                            <li><a href="info.php?id=9">MANAGE SITE / COUNTRIES / END STUDY / GROUPS / STUDY</a></li>
                        </ul>
                    </li>
                <?php } elseif ($user->data()->access_level == 4) { ?>
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-group"></span> STAFF</a>
                        <ul class="dropdown-menu">
                            <li><a href="#add_staff" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD STAFF</a></li>
                            <li><a href="info.php?id=1">MANAGE STAFF</a></li>
                        </ul>
                    </li>
                <?php } ?>

                <li class="dropdown active">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-group"></span> VOLUNTIER</a>
                    <ul class="dropdown-menu">
                        <li><a href="#add_voluntier" data-toggle="modal" data-backdrop="static" data-keyboard="false">REGISTER</a></li>
                        <li><a href="info.php?id=14">MANAGE VOLUNTIER</a></li>
                    </ul>
                </li>
        </ul>

    <?php } ?>




    <form class="navbar-form navbar-right" role="search">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="search..." />
        </div>
    </form>


    </div>
</nav>


<div class="modal" id="add_client" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD NEW CLIENT</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">CLIENT ID:</div>
                            <div class="col-md-10">
                                <input type="text" name="study_id" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">First Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="fname" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Midle Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="mname" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Last name:</div>
                            <div class="col-md-10">
                                <input type="text" name="lname" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">INITIALS:</div>
                            <div class="col-md-10">
                                <input type="text" name="initials" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <!-- <div class="form-row">
                            <div class="col-md-2">DATE OF BIRTH:</div>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                    <input type="text" name="dob" class="datepicker form-control" value="" />
                                </div>
                            </div>
                        </div> -->
                        <div class="form-row">
                            <div class="col-md-2">GENDER:</div>
                            <div class="col-md-10">
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="">SELECT GENDER</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Phone:</div>
                            <div class="col-md-10">
                                <input type="text" name="phone_number" class="form-control" value="" required="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Phone2:</div>
                            <div class="col-md-10">
                                <input type="text" name="phone_number2" class="form-control" value="" />
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
                                <select class="form-control" id="project_id" name="project_id" required>
                                    <option value="">SELECT STUDY</option>
                                    <?php foreach ($override->getData('study') as $group) { ?>
                                        <option value="<?= $group['id'] ?>"><?= $group['name'] ?></option>
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
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
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
<div class="modal" id="add_country" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD COUNTRY</h4>
                </div>
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
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_country" value="ADD" class="btn btn-success btn-clean">
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
<div class="modal" id="add_region" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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


<!-- ADD VOLUNTIER  -->
<!-- <div class="container">
    <div class="card mx-auto" style="width: 80rem;"> -->
<div class="modal faed-in" id="add_voluntier" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD NEW VOLUNTIER</h4>
                </div>
                <div class="modal-body clearfix">
                    <!-- <div class="controls"> -->

                    <div class="row-md-12">
                        <div class="col-md-4">

                            <div class="form-row" id="st">
                                <div>STUDY NAME:</div>
                                <div class="col-md-10">
                                    <select class="form-control" id="project_id" name="project_id" required>
                                        <option value="">SELECT STUDY</option>
                                        <?php foreach ($override->getData('study') as $group) { ?>
                                            <option value="<?= $group['name'] ?>"><?= $group['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                        </div>


                        <div class="col-md-4">
                            <div class="form-row">
                                <div>SENSITIZATION ONE</div>
                                <div class="col-md-10">
                                    <select id="sensitization_one" name="sensitization_one" class="form-control" required>
                                        <option value="">SELECT</option>
                                        <?php foreach ($override->getData('yes_no_na') as $lt) { ?>
                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>



                        <div class="col-md-4">
                            <div class="form-row">
                                <div>SENSITIZATION TWO</div>
                                <div class="col-md-10">
                                    <select id="sensitization_two" name="sensitization_two" class="form-control" required>
                                        <option value="">SELECT</option>
                                        <?php foreach ($override->getData('yes_no_na') as $lt) { ?>
                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row-md-12">
                        <div class="col-md-4">
                            <div class="form-row">
                                <div>INITIALS:</div>
                                <div class="col-md-10">
                                    <input type="text" name="initial" class="form-control" value="" minlength="3" maxlength="3" required="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-row">
                                <div>SENSITIZATION NUMBER</div>
                                <div class="col-md-10">
                                    <input type="text" name="sensitization_no" class="form-control" pattern="\d*" value="" minlength="3" maxlength="3" required="" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-row" id="st">
                                <div>Respondent is</div>
                                <div class="col-md-10">
                                    <select class="form-control" id="client_category" name="client_category" required>
                                        <option value="">SELECT</option>
                                        <?php foreach ($override->getData('client_category') as $group) { ?>
                                            <option value="<?= $group['name'] ?>"><?= $group['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row-md-12">
                        <div class="col-md-4">
                            <div class="form-row">
                                <div>First Name:</div>
                                <div class="col-md-10">
                                    <input type="text" name="fname" class="form-control" value="" required="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-row">
                                <div>Midle Name:</div>
                                <div class="col-md-10">
                                    <input type="text" name="mname" class="form-control" value="" required="" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-row">
                                <div>Last name:</div>
                                <div class="col-md-10">
                                    <input type="text" name="lname" class="form-control" value="" required="" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DATE OF BIRTH  -->

                    <div class="row-md-12">
                        <div class="col-md-4">
                            <div class="form-row">
                                <div><i class="fas fa-calendar input-prefix" tabindex="0"></i>DATE OF BIRTH:</div>
                                <div class="col-md-10">
                                    <input type="date" class="form-control fas fa-calendar input-prefix" name="dob" id="dob" required="" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-row">
                                <div>Child attending school?</div>
                                <div class="col-md-10">
                                    <select id="attend_school" name="attend_school" class="form-control" required>
                                        <option value="">SELECT</option>
                                        <?php foreach ($override->getData('yes_no_na') as $lt) { ?>
                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-row">
                                <div>GENDER:</div>
                                <div class="col-md-10">
                                    <select id="gender" name="gender" class="form-control" required>
                                        <option value="">SELECT GENDER</option>
                                        <?php foreach ($override->getData('gender') as $gender) { ?>
                                            <option value="<?= $gender['name'] ?>"><?= $gender['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- DATE OF BIRTH  -->


                    <!-- POHONE  -->

                    <div class="row-md-12">
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col-md-2">Phone:</div>
                                <div class="col-md-10">
                                    <input type="text" name="phone1" class="form-control" value="" pattern="\d*" minlength="10" maxlength="10" required="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col-md-2">Phone2:</div>
                                <div class="col-md-10">
                                    <input type="text" name="phone2" class="form-control" value="" pattern="\d*" minlength="10" maxlength="10" />
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- POHONE  -->


                    <!-- DEMOGRAPHIC  -->

                    <div class="row-md-12">
                        <div class="col-md-6">
                            <div class="form-row">
                                <div>REGION:</div>
                                <div class="col-md-10">
                                    <select id="region" name="region" class="form-control" required>
                                        <option value="">SELECT</option>
                                        <?php foreach ($override->getData('region') as $lt) { ?>
                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-row">
                                <div>DISTRICT:</div>
                                <div class="col-md-10">
                                    <select id="district" name="district" class="form-control" required>
                                        <option value="">SELECT</option>
                                        <?php foreach ($override->getData('district') as $lt) { ?>
                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row-md-12">
                        <div class="col-md-4">
                            <div class="form-row">
                                <div>WARD:</div>
                                <div class="col-md-10">
                                    <select id="ward" name="ward" class="form-control" required>
                                        <option value="">SELECT</option>
                                        <?php foreach ($override->getData('ward') as $lt) { ?>
                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-row">
                                <div>VILLAGE:</div>
                                <div class="col-md-10">
                                    <select id="village" name="village" class="form-control">
                                        <option value="">SELECT</option>
                                        <?php foreach ($override->getData('village') as $lt) { ?>
                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-row">
                                <div>Hamlet / Kitongoji:</div>
                                <div class="col-md-10">
                                    <select id="hamlet" name="hamlet" class="form-control" required>
                                        <option value="">SELECT</option>
                                        <?php foreach ($override->getData('hamlet') as $lt) { ?>
                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <br>
                    <br><br>
                    <div class="row-md-12">
                        <div class="col-md-6">
                            <div class="form-row">
                                <label for="duration">For Bagamoyo residents, please specify the intended duration of stay in Bagamoyo:</label>
                                <div class="col-md-2"></div>
                                <div class="col-md-10">
                                    <select id="duration" name="duration" class="form-control" required>
                                        <option value="">SELECT</option>
                                        <?php foreach ($override->getData('duration') as $lt) { ?>
                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-row">
                                <label for="duration">Is the participant willing to be contacted for the next sensitization meeting?</label>
                                <div class="col-md-2"></div>
                                <div class="col-md-10">
                                    <select id="willing_contact" name="willing_contact" class="form-control" required>
                                        <option value="">SELECT</option>
                                        <?php foreach ($override->getData('yes_no') as $lt) { ?>
                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <br>
                    <br><br>
                    <div class="form-row">
                        <label for="duration">Briefly describe participant residential location in relation to the nearest famous neighborhoods:</label>
                        <div class="col-md-2"></div>
                        <div class="col-md-10">
                            <textarea name="location" id="location" cols="100%" rows="4" required></textarea>
                        </div>
                    </div>

                    <!-- DEMOGRAPHIC  -->


                    <div class="row-md-12">
                        <div class="col-md-6">
                            <div class="form-row">
                                <label for="duration">Status:</label>
                                <div class="col-md-2"></div>
                                <div class="col-md-10">
                                    <select id="status" name="status" class="form-control" required>
                                        <option value="">SELECT</option>
                                        <?php foreach ($override->getData('status') as $lt) { ?>
                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-row">
                                <label for="duration">Reason</label>
                                <div class="col-md-2"></div>
                                <div class="col-md-10">
                                    <select id="reason" name="reason" class="form-control">
                                        <option value="">SELECT</option>
                                        <?php foreach ($override->getData('end_study_reason') as $lt) { ?>
                                            <option value="<?= $lt['reason'] ?>"><?= $lt['reason'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-row">
                            <label for="duration">Other reason Details:</label>
                            <div class="col-md-2"></div>
                            <div class="col-md-10">
                                <textarea name="other_reason" id="other_reason" cols="40%" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- INITIALS  -->

                </div>

                <!-- INITIALS  -->
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="register_voluntier" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- </div>
</div> -->


<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->


<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous"> -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"> -->

<!-- Datatable JS -->
<!-- <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> -->

<!-- Datatable CSS -->
<!-- <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'> -->


<!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script> -->

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


        // $('#searchSchedule').DataTable({
        //     dom: 'Bfrtip',
        //     buttons: [{

        //             extend: 'excelHtml5',
        //             title: 'VISITS',
        //             className: 'btn-primary'
        //         },
        //         {
        //             extend: 'pdfHtml5',
        //             title: 'VISITS',
        //             className: 'btn-primary'

        //         },
        //         {
        //             extend: 'csvHtml5',
        //             title: 'VISITS',
        //             className: 'btn-primary'
        //         },
        //         {
        //             extend: 'copyHtml5',
        //             title: 'VISITS',
        //             className: 'btn-primary'
        //         },
        //         //     {
        //         //         extend: 'print',
        //         //         // name: 'printButton'
        //         //         title: 'VISITS'
        //         //     }
        //     ],

        //     fields: [
        //     //     {
        //     //     label: 'First name:',
        //     //     name: 'first_name'
        //     // }, {
        //     //     label: 'Last name:',
        //     //     name: 'last_name'
        //     // }, {
        //     //     label: 'Updated date:',
        //     //     name: 'updated_date',
        //     //     type: 'date',
        //     //     def: function() {
        //     //         return new Date();
        //     //     },
        //     //     dateFormat: $.datepicker.ISO_8601
        //     // }, 
        //     {
        //         label: 'From:',
        //         name: 'from_date',
        //         type: 'date',
        //         def: function() {
        //             return new Date();
        //         },
        //         dateFormat: $.datepicker.ISO_8601
        //     }]


        // });


        // $("#search_schedule").on("submit", function() {
        //     $('#project_id').change(function() {
        //         var getUid = $(this).val();
        //         // $('#fl_wait').show();
        //         $.ajax({
        //             url: "process.php?cnt=study",
        //             method: "GET",
        //             data: {
        //                 getUid: getUid
        //             },
        //             success: function(data) {
        //                 // $('#client_id').html(data);
        //                 // $('#fl_wait').hide();
        //                 // console.log(data);
        //             }
        //         });

        //     });
        // });

        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    });
</script>