<?php error_reporting(E_ALL ^ E_NOTICE); ?>
<?php require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
if ($_GET['content'] == 'visit') {
    if ($_GET['studyID']) {
        $visit_code = $override->get('clients', 'id', $_GET['studyID']); ?>
        <input type="hidden" name="visit_code" class="form-control" value="<?= $visit_code[0]['visit_code'] + 1 ?>" />
        <input type="number" name="visit_code" class="form-control" value="<?= $visit_code[0]['visit_code'] + 1 ?>" disabled />
    <?php }
} elseif ($_GET['content'] == 'site') {
    if ($_GET['site']) {
        $sites = $override->getNews('site', 'c_id', $_GET['site'], 'status', 1); ?>
        <option value="">Select Site</option>
        <?php foreach ($sites as $site) { ?>
            <option value="<?= $site['id'] ?>"><?= $site['name'] ?></option>
        <?php }
    }
} elseif ($_GET['content'] == 'district') {
    if ($_GET['regionId']) {
        $district = $override->getNews('district', 'region_id', $_GET['regionId'], 'status', 1); ?>
        <option value="">Select Dstrict</option>
        <?php foreach ($district as $district) { ?>
            <option value="<?= $district['id'] ?>"><?= $district['name'] ?></option>
    <?php }
    }
} elseif ($_GET['cnt'] == 'study') {
    if ($_GET['getUid'] == 'VAC080') {
        $project_id = 1;
    } elseif ($_GET['getUid'] == 'VAC082') {
        $project_id = 2;
    } elseif ($_GET['getUid'] == 'VAC083') {
        $project_id = 3;
    } elseif ($_GET['getUid'] == 'MAL - HERBAL') {
        $project_id = 4;
    }
    $sts = $override->get('clients', 'project_id', $project_id) ?>
    <?php foreach ($sts as $st) { ?>
        <option value="<?= $st['study_id'] ?>"><?= $st['study_id'] ?></option>
    <?php }
} elseif ($_GET['content'] == 'full_name') {
    if ($_GET['project_name']) {
        $full_name = $override->get_full_name('details', 'project_name', $_GET['project_name']); ?>
        <option value="">Select Name</option>
        <?php foreach ($full_name as $name) { ?>
            <option value="<?= $name['id'] ?>"><?= $name['fname'] . ' ' . $name['mname'] . ' ' . $name['lname'] ?></option>
<?php }
    }
} elseif ($_GET['content'] == 'details') {
    if ($_GET['full_name_id']) {
        $output = array();
        $detail = $override->get_full_name('details', 'id', $_GET['full_name_id']);
        foreach ($detail as $name) {
            $output['initial']     = $name['initial'];
            $output['gender']      = $name['gender'];
            $output['dob']         = $name['dob'];
            $output['phone1']      = $name['phone1'];
            $output['phone2']      = $name['phone2'];
        }
        echo json_encode($output);
    }
} elseif ($_GET['content'] == 'project_id') {
    if ($_GET['project_name']) {
        $output = array();
        $project_id = $override->get_full_name('study', 'name', $_GET['project_name']);
        foreach ($project_id as $name) {
            $output['project_id']     = $name['id'];
        }
        echo json_encode($output);
    }
}elseif ($_GET['content'] == 'client_id') {
    if ($_GET['study_name']) {
        $full_name = $override->get_full_name('clients', 'project_name', $_GET['study_name']); ?>
        <option value="">Client ID</option>
        <?php foreach ($full_name as $name) { ?>
            <option value="<?= $name['id'] ?>"><?= $name['study_id'] ?></option>
<?php }
    }
}elseif ($_GET['content'] == 'participant_id') {
    if ($_GET['client_id']) {
        $output = array();
        $project_id = $override->get_full_name('clients', 'id', $_GET['client_id']);
        foreach ($project_id as $name) {
            $output['participant_id']     = $name['participant_id'];
        }
        echo json_encode($output);
    }
}elseif ($_GET['content'] == 'participant_group_id') {
    if ($_GET['patient_group_name']) {
        $output = array();
        $project_id = $override->get_full_name('study', 'name', $_GET['patient_group_name']);
        foreach ($project_id as $name) {
            $output['participant_group_id']     = $name['id'];
        }
        echo json_encode($output);
    }
} ?>





<?php
if ($_GET['id'] == '2') {

    foreach ($override->get('visit', 'visit_date', date('Y-m-d')) as $data) {

        // $data[] = $row;

        $json_data = array(
            // "draw"            => 1,   
            // "recordsTotal"    => intval( $totalRecords ),  
            // "recordsFiltered" => intval($totalRecords),
            "data"            => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format

    }
}




//include connection file 
// include_once("connection.php");

// // initilize all variable
// $params = $columns = $totalRecords = $data = array();

// $params = $_REQUEST;

// //define index of column
// $columns = array( 
//     0 =>'id',
//     1 =>'employee_name', 
//     2 => 'employee_salary',
//     3 => 'employee_age'
// );

// $where = $sqlTot = $sqlRec = "";

// // getting total number records without any search
// $sql = "SELECT * FROM `employee` ";
// $sqlTot .= $sql;
// $sqlRec .= $sql;


//  $sqlRec .=  " ORDER BY employee_name";

// $queryTot = mysqli_query($conn, $sqlTot) or die("database error:". mysqli_error($conn));


// $totalRecords = mysqli_num_rows($queryTot);

// $queryRecords = mysqli_query($conn, $sqlRec) or die("error to fetch employees data");

//iterate on results row and create new index array of data
// while( $row = mysqli_fetch_row($queryRecords) ) { 
//     $data[] = $row;
// }	

// $json_data = array(
//         "draw"            => 1,   
//         "recordsTotal"    => intval( $totalRecords ),  
//         "recordsFiltered" => intval($totalRecords),
//         "data"            => $data   // total data array
//         );

// echo json_encode($json_data);  // send data as json format

//     }
?>