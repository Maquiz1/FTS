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
<!DOCTYPE html>
<html lang="en">

<head>
    <title> VTS </title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/ico" href="<?php if ($favicon) {
                                                echo $favicon['location'];
                                            } else {
                                                echo 'favicon.ico';
                                            } ?>">
    <link href="css/stylesheets.css" rel="stylesheet" type="text/css">

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

                <div class="block">
                    <div class="header">
                        <h2>ADD SCHEDULED VISITS</h2>
                    </div>
                    <div class="content">
                        <div class="col-md-offset-2 col-md-8">
                            <form method="post">
                                <div class="modal-body clearfix">
                                    <div class="controls">
                                        <div class="form-row">
                                            <div class="col-md-2">STUDY NAME:</div>
                                            <div class="col-md-10">
                                                <select name="study_name" id="study_name" class="select2" style="width: 100%;" tabindex="-1">
                                                    <option value="">SELECT STUDY</option>
                                                    <?php foreach ($override->getData('study') as $study) { ?>
                                                        <option value="<?= $study['name'] ?>"><?= $study['name'] ?></option>
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
                                                        <option value="<?= $group['name'] ?>"><?= $group['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="col-md-2">SUBJECT ID</div>
                                            <div class="col-md-10">
                                                <select name="client_id" id="client_id" class="select2" style="width: 100%;" tabindex="-1">
                                                    <option value="">SELECT SUBJECT ID</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div id="waitS1" style="display:none;" class="col-md-offset-5 col-md-1"><img src='img/owl/spinner-mini.gif' width="12" height="12" /><br>Loading..</div>
                                        <div class="form-row" id="s1">
                                            <div class="col-md-2">VISIT CODE:</div>
                                            <div class="col-md-10" id="v_code">
                                                <select name="visit_code" id="visit_code" class="select2" style="width: 100%;" tabindex="-1" required="">
                                                    <option value="">SELECT VISIT TYPE</option>
                                                    <option value="Clinic">Clinic</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row" id="s1">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-10" id="v_code">
                                                <input type="text" name="imp" class="datepicker form-control" value="" required />
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-2">VISIT DATE:</div>
                                            <div class="col-md-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                                    <input type="text" name="visit_date" class="datepicker form-control" value="" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="pull-right col-md-3">
                                        <input type="hidden" name="participant_group" id="participant_group" class="form-control" value="" />
                                        <input type="hidden" name="participant_id" id="participant_id" class="form-control" value="" />
                                        <input type="submit" name="add_visit" value="ADD" class="btn btn-success">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        $('#study_name').change(function() {
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