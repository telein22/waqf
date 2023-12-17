<?php

namespace Application\ThirdParties\Whatsapp;

use Application\Helpers\AppHelper;

class WhatsappMessages
{
    public static function verificationCode(string $username, string $code): string
    {
        return <<<MESSAGE
Hello {$username}
                    
رمز التحقق الخاص بك هو:
Your verification token is as follows:

{$code}

Regards,
TeleIn Team
support@telein.net
MESSAGE;
    }

    public static function greetingAfterRegistration(string $username): string
    {
        return <<<MESSAGE
حياك الله يا {$username} ونشكر لك انضمامك معنا في منصة tele in ونرجو ان تنال على استحسانك. 

بإمكانك البحث عن اصحاب الاختصاص  والتواصل معهم داخل المنصه من خلال الرسائل او المكالمات الفرديه او حضور الجلسات المتاحه لديهم 

كما يمكنك اكمال ملفك الشخصي والاستفادة من خدمات المنصه كي تتيح للاخرين التواصل معك. 

            تحياتنا وتقديرنا لك
MESSAGE;

    }

    public static function shareLinksAfterVerification(string $twitter, string $linkedin): string
    {
        return <<<MESSAGE
يمكنك إرسال تغريدة في تويتر مزودة برابط لصفحتك الخاصة في تيلي ان لتسهل لمتابعينك الوصول إليها والتواصل معك في منصة تيلي ان وذلك من خلال الرابط التالي

{$twitter}

أيضاً تستطيع انشاء منشور في لينكد ان مزود برابط لصفحتك الخاصة في تيلي ان لتسهل لمتابعينك الوصول إليها والتواصل معك في منصة تيلي ان وذلك من خلال الرابط التالي

{$linkedin}
MESSAGE;
    }

    public static function sendBusinessCard(string $username): string
    {
        return <<<MESSAGE
أهلاً بك {$username}

نهدي لك بطاقة تعريفية خاصة بك تستطيع نشرها ومشاركتها مع من تريد لتسهل الوصول لصفحتك الخاصة والتواصل معك في منصة تيلي ان  

ملاحظة: عند كل مره يتم تغيير صورتك الشخصية في المنصة سوف يتم ارسال بطاقة تعريفية اخرى محدثه بالصورة الجديدة 

تحياتنا لك
MESSAGE;
    }

    public static function confirmCallCompleted(string $username, string $advisorName, int $callId): string
    {
        $baseURL = AppHelper::getBaseUrl();

        return <<<MESSAGE
أهلا بك {$username} 

يسعدنا تقييمك للخدمة المقدمة من {$advisorName}

{$baseURL}/review/{$callId}/call

شكرا لك
MESSAGE;
    }

    public static function confirmWorkshopCompleted(string $username, string $workshopName, int $workshopId): string
    {
        $baseURL = AppHelper::getBaseUrl();

        return <<<MESSAGE
أهلاً بك {$username} 

يسعدنا تقييم حضورك للجلسة تحت عنوان "{$workshopName}" وذلك عن طريق الرابط التالي 

{$baseURL}/review/{$workshopId}/workshop

شكرا لك
MESSAGE;
    }

    public static function confirmCallRequestHasBeenClosed(string $username, int $advisorId, string $advisorName): string
    {
        $baseURL = AppHelper::getBaseUrl();

        return <<<MESSAGE
أهلاً بك {$username} 

لقد تم جدولة مكالمات متاحه للحجز للمستخدم {$advisorName} ويمكنك الاطلاع عليها والحجز مع {$advisorName} وذلك عن الطريق الرابط التالي:

{$baseURL}/calls/find/{$advisorId}

شكرا لك
MESSAGE;
    }

    public static function confirmBookingWorkshop(string $username, string $workshopName): string
    {
        return <<<MESSAGE
أهلاً بك {$username} 

قد تم تسجيلك لحضور الجلسة بنجاح تحت عنوان "{$workshopName}" 
 
سوف نقوم بإرسال رابط لحضور هذه الجلسة قبل موعدها ب ١٥ دقيقة هنا وعبر الايميل
  
 لاضافة موعد وتفاصيل الجلسة الى التقويم بجهازك الرجاء الضغط على الملف التالي 

شكرا لك 
MESSAGE;
    }

    public static function confirmCallBookingForTheParticipant(string $creatorName, string $ownerName): string
    {
        return <<<MESSAGE
أهلا بك {$creatorName}

لقد تم حجز مكالمة مع {$ownerName} بنجاح، وسوف يتم ارسال رابط الاتصال عبر الواتس اب والايميل قبل موعد الاتصال ب 15 دقيقه

كما تستطيع اضافة موعد الاتصال في التقويم الخاص بك بالملف المرسل اليك

شكرا لك
MESSAGE;
    }

    public static function confirmCallBookingForTheOwner(string $ownerName, string $creatorName): string
    {
        $baseURL = AppHelper::getBaseUrl();

        return <<<MESSAGE
أهلا بك {$ownerName}

لديك حجز مكالمه مدفوعه من {$creatorName} للاطلاع على التفاصيل يرجى الضغط على الرابط التالي:

{$baseURL}/calls/a

علماً انه سوف يصلك رابط المكالمه قبل موعد المكالمه ب 15 دقيقه 

كما يمكنك اضافة هذا الموعد على التقويم الخاص بك عن الطريق الملف المرسل اليك بعد هذه الرسالة

شكراً لك 
MESSAGE;
    }

    public static function confirmAfterSendingMessageForSender(string $senderName, string $receiverName): string
    {
        return <<<MESSAGE
أهلاً بك {$senderName} 

لقد تم ارسال رسالتك ل {$receiverName} بنجاح، وسوف يتم اشعارك عبر الواتس اب والايميل اذا تم الرد على رسالتك

شكراً لك
MESSAGE;
    }

    public static function confirmAfterSendingMessageForReceiver(string $senderName, string $receiverName, int $conversationId): string
    {
        $baseURL = AppHelper::getBaseUrl();

        return <<<MESSAGE
أهلاً بك {$receiverName} 

لديك رسالة مدفوعه مرسلة من {$senderName} بإمكانك الاطلاع على الرسالة على الرابط التالي 

{$baseURL}/messaging/view/{$conversationId}

علماً ان مدة صلاحية الرد على هذه الرسالة هي ٤٨ ساعه من الان 

شكرا لك 
MESSAGE;
    }

    public static function confirmPayment(string $name): string
    {
        return <<<MESSAGE
أهلا بك {$name} 

لقد تم الدفع بنجاح 
ونشكر لك استخدامك احد خدماتنا داخل منصة تيلي ان، وبإمكانك الاطلاع على تفاصيل الفاتورة في المرفق  التالي 

شكرا لك
MESSAGE;
    }

    public static function confirmReplayToMessage(string $senderName, string $receiverName, int $conversationId): string
    {
        $baseURL = AppHelper::getBaseUrl();

        return <<<MESSAGE
أهلاً بك {$receiverName}

لقد تم الرد على رسالتك من {$senderName} وبإمكانك الاطلاع عليها من خلال الرابط التالي 

{$baseURL}/messaging/view/{$conversationId}

كما يسعدنا تقييمك للخدمة المقدمة من {$senderName}

{$baseURL}/review/{$conversationId}/conversation 

شكراً لك
MESSAGE;
    }

    public static function confirmCompleteCall(string $senderName, string $receiverName, int $callId): string
    {
        $baseURL = AppHelper::getBaseUrl();

        return <<<MESSSAGE
أهلاً بك {$receiverName}

يسعدنا تقييمك للخدمة المقدمة من {$senderName}

{$baseURL}/review/{$callId}/call

شكراً لك
MESSSAGE;
    }

    public static function confirmCompleteWorkshop(string $receiverName, $workshopTitle, int $workshopId): string
    {
        $baseURL = AppHelper::getBaseUrl();

        return <<<MESSAGE
أهلاً بك {$receiverName} 

يسعدنا تقييم حضورك للجلسة تحت عنوان "{$workshopTitle}" وذلك عن طريق الرابط التالي 

{$baseURL}/review/{$workshopId}/workshop

شكرا لك
MESSAGE;
    }

    public static function confirmAddingWithdrawalRequestForAdvisor(string $username, float $amount): string
    {
        return <<<MESSAGE
أهلاً بك {$username} 

لقد تم إضافة طلبك بنجاح, يمكنك متابعة حالة الطلب من خلال قائمة طلبات سحب الأموال, كما سيتم إرسال حالة الطلب لك عبر الواتساب

المبلغ المراد للسحب هو {$amount} ريال سعودي 

شكرا لك
MESSAGE;
    }

    public static function confirmAddingWithdrawalRequestForAdmin(string $username, float $amount): string
    {
        return <<<MESSAGE
لقد قام المستخدم {$username} بتقديم طلب سحب أرباح بقيمة {$amount} ريال سعودي.

بإنتظار مراجعتكم والموافقة على الطلب
MESSAGE;
    }

    public static function markWithdrawalRequestAsPending(string $username): string
    {
        return <<<MESSAGE
أهلاً بك {$username} 

طلب سحب الأموال الذي تقدمت به هو الآن قيد الأنتظار

 كما سيتم إبلاغك بتحديثات الطلب بشكل مستمر لتتمكن من متابعة حالته.
 
شكرا لك
MESSAGE;
    }

    public static function markWithdrawalRequestAsApproved(string $username): string
    {
        return <<<MESSAGE
أهلاً بك {$username} 

لقد تمت الموافقة على طلب سحب الأموال الذي تقدمت به من قبل الإدارة.

سيتم تحويل طلبكم إلى القسم المالي لدينا ليتم دراسته والبدء بمعالجته.

كما سيتم إبلاغك بتحديثات الطلب بشكل مستمر لتتمكن من متابعة حالته.
 
شكرا لك
MESSAGE;
    }

    public static function markWithdrawalRequestAsProcessing(string $username): string
    {
        return <<<MESSAGE
أهلاً بك {$username} 

لقد أصبح طلب سحب الأمول الذي تقدمت به قيد المعالجة.

كما سيتم إبلاغك بتحديثات الطلب بشكل مستمر لتتمكن من متابعة حالته.
 
شكرا لك
MESSAGE;
    }

    public static function markWithdrawalRequestAsCompleted(string $username, float $walletBalance): string
    {
        return <<<MESSAGE
أهلاً بك {$username} 

لقد تم تحويل المبلغ الذي طلبت سحبه إلى حسابك البنكي المربوط مع منصة تيلي إن

رصيد المحفظة الحالي هو:
({$walletBalance}) ريال سعودي
 
شكرا لك
MESSAGE;
    }

    public static function markWithdrawalRequestAsRejected(string $username): string
    {
        return <<<MESSAGE
أهلاً بك {$username} 

لقد تم رفض طلب سحب الأموال الخاص بك, يرجى مراجعة الإدارة لمناقشة الأمر
 
شكرا لك
MESSAGE;
    }

    public static function reminderForUpcomingWorkshop(string $username, int $workshopId, string $workshopTitle): string
    {
        $baseURL = AppHelper::getBaseUrl();
        $sessionUrl = $baseURL . "/waiting-room/sessions/{$workshopId}";

        return <<<MESSAGE
أهلا بك {$username} 

لديك جلسة مجدولة تحت عنوان "{$workshopTitle}" 

بامكانك حضور الجلسة عن طريق الرابط التالي

{$sessionUrl}

شكرا لك
MESSAGE;
    }

    public static function reminderForUpcomingCall(string $username, string $secondParty, int $callId): string
    {
        $baseURL = AppHelper::getBaseUrl();
        $url = $baseURL . "/waiting-room/calls/{$callId}";

        return <<<MESSAGE
أهلاً بك {$username} 

لديك مكالمه مجدولة مع {$secondParty} يرجى الضغط على الرابط التالي:

{$url} 

شكرا لك 
MESSAGE;
    }

}