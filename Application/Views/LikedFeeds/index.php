<?php

use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 row m-0 p-0">
            <div class="container">
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="iq-card position-relative inner-page-bg bg-primary" style="height: 150px;">
                            <div class="inner-page-title">
                                <h3 class="text-white"><?= $lang('liked_tweets'); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row feed-row">
                    <?php if (!empty($feeds)) : ?>
                        <?php foreach ($feeds as $feed) : ?>
                            <div class="col-sm-12  feed-col">
                                <?php View::include('Feed/feed', [
                                    'userInfo' => $userInfo,
                                    'feed' => $feed
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
                <?php if (count($feeds) == $feedLimit) : ?>
                    <div class="col-sm-12 text-center feed-loader">
                        <img src="<?= URL::asset("Application/Assets/images/page-img/page-load-loader.gif"); ?>" alt="loader" style="height: 100px;">
                    </div>
                <?php endif; ?>
                <div class="feed-new-loading bg-info">
                    <?= $lang('loading'); ?>
                </div>
            </div>

        </div>

    </div>
</div>

<define header_css>
    <style>
        .feed-new-notice,
        .feed-new-loading {
            position: fixed;
            width: 200px;
            padding: 10px;
            bottom: -100px;
            transition: bottom .3s ease-in-out;
            left: 50%;
            margin-left: -100px;
            cursor: pointer;
            /* background-color: aqua; */
            white-space: nowrap;
            border-radius: 10px;
            text-align: center;
            /* font-weight: bold; */
            /* color: white; */
        }

        .feed-new-notice.show,
        .feed-new-loading.show {
            bottom: 20px;
        }
    </style>
</define>
<define footer_js>
    <script>
        function onPostComplete(data) {
            if (data.payload.workshop) {
                toast('primary', '<?= $lang('success'); ?>', '<?= $lang('workshop_created') ?>')
            }

            var postId = data.payload.insertId;

            $.ajax({
                url: URLS.get_feed,
                type: 'POST',
                dataType: 'JSON',
                accepts: 'JSON',
                data: {
                    postId: postId
                },
                success: function(data) {
                    if (data.info === 'error') return;

                    $('.feed-textarea-col').after(
                        '<div class="col-sm-12">' + data.payload + '</div>'
                    );

                    new Feed(postId, []);

                },
                complete: function() {

                }
            });
        }


        var lastFeedId = <?= !empty($feeds) ? $feeds[count($feeds) - 1]['id'] : 0; ?>;
        var firstFeedId = <?= $feeds[0]['id'] ?>;
        var isBusyLoadMore = false;

        $(window).on('scroll', function() {
            if ($(this).scrollTop() >= $(document).height() - $(this).height() - 100) {
                // ajax call get data from server and append to the div
                loadMoreFeed(lastFeedId - 1, true);
            }
        });

        // load more feed.
        function loadMoreFeed(lastId, append, callback) {
            if (isBusyLoadMore) return;

            $.ajax({
                url: URLS.more_feed_liked,
                data: {
                    fromId: lastId,
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
                        new Feed(v.feedId, v.commentIds);
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

        <?php if (!empty($feeds)) : ?>

                (function(_scope) {

                    var before = function() {
                        return firstFeedId;
                    };

                    var after = function(count, values) {
                        if (values > 0) {
                            $('.feed-new-notice').addClass('show');
                        }

                    };

                    ping.subscribe('feed.check', before, after);
                })(window);

        <?php endif; ?>
    </script>
</define>