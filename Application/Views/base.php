<!doctype html>
<?php

use Application\Models\Language;
use System\Core\Config;
use System\Core\Model;
use System\Core\Request;
use System\Helpers\URL;
use System\Responses\View;

$lang = Model::get(Language::class);

$searchQ = Request::instance()->get('q');

/**
 * @var \Application\Models\User
 */
$userM = Model::get("\Application\Models\User");

$config = Config::get("Website");
$number = $config->whatsapp_number;
?>
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


   <title><?= isset($title) ? htmlentities($title) : 'teleIn'; ?></title>
   <!-- Favicon -->
   <!-- <link rel="shortcut icon" href="<?php // echo URL::asset('Application/Assets/images/favicon.ico') 
                                          ?>" /> -->
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
   <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Outer/css/style2.css') ?>">
   <!-- Toaster CSS -->
   <link rel="stylesheet" href="<?= URL::asset('Application/Assets/css/toasterjs.css') ?>">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

   <!-- Font Awesome -->
   <link rel="stylesheet" href="<?= URL::asset('Application/Assets/Admin/plugins/fontawesome-free/css/all.min.css') ?>">

   <link rel="stylesheet" href="<?= URL::asset('Application/Assets/css/custom.css') ?>">
   <link rel="stylesheet" href="<?= URL::asset('Application/Assets/css/cropper.css') ?>">

   <style>
      @media screen and (max-width: 768px) {
         #navbarSupportedContent {
            top: 62px;
            flex-direction: column;
            justify-content: center;
            align-items: center;
         }

         .navbar-light .search-form {
            margin-right: 0;
         }
      }
   </style>

   <call header_css />

   <style>
      .form-control {
         height: unset !important;
      }
   </style>
</head>

<body class="right-column-fixed sidebar-main-active lang-<?= $lang->current() ?>" <?= $lang->current() == 'ar' ? 'dir="rtl"' : '' ?>>
   <header>
      <div class="container-fluid">
         <nav class="navbar fixed-top navbar-expand-lg navbar-light">

            <a class="navbar-brand" href="<?= URL::full('') ?>">
               <img src="<?= URL::asset('\Application\Assets\images\logo.svg') ?>" class="img-fluid" width="120" alt="">
            </a>
            <div class="menu">
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
               </button>
               <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <form class="d-flex search-form" method="GET" action="<?= URL::full('search'); ?>">
                     <input class="form-control me-2" type="text" placeholder="<?= $lang('search_placeholder') ?>" aria-label="Search" name="q" value="<?= $searchQ ?>">
                     <button class="btn btn-outline" type="submit"><i class="fa fa-search"></i></button>
                  </form>
                  <div class="form-group outer-lang-change mb-0">
                     <select name="lang" id="langHeader" class="form-control">
                        <option <?php if ($lang->current() == 'en') echo 'selected' ?> value="en">English</option>
                        <option <?php if ($lang->current() == 'ar') echo 'selected' ?> value="ar">عربي</option>
                     </select>
                  </div>
                  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                     <?php if ($userM->isLoggedIn()) : ?>
                        <li class="nav-item">
                           <a class="nav-link btn-main" href="<?= URL::full("logout"); ?>"><?= $lang("logout") ?></a>
                        </li>
                     <?php else : ?>
                        <li class="nav-item">
                           <a class="nav-link" href="<?= URL::full("login"); ?>"><?= $lang("sign_in") ?></a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link btn-main" href="<?= URL::full("register"); ?>"><?= $lang("sign_up") ?></a>
                        </li>
                     <?php endif; ?>
                     <!-- <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-toggle="dropdown" aria-expanded="false">
                                    Dropdown
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                </ul>
                            </li> -->
                  </ul>

               </div>
            </div>
         </nav>
      </div>
   </header>
   <?php View::include('preloader') ?>
   <?= $content; ?>

   <footer class="bg-white iq-footer">
      <div class="container">
         <div class="row">
            <div class="col-lg-4 md-text-center sm-mb-2">
               <img width="200" src="<?= URL::asset('Application/Assets/images/footer-pay.jpeg') ?>" alt="">
               <ul class="list-inline mb-0">
                  <li class="list-inline-item"><a href="<?= URL::full('terms') ?>"><?= $lang('privacy_policy') ?></a></li>
                  <li class="list-inline-item"><a href="<?= URL::full('terms') ?>"><?= $lang('terms_of_use') ?></a></li>
               </ul>
            </div>
            <div class="col-lg-4 text-center d-flex align-items-end justify-content-center">
               <p><?= date('d-m-Y H:i') ?></p>
            </div>
            <div class="col-lg-4 text-right md-text-center footer-social">
               <ul class="list-inline mb-0 ">
                  <li class="list-inline-item">
                     <a target="_blank" href="https://t.me/TeleInTelegram"><i class="fab fa-telegram-plane"></i></a>
                  </li>
                  <li class="list-inline-item">
                     <a target="_blank" href=" https://mobile.twitter.com/telein_"><i class="fab fa-twitter"></i></a>
                  </li>
                  <li class="list-inline-item">
                     <a target="_blank" href="https://www.linkedin.com/company/telein-net/"><i class="fab fa-linkedin-in"></i></a>
                  </li>
                  <li class="list-inline-item">
                     <a target="_blank" href="<?= "https://api.whatsapp.com/send/?phone=" . $number . "&text=Hi&app_absent=0"; ?>"><i class="fab fa-whatsapp"></i></a>
                  </li>
                  <li class="list-inline-item">
                     <a target="_blank" href="https://www.instagram.com/tele.in/?igshid=YmMyMTA2M2Y%3D"><i class="fab fa-instagram"></i></a>
                  </li>
                  <li class="list-inline-item">
                     <a target="_blank" href="https://www.youtube.com/channel/UCgBlPTjzikIY4eeMPIH1Irg"><i class="fab fa-youtube"></i></a>
                  </li>
               </ul>
               <?= $lang('copyright', ['date' => date('Y')]) ?>
            </div>
         </div>
      </div>
   </footer>

   <!-- Wrapper END -->
   <!-- Footer -->
   <!-- <footer class="bg-white iq-footer">
   <div class="container">
      <div class="row">
         <div class="col-lg-6">
            <ul class="list-inline mb-0">
               <li class="list-inline-item"><a href="privacy-policy.html">Privacy Policy</a></li>
               <li class="list-inline-item"><a href="terms-of-service.html">Terms of Use</a></li>
            </ul>
         </div>
         <div class="col-lg-6 text-right">
            Copyright 2020 <a href="#">SocialV</a> All Rights Reserved.
         </div>
      </div>
   </div>
</footer> -->
   <!-- Footer END -->

   <script>
      var URLS = {
         more_feed: '<?= URL::full('ajax/outer-feed-more'); ?>',
         more_feed_comment: '<?= URL::full('ajax/outer-feed-more-comment'); ?>',
         more_feed_media: '<?= URL::full('ajax/outer-feed-more-media'); ?>',
         more_feed_liked: '<?= URL::full('ajax/outer-feed-more-liked'); ?>',
         more_feed_profile: '<?= URL::full('ajax/outer-feed-more-profile'); ?>',
      };
   </script>
   <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="<?= URL::asset('Application/Assets/js/jquery.min.js'); ?>"></script>
   <script src="<?= URL::asset('Application/Assets/js/popper.min.js'); ?>"></script>

   <!-- Insert bootstrap code based on user loggedin because outer side uses other login -->
   <?php if ($userM->isLoggedIn()) : ?>
      <script src="<?= URL::asset('Application/Assets/js/bootstrap.min.js'); ?>"></script>
   <?php else : ?>
<!--      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>-->
       <script src="<?= URL::asset('Application/Assets/js/bootstrap.min.js'); ?>"></script>
   <?php endif; ?>
   <!-- Appear JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/jquery.appear.js'); ?>"></script>
   <!-- Countdown JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/countdown.min.js'); ?>"></script>
   <!-- Counterup JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/waypoints.min.js'); ?>"></script>
   <script src="<?= URL::asset('Application/Assets/js/jquery.counterup.min.js'); ?>"></script>
   <!-- Wow JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/wow.min.js'); ?>"></script>
   <!-- Apexcharts JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/apexcharts.js'); ?>"></script>
   <!-- Slick JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/slick.min.js'); ?>"></script>
   <!-- Select2 JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/select2.min.js'); ?>"></script>
   <!-- Owl Carousel JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/owl.carousel.min.js'); ?>"></script>
   <!-- Magnific Popup JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/jquery.magnific-popup.min.js'); ?>"></script>
   <!-- Smooth Scrollbar JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/smooth-scrollbar.js'); ?>"></script>
   <!-- lottie JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/lottie.js'); ?>"></script>
   <!-- am core JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/core.js'); ?>"></script>
   <!-- am charts JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/charts.js'); ?>"></script>
   <!-- am animated JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/animated.js'); ?>"></script>
   <!-- am kelly JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/kelly.js'); ?>"></script>
   <!-- am maps JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/maps.js'); ?>"></script>
   <!-- am worldLow JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/worldLow.js'); ?>"></script>
   <!-- Chart Custom JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/chart-custom.js'); ?>"></script>
   <!-- Bootbox javascript -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>

   <script src="<?= URL::asset('Application/Assets/js/cleave.js'); ?>"></script>

   <!-- Custom JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/custom.js'); ?>"></script>
   <script src="<?= URL::asset('Application/Assets/js/cropper.js'); ?>"></script>
   <!-- Toaster JavaScript -->
   <script src="<?= URL::asset('Application/Assets/js/toasterjs-umd.js'); ?>"></script>



   <script>
      function toggleMenu() {
         alert(2);
      }

      cConfirm.labels.yes = '<?= $lang('yes') ?>';
      cConfirm.labels.no = '<?= $lang('no') ?>';

      // Workshop labels
      Workshop.labels.error_title = '<?= $lang('error') ?>';
      Workshop.labels.error_cancel_confirm = '<?= $lang('workshop_error_cancel_confirm') ?>';
      Workshop.labels.cancel_title = '<?= $lang('are_you_sure') ?>';
      Workshop.labels.cancel_placeholder = '<?= $lang('workshop_cancel_placeholder') ?>';
      Workshop.labels.yes = '<?= $lang('yes') ?>';
      Workshop.labels.no = '<?= $lang('no') ?>';

      Call.labels.error_title = '<?= $lang('error') ?>';
      Call.labels.error_cancel_confirm = '<?= $lang('workshop_error_cancel_confirm') ?>';
      Call.labels.cancel_title = '<?= $lang('are_you_sure') ?>';
      Call.labels.cancel_placeholder = '<?= $lang('workshop_cancel_placeholder') ?>';
      Call.labels.yes = '<?= $lang('yes') ?>';
      Call.labels.no = '<?= $lang('no') ?>';
   </script>

   <script>
      $("#langHeader").on('change', function(e) {
         var lang = $(this).val();
         var url = '<?= URL::full('/language/change') ?>?lang=' + lang + '&url=' + btoa(window.location.href);

         window.location.href = url;
         return;

         // $.ajax({
         //     url: '<?= URL::full('/ajax/change-lang-cookie'); ?>',
         //     type: 'POST',
         //     dataType: 'JSON',
         //     accepts: 'JSON',
         //     data: {
         //         lang: lang
         //     },
         //     success: function(data) {
         //         // window.location.reload();
         //     },
         //     complete: function() {

         //     }
         // });
      })

      $("#instruction").modal('show');
   </script>

   <call footer_js />

</body>

</html>