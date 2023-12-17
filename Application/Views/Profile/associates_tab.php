<?php

use Application\Helpers\UserHelper;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

$lang = Model::get('\Application\Models\Language');

?>
<div class="iq-card">
    <div class="iq-card-body">
        <h2 class="custom-text-right"><?= $lang('associates') ?></h2>
        <div class="friend-list-tab mt-2">
            <div class="container">

                <?php if ( !empty($associates) ): ?>
                    <div class="row following-tab-content">
                        <?php foreach ( $associates as $associate ): ?>
                            <?php
                            echo  View::include('Profile/user_profile', [
                                'user' => $associate
                            ]);
                            ?>
                        <?php endforeach; ?>
                    </div>
                    <?php // Load more follower
                    if ( $limit == count($associates) ): ?>
                        <div class="load-more pl-4">
                            <a href="#" class="following-load-more-click"><?= $lang('more'); ?> <i class="ri-arrow-right-s-line"></i></a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                <p class="text-center"><?= $lang('no_associate'); ?></p>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>
</div>
<define footer_js>
    <script>
        (function(_scope) {
            var skip = <?= $limit ?>;

            $('.following-load-more-click').on('click', function(e) {
                e.preventDefault();

                var oldHtml = $(this).html();
                $(this).html('<?= $lang('loading') ?>');
                var $self = $(this);

                $.ajax({
                    url: URLS.more_following,
                    data: {
                        skip: skip,
                        userId: <?= $user['id']; ?>
                    },
                    type: 'POST',
                    dataType: 'JSON',
                    accepts: 'JSON',
                    success: function( data ) {
                        if ( data.info !== 'success' )
                        {
                            return;
                        }

                        var f = data.payload.followings;
                        f = f.join(',');

                        $('.following-tab-content').append(f);

                        // else update the row.
                        $self.html(oldHtml);

                        skip = data.payload.skip;

                        if ( !data.payload.dataAvl ) $self.parent().remove();
                    },
                    complete: function() {

                    }
                });
            });

        })(window);
    </script>
</define>