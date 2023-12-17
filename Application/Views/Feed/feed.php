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
                    <a href="<?= URL::full('profile/' . $feed['user']['id']) ?>" class="">
                        <img class="rounded-circle img-fluid" src="<?= UserHelper::getAvatarUrl('fit:300,300', $feed['user']['id']); ?>" alt="">
                    </a>
                </div>
                <div class="media-support-info mt-2">
                    <h5 class="mb-0 d-inline-block"><a href="<?= URL::full('profile/' . $feed['user']['id']) ?>" class="">
                            <?= htmlentities($feed['user']['name']) ?>
                            <?php if ($feed['user']['account_verified']) echo '<i class="fas fa-check-circle color-primary"></i>' ?>
                        </a></h5>
                    <p class="mb-0 d-inline-block">@<?= htmlentities($feed['user']['username']) ?></p>
                    <p class="mb-0 text-primary"><?= DateHelper::butify($feed['created_at']); ?></p>
                </div>

                <?php
                $isOwner = isset($userInfo['id']) && $feed['user']['id'] == $userInfo['id'];
                $options = array();
                if ($isOwner) $options[] = array('class' => 'delete_btn', 'title' => $lang('delete_feed'), 'desc' => $lang('delete_feed_desc'), 'icon' => '<i class="ri-close-line"></i>');
                ?>

                <?php if (!empty($options)) : ?>
                    <div class="iq-card-post-toolbar">
                        <div class="dropdown">
                            <span class="dropdown-toggle" id="postdata-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                                <i class="ri-more-fill"></i>
                            </span>
                            <div class="dropdown-menu m-0 p-0" aria-labelledby="postdata-5">

                                <?php foreach ($options as $option) : ?>
                                    <a class="dropdown-item p-3 <?= $option['class'] ?>" href="#">
                                        <div class="d-flex align-items-top">
                                            <div class="icon font-size-20"><?= $option['icon'] ?></div>
                                            <div class="data ml-2">
                                                <h6><?= $option['title']; ?></h6>
                                                <p class="mb-0"><?= $option['desc']; ?></p>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                                <!-- <a class="dropdown-item p-3" href="#">
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
                            </a> -->
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
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
                <a href="<?= URL::full('Application/Uploads/' . $data['image']); ?>" class="float-image" target="_blank"><img src="<?= URL::media('Application/Uploads/' . $data['image'], 'resize:940,530'); ?>" alt="post-image" class="img-fluid rounded"></a>
            </div>
        <?php endif; ?>
        <?php if (!empty($data['workshop'])) : ?>
            <?php View::include('Feed/FeedParts/workshop', [
                'workshop' => $data['workshop'],
                'isOwner' => $isOwner,
                'platform_fees' => $platform_fees ?? 0,
            ]); ?>
        <?php endif; ?>
        <div class="comment-area mt-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="like-block position-relative d-flex align-items-center flex-wrap">
                    <div class="d-flex align-items-center">
                        <!-- <div class="like-data">
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
                        </div> -->
                        <div class="total-like-block ml-2 mr-3">
                            <div class="dropdown">
                                <span class="dropdown-toggle like-btn <?= $feed['isExpressed']['likes'] ? 'liked' : ''; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                                    <?php $likedWithViewer = $feed['isExpressed']['likes'] ? $feed['totalExpressions']['likes'] : $feed['totalExpressions']['likes'] + 1; ?>
                                    <?php $likedWithoutViewer = $feed['isExpressed']['likes'] ? $feed['totalExpressions']['likes'] - 1 : $feed['totalExpressions']['likes']; ?>
                                    <span class="like-counter"><i class="ri-heart-line"></i>
                                        <?= $lang('c_likes', ['num' => $likedWithoutViewer]);  ?>
                                    </span>
                                    <span class="like-counter"><i class="ri-heart-fill"></i>
                                        <?= $lang('c_likes', ['num' => $likedWithViewer]);  ?>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- <?php  // if ($feed['totalComments'] !== 0 && $feed['totalComments']  > 0) : 
                            ?> -->
                    <div class="total-comment-block mr-3">
                        <span>
                            <?= $lang('c_views', ['num' => $feed['totalViews']]);  ?>
                        </span>
                    </div>
                    <div class="total-comment-block mr-3 total-comments-count">
                        <div class="dropdown">
                            <span class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                                <?= $lang('total_comments', ['num' => $feed['totalComments']]);  ?>
                            </span>
                        </div>
                    </div>
                    <?php if ($userInfo) : ?>
                        <div class="total-comment-block mr-3" data-toggle="modal" data-target="#share-modal-<?= $feed['id'] ?>">
                            <span>
                                <?= $lang('share') ?>
                            </span>
                        </div>
                        <div class="modal fade share-modal" id="share-modal-<?= $feed['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="share-modal-<?= $feed['id'] ?>-title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <!-- <div class="modal-header">
                                        <h5 class="modal-title" id="share-modal-<?= $feed['id'] ?>-title"><?= $lang('share') ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div> -->
                                    <div class="modal-body">
                                        <div class="social-icons">
                                            <div class="share-icon whatsapp">
                                                <a href="https://api.whatsapp.com/send?text=<?= urlencode(URL::full('feed/' . $feed['id'])) ?>" data-action="share/whatsapp/share" target="_blank">
                                                    <i class="ri-whatsapp-line"></i>
                                                    <p><?= $lang('whatsapp') ?></p>
                                                </a>
                                            </div>

                                            <div class="share-icon email">
                                                <a href="mailto:?body=<?= URL::full('feed/' . $feed['id']) ?>" target="_blank">
                                                    <i class="ri-mail-line"></i>
                                                    <p><?= $lang('email') ?></p>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="share-input mt-3">
                                            <input type="text" readonly class="form-control" value="<?= URL::full('feed/' . $feed['id']) ?>"/>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $lang('close') ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- <div class="d-flex">
                        <?php if ($userInfo) : ?>
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
                    </div> -->

                    <?php // endif; 
                    ?>
                </div>
                <!-- <div class="share-block d-flex align-items-center feather-icon mr-3">
                                                <a href="javascript:void();"><i class="ri-share-line"></i>
                                                    <span class="ml-1">99 Share</span></a>
                                            </div> -->
            </div>
            <hr>
            <ul class="post-comments p-0 m-0">
                <?php if ($feed['totalComments'] !== 0 && $feed['totalComments']  > 5) : ?>
                    <li class="mb-3 comment-load-more"><a href="#"><?= $lang('load_more_comments'); ?></a></li>
                <?php endif; ?>
                <?php $feed['comments'] = array_reverse($feed['comments']); ?>
                <?php foreach ($feed['comments'] as $comment) : ?>
                    <li>
                        <?php View::include('Feed/comment', [
                            'userInfo' => $userInfo,
                            'comment' => $comment
                        ]); ?>
                    </li>
                <?php endforeach; ?>
                <!-- <li class="mb-2">
                    <div class="d-flex flex-wrap">
                        <div class="user-img">
                            <img src="<?php // echo URL::asset('Application/Assets/images/no-dp.jpg'); 
                                        ?>" alt="userimg" class="avatar-35 rounded-circle img-fluid">
                        </div>
                        <div class="comment-data-block ml-3">
                            <h6>Monty Carlo</h6>
                            <p class="mb-0">Lorem ipsum dolor sit amet</p>
                            <div class="d-flex flex-wrap align-items-center comment-activity">
                                <a href="javascript:void();">like</a>
                                                            <a href="javascript:void();">reply</a>
                                <span class="text-primary"> 5 min </span>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                  
                </li> -->
            </ul>
            <?php if ($userInfo) : ?>
                <form class="comment-text d-flex align-items-center mt-3" action="#" id="comment_input_<?= $feed['id']; ?>">
                    <input type="text" class="form-control rounded feed-comment" placeholder="<?= $lang('write_comment'); ?>">
                    <div class="comment-attagement d-flex">
                        <a href="javascript:void();" onclick="$('#comment_input_<?= $feed['id']; ?>').trigger('submit');"><i class="ri-send-plane-2-line mr-3"></i></a>
                        <!-- <a href="javascript:void();"><i class="ri-user-smile-line mr-3"></i></a>
                                                <a href="javascript:void();"><i class="ri-camera-line mr-3"></i></a> -->
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
if ($userInfo) :
    $commentIds = array();
    foreach ($feed['comments'] as $comment) {
        $commentIds[] = $comment['id'];
    }
?>
    <define footer_js>
        <script>
            (function() {
                var commentIds = [<?= implode(', ', $commentIds) ?>];
                var feed = new Feed(<?= $feed['id']; ?>, commentIds);
            })();
        </script>
    </define>
<?php
endif;
?>