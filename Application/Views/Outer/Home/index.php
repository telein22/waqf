<?php

use Application\Models\Language;
use System\Core\Model;
use System\Core\Config;
use System\Helpers\URL;
use System\Responses\View;
use Application\Helpers\AppHelper;

$lang = Model::get(Language::class);
?>

<!-- Hero -->
<section class="hero">
    <div class="big_slider " data-aos="fade-up">
        <div class="hero_slide">
            <img alt="" width="100%" src="<?= URL::asset('Application/Assets/Outer/home/images/slider/1.png') ?>">
        </div>
        <div class="hero_slide">
            <img alt="" width="100%" src="<?= URL::asset('Application/Assets/Outer/home/images/slider/3.png') ?>">
        </div>
        <div class="hero_slide">
            <img alt="" width="100%" src="<?= URL::asset('Application/Assets/Outer/home/images/slider/4.png') ?>">
        </div>
        <div class="hero_slide">
            <img alt="" width="100%" src="<?= URL::asset('Application/Assets/Outer/home/images/slider/2.png') ?>">
        </div>
    </div>
</section>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="search_header shadow " data-aos="fade-up" id="our-services">
                <ul class="nav nav-tabs border-0 nav-fill" id="myTab" role="tablist" style="width: 100%;
                  ">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                type="button" role="tab" aria-controls="home" aria-selected="true"><i
                                    class="fa-solid fa-check"></i> حدد رسوم التواصل معك </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                type="button" role="tab" aria-controls="profile" aria-selected="false"><i
                                    class="fa-solid fa-phone-flip"></i> تواصل مع
                            المستخدمين</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                type="button" role="tab" aria-controls="contact" aria-selected="false"><i
                                    class="fa-solid fa-message"></i> تواصل مع الجهات
                            المشاركة</button>
                    </li>
                </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="mt-2 p-3  row align-items-center cust_btn">
                    <ul class="nav nav-tabs nav-fill border-0" id="my-custom-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link  btn-outline-def" id="custom-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#custom-profile" type="button" role="tab"
                                    aria-controls="custom-profile" aria-selected="false">الرد علي مكالماتك</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link  btn-outline-def" id="custom-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#custom-contact" type="button" role="tab"
                                    aria-controls="custom-contact" aria-selected="false">رسائلكم</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link  btn-outline-def" id="custom-contact-tab2" data-bs-toggle="tab"
                                    data-bs-target="#custom-contact2" type="button" role="tab"
                                    aria-controls="custom-contact2" aria-selected="false">لحضور جلساتك</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="my-custom-tabs-content">
                        <div class="tab-pane fade" id="custom-profile" role="tabpanel"
                             aria-labelledby="custom-profile-tab">
                            <div class="p-3 text-secondary" style="font-size:12px">
                                يستطيع متابعينك حجز مكالمه معك حسب الاوقات التي تجدولها في حسابك لمدة ربع ساعه لكل مكالمه وذلك بمقابل مادي تحدده، ولكي تستفيد من هذه الخدمة يجب عليك التسجيل واكمال ملفك التعريفي اولاً ...
                                <a href="<?= URL::full('register')?>">سجل الآن</a>  واجعل لك مصدر دخل اضافي جديد معنا
                            </div>

                        </div>
                        <div class="tab-pane fade" id="custom-contact" role="tabpanel"
                             aria-labelledby="custom-contact-tab">
                            <div class="p-3 text-secondary" style="font-size:12px">
                                يستطيع متابعينك ارسال رسائل كتابية او صوتيه لك وذلك بمقابل مادي انت تحدده للرد على تلك الرسائل خلال مدة لا تتجاوز ٤٨ ساعه من استقبال الرسالة المدفوعه لك، ولكي تستفيد من هذه الخدمة يجب عليك التسجيل واكمال ملفك التعريفي اولاً ...
                                <a href="<?= URL::full('register')?>">سجل الآن</a>  واجعل لك مصدر دخل اضافي جديد معنا
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-contact2" role="tabpanel"
                             aria-labelledby="custom-contact-tab2">
                            <div class="p-3 text-secondary" style="font-size:12px">
                                يستطيع متابعينك الحجز لحضور جلسة او ندوه تنشئها على حسابك وذلك بمقابل مادي تحدده، ولكي تستفيد من هذه الخدمة يجب عليك التسجيل واكمال ملفك التعريفي اولاً ...
                                <a href="<?= URL::full('register')?>">سجل الآن</a>  واجعل لك مصدر دخل اضافي جديد معنا
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <form class=" p-3 row align-items-center cust_col" action="/">
                    <div class="col-lg-3">
                        <label class="form-label">التخصص</label>
                        <select class="form-select" aria-label="Default select example" id="spec" name="spec">
                            <option value="0" selected>اختر اسم التخصص</option>
                            <?php foreach ($specialties as $spec) : ?>
                                <option value="<?= $spec['id']; ?>"><?= htmlentities($spec['specialty_' . $lang->current()]); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label">التخصص الدقيق</label>
                        <select class="form-select" aria-label="Default select example" id="sub-spec" name="sub_spec">
                            <option value="0" selected>اختر اسم التخصص الدقيق</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="exampleInputEmail1" class="form-label">او ابحث بالاسم</label>
                        <input type="search" class="form-control" id="inputSearch" name="q">

                    </div>
                    <div class="col-lg-2 d-lg-flex justify-content-center">
                        <div class="">
                            <h3>&nbsp;</h3>
                            <button type="submit" class="btn btn-def btn_w" id="btn-search">بحث</button>
                        </div>

                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

                <div class="mt-2 p-3  row align-items-center cust_btn">
                    <ul class="nav nav-tabs nav-fill border-0" id="my-custom-tabs2" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link  btn-outline-def" id="governmental-tab" data-bs-toggle="tab"
                                    data-bs-target="#governmental" type="button" role="tab"
                                    aria-controls="governmental" aria-selected="false">جمعيات حكومية</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link  btn-outline-def" id="national-tab" data-bs-toggle="tab"
                                    data-bs-target="#national" type="button" role="tab"
                                    aria-controls="national" aria-selected="false">جمعيات أهلية</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link  btn-outline-def" id="charity-tab" data-bs-toggle="tab"
                                    data-bs-target="#charity" type="button" role="tab"
                                    aria-controls="charity" aria-selected="false">جمعيات خيرية</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="my-custom-tabs-content">
                        <div class="tab-pane fade" id="governmental" role="tabpanel"
                             aria-labelledby="governmental-tab">
                            <div class="p-3 text-secondary" style="font-size:12px">

                            </div>

                        </div>
                        <div class="tab-pane fade" id="national" role="tabpanel"
                             aria-labelledby="national-tab">
                            <div class="p-3 text-secondary" style="font-size:12px">

                            </div>
                        </div>
                        <div class="tab-pane fade" id="charity" role="tabpanel"
                             aria-labelledby="charity-tab2">
                            <div class="p-3 text-secondary" style="font-size:12px">

                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>

        </div>
    </div>
</div>

<main>

    <!-- Search result -->
    <?php if($section == 'search') : ?>
        <?php if(empty($users)): ?>
            <div class="rating search-result">
                <div class="container" data-aos="fade-up">
                    لايوجد نتائج...
                </div>
            </div>
        <?php else: ?>
            <div class="rating search-result">
                <div class="container" data-aos="fade-up">
                    <div class="responsive2" id="users-container">
                        <?php foreach($users as $user): ?>
                            <?php
                            View::include('Outer/Home/user', [
                                'user' => $user,
                                'isLoggedIn' => $isLoggedIn
                            ]);
                            ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <!-- Search result -->



<!-- start booking -->
<div class="booking_box" id="discover">
    <div class="container" data-aos="fade-up">
        <div class="heading_section" >
            <h2>استكشف</h2>
            <p>استكشف التخصص الذي يناسبك</p>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="responsive">





                    <a href="/specialties/Sciences" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <svg width="60" height="70" viewBox="0 0 60 70" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="Group">
                                    <path id="Vector"
                                        d="M29.9999 56.3239C30.753 56.3239 31.3636 55.7134 31.3636 54.9603C31.3636 54.2072 30.753 53.5966 29.9999 53.5966C29.2468 53.5966 28.6363 54.2072 28.6363 54.9603C28.6363 55.7134 29.2468 56.3239 29.9999 56.3239Z" />
                                    <path id="Vector_2"
                                        d="M29.9999 69.8239C30.753 69.8239 31.3636 69.2134 31.3636 68.4603C31.3636 67.7072 30.753 67.0966 29.9999 67.0966C29.2468 67.0966 28.6363 67.7072 28.6363 68.4603C28.6363 69.2134 29.2468 69.8239 29.9999 69.8239Z" />
                                    <path id="Vector_3"
                                        d="M36.1363 67.0967C35.3833 67.0967 34.7727 67.7073 34.7727 68.4603C34.7727 69.2133 35.3833 69.8239 36.1363 69.8239H58.6364C59.3894 69.8239 60 69.2133 60 68.4603V64.3694C60 61.3617 57.5531 58.9148 54.5454 58.9148H39.5454V54.9603C39.5454 54.6855 39.5311 54.414 39.5082 54.1449C47.0192 50.5091 51.8182 42.9132 51.8182 34.5057C51.8182 29.9586 50.4538 25.6518 47.8625 21.9744L52.9897 16.8468C53.5222 16.3143 53.5222 15.4509 52.9897 14.9182L51.0616 12.99L55.9366 8.11379C56.4689 7.58129 56.4689 6.71811 55.9366 6.18547L50.1521 0.399546C49.7158 -0.0365456 49.2024 0.00300001 49.1877 0C48.8261 0 48.4793 0.143728 48.2235 0.39941L43.3472 5.27578L41.4192 3.34773C40.8867 2.81564 40.0231 2.81523 39.4906 3.34773C39.407 3.43133 19.4046 23.4337 19.242 23.5963C18.7097 24.1284 18.7095 24.9922 19.2419 25.5249L24.0625 30.3455L22.1341 32.2749C21.6017 32.8075 21.6018 33.671 22.1345 34.2034C22.6668 34.7356 23.5304 34.7357 24.0629 34.2029L25.991 32.2739L30.8125 37.0955C31.345 37.6279 32.2083 37.6281 32.741 37.0955C32.7549 37.0816 45.882 23.9551 45.9015 23.9357C47.9926 27.0646 49.091 30.6882 49.091 34.5057C49.091 41.6585 45.1222 48.1407 38.8615 51.419C37.4518 47.9049 34.0123 45.4153 30.0002 45.4148C29.9998 45.4148 29.9993 45.4148 29.9988 45.4148C26.0772 45.4148 22.7323 47.7722 21.231 51.4687C17.9707 49.7857 15.2869 47.2194 13.4614 44.0512H21.8182C22.5712 44.0512 23.1819 43.4406 23.1819 42.6875C23.1819 41.9344 22.5712 41.3239 21.8182 41.3239C19.3862 41.3239 3.47755 41.3239 1.36364 41.3239C0.610638 41.3239 0 41.9344 0 42.6875C0 43.4406 0.610638 44.0512 1.36364 44.0512H10.3763C12.5327 48.4863 16.0911 52.0308 20.5319 54.1674C20.4825 54.5754 20.4547 54.9863 20.4547 55.398V58.9148H5.45469C2.44705 58.9148 0.000136289 61.3617 0.000136289 64.3694V68.4603C0.000136289 69.2133 0.610774 69.8239 1.36378 69.8239H23.8638C24.6168 69.8239 25.2275 69.2133 25.2275 68.4603C25.2275 67.7073 24.6168 67.0967 23.8638 67.0967H2.72742V64.3694C2.72742 62.8656 3.95087 61.6421 5.45469 61.6421H21.8184C22.5714 61.6421 23.182 61.0316 23.182 60.2785V55.398C23.182 51.5914 25.9835 48.1421 29.999 48.1421H30.0001C33.7596 48.1426 36.8184 51.2013 36.8184 54.9603V60.2785C36.8184 61.0316 37.429 61.6421 38.182 61.6421H54.5457C56.0495 61.6421 57.273 62.8656 57.273 64.3694V67.0967H36.1363ZM49.1876 3.29237L53.0439 7.1497L49.133 11.0616L45.2757 7.20424L49.1876 3.29237ZM40.455 6.24056L50.0968 15.8826C49.9051 16.0743 37.6119 28.3678 37.5621 28.4176L27.9207 18.7761L40.455 6.24056ZM31.7769 34.2028L22.1347 24.5608L25.9921 20.7044L35.6335 30.346L31.7769 34.2028Z" />
                                </g>
                            </svg>

                        </div>

                        العلوم
                    </a>
                    <a href="/specialties/Computer Science" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">

                            <svg width="60" height="60" viewBox="0 0 60 60" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="045-keyboard" clip-path="url(#clip0_1_2532)">
                                    <g id="Group">
                                        <path id="Vector"
                                            d="M51.0938 9.72656C51.741 9.72656 52.2656 9.2019 52.2656 8.55469C52.2656 7.90748 51.741 7.38281 51.0938 7.38281C50.4465 7.38281 49.9219 7.90748 49.9219 8.55469C49.9219 9.2019 50.4465 9.72656 51.0938 9.72656Z" />
                                        <path id="Vector_2"
                                            d="M51.0938 16.7578C51.741 16.7578 52.2656 16.2331 52.2656 15.5859C52.2656 14.9387 51.741 14.4141 51.0938 14.4141C50.4465 14.4141 49.9219 14.9387 49.9219 15.5859C49.9219 16.2331 50.4465 16.7578 51.0938 16.7578Z" />
                                        <path id="Vector_3"
                                            d="M51.0938 23.7891C51.741 23.7891 52.2656 23.2644 52.2656 22.6172C52.2656 21.97 51.741 21.4453 51.0938 21.4453C50.4465 21.4453 49.9219 21.97 49.9219 22.6172C49.9219 23.2644 50.4465 23.7891 51.0938 23.7891Z" />
                                        <path id="Vector_4"
                                            d="M19.4531 9.72656H22.9688C23.6159 9.72656 24.1406 9.2018 24.1406 8.55469C24.1406 7.90758 23.6159 7.38281 22.9688 7.38281H19.4531C18.806 7.38281 18.2812 7.90758 18.2812 8.55469C18.2812 9.2018 18.806 9.72656 19.4531 9.72656Z" />
                                        <path id="Vector_5"
                                            d="M33.5156 7.38281H30C29.3529 7.38281 28.8281 7.90758 28.8281 8.55469C28.8281 9.2018 29.3529 9.72656 30 9.72656H33.5156C34.1627 9.72656 34.6875 9.2018 34.6875 8.55469C34.6875 7.90758 34.1627 7.38281 33.5156 7.38281Z" />
                                        <path id="Vector_6"
                                            d="M19.4531 16.7578H22.9688C23.6159 16.7578 24.1406 16.233 24.1406 15.5859C24.1406 14.9388 23.6159 14.4141 22.9688 14.4141H19.4531C18.806 14.4141 18.2812 14.9388 18.2812 15.5859C18.2812 16.233 18.806 16.7578 19.4531 16.7578Z" />
                                        <path id="Vector_7"
                                            d="M33.5156 14.4141H30C29.3529 14.4141 28.8281 14.9388 28.8281 15.5859C28.8281 16.233 29.3529 16.7578 30 16.7578H33.5156C34.1627 16.7578 34.6875 16.233 34.6875 15.5859C34.6875 14.9388 34.1627 14.4141 33.5156 14.4141Z" />
                                        <path id="Vector_8"
                                            d="M40.5469 9.72656H44.0625C44.7096 9.72656 45.2344 9.2018 45.2344 8.55469C45.2344 7.90758 44.7096 7.38281 44.0625 7.38281H40.5469C39.8998 7.38281 39.375 7.90758 39.375 8.55469C39.375 9.2018 39.8998 9.72656 40.5469 9.72656Z" />
                                        <path id="Vector_9"
                                            d="M40.5469 16.7578H44.0625C44.7096 16.7578 45.2344 16.233 45.2344 15.5859C45.2344 14.9388 44.7096 14.4141 44.0625 14.4141H40.5469C39.8998 14.4141 39.375 14.9388 39.375 15.5859C39.375 16.233 39.8998 16.7578 40.5469 16.7578Z" />
                                        <path id="Vector_10"
                                            d="M40.5469 23.7891H44.0625C44.7096 23.7891 45.2344 23.2643 45.2344 22.6172C45.2344 21.9701 44.7096 21.4453 44.0625 21.4453H40.5469C39.8998 21.4453 39.375 21.9701 39.375 22.6172C39.375 23.2643 39.8998 23.7891 40.5469 23.7891Z" />
                                        <path id="Vector_11"
                                            d="M12.4219 7.38281H8.90625C8.25914 7.38281 7.73438 7.90758 7.73438 8.55469C7.73438 9.2018 8.25914 9.72656 8.90625 9.72656H12.4219C13.069 9.72656 13.5938 9.2018 13.5938 8.55469C13.5938 7.90758 13.069 7.38281 12.4219 7.38281Z" />
                                        <path id="Vector_12"
                                            d="M12.4219 14.4141H8.90625C8.25914 14.4141 7.73438 14.9388 7.73438 15.5859C7.73438 16.233 8.25914 16.7578 8.90625 16.7578H12.4219C13.069 16.7578 13.5938 16.233 13.5938 15.5859C13.5938 14.9388 13.069 14.4141 12.4219 14.4141Z" />
                                        <path id="Vector_13"
                                            d="M12.4219 21.4453H8.90625C8.25914 21.4453 7.73438 21.9701 7.73438 22.6172C7.73438 23.2643 8.25914 23.7891 8.90625 23.7891H12.4219C13.069 23.7891 13.5938 23.2643 13.5938 22.6172C13.5938 21.9701 13.069 21.4453 12.4219 21.4453Z" />
                                        <path id="Vector_14"
                                            d="M19.4531 23.7891H33.5156C34.1627 23.7891 34.6875 23.2643 34.6875 22.6172C34.6875 21.9701 34.1627 21.4453 33.5156 21.4453H19.4531C18.806 21.4453 18.2812 21.9701 18.2812 22.6172C18.2812 23.2643 18.806 23.7891 19.4531 23.7891Z" />
                                        <path id="Vector_15"
                                            d="M30 2.34375C30.6472 2.34375 31.1719 1.81908 31.1719 1.17188C31.1719 0.524666 30.6472 0 30 0C29.3528 0 28.8281 0.524666 28.8281 1.17188C28.8281 1.81908 29.3528 2.34375 30 2.34375Z" />
                                        <path id="Vector_16"
                                            d="M58.8281 0H35.2734C34.6263 0 34.1016 0.524766 34.1016 1.17188C34.1016 1.81898 34.6263 2.34375 35.2734 2.34375H57.6562V28.8281H2.34375V2.34375H24.7266C25.3737 2.34375 25.8984 1.81898 25.8984 1.17188C25.8984 0.524766 25.3737 0 24.7266 0H1.17188C0.524766 0 0 0.524766 0 1.17188V30C0 30.6471 0.524766 31.1719 1.17188 31.1719H28.8383C29.0854 37.4171 34.2422 42.4219 40.5469 42.4219H52.8516C55.0734 42.4219 57.6562 44.2136 57.6562 46.5234C57.6562 48.8333 55.0734 50.625 52.8516 50.625H23.3524C22.782 46.655 19.3596 43.5938 15.2344 43.5938H8.20312C3.6798 43.5938 0 47.2736 0 51.7969C0 56.3202 3.6798 60 8.20312 60H15.2344C19.3597 60 22.782 56.9387 23.3524 52.9688H52.8516C56.3566 52.9688 60 50.1479 60 46.5234C60 42.8986 56.3555 40.0781 52.8516 40.0781H40.5469C35.5346 40.0781 31.4289 36.1243 31.1838 31.1719H58.8281C59.4752 31.1719 60 30.6471 60 30V1.17188C60 0.524766 59.4752 0 58.8281 0ZM2.34375 51.7969C2.34375 48.566 4.97227 45.9375 8.20312 45.9375H10.5469V57.6562H8.20312C4.97227 57.6562 2.34375 55.0277 2.34375 51.7969ZM15.2344 57.6562H12.8906V52.9688H15.2344C15.8815 52.9688 16.4062 52.444 16.4062 51.7969C16.4062 51.1498 15.8815 50.625 15.2344 50.625H12.8906V45.9375H15.2344C18.4652 45.9375 21.0938 48.566 21.0938 51.7969C21.0938 55.0277 18.4652 57.6562 15.2344 57.6562Z" />
                                    </g>
                                </g>
                                <defs>
                                    <clipPath id="clip0_1_2532">
                                        <rect width="60" height="60" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>

                        </div>

                        علوم الكمبيوتر
                    </a>
                    <a href="/specialties/Engineering" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <svg width="45" height="60" viewBox="0 0 45 60" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="Group">
                                    <path id="Vector"
                                        d="M8.20312 27.3047H15.2344C15.8815 27.3047 16.4062 26.78 16.4062 26.1328C16.4062 25.4856 15.8815 24.9609 15.2344 24.9609H8.20312C7.55602 24.9609 7.03125 25.4856 7.03125 26.1328C7.03125 26.78 7.55602 27.3047 8.20312 27.3047Z" />
                                    <path id="Vector_2"
                                        d="M29.2969 27.3047H31.6406V29.6484C31.6406 30.2957 32.1654 30.8203 32.8125 30.8203C33.4596 30.8203 33.9844 30.2957 33.9844 29.6484V27.3047H36.3281C36.9752 27.3047 37.5 26.78 37.5 26.1328C37.5 25.4856 36.9752 24.9609 36.3281 24.9609H33.9844V22.6172C33.9844 21.97 33.4596 21.4453 32.8125 21.4453C32.1654 21.4453 31.6406 21.97 31.6406 22.6172V24.9609H29.2969C28.6498 24.9609 28.125 25.4856 28.125 26.1328C28.125 26.78 28.6498 27.3047 29.2969 27.3047Z" />
                                    <path id="Vector_3"
                                        d="M13.3761 47.9297L16.0631 45.2427C16.5207 44.7851 16.5207 44.043 16.0631 43.5853C15.6054 43.1277 14.8636 43.1277 14.4057 43.5853L11.7187 46.2723L9.03175 43.5853C8.57402 43.1277 7.83222 43.1277 7.37437 43.5853C6.91675 44.0429 6.91675 44.785 7.37437 45.2427L10.0614 47.9297L7.37437 50.6167C6.91675 51.0743 6.91675 51.8163 7.37437 52.2741C7.83211 52.7317 8.5739 52.7317 9.03175 52.2741L11.7187 49.5871L14.4057 52.2741C14.8635 52.7317 15.6053 52.7317 16.0631 52.2741C16.5207 51.8164 16.5207 51.0744 16.0631 50.6167L13.3761 47.9297Z" />
                                    <path id="Vector_4"
                                        d="M36.3281 43.2422H29.2969C28.6498 43.2422 28.125 43.7668 28.125 44.4141C28.125 45.0613 28.6498 45.5859 29.2969 45.5859H36.3281C36.9752 45.5859 37.5 45.0613 37.5 44.4141C37.5 43.7668 36.9752 43.2422 36.3281 43.2422Z" />
                                    <path id="Vector_5"
                                        d="M36.3281 50.2734H29.2969C28.6498 50.2734 28.125 50.7981 28.125 51.4453C28.125 52.0925 28.6498 52.6172 29.2969 52.6172H36.3281C36.9752 52.6172 37.5 52.0925 37.5 51.4453C37.5 50.7981 36.9752 50.2734 36.3281 50.2734Z" />
                                    <path id="Vector_6"
                                        d="M36.3281 9.375C36.9752 9.375 37.5 8.85035 37.5 8.20312C37.5 7.5559 36.9752 7.03125 36.3281 7.03125H32.8125C32.1654 7.03125 31.6406 7.5559 31.6406 8.20312C31.6406 8.85035 32.1654 9.375 32.8125 9.375H36.3281Z" />
                                    <path id="Vector_7"
                                        d="M22.2656 9.375H25.7812C26.4284 9.375 26.9531 8.85035 26.9531 8.20312C26.9531 7.5559 26.4284 7.03125 25.7812 7.03125H22.2656C21.6185 7.03125 21.0938 7.5559 21.0938 8.20312C21.0938 8.85035 21.6185 9.375 22.2656 9.375Z" />
                                    <path id="Vector_8"
                                        d="M22.2656 2.34375C22.9128 2.34375 23.4375 1.81908 23.4375 1.17188C23.4375 0.524666 22.9128 0 22.2656 0C21.6184 0 21.0938 0.524666 21.0938 1.17188C21.0938 1.81908 21.6184 2.34375 22.2656 2.34375Z" />
                                    <path id="Vector_9"
                                        d="M1.17187 60H43.3594C44.0065 60 44.5312 59.4754 44.5312 58.8281V1.17188C44.5312 0.524648 44.0065 0 43.3594 0H27.5391C26.892 0 26.3672 0.524648 26.3672 1.17188C26.3672 1.8191 26.892 2.34375 27.5391 2.34375H42.1875V14.0625H2.34375V2.34375H16.9922C17.6393 2.34375 18.1641 1.8191 18.1641 1.17188C18.1641 0.524648 17.6393 0 16.9922 0H1.17187C0.524766 0 0 0.524648 0 1.17188V58.8281C0 59.4754 0.524766 60 1.17187 60ZM2.34375 38.2031H21.0937V57.6562H2.34375V38.2031ZM23.4375 57.6562V38.2031H42.1875V57.6562H23.4375ZM42.1875 35.8594H23.4375V16.4062H42.1875V35.8594ZM21.0937 16.4062V35.8594H2.34375V16.4062H21.0937Z" />
                                </g>
                            </svg>

                        </div>

                        الهندسة
                    </a>
                    <a href="/specialties/Business and Management" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <svg width="60" height="60" viewBox="0 0 60 60" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="003-tablet 1">
                                    <g id="Group">
                                        <path id="Vector"
                                            d="M44.0625 28.8281H15.9375C15.2903 28.8281 14.7656 29.3528 14.7656 30C14.7656 30.6472 15.2903 31.1719 15.9375 31.1719H44.0625C44.7096 31.1719 45.2344 30.6472 45.2344 30C45.2344 29.3528 44.7096 28.8281 44.0625 28.8281Z" />
                                        <path id="Vector_2"
                                            d="M44.0625 35.8594H15.9375C15.2903 35.8594 14.7656 36.384 14.7656 37.0312C14.7656 37.6785 15.2903 38.2031 15.9375 38.2031H44.0625C44.7096 38.2031 45.2344 37.6785 45.2344 37.0312C45.2344 36.384 44.7096 35.8594 44.0625 35.8594Z" />
                                        <path id="Vector_3"
                                            d="M44.0625 42.8906H15.9375C15.2903 42.8906 14.7656 43.4153 14.7656 44.0625C14.7656 44.7097 15.2903 45.2344 15.9375 45.2344H44.0625C44.7096 45.2344 45.2344 44.7097 45.2344 44.0625C45.2344 43.4153 44.7096 42.8906 44.0625 42.8906Z" />
                                        <path id="Vector_4"
                                            d="M30 60C30.6472 60 31.1719 59.4753 31.1719 58.8281C31.1719 58.1809 30.6472 57.6562 30 57.6562C29.3528 57.6562 28.8281 58.1809 28.8281 58.8281C28.8281 59.4753 29.3528 60 30 60Z" />
                                        <path id="Vector_5"
                                            d="M12.4219 60H24.7266C25.3738 60 25.8984 59.4754 25.8984 58.8281C25.8984 58.1809 25.3738 57.6562 24.7266 57.6562H12.4219C11.1295 57.6562 10.0781 56.6048 10.0781 55.3125V52.2656H49.9219V55.3125C49.9219 56.6048 48.8705 57.6562 47.5781 57.6562H35.2734C34.6263 57.6562 34.1016 58.1809 34.1016 58.8281C34.1016 59.4754 34.6263 60 35.2734 60H47.5781C50.1628 60 52.2656 57.8972 52.2656 55.3125V4.6875C52.2656 2.10281 50.1628 0 47.5781 0H12.4219C9.83719 0 7.73438 2.10281 7.73438 4.6875V55.3125C7.73438 57.8972 9.83719 60 12.4219 60ZM10.0781 49.9219V10.0781H32.3438V22.9688C32.3438 23.4009 32.5816 23.7981 32.9626 24.002C33.3437 24.2059 33.8061 24.1835 34.1657 23.9438L38.7891 20.8616L43.4125 23.9439C43.7726 24.184 44.235 24.2057 44.6155 24.0021C44.9965 23.7982 45.2344 23.4011 45.2344 22.9689V10.0781H49.9219V49.9219H10.0781ZM42.8906 10.0781V20.7791L39.4391 18.4781C39.0455 18.2157 38.5328 18.2157 38.139 18.4781L34.6875 20.7791V10.0781H42.8906ZM12.4219 2.34375H47.5781C48.8705 2.34375 49.9219 3.39516 49.9219 4.6875V7.73438H10.0781V4.6875C10.0781 3.39516 11.1295 2.34375 12.4219 2.34375Z" />
                                    </g>
                                </g>
                            </svg>

                        </div>

                        الأعمال و الإدارة
                    </a>
                    <a href="/specialties/Education" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <svg width="60px" height="60px" viewBox="0 0 60 81" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="Group">
                                    <path id="Vector"
                                        d="M1.57895 43.5789C0.706895 43.5789 0 44.2858 0 45.1579C0 46.0299 0.706895 46.7368 1.57895 46.7368H4.73684V79.2631C4.73684 80.1352 5.44374 80.8421 6.31579 80.8421C7.18784 80.8421 7.89474 80.1352 7.89474 79.2631V46.7368H12.7048C15.8479 51.6431 20.9387 54.9704 26.6147 55.9269C23.649 59.2453 18.9474 65.1609 18.9474 69.3158C18.9474 75.5637 24.0088 80.8421 30 80.8421C35.9912 80.8421 41.0526 75.5637 41.0526 69.3158C41.0526 65.1609 36.351 59.2453 33.3853 55.9269C39.0614 54.9704 44.1523 51.6429 47.2952 46.7368H52.1053V79.2631C52.1053 80.1352 52.8122 80.8421 53.6842 80.8421C54.5563 80.8421 55.2632 80.1352 55.2632 79.2631V46.7368H58.4211C59.2931 46.7368 60 46.0299 60 45.1579C60 44.2858 59.2931 43.5789 58.4211 43.5789H48.9504C49.986 41.0913 50.5263 38.405 50.5263 35.6842C50.5263 33.9507 50.3072 32.2764 49.9238 30.7301C49.7139 29.8838 48.8577 29.3676 48.0114 29.5775C47.1649 29.7873 46.6489 30.6436 46.8587 31.4899C47.0749 32.3618 47.2213 33.2542 47.3 34.1533C42.5086 35.285 37.4269 34.5098 33.1808 31.9618C27.1306 28.332 19.8279 27.8318 13.4356 30.4525C14.9765 25.5666 18.6321 21.542 23.4872 19.5774C24.0835 19.3361 24.4738 18.7569 24.4738 18.1137V3.15789H35.5265V18.1137C35.5265 18.7569 35.9166 19.3359 36.513 19.5772C37.5291 19.9885 38.5119 20.5004 39.4344 21.0984C40.1645 21.5719 41.1428 21.3655 41.6182 20.6324C42.0927 19.9007 41.8841 18.9231 41.1523 18.4486C40.3631 17.9368 39.5365 17.4786 38.6844 17.0801V3.15789H44.2107C45.0827 3.15789 45.7896 2.451 45.7896 1.57895C45.7896 0.706895 45.0827 0 44.2107 0H15.7895C14.9174 0 14.2105 0.706895 14.2105 1.57895C14.2105 2.451 14.9174 3.15789 15.7895 3.15789H21.3158V17.0796C14.2134 20.3956 9.47368 27.5643 9.47368 35.6842C9.47368 38.4052 10.0142 41.0915 11.0496 43.5789H1.57895ZM31.5789 77.4991V69.3158C31.5789 68.4437 30.8721 67.7368 30 67.7368C29.1279 67.7368 28.4211 68.4437 28.4211 69.3158V77.4991C24.9126 76.6759 22.1053 73.2044 22.1053 69.3158C22.1053 66.3237 26.5674 60.5632 30 56.9019C33.4326 60.5632 37.8947 66.3237 37.8947 69.3158C37.8947 73.2044 35.0874 76.6759 31.5789 77.4991ZM30 53.0526C24.7904 53.0526 19.8764 50.6881 16.6048 46.7353H43.3953C40.1239 50.688 35.2097 53.0526 30 53.0526ZM12.6316 35.6842C12.6316 35.2241 12.6518 34.7619 12.6878 34.3025C18.5859 31.0628 25.7447 31.1833 31.5561 34.6696C36.2067 37.4602 41.8056 38.4827 47.285 37.3862C47.073 39.5482 46.4566 41.6512 45.4715 43.5789H14.5285C13.2865 41.1486 12.6316 38.4385 12.6316 35.6842Z" />
                                    <path id="Vector_2"
                                        d="M45.4737 26.2105C46.3458 26.2105 47.0527 25.5036 47.0527 24.6316C47.0527 23.7595 46.3458 23.0526 45.4737 23.0526C44.6017 23.0526 43.8948 23.7595 43.8948 24.6316C43.8948 25.5036 44.6017 26.2105 45.4737 26.2105Z" />
                                </g>
                            </svg>

                        </div>

                        التعليم
                    </a>
                    <a href="/specialties/Arts" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <svg width="60" height="60" viewBox="0 0 60 60" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="046-presentation" clip-path="url(#clip0_1_2528)">
                                    <g id="Group">
                                        <path id="Vector"
                                            d="M58.8281 7.03125H41.7188V1.17188C41.7188 0.524766 41.1941 0 40.5469 0H19.4531C18.8059 0 18.2812 0.524766 18.2812 1.17188V7.03125H1.17188C0.524648 7.03125 0 7.55602 0 8.20312V44.0625C0 44.7096 0.524648 45.2344 1.17188 45.2344H7.03125V47.5781C7.03125 48.2252 7.5559 48.75 8.20312 48.75H23.7793L15.0825 58.0266C14.6399 58.4987 14.6638 59.2404 15.1359 59.683C15.6067 60.1246 16.3486 60.103 16.7924 59.6297L26.9921 48.75H28.8281V58.8281C28.8281 59.4752 29.3528 60 30 60C30.6472 60 31.1719 59.4752 31.1719 58.8281V48.75H33.0079L43.2075 59.6297C43.6507 60.1024 44.3925 60.1252 44.8639 59.683C45.3361 59.2405 45.36 58.4988 44.9174 58.0266L36.2207 48.75H51.7969C52.4441 48.75 52.9688 48.2252 52.9688 47.5781V45.2344H58.8281C59.4754 45.2344 60 44.7096 60 44.0625V8.20312C60 7.55602 59.4754 7.03125 58.8281 7.03125ZM20.625 2.34375H39.375V7.03125H20.625V2.34375ZM57.6562 42.8906H52.9688V40.5469C52.9688 39.8998 52.4441 39.375 51.7969 39.375H35.2734C34.6262 39.375 34.1016 39.8998 34.1016 40.5469C34.1016 41.194 34.6262 41.7188 35.2734 41.7188H50.625V46.4062H9.375V41.7188H24.7266C25.3738 41.7188 25.8984 41.194 25.8984 40.5469C25.8984 39.8998 25.3738 39.375 24.7266 39.375H8.20312C7.5559 39.375 7.03125 39.8998 7.03125 40.5469V42.8906H2.34375V9.375H57.6562V42.8906Z" />
                                        <path id="Vector_2"
                                            d="M30 41.7188C30.6472 41.7188 31.1719 41.1941 31.1719 40.5469C31.1719 39.8997 30.6472 39.375 30 39.375C29.3528 39.375 28.8281 39.8997 28.8281 40.5469C28.8281 41.1941 29.3528 41.7188 30 41.7188Z" />
                                    </g>
                                </g>
                                <defs>
                                    <clipPath id="clip0_1_2528">
                                        <rect width="60" height="60" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>

                        </div>
                        الفنون
                    </a>
                    <a href="/specialties/Communication & Media" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                           <img width="60px" height="60px" class="m-auto" src="<?= AppHelper::getFileFromS3('images/specialities/communication_media.png') ?>">
                        </div>
                        الاعلام و الاتصال
                    </a>
                    <a href="/specialties/Agriculture & Natural" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <img width="60px" height="60px" class="m-auto" src="<?= AppHelper::getFileFromS3('images/specialities/agriculture_natural.png') ?>">
                        </div>
                        الزراعة والطبيعة
                    </a>
                    <a href="/specialties/Foreign Languages" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <img width="60px" height="60px" class="m-auto" src="<?= AppHelper::getFileFromS3('images/specialities/foreign_languages.png') ?>">
                        </div>
                        اللغات الأجنبية
                    </a>
                    <a href="/specialties/Medicine" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <img width="60px" height="60px" class="m-auto" src="<?= AppHelper::getFileFromS3('images/specialities/medicine.png') ?>">
                        </div>
                        الطب
                    </a>
                    <a href="/specialties/Health Administration" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <img width="60px" height="60px" class="m-auto" src="<?= AppHelper::getFileFromS3('images/specialities/health_administration.png') ?>">
                        </div>
                        الإدارة الصحية
                    </a>
                    <a href="/specialties/Tourism" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <img width="60px" height="60px" class="m-auto" src="<?= AppHelper::getFileFromS3('images/specialities/tourism.png') ?>">
                        </div>
                        السياحة
                    </a>
                    <a href="/specialties/Entertainment" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <img width="60px" height="60px" class="m-auto" src="<?= AppHelper::getFileFromS3('images/specialities/entertainment.png') ?>">
                        </div>
                        الترفيه
                    </a>
                    <a href="/specialties/Mathematics" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <img width="60px" height="60px" class="m-auto" src="<?= AppHelper::getFileFromS3('images/specialities/calculating.png') ?>">
                        </div>
                        الرياضيات
                    </a>
                    <a href="/specialties/Physical Education and Sports" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <img width="60px" height="60px" class="m-auto" src="<?= AppHelper::getFileFromS3('images/specialities/physical_education.png') ?>">
                        </div>
                        التربية البدنية
                    </a>
                    <a href="/specialties/Sports" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <img width="60px" height="60px" class="m-auto" src="<?= AppHelper::getFileFromS3('images/specialities/sports.png') ?>">
                        </div>
                        الرياضه
                    </a>
                    <a href="/specialties/Law" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <img width="60px" height="60px" class="m-auto" src="<?= AppHelper::getFileFromS3('images/specialities/law.png') ?>">
                        </div>
                        القانون
                    </a>
                    <a href="/specialties/Cafes and restaurants sector" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <img width="60px" height="60px" class="m-auto" src="<?= AppHelper::getFileFromS3('images/specialities/restaurant.png') ?>">
                        </div>
                       المطاعم
                    </a>
                    <a href="/specialties/Economic" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <img width="60px" height="60px" class="m-auto" src="<?= AppHelper::getFileFromS3('images/specialities/economic.png') ?>">
                        </div>
                        الاقتصاد
                    </a>
                    <a href="/specialties/Finance and Business" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <img width="60px" height="60px" class="m-auto" src="<?= AppHelper::getFileFromS3('images/specialities/finance_and_business.png') ?>">
                        </div>
                        المال والاعمال
                    </a>
                    <a href="/specialties/Islamic Sciences" class="book-box specialities">
                        <div class="img_wrapper" style="min-height: 30px">
                            <img width="60px" height="60px" class="m-auto" src="<?= AppHelper::getFileFromS3('images/specialities/islamic_sciences.png') ?>">
                        </div>
                        العلوم الاسلامية
                    </a>


                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($section == 'discover') : ?>
    <?php if (empty($users)): ?>
    <div class="rating pt-0">
        <div class="container" data-aos="fade-up">
                لايوجد نتائج...
        </div>
    </div>
    <?php else: ?>
        <div class="rating pt-0">
            <div class="container" data-aos="fade-up">
                <div class="responsive2" id="users-container">
                    <?php foreach($users as $user): ?>
                        <?php
                        View::include('Outer/Home/user', [
                            'user' => $user,
                            'isLoggedIn' => $isLoggedIn
                        ]);
                        ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>


    <!-- start provider -->
<!--    <section class="provider">-->
<!--        <div class="container-fluid" data-aos="fade-up">-->
<!--            <div class="heading_section mb-4">-->
<!--                <h2>كيف تستفيد من المنصة</h2>-->
<!--            </div>-->
<!--            <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">-->
<!--                <li class="nav-item" role="presentation">-->
<!--                    <button class="nav-link active" id="side-tabs-tab" data-bs-toggle="pill"-->
<!--                            data-bs-target="#side-tabs" type="button" role="tab" aria-controls="side-tabs"-->
<!--                            aria-selected="true">كمقدم خدمة</button>-->
<!--                </li>-->
<!--                <li class="nav-item" role="presentation">-->
<!--                    <button class="nav-link" id="large-tabs-tab" data-bs-toggle="pill" data-bs-target="#large-tabs"-->
<!--                            type="button" role="tab" aria-controls="large-tabs" aria-selected="false">كطالب خدمة</button>-->
<!--                </li>-->
<!--            </ul>-->
<!--            <div class="tab-content mt-5">-->
<!--                <div class="tab-pane fade show active" id="side-tabs" role="tabpanel"-->
<!--                     aria-labelledby="side-tabs-tab">-->
<!--                    <div class="row">-->
<!--                        <div class="col-lg-5" data-aos="fade-up">-->
<!--                            <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">-->
<!--                                <a class="nav-link active" id="tab1-tab" data-bs-toggle="pill" href="#tab1"-->
<!--                                   role="tab" aria-controls="tab1" aria-selected="true">-->
<!--                                    <span class="faq_num">⦿</span>-->
<!--                                    كيف يمكن إكمال الملف الشخصي-->
<!---->
<!--                                </a>-->
<!--                                <a class="nav-link" id="tab2-tab" data-bs-toggle="pill" href="#tab2" role="tab"-->
<!--                                   aria-controls="tab2" aria-selected="false">-->
<!--                                    <span class="faq_num">⦿</span>-->
<!---->
<!--                                    كيف يفعل استقبال الرسائل-->
<!--                                </a>-->
<!--                                <a class="nav-link" id="tab3-tab" data-bs-toggle="pill" href="#tab3" role="tab"-->
<!--                                   aria-controls="tab3" aria-selected="false">-->
<!--                                    <span class="faq_num">⦿</span>-->
<!---->
<!--                                    كيف ينشئ ويجدول المكالمات-->
<!--                                </a>-->
<!--                                <a class="nav-link" id="tab4-tab" data-bs-toggle="pill" href="#tab4" role="tab"-->
<!--                                   aria-controls="tab4" aria-selected="true">-->
<!--                                    <span class="faq_num">⦿</span>-->
<!---->
<!--                                    كيف ينشئ الجلسات-->
<!--                                </a>-->
<!--                                <a class="nav-link" id="tab5-tab" data-bs-toggle="pill" href="#tab5" role="tab"-->
<!--                                   aria-controls="tab5" aria-selected="true">-->
<!--                                    <span class="faq_num">⦿</span>-->
<!---->
<!--                                    مشاركة وبدء الجلسة-->
<!--                                </a>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="col-1"></div>-->
<!--                        <div class="col-lg-6" data-aos="fade-up">-->
<!--                            <div class="tab-content">-->
<!--                                <div class="tab-pane fade show active" id="tab1" role="tabpanel"-->
<!--                                     aria-labelledby="tab1-tab">-->
<!--                                    <div class="serv_slider">-->
<!---->
<!--                                        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">-->
<!--                                            <div class="carousel-indicators">-->
<!--                                            </div>-->
<!--                                            <div class="carousel-inner">-->
<!--                                                <div class="carousel-item active">-->
<!--                                                    <img src="--><?php //= URL::full('Application/Assets/Outer/home/images/instructions/complete_profile/0.png') ?><!--" alt="Tab Image" class="wrapp_box d-block w-100">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!---->
<!--                                </div>-->
<!--                                <div class="tab-pane fade " id="tab2" role="tabpanel" aria-labelledby="tab2-tab">-->
<!--                                    <div class="serv_slider">-->
<!---->
<!--                                        <div id="carouselExampleIndicators1" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">-->
<!--                                            <div class="carousel-indicators">-->
<!--                                            </div>-->
<!--                                            <div class="carousel-inner">-->
<!--                                                <div class="carousel-item active">-->
<!--                                                    <img src="--><?php //= URL::full('Application/Assets/Outer/home/images/instructions/receive_messages/0.png') ?><!--" alt="Tab Image" class="wrapp_box d-block w-100">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="tab-pane fade " id="tab3" role="tabpanel" aria-labelledby="tab3-tab">-->
<!--                                    <div class="serv_slider">-->
<!---->
<!--                                        <div id="carouselExampleIndicators2" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">-->
<!--                                            <div class="carousel-indicators">-->
<!--                                            </div>-->
<!--                                            <div class="carousel-inner">-->
<!--                                                <div class="carousel-item active">-->
<!--                                                    <img src="--><?php //= URL::full('Application/Assets/Outer/home/images/instructions/receive_calls/0.png') ?><!--" alt="Tab Image" class="wrapp_box d-block w-100">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="tab-pane fade  " id="tab4" role="tabpanel" aria-labelledby="tab4-tab">-->
<!--                                    <div class="serv_slider">-->
<!---->
<!--                                        <div id="carouselExampleIndicators3" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">-->
<!--                                            <div class="carousel-indicators">-->
<!--                                            </div>-->
<!--                                            <div class="carousel-inner">-->
<!--                                                <div class="carousel-item active">-->
<!--                                                    <img src="--><?php //= URL::full('Application/Assets/Outer/home/images/instructions/create_sessions/0.png') ?><!--" alt="Tab Image" class="wrapp_box d-block w-100">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="tab-pane fade  " id="tab5" role="tabpanel" aria-labelledby="tab5-tab">-->
<!--                                    <div class="serv_slider">-->
<!---->
<!--                                        <div id="carouselExampleIndicators4" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">-->
<!--                                            <div class="carousel-indicators">-->
<!--                                            </div>-->
<!--                                            <div class="carousel-inner">-->
<!--                                                <div class="carousel-item active">-->
<!--                                                    <img src="--><?php //= URL::full('Application/Assets/Outer/home/images/instructions/share_start_session/0.png') ?><!--" alt="Tab Image" class="wrapp_box d-block w-100">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="tab-pane fade" id="large-tabs" role="tabpanel" aria-labelledby="large-tabs-tab">-->
<!--                    <div class="row">-->
<!--                        <div class="col-lg-5" data-aos="fade-up">-->
<!--                            <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">-->
<!---->
<!--                                <a class="nav-link active" id="tab6-tab" data-bs-toggle="pill" href="#tab6"-->
<!--                                   role="tab" aria-controls="tab6" aria-selected="false">-->
<!--                                    <span class="faq_num">⦿</span>-->
<!---->
<!--                                    إرسال الرسائل-->
<!--                                </a>-->
<!--                                <a class="nav-link" id="tab7-tab" data-bs-toggle="pill" href="#tab7" role="tab"-->
<!--                                   aria-controls="tab7" aria-selected="false">-->
<!--                                    <span class="faq_num">⦿</span>-->
<!---->
<!--                                    التسجيل في الجلسات-->
<!--                                </a>-->
<!--                                <a class="nav-link" id="tab8-tab" data-bs-toggle="pill" href="#tab8" role="tab"-->
<!--                                   aria-controls="tab8" aria-selected="false">-->
<!--                                    <span class="faq_num">⦿</span>-->
<!---->
<!--                                    حجز مكالمة-->
<!--                                </a>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="col-1"></div>-->
<!---->
<!--                        <div class="col-lg-6" data-aos="fade-up">-->
<!--                            <div class="tab-content">-->
<!---->
<!--                                <div class="tab-pane fade show active" id="tab6" role="tabpanel"-->
<!--                                     aria-labelledby="tab6-tab">-->
<!--                                    <div class="serv_slider">-->
<!---->
<!--                                        <div id="carouselExampleIndicators5" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">-->
<!--                                            <div class="carousel-indicators">-->
<!--                                            </div>-->
<!--                                            <div class="carousel-inner">-->
<!--                                                <div class="carousel-item active">-->
<!--                                                    <img src="--><?php //= URL::full('Application/Assets/Outer/home/images/instructions/send_messages/0.png') ?><!--" alt="Tab Image" class="wrapp_box d-block w-100">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="tab-pane fade " id="tab7" role="tabpanel" aria-labelledby="tab7-tab">-->
<!--                                    <div class="serv_slider">-->
<!---->
<!--                                        <div id="carouselExampleIndicators6" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">-->
<!--                                            <div class="carousel-indicators">-->
<!--                                            </div>-->
<!--                                            <div class="carousel-inner">-->
<!--                                                <div class="carousel-item active">-->
<!--                                                    <img src="--><?php //= URL::full('Application/Assets/Outer/home/images/instructions/book_session/0.png') ?><!--" alt="Tab Image" class="wrapp_box d-block w-100">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="tab-pane fade " id="tab8" role="tabpanel" aria-labelledby="tab8-tab">-->
<!--                                    <div class="serv_slider">-->
<!---->
<!--                                        <div id="carouselExampleIndicators7" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">-->
<!--                                            <div class="carousel-indicators">-->
<!--                                            </div>-->
<!--                                            <div class="carousel-inner">-->
<!--                                                <div class="carousel-item active">-->
<!--                                                    <img src="--><?php //= URL::full('Application/Assets/Outer/home/images/instructions/book_call/0.png') ?><!--" alt="Tab Image" class="wrapp_box d-block w-100">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </section>-->


<!-- start sessions -->
<?php if (!empty($availableWorkshops)): ?>
    <div class="booking_box" id="sessions">
    <div class="container" data-aos="fade-up">
        <div class="heading_section">
            <h2>الجلسات المتاحة للحجز</h2>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="slider_wrapper">
<!--                    <ul class="nav nav-tabs my-custom-tab border-0" id="myNewTab" role="tablist">-->
<!--                        <li class="nav-item" role="presentation"> <button class="nav-link active"-->
<!--                                id="home-new-tab" data-bs-toggle="tab" data-bs-target="#home-new" type="button"-->
<!--                                role="tab" aria-controls="home-new" aria-selected="true">الكل</button> </li>-->
<!--                        <li class="nav-item" role="presentation"> <button class="nav-link" id="profile-new-tab"-->
<!--                                data-bs-toggle="tab" data-bs-target="#profile-new" type="button" role="tab"-->
<!--                                aria-controls="profile-new" aria-selected="false">الاعمال و الادارة</button>-->
<!--                        </li>-->
<!--                        <li class="nav-item" role="presentation"> <button class="nav-link" id="contact-new-tab"-->
<!--                                data-bs-toggle="tab" data-bs-target="#contact-new" type="button" role="tab"-->
<!--                                aria-controls="contact-new" aria-selected="false">علوم الكمبيوتر</button> </li>-->
<!--                        <li class="nav-item" role="presentation"> <button class="nav-link" id="about-new-tab"-->
<!--                                data-bs-toggle="tab" data-bs-target="#about-new" type="button" role="tab"-->
<!--                                aria-controls="about-new" aria-selected="false">الاعلام والاتصال</button> </li>-->
<!--                        <li class="nav-item" role="presentation"> <button class="nav-link" id="services-new-tab"-->
<!--                                data-bs-toggle="tab" data-bs-target="#services-new" type="button" role="tab"-->
<!--                                aria-controls="services-new" aria-selected="false">أسم القسم</button> </li>-->
<!--                    </ul>-->
                    <div class="tab-content" id="myNewTabContent">
                        <!-- start tab 1 -->
                        <div class="tab-pane fade show active" id="home-new" role="tabpanel"
                            aria-labelledby="home-new-tab">
                            <div class="responsive3">
                                <?php foreach ($availableWorkshops as $workshop): ?>
                                    <a href="<?= URL::full('login') ?>" class="card">
                                    <!-- <img src="<?= URL::asset('Application/Assets/Outer/home/images/card1.png') ?>" class="card-img-top"
                                        alt="Card Image"> -->
                                    <div class="card-body">


                                        <h5 class="card-title"><?= $workshop['name'] ?></h5>
                                        <p class="card-text"><?= substr_replace($workshop['desc'], '...', 90) ?></p>


                                          <ul class="list-unstyled d-flex mt-3">
                                            <li><i class="fa-regular fa-calendar-days"></i><?= $workshop['date'] ?></li>
                                            <li><i class="fa-regular fa-clock"></i> <?= $workshop['duration'] ?> دقيقة</li>
                                            <li><i class="fa-solid fa-money-bill-wave"></i>  <?= $workshop['price'] ?> ريال سعودي</li>
                                        </ul>
                                        <div class="d-flex align-items-center mt-3">
                                            <img src="<?= $workshop['user']['avatarUrl'] ?>"
                                                class=" rounded-circle ms-2  " alt="User Image" width="100"
                                                height="100">
                                            <div>
                                                <p class="dis"><?= $workshop['user']['name'] ?> <?php if ($workshop['user']['account_verified']) echo '<i class="fa-regular fa-circle-check"></i>'; ?></p>
                                                <small class="text-muted">@<?= $workshop['user']['username'] ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- start tab 2 -->
                        <div class="tab-pane fade" id="profile-new" role="tabpanel"
                            aria-labelledby="profile-new-tab">
                            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Repudiandae delectus eum
                            non praesentium magnam nemo corporis officia labore magni, nobis quidem at nam
                            soluta, nihil architecto enim dolor unde eligendi.
                        </div>
                        <!-- start tab 3 -->
                        <div class="tab-pane fade" id="contact-new" role="tabpanel"
                            aria-labelledby="contact-new-tab">
                            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Repudiandae delectus eum
                            non praesentium magnam nemo corporis officia labore magni, nobis quidem at nam
                            soluta, nihil architecto enim dolor unde eligendi.

                        </div>
                        <!-- start tab 4 -->
                        <div class="tab-pane fade" id="about-new" role="tabpanel"
                            aria-labelledby="about-new-tab">
                            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Repudiandae delectus eum
                            non praesentium magnam nemo corporis officia labore magni, nobis quidem at nam
                            soluta, nihil architecto enim dolor unde eligendi.

                        </div>
                        <!-- start tab 5 -->
                        <div class="tab-pane fade" id="services-new" role="tabpanel"
                            aria-labelledby="services-new-tab">
                            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Repudiandae delectus eum
                            non praesentium magnam nemo corporis officia labore magni, nobis quidem at nam
                            soluta, nihil architecto enim dolor unde eligendi.

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- start numbers -->
<!--<div class="clients">-->
<!--<div class="container" data-aos="fade-up">-->
<!--<div class="heading_section mb-5">-->
<!--    <h2>الأرقام</h2>-->
<!--    <p>تيلي ان في ارقام</p>-->
<!--</div>-->
<!--<div class="row">-->
<!--    <div class="col-lg-3 col-6">-->
<!--        <div class="counter-wrapper">-->
<!--            <div class="img_wrapper">-->
<!--                <img src="--><?php //= URL::asset('Application/Assets/Outer/home/images/n1.svg') ?><!--" alt="Image" />-->
<!---->
<!--            </div>-->
<!--            <h5 class="stat-number">--><?php //= $feedViewsCount ?><!--</h5>-->
<!--            <p>عدد زيارات الموقع</p>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-lg-3 col-6">-->
<!--        <div class="counter-wrapper">-->
<!--            <div class="img_wrapper">-->
<!--                <img src="--><?php //= URL::asset('Application/Assets/Outer/home/images/n2.svg') ?><!--" alt="Image" />-->
<!---->
<!--            </div>-->
<!---->
<!--            <h5 class="stat-number">--><?php //= $numberOfMinutesForPerformedWorkshops ?><!--</h5>-->
<!--            <p>عدد دقائق الجلسات التي تم اجراؤها</p>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-lg-3 col-6">-->
<!--        <div class="counter-wrapper">-->
<!--            <div class="img_wrapper">-->
<!--                <img src="--><?php //= URL::asset('Application/Assets/Outer/home/images/n3.svg') ?><!--" alt="Image" />-->
<!---->
<!--            </div>-->
<!--            <h5 class="stat-number">--><?php //= $numberOfMinutesForPerformedCalls ?><!--</h5>-->
<!--            <p>عدد دقائق المكالمات التي تم انشاؤها</p>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-lg-3 col-6">-->
<!--        <div class="counter-wrapper">-->
<!--            <div class="img_wrapper">-->
<!--                <img src="--><?php //= URL::asset('Application/Assets/Outer/home/images/n4.svg') ?><!--" alt="Image" />-->
<!---->
<!--            </div>-->
<!--            <h5 class="stat-number">--><?php //= $usersCount ?><!--</h5>-->
<!--            <p>مستخدم للموقع</p>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!--</div>-->
<!--</div>-->

<!-- bg-blue -->
<!-- start clients -->
<!--<div class="clients " >-->
<!--<div class="container" data-aos="fade-up">-->
<!--<div class="heading_section mb-3">-->
<!--<h2> عملائنا وشركائنا </h2>-->
<!--<p>تيلي ان في ارقام</p>-->
<!--</div>-->
<!--<div class="responsive4">-->
<!--    <a>-->
<!--        <img src="--><?php //= AppHelper::getFileFromS3('images/partners/p1.png') ?><!--" alt="">-->
<!--    </a>-->
<!--    <a>-->
<!--        <img src="--><?php //= AppHelper::getFileFromS3('images/partners/p2.png') ?><!--" alt="">-->
<!--    </a>-->
<!--    <a>-->
<!--        <img src="--><?php //= AppHelper::getFileFromS3('images/partners/p3.png') ?><!--" alt="">-->
<!--    </a>-->
<!--    <a>-->
<!--        <img src="--><?php //= AppHelper::getFileFromS3('images/partners/p4.png') ?><!--" alt="">-->
<!--    </a>-->
<!--    <a>-->
<!--        <img src="--><?php //= AppHelper::getFileFromS3('images/partners/p5.png') ?><!--" alt="">-->
<!--    </a>-->
<!--    <a>-->
<!--        <img src="--><?php //= AppHelper::getFileFromS3('images/partners/p6.png') ?><!--" alt="">-->
<!--    </a>-->
<!--    <a>-->
<!--        <img src="--><?php //= AppHelper::getFileFromS3('images/partners/p7.png') ?><!--" alt="">-->
<!--    </a>-->
<!--    <a>-->
<!--        <img src="--><?php //= AppHelper::getFileFromS3('images/partners/p8.png') ?><!--" alt="">-->
<!--    </a>-->
<!--</div>-->
<!--</div>-->
<!--</div>-->

<div class="rating" id="highest-rated">
    <div class="container" data-aos="fade-up">
        <div class="heading_section">
            <h2>الاكثر تقييماً</h2>
        </div>
        <div class="responsive2">
            <?php foreach ($highRatedUsers as $user): ?>
                <div class="card border-0 shadow-sm position-relative">
<!--                <button class="btn label_badge">-->
<!--                    متابعة-->
<!--                </button>-->
                <?php $url = $isLoggedIn ? URL::full('profile/' . $user['id']) : URL::full('outer-profile/' . $user['id']); ?>
                <a href="<?= $url ?>">
                    <div class="text-center p-3">
                        <img src="<?= $user['avatarUrl'] ?>" class="rounded-circle" alt="Profile Image" width="100" height="100" style="border: 1px solid #3F4AAA; padding: 3px;">

                        <h5 class="card-title mb-1 mt-3"><?= $user['name'] ?> <?php if ($user['account_verified']) echo '<i class="fa-regular fa-circle-check"></i>'; ?></h5>
                        <p class="card-text"><i class="fa-regular fa-star"></i> <?= number_format($user['rating'], 1) ?></p>
                        <p class="card-text">@<?= $user['username'] ?></p>
                      </div>
                </a>
<!--                <div class="card-footer border-0 p-3">-->
<!--                    <ul class="social-foot social-foot2  justify-content-center">-->
<!--                        <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>-->
<!--                        <li><a href="#"><i class="fa-brands fa-whatsapp"></i></a></li>-->
<!--                        <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>-->
<!--                        <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>-->
<!--                        <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>-->
<!--                    </ul>-->
<!--                </div>-->

                  <!-- <div class="card-footer border-0 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-subtitle mb-2">التخصاصات</h6>
                        <a href="#" class="card-subtitle"> المزيد<i class="fa-solid fa-arrow-left me-2 "></i></a>

                    </div>
                    <div class="cat_box">
                       <a href="#" class="badge bg-secondary">إدارة الأعمال</a>
                       <a href="#" class="badge bg-secondary">تسويق</a>
                       <a href="#" class="badge bg-secondary">التدريس</a>
                       <a href="#" class="badge bg-secondary"> الخدمات الطلابية</a>
                       <a href="#" class="badge bg-secondary">التعليم الطبي</a>
                       <a href="#" class="badge bg-secondary">الفلسفة</a>
                       <a href="#" class="badge bg-secondary"> المزيد<i class="fa-solid fa-arrow-left me-2"></i></a>

                    </div>


                  </div> -->
              </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>

<!-- start testmonial -->
<!--<section class="bg-cust_2">-->
<!--        <div class="container-fluid" data-aos="fade-up">-->
<!--            <div class="heading_section text-white mb-5">-->
<!--                <h2>ماذا قالوا عن المنصة</h2>-->
<!--                <p>هو ببساطة نص شكلي بمعنى أن الغاية هي الشكل وليس المحتوى</p>-->
<!--            </div>-->
<!--            <div class="row">-->
<!--                <div class="col-12">-->
<!--                    <div class="responsive22">-->
<!--                        <a>-->
<!--                            <div class="d-flex align-items-end test_box">-->
<!--                                <img src="--><?php //= AppHelper::getFileFromS3('images/ISNVEWpMpNqOkJpd2LGB_1696326901.jpg') ?><!--" class="rounded-circle ms-3"-->
<!--                                     alt="Testimonial Image" width="60" height="60"-->
<!--                                     style="border:2px solid #3F4AAA;">-->
<!---->
<!--                                <div class="card   border-0">-->
<!--                                    <div class="card-body">-->
<!--                                        <div>-->
<!--                                            <p class="mb-0">تيلي ان وفرت الفرصة لاصحاب الخبرات لتقديم عصارة خبراتهم بالطريقة و الاسلوب المناسب لهم و للمستفيدين من خبراتهم</p>-->
<!--                                            <h5>Ahmed Alhuwaymil</h5>-->
<!--                                            <small class="d-block  mt-2">@ahmed</small>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="speech-bubble"></div>-->
<!---->
<!--                                </div>-->
<!---->
<!--                            </div>-->
<!--                        </a>-->
<!--                        <a>-->
<!--                            <div class="d-flex align-items-end test_box">-->
<!--                                <img src="--><?php //= AppHelper::getFileFromS3('images/ZFVqxecyucWtVKferpY2_1663769344.jpg') ?><!--" class="rounded-circle ms-3"-->
<!--                                     alt="Testimonial Image" width="60" height="60"-->
<!--                                     style="border:2px solid #3F4AAA;">-->
<!---->
<!--                                <div class="card   border-0">-->
<!--                                    <div class="card-body">-->
<!--                                        <div>-->
<!--                                            <p class="mb-0">منصة تيلي إن فريدة ومن خلالها تم توثيق وتأمين العلاقة مابين المستخدمين، حيث اصبحت تيلي إن الخيار الأمثل لكل أطراف العلاقة سواء خبير أو طالب خدمة.</p>-->
<!--                                            <h5>Fahad Alhammad</h5>-->
<!--                                            <small class="d-block  mt-2">@fahad</small>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="speech-bubble"></div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </a>-->
<!--                        <a>-->
<!--                            <div class="d-flex align-items-end test_box">-->
<!--                                <img src="--><?php //= AppHelper::getFileFromS3('images/JfKrq66lBZA63aSCdVkh_1661524215.jpg') ?><!--" class="rounded-circle ms-3"-->
<!--                                     alt="Testimonial Image" width="60" height="60"-->
<!--                                     style="border:2px solid #3F4AAA;">-->
<!---->
<!--                                <div class="card   border-0">-->
<!--                                    <div class="card-body">-->
<!--                                        <div>-->
<!--                                            <p class="mb-0">ساهمت منصة تيلي بسهولة وصولي لجمهوري و تقديم خبراتي ودوراتي عبر المنصة في وقت فراغي دون أي قيود والتزامات إدارية</p>-->
<!--                                            <h5>Dr. Salim Ghandorah</h5>-->
<!--                                            <small class="d-block  mt-2">@ssghando</small>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="speech-bubble"></div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </a>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </section>-->



<!-- start upload -->
<section class="bg-custom pt-5">
    <div class="container" data-aos="fade-up">
        <div class="row">
            <div class="col-lg-6  text-white" style="    display: flex;
        align-items: center;
    ">
                <div class="up-info">
                    <h2 class="mb-4">حمل تطبيق تيلي ان</h2>
                    <a class="btn p-0"><img src="<?= URL::asset('Application/Assets/Outer/home/images/apple.svg') ?>" alt="" class=""></a>
                    <a class="btn p-0"><img src="<?= URL::asset('Application/Assets/Outer/home/images/google.svg') ?>" alt=""></a>

                </div>
            </div>
            <div class="col-lg-6 d-flex justify-content-end">
              <a href="#">
                <img src="<?= URL::asset('Application/Assets/Outer/home/images/app.png') ?>" class="img-fluid" alt="upload Image">
              </a>
            </div>
        </div>
    </div>
</section>
<!-- start blog -->
<!--<div class="booking_box" id="blog">-->
<!--<div class="container" data-aos="fade-up">-->
<!--    <div class="heading_section">-->
<!--        <h2>المدونة</h2>-->
<!--        <p>تابع اول باول ليصلك كل جديد</p>-->
<!--    </div>-->
<!--    <div class="row">-->
<!--        <div class="col-12">-->
<!--            <div class="responsive3 slider_wrapper">-->
<!--                <a href="--><?php //= URL::full('blogs') . '/1' ?><!--">-->
<!--                    <div class="card">-->
<!--                        <div class="card-body">-->
<!--                            <h5 class="card-title">آفاق جديدة لزكاة عِلمك</h5>-->
<!--                            <p class="card-text">قديماً؛ كانت فرص التعلم لأي مهارة جديدة محصورة في اجتهادات شخصية مقرونة بالشغف وحب التغيير…-->
<!--                                </p>-->
<!---->
<!--                            <div class="d-flex align-items-center">-->
<!--                                <img src="--><?php //= AppHelper::getFileFromS3('images/HLGc5RjVrVwiXfUDtkn0_1661777365.jpeg') ?><!--" class=" rounded-circle ms-2  "-->
<!--                                    alt="User Image" width="100" height="100">-->
<!--                                <div>-->
<!--                                    <p class="dis">Abdulaziz Alarifi<i class="fa-regular fa-circle-check"></i></p>-->
<!--                                    <small class="text-muted">@alarifia</small>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </a>-->
<!--                <a href="--><?php //= URL::full('blogs') . '/2' ?><!--">-->
<!--                    <div class="card">-->
<!--                        <div class="card-body">-->
<!--                            <h5 class="card-title">العلامة التجارية العابر للقارات</h5>-->
<!--                            <p class="card-text">ماكدونالدز، ستاربكس، أرامكو، توتال انيرجيز، HSBC...-->
<!--                            </p>-->
<!---->
<!--                            <div class="d-flex align-items-center">-->
<!--                                <img src="--><?php //= AppHelper::getFileFromS3('images/ISNVEWpMpNqOkJpd2LGB_1696326901.jpg') ?><!--" class=" rounded-circle ms-2  "-->
<!--                                     alt="User Image" width="100" height="100">-->
<!--                                <div>-->
<!--                                    <p class="dis">Ahmed Alhuwaymil<i class="fa-regular fa-circle-check"></i></p>-->
<!--                                    <small class="text-muted">@ahmed</small>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </a>-->
<!--                <a href="--><?php //= URL::full('blogs') . '/3' ?><!--">-->
<!--                    <div class="card">-->
<!--                        <div class="card-body">-->
<!--                            <h5 class="card-title">دور منصة تيلي إن في مشاركة المعرفة</h5>-->
<!--                            <p class="card-text">نقل العلوم والمعارف وتبادلها بين أصحاب الاختصاص ومع...-->
<!--                            </p>-->
<!---->
<!--                            <div class="d-flex align-items-center">-->
<!--                                <img src="--><?php //= AppHelper::getFileFromS3('images/default-avatar.jpg') ?><!--" class=" rounded-circle ms-2  "-->
<!--                                     alt="User Image" width="100" height="100">-->
<!--                                <div>-->
<!--                                    <p class="dis">Reem Alhassan<i class="fa-regular fa-circle-check"></i></p>-->
<!--                                    <small class="text-muted">@reem</small>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </a>-->
<!--                <a href="--><?php //= URL::full('blogs') . '/4' ?><!--">-->
<!--                    <div class="card">-->
<!--                        <div class="card-body">-->
<!--                            <h5 class="card-title">الفرص والتحديات</h5>-->
<!--                            <p class="card-text">عدم وجود منصة افتراضية تقدم الخدمة المجتمعية المتمثلة بنقل العلم ورفع الوعي وتبادل المنافع...-->
<!--                            </p>-->
<!---->
<!--                            <div class="d-flex align-items-center">-->
<!--                                <img src="--><?php //= AppHelper::getFileFromS3('images/default-avatar.jpg') ?><!--" class=" rounded-circle ms-2  "-->
<!--                                     alt="User Image" width="100" height="100">-->
<!--                                <div>-->
<!--                                    <p class="dis">منصة تيلي إن<i class="fa-regular fa-circle-check"></i></p>-->
<!--                                    <small class="text-muted">@telein</small>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </a>-->
<!--                <a href="--><?php //= URL::full('blogs') . '/5' ?><!--">-->
<!--                    <div class="card">-->
<!--                        <div class="card-body">-->
<!--                            <h5 class="card-title">المسؤولية الاجتماعية</h5>-->
<!--                            <p class="card-text">تأتي منصة تيلي إن كقيمة مضافة على شكل وسيط يخدم أفراد المجتمع تحت شعار من المجتمع إلى المجتمع حيث...-->
<!--                            </p>-->
<!---->
<!--                            <div class="d-flex align-items-center">-->
<!--                                <img src="--><?php //= AppHelper::getFileFromS3('images/default-avatar.jpg') ?><!--" class=" rounded-circle ms-2  "-->
<!--                                     alt="User Image" width="100" height="100">-->
<!--                                <div>-->
<!--                                    <p class="dis">منصة تيلي إن<i class="fa-regular fa-circle-check"></i></p>-->
<!--                                    <small class="text-muted">@telein</small>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </a>-->
<!--                <a href="--><?php //= URL::full('blogs') . '/6' ?><!--">-->
<!--                    <div class="card">-->
<!--                        <div class="card-body">-->
<!--                            <h5 class="card-title">مايميز تيلي إن</h5>-->
<!--                            <p class="card-text">تمكن المنصة المستخدمون بالاستفادة من خصائص المنصة على مختلف تخصصاتهم ومجالاتهم...-->
<!--                            </p>-->
<!---->
<!--                            <div class="d-flex align-items-center">-->
<!--                                <img src="--><?php //= AppHelper::getFileFromS3('images/default-avatar.jpg') ?><!--" class=" rounded-circle ms-2  "-->
<!--                                     alt="User Image" width="100" height="100">-->
<!--                                <div>-->
<!--                                    <p class="dis">منصة تيلي إن<i class="fa-regular fa-circle-check"></i></p>-->
<!--                                    <small class="text-muted">@telein</small>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </a>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!--</div>-->

<!-- contact -->
<div class="booking_box bg-blue contact">
    <div class="container" data-aos="fade-up">
        <div class="heading_section">
            <h2>تواصل معنا </h2>
            <p>تابع اول باول ليصلك كل جديد</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-10">
                <form class="row justify-content-center mt-4" id="contact-form" method="post" action="<?= URL::full('/'); ?>">
                    <div class="mb-3 col-md-6">
                      <label for="username" class="form-label">الإسم </label>
                      <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3 col-md-6">
                      <label for="email" class="form-label">البريد الإلكتروني</label>
                      <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" required>
                    </div>
                    <div class="mb-3 col-md-12">
                      <label for="message" class="form-label">الرسالة</label>
                      <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <div class="col-md-3 d-grid mt-3">

                        <div class="g-recaptcha mb-2" data-sitekey="<?= Config::get('Application')->Recaptcha['key'] ?>"></div>

                        <button id="contact-with-us" type="submit" class="btn btn-def" style="    padding-bottom: 12px;">ارسال</button>
                    </div>
                  </form>
            </div>
        </div>
    </div>
</div>

<define footer_js>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js" integrity="sha512-3j3VU6WC5rPQB4Ld1jnLV7Kd5xr+cq9avvhwqzbH/taCRNURoeEpoPBK9pDyeukwSxwRPJ8fDgvYXd6SkaZ2TA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
         $('#btn-search').on('click', function(e) {
             // $.cookie('section', 'our-services');
             $.cookie('tab', 'profile-tab');
         });

         // $('.specialities').on('click', function() {
         //     $.cookie('section', 'discover');
         // });

         <?php if (isset($section) && $section == 'search'): ?>
                    window.location.href = `${window.location.href}#our-services`;
         <?php elseif(isset($section)&& $section == 'discover'): ?>
                    window.location.href = `${window.location.href}#discover`;
        <?php endif ?>


         if ($.cookie('tab') !== undefined) {
             var id = $.cookie('tab');
             $(`#${id}`).click();
             $.removeCookie('tab');

             if (id === 'profile-tab') {
                 $('.search-result').removeClass('d-none');
             }

         }

         $('#home-tab, #contact-tab').on('click', function() {
             $('.search-result').addClass('d-none');
         });


        $('#spec').on('change', function(e)  {
                var spec =  this.value,
                    formData = new FormData();

                formData.append('specialtyId[]', spec);

                $.ajax({
                     url: URLS.specialty_get_sub_specialties,
                     type: 'POST',
                     processData: false,
                     contentType: false,
                     data: formData,
                     success: function(data) {
                         $('#sub-spec').empty();
                         $('#sub-spec').append(`<option value="0" selected>اختر اسم التخصص الدقيق</option>`);

                         data.payload.forEach(function(elem) {
                             $('#sub-spec').append(`<option value="${elem.id}">${elem.specialty_ar}</option>`)
                         })
                     },
                     complete: function() {

                     }
                });
        });

        $('#contact-with-us').on('click', function(e) {
            e.preventDefault()

            var name = $('#username').val(),
                email = $('#email').val(),
                message = $('#message').val(),
                recaptcha = $('#g-recaptcha-response').val(),
                formData = new FormData();

            if (!name || !email || !message || !recaptcha) {
                return;
            }

            formData.append('name', name);
            formData.append('email', email);
            formData.append('message', message);
            formData.append('recaptcha', recaptcha);

            $.ajax({
                url: URLS.support,
                type: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function(data) {
                    if (data.info === 'error') {
                        $.toast({
                            heading: "<?= $lang('error')?>",
                            text: "<?= $lang('error')?>",
                            showHideTransition: 'slide',
                            hideAfter: 5000,
                            icon: 'error',
                            position: 'top-center'
                        });
                        return;
                    }

                    $('#contact-form input, #contact-form textarea').each(function() {
                        $(this).val('');
                    })

                    $.toast({
                        heading: "<?= $lang('success')?>",
                        text: "<?= $lang('thanks_for_contacting_with_us')?>",
                        showHideTransition: 'slide',
                        hideAfter: 5000,
                        icon: 'info',
                        position: 'top-center'
                    });
                },
                complete: function() {

                }
            });
        })
    </script>
</define>