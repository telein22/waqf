<?php

use Application\Models\Language;
use System\Core\Config;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get(Language::class);

$config = Config::get("Website");
$number = $config->whatsapp_number;
?>

</main>
<!-- start footer -->
<footer id="about-us">
    <div class="container-fluid" data-aos="fade-up">
        <div class="row">
            <div class="col-lg-5">
                <a href="<?= URL::full("/"); ?>">
                    <img src="<?= URL::asset("Application/Assets/Outer/home/images/logo.svg") ?>" class="img-fluid" alt="Footer">
                </a>
                <p class="mb-0">منصة تواصل اجتماعي تمكن مختلف أفراد المجتمع من التواصل الفعّال فيما بينهم في مختلف المجالات والخبرات.
                    يستطيع المستخدم التواصل مع أصحاب الخبرات والإستفادة من حسابه الشخصي كمختص عن طريق فتح قناة للتواصل مع الآخرين.
                    كما تمكن المنصة الجهات الحكومية والخاصة والجمعيات الخيرية من الإستفادة مع منسوبيها وذوي الخبرات بتقديم خدماتهم على أوسع نطاق.</p>
            </div>
            <div class="col-lg-3 mb-4 mb-lg-0">
                <h5 class="mb-4">روابط هامة</h5>
                <ul class="list-unstyled">
                    <li><a href="<?= URL::full('terms') ?>">شروط الاستخدام</a></li>
                    <li><a href="<?= URL::full('faq') ?>">كيفية الاستخدام</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
<!--                <h5 class="mb-2 ">القائمة الاخبارية</h5>-->
<!--                <p class="mb-3">اشترك ليصلك كل الجديد</p>-->
<!--                <form class="mb-4">-->
<!--                    <div class="input-group p-2 mb-3 rounded-pill"-->
<!--                         style="background-color: #d0d0d032;    align-items: flex-start;">-->
<!--                        <input type="email" class="form-control rounded-0 border-0 bg-transparent"-->
<!--                               placeholder="البريد الإلكتروني ">-->
<!--                        <button class="btn btn-def rounded-circle border-0" type="submit" style="    width: 20px;-->
<!--                  height: 20px;-->
<!--                  display: flex;-->
<!--                  justify-content: center;-->
<!--                  align-items: center;-->
<!--                  min-height: unset;-->
<!--    padding: 20px;"><i class="fa-solid fa-paper-plane"></i></button>-->
<!--                    </div>-->
<!--                </form>-->
                <h5 class="mb-2 ">التواصل الاجتماعي</h5>
                <ul class="social-foot mb-2">
                    <li><a href="https://www.youtube.com/channel/UCgBlPTjzikIY4eeMPIH1Irg" target="_blank"><i class="fa-brands fa-youtube"></i></a></li>
                    <li><a href="<?= "https://api.whatsapp.com/send/?phone=" . $number . "&text=Hi&app_absent=0"; ?>" target="_blank"><i class="fa-brands fa-whatsapp"></i></a></li>
                    <li><a href="https://mobile.twitter.com/telein_" target="_blank"><i class="fa-brands fa-twitter"></i></a></li>
                    <li><a href="https://www.instagram.com/tele.in/?igshid=YmMyMTA2M2Y%3D" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                    <li><a href="https://www.linkedin.com/company/telein-net" target="_blank"><i class="fa-brands fa-linkedin"></i></a></li>
                </ul>
                <a href="#"><img src="assets/images/footer-pay.jpeg.png" alt="">
                </a>
            </div>
        </div>
    </div>
</footer>
<div class="sub-footer py-4 border-top text-center">
    <p>جميع الحقوق محفوظة لمنصة تيلي ان 2023</p>
</div>







<!-- start scripts included -->
<!-- bootstrap included -->
<script src="<?= URL::asset("Application/Assets/Outer/home/js/bootstrap.bundle.js") ?>"></script>
<!-- jquery included -->
<script src="<?= URL::asset("Application/Assets/Outer/home/js/jquery.js") ?>"></script>
<script>
    $('.stat-number').each(function () {
        var size = $(this).text().split(".")[1] ? $(this).text().split(".")[1].length : 0;
        $(this).prop('Counter', 0).animate({
            Counter: $(this).text()
        }, {
            duration: 3000,
            step: function (func) {
                $(this).text(parseFloat(func).toFixed(size));
            }
        });
    });
</script>

<!-- font awsome included -->
<script src="<?= URL::asset("Application/Assets/Outer/home/js/all.min.js")?>"></script>
<!-- MY code included -->
<script src="<?= URL::asset("Application/Assets/Outer/home/js/code.js")?>"></script>
<script src="<?= URL::asset("Application/Assets/Outer/home/js/slick.min.js") ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"
        integrity="sha512-A7AYk1fGKX6S2SsHywmPkrnzTZHrgiVT7GcQkLGDe2ev0aWb8zejytzS8wjo7PGEXKqJOrjQ4oORtnimIRZBtw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    AOS.init({
        duration: 1200,
    })
</script>
<script>
    $('.big_slider').slick({
        draggable: true,
        autoplay: true,
        autoplaySpeed: 5000,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false,
        arrows: true,
        rtl: true,
        speed: 300,
        prevArrow: "<img class='a-left control-c prev slick-prev' src='<?= URL::asset("Application/Assets/Outer/home/images/left2.svg") ?>'>",
        nextArrow: "<img class='a-right control-c next slick-next' src='<?= URL::asset("Application/Assets/Outer/home/images/right2.svg") ?>'>",

        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: false,
                    arrows: true,

                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: false,
                    arrows: true,


                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: false,
                    arrows: true,


                }
            }

        ]
    });
</script>
<script>
    $('.responsive').slick({

        dots: true,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 5000,

        speed: 300,
        slidesToShow: 5,
        slidesToScroll: 5,
        prevArrow: "<img class='a-left control-c prev slick-prev' src='<?= URL::asset("Application/Assets/Outer/home/images/left.svg") ?>'>",
        nextArrow: "<img class='a-right control-c next slick-next' src='<?= URL::asset("Application/Assets/Outer/home/images/right.svg") ?>'>",

        rtl: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: true,
                    arrows: true,
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    dots: true,
                    arrows: true,

                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    dots: true,
                    arrows: true,

                }
            }

        ]
    });
</script>
<script>
    $('.responsive2').slick({
        dots: true,
        arrows: true,

        infinite: true,
        autoplay: true,
        autoplaySpeed: 5000,

        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 4,
        prevArrow: "<img class='a-left control-c prev slick-prev' src='<?= URL::asset("Application/Assets/Outer/home/images/left.svg") ?>'>",
        nextArrow: "<img class='a-right control-c next slick-next' src='<?= URL::asset("Application/Assets/Outer/home/images/right.svg") ?>'>",

        rtl: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    infinite: true,
                    dots: true,
                    arrows: true,
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    dots: true,
                    arrows: true,

                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    dots: true,
                    arrows: true,

                }
            }

        ]
    });
</script>
<script>
    $('.responsive22').slick({
        dots: true,
        // arrows: true,

        infinite: true,
        autoplay: true,
        autoplaySpeed: 5000,

        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 3,
        prevArrow: "<img class='a-left control-c prev slick-prev' src='<?= URL::asset("Application/Assets/Outer/home/images/left.svg") ?>'>",
        nextArrow: "<img class='a-right control-c next slick-next' src='<?= URL::asset("Application/Assets/Outer/home/images/right.svg") ?>'>",

        rtl: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    infinite: true,
                    dots: true,
                    arrows: true,
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: true,
                    arrows: true,

                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: true,
                    arrows: true,

                }
            }

        ]
    });
</script>
<script>
    $('.responsive3').slick({
        dots: true,

        infinite: true,
        autoplay: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        prevArrow: "<img class='a-left control-c prev slick-prev' src='<?= URL::asset("Application/Assets/Outer/home/images/left.svg") ?>'>",
        nextArrow: "<img class='a-right control-c next slick-next' src='<?= URL::asset("Application/Assets/Outer/home/images/right.svg") ?>'>",

        rtl: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    infinite: true,
                    dots: true,
                    arrows: true,
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: true,
                    arrows: true,

                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: true,
                    arrows: true,

                }
            }

        ]
    });

</script>
<script>
    $('.small-slider').slick({
        rtl: true,
        arrows: true,

        dots: true,
        infinite: false,
        // autoplay: true,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: "<img class='sm_slider_btn_prev a-left control-c prev slick-prev' src='<?= URL::asset("Application/Assets/Outer/home/images/left.svg") ?>'>",
        nextArrow: "<img class='sm_slider_btn_next a-right control-c next slick-next' src='<?= URL::asset("Application/Assets/Outer/home/images/right.svg") ?>'>",

        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true,
                    arrows: true,
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: true,
                    arrows: true,

                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: true,
                    arrows: true,

                }
            }

        ]
    });
    //
    $('a[data-bs-toggle="pill"]').on("shown.bs.tab", function (e) {
        $(".small-slider").slick("setPosition");
    });

</script>

<script>
    $('.responsive4').slick({
        dots: true,

        infinite: true,
        autoplay: true,
        autoplaySpeed: 5000,

        speed: 300,
        slidesToShow: 6,
        slidesToScroll: 6,
        prevArrow: "<img class='a-left control-c prev slick-prev' src='<?= URL::asset("Application/Assets/Outer/home/images/left.svg") ?>'>",
        nextArrow: "<img class='a-right control-c next slick-next' src='<?= URL::asset("Application/Assets/Outer/home/images/right.svg") ?>'>",

        rtl: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4,
                    infinite: true,
                    dots: true,
                    arrows: true,
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    dots: true,
                    arrows: true,

                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    dots: true,
                    arrows: true,

                }
            }

        ]
    });
</script>
<script>
    var URLS = {
        specialty_get_sub_specialties: '<?= URL::full('ajax/sub-specialty') ?>',
        support: '<?= URL::full('ajax/contact-with-us') ?>',
        search: '<?= URL::full('ajax/search'); ?>',
        user_search: '<?= URL::full('ajax/user/search'); ?>',
        search_by_spec: '<?= URL::full('ajax/user/search-by-spec'); ?>',
        auth_resend_otp: '<?= URL::full('ajax/auth/resend-otp'); ?>',
        find_more_workshop: '<?= URL::full('ajax/workshop/find-more'); ?>'
    };
</script>

<!-- Toaster JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/toasterjs-umd.js'); ?>"></script>
<script src="<?= URL::asset('Application/Assets/js/jquery.toast.min.js'); ?>"></script>




<call footer_js />
</body>
<!-- end of body -->

</html>
<!-- end of code -->