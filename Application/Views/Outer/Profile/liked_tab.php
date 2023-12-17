<?php

use Application\Helpers\UserHelper;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

$lang = Model::get('\Application\Models\Language');

?>
<div class="iq-card-body p-0">
    <div class="row">
        <!-- <div class="col-lg-4">
            //<?php
            //View::include('Profile/followers_widget', [
            //    'followers' => $followers
            //]);
            //?>
            //<?php
            //View::include('Profile/followings_widget', [
            //    'followings' => $followings
            //]);
            //?>
        </div> -->
        <div class="col-lg-12">
            <div class=" p-0">
                <div class="row feed-row">
                    <?php if (!empty($feeds)) : ?>
                        <?php foreach ($feeds as $liked) : ?>
                            <div class="col-sm-12  feed-col mx-auto">
                                <?php View::include('Feed/static_feed', [
                                    'feed' => $liked
                                ]) ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="col-sm-12">
                            <div class="iq-card">
                                <div class="iq-card-body text-center">
                                    <?= $lang('no_tweets'); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (count($feeds) == $limit) : ?>
                    <div class="col-sm-12 text-center feed-loader">
                        <img src="<?= URL::asset("Application/Assets/images/page-img/page-load-loader.gif"); ?>" alt="loader" style="height: 100px;">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<define footer_js>
    <script>
        var lastFeedId = <?= !empty($feeds) ? $feeds[count($feeds) - 1]['id'] : 0; ?>;
        var firstFeedId = <?= !empty($feeds) ? $feeds[0]['id'] : 0; ?>;
        var isBusyLoadMore = false;

        <?php if (count($feeds) === $limit) : ?>
            $(window).on('scroll', function() {
                if ($(this).scrollTop() >= $(document).height() - $(this).height() - 100) {
                    // ajax call get data from server and append to the div
                    loadMoreFeed(lastFeedId - 1, true);
                }
            });
        <?php endif; ?>

        // load more feed.
        function loadMoreFeed(lastId, append, callback) {
            if (isBusyLoadMore) return;

            $.ajax({
                url: URLS.more_feed_liked,
                type: 'POST',
                data: {
                    fromId: lastId,
                    profileId: '<?= $entityUser['id'] ?>'
                },
                beforeSend: function() {
                    isBusyLoadMore = true;
                },
                success: function(data) {
                    console.log(data);
                    if (data.info !== 'success') {
                        return;
                    }

                    var feeds = data.payload.feeds;
                    if (!append) $('.feed-row .feed-col').remove();

                    feeds.forEach(function(v) {
                        $('.feed-row').append('<div class="col-sm-12  feed-col">' + v.feed + '</div>');
                        console.log(v.feedId);
                        lastFeedId = v.feedId;
                    });


                    if (!data.payload.dataAvl) {
                        $('.feed-loader').remove();
                    }

                    callback && callback(feeds);

                },
                complete: function() {
                    isBusyLoadMore = false;
                }
            })
        }

       
    </script>
</define>