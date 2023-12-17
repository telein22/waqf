<?php

use Application\Models\Language;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get(Language::class);

?>

<section class=" terms-service"  style="text-align: right">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1 class="text-center mb-5"><?= $lang('faq'); ?></h1>
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-heading">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse" aria-expanded="false" aria-controls="flush-collapse">
                                التسجيل في منصة تيلي ان 
                            </button>
                        </h2>
                        <div id="flush-collapse" class="accordion-collapse collapse" aria-labelledby="flush-heading" data-parent="#accordionFlushExample">
                            <div class="accordion-body">
                            بعد إتمام تسجيلك في المنصة يرجى اكمال بياناتك الشخصية لتتمكن من حضور/ إنشاء الجلسات، أو حضور/ إنشاء المكالمات، أوالتمكين من استقبال وإرسال الرسائل. 
يمكنك اتمام التسجيل بالضغط على رمز القائمة يمين أعلى الصفحة، ثم اختيار الملف الشخصي والذهاب الى أيقونة تعديل الملف الشخصي. سوف تظهر لك معلوماتك العامة وروابط مواقع التواصل الاجتماعي الخاصة بك. أيضا يمكنك تعديل كلمة المرور الخاصة بحسابك.</div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            البحث عن التخصصات المختلفة
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-parent="#accordionFlushExample">
                            <div class="accordion-body">يمكنك إيجاد التخصصات المختلفة بالضغط على رمز القائمة يمين أعلى الصفحة، ثم النقر على بحث أو استكشف، أو تصفح جميع التخصصات الموجودة <a href="<?= URL::full('dashboard') ?>" class="text-primary">بالضغط هنا</a > .</div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                            طريقة إنشاء جلسة 
                            </button>
                        </h2>
                        <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-parent="#accordionFlushExample">
                            <div class="accordion-body">
                            يمكنك مشاهدة الجلسات بالضغط على رمز القائمة يمين أعلى الصفحة، ثم النقر على جلساتي. 
سوف تظهر لك جلساتك الصادرة والواردة ويمكنك أيضا إنشاء جلسة بتعبئة البيانات المطلوبة: اسم الجلسة، ووصف عن محتوى الجلسة، والتاريخ المناسب لك. علما بأن كتابة الوقت بالدقائق وتحديد عدد الحضور المراد في الجلسة.  
 يمكن أيضا اعلان عن جلستك او اي جلسة اخرى وذلك من خلال الضغط على ايقونة مشاركة، حيث نتيح لك خاصية مشاركتها عبر الواتساب او الايميل أو نسخ الرابط ونشره في اي من حساباتك في التواصل الاجتماعي. 
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                            طريقة إنشاء مكالمة
                            </button>
                        </h2>
                        <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-parent="#accordionFlushExample">
                            <div class="accordion-body">
                            يمكنك مشاهدة المكالمات بالضغط على  رمز القائمة يمين أعلى الصفحة، ثم النقر على مكالماتي. 
سوف تظهر لك مكالماتك الصادرة والواردة ويمكنك أيضا إنشاء مكالمة باختيار الوقت والتاريخ المناسب لك والمبلغ المراد من المستفيدين، مدة المكالمة 15 دقيقة. 
تستطيع ايضا جدولة العديد من المكالمات في أوقات مختلفه حسب ما يناسب جدول اعمالك.   
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                            طريقة استقبال الرسائل
                            </button>
                        </h2>
                        <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-parent="#accordionFlushExample">
                            <div class="accordion-body">
                            يمكنك مشاهدة الرسائل بالضغط على رمز القائمة يمين أعلى الصفحة، ثم النقر على رسائلي. 
سوف تظهر لك رسائلك الصادرة والواردة، ويمكنك أيضا ضبط خدمة الرسائل بتحديد السعر المراد من المستفيدين وتفعيل الرسائل.
يرجى العلم في حالة استقبالك للرسائل يجب الرد على الرسائل خلال مدة اقصاها ٤٨ ساعة، لتتمكن من الحصول على مبلغ الرسالة. وفي حال عدم الرد لطلب الرسالة، سوف يسترجع المبلغ لمقدم الطلب.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                            كيف اعرف أرباحي 
                            </button>
                        </h2>
                        <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-parent="#accordionFlushExample">
                            <div class="accordion-body">
                            يمكنك الاطلاع على أرباحك بالضغط على رمز القائمة يمين أعلى الصفحة، ثم النقر على أرباحي.
سوف تظهر لك جميع طلباتك في حال أنشأت جلسة أو مكالمة أو تم تفعيل خاصية الرسائل. 
الإجمالي هو عدد الطلبات التي تم الانتهاء منها. 
تعمل الان هي الطلبات التي لازالت قائمة تحت الاجراء.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingSix">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                            كيف يمكنني معرفة الجلسات المتوفرة للحجز 
                            </button>
                        </h2>
                        <div id="flush-collapseSix" class="accordion-collapse collapse" aria-labelledby="flush-headingSix" data-parent="#accordionFlushExample">
                            <div class="accordion-body">
                            يمكنك الاطلاع على جميع الجلسات المتاحة للحجز بالضغط على رمز القائمة يمين أعلى الصفحة، ثم النقر على الجلسات المتاحة للحجز، سوف تظهر لك قائمة بالجلسات المتاحة للحجز من قبل الاشخاص المتابعون من قبلك أو يمكنك ايضا الاطلاع على الجلسات المتاحة الاخرى  .
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingSeven">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseSeven">
                            التواصل معنا
                            </button>
                        </h2>
                        <div id="flush-collapseSeven" class="accordion-collapse collapse" aria-labelledby="flush-headingSeven" data-parent="#accordionFlushExample">
                            <div class="accordion-body">
                            تستطيع إرسال استفسارك بالضغط على رمز القائمة يمين أعلى الصفحة، ثم النقر على دعم العملاء، و كتابة استفسارك واختيار طريقة الارسال عن طريق البريد الالكتروني أو الواتساب 
يمكنك أيضا التواصل معنا من خلال البريد الالكتروني Support@telein.net   
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<define header_css>
    <style>
        body {
            background: #fafafb;
        }

        .accordion-item {
            margin-bottom: 25px;
            border-bottom: none;
            -webkit-box-shadow: 0px 0px 20px 0px rgba(44, 101, 144, 0.1);
            box-shadow: 0px 0px 20px 0px rgba(44, 101, 144, 0.1);
            border-radius: 5px !important;
            overflow: hidden;
        }
        .accordion-button::after {
            margin-left: unset;
            margin-right: auto;
        }
    </style>
</define>