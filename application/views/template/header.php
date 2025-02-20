<?php
$user = get_logged_in_user();
$url = $this->uri->rsegment(1); // Get the controller name

?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | Support Ticket Management System</title>

    <meta name="description" content="" />
    <base href="<?= base_url(); ?>cms-assets/">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="assets/vendor/libs/apex-charts/apex-charts.css" />


    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- <link rel="stylesheet" href="//cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css"> -->
    <!-- <script src="//cdn.datatables.net/2.1.7/js/dataTables.min.js"></script> -->
    <link rel="stylesheet" href="assets/vendor/libs/datatables/datatables.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables/responsive.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables/datatables.checkboxes.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables/buttons.bootstrap5.css">

    <script src="assets/vendor/libs/datatables/datatables-bootstrap5.min.js"></script>

    <!-- <script src="assets/vendor/libs/@form-validation/popular.js"></script>
    <script src="assets/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="assets/vendor/libs/@form-validation/auto-focus.js"></script>

    <script src="assets/js/tables-datatables-basic.js"></script> -->
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="<?= base_url() ?>" class="app-brand-link">
                        <img src="<?= base_url() ?>cms-assets/assets/img/logo.png" alt="" class="img-fluid">
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item <?= ($url === 'Dashboard') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>

                    <?php if ($user['role'] === 'ADMIN'): ?>
                        <li class="menu-item <?= ($url === 'Agent') ? 'active' : '' ?>">
                            <a href="<?= base_url() ?>agents" class="menu-link">
                                <i class="menu-icon tf-icons bx bxs-user-badge"></i>
                                <div data-i18n="Analytics">Support Agent</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (in_array($user['role'], ['ADMIN', 'SUPPORT'])): ?>
                        <li class="menu-item <?= ($url === 'User') ? 'active' : '' ?>">
                            <a href="<?= base_url() ?>users" class="menu-link">
                                <i class="menu-icon tf-icons bx bxs-user-badge"></i>
                                <div data-i18n="Analytics">Users</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bxs-user-badge"></i>

                            <div data-i18n="Account Settings">Manage Student</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="<?= base_url() ?>Student" class="menu-link">
                                    <div data-i18n="Notifications">Students</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= base_url() ?>Student/today_attendance" class="menu-link">
                                    <div data-i18n="Connections">Today Attendance</div>
                                </a>
                            </li>
                        </ul>
                    </li> -->




                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block"><?= $user['name'] ?></span>
                                                    <small class="text-muted"><?= $user['role'] ?></small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= base_url() ?>profile">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= base_url() ?>logout">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>

                </nav>

                <!-- / Navbar -->
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">