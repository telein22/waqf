<?php

use Application\Helpers\UserHelper;
use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\User;
use Application\Models\Workshop;
use System\Core\Model;
use System\Helpers\URL;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');
$currentLang =  $lang->current();

$cookieM = Model::get('\System\Models\Cookie');
$prefix = $userInfo['type'] == User::TYPE_ADMIN ? 'admin' : 'entities';

// var_dump($currentLang);exit;
?>
<!DOCTYPE html>
<html lang="<?= $lang->current() ?>">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $lang('admin') ?></title>

  <link rel="shortcut icon" href="<?= URL::asset('Application/Assets/images/favicon.ico') ?>" />

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Admin/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Admin/plugins/jqvmap/jqvmap.min.css') ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Admin/css/adminlte.min.css') ?>">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Admin/plugins/daterangepicker/daterangepicker.css') ?>">
  <!-- summernote -->
  <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Admin/plugins/summernote/summernote-bs4.min.css') ?>">
  <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Admin/css/style.css') ?>">

  <!-- Data table -->
  <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">

  <link href="<?= URL::asset('Application/Assets/css/select2.min.css'); ?>" rel="stylesheet" />

</head>

<body class="hold-transition sidebar-mini layout-fixed lang-<?= $lang->current() ?>" <?= $lang->current() == 'ar' ? 'dir="rtl"' : '' ?>>
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="<?= URL::asset('Application/Assets/images/logo.svg') ?>" alt="AdminLTELogo" height="100" width="100">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>

      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <select name="lang" id="langHeader" class="form-control">
            <option <?php if ($currentLang == 'en') echo 'selected' ?> value="en">English</option>
            <option <?php if ($currentLang == 'ar') echo 'selected' ?> value="ar">عربي</option>
          </select>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= URL::full('logout'); ?>"><?= $lang('logout') ?></a>
        </li>
        <!-- Navbar Search -->
        <!-- <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li> -->


        <!-- Notifications Dropdown Menu -->
        <!-- <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge">15</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">15 Notifications</span>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-envelope mr-2"></i> 4 new messages
              <span class="float-right text-muted text-sm">3 mins</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-users mr-2"></i> 8 friend requests
              <span class="float-right text-muted text-sm">12 hours</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-file mr-2"></i> 3 new reports
              <span class="float-right text-muted text-sm">2 days</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
          </a>
        </li> -->
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?= URL::full($prefix) ?>" class="brand-link">
        <img src="<?= URL::asset('\Application\Assets\images\logo.svg') ?>" class="img-fluid" width="120" alt="">
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="<?= UserHelper::getAvatarUrl("fit:300,300") ?>" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info text-white">
            <?= htmlentities($userInfo['name']) ?>
          </div>
        </div>

        <!-- SidebarSearch Form
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <!-- <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.html" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v1</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index2.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v2</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index3.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v3</p>
                </a>
              </li>
            </ul>
          </li> -->
            <!-- <li class="nav-header">EXAMPLES</li> -->
              <?php if ($userInfo['type'] == \Application\Models\User::TYPE_ENTITY) : ?>
                  <li class="nav-item">
                      <a href="<?= URL::full('entities') ?>" class="nav-link sidebar-item">
                          <i class="nav-icon far fa-calendar-alt"></i>
                          <p>
                              <?= $lang('dashboard') ?>
                              <!-- <span class="badge badge-info right">2</span> -->
                          </p>
                      </a>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'users')) echo 'active' ?>">
                      <a href="<?= URL::full('entities/users') ?>" class="nav-link sidebar-item">
                          <i class="nav-icon fas fa-users"></i>
                          <p>
                              <?= $lang('associates') ?>
                              <!-- <span class="badge badge-info right">2</span> -->
                          </p>
                      </a>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'billings')) echo 'active menu-open' ?>">
                      <a href="#" class="nav-link  sidebar-item">
                          <i class="nav-icon fas fa-money-bill"></i>
                          <p>
                              <?= $lang('reports') ?>
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="<?= URL::full('entities/billings') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('billings') ?></p>
                              </a>
                          </li>
                        <?php if(User::isCharity()) : ?>
                              <li class="nav-item">
                                  <a href="<?= URL::full('entities/donations') ?>" class="nav-link ">
                                      <i class="far fa-circle nav-icon"></i>
                                      <p><?= $lang('donations') ?></p>
                                  </a>
                              </li>
                          <?php endif; ?>
                      </ul>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'workshops')) echo 'active' ?>">
                      <a href="<?= URL::full('entities/workshops') ?>" class="nav-link sidebar-item">
                          <i class="nav-icon far fa-calendar-alt"></i>
                          <p>
                              <?= $lang('workshops') ?>
                          </p>
                      </a>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'calls')) echo 'active' ?>">
                      <a href="<?= URL::full('entities/calls') ?>" class="nav-link sidebar-item">
                          <i class="nav-icon fas fa-phone"></i>
                          <p>
                              <?= $lang('calls') ?>
                          </p>
                      </a>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'coupon')) echo 'active menu-open' ?> ">
                      <a href="#" class="nav-link sidebar-item">
                          <i class="nav-icon  fas fa-tags"></i>
                          <p>
                              <?= $lang('coupons') ?>
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="<?= URL::full('entities/coupons') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('all_coupons') ?></p>
                              </a>
                              <a href="<?= URL::full('entities/add-coupon') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('add_coupon') ?></p>
                              </a>
                          </li>
                      </ul>
                  </li>
              <?php elseif($userInfo['type'] == \Application\Models\User::TYPE_ADMIN) : ?>
                  <li class="nav-item">
                      <a href="<?= URL::full('admin') ?>" class="nav-link sidebar-item">
                          <i class="nav-icon far fa-calendar-alt"></i>
                          <p>
                              <?= $lang('dashboard') ?>
                              <!-- <span class="badge badge-info right">2</span> -->
                          </p>
                      </a>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'users')) echo 'active' ?>">
                      <a href="<?= URL::full('admin/users') ?>" class="nav-link sidebar-item">
                          <i class="nav-icon fas fa-users"></i>
                          <p>
                              <?= $lang('users') ?>
                              <!-- <span class="badge badge-info right">2</span> -->
                          </p>
                      </a>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'feeds')) echo 'active' ?>">
                      <a href="<?= URL::full('admin/feeds') ?>" class="nav-link sidebar-item">
                          <i class="nav-icon  fas fa-rss"></i>
                          <p>
                              <?= $lang('feeds') ?>
                              <!-- <span class="badge badge-info right">2</span> -->
                          </p>
                      </a>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'settings')) echo 'active' ?>">
                      <a href="<?= URL::full('admin/settings') ?>" class="nav-link sidebar-item">
                          <i class="nav-icon  fas fa-cog"></i>
                          <p>
                              <?= $lang('settings') ?>
                              <!-- <span class="badge badge-info right">2</span> -->
                          </p>
                      </a>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'coupon')) echo 'active menu-open' ?> ">
                      <a href="#" class="nav-link sidebar-item">
                          <i class="nav-icon  fas fa-tags"></i>
                          <p>
                              <?= $lang('coupons') ?>
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="<?= URL::full('admin/coupons') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('all_coupons') ?></p>
                              </a>
                              <a href="<?= URL::full('admin/add-coupon') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('add_coupon') ?></p>
                              </a>
                          </li>
                      </ul>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'charity') || strpos(URL::current(), 'charities')) echo 'active menu-open' ?>">
                      <a href="#" class="nav-link  sidebar-item">
                          <i class="nav-icon  fas fa-hand-holding-heart"></i>
                          <p>
                              <?= $lang('charities') ?>
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="<?= URL::full('admin/charities') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('all_charities') ?></p>
                              </a>
                              <a href="<?= URL::full('admin/add-charity') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('add_charity') ?></p>
                              </a>
                              <a href="<?= URL::full('admin/import-charity') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('import_charities') ?></p>
                              </a>
                          </li>
                      </ul>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'charity') || strpos(URL::current(), 'entities')) echo 'active menu-open' ?>">
                      <a href="#" class="nav-link  sidebar-item">
                          <i class="nav-icon  fas fa-hand-holding-heart"></i>
                          <p>
                              <?= $lang('entities') ?>
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="<?= URL::full('admin/entities') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('all_entities') ?></p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="<?= URL::full('admin/add-entity') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('add_entity') ?></p>
                              </a>
                          </li>
                      </ul>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'tracker')) echo 'active' ?>">
                      <a href="<?= URL::full('admin/tracker') ?>" class="nav-link sidebar-item">
                          <i class="nav-icon fas fa-list"></i>
                          <p>
                              <?= $lang('user_tracker') ?>
                          </p>
                      </a>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'messages')) echo 'active' ?>">
                      <a href="<?= URL::full('admin/messages') ?>" class="nav-link sidebar-item">
                          <i class="nav-icon fas fa-history"></i>
                          <p>
                              <?= $lang('messages') ?>
                          </p>
                      </a>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'search-history')) echo 'active' ?>">
                      <a href="<?= URL::full('admin/search-history') ?>" class="nav-link sidebar-item">
                          <i class="nav-icon fas fa-history"></i>
                          <p>
                              <?= $lang('search_history') ?>
                          </p>
                      </a>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'blocked')) echo 'active menu-open' ?>">
                      <a href="#" class="nav-link  sidebar-item">
                          <i class="nav-icon  fas fa-remove-format"></i>
                          <p>
                              <?= $lang('blocked_words') ?>
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="<?= URL::full('admin/blocked-words') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('all_words') ?></p>
                              </a>
                              <a href="<?= URL::full('admin/add-blocked-words') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('add_new_word') ?></p>
                              </a>
                              <a href="<?= URL::full('admin/feed-with-blocked') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('feeds-with-blocked-word') ?></p>
                              </a>
                          </li>
                      </ul>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'reviews')) echo 'active menu-open' ?>">
                      <a href="#" class="nav-link  sidebar-item">
                          <i class="nav-icon fas fa-star"></i>
                          <p>
                              <?= $lang('reviews') ?>
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="<?= URL::full('admin/reviews/' . Call::ENTITY_TYPE) ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('zoom_call') ?></p>
                              </a>
                              <a href="<?= URL::full('admin/reviews/' . Workshop::ENTITY_TYPE) ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('workshop') ?></p>
                              </a>
                              <a href="<?= URL::full('admin/reviews/' . Conversation::ENTITY_TYPE) ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('message') ?></p>
                              </a>
                          </li>
                      </ul>
                  </li>

                  <li class="nav-item <?php if (strpos(URL::current(), 'billings')) echo 'active menu-open' ?>">
                      <a href="#" class="nav-link  sidebar-item">
                          <i class="nav-icon fas fa-money-bill"></i>
                          <p>
                              <?= $lang('financial_department') ?>
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="<?= URL::full('admin/billings') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('all_orders') ?></p>
                              </a>
                          </li>
                          <li class="nav-item <?php if (strpos(URL::current(), 'wallets')) echo 'active ' ?>">
                              <a href="<?= URL::full('admin/wallets') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('all_wallets') ?></p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="<?= URL::full('admin/withdrawal-requests') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('withdrawal_requests') ?></p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="<?= URL::full('admin/commissions') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('commissions') ?></p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="<?= URL::full('admin/profit-proceeds') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('profits_proceed') ?></p>
                              </a>
                          </li>
                      </ul>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'workshops')) echo 'active' ?>">
                      <a href="<?= URL::full('admin/workshops') ?>" class="nav-link sidebar-item">
                          <i class="nav-icon far fa-calendar-alt"></i>
                          <p>
                              <?= $lang('workshops') ?>
                          </p>
                      </a>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'calls')) echo 'active' ?>">
                      <a href="<?= URL::full('admin/calls') ?>" class="nav-link sidebar-item">
                          <i class="nav-icon fas fa-phone"></i>
                          <p>
                              <?= $lang('calls') ?>
                          </p>
                      </a>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'specialties') || strpos(URL::current(), 'add-specialists')) echo 'active menu-open' ?>">
                      <a href="#" class="nav-link  sidebar-item">
                          <i class="nav-icon fas fa-filter"></i>
                          <p>
                              <?= $lang('specialists') ?>
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="<?= URL::full('admin/specialties') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('all_specialists') ?></p>
                              </a>
                              <a href="<?= URL::full('admin/add-specialists') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('add_new_specialists') ?></p>
                              </a>
                          </li>
                      </ul>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'sub-specialties') || strpos(URL::current(), 'add-sub-specialists')) echo 'active menu-open' ?>">
                      <a href="#" class="nav-link  sidebar-item">
                          <i class="nav-icon fas fa-filter"></i>
                          <p>
                              <?= $lang('sub_specialists') ?>
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="<?= URL::full('admin/sub-specialties') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('all_sub_specialists') ?></p>
                              </a>
                              <a href="<?= URL::full('admin/add-sub-specialists') ?>" class="nav-link ">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p><?= $lang('add_new_sub_specialists') ?></p>
                              </a>
                          </li>
                      </ul>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'logs')) echo 'active' ?>">
                      <a href="<?= URL::full('admin/logs') ?>" class="nav-link sidebar-item">
                          <i class="nav-icon fas fa-history"></i>
                          <p>
                              <?= $lang('system_logs') ?>
                          </p>
                      </a>
                  </li>
                  <li class="nav-item <?php if (strpos(URL::current(), 'session')) echo 'active' ?>">
                      <a href="<?= URL::full('admin/live-session') ?>" class="nav-link sidebar-item">
                          <i class="nav-icon fas fa-history"></i>
                          <p>
                              <?= $lang('session_tracker') ?>
                          </p>
                      </a>
                  </li>
              <?php endif; ?>


          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6 text-right">
              <!-- <h1 class="m-0"><?= $currentPage ?></h1> -->
            </div><!-- /.col -->
            <div class="col-sm-6">
              <!-- <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol> -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <define footer_js>
        <script>
          $("#langHeader").on('change', function(e) {
            var lang = $(this).val();
            var url = '<?= URL::full('/language/change') ?>?lang=' + lang + '&url=' + btoa(window.location.href);

            window.location.href = url;
            return;
          })
        </script>
      </define>