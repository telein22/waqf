<?php

use System\Core\Model;
use System\Core\Request;
use System\Helpers\URL;
use System\Responses\View;

$lang = Model::get("\Application\Models\Language");
$currentLang =  $lang->current();

/**
 * @var \Application\Models\User
 */
$userM = Model::get("\Application\Models\User");
?>

<!DOCTYPE html>
<html lang="<? $currentLang ?>">
<!-- Start head -->

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />



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




    <link rel="shortcut icon" href="<?= URL::asset('Application/Assets/images/favicon.ico') ?>" />
    <title> </title>
    <!-- bootstrap included -->
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Outer/home/css/bootstrap.min.css') ?>" />
    <!-- font Awesome  library-->
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Outer/home/css/all.min.css') ?>" />
    <!-- slick slider -->
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Outer/home/css/slick-theme.css') ?>" />
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Outer/home/css/slick.css') ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
          integrity="sha512-1cK78a1o+ht2JcaW6g8OXYwqpev9+6GqOkz9xmBN9iUUhIndKtxwILGWYOSibOKjLsEdjyjZvYDq/cZwNeak0w=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- start css file -->
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Outer/home/css/style.css') ?>" />
    <!-- start responsive -->
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Outer/home/css/responsive.css') ?>" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <!-- Toaster CSS -->
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/css/toasterjs.css') ?>">
    <link rel="stylesheet" href="<?= URL::asset('Application/Assets/css/jquery.toast.min.css') ?>">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title><?= $lang('welcome_title') ?></title>

    <call header_css />
</head>
<!-- start body -->

<body>

<!-- start header -->
<header>
    <!-- start navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= URL::full("/"); ?>">
                <img src="<?= URL::asset('Application/Assets/Outer/home/images/logo.svg') ?>" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?= URL::full("/"); ?>">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#discover">اكتشف</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#highest-rated">المتخصصون</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#our-services">خدمتنا</a>
                    </li>
                    <?php if (!empty($availableWorkshops)): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#sessions">الجلسات</a>
                        </li>
                    <?php endif ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#about-us">من نحن</a>
                    </li>
                </ul>
                <ul class="nav_left d-lg-flex">
                    <?php if ($userM->isLoggedIn()) : ?>
                        <?php if ($_SERVER['REQUEST_URI'] !== '/verify-account') : ?>
                            <li><a href="<?= URL::full("feeds"); ?>"><?= $lang("back_to_my_account") ?></a></li>
                        <?php endif; ?>
                        <li><a href="<?= URL::full("logout"); ?>"><?= $lang("logout") ?></a></li>
                        <?php else : ?>
                        <li><a href="<?= URL::full("login"); ?>" class="btn">تسجيل دخول</a></li>
                        <li><a href="<?= URL::full("register"); ?>" class="btn btn-def">تسجيل جديد</a></li>
                    <?php endif; ?>
    <!--                <li><a href="#" class="btn btn-outline-def">English</a></li>-->
                </ul>
            </div>
        </div>
    </nav>

</header>

<define footer_js>
    <script>
        $('.nav-item a').on('click', function() {
            var section = $(this).attr('href');
            window.location.href = `<?= URL::full('/') ?>${section}`
        });
    </script>
</define>
