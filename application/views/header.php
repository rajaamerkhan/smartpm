<!doctype html>
<html lang="en">

<head>
    <?php $admin = json_decode(json_encode($this->session->userdata), true); ?>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/' . $admin['admindata']['favicon']); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title><?= $title ?> - SmartPM CRM</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="<?= base_url('assets/css/animate.min.css') ?>" rel="stylesheet" />

    <!--  Light Bootstrap Table core CSS    -->
    <link href="<?= base_url('assets/css/light-bootstrap-dashboard.css?v=1.4.0') ?>" rel="stylesheet" />
    <!-- bootstrap tagsinput CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap-tagsinput.css') ?>">
    <!-- At.js CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/jquery.atwho.css') ?>">

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="<?= base_url('assets/css/demo.css?v=1.1') ?>" rel="stylesheet" />

    <!--     Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="<?= base_url('assets/css/pe-icon-7-stroke.css') ?>" rel="stylesheet" />
    <!-- select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Others -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/jquery.fancybox.min.css') ?>">

    <!-- JS FILES -->

    <!--   Core JS Files   -->
    <script src="<?= base_url('assets/js/jquery.3.2.1.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js') ?>" type="text/javascript"></script>
    <!--  Notifications Plugin    -->
    <script src="<?= base_url('assets/js/bootstrap-notify.js') ?>"></script>
    <!-- bootstrap tagsinput JS -->
    <script src="<?= base_url('assets/js/bootstrap-tagsinput.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap3-typeahead.min.js') ?>"></script>
    <!-- At.js JS -->
    <script src="<?= base_url('assets/js/jquery.caret.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery.atwho.js') ?>"></script>
    <!-- moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
    <!-- validate.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/validate.js/0.13.1/validate.min.js"></script>
    <!-- select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <!-- Others -->
    <script src="<?= base_url('assets/js/search-box.js') ?>"></script>
    <script src="<?= base_url('assets/js/light-bootstrap-dashboard.js?v=1.4.0') ?>"></script>
    <script src="<?= base_url('assets/js/demo.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery.fancybox.js') ?>"></script>
    <script src="<?= base_url('assets/js/validate-support.js') ?>"></script>
    <script src="<?= base_url('assets/js/show-edit-section.js') ?>"></script>

</head>

<body>

    <div class="wrapper">
        <div class="sidebar" <?php if ($this->session->userdata("color") != '') {
                                    echo "data-color=" . $this->session->userdata("color");
                                } else if ($admin['admindata']['color'] != '') {
                                    echo "data-color=" . $admin['admindata']['color'];
                                } else {
                                    echo "data-color=blue";
                                } ?> data-image="assets/img/sidebar-5.jpg">
            <div class="sidebar-wrapper">
                <div class="logo">
                    <a href="#" class="simple-text">
                        CRM
                    </a>
                </div>
                <ul class="nav">
                    <?php if ($this->session->logoUrl != '') : ?>
                        <li class="text-center" style="padding: 0 20px; margin-bottom: 20px;">
                            <img src="<?= base_url('assets/company_photo/' . $this->session->logoUrl) ?>">
                        </li>
                    <?php endif; ?>
                    <li class="<?= !empty($dashboard) ? 'active' : '' ?>">
                        <a href="<?= base_url('dashboard') ?>">
                            <i class="pe-7s-graph"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('leads') ?>">
                            <i class="pe-7s-note2"></i>
                            <p>Leads / Clients</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('lead/all-status') ?>">
                            <i class="pe-7s-note2"></i>
                            <p>All Statuses</p>
                        </a>
                    </li>
                    <!-- <li>
                        <a href="<?= base_url('lead/cash-jobs') ?>">
                            <i class="pe-7s-user"></i>
                            <p>Cash Jobs</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('lead/insurance-jobs') ?>">
                            <i class="pe-7s-note2"></i>
                            <p>Insurance Jobs</p>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="pe-7s-note2"></i>
                            <p>Insurance Negotiations</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('lead/labor-jobs') ?>">
                            <i class="pe-7s-note"></i>
                            <p>Labor Only Jobs</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('lead/financial-jobs') ?>">
                            <i class="pe-7s-note"></i>
                            <p>Financial Jobs</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('lead/production-jobs') ?>">
                            <i class="pe-7s-note"></i>
                            <p>Production Jobs</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('lead/completed-jobs') ?>">
                            <i class="pe-7s-note"></i>
                            <p>Completed Jobs</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('lead/closed-jobs') ?>">
                            <i class="pe-7s-note"></i>
                            <p>Closed Jobs</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('lead/archive-jobs') ?>">
                            <i class="pe-7s-note"></i>
                            <p>Archive Jobs</p>
                        </a>
                    </li> -->
                    <li>
                        <a class="nav-link" data-toggle="collapse" href="#financialCollapse">
                            <i class="pe-7s-graph"></i>
                            <p>Financial <b class="caret"></b></p>
                        </a>
                        <div class="collapse submenu" id="financialCollapse">
                            <ul class="nav">
                                <li class="nav-item ">
                                    <a class="nav-link" href="<?= base_url('financial/estimates') ?>">
                                        <span class="sidebar-normal">Estimates</span>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" href="<?= base_url('financial/records') ?>">
                                        <span class="sidebar-normal">Records</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="<?= base_url('vendors') ?>">
                            <i class="pe-7s-note2"></i>
                            <p>Vendors</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('users') ?>">
                            <i class="pe-7s-note2"></i>
                            <p>Users</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('tasks') ?>">
                            <i class="pe-7s-note"></i>
                            <p>Tasks</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('company-docs') ?>">
                            <i class="pe-7s-note"></i>
                            <p>Company Documents</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('company-photos') ?>">
                            <i class="pe-7s-note"></i>
                            <p>Company Photos</p>
                        </a>
                    </li>
                    <li class="">
                        <a class="nav-link" data-toggle="collapse" href="#componentsCollapse">
                            <i class="pe-7s-graph"></i>
                            <p>Admin <b class="caret"></b></p>
                        </a>
                        <div class="collapse submenu" id="componentsCollapse">
                            <ul class="nav">
                                <li class="nav-item ">
                                    <a class="nav-link" href="<?= base_url('setting') ?>">
                                        <span class="sidebar-normal"> Setting </span>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" href="<?= base_url('teams') ?>">
                                        <span class="sidebar-normal"> Teams</span>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" href="<?= base_url('items') ?>">
                                        <span class="sidebar-normal">Items</span>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" href="<?= base_url('setting/financial-options') ?>">
                                        <span class="sidebar-normal">Financial Options</span>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" href="<?= base_url('setting/client-options') ?>">
                                        <span class="sidebar-normal">Client Options</span>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" href="<?= base_url('setting/task-options') ?>">
                                        <span class="sidebar-normal">Task Options</span>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" href="<?= base_url('setting/user-options') ?>">
                                        <span class="sidebar-normal">User Options</span>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" href="<?= base_url('setting/smtp-settings') ?>">
                                        <span class="sidebar-normal">SMTP Settings</span>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" href="<?= base_url('setting/twilio-settings') ?>">
                                        <span class="sidebar-normal">Twilio Settings</span>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" href="<?= base_url('setting/dashboard-options') ?>">
                                        <span class="sidebar-normal">Dashboard Options</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="main-panel">
            <nav class="navbar navbar-default navbar-fixed" id="topnav" <?php if ($this->session->userdata("color") != '') {
                                                                            echo "data-color=" . $this->session->userdata("color");
                                                                        } else if ($admin['admindata']['color'] != '') {
                                                                            echo "data-color=" . $admin['admindata']['color'];
                                                                        } else {
                                                                            echo "data-color=blue";
                                                                        } ?>>
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#"><?= $title ?></a>
                    </div>
                    <div class="collapse navbar-collapse">


                        <ul class="nav navbar-nav navbar-right">

                            <li>
                                <div class="search-box">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                    <input type="search" name="search" id="search" autocomplete="off">
                                    <div class="search-result">
                                        <div class="leads-search-result">
                                        </div>
                                        <div class="tasks-search-result">
                                        </div>
                                        <div class="users-search-result">
                                        </div>
                                        <div class="loading-sign">
                                            <div class="text-center">
                                                <small>Loading...</small>
                                            </div>
                                        </div>
                                        <div class="no-result">
                                            <div class="text-center">
                                                <small>No Result Found!</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <a href="">Welcome <strong><?= ($admin['first_name'] . ' ' . $admin['last_name']) ?></strong></a>
                            </li>
                            <li>

                                <a href="<?= base_url('logout') ?>" data-method="POST" class="logout">
                                    <p><i class="pe-7s-power"></i> Log out</p>
                                </a>
                            </li>
                            <li class="separator hidden-lg"></li>
                        </ul>
                    </div>
                </div>
            </nav>


            <div class="content">
