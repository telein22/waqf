<?php

use Application\Helpers\DateHelper;
use Application\Helpers\HashTagHelper;
use Application\Helpers\UserHelper;
use Application\Models\Workshop;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

?>
<div class="iq-card iq-card-block iq-card-stretch iq-card-height" id="feed_<?= $feed['id']; ?>">
    <div class="iq-card-body">
        <div class="user-post-data">
            <div class="d-flex flex-wrap">
                <div class="media-support-user-img mr-3">
                    <a href="<?= URL::full('outer-profile/' . $feed['user']['id']) ?>" class="">
                        <img class="rounded-circle img-fluid" src="<?= UserHelper::getAvatarUrl('fit:300,300', $feed['user']['id']); ?>" alt="">
                    </a>
                </div>
                <div class="media-support-info mt-2">
                    <h5 class="mb-0 d-inline-block"><a href="<?= URL::full('outer-profile/' . $feed['user']['id']) ?>" class="">
                            <?= htmlentities($feed['user']['name']) ?>
                            <?php if ($feed['user']['account_verified']) echo '<i class="fas fa-check-circle color-primary"></i>' ?>
                        </a></h5>
                    <p class="mb-0 d-inline-block">@<?= htmlentities($feed['user']['username']) ?></p>
                    <p class="mb-0 text-primary"><?= DateHelper::butify($feed['created_at']); ?></p>
                </div>

                
                <!-- <div class="iq-card-post-toolbar">
                    <div class="dropdown">
                        <span class="dropdown-toggle" id="postdata-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                            <i class="ri-more-fill"></i>
                        </span>
                        <div class="dropdown-menu m-0 p-0" aria-labelledby="postdata-5">
                            <a class="dropdown-item p-3" href="#">
                                <div class="d-flex align-items-top">
                                    <div class="icon font-size-20"><i class="ri-save-line"></i></div>
                                    <div class="data ml-2">
                                        <h6>Save Post</h6>
                                        <p class="mb-0">Add this to your saved items</p>
                                    </div>
                                </div>
                            </a>
                            <a class="dropdown-item p-3" href="#">
                                <div class="d-flex align-items-top">
                                    <div class="icon font-size-20"><i class="ri-close-circle-line"></i></div>
                                    <div class="data ml-2">
                                        <h6>Hide Post</h6>
                                        <p class="mb-0">See fewer posts like this.</p>
                                    </div>
                                </div>
                            </a>
                            <a class="dropdown-item p-3" href="#">
                                <div class="d-flex align-items-top">
                                    <div class="icon font-size-20"><i class="ri-user-unfollow-line"></i></div>
                                    <div class="data ml-2">
                                        <h6>Unfollow User</h6>
                                        <p class="mb-0">Stop seeing posts but stay friends.</p>
                                    </div>
                                </div>
                            </a>
                            <a class="dropdown-item p-3" href="#">
                                <div class="d-flex align-items-top">
                                    <div class="icon font-size-20"><i class="ri-notification-line"></i></div>
                                    <div class="data ml-2">
                                        <h6>Notifications</h6>
                                        <p class="mb-0">Turn on notifications for this post</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
        <?php $data = $feed['data']; ?>
        <div class="mt-3">
            <?php if (!empty($feed['text'])) : ?>
                <p><?= HashTagHelper::highlightHash(htmlentities($feed['text'])); ?></p>
            <?php endif; ?>
        </div>
        <?php if (!empty($data['image'])) : ?>
            <div class="user-post">
                <img src="<?= URL::media('Application/Uploads/' . $data['image'], 'fit:597,384'); ?>" alt="post-image" class="img-fluid rounded w-100">
            </div>
        <?php endif; ?>
        <?php if (!empty($data['workshop'])) : ?>
                <?php View::include('Feed/FeedParts/static_workshop', [
                'workshop' => $data['workshop'],
                'isOwner' => false,
                'platform_fees' => isset($platform_fees) ? $platform_fees : 0
            ]); ?>
        <?php endif; ?>
        <div class="comment-area mt-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="like-block position-relative d-flex align-items-center flex-wrap">
                    <div class="d-flex align-items-center">
                        <div class="like-data">
                            <div class="dropdown">
                                <span class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                                    <img src="images/icon/01.png" class="img-fluid" alt="">
                                </span>
                                <div class="dropdown-menu">
                                    <a class="ml-2 mr-2" href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Like"><img src="images/icon/01.png" class="img-fluid" alt=""></a>
                                    <a class="mr-2" href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Love"><img src="images/icon/02.png" class="img-fluid" alt=""></a>
                                    <a class="mr-2" href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Happy"><img src="images/icon/03.png" class="img-fluid" alt=""></a>
                                    <a class="mr-2" href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="HaHa"><img src="images/icon/04.png" class="img-fluid" alt=""></a>
                                    <a class="mr-2" href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Think"><img src="images/icon/05.png" class="img-fluid" alt=""></a>
                                    <a class="mr-2" href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sade"><img src="images/icon/06.png" class="img-fluid" alt=""></a>
                                    <a class="mr-2" href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Lovely"><img src="images/icon/07.png" class="img-fluid" alt=""></a>
                                </div>
                            </div>
                        </div>
                        <div class="total-like-block ml-2 mr-3">
                            <div class="dropdown">
                                <a href="<?= URL::full('login') ?>" class="dropdown-toggle like-btn <?= $feed['isExpressed']['likes'] ? 'liked' : ''; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                                    <?php $likedWithViewer = $feed['isExpressed']['likes'] ? $feed['totalExpressions']['likes'] : $feed['totalExpressions']['likes'] + 1; ?>
                                    <?php $likedWithoutViewer = $feed['isExpressed']['likes'] ? $feed['totalExpressions']['likes'] - 1 : $feed['totalExpressions']['likes']; ?>
                                    <span class="like-counter"><i class="ri-heart-line"></i> 
                                        <?= $lang('c_likes', ['num' => $likedWithoutViewer]);  ?>
                                    </span>
                                    <span class="like-counter"><i class="ri-heart-fill"></i>
                                        <?= $lang('c_likes', ['num' => $likedWithViewer]);  ?>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- <?php  // if ($feed['totalComments'] !== 0 && $feed['totalComments']  > 0) : 
                            ?> -->
                    <div class="total-comment-block mr-3">
                        <a href="<?= URL::full('login') ?>">
                            <span>
                                <?= $lang('c_views', ['num' => $feed['totalViews']]);  ?>
                            </span>
                        </a>
                    </div>
                    <div class="total-comment-block mr-3 total-comments-count">
                        <div class="dropdown">
                            <a href="<?= URL::full('login') ?>" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                                <?= $lang('total_comments', ['num' => $feed['totalComments']]);  ?>
                            </a>
                        </div>
                    </div>

                    <div class="d-flex">
                        <?php if (isset($userInfo)) : ?>
                            <div class="total-comment-block mr-3">
                                <div class="dropdown">
                                    <a href="https://api.whatsapp.com/send?text=<?= urlencode(URL::full('feed/' . $feed['id'])) ?>" data-action="share/whatsapp/share" target="_blank">
                                        <span class="dropdown-toggle share-btn">
                                            <i class="ri-whatsapp-line"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <div class="total-comment-block">
                                <div class="dropdown">
                                    <a href="mailto:?body=<?= URL::full('feed/' . $feed['id']) ?>" target="_blank">
                                        <span class="dropdown-toggle share-btn">
                                            <i class="ri-mail-line"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php // endif; 
                    ?>
                </div>
                <!-- <div class="share-block d-flex align-items-center feather-icon mr-3">
                                                <a href="javascript:void();"><i class="ri-share-line"></i>
                                                    <span class="ml-1">99 Share</span></a>
                                            </div> -->
            </div>
        </div>
    </div>
</div>
