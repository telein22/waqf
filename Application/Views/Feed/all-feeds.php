<?php

use Application\Helpers\DateHelper;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

?>

<div class="container">
    <div class="row feed-page-row">
        <div class="col-lg-8 row m-0 p-0">
            <div class="container">
                <div class="row feed-row">
                    
                    <!-- <?php // if (!empty($suggest)) : ?>
                        <div class="col-sm-12">
                            <?php // View::include('Feed/suggestion', ['suggests' => $suggest]); ?>
                        </div>
                    <?php // endif; ?> -->
                    <?php if (!empty($feeds)) : ?>
                        <?php foreach ($feeds as $feed) : ?>
                            <div class="col-sm-12  feed-col">
                                <?php View::include('Feed/feed', [
                                    'userInfo' => $userInfo,
                                    'feed' => $feed
                                ]) ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
                <?php if (count($feeds) == $feedLimit) : ?>
                    <div class="col-sm-12 text-center feed-loader">
                        <img src="<?= URL::asset("Application/Assets/images/page-img/page-load-loader.gif"); ?>" alt="loader" style="height: 100px;">
                    </div>
                <?php endif; ?>
                <div class="feed-new-notice bg-info">
                    <?= $lang('new_feed_available'); ?>
                </div>
                <div class="feed-new-loading bg-info">
                    <?= $lang('loading'); ?>
                </div>
            </div>

        </div>
        <div class="col-lg-4 d-sm-none d-md-block">
            <!-- <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Stories</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <ul class="media-story m-0 p-0">
                        <li class="d-flex mb-4 align-items-center">
                            <i class="ri-add-line font-size-18"></i>
                            <div class="stories-data ml-3">
                                <h5>Creat Your Story</h5>
                                <p class="mb-0">time to story</p>
                            </div>
                        </li>
                        <li class="d-flex mb-4 align-items-center active">
                            <img src="images/page-img/s2.jpg" alt="story-img" class="rounded-circle img-fluid">
                            <div class="stories-data ml-3">
                                <h5>Anna Mull</h5>
                                <p class="mb-0">1 hour ago</p>
                            </div>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                            <img src="images/page-img/s3.jpg" alt="story-img" class="rounded-circle img-fluid">
                            <div class="stories-data ml-3">
                                <h5>Ira Membrit</h5>
                                <p class="mb-0">4 hour ago</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-center">
                            <img src="images/page-img/s1.jpg" alt="story-img" class="rounded-circle img-fluid">
                            <div class="stories-data ml-3">
                                <h5>Bob Frapples</h5>
                                <p class="mb-0">9 hour ago</p>
                            </div>
                        </li>
                    </ul>
                    <a href="#" class="btn btn-primary d-block mt-3">See All</a>
                </div>
            </div> -->

            <!-- Change && to || to bring it back -->
            <?php // if ( !empty($aWorkshops) && !empty($bWorkshops) ): ?>
                <?php // View::include('Feed/upcoming_workshops', [
                    // 'aWorkshops' => $aWorkshops,
                    // 'bWorkshops' => $bWorkshops
                // ]) ?>
            <?php // endif; ?>
            <?php if ( !empty($aCalls) || !empty($bCalls) ): ?>
                <?php View::include('Feed/upcoming_calls', [
                    'aCalls' => $aCalls,
                    'bCalls' => $bCalls
                ]) ?>
            <?php endif; ?>
            <?php if ( !empty($aCons) || !empty($bCons) ): ?>
                <?php View::include('Feed/upcoming_conversations', [
                    'aCons' => $aCons,
                    'bCons' => $bCons
                ]) ?>
            <?php endif; ?>
            <div class="iq-card" id="trends">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title"><?= $lang('whats_trending'); ?></h4>
                    </div>
                    <!-- <div class="iq-card-header-toolbar d-flex align-items-center">
                        <div class="dropdown">
                            <span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false" role="button">
                                <i class="ri-more-fill"></i>
                            </span>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="">
                                <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="iq-card-body">
                    <ul class="media-story m-0 p-0">
                        <li class="font-weight-bold d-flex align-items-center heading"><?= $lang('hashtags'); ?></li>
                        <?php if (!empty($trends['hash'])) : ?>
                            <?php foreach ($trends['hash'] as $hash) : ?>
                                <li class="d-flex align-items-center ">
                                    <a href="<?= URL::full('search?q=' . rawurlencode($hash['tag'])); ?>"><?= $hash['tag'] ?> (<?= $hash['count'] ?>)</a>
                                </li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li class="d-flex align-items-center text-center"><?= $lang('no_hashtag_found') ?></li>
                        <?php endif; ?>
                        <li class="font-weight-bold heading d-flex align-items-center"><?= $lang('category'); ?></li>
                        <?php if (!empty($trends['specialty'])) : ?>
                            <?php foreach ($trends['specialty'] as $specs) : ?>
                                <li class="d-flex align-items-center ">
                                    <a href="<?= URL::full('search?spec[]=' . $specs['parent']['id'] . '&subSpec[]=' . $specs['id']); ?>"><?= $specs['parent']['specialty_' . $lang->current()] ?>/<?= $specs['specialty_' . $lang->current()] ?> (<?= $specs['count'] ?>)</a>
                                </li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li class="d-flex align-items-center text-center"><?= $lang('no_category_found') ?></li>
                        <?php endif; ?>
                        <!-- <li class="d-flex align-items-center">
                            <img src="<?php // echo URL::asset('Application/Assets/images/workshop.png')
                                        ?>" alt="workshop-img" class="rounded-circle img-fluid">
                            <div class="stories-data ml-3">
                                <h5>Fun Events and Festivals</h5>
                                <p class="mb-0">Jan 16th, 02:30 am</p>
                            </div>
                        </li> -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php View::include('Checkout/modal'); ?>

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

        @media screen and (max-width: 900px) {
            .feed-page-row {
                flex-direction: column-reverse;
            }
        }

        @media only screen and (max-width: 800px) {
            #trends {
                display: none;
            }
        }

        @media only screen and (max-width: 500px) {
            #trends {
                display: none;
            }
        }
    </style>
</define>
<define footer_js>
    <script>
        <?php if( !empty($toastArr) ): ?>
            toast('danger', '<?= $toastArr[0]['message'] ?>');
        <?php endif; ?>

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
        var firstFeedId = <?= !empty($feeds) ? $feeds[0]['id'] : 0; ?>;
        var isBusyLoadMore = false;

        <?php if ( count($feeds) === $feedLimit ): ?>

        $(window).on('scroll', function() {
            if ($(this).scrollTop() >= $(document).height() - $(this).height() - 100) {
                // ajax call get data from server and append to the div
                loadMoreFeed(lastFeedId - 1, true);
            }
        });
        <?php endif; ?>


        $('.feed-new-notice').on('click', function() {
            $('.feed-new-notice').removeClass('show');
            $('.feed-new-loading').addClass('show');
            $('html, body').animate({
                scrollTop: 0
            }, 1000, function() {
                loadMoreFeed(null, false, function(feeds) {
                    firstFeedId = feeds.length > 0 ? feeds[0].feedId : firstFeedId;
                    $('.feed-new-loading').removeClass('show');
                });
            });
        });


        // load more feed.
        function loadMoreFeed(lastId, append, callback) {
            if (isBusyLoadMore) return;

            $.ajax({
                url: URLS.more_feed,
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
