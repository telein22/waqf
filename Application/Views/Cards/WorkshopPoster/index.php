<?php

use Application\Helpers\UserHelper;
use Application\Models\UserSettings;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galsa</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>
<style>
    @font-face {
        font-family: Headline;
        src: url(<?= URL::asset('/Application/Assets/business_card_and_poster/Co-Headline.otf')?>);
        font-weight: bold;
    }
    @font-face {
        font-family: Headline;
        src: url(<?= URL::asset('/Application/Assets/business_card_and_poster/Co-Headline-Light.otf')?>);
        font-weight: 400;

    }
</style>


<body style="margin: 0; text-align: right;direction: rtl;   font-family: Headline;
">
<table
    id="content"
    style="background: url(<?= URL::asset('/Application/Assets/business_card_and_poster/shape.png')?>)no-repeat; background-size: 100%; background-position:100px center ; min-height: 400px;width: 600px;border-spacing: 0; margin: 0 auto; padding: 50px 0;">
    <tr style="height: 150px;">
        <td style=" display: flex; justify-content: space-evenly; align-items: center;">
                <span style="text-align: center;">
                    <h2 style="margin: 5px 0;font-size: 25px; font-weight: 400; color: var(--iq-primary);">جلسة بعنوان</h2>
                    <h1 style="
                    line-height: 45px;
                margin: 5px 0;font-size: 40px; font-weight: bold; color: var(--iq-primary);">" <?= $workshop['name'] ?> "</h1>
                    <h2 style="margin: 5px 0;font-size: 25px; font-weight: 400; color: var(--iq-primary);"> <?= $user['name'] ?></h2>

                    <?php
                    $userSM = Model::get(UserSettings::class);
                    $avatar = $userSM->take($user['id'], UserSettings::KEY_AVATAR);
                    $avatarPath = dirname(dirname(dirname(__DIR__))) . "/Uploads/{$avatar}";

                    if (is_null($avatar)) {
                        $avatarPath = dirname(dirname(dirname(__DIR__))) . "/Uploads/default-avatar.jpg";
                    }


                    ?>

                </span> <img src="data:image/png;base64,<?= base64_encode(file_get_contents($avatarPath)); ?>" alt="" style=" width: 170px; height: 170px; border-radius: 50%; object-fit: cover; -webkit-filter: grayscale(100%); /* Safari 6.0 - 9.0 */
                filter: grayscale(100%);">
        </td>

    </tr>

    <tr>
        <td>

            <p
                style="width: 85% ;color: var(--iq-primary);  ; font-size: 18px;  line-height: 35px;margin: 35px auto ; text-align: center;">
                <?= $workshop['desc'] ?>

            </p>

        </td>

    </tr>
    <tr style=" width: 100%;">
        <td>
            <table style=" width: 100%; ">
                <tr>

                    <td style="width:32%; padding-inline-start: 4%;">
                        <p style="display: flex;align-items: center;">
                            <img src="<?= URL::asset('/Application/Assets/business_card_and_poster/calendar (1).png')?>" alt="" style="width: 40px;margin-inline-end: 10px;height: 40px; padding-inline-end: 10px;  border-left: 2px solid var(--iq-primary);">
                            <span
                                style="color: var(--iq-primary);  ; font-size: 18px;  line-height: 28px; margin-bottom: 10px;margin-top: 0;">
                                يوم <?= $workshop['day']?>
                                <br>
                                <?= $workshop['date']?></span></p>
                        <p style="display: flex;align-items: center;">
                            <img src="<?= URL::asset('/Application/Assets/business_card_and_poster/clock (1).png')?>" alt="" style="width: 40px;margin-inline-end: 10px; padding-inline-end: 10px; height: 40px; border-left: 2px solid var(--iq-primary);">
                            <span
                                style="color: var(--iq-primary);  ; font-size: 18px;  line-height: 28px; margin-bottom: 0px; margin-top: 0;">
                                    تبدأ الساعة
                                    <br>
                                <?= $workshop['time']?></span>
                        </p>

                    </td>
                    <td style="width:32%;    text-align: center;">
                        <h3 style="margin: 0px 0 10px 0;font-size: 28px; font-weight: bold; color: var(--iq-primary);">للتسجيل</h3>
                        <img src="data:image/png;base64,<?= base64_encode(file_get_contents(URL::asset("Storage/BusinessCards/qr_code_poster_{$feedId}.png"))); ?>"   alt="" style="    width: 130px;
                          height: 130px;">
                        <span style="font-size: 16px; font-weight: 400; color: var(--iq-primary); display:block"><?= $user['username']?>@</span>
                    </td>
                    <td style="width:32%;     text-align: center;">






                        <img src="<?= URL::asset('/Application/Assets/business_card_and_poster/logo.png')?>" alt="" style="width: 75%;">


                    </td>
                </tr>
            </table>
        </td>


    </tr>

</table>


</body>

</html>

<define footer_js>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            var content = document.getElementById('content');

            html2canvas(content).then(canvas => {
                const imgDataUrl = canvas.toDataURL('image/png');

                $.ajax({
                    url: "<?= URL::full('ajax/workshop-poster/send') ?>",
                    data: {
                        data: imgDataUrl
                    },
                    beforeSend: function () {
                        // alert('beforeSend');
                    },
                    success: function (data) {

                        // alert('success');
                    },
                    complete: function () {
                        window.parent.workshopStorringOnComplete()
                    }
                })
            });
        });


    </script>
</define>