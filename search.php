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
        if (Input::get('search_schedule')) {
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

    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">  -->


    <!-- <link rel="stylesheet" href="http://code.jquery.com/ui/1.8.3/themes/base/jquery-ui.css" />
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
    <script type='text/javascript' src='js/settings.js'></script> -->

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
                <?php
                require 'topBar.php'
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <?php require
                    'sideBar.php'
                ?>
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

                <div class="col-md-4">
                    <!-- <div class="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> -->
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title">SEARCH SCHEDULE</h4>
                                </div>
                                <div class="modal-body clearfix">
                                    <div class="controls">
                                        <div class="col-md-12">
                                            <div class="form-row">
                                                <div><i class="fas fa-calendar input-prefix" tabindex="0"></i>Project:</div>
                                                <select class="form-control" id="project_id" name="project_id" required>
                                                    <option value="ALL">ALL STUDIES</option>
                                                    <?php foreach ($override->getData('study') as $group) { ?>
                                                        <option value="<?= $group['name'] ?>"><?= $group['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="row-md-12">
                                            <div class="col-md-12">
                                                <div class="form-row">
                                                    <div><i class="fas fa-calendar input-prefix" tabindex="0"></i>From:</div>
                                                    <div class="col-md-10">
                                                        <input type="date" class="form-control fas fa-calendar input-prefix" name="from_date" id="from_date" required="" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="col-md-12">
                                                <div class="form-row">
                                                    <div><i class="fas fa-calendar input-prefix" tabindex="0"></i>TO:</div>
                                                    <div class="col-md-10">
                                                        <input type="date" class="form-control fas fa-calendar input-prefix" name="to_date" id="to_date" required="" />
                                                    </div>
                                                </div>
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
                    <!-- </div> -->
                </div>

            </div>
        </div>
    </div>
</body>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.3/themes/base/jquery-ui.css" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.8.3/jquery-ui.js"></script>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>


</html>