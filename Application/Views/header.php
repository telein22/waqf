<?php

use Application\Helpers\UserHelper;
use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Message;
use Application\Models\User;
use Application\Models\UserSettings;
use Application\Models\Workshop;
use System\Core\Model;
use System\Core\Request;
use System\Helpers\URL;
use System\Responses\View;

/**
 * @var \Application\Models\User
 */
$userM = Model::get('\Application\Models\User');
$userInfo = $userM->getInfo();

$lang = Model::get('\Application\Models\Language');
$currentLang =  $lang->current();

// $orderM = Model::get('\Application\Models\Order');

// $workshopCount = $orderM->totalCount($userInfo['id'], Workshop::ENTITY_TYPE);
// $callCount = $orderM->totalCount($userInfo['id'], Call::ENTITY_TYPE);
// $conversationCount = $orderM->totalCount($userInfo['id'], Conversation::ENTITY_TYPE);

// $pendingCount = (int) $workshopCount + (int) $callCount + (int) $conversationCount;

$searchQ = Request::instance()->get('q');


?>
<!doctype html>
<html lang="<?= $lang->current() ?>">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="etag" content="16484efefb1ff2a9c3f25a8bc4f165d11548b2a9" />
    <meta name="msapplication-TileImage" content="https://bin.bnbstatic.com/static/images/bnb-for/brand.png" />
    <meta name="description" content="منصة تواصل اجتماعي تمكن مختلف أفراد المجتمع من التواصل الفعّال فيما بينهم في مختلف المجالات والخبرات. يستطيع المستخدم التواصل مع أصحاب الخبرات والإستفادة من حسابه الشخصي كمختص عن طريق فتح قناة للتواصل مع الآخرين. كما تمكن المنصة الجهات الحكومية والخاصة والجمعيات الخيرية من الإستفادة مع منسوبيها وذوي الخبرات بتقديم خدماتهم على أوسع نطاق."/>
    <meta property="og:url" content="<?= \Application\Helpers\AppHelper::getBaseUrl() ?>"/>
    <meta property="og:type" content="website" data-shuvi-head="true" />
    <meta property="og:title" content="منصة تواصل اجتماعي تمكن مختلف أفراد المجتمع من التواصل الفعّال فيما بينهم في مختلف المجالات والخبرات. يستطيع المستخدم التواصل مع أصحاب الخبرات والإستفادة من حسابه الشخصي كمختص عن طريق فتح قناة للتواصل مع الآخرين. كما تمكن المنصة الجهات الحكومية والخاصة والجمعيات الخيرية من الإستفادة مع منسوبيها وذوي الخبرات بتقديم خدماتهم على أوسع نطاق."/>
    <meta property="og:site_name" content="Binance Blog" data-shuvi-head="true" />

    <?php $imageURL = isset($user) ? URL::asset("Storage/SocialMediaCards/social_media_card_{$user['id']}.jpg") : "" ?>
    <meta property="og:image" content="<?= $imageURL ?>" data-shuvi-head="true" />
    <meta property="og:description" content="منصة تواصل اجتماعي تمكن مختلف أفراد المجتمع من التواصل الفعّال فيما بينهم في مختلف المجالات والخبرات. يستطيع المستخدم التواصل مع أصحاب الخبرات والإستفادة من حسابه الشخصي كمختص عن طريق فتح قناة للتواصل مع الآخرين. كما تمكن المنصة الجهات الحكومية والخاصة والجمعيات الخيرية من الإستفادة مع منسوبيها وذوي الخبرات بتقديم خدماتهم على أوسع نطاق."/>
    <meta property="twitter:title" content="منصة تواصل اجتماعي تمكن مختلف أفراد المجتمع من التواصل الفعّال فيما بينهم في مختلف المجالات والخبرات" data-shuvi-head="true" />
    <meta property="twitter:site" content="Binance Blog" data-shuvi-head="true" />
    <meta property="twitter:image" content="<?= $imageURL ?>" data-shuvi-head="true" />
    <meta property="twitter:image:src" content="<?= $imageURL ?>" data-shuvi-head="true" />
    <meta property="twitter:card" content="summary_large_image" data-shuvi-head="true" />
    <meta name="twitter:description" content="Telein Desc" data-shuvi-head="true" />

    <meta property="facebook:title" content="منصة تواصل اجتماعي تمكن مختلف أفراد المجتمع من التواصل الفعّال فيما بينهم في مختلف المجالات والخبرات" data-shuvi-head="true" />
    <meta property="facebook:site" content="Binance Blog" data-shuvi-head="true" />
    <meta property="facebook:image" content="<?= $imageURL ?>" data-shuvi-head="true" />
    <meta property="facebook:image:src" content="<?= $imageURL ?>" data-shuvi-head="true" />
    <meta property="facebook:card" content="summary_large_image" data-shuvi-head="true" />
    <meta name="facebook:description" content="Telein Desc" data-shuvi-head="true" />

    <meta property="linkedin:title" content="منصة تواصل اجتماعي تمكن مختلف أفراد المجتمع من التواصل الفعّال فيما بينهم في مختلف المجالات والخبرات" data-shuvi-head="true" />
    <meta property="linkedin:site" content="Binance Blog" data-shuvi-head="true" />
    <meta property="linkedin:image" content="<?= $imageURL ?>" data-shuvi-head="true" />
    <meta property="linkedin:image:src" content="<?= $imageURL ?>" data-shuvi-head="true" />
    <meta property="linkedin:card" content="summary_large_image" data-shuvi-head="true" />
    <meta name="linkedin:description" content="Telein Desc" data-shuvi-head="true" />


    <meta name="apple-mobile-web-app-status-bar-style" content="black" data-shuvi-head="true" />
    <meta name="apple-mobile-web-app-capable" content="yes" data-shuvi-head="true" />
    <meta name="format-detection" content="email=no" data-shuvi-head="true" />


    <title><?= isset($title) ? htmlentities($title) : 'TELE IN'; ?></title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= URL::asset('Application/Assets/images/favicon.ico') ?>" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/css/bootstrap.min.css') ?>">
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/css/owl.carousel.min.css') ?>">
    <!-- Typography CSS -->
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/css/typography.css') ?>">
    <!-- Style CSS -->
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/css/style.css') ?>">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/css/responsive.css') ?>">
    <!-- Toaster CSS -->
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/css/toasterjs.css') ?>">
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/css/jquery.toast.min.css') ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Admin/plugins/fontawesome-free/css/all.min.css') ?>">

    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/css/custom.css') ?>">
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/css/cropper.css') ?>">

    <!-- Data table -->
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">

    <call header_css />
</head>

<body class="right-column-fixed sidebar-main-active lang-<?= $lang->current() ?>" <?= $lang->current() == 'ar' ? 'dir="rtl"' : '' ?>>
<?php View::include('preloader') ?>

<img id="loader" class="d-none" src="<?= URL::asset("Application/Assets/images/page-img/ajax-loader.gif"); ?>" alt="loader" style="width: 75px; height: 75px; margin: 0 auto; position: fixed; top:20%; left: 50%; z-index:99999999;" >

    <!-- loader Start -->
    <!-- <div id="loading">
        <div id="loading-center">
        </div>
    </div> -->
    <!-- <div aria-live="polite" class="polite" aria-atomic="true">
        <div id="toaster" class="toast bg-primary">
            <div class="toast-header">
                <strong class="mr-auto title">Bootstrap</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                Hello, world! This is a toast message.
            </div>
        </div>
    </div> -->
    <!-- loader END -->
    <!-- Wrapper Start -->
    <div class="wrapper">
        <!-- Sidebar  -->
        <div class="iq-sidebar">
            <div id="sidebar-scrollbar">
                <nav class="iq-sidebar-menu">
                    <ul id="iq-sidebar-toggle" class="iq-menu">
                        <li <?php if (strpos(URL::current(), 'home')) echo 'class="active"' ?>>
                            <a href="<?= URL::full('/') ?>" class="iq-waves-effect" title="<?= $lang('home'); ?>"><i class="ri-home-line"></i><span><?= $lang('home'); ?></span></a>
                        </li>
                        <li <?php if (strpos(URL::current(), 'home')) echo 'class="active"' ?>>
                            <a href="<?= URL::full('feeds') ?>" class="iq-waves-effect" title="<?= $lang('latest_news'); ?>"><i class="ri-feedback-line"></i><span><?= $lang('latest_news'); ?></span></a>
                        </li>
                        <li <?php if (strpos(URL::current(), 'dashboard')) echo 'class="active"' ?>>
                            <a href="<?= URL::full('dashboard') ?>" class="iq-waves-effect" title="<?= $lang('explore'); ?>"><i class="ri-group-line"></i><span><?= $lang('explore'); ?></span></a>
                        </li>
                        <!-- <li <?php if (strpos(URL::current(), 'liked-feeds')) echo 'class="active"' ?>>
                            <a href="<?= URL::full('liked-feeds') ?>" class="iq-waves-effect" title="<?= $lang('saved_feed'); ?>"><i class="las la-newspaper"></i><span><?= $lang('saved_feed'); ?></span></a>
                        </li> -->
                        <li <?php if (strpos(URL::current(), 'profile')) echo 'class="active"' ?>>
                            <a href="<?= URL::full('profile') ?>" class="iq-waves-effect" title="<?= $lang('profile'); ?>"><i class="las la-user"></i><span><?= $lang('profile'); ?></span></a>
                        </li>
                        <li <?php if (strpos(URL::current(), 'search')) echo 'class="active"' ?>>
                            <a href="<?= URL::full('search') ?>" class="iq-waves-effect" title="<?= $lang('search'); ?>"><i class="ri-search-line"></i><span><?= $lang('search'); ?></span></a>
                        </li>
                        <li <?php if (strpos(URL::current(), 'workshops') && strpos(URL::current(), 'find')) echo 'class="active"' ?>>
                            <a href="<?= URL::full('workshops/find') ?>"  class="iq-waves-effect" title="<?= $lang('find_more_workshops_nav'); ?>"><i class="ri-slideshow-line"></i><span><?= $lang('find_more_workshops_nav'); ?></span></a>
                        </li>
                        <li <?php if (strpos(URL::current(), 'workshops') && !strpos(URL::current(), 'find')) echo 'class="active"' ?>>
                            <a href="<?= URL::full('workshops/b') ?>"  class="iq-waves-effect" title="<?= $lang('header_my_workshops_nav'); ?>"><i class="ri-slideshow-line"></i><span><?= $lang('header_my_workshops_nav'); ?></span></a>
                        </li>
                        <li <?php if (strpos(URL::current(), 'calls')) echo 'class="active"' ?>>
                            <a href="<?= URL::full('calls/b') ?>" class="iq-waves-effect" title="<?= $lang('header_my_calls'); ?>"><i class="ri-phone-line"></i><span><?= $lang('header_my_calls'); ?></span></a>
                        </li>
                        <li <?php if (strpos(URL::current(), 'messaging')) echo 'class="active"' ?>>
                            <a href="<?= URL::full('messaging/b') ?>" class="iq-waves-effect" class="iq-waves-effect" title="<?= $lang('header_my_messages'); ?>"><i class="ri-message-2-line"></i><span><?= $lang('header_my_messages'); ?></span></a>
                        </li>
                        <?php if ($userM->canCreateWorkshop()) : ?>
                            <!-- <li <?php if (strpos(URL::current(), 'invite')) echo 'class="active"' ?>>
                                <a href="<?= URL::full('invite') ?>" class="iq-waves-effect" title="<?= $lang('invite_free_users'); ?>"><i class="las la-users"></i><span><?= $lang('invite_free_users'); ?></span></a>
                            </li> -->
                        <?php endif; ?>
                        <li <?php if (strpos(URL::current(), 'earnings')) echo 'class="active"' ?>>
                            <a href="<?= URL::full('earnings') ?>" class="iq-waves-effect" title="<?= $lang('my_orders'); ?>"><i class="ri-copper-coin-line"></i><span><?= $lang('my_earnings'); ?></span></a>
                        </li>
                        <li <?php if (strpos(URL::current(), 'my')) echo 'class="active"' ?>>
                            <a href="<?= URL::full('order/my') ?>" class="iq-waves-effect" title="<?= $lang('my_orders'); ?>"><i class="ri-shopping-cart-2-line"></i><span><?= $lang('my_orders'); ?></span></a>
                        </li>
                        <!-- <li <?php if (strpos(URL::current(), 'requests')) echo 'class="active"' ?>>
                            <a href="<?= URL::full('/order/requests') ?>" title="<?= $lang('reservation_requests'); ?>"><i class="ri-checkbox-multiple-line"></i><span><?= $lang('reservation_requests'); ?></span><small class="badge pending-order-badge  badge-light float-right pt-1 d-none"></small></a>
                        </li> -->
                        <li <?php if (strpos(URL::current(), 'settings')) echo 'class="active"' ?>>
                            <a href="<?= URL::full('settings') ?>" class="iq-waves-effect" title="<?= $lang('customer_support'); ?>"><i class="ri-question-line"></i><span><?= $lang('customer_support'); ?></span></a>
                        </li>
                        <li <?php if (strpos(URL::current(), 'faq')) echo 'class="active"' ?>>
                            <a href="<?= URL::full('faq') ?>" class="iq-waves-effect" title="<?= $lang('faq'); ?>"><i class="ri-article-line"></i><span><?= $lang('faq'); ?></span></a>
                        </li>
                        <li <?php if (strpos(URL::current(), 'terms')) echo 'class="active"' ?>>
                            <a href="<?= URL::full('terms') ?>" class="iq-waves-effect" title="<?= $lang('terms_condition'); ?>"><i class="ri-article-line"></i><span><?= $lang('terms_condition'); ?></span></a>
                        </li>                        
                    </ul>
                </nav>
                <div class="p-3"></div>
            </div>
        </div>
        <!-- TOP Nav Bar -->
        <div class="iq-top-navbar">
            <div class="iq-navbar-custom">
                <nav class="navbar navbar-expand-lg navbar-light p-0">
                    <div class="iq-navbar-logo d-flex justify-content-between">
                        <a href="<?= URL::full('feeds'); ?>">
                            <img src="<?= URL::asset('\Application\Assets\images\logo.svg') ?>" class="img-fluid" width="100" alt="">
                        </a>
                        <div class="iq-menu-bt align-self-center m-0">
                            <div class="wrapper-menu">
                                <div class="main-circle"><i class="ri-menu-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="wrap">
                        <div class="join-container workshop d-none">
                            <div class="join-info">
                                <p class="join-message"></p>
                                <span class="user-name"></span>
                            </div>
                            <div class="join-control">
                                <button class="header-join-btn">
                                    <?= $lang('join') ?>
                                </button>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="wrap">
                        <div class="join-container call d-none">
                            <div class="join-info">
                                <p class="join-message"></p>
                                <span class="user-name"></span>
                            </div>
                            <div class="join-control">
                                <button class="header-join-btn">
                                    <?= $lang('join') ?>
                                </button>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="iq-search-bar">
                        <form action="<?= URL::full('search') ?>" method="GET" class="searchbox">
                            <input type="text" class="text search-input" placeholder="<?= $lang('search_placeholder') ?>" name="q" value="<?= $searchQ; ?>">
                            <a class="search-link header-search-link" href="#" onclick="$('.searchbox').submit();"><i class="ri-search-line"></i></a>
                        </form>
                    </div>
                    <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-label="Toggle navigation">
                        <i class="ri-menu-3-line"></i>
                    </button> -->
                    <div class="navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto navbar-list d-flex flex-row">
                            <li class="d-flex align-items-center">
                                <div class="form-group outer-lang-change mb-0">
                                    <select name="lang" id="langHeader" class="form-control change-lang-class">
                                        <option <?php if ($currentLang == 'en') echo 'selected' ?> value="en">English</option>
                                        <option <?php if ($currentLang == 'ar') echo 'selected' ?> value="ar">عربي</option>
                                    </select>
                                </div>
                            </li>
                            <li class="order-5">
                                <a href="<?= URL::full('profile') ?>" class="iq-waves-effect d-flex align-items-center">
                                    <img src="<?= UserHelper::getAvatarUrl('fit:300,300'); ?>" class="img-fluid rounded-circle mr-3" alt="user">
                                    <div class="caption">
                                        <h6 class="mb-0 line-height"><?= htmlentities($userInfo['name']); ?>
                                            <?php if ($userInfo['account_verified']) echo '<i class="fas fa-check-circle color-primary"></i>' ?>
                                        </h6>
                                    </div>
                                </a>
                            </li>
                            <li class="order-4">
                                <a href="<?= URL::full('/feeds') ?>" class="iq-waves-effect d-flex align-items-center">
                                    <i class="ri-home-line"></i>
                                </a>
                            </li>
                            <li class="mobile-only d-none order-3">
                                <a href="<?= URL::full('/hashtags') ?>" class="iq-waves-effect d-flex align-items-center">
                                    #
                                </a>
                            </li>
                            <li class="mobile-only d-none order-2">
                                <a href="<?= URL::full('/categories') ?>" class="iq-waves-effect d-flex align-items-center">
                                    *
                                </a>
                            </li>
                            <li class="nav-item notification-item order-1">
                                <a href="#" class="search-toggle iq-waves-effect" id="notification-arrow">
                                    <i class="ri-notification-4-line"></i>
                                    <span class="bg-danger dots d-none"></span>
                                </a>

                                <div class="iq-sub-dropdown">
                                    <div class="iq-card shadow-none m-0">
                                        <div class="iq-card-body p-0 ">
                                            <div class="bg-primary p-3">
                                                <h5 class="mb-0 text-white"><?= $lang('notifications') ?><small class="badge total-noti-count  badge-light float-right pt-1">4</small></h5>
                                            </div>
                                            <a href="<?= URL::full('notification/social') ?>" class="iq-sub-card">
                                                <div class="media align-items-center">
                                                    <div class="media-body ml-3">
                                                        <small class="float-right font-size-12 badge badge-primary social-noti-count"><?= 2 ?></small>
                                                        <h6 class="mb-0 "><?= $lang('account_notifications') ?></h6>

                                                        <!-- <p class="mb-0">95 MB</p> -->
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="<?= URL::full('notification/service') ?>" class="iq-sub-card">
                                                <div class="media align-items-center">
                                                    <div class="media-body ml-3">
                                                        <small class="float-right font-size-12 badge badge-primary service-noti-count"><?= 2 ?></small>
                                                        <h6 class="mb-0 "><?= $lang('advisor') ?></h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="notification-toast bg-primary d-none animate__animated animate__fadeInDown">
                                    <p class="m-0">You have got pending notifications</p>
                                </div>
                            </li>
                        </ul>
                        <ul class="navbar-list">
                            <li>
                                <a href="#" class="search-toggle iq-waves-effect d-flex align-items-center p-0">
                                    <i class="ri-arrow-down-s-fill"></i>
                                </a>
                                <div class="iq-sub-dropdown iq-user-dropdown">
                                    <div class="iq-card shadow-none m-0">
                                        <div class="iq-card-body p-0 ">
                                            <div class="bg-primary p-3 line-height">
                                                <h5 class="mb-0 text-white line-height"><?= $lang('menu_hello', ['name' => $userInfo['name']]); ?></h5>
                                            </div>
                                            <?php if ($userInfo['type'] == User::TYPE_ADMIN) : ?>
                                                <a href="<?= URL::full('admin'); ?>" class="iq-sub-card iq-bg-info-hover">
                                                    <div class="media align-items-center">
                                                        <div class="rounded iq-card-icon iq-bg-info">
                                                            <i class="ri-account-box-line"></i>
                                                        </div>
                                                        <div class="media-body ml-3">
                                                            <h6 class="mb-0 "><?= $lang('admin') ?></h6>
                                                            <p class="mb-0 font-size-12"><?= $lang('go_to_admin_panel') ?></p>
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($userInfo['type'] == User::TYPE_ENTITY) : ?>
                                                <a href="<?= URL::full('entities'); ?>" class="iq-sub-card iq-bg-info-hover">
                                                    <div class="media align-items-center">
                                                        <div class="rounded iq-card-icon iq-bg-info">
                                                            <i class="ri-account-box-line"></i>
                                                        </div>
                                                        <div class="media-body ml-3">
                                                            <h6 class="mb-0 "><?= $lang('admin') ?></h6>
                                                            <p class="mb-0 font-size-12"><?= $lang('go_to_admin_panel') ?></p>
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?= URL::full('profile'); ?>" class="iq-sub-card iq-bg-primary-hover">
                                                <div class="media align-items-center">
                                                    <div class="rounded iq-card-icon iq-bg-primary">
                                                        <i class="ri-file-user-line"></i>
                                                    </div>
                                                    <div class="media-body ml-3">
                                                        <h6 class="mb-0 "><?= $lang('my_profile') ?></h6>
                                                        <p class="mb-0 font-size-12"><?= $lang('view_personal_profile_details') ?></p>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="<?= URL::full('profile/edit'); ?>" class="iq-sub-card iq-bg-warning-hover">
                                                <div class="media align-items-center">
                                                    <div class="rounded iq-card-icon iq-bg-warning">
                                                        <i class="ri-profile-line"></i>
                                                    </div>
                                                    <div class="media-body ml-3">
                                                        <h6 class="mb-0 "><?= $lang('menu_edit_profile'); ?></h6>
                                                        <p class="mb-0 font-size-12"><?= $lang('menu_edit_profile_desc'); ?></p>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="<?= URL::full('settings'); ?>" class="iq-sub-card iq-bg-info-hover">
                                                <div class="media align-items-center">
                                                    <div class="rounded iq-card-icon iq-bg-info">
                                                        <i class="ri-question-line"></i>
                                                    </div>
                                                    <div class="media-body ml-3">
                                                        <h6 class="mb-0 "><?= $lang('customer_support') ?></h6>
                                                        <!-- <p class="mb-0 font-size-12"><?= $lang('view_personal_profile_details') ?></p> -->
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="<?= URL::full('language/change?lang=' . ($currentLang == 'en' ? 'ar' : 'en') . '&url=' . base64_encode(URL::current()) ) ?>" class="iq-sub-card iq-bg-info-hover" id="language-menu">
                                                <div class="media align-items-center">
                                                    <div class="rounded iq-card-icon iq-bg-info">
                                                        <i class="ri-global-line"></i>
                                                    </div>
                                                    <div class="media-body ml-3">
                                                        <h6 class="mb-0 "><?= $lang('language') ?> (<?= $currentLang == 'en' ? 'English' : 'العربية' ?>)</h6>
                                                        <p class="mb-0 font-size-12"><?= $lang('change_language') ?></p>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="d-inline-block w-100 text-center p-3">
                                                <a class="bg-primary iq-sign-btn" href="<?= URL::full('logout'); ?>" role="button"><?= $lang('sign_out') ?><i class="ri-login-box-line ml-2"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!-- TOP Nav Bar END -->
        <!-- Page Content  -->
        <div id="content-page" class="content-page">
            <?php if (!$userM->canCreateWorkshop()) : ?>
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-danger workshop-cant-create" role="alert">
                                <?= $lang('workshop_cant_create_alert', [ 'url' => URL::full('profile/edit') ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php View::include('modal_instruction', [
                'user' => $userInfo
            ]); ?>

            <?php View::include('Calls/modal', [
                'charities' => $charities,
            ]) ?>

            <?php View::include('Workshop/modal', [
                'charities' => [],
                'user' => $userInfo
            ]) ?>


            <!-- Floating buttons container -->
            <div class="floating-buttons">
                <!-- Button to create a post -->
                <div class="floating-button" onclick='window.location.href="<?= URL::full('feeds') ?>"'>
                    <i class="fas fa-pencil-alt"></i> <!-- Font Awesome icon for pencil -->
                </div>

                <!-- Button to make a call -->
                <div class="floating-button" data-toggle="modal" data-target="#create-call-modal">
                    <i class="fas fa-phone"></i> <!-- Font Awesome icon for phone -->
                </div>

                <!-- Button to create a workshop -->
                <div class="floating-button" data-toggle="modal" data-target="#create-workshop-modal">
                    <i class="fas fa-chalkboard-teacher"></i> <!-- Font Awesome icon for chalkboard teacher -->
                </div>
            </div>

            <define footer_js>
                <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>

                <script>
                    $("#langHeader").on('change', function(e) {
                        var lang = $(this).val();
                        var url = '<?= URL::full('/language/change') ?>?lang=' + lang + '&url=' + btoa(window.location.href);

                        window.location.href = url;
                        return;
                    })

                    var oldPendingCount = 0;
                    var oldPendingCount = 0;

                    var before = function(data) {
                        // console.log(data);
                    };

                    var after = function(count, value) {

                        value = parseInt(value);

                        $(".pending-order-badge").text(value);
                        $(".pending-order-badge").removeClass('d-none');
                    };

                    ping.subscribe('pendingRequest.count', before, after);
                </script>
                <script>
                    window.isCountdownActive = true;

                    var joinBtn = $('.header-join-btn'),
                        entityId = null,
                        joinWorkshop = $('.workshop'),
                        joinCallBtn = $('.call'),
                        workshopJoinMessage = $('.workshop .join-message'),
                        callJoinMessage = $('.call .join-message'),
                        withUserName = $('.user-name'),
                        workshopJoinURL = '<?= URL::full('ajax/workshop/join') ?>',
                        workshopStartAndJoinURL = '<?= URL::full('ajax/workshop/startAndJoin') ?>',
                        callJoinURL = '<?= URL::full('ajax/calls/join') ?>',
                        callStartAndJoinURL = '<?= URL::full('ajax/calls/startAndJoin') ?>',
                        waitingRoomURL = null,
                        youHaveSessionWithAdvisor = "<?= $lang('you_have_session_with_advisor')?>",
                        youHaveSessionWithParticipant = "<?= $lang('you_have_session_with_participant')?>",
                        youHaveCallWithAdvisor = "<?= $lang('you_have_call_with_advisor')?>",
                        youHaveCallWithParticipant = "<?= $lang('you_have_call_with_participant')?>",
                        isAdvisor = false,
                        isWorkshop = false,

                        before = function(data) {

                                },

                        afterWorkshop = function(count, value) {
                            joinWorkshop.addClass('d-none');
                            if (value.workshop !== false) {
                                var joinMsg = value.isAdvisor ? youHaveSessionWithParticipant : youHaveSessionWithAdvisor,
                                    userName = value.isAdvisor ? '' : value.user.name;

                                workshopJoinMessage.text(joinMsg);
                                withUserName.text(userName);
                                entityId = value.workshop.id;
                                joinURL = value.isAdvisor ? workshopStartAndJoinURL : workshopJoinURL;
                                waitingRoomURL = '/waiting-room/sessions/' + entityId;
                                isWorkshop = true;
                                isAdvisor = value.isAdvisor;
                                joinWorkshop.removeClass('d-none');

                                // Hide the join button inside the waiting room
                                if (document.URL.indexOf("waiting-room") >= 0 && isAdvisor === false && window.isCountdownActive === true) {
                                    $('.join-container').hide();
                                }
                            }
                        };

                        afterCall = function(count, value) {
                            joinCallBtn.addClass('d-none');
                            if (value.call !== false) {
                                var joinMsg = value.isAdvisor ? youHaveCallWithParticipant : youHaveCallWithAdvisor,
                                    userName = value.isAdvisor ? '' : value.user.name;

                                callJoinMessage.text(joinMsg);
                                withUserName.text(userName);
                                entityId = value.call.id;
                                joinURL = value.isAdvisor ? callStartAndJoinURL : callJoinURL;
                                waitingRoomURL = '/waiting-room/calls/' + entityId;
                                isAdvisor = value.isAdvisor;
                                joinCallBtn.removeClass('d-none');

                                // Hide the join button inside the waiting room
                                if (document.URL.indexOf("waiting-room") >= 0 && isAdvisor === false && window.isCountdownActive === true) {
                                    $('.join-container').hide();
                                }
                            }
                        };

                    ping.subscribe('upcomingOrCurrentCall', before, afterCall);
                    ping.subscribe('upcomingOrCurrentWorkshop', before, afterWorkshop);


                    joinBtn.on('click', function(e){
                        e.preventDefault();
                        $.ajax({
                            url: joinURL,
                            data: {
                                id: entityId
                            },
                            beforeSend: function() {

                            },
                            success: function( data ) {
                                if ( data.info !== 'success' ) {
                                    if (document.URL.indexOf("waiting-room") < 0) {
                                            window.location.href = waitingRoomURL;
                                            return;
                                    }

                                    toast('danger', Workshop.labels.error_title, data.payload.msg);
                                    return;
                                }

                                setTimeout(function() {
                                    if (isAdvisor) {
                                        window.location.href = data.payload.Advisor_url;
                                        return;
                                    }

                                    window.location.href = data.payload.JoinMeetingURL;
                                });
                            },
                            complete: function() {

                            }
                        });

                    });

                </script>
            </define>
            <define header_css>
                <style>
                    .header-join-btn {
                        min-width: 25px;
                        min-height: 60px;
                        font-family: 'Nunito', sans-serif;
                        font-size: 22px;
                        text-transform: uppercase;
                        letter-spacing: 1.3px;
                        font-weight: 700;
                        color: white;
                        background: linear-gradient(90deg, rgb(133 249 0 / 72%) 0%, rgb(106 232 14 / 86%) 100%);
                        border: none;
                        border-radius: 50%;
                        position: relative;
                        top: 7px;
                        padding: 10px;
                        box-shadow: 0 0 20px 4px #567f4b;
                        transition: all 0.3s ease-in-out 0s;

                    }

                    .header-join-btn::before {
                        content: "";
                        border: 2px solid rgb(133 249 0 / 72%);
                        width: 90px;
                        height: 80px;
                        border-radius: 50%;
                        animation: pulse 1s linear infinite;
                        position: absolute;
                        z-index: -1;
                        top: -12%;
                        left: -7%;
                        opacity: .05
                    }

                    .header-join-btn::after {
                        content: "";
                        border: 2px solid rgb(133 249 0 / 72%);
                        width: 90px;
                        height: 80px;
                        border-radius: 50%;
                        animation: pulse 1s linear infinite;
                        animation-delay: 0.3s;
                        position: absolute;
                        z-index: -1;
                        top: -12%;
                        left: -7%;
                        opacity: .05
                    }

                    @keyframes pulse {
                        0% {
                            transform: scale(0.5);
                            opacity: 0
                        }

                        50% {
                            transform: scale(1);
                            opacity: 1
                        }

                        100% {
                            transform: scale(1.3);
                            opacity: 0
                        }
                    }

                    .header-join-btn:hover, .header-join-btn:focus {
                        transform: translateY(-6px);
                    }
                </style>

                <style>
                    .join-container {
                        width: 27%;
                        height: 80px;
                        background-color: #333333;
                        border-radius: 8px;
                        position: absolute;
                        left: 37%;
                        top: 103%;
                        box-shadow: inset 1px -1px 7px 2px;
                        z-index: 300;
                    }

                    .join-info {
                        text-align: center;
                        width: 66%;
                        height: 100%;
                        float: left;
                        position: relative;
                        font-size: .9rem;
                        color: white;
                        font-weight: bolder;
                        line-height: 1;
                        top: 22%;
                    }

                    .join-control {
                        text-align: center;
                        border-radius: 8px;
                        width: 33%;
                        height: 100%;
                        float: left;
                        position: relative;
                        line-height: 2;
                    }

                    .clearfix {
                        clear: both;

                    }

                    @media only screen and (max-width: 800px) {
                        .join-container {
                            width: 50% !important;
                            left: 25% !important;

                        }

                        .mobile-only {
                            display: block !important;
                        }
                    }

                    @media only screen and (max-width: 500px) {
                        .join-container {
                            width: 85% !important;
                            left: 8% !important;

                        }

                        .mobile-only {
                            display: block !important;
                        }
                    }

                    @media only screen and (max-width: 360px) {
                        #notification-arrow {
                            padding: 0px;

                        }
                    }
                </style>
                <style>
                    /* Add styles for the floating buttons */
                    .floating-buttons {
                        position: fixed;
                        bottom: 20px;
                        right: 20px;
                        z-index: 1000;
                    }

                    .floating-button {
                        display: block;
                        margin-right: 10px;
                        background-color: #3f4aaa;
                        color: #ffffff;
                        padding: 10px;
                        border-radius: 50%;
                        text-align: center;
                        cursor: pointer;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                        margin-bottom: 4px;
                    }

                    .lang-ar .floating-buttons {
                        position: fixed;
                        bottom: 20px;
                        left: 20px;
                        right: unset;
                        z-index: 1000;
                    }
                </style>
            </define>