<?php

use Application\Helpers\UserHelper;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');
?>

<div class="iq-card suggestion-card-container">
    <div class="iq-card-header d-flex justify-content-between">
        <div class="iq-header-title">
            <h4 class="card-title font-size-18">Follow some of our best advisors</h4>
        </div>
    </div>
    <div class="iq-card-body text-center">
        <div class="owl-carousel suggestion-carousel">
            <?php foreach ($suggests as $user) : ?>
                <div class="item">
                    <div class="suggestion-card d-flex justify-content-center align-items-center flex-column p-4">
                        <div class="profile-img">
                            <img src="<?= UserHelper::getAvatarUrl('fit:300,300', $user['id']); ?>" alt="profile-img" class="avatar-50 img-fluid" />
                        </div>
                        <h3 class="font-size-18 text-nowrap w-100"><a href="<?= URL::full('profile/' . $user['id']); ?>">
                                <?= htmlentities($user['name']); ?>
                                <?php if ($user['account_verified']) echo '<i class="fas fa-check-circle color-primary"></i>' ?>
                            </a></h3>
                        <p class="mb">@<?= $user['username']; ?></p>
                        <button type="submit" class="btn btn-secondary d-none"><?= $lang('following'); ?></button>
                        <button type="submit" class="btn btn-primary" onclick="onclickFollow(this, <?= $user['id'] ?>);"><?= $lang('follow'); ?></button>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</div>
<define header_css>
    <style>
        .suggestion-card-container .item {
            border-right: 1px solid var(--iq-border-light);
        }

        .suggestion-card-container .owl-item:last-child .item {
            border-right: none;
        }

        .suggestion-card img {
            width: 80px !important;
            height: 80px !important;
        }

        .suggestion-card-container .iq-card-body {
            padding: 0;
        }

        .suggestion-card-container .owl-dots {
            display: n;
        }

        /* @media screen and  (max-width: 900px) { */
        /* .suggestion-card img {
                width: 60px !important;
                height: 60px !important;
            } */
        /* } */
    </style>
</define>
<define footer_js>
    <script>
        $('.suggestion-carousel').owlCarousel({
            loop: false,
            responsive: {
                0: {
                    items: 1.6,
                    dots: false
                },
                700: {
                    items: 3,
                    dots: false
                }
            }
        });

        function onclickFollow(elm, entityId) {

            $.ajax({
                url: URLS.toggle_follow,
                data: {
                    id: entityId
                },
                dataType: 'JSON',
                accepts: 'JSON',
                type: 'POST',
                beforeSend: function() {

                },
                success: function(data) {
                    if (data.info !== 'success') {
                        return;
                    }
                },
                complete: function() {
                    // Follow
                    // toggle the follow icon
                    $(elm).addClass('d-none');
                    $(elm).prev().removeClass('d-none');
                    toast('primary', '<?= $lang('success'); ?>', '<?= $lang('follow_success'); ?>');

                }

            });


        }
    </script>
</define>