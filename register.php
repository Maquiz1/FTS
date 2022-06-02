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
    <title> FTS </title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- <link href="css/stylesheets.css" rel="stylesheet" type="text/css"> -->

    <link href="css/stylesheets.css" rel="stylesheet" type="text/css">

    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous"> --> -->


    <link rel="stylesheet" href="http://code.jquery.com/ui/1.8.3/themes/base/jquery-ui.css" />
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.8.3/jquery-ui.js"></script>

    <script type='text/javascript' src='js/plugins/jquery/jquery.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/jquery-ui.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/jquery-migrate.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/globalize.js'></script>
    <script type='text/javascript' src='js/plugins/bootstrap/bootstrap.min.js'></script>

    <script type='text/javascript' src='js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script>
    <script type='text/javascript' src='js/plugins/uniform/jquery.uniform.min.js'></script>

    <script type='text/javascript' src='js/plugins/knob/jquery.knob.js'></script>
    <script type='text/javascript' src='js/plugins/sparkline/jquery.sparkline.min.js'></script>
    <script type='text/javascript' src='js/plugins/flot/jquery.flot.js'></script>
    <script type='text/javascript' src='js/plugins/flot/jquery.flot.resize.js'></script>

    <script type='text/javascript' src='js/plugins/uniform/jquery.uniform.min.js'></script>
    <script type='text/javascript' src='js/plugins/datatables/jquery.dataTables.min.js'></script>
    <script type='text/javascript' src='js/plugins/select2/select2.min.js'></script>
    <script type='text/javascript' src='js/plugins/tagsinput/jquery.tagsinput.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/jquery-ui-timepicker-addon.js'></script>
    <script type='text/javascript' src='js/plugins/bootstrap/bootstrap-file-input.js'></script>

    <script type='text/javascript' src='js/plugins.js'></script>
    <script type='text/javascript' src='js/actions.js'></script>
    <script type='text/javascript' src='js/settings.js'></script>

    <style>
        .tabs-wrap {
            margin-top: 40px;
        }

        .tab-content .tab-pane {
            padding: 20px 0;
            background-color: black;
        }

        .back-cont {
            font: 23px "Play";
            position: relative;
            margin-left: 605px;
            padding-top: 500px;
            /* padding-bottom: 300px; */
        }
    </style>


</head>

<body class="bg-img-num1" data-settings="open">

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php require 'topBar.php' ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <?php require 'sideBar.php' ?>
            </div>
            <div class="col-md-10">
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

                <div id="tabs">
                    <ul>
                        <li> <a href="#details" aria-controls="billing" role="tab" data-toggle="tab" aria-expanded="true">STUDY DETAILS</a></li>
                        <li><a href="#contact" aria-controls="shipping" role="tab" data-toggle="tab" aria-expanded="false">NAME AND CONTACT</a></li>
                        <li> <a href="#demographic" aria-controls="shipping" role="tab" data-toggle="tab" aria-expanded="false">DEMOGRAPHIC</a></li>
                        <li><a href="#reason_status" aria-controls="review" role="tab" data-toggle="tab" aria-expanded="false">REASON &amp; STATUS</a></li>
                        <li> <a href="#register_subject" aria-controls="review" role="tab" data-toggle="tab" aria-expanded="false">REGISTER SUBJECT</a></li>
                    </ul>

                    <form action="#" method="post">
                        <div role="tabpanel" class="tab-pane active" id="details">
                            <h3 class="">STUDY DETAILS</h3>
                            <div class="row-md-12">
                                <div class="col-md-12">
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
                            </div>
                            <div class="col-md-12">
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

                            <div class="col-md-12">
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

                            <div class="row-md-12">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <div>INITIALS:</div>
                                        <div class="col-md-10">
                                            <input type="text" name="initial" class="form-control" value="" minlength="3" maxlength="3" required="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-row">
                                    <div>SENSITIZATION NUMBER</div>
                                    <div class="col-md-10">
                                        <input type="text" name="sensitization_no" class="form-control" pattern="\d*" value="" minlength="3" maxlength="3" required="" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
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

                            <br>
                            <br>
                            <div class="back-cont mt-4">
                                <a class="btn btn-primary continue">
                                    <input type="button" id="btnNext" value="Next" class="btn btn-primary" />
                                </a>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="contact">
                            <h3 class="">SUBJECT NAME</h3>
                            <div class="row-md-12">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <div>First Name:</div>
                                        <div class="col-md-10">
                                            <input type="text" name="fname" class="form-control" value="" required="" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-row">
                                    <div>Midle Name:</div>
                                    <div class="col-md-10">
                                        <input type="text" name="mname" class="form-control" value="" required="" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-row">
                                    <div>Last name:</div>
                                    <div class="col-md-10">
                                        <input type="text" name="lname" class="form-control" value="" required="" />
                                    </div>
                                </div>
                            </div>

                            <!-- DATE OF BIRTH  -->

                            <div class="row-md-12">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <div><i class="fas fa-calendar input-prefix" tabindex="0"></i>DATE OF BIRTH:</div>
                                        <div class="col-md-10">
                                            <input type="date" class="form-control fas fa-calendar input-prefix" name="dob" id="dob" required="" />
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12">
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

                            <div class="col-md-12">
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

                            <!-- DATE OF BIRTH  -->


                            <!-- POHONE  -->

                            <br>
                            <br>
                            <div class="back-cont mt-4">
                                <a class="btn btn-primary continue">
                                    <input type="button" id="btnPrevious" value="Previous" class="btn btn-primary" />
                                </a>
                                <a class="btn btn-primary continue">
                                    <input type="button" id="btnNext" value="Next" class="btn btn-primary" />
                                </a>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="demographic">
                            <h3 class="">DEMOGRAPHIC & CONTACT INFORMATION</h3>

                            <div class="row-md-12">
                                <div class="col-md-10">
                                    <div class="form-row">
                                        <div>Phone:</div>
                                        <div>
                                            <input type="text" name="phone1" class="form-control" value="" pattern="\d*" minlength="10" maxlength="10" required="" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-10">
                                    <div class="form-row">
                                        <div>Phone2:</div>
                                        <div>
                                            <input type="text" name="phone2" class="form-control" value="" pattern="\d*" minlength="10" maxlength="10" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row-md-12">
                                <div class="col-md-12">
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
                            </div>

                            <div class="col-md-12">
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


                            <div class="row-md-12">
                                <div class="col-md-12">
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
                            </div>

                            <div class="col-md-12">
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

                            <div class="col-md-12">
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

                            <br>
                            <br>
                            <div class="back-cont mt-4">
                                <a class="btn btn-primary continue">
                                    <input type="button" id="btnPrevious" value="Previous" class="btn btn-primary" />
                                </a>
                                <a class="btn btn-primary continue">
                                    <input type="button" id="btnNext" value="Next" class="btn btn-primary" />
                                </a>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="reason_status">
                            <h3 class="">REASON &amp; STATUS</h3>

                            <div class="row-md-12">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <label for="duration">For Bagamoyo residents, please specify the intended duration of stay in Bagamoyo:</label>
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


                            </div>

                            <div class="col-md-12">
                                <div class="form-row">
                                    <label for="duration">Is the participant willing to be contacted for the next sensitization meeting?</label>
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


                            <div class="col-md-12">
                                <div class="form-row">
                                    <label for="duration">Briefly describe participant residential location in relation to the nearest famous neighborhoods:</label>
                                    <div class="col-md-10">
                                        <textarea name="location" id="location" class="form-control" cols="40%" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="row-md-12"> -->
                            <div class="col-md-12">
                                <div class="form-row">
                                    <label for="duration">Status:</label>
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

                            <div class="col-md-12">
                                <div class="form-row">
                                    <label for="duration">Reason</label>
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
                            <!-- </div> -->


                            <div class="col-md-12">
                                <div class="form-row">
                                    <label for="duration">Other reason Details:</label>
                                    <div class="col-md-10">
                                        <textarea name="other_reason" id="other_reason" cols="40%" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <br />

                            <div class="back-cont mt-4">
                                <a class="btn btn-primary continue">
                                    <input type="button" id="btnPrevious" value="Previous" class="btn btn-primary" />
                                </a>
                                <a class="btn btn-primary continue">
                                    <input type="button" id="btnNext" value="Next" class="btn btn-primary" />
                                </a>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="register_subject">
                            <h3 class="">SUBMIT</h3>
                            <div class="back-cont mt-2">
                                <a class="btn btn-primary continue">
                                    <input type="button" id="btnPrevious" value="Previous" />
                                </a>
                                <a class="btn btn-primary continue">
                                    <input type="submit" name="Register" value="Register Subject">
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <br />
            </div>
        </div>
        <div id="push"></div>
    </div>
</body>


<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.3/themes/base/jquery-ui.css" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.8.3/jquery-ui.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script> --> -->


<!-- 
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.3/themes/base/jquery-ui.css" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.8.3/jquery-ui.js"></script> -->


<script type="text/javascript">
    $(document).ready(function() {
        var currentTab = 0;
        $(function() {
            $("#tabs").tabs({
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