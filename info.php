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
        if (Input::get('edit_client')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'study_id' => array(
                    'required' => true,
                    'min' => 6,
                ),
                'initials' => array(
                    'required' => true,
                    'max' => 3,
                ),
                'visit_code' => array(),
                'phone_number' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('clients', array(
                        'study_id' => Input::get('study_id'),
                        'visit_code' => Input::get('visit_code'),
                        'initials' => Input::get('initials'),
                        'phone_number' => Input::get('phone_number'),
                        'phone_number2' => Input::get('phone_number2'),
                    ), Input::get('id'));
                    $successMessage = 'Information Saved successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('delete_client')) {
            try {
                $user->updateRecord('clients', array(
                    'status' => 0,
                    'reason' => Input::get('reason'),
                ), Input::get('id'));
                $successMessage = 'Patient End Study Successful';
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } elseif (Input::get('delete_client_schedule')) {
            try {
                $user->deleteRecord('visit', 'client_id', Input::get('id'));
                $successMessage = 'Patient Deleted Successful';
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } elseif (Input::get('appointment')) {
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
                    if ($user->data()->position == 6) {
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
                    } elseif ($user->data()->position == 5) {
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
                            $errorMessage = 'Patient must be attended by study nurse, clinician and  Data Clerck First';
                        }
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('delete_staff')) {
            try {
                $user->updateRecord('staff', array(
                    'status' => 0,
                ), Input::get('id'));
                $successMessage = 'Staff Deleted Successful';
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } elseif (Input::get('edit_staff')) {
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
                ),
                'phone_number' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
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
                    $user->updateRecord('staff', array(
                        'firstname' => Input::get('firstname'),
                        'lastname' => Input::get('lastname'),
                        'position' => Input::get('position'),
                        'username' => Input::get('username'),
                        'access_level' => $accessLevel,
                        'phone_number' => Input::get('phone_number'),
                        'email_address' => Input::get('email_address'),
                        'c_id' => Input::get('country_id'),
                        's_id' => Input::get('site_id'),
                        'status' => 1
                    ), Input::get('id'));
                    $successMessage = 'Staff Info Updated Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('edit_participant')) {
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
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('delete_site')) {
            try {
                $user->updateRecord('site', array(
                    'status' => 0,
                ), Input::get('id'));
                $successMessage = 'Site Deleted Successful';
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } elseif (Input::get('delete_country')) {
            try {
                $user->updateRecord('country', array(
                    'status' => 0,
                ), Input::get('id'));
                $successMessage = 'Country Deleted Successful';
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } elseif (Input::get('delete_end_study')) {
            try {
                $user->deleteRecord('end_study_reason', 'id', Input::get('id'));
                $successMessage = 'Reason Deleted Successful';
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } elseif (Input::get('edit_site')) {
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
                )
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('site', array(
                        'name' => Input::get('site_name'),
                        'short_code' => Input::get('short_code'),
                        'c_id' => Input::get('country_id')
                    ), Input::get('id'));
                    $successMessage = 'Site Updated Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('edit_country')) {
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
                    $user->updateRecord('country', array(
                        'name' => Input::get('country_name'),
                        'short_code' => Input::get('short_code'),
                    ), Input::get('id'));
                    $successMessage = 'Country Updated Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('edit_end_study')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'reason' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('end_study_reason', array(
                        'reason' => Input::get('reason'),
                    ), Input::get('id'));
                    $successMessage = 'Reason Updated Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('reset_password')) {
            $salt = $random->get_rand_alphanumeric(32);
            $password = '123456';
            try {
                $user->updateRecord('staff', array(
                    'password' => Hash::make($password, $salt),
                    'salt' => $salt,
                ), Input::get('id'));
                $email->resetPassword(Input::get('email'), Input::get('firstname'), 'Password Reset');
                $successMessage = 'Password Reset to Default Successful';
            } catch (Exception $e) {
                $e->getMessage();
            }
        } elseif (Input::get('add_end_study')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'reason' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('clients', array(
                        'status' => 0,
                        'reason' => Input::get('reason'),
                        'details' => Input::get('details')
                    ), Input::get('id'));
                    foreach ($override->get('visit', 'client_id', Input::get('id')) as $end) {
                        if ($end['status'] == 0) {
                            $user->updateRecord('visit', array('status' => 4), $end['id']);
                        }
                    }

                    $successMessage = 'End of Study Added Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_reason')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'details' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('visit', array(
                        'details' => Input::get('details')
                    ), Input::get('id'));

                    $successMessage = 'Reaon Edited Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('edit_visit')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),
                'visit_code' => array(
                    'required' => true,
                ),
                'details' => array(
                    'required' => true,
                ),
                'reason' => array(
                    'required' => true,
                ),
                'status' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $date = date('Y-m-d', strtotime(Input::get('visit_date')));
                    $user->updateRecord('visit', array(
                        'visit_date' => $date,
                        'visit_code' => Input::get('visit_code'),
                        'details' => Input::get('details'),
                        'reason' => Input::get('reason'),
                        'status' => Input::get('status')
                    ), Input::get('id'));

                    $successMessage = 'Visit Edited Successful';
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
                    if ((Input::get('project_name') == 'VAC080') and (Input::get('group_name') == 'Group 1A' || Input::get('group_name') == 'Group 2A')) {
                        $user->updateScheduleNotDelayedVac080(Input::get('project_name'), Input::get('id'), Input::get('visit_date'), Input::get('visit'), Input::get('participant_group'));
                    } elseif ((Input::get('project_name') == 'VAC080') and (Input::get('group_name') == 'Group 1B' || Input::get('group_name') == 'Group 2B' || Input::get('group_name') == 'Group 2C' || Input::get('group_name') == 'Group 2D')) {
                        $user->updateScheduleDelayedVac080(Input::get('project_name'), Input::get('id'), Input::get('visit_date'), Input::get('visit'), Input::get('participant_group'));
                    } elseif ((Input::get('project_name') == 'VAC082') and (Input::get('group_name') == 'Group 1A' || Input::get('group_name') == 'Group 1B' || Input::get('group_name') == 'Group 2A' || Input::get('group_name') == 'Group 2B' || Input::get('group_name') == 'Group 3A' || Input::get('group_name') == 'Group 3B')) {
                        $user->updateScheduleNotDelayedVac082(Input::get('project_name'), Input::get('id'), Input::get('visit_date'), Input::get('visit'), Input::get('participant_group'));
                    } elseif ((Input::get('project_name') == 'VAC082') and (Input::get('group_name') == 'Group 3C' || Input::get('group_name') == 'Group 4A' || Input::get('group_name') == 'Group 4B' || Input::get('group_name') == 'Group 4C')) {
                        $user->updateScheduleDelayedVac082(Input::get('project_name'), Input::get('id'), Input::get('visit_date'), Input::get('visit'), Input::get('participant_group'));
                    } elseif ((Input::get('project_name') == 'RAB002')) {
                        $user->updateScheduleRAB002(Input::get('project_name'), Input::get('id'), Input::get('visit_date'), Input::get('visit'), Input::get('participant_group'));
                    } elseif ((Input::get('project_name') == 'EBL08')) {
                        $user->updateScheduleEBL08(Input::get('project_name'), Input::get('id'), Input::get('visit_date'), Input::get('visit'), Input::get('participant_group'));
                    }
                    $successMessage = 'Visit Edited Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('delete_visit')) {
            try {
                $vsc = $override->get('clients', 'id', Input::get('cl_id'));
                $nw_vc = $vsc[0]['visit_code'] - 1;
                $user->updateRecord('clients', array('visit_code' => $nw_vc), Input::get('cl_id'));
                $user->deleteRecord('visit', 'id', Input::get('id'));
                $successMessage = 'Visit Deleted Successful';
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } elseif (Input::get('edit_visit_detail')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),
                'details' => array(
                    'required' => true,
                ),
                'reason' => array(
                    'required' => true,
                ),
                'status' => array(
                    'required' => true,
                )
            ));
            if ($validate->passed()) {
                try {
                    $date = date('Y-m-d', strtotime(Input::get('visit_date')));
                    $user->updateRecord('visit', array(
                        'visit_date' => $date,
                        'details' => Input::get('details'),
                        'reason' => Input::get('reason'),
                        'status' => Input::get('status')
                    ), Input::get('id'));

                    $successMessage = 'Visit Details Edited Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
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
        } elseif (Input::get('reschedule_pending_visit')) {
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
        } elseif (Input::get('download')) {
            $project_id = $_GET['project_id'];
            $override->dateRange('visit', 'visit_date', $_GET['from'], $_GET['to'], 'project_id', $project_id);
            $y = 0;
            $list = array();
            $data1 = null;
            $mqz = null;
            $am = null;
            $r = 0;
            while ($y <= $user->dateDiff($_GET['to'], $_GET['from'])) {
                if ($y == 0) {
                    $list[$y] = 'Study ID';
                    $y++;
                } else {
                    $list[$y] = date('Y-m-d', strtotime($_GET['from'] . ' + ' . $y . ' days'));
                    $y++;
                }
            }
            $data1[0] = $list;

            foreach ($override->dateRangeD('visit', 'client_id', 'visit_date', $_GET['from'], $_GET['to'], 'project_id', $project_id) as $dt) {
                $f = 0;
                if ($dt['status'] != 4) {
                    $client = $override->get('clients', 'id', $dt['client_id'])[0];
                    $clientGroup = $override->get('patient_group', 'id', $client['pt_group'])[0]['name'];
                    foreach ($list as $data) {
                        $d = $override->getNews('visit', 'client_id', $dt['client_id'], 'visit_date', $data)[0];
                        if ($f == 0) {
                            $mqz[$f] = $client['study_id'] . '(' . $clientGroup . ') ';
                            $f++;
                        } else {
                            if ($d) {
                                if ($d['status'] == 1) {
                                    $mqz[$f] = 'Done ' . $d['visit_code'] . ' ' . $d['visit_type'];
                                    $f++;
                                } elseif ($d['status'] == 2) {
                                    $mqz[$f] = 'Missed ' . $d['visit_code'] . ' ' . $d['visit_type'];
                                    $f++;
                                } elseif ($d['status'] == 0) {
                                    $mqz[$f] = 'Scheduled ' . $d['visit_code'] . ' ' . $d['visit_type'];
                                    $f++;
                                }
                            } else {
                                $mqz[$f] = 'NO VISIT ';
                                $f++;
                            }
                        }
                    }
                    $am[$r] = $mqz;
                    $r++;
                }
            }
            $user->exportSchedule($data1, $am, 'schedule');
        } elseif (Input::get('edit_pt_group')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'group_name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('patient_group', array(
                        'name' => Input::get('group_name'),
                    ), Input::get('id'));
                    $successMessage = 'Group Updated Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('edit_study')) {
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
                    $user->updateRecord('study', array(
                        'name' => Input::get('name'),
                        'study_code' => Input::get('study_code'),
                        'sample_size' => Input::get('sample_size'),
                        'duration' => Input::get('duration'),
                        'start_date' => Input::get('start_date'),
                        'end_date' => Input::get('end_date'),
                        'details' => Input::get('details'),
                    ), Input::get('id'));
                    $successMessage = 'Study Edited Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('edit_image')) {
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
                        $user->updateRecord('images', array(
                            'location' => $attachment_file,
                            'project_id' => Input::get('project'),
                            'cat' => Input::get('image_cat'),
                            'status' => 1
                        ), Input::get('id'));
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

    <!-- <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" /> -->


</head>

<body class="bg-img-num1" data-settings="open">

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php require 'topBar.php' ?>
            </div>
        </div>
        <div class="row">
            <?php if ($_GET['id'] != 11) { ?>
                <div class="col-md-<?= $col1 ?>">
                    <?php require 'sideBar.php' ?>
                </div>
            <?php } ?>
            <div class="col-md-offset-0 col-md-<?= $col2 ?>">
                <div>
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
                </div>
                <?php if ($_GET['id'] == 1) { ?>
                    <div class="block">
                        <div class="header">
                            <h2>TODAY SCHEDULE VISITS FOR ALL STUDIES</h2>
                        </div>
                        <div class="content">
                            <table id="example2" cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
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
                                    <?php


                                    if (isset($_GET['study'])) {
                                        $study =  $_GET['study'];
                                    }


                                    $x = 1;
                                    foreach ($override->get2('visit', 'visit_date', date('Y-m-d'), 'project_id', $study) as $data) {
                                        $client = $override->get('clients', 'id', $data['client_id'])[0];
                                        $lastVisit = $override->getlastRow('visit', 'client_id', $data['client_id'], 'visit_date');
                                        if ($client['status'] == 1) { ?>
                                            <tr>
                                                <td><?= $client['study_id']; ?></td>
                                                <td><?= $override->get('study', 'id', $client['project_id'])[0]['study_code']; ?></td>
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
                                                            <?php if ($data['sn_cl_status'] == 0) { ?>&nbsp;
                                                            <button class="btn btn-warning">Pending</button>
                                                        <?php } elseif ($data['sn_cl_status'] == 1) { ?>
                                                            <button class="btn btn-success">Reviewed</button><button class="btn btn-info"><?= $data['initial2'] ?></button>
                                                        <?php } elseif ($data['sn_cl_status'] == 2) { ?>
                                                            <button class="btn btn-danger">Missed</button><button class="btn btn-info"><?= $data['initial2'] ?></button>
                                                        <?php } ?>
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
                                                        <div><a href="#appnt<?= $x ?>" data-toggle="modal" class="widget-icon" title="Add Visit"><span class="icon-share"></span></a></div>

                                                    </td>
                                                    <td>

                                                        <div><a href="#edit_visit<?= $x ?>" data-toggle="modal" class="widget-icon" title="Edit Scheduled and Un-scheduled Visit"><span class="icon-edit"></span></a></div>
                                                    </td>



                                                <?php } ?>


                                                <div class="modal" id="re_schedule_vaccine<?= $y ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">RE - SCHEDULE VACCINE OR PRE - VAC</h4>
                                                                </div>
                                                                <div class="modal-body clearfix">
                                                                    <div class="controls">
                                                                        <div class="form-row" id="st">
                                                                            <div class="col-md-2">Project:</div>
                                                                            <div class="col-md-10">
                                                                                <select class="form-control" id="project_name" name="project_name" required>
                                                                                    <option value="<?= $group['project_id'] ?>"><?= $override->get('study', 'id', $group['project_id'])['id']['name'] ?></option>
                                                                                    <?php foreach ($override->getData('study') as $group) { ?>
                                                                                        <option value="<?= $group['name'] ?>"><?= $group['name'] ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Visit:</div>
                                                                            <div class="col-md-10">
                                                                                <select class="form-control" id="c" name="visit" required>
                                                                                    <option value="">Select Visit</option>
                                                                                    <option value="1">V1</option>
                                                                                    <option value="2">V2</option>
                                                                                    <option value="3">V3</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row" id="st">
                                                                            <div class="col-md-2">Group:</div>
                                                                            <div class="col-md-10">
                                                                                <select class="form-control" id="group_name" name="group_name" required>
                                                                                    <option value="">Select Group</option>
                                                                                    <?php foreach ($override->getData('patient_group') as $group) { ?>
                                                                                        <option value="<?= $group['name'] ?>"><?= $group['name'] ?></option>
                                                                                    <?php } ?>
                                                                                </select>
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
                                                                        <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                                        <!-- <input type="hidden" name="participant_group" id="participant_group" class="form-control" value="" /> -->
                                                                        <input type="submit" name="reschedule_vaccine" value="Submit" class="btn btn-success btn-clean">
                                                                    </div>
                                                                    <div class="pull-right col-md-2">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>



                                                <div class="modal" id="edit_visit<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">Re - Schedule VISIT</h4>
                                                                </div>
                                                                <div class="modal-body clearfix">
                                                                    <div class="controls">
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Study Name :</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="study_name" class="datepicker form-control" value="<?= $override->get('study', 'id', $client['project_id'])[0]['study_code']; ?>" disabled />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Client ID:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="client_id" class="form-control" value="<?= $client['study_id'] ?>" disabled />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">VISIT CODE:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="visit_code" class="form-control" value="<?= $data['visit_code'] ?>" disabled />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Study Group:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="initials" class="form-control" value="<?= $override->get('patient_group', 'id', $client['pt_group'])[0]['name'] ?>" disabled />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Visit Date:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="reschedule_date" class="datepicker form-control" value="<?= $data['visit_date'] ?>" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Details:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="details" class="form-control" value="<?= 'Changed from ' .  ' ' . $data['visit_date'] . ' to ' . ' ' . $data['visit_date'] ?>" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Reason:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="reason" class="form-control" value="<?= $data['reason'] ?>" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="pull-right col-md-3">
                                                                        <input type="hidden" name="reschedule_id" class="form-control" value="<?= $data['id'] ?>" required="" />
                                                                        <input type="submit" name="reschedule_visit" value="SUBMIT" class="btn btn-success btn-clean">
                                                                    </div>
                                                                    <div class="pull-right col-md-2">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="modal" id="appnt<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">APPOINTMENT</h4>
                                                                </div>
                                                                <div class="modal-body clearfix">
                                                                    <div class="controls">
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">VISIT CODE:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="hidden" name="visit_code" value="<?= $client['visit_code'] + 1 ?>">
                                                                                <input type="text" name="visit_code" class="form-control" value="<?= $data['visit_code'] . '(' . $data['visit_type'] . ')' ?>" disabled />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row" id="st">
                                                                            <div class="col-md-2">Status</div>
                                                                            <div class="col-md-10">
                                                                                <select class="form-control" id="site" name="visit_status" required>
                                                                                    <option value="">Select Status</option>
                                                                                    <option value="1">Complete</option>
                                                                                    <option value="2">Missing</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="pull-right col-md-3">
                                                                        <input type="hidden" name="id" value="<?= $lastVisit[0]['id'] ?>">
                                                                        <input type="hidden" name="v_id" value="<?= $data['id'] ?>">
                                                                        <input type="hidden" name="client_id" value="<?= $client['id'] ?>">
                                                                        <input type="hidden" name="sn2" value="<?= $data['status'] ?>">
                                                                        <input type="hidden" name="sn" value="<?= $data['sn_cl_status'] ?>">
                                                                        <input type="hidden" name="sn3" value="<?= $data['dc_status'] ?>">
                                                                        <input type="submit" name="appointment" value="Submit" class="btn btn-success btn-clean">
                                                                    </div>
                                                                    <div class="pull-right col-md-2">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </tr>
                                    <?php }
                                        $x++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } elseif ($_GET['id'] == 2) { ?>
                    <div class="block">
                        <div class="header">
                            <h2>MISSED VISITS</h2>
                        </div>
                        <div class="content">

                            <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                                <thead>
                                    <tr>
                                        <th width="5%">CLIENT ID</th>
                                        <th width="5%">VISIT CODE</th>
                                        <th width="5%">VISIT DATE</th>
                                        <th width="5%">DAY</th>
                                        <th width="5%">STUDY</th>
                                        <th width="5%">GROUP</th>
                                        <th width="5%">DAYS MISSING</th>
                                        <th width="5%">STATUS</th>
                                        <th width="5%">DETAILS</th>


                                        <?php
                                        if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {

                                        ?>
                                            <th width="5%">ACTION</th>

                                            <th width="5%">PHONE</th>

                                        <?php } ?>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    if (isset($_GET['study'])) {
                                        $study =  $_GET['study'];
                                    }



                                    $x = 1;
                                    foreach ($override->getDataOrderBy1('visit', 'status', 2, 'visit_date', 'project_id', $study) as $data) {
                                        $cl = $override->get('clients', 'id', $data['client_id']);
                                        if ($cl[0]['status'] == 1) {
                                            if ($data['visit_date'] <= date('Y-m-d')) {
                                                $lastVisit = $override->getlastRow('visit', 'client_id', $data['client_id'], 'id');
                                                $client = $override->get('clients', 'id', $data['client_id']);
                                                $group = $override->get('patient_group', 'id', $client[0]['pt_group'])[0]['name'];
                                                $mcDays = (strtotime(date('Y-m-d')) - strtotime($data['visit_date'])) ?>
                                                <tr>
                                                    <td><?= $client[0]['study_id'] ?></td>
                                                    <td><?= $data['visit_code'] ?></td>
                                                    <td><?= $data['visit_date'] ?></td>
                                                    <td><?= date('l', strtotime($data['visit_date'])) ?></td>

                                                    <td><?= $override->get('study', 'id', $client[0]['project_id'])[0]['study_code'] ?></td>
                                                    <td><?= $group ?></td>
                                                    <td><?= ($mcDays / 86400) ?></td>

                                                    <td>
                                                        <div class="btn-group btn-group-xs"><?php if ($client[0]['status'] == 2) { ?>&nbsp;<button class="btn btn-danger">End Study</button> <?php echo $client[0]['reason'] . ' { ' . $client[0]['details'] . ' } ';
                                                                                                                                                                                            } else { ?><button class="btn btn-success">Active</button><?php }
                                                                                                                                                                                                                                                    echo ' ' ?></div>
                                                    </td>
                                                    <td>
                                                        <?= $data['details'] ?>
                                                    </td>

                                                    <?php
                                                    if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
                                                    ?>


                                                        <td>
                                                            <a href="#detail<?= $x ?>" data-toggle="modal" class="widget-icon" title="Update Information"><span class="glyphicon-log-out"></span></a>
                                                        </td>
                                                        <td><?= $client[0]['phone_number']  ?></td>

                                                    <?php } ?>
                                                </tr>
                                                <div class="modal" id="detail<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">EDIT REASON</h4>
                                                                </div>
                                                                <div class="modal-body clearfix">
                                                                    <div class="controls">
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">CLIENT ID:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="client_id" value="<?= $client[0]['study_id'] ?>" class="form-control" disabled></inputtext>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">VISIT CODE:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="visit_code" value="<?= $data['visit_code'] ?>" class="form-control" disabled></inputtext>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">DETAILS:</div>
                                                                            <div class="col-md-10">
                                                                                <textarea name="details" class="form-control" rows="4"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="pull-right col-md-3">
                                                                        <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                                                        <input type="submit" name="add_reason" value="Submit" class="btn btn-success btn-clean">
                                                                    </div>
                                                                    <div class="pull-right col-md-2">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                    <?php $x++;
                                            }
                                        }
                                    } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                <?php } elseif ($_GET['id'] == 3) { ?>
                    <div class="block">
                        <div class="header">
                            <h2>All SCHEDULED VISIT</h2>
                        </div>

                        <div class="content">
                            <table id="allVisit" cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                                <thead>
                                    <tr>
                                        <th width="5%">CLIENT ID</th>
                                        <th width="5%">INITIAL</th>
                                        <th width="5%">STUDY</th>
                                        <th width="5%">GROUP</th>
                                        <th width="5%">VISIT CODE</th>
                                        <th width="5%">SCHEDULE TYPE</th>
                                        <th width="5%">VISIT TYPE</th>
                                        <th width="5%">STATUS</th>
                                        <th width="5%">VISIT DATE</th>
                                        <th width="5%">DAY</th>
                                        <th width="5%">DETAILS</th>
                                        <th width="5%">REASON</th>
                                        <?php
                                        if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {

                                        ?>
                                            <th width="5%">PHONE</th>
                                            <th width="20%">ACTION</th>

                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $x = 1;
                                    if (isset($_GET['study'])) {
                                        $study =  $_GET['study'];
                                    }
                                    // foreach ($override->getDataOrderByAsc('schedule', 'visit_date') as $data) {
                                    foreach ($override->getDataOrderByAsc('visit', 'client_id', 'project_id', $study) as $data) {
                                        $client = $override->get('clients', 'id', $data['client_id']);
                                        $lastVisit = $override->getlastRow('visit', 'client_id', $data['client_id'], 'id');
                                        $group = $override->get('patient_group', 'id', $client[0]['pt_group'])[0]['name'];

                                    ?>


                                        <tr>
                                            <td><?= $client[0]['study_id'] ?></td>
                                            <td><?= $client[0]['initials'] ?></td>
                                            <td><?= $data['project_id'] ?></td>
                                            <td><?= $group ?></td>

                                            <td><?= $data['visit_code'] ?></td>
                                            <td><?= $data['visit_type'] ?></td>
                                            <td>
                                                <div class="btn-group btn-group-xs">
                                                    <?php if ($data['schedule'] == 'Scheduled') { ?>&nbsp;
                                                    <button class="btn btn-info">Scheduled</button>
                                                <?php } elseif ($data['schedule'] == 'UnScheduled') { ?>
                                                    <button class="btn btn-danger">UnScheduled</button>
                                                <?php } ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-xs">
                                                    <?php if ($data['status'] == 0) { ?>&nbsp;
                                                    <button class="btn btn-warning">NOT DONE</button>
                                                <?php } elseif ($data['status'] == 3) { ?>
                                                    <button class="btn btn-success">Pending</button>
                                                <?php } elseif ($data['status'] == 1) { ?>
                                                    <button class="btn btn-success">Completed</button>
                                                <?php } elseif ($data['status'] == 2) { ?>
                                                    <button class="btn btn-danger">Missed</button>
                                                <?php } ?>
                                                </div>
                                            </td>
                                            <td><?= $data['visit_date'] ?></td>
                                            <td><?= date('l', strtotime($data['visit_date'])) ?></td>

                                            <td><?= $data['details'] ?></td>
                                            <td><?= $data['reason'] ?></td>

                                            <?php
                                            if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {

                                            ?>

                                                <td><?= $client[0]['phone_number'] ?></td>

                                                <td>
                                                    <a href="#edit_visit_details<?= $x ?>" data-toggle="modal" class="widget-icon" title="Edit Visit Details"><span class="icon-pencil"></span></a>
                                                </td>

                                            <?php } ?>
                                        </tr>
                                        <div class="modal" id="edit_visit_details<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">EDIT VISIT DETAILS</h4>
                                                        </div>
                                                        <div class="modal-body clearfix">
                                                            <div class="controls">
                                                                <div class="form-row">
                                                                    <div class="col-md-2">CLIENT ID:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" name="study_id" class="form-control" value="<?= $client[0]['study_id'] ?>" disabled />
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">VISIT CODE:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" name="visit_code" class="form-control" value="<?= $data['visit_code'] ?>" disabled />
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">STUDY NAME</div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" name="study" class="datepicker form-control" value="<?= $data['project_id'] ?>" disabled />
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">INITIALS:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" name="initials" class="form-control" value="<?= $client[0]['initials'] ?>" disabled />
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">STATUS:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="number" name="status" class="form-control" value="<?= $data['status'] ?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">VISIT DATE:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" name="visit_date" class="datepicker form-control" value="<?= $data['visit_date'] ?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">DETAILS:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" name="details" class="datepicker form-control" value="<?= 'Changed from ' .  ' ' . $data['visit_date'] . ' to ' . ' ' . $data['visit_date'] ?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">REASON:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" name="reason" class="form-control" value="<?= $data['reason'] ?>" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="pull-right col-md-3">
                                                                <input type="hidden" name="id" class="form-control" value="<?= $data['id'] ?>" required="" />
                                                                <input type="submit" name="edit_visit_detail" value="SUBMIT" class="btn btn-success btn-clean">
                                                            </div>
                                                            <div class="pull-right col-md-2">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php $x++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } elseif ($_GET['id'] == 4) { ?>
                    <div class="block">
                        <div class="header">
                            <h2>TOTAL VACCINATED PARTICIPANTS</h2>
                        </div>
                        <div class="content">
                            <table id="allVisit" cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="10%">STUDY ID</th>
                                        <th width="10%">STATUS</th>
                                        <th width="10%">GROUP</th>
                                        <th width="15%">VIEW</th>
                                        <?php
                                        if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
                                        ?>
                                            <th width="10%">PHONE NUMBER</th>
                                        <?php } ?>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_GET['study'])) {
                                        $study =  $_GET['study'];
                                    }

                                    $x = 1;
                                    $no = 0;
                                    $data = $override->getRepeatAll('visit', 'client_id', 'id', 'project_id', $study);

                                    foreach ($data as $value) {
                                        $client = $override->get('clients', 'id', $value['client_id']);
                                        $group = $override->get('patient_group', 'id', $client[0]['pt_group'])[0]['name'];
                                    ?>

                                        <tr>
                                            <td><?= $x ?></td>
                                            <td>
                                                <?= $client[0]['study_id'] ?>
                                            </td>
                                            <td>
                                                <?php if ($client[0]['status'] == 0) { ?>
                                                    <div class="btn-group btn-group-xs">
                                                        <button class="btn btn-danger"><span class="icon-ok-sign"></span> End Study </button>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="btn-group btn-group-xs">
                                                        <button class="btn btn-success"><span class="icon-ok-sign"></span> Active </button>
                                                    </div>
                                                <?php } ?>
                                            </td>
                                            <td><?= $group ?></td>

                                            <?php
                                            if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
                                            ?>

                                            <?php } ?>
                                            <td>
                                                <div class="btn-group btn-group-xs"><a href="info.php?id=6&cid=<?= $value['client_id'] ?>" class="btn btn-info btn-clean"><span class="icon-eye-open"></span> View All Visits</a></div>
                                            </td>

                                            <?php
                                            if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
                                            ?>
                                                <td><?= $client[0]['phone_number'] ?></td>

                                            <?php } ?>
                                        </tr>
                                    <?php $x++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } elseif ($_GET['id'] == 5) { ?>
                    <div class="block">
                        <div class="header">
                            <h2>SCREENED PARTICIPANTS</h2>
                        </div>
                        <div class="content">
                            <table id="allVisit" cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                                <thead>
                                    <tr>
                                        <th width="5%">STUDY ID</th>
                                        <th width="5%">INITIAL</th>
                                        <th width="5%">STATUS</th>
                                        <th width="5%">STUDY</th>
                                        <th width="5%">GROUP</th>
                                        <th width="6%">END OF STUDY</th>

                                        <?php
                                        if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
                                        ?>

                                            <th width="8%">PHONE NUMBER 1</th>
                                            <th width="8%">PHONE NUMBER 2</th>
                                            <th width="20%">Manage</th>

                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_GET['study'])) {
                                        $study =  $_GET['study'];
                                    }

                                    $y = 1;
                                    foreach ($override->getDataOrderByAsc('clients', 'study_id', 'project_id', $study) as $client) {
                                        $lastVisit = $override->getlastRow('visit', 'client_id', $client['id'], 'id') ?>
                                        <tr>
                                            <td><?= $client['study_id'] ?></td>
                                            <td><?= $client['initials'] ?></td>
                                            <td>
                                                <?php
                                                if ($client['status'] == 1) {
                                                ?><div class="btn-group btn-group-xs"><button class="btn btn-success">Active</button></div><?php
                                                                                                                                        } else {
                                                                                                                                            ?>

                                                    <div class="btn-group btn-group-xs"><button class="btn btn-danger">End Study</button></div>

                                                <?php
                                                                                                                                        }
                                                ?>
                                            </td>
                                            <td><?= $override->get('study', 'id', $client['project_id'])[0]['study_code'] ?></td>
                                            <td><?= $override->get('patient_group', 'id', $client['pt_group'])[0]['name'] ?></td>
                                            <td><?php if ($lastVisit) {
                                                    echo $lastVisit[0]['visit_date'];
                                                } else {
                                                    echo '';
                                                } ?></td>

                                            <?php
                                            if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
                                            ?>

                                                <td><?= $client['phone_number'] ?></td>
                                                <td><?= $client['phone_number2'] ?></td>
                                                <td>
                                                    <a href="#edit_client<?= $y ?>" data-toggle="modal" class="widget-icon" title="Edit Client Information"><span class="icon-pencil"></span></a>
                                                    <a href="#reasons<?= $y ?>" data-toggle="modal" class="widget-icon" title="End Study"><span class="icon-warning-sign"></span></a>
                                                    <a href="#edit_schedule<?= $y ?>" data-toggle="modal" class="widget-icon" title="Edit Schedule"><span class="icon-refresh"></span></a>
                                                    <a href="#delete_client<?= $y ?>" data-toggle="modal" class="widget-icon" title="Delete Staff"><span class="icon-trash"></span></a>
                                                    <a href="info.php?id=11&pid=<?= $client['id'] ?>" class="widget-icon" title="list schedule"><span class="icon-list"></span></a>
                                                    <a href="#delete_client_schedule<?= $y ?>" data-toggle="modal" class="widget-icon" title="Delete Patient Schedules"><span class="icon-remove"></span></a>
                                                </td>

                                            <?php } ?>
                                        </tr>
                                        <div class="modal" id="edit_client<?= $y ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">EDIT CLIENT</h4>
                                                        </div>
                                                        <div class="modal-body clearfix">
                                                            <div class="controls">
                                                                <div class="form-row">
                                                                    <div class="col-md-2">CLIENT ID:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" name="study_id" class="form-control" value="<?= $client['study_id'] ?>" required="" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">STUDY NAME:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="number" name="project_id" class="form-control" value="<?= $override->get('study', 'id', $client['project_id']) ?>" required="" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">INITIALS:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" name="initials" class="form-control" value="<?= $client['initials'] ?>" required="" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">Phone:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" name="phone_number" class="form-control" value="<?= $client['phone_number'] ?>" required="" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">Phone2:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" name="phone_number2" class="form-control" value="<?= $client['phone_number2'] ?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-row" id="st">
                                                                    <div class="col-md-2">Project:</div>
                                                                    <div class="col-md-10">
                                                                        <select class="form-control" id="project_id" name="project_id" required>
                                                                            <option value="<?= $group['project_id'] ?>"><?= $override->get('study', 'id', $group['project_id'])['id']['name'] ?></option>
                                                                            <?php foreach ($override->getData('study') as $group) { ?>
                                                                                <option value="<?= $group['id'] ?>"><?= $group['name'] ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="pull-right col-md-3">
                                                                <input type="hidden" name="id" class="form-control" value="<?= $client['id'] ?>" required="" />
                                                                <input type="submit" name="edit_client" value="SUBMIT" class="btn btn-success btn-clean">
                                                            </div>
                                                            <div class="pull-right col-md-2">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal" id="reasons<?= $y ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">END OF STUDY</h4>
                                                        </div>
                                                        <div class="modal-body clearfix">
                                                            <div class="controls">
                                                                <div class="form-row">
                                                                    <div class="col-md-2">Reason:</div>
                                                                    <div class="col-md-10">
                                                                        <select class="form-control" id="c" name="reason" required="">
                                                                            <option value="">Select reason for study termination</option>
                                                                            <?php foreach ($override->getData('end_study_reason') as $end_study) { ?>
                                                                                <option value="<?= $end_study['reason'] ?>"><?= $end_study['reason'] ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">Details:</div>
                                                                    <div class="col-md-10">
                                                                        <textarea name="details" class="form-control" rows="4"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="pull-right col-md-3">
                                                                <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                                <input type="submit" name="add_end_study" value="Submit" class="btn btn-success btn-clean">
                                                            </div>
                                                            <div class="pull-right col-md-2">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal" id="edit_schedule<?= $y ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">EDIT SCHEDULE</h4>
                                                        </div>
                                                        <div class="modal-body clearfix">
                                                            <div class="controls">
                                                                <div class="form-row" id="st">
                                                                    <div class="col-md-2">Project:</div>
                                                                    <div class="col-md-10">
                                                                        <select class="form-control" id="project_name" name="project_name" required>
                                                                            <option value="<?= $group['project_id'] ?>"><?= $override->get('study', 'id', $group['project_id'])['id']['name'] ?></option>
                                                                            <?php foreach ($override->getData('study') as $group) { ?>
                                                                                <option value="<?= $group['name'] ?>"><?= $group['name'] ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">Visit:</div>
                                                                    <div class="col-md-10">
                                                                        <select class="form-control" id="c" name="visit" required>
                                                                            <option value="">Select Visit</option>
                                                                            <option value="1">V1</option>
                                                                            <option value="2">V2</option>
                                                                            <option value="3">V3</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row" id="st">
                                                                    <div class="col-md-2">Group:</div>
                                                                    <div class="col-md-10">
                                                                        <select class="form-control" id="group_name" name="group_name" required>
                                                                            <option value="">Select Group</option>
                                                                            <?php foreach ($override->getData('patient_group') as $group) { ?>
                                                                                <option value="<?= $group['name'] ?>"><?= $group['name'] ?></option>
                                                                            <?php } ?>
                                                                        </select>
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
                                                                <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                                <input type="hidden" name="participant_group" id="participant_group" class="form-control" value="" />
                                                                <input type="submit" name="reschedule_vaccine" value="Submit" class="btn btn-success btn-clean">
                                                            </div>
                                                            <div class="pull-right col-md-2">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal modal-danger" id="delete_client<?= $y ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS PATIENT ?</h4>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="col-md-2">Reason:</div>
                                                            <div class="col-md-10">
                                                                <textarea rows="4" name="reason" class="form-control" value="<?= $client['study_id'] ?>" required=""></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <div class="col-md-2 pull-right">
                                                                <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                                <input type="submit" name="delete_client" value="END" class="btn btn-default btn-clean">
                                                            </div>
                                                            <div class="col-md-2 pull-right">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal modal-danger" id="delete_client_schedule<?= $y ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS PATIENT SCHEDULES?</h4>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="col-md-2 pull-right">
                                                                <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                                <input type="submit" name="delete_client_schedule" value="DELETE" class="btn btn-default btn-clean">
                                                            </div>
                                                            <div class="col-md-2 pull-right">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php $y++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } elseif ($_GET['id'] == 6) { ?>
                    <div class="block">
                        <div class="header">
                            <h2>VISITS</h2>
                        </div>
                        <div class="content">
                            <table id="allVisit" cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                                <thead>
                                    <tr>
                                        <th width="3%">STUDY ID</th>
                                        <th width="3%">INITIALS</th>
                                        <th width="3%">STUDY</th>
                                        <th width="3%">GROUP</th>
                                        <th width="3%">VISIT CODE</th>
                                        <th width="3%">VISIT TYPE</th>
                                        <th width="3%">SCHEDLUE TYPE</th>
                                        <th width="3%">VISIT DATE</th>
                                        <th width="3%">DAY</th>
                                        <th width="3%">STATUS</th>
                                        <th width="3%">DETAILS</th>
                                        <th width="3%">REASON</th>
                                        <th width="3%">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $y = 1;
                                    if (isset($_GET['cid'])) {


                                        $x = 1;
                                        foreach ($override->getDataOrderByA1('visit', 'client_id', $_GET['cid'], 'visit_date') as $data) {
                                            $client = $override->get('clients', 'id', $data['client_id']);
                                            $nextVisit = $override->get('schedule', 'client_id', $data['client_id']) ?>
                                            <tr>
                                                <td><?= $client[0]['study_id'] ?></td>
                                                <td><?= $client[0]['initials'] ?></td>
                                                <td><?= $data['project_id'] ?></td>
                                                <td><?= $override->get('patient_group', 'id', $client[0]['pt_group'])[0]['name'] ?></td>
                                                <td><?= $data['visit_code'] ?></td>

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
                                                        <?php if ($data['schedule'] == 'Scheduled') { ?>&nbsp;
                                                        <button class="btn btn-info">Scheduled</button>
                                                    <?php } elseif ($data['schedule'] == 'UnScheduled') { ?>
                                                        <button class="btn btn-danger">UnScheduled</button>
                                                    <?php } ?>
                                                    </div>
                                                </td>

                                                <td><?= $data['visit_date'] ?></td>
                                                <td><?= date('l', strtotime($data['visit_date'])) ?></td>

                                                <td>
                                                    <div class="btn-group btn-group-xs">
                                                        <?php if ($data['status'] == 0) { ?>&nbsp;
                                                        <button class="btn btn-warning">NOT DONE</button>
                                                    <?php } elseif ($data['status'] == 3) { ?>
                                                        <button class="btn btn-success">Pending</button>
                                                    <?php } elseif ($data['status'] == 1) { ?>
                                                        <button class="btn btn-success">Completed</button>
                                                    <?php } elseif ($data['status'] == 2) { ?>
                                                        <button class="btn btn-danger">Missed</button>
                                                    <?php } ?>
                                                    </div>
                                                </td>

                                                <td><?= $data['details'] ?></td>
                                                <td><?= $data['reason'] ?></td>




                                                <?php
                                                if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
                                                ?>
                                                    <td>
                                                        <a href="#edit_visit<?= $y ?>" data-toggle="modal" class="widget-icon" title="Edit Staff Information"><span class="icon-pencil"></span></a>
                                                        <a href="#delete_visit<?= $y ?>" data-toggle="modal" class="widget-icon" title="Delete Staff"><span class="icon-trash"></span></a>
                                                    </td>

                                                <?php } ?>
                                            </tr>
                                            <div class="modal" id="edit_visit<?= $y ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="post">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                <h4 class="modal-title">EDIT VISIT</h4>
                                                            </div>
                                                            <div class="modal-body clearfix">
                                                                <div class="controls">
                                                                    <div class="form-row">
                                                                        <div class="col-md-2">STUDY ID:</div>
                                                                        <div class="col-md-10">
                                                                            <input type="text" name="study_id" class="form-control" value="<?= $client[0]['study_id'] ?>" disabled />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="col-md-2">VISIT CODE:</div>
                                                                        <div class="col-md-10">
                                                                            <input type="text" name="visit_code" class="form-control" value="<?= $data['visit_code'] ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="col-md-2">INITIALS:</div>
                                                                        <div class="col-md-10">
                                                                            <input type="text" name="initials" class="form-control" value="<?= $client[0]['initials'] ?>" disabled />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="col-md-2">STATUS:</div>
                                                                        <div class="col-md-10">
                                                                            <input type="number" name="status" class="form-control" value="<?= $data['status'] ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="col-md-2">Date:</div>
                                                                        <div class="col-md-10">
                                                                            <input type="text" name="visit_date" class="datepicker form-control" value="<?= $data['visit_date'] ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="col-md-2">Details:</div>
                                                                        <div class="col-md-10">
                                                                            <input type="text" name="details" class="form-control" value="<?= 'Changed from ' .  ' ' . $data['visit_date'] . ' to ' . ' ' . $data['visit_date'] ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="col-md-2">Reason:</div>
                                                                        <div class="col-md-10">
                                                                            <input type="text" name="reason" class="form-control" value="<?= $data['reason'] ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="pull-right col-md-3">
                                                                    <input type="hidden" name="id" class="form-control" value="<?= $data['id'] ?>" required="" />
                                                                    <input type="submit" name="edit_visit" value="SUBMIT" class="btn btn-success btn-clean">
                                                                </div>
                                                                <div class="pull-right col-md-2">
                                                                    <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal modal-danger" id="delete_visit<?= $y ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="post">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS VISIT?</h4>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="col-md-2 pull-right">
                                                                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                                                    <input type="hidden" name="cl_id" value="<?= $data['client_id'] ?>">
                                                                    <input type="submit" name="delete_visit" value="DELETE" class="btn btn-default btn-clean">
                                                                </div>
                                                                <div class="col-md-2 pull-right">
                                                                    <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php $y++;
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } elseif ($_GET['id'] == 7) { ?>
                    <div class="block">
                        <div class="header">
                            <h2>PARTICIPANTS VISIT</h2>
                        </div>
                        <div class="content">
                            <table id="allVisit" cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                                <thead>
                                    <tr>

                                        <th width="20%">STUDY ID</th>
                                        <th width="10%">VISIT CODE</th>
                                        <th width="25%">LAST VISIT</th>

                                        <?php
                                        if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
                                        ?>
                                            <th width="20%">PHONE NUMBER</th>
                                            <th width="20%"></th>

                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $x = 1;
                                    if (isset($_GET['cid'])) {
                                        foreach ($override->get('schedule', 'client_id', $_GET['cid']) as $data) {
                                            $client = $override->get('clients', 'id', $data['client_id']) ?>
                                            <tr>
                                                <td><?= $client[0]['study_id'] ?></td>
                                                <td><?= $client[0]['visit_code'] ?></td>
                                                <td><?= $data['visit_date'] ?></td>
                                                <td><?= $client[0]['phone_number'] ?></td>
                                                <td>
                                                    <a href="#appnt<?= $x ?>" data-toggle="modal" class="widget-icon" title="Add Visit"><span class="icon-share"></span></a>
                                                </td>
                                                <div class="modal" id="appnt<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">APPOINTMENT</h4>
                                                                </div>
                                                                <div class="modal-body clearfix">
                                                                    <div class="controls">
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">VISIT CODE:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="hidden" name="visit_code" value="<?= $client[0]['visit_code'] + 1 ?>">
                                                                                <input type="number" name="visit_code" class="form-control" value="<?= $client[0]['visit_code'] + 1 ?>" disabled />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">NEXT VISIT:</div>
                                                                            <div class="col-md-10">
                                                                                <div class="input-group">
                                                                                    <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                                                                    <input type="text" name="visit_date" class="datepicker form-control" value="" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="pull-right col-md-3">
                                                                        <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                                                        <input type="submit" name="appointment" value="Submit" class="btn btn-success btn-clean">
                                                                    </div>
                                                                    <div class="pull-right col-md-2">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </tr>
                                    <?php $x++;
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } elseif ($_GET['id'] == 8) { ?>
                    <div class="block">
                        <div class="header">
                            <h2>STAFF</h2>
                        </div>
                        <div class="content">
                            <table id="allVisit" cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th width="15%">NAME</th>
                                        <th width="10%">USERNAME</th>
                                        <th width="10%">POSITION</th>
                                        <th width="10%">COUNTRY</th>
                                        <th width="10%">SITE</th>
                                        <th width="10%">PHONE</th>
                                        <th width="10%">EMAIL</th>
                                        <th width="10%">STATUS</th>
                                        <th width="25%">MANAGE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $y = 0;
                                    $x = 1;
                                    if ($user->data()->access_level == 1 || $user->data()->access_level == 2 || $user->data()->access_level == 3) {
                                        $staffs = $override->get('staff', 'status', 1);
                                    } elseif ($user->data()->access_level == 4) {
                                        $staffs = $override->getNews('staff', 'status', 1, 'c_id', $user->data()->c_id);
                                    }
                                    foreach ($staffs as $staff) {
                                        if ($user->data()->access_level != 1 || $user->data()->id != $staff['id']) {
                                            if ($user->data()->access_level == 1) {
                                                $power = 1;
                                            } else {
                                                $power = 0;
                                            }
                                            $site = $override->get('site', 'id', $staff['s_id']);
                                            $country = $override->get('country', 'id', $staff['c_id']);
                                            $position = $override->get('position', 'id', $staff['position'])[0] ?>
                                            <tr>
                                                <td><?= $x ?></td>
                                                <td><?= $staff['firstname'] . ' ' . $staff['lastname'] ?></td>
                                                <td><?= $staff['username'] ?></td>
                                                <td><?= $position['name'] ?></td>
                                                <td><?= $country[0]['name'] ?></td>
                                                <td><?= $site[0]['name'] ?></td>
                                                <td><?= $staff['phone_number'] ?></td>
                                                <td><?= $staff['email_address'] ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-xs"> <?php if ($staff['token'] || $staff['count'] >= 4) { ?><button class="btn btn-warning">INACTIVE</button> <?php } else { ?><button class="btn btn-success">ACTIVE</button><?php } ?></div>
                                                </td>
                                                </td>
                                                <td>
                                                    <?php if ($staff['access_level'] != 2 || $power == 1) { ?>
                                                        <a href="#edit_staff<?= $y ?>" data-toggle="modal" class="widget-icon" title="Edit Staff Information"><span class="icon-pencil"></span></a>
                                                        <a href="#reset_password<?= $y ?>" data-toggle="modal" class="widget-icon" title="Reset Password to Default"><span class="icon-refresh"></span></a>
                                                        <a href="#delete_staff<?= $y ?>" data-toggle="modal" class="widget-icon" title="Delete Staff"><span class="icon-trash"></span></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <div class="modal" id="edit_staff<?= $y ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="post">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                <h4 class="modal-title">EDIT STAFF</h4>
                                                            </div>
                                                            <div class="modal-body clearfix">
                                                                <div class="controls">
                                                                    <div class="form-row">
                                                                        <div class="col-md-2">First Name:</div>
                                                                        <div class="col-md-10">
                                                                            <input type="text" name="firstname" class="form-control" value="<?= $staff['firstname'] ?>" required="" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="col-md-2">Last Name:</div>
                                                                        <div class="col-md-10">
                                                                            <input type="text" name="lastname" class="form-control" value="<?= $staff['lastname'] ?>" required="" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="col-md-2">Country:</div>
                                                                        <div class="col-md-10">
                                                                            <select class="form-control" id="c" name="country_id" required="">
                                                                                <option value="<?= $country[0]['id'] ?>"><?= $country[0]['name'] ?></option>
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
                                                                    <div id="w" style="display:none;" class="col-md-offset-5 col-md-1"><img src='img/owl/spinner-mini.gif' width="12" height="12" /><br>Loading..</div>
                                                                    <div class="form-row" id="s_i">
                                                                        <div class="col-md-2">Site:</div>
                                                                        <div class="col-md-10">
                                                                            <select class="form-control" id="site_i" name="site_id" required="">
                                                                                <option value="<?= $site[0]['id'] ?>"><?= $site[0]['name'] ?></option>
                                                                                <?php foreach ($override->get('site', 'status', 1) as $site) { ?>
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
                                                                                <option value="<?= $position['id'] ?>"><?= $position['name'] ?></option>
                                                                                <?php foreach ($override->getData('position') as $position) {
                                                                                    if ($user->data()->access_level == 1 && $user->data()->power == 1) { ?>
                                                                                        <option value="<?= $position['id'] ?>"><?= $position['name'] ?></option>
                                                                                    <?php } elseif ($user->data()->access_level == 1 && $position['name'] != 'Principle Investigator') { ?>
                                                                                        <option value="<?= $position['id'] ?>"><?= $position['name'] ?></option>
                                                                                    <?php } elseif ($user->data()->access_level == 2 && $position['name'] != 'Coordinator' && $position['name'] != 'Principle Investigator') { ?>
                                                                                        <option value="<?= $position['id'] ?>"><?= $position['name'] ?></option>
                                                                                <?php }
                                                                                } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="col-md-2">Username:</div>
                                                                        <div class="col-md-10">
                                                                            <input type="text" name="username" class="form-control" value="<?= $staff['username'] ?>" required="" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="col-md-2">Phone:</div>
                                                                        <div class="col-md-10">
                                                                            <input type="text" name="phone_number" class="form-control" value="<?= $staff['phone_number'] ?>" required="" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="col-md-2">Email:</div>
                                                                        <div class="col-md-10">
                                                                            <input type="text" name="email_address" class="form-control" value="<?= $staff['email_address'] ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="pull-right col-md-3">
                                                                    <input type="hidden" name="id" value="<?= $staff['id'] ?>">
                                                                    <input type="submit" name="edit_staff" value="Submit" class="btn btn-success btn-clean">
                                                                </div>
                                                                <div class="pull-right col-md-2">
                                                                    <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal modal-info" id="reset_password<?= $y ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="post">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                <h4 class="modal-title">YOU SURE YOU WANT TO RESET PASSWORD FOR THIS STAFF ?</h4>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="col-md-2 pull-right">
                                                                    <input type="hidden" name="firstname" value="<?= $staff['firstname'] ?>">
                                                                    <input type="hidden" name="email" value="<?= $staff['email_address'] ?>">
                                                                    <input type="hidden" name="id" value="<?= $staff['id'] ?>">
                                                                    <input type="submit" name="reset_password" value="RESET" class="btn btn-default btn-clean">
                                                                </div>
                                                                <div class="col-md-2 pull-right">
                                                                    <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal modal-danger" id="delete_staff<?= $y ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="post">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS STAFF ?</h4>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="col-md-2 pull-right">
                                                                    <input type="hidden" name="id" value="<?= $staff['id'] ?>">
                                                                    <input type="submit" name="delete_staff" value="DELETE" class="btn btn-default btn-clean">
                                                                </div>
                                                                <div class="col-md-2 pull-right">
                                                                    <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php $x++;
                                        }
                                        $y++;
                                    } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                <?php } elseif ($_GET['id'] == 9) { ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="block">
                                <div class="header">
                                    <h2>COUNTRIES</h2>
                                </div>
                                <div class="content">
                                    <table id="allVisit" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>NAME</th>
                                                <th>SHORT CODE</th>
                                                <th>MANAGE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $x = 1;
                                            foreach ($override->get('country', 'status', 1) as $country) { ?>
                                                <tr>
                                                    <td><?= $x ?></td>
                                                    <td><?= $country['name'] ?></td>
                                                    <td><?= $country['short_code'] ?></td>
                                                    <td>
                                                        <a href="#edit_country<?= $x ?>" data-toggle="modal" class="widget-icon" title="Edit Site Information"><span class="icon-pencil"></span></a>
                                                        <a href="#delete_country<?= $x ?>" data-toggle="modal" class="widget-icon" title="Delete Site"><span class="icon-trash"></span></a>
                                                    </td>
                                                </tr>
                                                <div class="modal" id="edit_country<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">EDIT COUNTRY</h4>
                                                                </div>
                                                                <div class="modal-body clearfix">
                                                                    <div class="controls">
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Name:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="country_name" class="form-control" value="<?= $country['name'] ?>" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Short Code:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="short_code" class="form-control" value="<?= $country['short_code'] ?>" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="pull-right col-md-3">
                                                                        <input type="hidden" name="id" value="<?= $country['id'] ?>">
                                                                        <input type="submit" name="edit_country" value="Submit" class="btn btn-success btn-clean">
                                                                    </div>
                                                                    <div class="pull-right col-md-2">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal modal-danger" id="delete_country<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS COUNTRY</h4>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="col-md-2 pull-right">
                                                                        <input type="hidden" name="id" value="<?= $country['id'] ?>">
                                                                        <input type="submit" name="delete_country" value="DELETE" class="btn btn-default btn-clean">
                                                                    </div>
                                                                    <div class="col-md-2 pull-right">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php $x++;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="block">
                                <div class="header">
                                    <h2>SITES</h2>
                                </div>
                                <div class="content">
                                    <table id="allVisit" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>NAME</th>
                                                <th>SHORT CODE</th>
                                                <th>COUNTY</th>
                                                <th>MANAGE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $x = 1;
                                            foreach ($override->get('site', 'status', 1) as $site) {
                                                $country = $override->get('country', 'id', $site['c_id']) ?>
                                                <tr>
                                                    <td><?= $x ?></td>
                                                    <td><?= $site['name'] ?></td>
                                                    <td><?= $site['short_code'] ?></td>
                                                    <td><?= $country[0]['name'] ?></td>
                                                    <td>
                                                        <a href="#edit_site<?= $x ?>" data-toggle="modal" class="widget-icon" title="Edit Site Information"><span class="icon-pencil"></span></a>
                                                        <a href="#delete_site<?= $x ?>" data-toggle="modal" class="widget-icon" title="Delete Site"><span class="icon-trash"></span></a>
                                                    </td>
                                                </tr>
                                                <div class="modal" id="edit_site<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">EDIT SITE</h4>
                                                                </div>
                                                                <div class="modal-body clearfix">
                                                                    <div class="controls">
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Name:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="site_name" class="form-control" value="<?= $site['name'] ?>" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Short Code:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="short_code" class="form-control" value="<?= $site['short_code'] ?>" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Country:</div>
                                                                            <div class="col-md-10">
                                                                                <select class="form-control" name="country_id">
                                                                                    <option value="<?= $country[0]['id'] ?>"><?= $country[0]['name'] ?></option>
                                                                                    <?php foreach ($override->getData('country') as $country) { ?>
                                                                                        <option value="<?= $country['id'] ?>"><?= $country['name'] ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="pull-right col-md-3">
                                                                        <input type="hidden" name="id" value="<?= $site['id'] ?>">
                                                                        <input type="submit" name="edit_site" value="Submit" class="btn btn-success btn-clean">
                                                                    </div>
                                                                    <div class="pull-right col-md-2">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal modal-danger" id="delete_site<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS SITE</h4>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="col-md-2 pull-right">
                                                                        <input type="hidden" name="id" value="<?= $site['id'] ?>">
                                                                        <input type="submit" name="delete_site" value="DELETE" class="btn btn-default btn-clean">
                                                                    </div>
                                                                    <div class="col-md-2 pull-right">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php $x++;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="block">
                                <div class="header">
                                    <h2>END OF STUDY REASON</h2>
                                </div>
                                <div class="content">
                                    <table id="allVisit" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>REASON</th>
                                                <th>MANAGE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $x = 1;
                                            foreach ($override->getData('end_study_reason') as $site) { ?>
                                                <tr>
                                                    <td><?= $x ?></td>
                                                    <td><?= $site['reason'] ?></td>
                                                    <td>
                                                        <a href="#edit_end_reason<?= $x ?>" data-toggle="modal" class="widget-icon" title="Edit Site Information"><span class="icon-pencil"></span></a>
                                                        <a href="#delete_end_reason<?= $x ?>" data-toggle="modal" class="widget-icon" title="Delete Site"><span class="icon-trash"></span></a>
                                                    </td>
                                                </tr>
                                                <div class="modal" id="edit_end_reason<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">EDIT SITE</h4>
                                                                </div>
                                                                <div class="modal-body clearfix">
                                                                    <div class="controls">
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Reason:</div>
                                                                            <div class="col-md-10">
                                                                                <textarea name="reason" rows="4" class="form-control"><?= $site['reason'] ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="pull-right col-md-3">
                                                                        <input type="hidden" name="id" value="<?= $site['id'] ?>">
                                                                        <input type="submit" name="edit_end_study" value="Submit" class="btn btn-success btn-clean">
                                                                    </div>
                                                                    <div class="pull-right col-md-2">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal modal-danger" id="delete_end_reason<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS REASON</h4>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="col-md-2 pull-right">
                                                                        <input type="hidden" name="id" value="<?= $site['id'] ?>">
                                                                        <input type="submit" name="delete_end_study" value="DELETE" class="btn btn-default btn-clean">
                                                                    </div>
                                                                    <div class="col-md-2 pull-right">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php $x++;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="block">
                                <div class="header">
                                    <h2>PARTICIPANT GROUP</h2>
                                </div>
                                <div class="content">
                                    <table id="allVisit" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>NAME</th>
                                                <th>MANAGE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $x = 1;
                                            foreach ($override->getData('patient_group') as $group) { ?>
                                                <tr>
                                                    <td><?= $x ?></td>
                                                    <td><?= $group['name'] ?></td>
                                                    <td>
                                                        <a href="#edit_patient_group<?= $x ?>" data-toggle="modal" class="widget-icon" title="Edit Site Information"><span class="icon-pencil"></span></a>
                                                        <!--                                                <a href="#delete_patient_group--><? //=$x
                                                                                                                                                ?>
                                                        <!--" data-toggle="modal" class="widget-icon" title="Delete Site"><span class="icon-trash"></span></a>-->
                                                    </td>
                                                </tr>
                                                <div class="modal" id="edit_patient_group<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">EDIT PATIENT GROUP</h4>
                                                                </div>
                                                                <div class="modal-body clearfix">
                                                                    <div class="controls">
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Name:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="group_name" class="form-control" value="<?= $group['name'] ?>" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="pull-right col-md-3">
                                                                        <input type="hidden" name="id" value="<?= $group['id'] ?>">
                                                                        <input type="submit" name="edit_pt_group" value="Submit" class="btn btn-success btn-clean">
                                                                    </div>
                                                                    <div class="pull-right col-md-2">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal modal-danger" id="delete_patient_group<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS GROUP</h4>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="col-md-2 pull-right">
                                                                        <input type="hidden" name="id" value="<?= $site['id'] ?>">
                                                                        <input type="submit" name="delete_pt_group" value="DELETE" class="btn btn-default btn-clean">
                                                                    </div>
                                                                    <div class="col-md-2 pull-right">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php $x++;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-offset-2 col-md-8">
                            <div class="block">
                                <div class="header">
                                    <h2>STUDY</h2>
                                </div>
                                <div class="content">
                                    <table id="allVisit" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>NAME</th>
                                                <th>CODE</th>
                                                <th>DURATION</th>
                                                <th>SAMPLE SIZE</th>
                                                <th>START</th>
                                                <th>END</th>
                                                <th>DETAILS</th>
                                                <th>MANAGE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $x = 1;
                                            foreach ($override->getData('study') as $group) { ?>
                                                <tr>
                                                    <td><?= $x ?></td>
                                                    <td><?= $group['name'] ?></td>
                                                    <td><?= $group['study_code'] ?></td>
                                                    <td><?= $group['duration'] ?></td>
                                                    <td><?= $group['sample_size'] ?></td>
                                                    <td><?= $group['start_date'] ?></td>
                                                    <td><?= $group['end_date'] ?></td>
                                                    <td><?= $group['details'] ?></td>
                                                    <td>
                                                        <a href="#edit_study<?= $x ?>" data-toggle="modal" class="widget-icon" title="Edit Site Information"><span class="icon-pencil"></span></a>
                                                        <!--                                                <a href="#delete_patient_group--><? //=$x
                                                                                                                                                ?>
                                                        <!--" data-toggle="modal" class="widget-icon" title="Delete Site"><span class="icon-trash"></span></a>-->
                                                    </td>
                                                </tr>
                                                <div class="modal" id="edit_study<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">EDIT STUDY</h4>
                                                                </div>
                                                                <div class="modal-body clearfix">
                                                                    <div class="controls">
                                                                        <div class="form-row">
                                                                            <div class="col-md-3">STUDY NAME:</div>
                                                                            <div class="col-md-8">
                                                                                <input type="text" name="name" class="form-control" value="<?= $group['name'] ?>" required="" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-3">STUDY CODE:</div>
                                                                            <div class="col-md-8">
                                                                                <input type="text" name="study_code" class="form-control" value="<?= $group['study_code'] ?>" required="" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-3">STUDY DURATION:</div>
                                                                            <div class="col-md-8">
                                                                                <input type="number" name="duration" class="form-control" value="<?= $group['duration'] ?>" required="" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-3">SAMPLE SIZE:</div>
                                                                            <div class="col-md-8">
                                                                                <input type="number" name="sample_size" class="form-control" value="<?= $group['sample_size'] ?>" required="" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-3">START DATE:</div>
                                                                            <div class="col-md-8">
                                                                                <div class="input-group">
                                                                                    <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                                                                    <input type="text" name="start_date" class="datepicker form-control" value="<?= $group['start_date'] ?>" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-3">END DATE:</div>
                                                                            <div class="col-md-8">
                                                                                <div class="input-group">
                                                                                    <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                                                                    <input type="text" name="end_date" class="datepicker form-control" value="<?= $group['end_date'] ?>" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-3">Details:</div>
                                                                            <div class="col-md-8">
                                                                                <textarea name="details" class="form-control" rows="4"><?= $group['details'] ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="pull-right col-md-3">
                                                                        <input type="hidden" name="id" class="form-control" value="<?= $group['id'] ?>" />
                                                                        <input type="submit" name="edit_study" value="ADD" class="btn btn-success btn-clean">
                                                                    </div>
                                                                    <div class="pull-right col-md-2">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal modal-danger" id="delete_delete<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS GROUP</h4>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="col-md-2 pull-right">
                                                                        <input type="hidden" name="id" value="<?= $site['id'] ?>">
                                                                        <input type="submit" name="delete_pt_group" value="DELETE" class="btn btn-default btn-clean">
                                                                    </div>
                                                                    <div class="col-md-2 pull-right">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php $x++;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="block">
                                <div class="header">
                                    <h2>IMAGES</h2>
                                </div>
                                <div class="content">
                                    <table id="allVisit" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>CATEGORY</th>
                                                <th>LOCATION</th>
                                                <th>MANAGE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $x = 1;
                                            foreach ($override->getData('images') as $group) { ?>
                                                <tr>
                                                    <td><?= $x ?></td>
                                                    <td><?php if ($group['cat'] == 1) {
                                                            echo 'Favicon';
                                                        } elseif ($group['cat'] == 2) {
                                                            echo 'Logo';
                                                        } elseif ($group['cat'] == 3) {
                                                            echo 'Image';
                                                        } ?></td>
                                                    <td><?= $group['location'] ?></td>
                                                    <td>
                                                        <a href="#edit_image<?= $x ?>" data-toggle="modal" class="widget-icon" title="Edit Site Information"><span class="icon-pencil"></span></a>
                                                        <!--                                                <a href="#delete_patient_group--><? //=$x
                                                                                                                                                ?>
                                                        <!--" data-toggle="modal" class="widget-icon" title="Delete Site"><span class="icon-trash"></span></a>-->
                                                    </td>
                                                </tr>
                                                <div class="modal" id="edit_image<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                                        <input type="hidden" name="id" value="<?= $group['id'] ?>">
                                                                        <input type="submit" name="edit_image" value="ADD" class="btn btn-success btn-clean">
                                                                    </div>
                                                                    <div class="pull-right col-md-2">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal modal-danger" id="delete_image<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS GROUP</h4>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="col-md-2 pull-right">
                                                                        <input type="hidden" name="id" value="<?= $site['id'] ?>">
                                                                        <input type="submit" name="delete_pt_group" value="DELETE" class="btn btn-default btn-clean">
                                                                    </div>
                                                                    <div class="col-md-2 pull-right">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php $x++;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } elseif ($_GET['id'] == 10) { ?>
                    <div class="block">
                        <div class="header">
                            <h2>END OF STUDY CLIENTS</h2>
                        </div>
                        <div class="content">

                            <table id="allVisit" cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="10%">STUDY ID</th>

                                        <?php
                                        if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
                                        ?>
                                            <th width="10%">PHONE</th>

                                        <?php } ?>
                                        <th width="10%">STUDY</th>
                                        <th width="10%">GROUP</th>
                                        <th width="5%">STATUS</th>
                                        <th width="20%">Reason</th>
                                        <th width="50%">DETAILS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    if (isset($_GET['study'])) {
                                        $study =  $_GET['study'];
                                    }




                                    $x = 1;
                                    foreach ($override->getDataOrderByA('clients', 'status', 0, 'study_id', 'project_id', $study) as $data) {
                                        $lastVisit = $override->getlastRow('visit', 'client_id', $data['id'], 'id');
                                        if ($lastVisit) {
                                            $lVisit = $lastVisit[0]['visit_date'];
                                        } else {
                                            $lVisit = '';
                                        } ?>
                                        <tr>
                                            <td><?= $x ?></td>
                                            <td><?= $data['study_id'] ?></td>

                                            <?php
                                            if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
                                            ?>
                                                <td><?= $data['phone_number'] ?></td>

                                            <?php } ?>
                                            <td><?= $override->get('study', 'id', $data['project_id'])[0]['study_code'] ?></td>
                                            <td><?= $override->get('patient_group', 'id', $data['pt_group'])[0]['name'] ?></td>
                                            <td>
                                                <div class="btn-group btn-group-xs"><button class="btn btn-danger">End Study</button></div>
                                            </td>
                                            <td>( <?= $data['reason'] ?> )</td>
                                            <td><?= $data['details'] ?></td>
                                        </tr>

                                    <?php $x++;
                                    } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                <?php } elseif ($_GET['id'] == 11) { ?>
                    <table id="allVisit" cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="3%">Study ID</th>
                                <?php

                                if (isset($_GET['study'])) {
                                    $study =  $_GET['study'];
                                }

                                $x = 1;
                                foreach ($override->getDataOrderByA('visit', 'client_id', $_GET['pid'], 'visit_date', 'project_id', $study) as $data) { ?>
                                    <th width="3%"><?= $data['visit_date'] ?></th>
                                <?php $x++;
                                } ?>
                            </tr>

                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight: bold"><?= $override->get('clients', 'id', $_GET['pid'])[0]['study_id'] ?></td>
                                <?php $x = 1;
                                foreach ($override->getDataOrderByA('visit', 'client_id', $_GET['pid'], 'visit_date', 'project_id', $study) as $data) { ?>
                                    <td>
                                        <div class="btn-group btn-group-xs"><?php if ($data['status'] == 1) { ?>&nbsp;
                                            <button class="btn btn-success"><span class="icon-ok-sign"></span> Done</button>
                                        <?php } elseif ($data['status'] == 2) { ?>
                                            <button class="btn btn-danger"><span class="icon-remove-sign"></span> Missed</span></button>
                                        <?php } elseif ($data['status'] == 0) { ?>
                                            <button class="btn btn-info"><span class="icon-dashboard"></span> <?php if ($d['schedule'] == 'Scheduled') {
                                                                                                                    echo 'Scheduled ' . $data['visit_code'] . ' ' . $data['visit_type'];
                                                                                                                } else {
                                                                                                                    echo 'UnScheduled ' . $data['visit_code'] . ' ' . $data['visit_type'];
                                                                                                                } ?></button>
                                        <?php } ?>
                                    </td>
                                <?php $x++;
                                } ?>
                            </tr>
                        </tbody>
                    </table>
                <?php } elseif ($_GET['id'] == 12) {

                    // $override->dateRange1('visit', 'visit_date', $_GET['from'], $_GET['to']);
                    if ($_GET['project_id'] == 'ALL') {
                        $override->dateRange1('visit', 'visit_date', $_GET['from'], $_GET['to']);
                    } else {
                        $project_id = $_GET['project_id'];
                        $override->dateRange('visit', 'visit_date', $_GET['from'], $_GET['to'], 'project_id', $project_id);
                    }


                    $y = 0;
                    $list = array();
                    while ($y <= $user->dateDiff($_GET['to'], $_GET['from'])) {
                        $list[$y] = date('Y-m-d', strtotime($_GET['from'] . ' + ' . $y . ' days'));
                        $y++;
                    } ?>
                    <form method="post">
                        <input type="submit" name="download" value="Download Data" class="btn btn-info">
                    </form>
                    <table id="allVisit" cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="2%">CLIENT ID</th>
                                <th width="2%">STUDY NAME</th>
                                <th width="2%">GROUP NAME</th>
                                <?php $x = 1;
                                foreach ($list as $data) { ?>
                                    <th width="2%"><?= $data ?></th>
                                <?php $x++;
                                } ?>
                            </tr>

                        </thead>
                        <tbody>


                            <?php
                            if ($_GET['project_id'] == 'ALL') {
                                $override->dateRange1('visit', 'visit_date', $_GET['from'], $_GET['to']);



                                foreach ($override->dateRangeD1('visit', 'client_id', 'visit_date', $_GET['from'], $_GET['to']) as $dt) {
                                    if ($dt['status'] != 4) {
                                        $client = $override->get('clients', 'id', $dt['client_id'])[0];
                            ?>

                                        <tr>
                                            <td><?= $client['study_id'] ?>
                                            <td><?= $override->get('study', 'id', $client['project_id'])[0]['study_code']; ?></td>
                                            <td><?= $override->get('patient_group', 'id', $client['pt_group'])[0]['name'] ?>
                                                <?php if ($client['status'] == 0) { ?>
                                                    <div class="btn-group btn-group-xs">
                                                        <button class="btn btn-danger"><span class="icon-ok-sign"></span> End Study </button>
                                                    </div>
                                            <?php }
                                            } ?>
                                            </td>
                                            <?php $x = 1;
                                            foreach ($list as $data) {
                                                $d = $override->getNews('visit', 'client_id', $dt['client_id'], 'visit_date', $data)[0];
                                                //                                echo ' => ';print_r($dt['client_id']);print_r($d['status']);echo ' : ';print_r($d['visit_date']);echo ' , '
                                                //                                print_r($data['client_id']);echo ' : ';print_r($data['visit_date']);echo ' , '
                                            ?>
                                                <td>
                                                    <div class="btn-group btn-group-xs"><?php if ($d) {
                                                                                            if ($d['status'] == 1) { ?>&nbsp;
                                                        <button class="btn btn-success"><span class="icon-ok-sign"></span> Done <?= $d['visit_code'] . ' ' . $d['visit_type'] ?></button>
                                                    <?php } elseif ($d['status'] == 2) { ?>
                                                        <button class="btn btn-danger"><span class="icon-remove-sign"></span> Missed <?= $d['visit_code'] . ' ' . $d['visit_type'] ?></span></button>
                                                    <?php } elseif ($d['status'] == 0 || $d['status'] == 3) { ?>
                                                        <button class="btn btn-info"><span class="icon-dashboard"></span> <?php if ($d['schedule'] == 'Scheduled') {
                                                                                                                                echo 'Scheduled ' . $d['visit_code'] . ' ' . $d['visit_type'];
                                                                                                                            } else {
                                                                                                                                echo 'UnScheduled ' . $d['visit_code'] . ' ' . $d['visit_type'];
                                                                                                                            } ?></button>
                                                    <?php }
                                                                                        } else { ?>
                                                    <?php echo ' '; ?>
                                                    <!-- NO VISIT -->
                                                <?php } ?>
                                                    </div>
                                                </td>
                                            <?php $x++;
                                            } ?>
                                        </tr>

                                    <?php } ?>
                                    <?php


                                } else {
                                    $project_id = $_GET['project_id'];
                                    $override->dateRange('visit', 'visit_date', $_GET['from'], $_GET['to'], 'project_id', $project_id);

                                    foreach ($override->dateRangeD('visit', 'client_id', 'visit_date', $_GET['from'], $_GET['to'], 'project_id', $project_id) as $dt) {
                                        if ($dt['status'] != 4) {
                                            $client = $override->get('clients', 'id', $dt['client_id'])[0];

                                    ?>


                                            <tr>
                                                <td><?= $client['study_id'] ?>
                                                <td><?= $override->get('study', 'id', $client['project_id'])[0]['study_code']; ?></td>
                                                <td><?= $override->get('patient_group', 'id', $client['pt_group'])[0]['name'] ?>
                                                    <?php if ($client['status'] == 0) { ?>
                                                        <div class="btn-group btn-group-xs">
                                                            <button class="btn btn-danger"><span class="icon-ok-sign"></span> End Study </button>
                                                        </div>
                                                <?php }
                                                } ?>
                                                </td>
                                                <?php $x = 1;
                                                foreach ($list as $data) {
                                                    $d = $override->getNews('visit', 'client_id', $dt['client_id'], 'visit_date', $data)[0];
                                                    //                                echo ' => ';print_r($dt['client_id']);print_r($d['status']);echo ' : ';print_r($d['visit_date']);echo ' , '
                                                    //                                print_r($data['client_id']);echo ' : ';print_r($data['visit_date']);echo ' , '
                                                ?>
                                                    <td>
                                                        <div class="btn-group btn-group-xs"><?php if ($d) {
                                                                                                if ($d['status'] == 1) { ?>&nbsp;
                                                            <button class="btn btn-success"><span class="icon-ok-sign"></span> Done <?= $d['visit_code'] . ' ' . $d['visit_type'] ?></button>
                                                        <?php } elseif ($d['status'] == 2) { ?>
                                                            <button class="btn btn-danger"><span class="icon-remove-sign"></span> Missed <?= $d['visit_code'] . ' ' . $d['visit_type'] ?></span></button>
                                                        <?php } elseif ($d['status'] == 0 || $d['status'] == 3) { ?>
                                                            <button class="btn btn-info"><span class="icon-dashboard"></span> Scheduled <?= $d['visit_code'] . ' ' . $d['visit_type'] ?></button>
                                                        <?php }
                                                                                            } else { ?>
                                                        <?php echo ' '; ?>
                                                        <!-- NO VISIT  -->
                                                    <?php } ?>
                                                        </div>
                                                    </td>
                                                <?php $x++;
                                                } ?>
                                            </tr>

                                    <?php }
                                } ?>


                        </tbody>
                    </table>
                <?php } elseif ($_GET['id'] == 13) { ?>
                    <div class="block">
                        <div class="header">
                            <h2>TODAY PENDING VISITS</h2>
                        </div>
                        <div class="content">
                            <table id="example3" cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                                <thead>
                                    <tr>
                                        <th width="5%">STUDY ID</th>
                                        <th width="5%">STUDY NAME</th>
                                        <th width="3%">GROUP NAME</th>
                                        <th width="3%">VISIT CODE</th>
                                        <th width="3%">SCHEDLUE TYPE</th>
                                        <th width="3%">VISIT TYPE</th>
                                        <th width="5%">VISIT STATUS<i></i>&nbsp;<span class="label label-danger"><?= $override->countDataNot('visit', 'status', 0, 'status', 0) ?></span></th>
                                        <th width="5%">CLINICIAN STATUS<i></i>&nbsp;<span class="label label-danger"><?= $override->countDataNot('visit', 'status', 0, 'sn_cl_status', 0) ?></span></th>
                                        <th width="5%">DATACLERK STATUS<i></i>&nbsp;<span class="label label-danger"><?= $override->countDataNot('visit', 'status', 0, 'dc_status', 0) ?></span></th>
                                        <th width="5%">DATAMANAGER STATUS<i></i>&nbsp;<span class="label label-danger"><?= $override->countDataNot('visit', 'status', 0, 'dm_status', 0) ?></span></th>

                                        <?php
                                        if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
                                        ?>

                                            <th width="3%">PHONE NUMBER</th>
                                            <th width="3%">ACTION</th>
                                            <th width="3%">RE-SCHEDULE</th>

                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($user->data()->position == 1) {
                                        $a_status = 'dm_status';
                                    } elseif ($user->data()->position == 6) {
                                        $a_status = 'status';
                                    } elseif ($user->data()->position == 12) {
                                        $a_status = 'dc_status';
                                    } elseif ($user->data()->position == 5) {
                                        $a_status = 'sn_cl_status';
                                    }

                                    if (isset($_GET['study'])) {

                                        $study = $_GET['study'];
                                    }

                                    $x = 1;
                                    foreach ($override->getDataNot2('visit', 'status', 0, $a_status, 0, 'project_id', $study) as $data) {
                                        $client = $override->get('clients', 'id', $data['client_id'])[0];
                                        $lastVisit = $override->getlastRow('visit', 'client_id', $data['client_id'], 'visit_date');
                                        if ($client['status'] == 1) { ?>
                                            <tr>
                                                <td><?= $client['study_id']; ?></td>
                                                <td><?= $override->get('study', 'id', $client['project_id'])[0]['study_code']; ?></td>
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


                                                <td><?= $data['visit_type'] ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-xs">
                                                        <?php if ($data['status'] == 3) { ?>&nbsp;
                                                        <button class="btn btn-warning">Pending</button>
                                                    <?php } elseif ($data['status'] == 1) { ?>
                                                        <button class="btn btn-success">Completed</button><button class="btn btn-info"><?= $data['initial1'] ?></button>
                                                    <?php } elseif ($data['status'] == 2) { ?>
                                                        <button class="btn btn-danger">Visit Missed</button><button class="btn btn-info"><?= $data['initial1'] ?></button>
                                                    <?php } ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-xs">
                                                        <?php if ($data['sn_cl_status'] == 0) { ?>&nbsp;
                                                        <button class="btn btn-warning">Pending</button>
                                                    <?php } elseif ($data['sn_cl_status'] == 1) { ?>
                                                        <button class="btn btn-success">Reviewed</button><button class="btn btn-info"><?= $data['initial2'] ?></button>
                                                    <?php } elseif ($data['sn_cl_status'] == 2) { ?>
                                                        <button class="btn btn-danger">Missed</button><button class="btn btn-info"><?= $data['initial2'] ?></button>
                                                    <?php } ?>
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

                                                <?php
                                                if ($user->data()->position == 1 || $user->data()->position == 5 || $user->data()->position == 6 || $user->data()->position == 12) {
                                                ?>

                                                    <td><?= $client['phone_number'] ?></td>
                                                    <td>
                                                        <a href="#appnt<?= $x ?>" data-toggle="modal" class="widget-icon" title="Add Visit"><span class="icon-share"></span></a>
                                                    </td>

                                                    <td>

                                                        <div><a href="#re_schedule_pending<?= $x ?>" data-toggle="modal" class="widget-icon" title="Re - Schedule Visit"><span class="icon-edit"></span></a></div>
                                                    </td>

                                                <?php } ?>



                                                <div class="modal" id="re_schedule_pending<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">Re - Schedule VISIT</h4>
                                                                </div>
                                                                <div class="modal-body clearfix">
                                                                    <div class="controls">
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Study Name :</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="study_name" class="datepicker form-control" value="<?= $override->get('study', 'id', $client['project_id'])[0]['study_code']; ?>" disabled />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Client ID:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="client_id" class="form-control" value="<?= $client['study_id'] ?>" disabled />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">VISIT CODE:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="visit_code" class="form-control" value="<?= $data['visit_code'] ?>" disabled />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Study Group:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="initials" class="form-control" value="<?= $override->get('patient_group', 'id', $client['pt_group'])[0]['name'] ?>" disabled />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Visit Date:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="reschedule_date" class="datepicker form-control" value="<?= $data['visit_date'] ?>" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Details:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="details" class="form-control" value="<?= 'Changed from ' .  ' ' . $data['visit_date'] . ' to ' . ' ' . $data['visit_date'] ?>" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">Reason:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="reason" class="form-control" value="<?= $data['reason'] ?>" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="pull-right col-md-3">
                                                                        <input type="hidden" name="reschedule_id" class="form-control" value="<?= $data['id'] ?>" required="" />
                                                                        <input type="submit" name="reschedule_pending_visit" value="SUBMIT" class="btn btn-success btn-clean">
                                                                    </div>
                                                                    <div class="pull-right col-md-2">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>



                                                <div class="modal" id="appnt<?= $x ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">APPOINTMENT</h4>
                                                                </div>
                                                                <div class="modal-body clearfix">
                                                                    <div class="controls">
                                                                        <div class="form-row">
                                                                            <div class="col-md-2">VISIT CODE:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="hidden" name="visit_code" value="<?= $client['visit_code'] + 1 ?>">
                                                                                <input type="text" name="visit_code" class="form-control" value="<?= $data['visit_code'] . '(' . $data['visit_type'] . ')' ?>" disabled />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row" id="st">
                                                                            <div class="col-md-2">Status</div>
                                                                            <div class="col-md-10">
                                                                                <select class="form-control" id="site" name="visit_status" required>
                                                                                    <option value="">Select Status</option>
                                                                                    <option value="1">Complete</option>
                                                                                    <option value="2">Missing</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="pull-right col-md-3">
                                                                        <input type="hidden" name="id" value="<?= $lastVisit[0]['id'] ?>">
                                                                        <input type="hidden" name="v_id" value="<?= $data['id'] ?>">
                                                                        <input type="hidden" name="s_id" value="<?= $user->data()->initial ?>">
                                                                        <input type="hidden" name="client_id" value="<?= $client['id'] ?>">
                                                                        <input type="hidden" name="sn2" value="<?= $data['status'] ?>">
                                                                        <input type="hidden" name="sn" value="<?= $data['sn_cl_status'] ?>">
                                                                        <input type="hidden" name="sn3" value="<?= $data['dc_status'] ?>">
                                                                        <input type="submit" name="appointment" value="Submit" class="btn btn-success btn-clean">
                                                                    </div>
                                                                    <div class="pull-right col-md-2">
                                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </tr>
                                    <?php }
                                        $x++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } elseif ($_GET['id'] == 14) { ?>
                    <div class="block">
                        <div class="header">
                            <h2>LIST OF PARTICIPANTS</h2>
                        </div>
                        <div class="content">
                            <table id="allVisit" cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th width="15%">FIRST NAME</th>
                                        <th width="10%">MIDDLE NAME </th>
                                        <th width="10%">LAST NAME</th>
                                        <th width="10%">INITIAL</th>
                                        <th width="10%">SENSITIZATION</th>
                                        <th width="10%">CATEGORY</th>
                                        <th width="10%">STUDY</th>
                                        <th width="10%">GENDER</th>
                                        <th width="10%">DATE BIRTH</th>
                                        <th width="10%">PHONE</th>
                                        <th width="10%">NEXT CONTACT</th>
                                        <th width="10%">STATUS</th>
                                        <th width="25%">MANAGE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $y = 0;
                                    $x = 1;
                                    if ($user->data()->access_level == 1 || $user->data()->access_level == 2 || $user->data()->access_level == 3) {
                                        $staffs = $override->get1('details');
                                    } elseif ($user->data()->access_level == 4) {
                                        // $staffs = $override->getNews('staff', 'status', 1, 'c_id', $user->data()->c_id);
                                        $staffs = $override->getNews1('details');
                                    }
                                    foreach ($staffs as $staff) {
                                        if ($user->data()->access_level != 1 || $user->data()->id != $staff['id']) {
                                            if ($user->data()->access_level == 1) {
                                                $power = 1;
                                            } else {
                                                $power = 0;
                                            }
                                            $site = $override->get('site', 'id', $staff['s_id']);
                                            $country = $override->get('country', 'id', $staff['c_id']);
                                            $position = $override->get('position', 'id', $staff['position'])[0] ?>
                                            <tr>
                                                <td><?= $x ?></td>
                                                <td><?= $staff['fname']; ?></td>
                                                <td><?= $staff['mname']; ?></td>
                                                <td><?= $staff['lname']; ?></td>
                                                <td><?= $staff['initial'] ?></td>
                                                <td><?= $staff['sensitization_no'] ?></td>
                                                <td><?= $staff['client_category'] ?></td>
                                                <td><?= $staff['project_name'] ?></td>
                                                <td><?= $staff['gender'] ?></td>
                                                <td><?= $staff['dob'] ?></td>
                                                <td><?= $staff['phone1'] ?></td>
                                                <td><?= $staff['willing_contact'] ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-xs">
                                                        <?php if ($staff['status'] == 'Enrolled') { ?><button class="btn btn-success">Enrolled</button> <?php } elseif ($staff['status'] == 'Not Enrolled') { ?><button class="btn btn-warning">Not Enrolled</button><?php } elseif ($staff['status'] == 'On Screening') { ?><button class="btn btn-info">On Screening</button><?php } else { ?><button class="btn btn-warning">Not Enrolled</button><?php } ?>
                                                    </div>
                                                </td>
                                                </td>
                                                <td>
                                                    <?php if ($staff['access_level'] != 2 || $power == 1) { ?>
                                                        <a href="#edit_participant<?= $y ?>" data-toggle="modal" class="widget-icon" title="Edit Participant Information"><span class="icon-pencil"></span></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>



                                            <!-- <div class="modal fade" id="modalRegisterForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header text-center">
                                                            <h4 class="modal-title w-100 font-weight-bold">Sign up</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body mx-3">
                                                            <div class="md-form mb-5">
                                                                <i class="fas fa-user prefix grey-text"></i>
                                                                <input type="text" id="orangeForm-name" class="form-control validate">
                                                                <label data-error="wrong" data-success="right" for="orangeForm-name">Your name</label>
                                                            </div>
                                                            <div class="md-form mb-5">
                                                                <i class="fas fa-envelope prefix grey-text"></i>
                                                                <input type="email" id="orangeForm-email" class="form-control validate">
                                                                <label data-error="wrong" data-success="right" for="orangeForm-email">Your email</label>
                                                            </div>

                                                            <div class="md-form mb-4">
                                                                <i class="fas fa-lock prefix grey-text"></i>
                                                                <input type="password" id="orangeForm-pass" class="form-control validate">
                                                                <label data-error="wrong" data-success="right" for="orangeForm-pass">Your password</label>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer d-flex justify-content-center">
                                                            <button class="btn btn-deep-orange">Sign up</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <a href="" class="btn btn-default btn-rounded mb-4" data-toggle="modal" data-target="#modalRegisterForm">Launch
                                                    Modal Register Form</a>
                                            </div> -->


                                            <div class="modal fade" id="edit_participant<?= $y ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="post">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                <h4 class="modal-title">EDIT PARTICIPANT</h4>
                                                            </div>

                                                            <div class="modal-body clearfix">
                                                                <!-- <div class="controls"> -->

                                                                <div class="row-md-12">
                                                                    <div class="col-md-4">

                                                                        <div class="form-row" id="st">
                                                                            <div>STUDY NAME:</div>
                                                                            <div class="col-md-10">
                                                                                <select class="form-control" id="project_id" name="project_id" required>
                                                                                    <option value="<?= $staff['project_name']; ?>"><?= $staff['project_name']; ?></option>
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
                                                                                    <option value="<?= $staff['sensitization_one']; ?>"><?= $staff['sensitization_one']; ?></option>
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
                                                                                    <option value="<?= $staff['sensitization_two']; ?>"><?= $staff['sensitization_two']; ?></option>
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
                                                                                <input type="text" name="initial" class="form-control" value="<?= $staff['initial']; ?>" minlength="3" maxlength="3" required="" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-row">
                                                                            <div>SENSITIZATION NUMBER</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="sensitization_no" class="form-control" pattern="\d*" value="<?= $staff['sensitization_no']; ?>" minlength="3" maxlength="3" required="" />
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-row" id="st">
                                                                            <div>Respondent is</div>
                                                                            <div class="col-md-10">
                                                                                <select class="form-control" id="client_category" name="client_category" required>
                                                                                    <option value="<?= $staff['client_category']; ?>"><?= $staff['client_category']; ?></option>
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
                                                                                <input type="text" name="fname" class="form-control" value="<?= $staff['fname'] ?>" required="" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-row">
                                                                            <div>Midle Name:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="mname" class="form-control" value="<?= $staff['mname']; ?>" required="" />
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-row">
                                                                            <div>Last name:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="lname" class="form-control" value="<?= $staff['lname']; ?>" required="" />
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
                                                                                <input type="date" class="form-control fas fa-calendar input-prefix" value="<?= $staff['dob']; ?>" name="dob" id="dob" required="" />
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-row">
                                                                            <div>Child attending school?</div>
                                                                            <div class="col-md-10">
                                                                                <select id="attend_school" name="attend_school" class="form-control" required>
                                                                                    <option value="<?= $staff['attend_school']; ?>"><?= $staff['attend_school']; ?></option>
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
                                                                                    <option value="<?= $staff['gender']; ?>"><?= $staff['gender']; ?></option>
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
                                                                            <div>Phone:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="phone1" class="form-control" value="<?= $staff['phone1']; ?>" pattern="\d*" minlength="10" maxlength="10" required="" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-row">
                                                                            <div>Phone2:</div>
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="phone2" class="form-control" value="<?= $staff['phone2']; ?>" pattern="\d*" minlength="10" maxlength="10" />
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
                                                                                    <option value="<?= $staff['region']; ?>"><?= $staff['region']; ?></option>
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
                                                                                    <option value="<?= $staff['district']; ?>"><?= $staff['district']; ?></option>
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
                                                                                    <option value="<?= $staff['ward']; ?>"><?= $staff['ward']; ?></option>
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
                                                                                    <option value="<?= $staff['village']; ?>"><?= $staff['village']; ?></option>
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
                                                                                    <option value="<?= $staff['hamlet']; ?>"><?= $staff['hamlet']; ?></option>
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
                                                                                    <option value="<?= $staff['duration']; ?>"><?= $staff['duration']; ?></option>
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
                                                                                    <option value="<?= $staff['willing_contact']; ?>"><?= $staff['willing_contact']; ?></option>
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
                                                                        <textarea name="location" id="location" value="<?= $staff['location']; ?>" cols="100%" rows="4" required></textarea>
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
                                                                                    <option value="<?= $staff['status']; ?>"><?= $staff['status']; ?></option>
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
                                                                                    <option value="<?= $staff['reason']; ?>"><?= $staff['reason']; ?></option>
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
                                                                            <textarea name="other_reason" id="other_reason" value="<?= $staff['other_reason']; ?>" cols="40%" rows="2"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- INITIALS  -->

                                                            </div>

                                                            <div class="modal-footer">
                                                                <div class="pull-right col-md-3">
                                                                    <input type="hidden" name="id" value="<?= $staff['id'] ?>">
                                                                    <input type="submit" name="edit_participant" value="Submit" class="btn btn-success btn-clean">
                                                                </div>
                                                                <div class="pull-right col-md-2">
                                                                    <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php $x++;
                                        }
                                        $y++;
                                    } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>



    <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> -->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        // $(document).ready(function() {
        //     $('#example').DataTable({
        //         paging: false,
        //         scrollY: 10
        //     });

        // });

        // $('#allVisit').DataTable({
        //     paging: false,
        //     scrollY: 10,
        //     searchBuilder: {

        //     },
        //     dom: 'Qfrtip'
        // })



        $(document).ready(function() {

            $('#allVisit').DataTable({

                // columnDefs: [{
                //     // "targets": -1,
                //     // "data": null,
                //     // "orderable": false,
                //     "defaultContent": ['<input type="text" class="form-control datePicker" placeholder="Date" />']
                // }],

                // "columnDefs": [{
                //     targets: 4,
                //     render: $.fn.dataTable.render.moment('M-DD-YYYY,THH:mm', 'M/DD/YYYY')
                // }],


                dom: 'lBfrtip',
                buttons: [{

                        extend: 'excelHtml5',
                        title: 'VISITS',
                        className: 'btn-primary',
                        // displayFormat: 'dddd D MMMM YYYY',
                        // wireFormat: 'YYYY-MM-DD',
                        // columnDefs: [{
                        // targets: [6],
                        // render: $.fn.dataTable.render.moment('DD/MM/YYYY')
                        // }],
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'VISITS',
                        className: 'btn-primary',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'

                    },
                    {
                        extend: 'csvHtml5',
                        title: 'VISITS',
                        className: 'btn-primary'
                    },
                    {
                        extend: 'copyHtml5',
                        title: 'VISITS',
                        className: 'btn-primary'
                    },
                    //     {
                    //         extend: 'print',
                    //         // name: 'printButton'
                    //         title: 'VISITS'
                    //     }
                ],
                // paging: true,
                // scrollY: 10
                // select: true,
                // dom: 'Bfrtip',
                // buttons: [
                //     'copy', 'excel', 'pdf'
                // ]
                "pageLength": 100




            });

            // $(".dataTables_empty").text("There is No Any Visit Today.");



            $('#example2').DataTable({

                "language": {
                    "emptyTable": "<div class='display-1 font-weight-bold'><h1 style='color: tomato;visibility: visible'>No Any Visit Today</h1><div><span></span></div></div>"
                },
                // columns: columnDefs,

                dom: 'lBfrtip',
                buttons: [{

                        extend: 'excelHtml5',
                        title: 'VISITS',
                        className: 'btn-primary'
                    },

                    {
                        extend: 'pdfHtml5',
                        title: 'VISITS',
                        className: 'btn-primary',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'

                    },


                    {
                        extend: 'csvHtml5',
                        title: 'VISITS',
                        className: 'btn-primary'
                    },
                    {
                        extend: 'copyHtml5',
                        title: 'VISITS',
                        className: 'btn-primary'
                    },
                    //     {
                    //         extend: 'print',
                    //         // name: 'printButton'
                    //         title: 'VISITS'
                    //     }
                ],
                "pageLength": 100
            });

            // $(".dataTables_empty").text("There is No Any Visit Today.").css('color', '#FF0000');


            $('#example3').DataTable({

                "language": {
                    "emptyTable": "<div class='display-1 font-weight-bold'><h1 style='color: tomato;visibility: visible'>No Any Pending Issue Today</h1><div><span></span></div></div>"
                },
                // columns: columnDefs,

                dom: 'lBfrtip',
                buttons: [{

                        extend: 'excelHtml5',
                        title: 'VISITS',
                        className: 'btn-primary'
                    },

                    {
                        extend: 'pdfHtml5',
                        title: 'VISITS',
                        className: 'btn-primary',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'

                    },


                    {
                        extend: 'csvHtml5',
                        title: 'VISITS',
                        className: 'btn-primary'
                    },
                    {
                        extend: 'copyHtml5',
                        title: 'VISITS',
                        className: 'btn-primary'
                    },
                    //     {
                    //         extend: 'print',
                    //         // name: 'printButton'
                    //         title: 'VISITS'
                    //     }
                ],
                "pageLength": 100
            });



            // $(".dataTables_empty").text("There is No Any Visit Today.").css('color', '#FF0000');


            $('#group_name').change(function() {
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
                        // console.log(data.participant_group_id);
                        $('#participant_group').val(data.participant_group_id);
                    }
                });

            });

        });
    </script>


</body>

</html>