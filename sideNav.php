<?php error_reporting(E_ALL ^ E_NOTICE); ?>
<?php

require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$site = $override->get('site', 'id', $user->data()->s_id);
$country = $override->get('country', 'id', $user->data()->c_id);
$clntNo = $override->getNo('clients');
$ap = $override->countNoRepeatAll('visit', 'client_id');
$end = $override->getCount('clients', 'status', 0);
$tv = $override->getCount('visit', 'visit_date', date('Y-m-d'));
?>


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
                                    <a href="today_visit.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>View Today Visit</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <!-- <a href="info.php?id=14" class="nav-link"> -->
                                    <a href="dashboard3.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Manager Volunteers</p>
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
                                    <a href="add_screening.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Add Screening</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_enrollment.php" class="nav-link">
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