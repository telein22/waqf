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
<!-- background: url(./assets/shape.png)no-repeat; background-size: 100%; background-position:100px center ; -->
<body style="margin: 0; text-align: right;direction: rtl;  font-family: Headline;
">

<table
        id="content"
        style="min-height: 500px;width: 400px;border-spacing: 0; margin: 0 auto; background-color: #404AAB;  padding: 30px;">
    <tr>
        <td style="padding-bottom: 30px; text-align: end;">
            <img src="<?= URL::asset('/Application/Assets/business_card_and_poster/dark.png')?>" alt="" style="width: 130px;">
        </td>
    </tr>
    <tr>
        <td>
            <table style="background: url(<?= URL::asset('/Application/Assets/business_card_and_poster/shape.png') ?>) no-repeat ,#fff; background-size: 100%; background-position:100px center ; width: 100%;padding: 30px; border-radius: 35px; border-bottom-right-radius: 0;">
                <tr style="height: 150px;">
                    <td style="text-align: center;">
                        <?php
                            $userSM = Model::get(UserSettings::class);
                            $avatar = $userSM->take($user['id'], UserSettings::KEY_AVATAR);
                            $avatarPath = dirname(dirname(dirname(__DIR__))) . "/Uploads/{$avatar}";

                            if (is_null($avatar)) {
                                $avatarPath = dirname(dirname(dirname(__DIR__))) . "/Uploads/default-avatar.jpg";
                            }


                        ?>
                        <img src="data:image/png;base64,<?= base64_encode(file_get_contents($avatarPath)); ?>" alt="" style=" width: 170px; height: 170px; border-radius: 50%; object-fit: cover; -webkit-filter: grayscale(100%); /* Safari 6.0 - 9.0 */
                filter: grayscale(100%);">

                    </td>

                </tr>

                <tr>
                    <td style="text-align: center; padding: 30px 0;">
                        <h1 style="margin: 5px 0;font-size: 32px; font-weight: bold; color: #434CA2;"><?=  $user['name']; ?></h1>



                    </td>


                </tr>

                <tr style=" text-align: center;">
                    <td style=" text-align: center;">
                        <img src="data:image/png;base64,<?= base64_encode(file_get_contents(URL::asset("Storage/BusinessCards/qr_code_profile_{$user['id']}.png"))); ?>" alt="" style="    width: 130px;
                    height: 130px;">
                        <p style="font-size: 16px; font-weight: 400; color: #434CA2; margin-top: 0px; margin-bottom: 0;"><?= $user['username'] ?>@</p>
                    </td>


                </tr>
                <tr>
                    <td style="position: relative;"><span style="     position: absolute;
                    right: -37px;
                    bottom: -70px;
                    width: 40px;
                    height: 40px;
                    transform: rotate(138deg);
                    border-bottom: solid 20px #fff;
                    border-left: solid 20px #fff;
                    border-right: solid 20px transparent;
                    border-top: solid 20px transparent;"
                        ></span></td>
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
                    url: "<?= URL::full('ajax/business-card/send') ?>",
                    data: {
                        data: imgDataUrl
                    },
                    beforeSend: function () {

                    },
                    success: function (data) {

                    },
                    complete: function () {
                        if (typeof window.parent.businessCardStoringOnComplete === 'function') {
                            window.parent.businessCardStoringOnComplete()
                        }
                    }
                })
            });
        });


    </script>
</define>