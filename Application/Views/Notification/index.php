<?php

use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

$lang = Model::get('\Application\Models\Language');
?>
<div class="container">
    <div class="row mt-5 justify-content-center">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card position-relative inner-page-bg bg-primary" style="height: 150px;">
                        <div class="inner-page-title">
                            <h3 class="text-white"><?= $lang('notifications'); ?></h3>
                            <p class="text-white"><?= $lang('advisor'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-body noti-row">
                            <?php if( !empty( $notifications ) ) : ?>
                                <?php foreach ($notifications as $notification) : ?>
                                    <?php View::include('Notification/item', array(
                                        'notification' => $notification
                                    )) ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="mb-0 pb-0"><?= $lang('notification_no_notifications') ?></p>
                            <?php endif; ?>
                        </div>
                        <?php if ( count($notifications) == $limit ): ?>
                        <div class="load-more pl-4">
                            <a href="#" class="load-more-click"><?= $lang('more'); ?> <i class="ri-arrow-right-s-line"></i></a>
                        </div>
                        <div class="col-sm-12 text-center noti-loader d-none">
                            <img src="<?= URL::asset("Application/Assets/images/page-img/page-load-loader.gif"); ?>" alt="loader" style="height: 100px;">
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<define footer_js>
    <script>
        var lastNotiId = <?= !empty($notifications) ? $notifications[count($notifications) - 1]['id'] : 0; ?>;
        var isBusyLoadMore = false;

        $(".load-more-click").on('click', function(e) {
            e.preventDefault();
            loadMoreNotification( lastNotiId - 1, true);    
        })


        // load more notification.
        function loadMoreNotification(lastId, append, callback) {
            if (isBusyLoadMore) return;
            $(".noti-loader").removeClass('d-none');

            $.ajax({
                url: URLS.more_noti,
                data: {
                    fromId: lastId
                },
                beforeSend: function() {
                    isBusyLoadMore = true;
                },
                success: function(data) {
                    if (data.info !== 'success') {
                        return;
                    }

                    var notis = data.payload.notis;

                    notis.forEach(function(v) {
                        $('.noti-row').append(v.noti);
                        console.log(v.notiId);
                        lastNotiId = v.notiId;
                    });

                    if ( !data.payload.dataAvl ) {
                        $('.load-more').remove();
                    }
                    
                    callback && callback(notis);
                    
                },
                complete: function() {
                    $(".noti-loader").addClass('d-none');
                    isBusyLoadMore = false;
                }
            })
        }
    </script>
</define>