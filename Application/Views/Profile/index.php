<?php

use Application\Helpers\DateHelper;
use Application\Helpers\FollowerHelper;
use Application\Helpers\UserHelper;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

?>

<div class="container">
   <div class="row">
      <div class="col-sm-12">
         <div class="iq-card">
            <?php
            View::include('Profile/top', [
               'reviews' => $reviews,
               'user' => $user,
               'isOwner' => $isOwner,
               'isFollowing' => $isFollowing,
               'coverUrl' => $coverUrl,
               'avatarUrl' => $avatarUrl,
               'followerCount' => $followerCount,
               'followCount' => $followCount,
               'associatesCount' => $associatesCount,
               'feedCount' => $feedCount,
               'social' => $social,
               'enableMessaging' => $enableMessaging,
               'messagingPrice' => $messagingPrice,
               'viewerCanWorkshop' => $viewerCanWorkshop,
               'isEntity' => $isEntity
            ]);
            ?>
         </div>
         <div class="iq-card">
            <div class="iq-card-body p-0">
               <div class="user-tabing">
                  <ul class="nav nav-pills d-flex align-items-center justify-content-center profile-feed-items p-0 m-0">
                     <li class="col-sm-2 p-0">
                        <a class="<?php if ($type == 'timeline') echo 'active' ?> nav-link timeline-tab-nav" href="<?= URL::full('profile/' . $user['id']) ?>">
                           <?= $lang('timeline'); ?>
                        </a>
                     </li>
                     <li class="col-sm-2 p-0">
                        <a class="<?php if ($type == 'about') echo 'active' ?> nav-link about-tab-nav" href="<?= URL::full('profile/' . $user['id'] . '/about') ?>">
                           <?= $lang('about'); ?>
                        </a>
                     </li>
                     <li class="col-sm-2 p-0">
                        <a class="<?php if ($type == 'comment') echo 'active' ?> nav-link comment-tab-nav" href="<?= URL::full('profile/' . $user['id'] . '/comment') ?>">
                           <?= $lang('comments'); ?>
                        </a>
                     </li>
                     <li class="col-sm-2 p-0">
                        <a class="<?php if ($type == 'media') echo 'active' ?> nav-link media-tab-nav" href="<?= URL::full('profile/' . $user['id'] . '/media') ?>">
                           <?= $lang('media'); ?>
                        </a>
                     </li>
                     <li class="col-sm-2 p-0">
                        <a class="<?php if ($type == 'liked') echo 'active' ?> nav-link liked-tab-nav" href="<?= URL::full('profile/' . $user['id'] . '/liked') ?>">
                           <?= $lang('likes'); ?>
                        </a>
                     </li>
                     <li class="col-sm-3 p-0 d-none">
                        <a class="nav-link followers-tab-nav" href="<?= URL::full('profile/' . $user['id'] . '/followers') ?>">
                           <?= $lang('followers'); ?>
                        </a>
                     </li>
                     <li class="col-sm-3 p-0 d-none">
                        <a class="nav-link following-tab-nav" href="<?= URL::full('profile/' . $user['id'] . '/following') ?>">
                           <?= $lang('following'); ?>
                        </a>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-12">
         <div class="tab-content">
            <?php if ($type == 'about') : ?>
               <div class="tab-pane fade active show" id="about" role="tabpanel">
                  <div class="iq-card">
                     <div class="iq-card-body">
                        <div class="row">
                           <div class="col-md-12">
                              <!-- <h4><?php // echo $lang('general'); ?></h4>
                              <hr> -->
                              <!-- <div class="row"> -->
                                 <!-- <div class="col-3">
                                    <h6><?php // echo $lang('email'); ?></h6>
                                 </div>
                                 <div class="col-9">
                                    <p class="mb-0"><?php // echo $about['email']; ?></p>
                                 </div>
                                 <div class="col-3">
                                    <h6><?php // echo $lang('phone'); ?></h6>
                                 </div>
                                 <div class="col-9">
                                    <p class="mb-0"><?php // echo $about['phone']; ?></p>
                                 </div> -->
                                 <!-- <div class="col-3">
                                    <h6><?php // echo $lang('gender'); ?></h6>
                                 </div>
                                 <div class="col-9">
                                    <p class="mb-0"><?php // echo UserHelper::genderText($about['gender']); ?></p>
                                 </div> -->
                                 <!-- <div class="col-3">
                                    <h6><?php // echo $lang('dob'); ?></h6>
                                 </div> -->
                                 <!-- <div class="col-9">
                                    <p class="mb-0"><?php // echo date('M jS, Y', strtotime($about['dob'])); ?></p>
                                 </div> -->
                              <!-- </div> -->
                              <?php if ( !empty($about['bio']) ): ?>
                              <h4 class="mt-3"><?= $lang('bio'); ?></h4>
                                 <hr>
                                 <div class="row">
                                    <div class="col-sm-12">
                                       <p><?= htmlentities($about['bio']); ?></p>
                                    </div>
                                 </div>
                              <?php endif; ?>
                              <?php if ( !empty($about['achievements']) ): ?>
                                 <h4 class="mt-3"><?= $lang('achievements'); ?></h4>
                                 <hr>
                                 <div class="row">
                                     <div class="col-sm-12">
                                         <p><?= htmlentities($about['achievements']); ?></p>
                                     </div>
                                 </div>
                              <?php endif; ?>
                              <?php if ( !empty($about['city']) ): ?>
                              <h4 class="mt-3"><?= $lang('location'); ?></h4>
                                 <hr>
                                 <div class="row">
                                    <div class="col-sm-12">
                                       <p><?= $about['city'][ $lang->current() .  '_name'] . ', ' . $about['country'][ $lang->current() . '_name'] ?></p>
                                    </div>
                                 </div>
                              <?php endif; ?>                              
                              <?php if (!empty($about['specialties'])) : ?>
                                 <h4 class="mt-3"><?= $lang('specialties'); ?></h4>
                                 <hr>
                                 <div class="row">
                                    <div class="col-sm-12">
                                       <?php foreach ($about['specialties'] as $specialty) : ?>
                                          <span class="badge badge-primary fz-14"><?= $specialty['specialty_' . $lang->current()]; ?></span>
                                       <?php endforeach; ?>
                                    </div>
                                 </div>
                              <?php endif; ?>
                              <?php if (!empty($about['subSpecialties'])) : ?>
                                 <h4 class="mt-3"><?= $lang('sub_specialties'); ?></h4>
                                 <hr>
                                 <div class="row">
                                    <div class="col-sm-12">
                                       <?php foreach ($about['subSpecialties'] as $specialty) : ?>
                                          <span class="badge badge-primary fz-14"><?= $specialty['specialty_' . $lang->current()]; ?></span>
                                       <?php endforeach; ?>
                                    </div>
                                 </div>
                              <?php endif; ?>
                               <?php if(!$isEntity && !empty($entityInfo)): ?>
                                   <h4 class="mt-3"><?= $lang('your_entity'); ?></h4>
                                   <hr>
                                   <div class="row">
                                       <div class="col-sm-12 custom-text-right">
                                           <a class="nav-link following-tab-nav" href="<?= URL::full('profile/' . $entityInfo['id']) ?>">
                                               <div class="col-sm-12">
                                                   <p><?= $entityInfo['name'] ?></p>
                                               </div>
                                           </a>
                                       </div>
                                   </div>
                                <?php endif ?>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            <?php elseif ($type == 'timeline') : ?>
               <div class="tab-pane fade active show" id="timeline" role="tabpanel">
                  <?php
                  View::include('Profile/timeline_tab', [
                     'user' => $cUser,
                     'entityUser' => $user,
                     'feeds' => $feeds,
                     'limit' => $feedLimit,
                     'followers' => $followers,
                     'followings' => $followings,
                  ]);
                  ?>
               </div>
            <?php elseif ($type == 'comment') : ?>
               <div class="tab-pane fade active show" id="comment" role="tabpanel">
                  <?php
                  View::include('Profile/comment_tab', [
                     'user' => $cUser,
                     'entityUser' => $user,
                     'feeds' => $feeds,
                     'limit' => $feedLimit,
                     'followers' => $followers,
                     'followings' => $followings,
                  ]);
                  ?>
               </div>
            <?php elseif ($type == 'media') : ?>
               <div class="tab-pane fade active show" id="media" role="tabpanel">
                  <?php
                  View::include('Profile/media_tab', [
                     'user' => $cUser,
                     'entityUser' => $user,
                     'feeds' => $feeds,
                     'limit' => $feedLimit,
                     'followers' => $followers,
                     'followings' => $followings,
                  ]);
                  ?>
               </div>
            <?php elseif ($type == 'liked') : ?>
               <div class="tab-pane fade active show" id="liked" role="tabpanel">
                  <?php
                  View::include('Profile/liked_tab', [
                     'user' => $cUser,
                     'entityUser' => $user,
                     'feeds' => $feeds,
                     'limit' => $feedLimit,
                     'followers' => $followers,
                     'followings' => $followings,
                  ]);
                  ?>
               </div>
            <?php elseif ($type == 'followers') : ?>
               <div class="tab-pane fade active show" id="followers" role="tabpanel">
                  <?php
                  View::include('Profile/follower_tab', [
                     'user' => $cUser,
                     'entityUser' => $user,
                     'followers' => $followers,
                     'limit' => $feedLimit,
                     'followers' => $followers,
                     'followings' => $followings,
                  ]);
                  ?>
               </div>
            <?php elseif ($type == 'following') : ?>
               <div class="tab-pane fade active show" id="following" role="tabpanel">
                  <?php
                  View::include('Profile/following_tab', [
                     'user' => $cUser,
                     'entityUser' => $user,
                     'followings' => $followings,
                     'limit' => $feedLimit,
                     'followers' => $followers,
                     'followings' => $followings,
                  ]);
                  ?>
               </div>
            <?php elseif ($type == 'associates') : ?>
                <div class="tab-pane fade active show" id="associates" role="tabpanel">
                    <?php
                    View::include('Profile/associates_tab', [
                        'user' => $cUser,
                        'entityUser' => $user,
                        'limit' => $feedLimit,
                        'associates' => $associates,
                    ]);
                    ?>
                </div>
            <?php endif; ?>
         </div>
      </div>
      <!-- <div class="col-sm-12 text-center">
                     <img src="images/page-img/page-load-loader.gif" alt="loader" style="height: 100px;">
                  </div> -->
   </div>
</div>
<?php View::include('Profile/call_modal', [
   'user' => $user
]); ?>

<?php View::include('Profile/workshop_modal', [
   'user' => $user
]); ?>


<?php
// Include message modal
if ($enableMessaging && $messagingPrice) {
   View::include('Profile/message_modal', [
      'user' => $user,
      'messagingPrice' => $messagingPrice
   ]);
   // All include checkout modal as this will be required
   // By the message modal
   View::include('Checkout/modal');
}
?>
<?php  ?>

<define header_css>
   <style>
      @media screen and (max-width: 990px) {
      .profile-header .user-detail {
         position: relative;
         bottom: 0;
         margin-top: -70px;
      }

      .profile-info.p-4.d-flex {
         display: block !important;
      }

      .profile-info {
         padding: 1.5rem !important;
      }

      .social-info {
         margin-top: 20px;
      }
   }
   </style>
</define>