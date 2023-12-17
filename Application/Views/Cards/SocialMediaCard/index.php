<?php

use Application\Helpers\UserHelper;
use Application\Models\UserSettings;
use System\Core\Model;
use System\Helpers\URL;

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


<body style="margin: 0; text-align: right;direction: rtl;   font-family: Headline;">
<table
    id="content"
    style="background: url(<?= URL::asset('/Application/Assets/business_card_and_poster/bg.svg')?>) no-repeat , #3F4AAA;  background-position:right ;     background-size: 75%; min-height: 400px;min-width:1200px;border-spacing: 0; margin: 0 auto; padding: 50px;">
    <tr>
        <td>&nbsp;</td>
        <td style="text-align: left;"><img src="<?= URL::asset('/Application/Assets/business_card_and_poster/logo-ww.svg')?>" alt=""></td>
    </tr>
    <tr>
        <td>
                <span style="width: 50%; text-align: center;display: block;">
                    <?php
                    $userSM = Model::get(UserSettings::class);
                    $avatar = $userSM->take($user['id'], UserSettings::KEY_AVATAR);
                    $avatarPath = dirname(dirname(dirname(__DIR__))) . "/Uploads/{$avatar}";

                    if (is_null($avatar)) {
                        $avatarPath = dirname(dirname(dirname(__DIR__))) . "/Uploads/default-avatar.jpg";
                    }


                    ?>

                    <img src="data:image/png;base64,<?= base64_encode(file_get_contents($avatarPath)); ?>" alt="" style="margin: 0 auto; width: 170px; height: 170px; border-radius: 50%; object-fit: cover; -webkit-filter: grayscale(100%); /* Safari 6.0 - 9.0 */
                    filter: grayscale(100%);">
                    <h2 style="    line-height: 60px; margin: 0;font-size: 35px; font-weight: bold; color: #fff;"> <?= $user['name'] ?></h2>
                <p style="margin: 0;font-size: 25px; font-weight: 400; color: #fff;"><?= $user['username'] ?>@
                </p>

                </span>
        </td>

    </tr>

    <tr>


        <td
            style="color: #fff;  ; font-size:30px;">
                    <span style="width: 50%; text-align: center;display: block;">

                    يسعدني التواصل معكم من خلال

                    منصة <b>تيلي ان</b>
                    </span>

        </td>
        <td style="text-align: left;">
            <img src="data:image/png;base64,<?= base64_encode(file_get_contents(URL::asset('Storage/BusinessCards/qr_code_profile_'. $user['id'] .'.png'))); ?>" alt="">
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
            url: "<?= URL::full('ajax/social-media-card/send') ?>",
            data: {
                data: imgDataUrl
            },
            beforeSend: function () {

            },
            success: function (data) {
            },
            complete: function () {

            }
        })


        });
        });


    </script>
</define>