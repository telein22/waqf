<?php

use System\Core\Config;
use System\Core\Model;
use System\Helpers\URL;
use System\Models\Session;
use System\Responses\View;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

/**
 * @var Session
 */
$session = Model::get(Session::class);

$config = Config::get("Website");
$number = $config->whatsapp_number;

?>
<?php View::include('modal_workshop'); ?>
<?php View::include('modal_call'); ?>
<?php View::include('modal_messaging'); ?>
</div> <!-- END CONTENT PAGE -->
</div>
</div>
<!-- Wrapper END -->
<!-- Footer -->
<footer class="bg-white iq-footer">
   <div class="container">
      <div class="row">
         <div class="col-lg-4">
            <img width="200" src="<?= URL::asset('Application/Assets/images/footer-pay.jpeg') ?>" alt="">
            <ul class="list-inline mb-0">
               <li class="list-inline-item"><a href="<?= URL::full('terms') ?>"><?= $lang('privacy_policy') ?></a></li>
               <li class="list-inline-item"><a href="<?= URL::full('terms') ?>"><?= $lang('terms_of_service') ?></a></li>
            </ul>
         </div>
         <div class="col-lg-4 text-center d-flex align-items-end justify-content-center">
            <p><?= date('d-m-Y H:i') ?></p>
         </div>
         <div class="col-lg-4 text-right sm-text-center footer-social">
            <ul class="list-inline mb-0 ">
               <li class="list-inline-item">
                  <a target="_blank" href="https://twitter.com/waqf_kau"><i class="fab fa-twitter"></i></a>
               </li>
               <li class="list-inline-item">
                  <a target="_blank" href="https://www.youtube.com/@waqforg/videos"><i class="fab fa-youtube"></i></a>
               </li>
            </ul>
            <span class="footer-copyright"><?= $lang('copyright', ['date' => date('Y')]) ?></span>
         </div>
      </div>
   </div>
</footer>
<!-- Footer END -->

<!-- Optional JavaScript -->

<script>
   var URLS = {
      delete_feed: '<?= URL::full('ajax/feed-delete'); ?>',
      get_feed: '<?= URL::full('ajax/feed-get'); ?>',
      more_feed: '<?= URL::full('ajax/feed-more'); ?>',
      more_feed_comment: '<?= URL::full('ajax/feed-more-comment'); ?>',
      more_feed_media: '<?= URL::full('ajax/feed-more-media'); ?>',
      more_feed_liked: '<?= URL::full('ajax/feed-more-liked'); ?>',
      more_feed_profile: '<?= URL::full('ajax/feed-more-profile'); ?>',
      post_comment: '<?= URL::full('ajax/comment-post'); ?>',
      get_comment: '<?= URL::full('ajax/comment-get'); ?>',
      delete_comment: '<?= URL::full('ajax/comment-delete'); ?>',
      load_comment: '<?= URL::full('ajax/comment-load'); ?>',
      toggle_expression: '<?= URL::full('ajax/expression-toggle'); ?>',
      toggle_follow: '<?= URL::full('ajax/follow-toggle'); ?>',
      more_follower: '<?= URL::full('ajax/follow-more-follower'); ?>',
      more_following: '<?= URL::full('ajax/follow-more-folllowing'); ?>',
      profile_upload_cover: '<?= URL::full('ajax/profile-upload-cover'); ?>',
      profile_upload_avatar: '<?= URL::full('ajax/profile-upload-avatar'); ?>',
      location_get_cities: '<?= URL::full('ajax/location/cities') ?>',
      specialty_get_sub_specialties: '<?= URL::full('ajax/sub-specialty') ?>',
      workshop_create: '<?= URL::full('ajax/workshop/create'); ?>',
      workshop_search: '<?= URL::full('ajax/workshop/search'); ?>',
      more_workshop: '<?= URL::full('ajax/workshop/more'); ?>',
      find_more_workshop: '<?= URL::full('ajax/workshop/find-more'); ?>',
      workshop_find_search: '<?= URL::full('ajax/workshop/find/search'); ?>',
      search_profile_workshop: '<?= URL::full('ajax/workshop/search-profile-workshop'); ?>',
      delete_workshop: '<?= URL::full('ajax/workshop/delete') ?>',
      start_workshop: '<?= URL::full('ajax/workshop/start') ?>',
      join_workshop: '<?= URL::full('ajax/workshop/join') ?>',
      complete_workshop: '<?= URL::full('ajax/workshop/complete') ?>',
      cancel_workshop: '<?= URL::full('ajax/workshop/cancel') ?>',
      participant_list: '<?= URL::full('ajax/participant-list'); ?>',
      ping: '<?= URL::full('ajax/ping') ?>',
      create_order: '<?= URL::full('ajax/order/create') ?>',
      invite: '<?= URL::full('ajax/invite') ?>',
      prepare_checkout: '<?= URL::full('ajax/checkout/prepare') ?>',
      checkout_apply_coupon: '<?= URL::full('ajax/checkout/coupon/apply') ?>',
      checkout_remove_coupon: '<?= URL::full('ajax/checkout/coupon/remove') ?>',
      more_order_requests: '<?= URL::full('ajax/order/more-request') ?>',
      accept_order_request: '<?= URL::full('ajax/order/request/accept') ?>',
      decline_order_request: '<?= URL::full('ajax/order/request/decline') ?>',
      delete_call_slot: '<?= URL::full('ajax/calls/slot/delete') ?>',
      search_call_slots: '<?= URL::full('ajax/calls/slot/search') ?>',
      cancel_call: '<?= URL::full('ajax/calls/cancel') ?>',
      complete_call: '<?= URL::full('ajax/calls/complete') ?>',
      start_call: '<?= URL::full('ajax/calls/start') ?>',
      call_search: '<?= URL::full('ajax/calls/search'); ?>',
      more_call: '<?= URL::full('ajax/calls/more'); ?>',
      join_call: '<?= URL::full('ajax/calls/join'); ?>',
      book_message: '<?= URL::full('ajax/messaging/book') ?>',
      conversation_search: '<?= URL::full('ajax/messaging/search'); ?>',
      more_conversations: '<?= URL::full('ajax/messaging/more'); ?>',
      more_noti: '<?= URL::full('ajax/noti-more'); ?>',
      more_order: '<?= URL::full('ajax/order/more-orders'); ?>',
      more_logs: '<?= URL::full('ajax/earningLogs/more-logs'); ?>',
      search: '<?= URL::full('ajax/search'); ?>',
      user_search: '<?= URL::full('ajax/user/search'); ?>',
      more_user_suggestions: '<?= URL::full('ajax/suggestions/more'); ?>',
      apply_for_withdrawal_request: '<?= URL::full('ajax/add-withdrawal-requests'); ?>',
      get_withdrawal_request: '<?= URL::full('ajax/withdrawal-requests'); ?>',
   };
</script>

<script>
   if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
   }
</script>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="<?= URL::asset('Application/Assets/js/jquery.min.js'); ?>"></script>
<script src="<?= URL::asset('Application/Assets/js/popper.min.js'); ?>"></script>
<script src="<?= URL::asset('Application/Assets/js/bootstrap.min.js'); ?>"></script>
<!-- Appear JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/jquery.appear.js'); ?>"></script>
<!-- Countdown JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/countdown.min.js'); ?>"></script>
<!-- Counterup JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/waypoints.min.js'); ?>"></script>
<script src="<?= URL::asset('Application/Assets/js/jquery.counterup.min.js'); ?>"></script>
<!-- Wow JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/wow.min.js'); ?>"></script>
<!-- Apexcharts JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/apexcharts.js'); ?>"></script>
<!-- Slick JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/slick.min.js'); ?>"></script>
<!-- Select2 JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/select2.min.js'); ?>"></script>
<!-- Owl Carousel JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/owl.carousel.min.js'); ?>"></script>
<!-- Magnific Popup JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/jquery.magnific-popup.min.js'); ?>"></script>
<!-- Smooth Scrollbar JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/smooth-scrollbar.js'); ?>"></script>
<!-- lottie JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/lottie.js'); ?>"></script>
<!-- am core JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/core.js'); ?>"></script>
<!-- am charts JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/charts.js'); ?>"></script>
<!-- am animated JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/animated.js'); ?>"></script>
<!-- am kelly JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/kelly.js'); ?>"></script>
<!-- am maps JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/maps.js'); ?>"></script>
<!-- am worldLow JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/worldLow.js'); ?>"></script>
<!-- Chart Custom JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/chart-custom.js'); ?>"></script>
<!-- Bootbox javascript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>

<script src="<?= URL::asset('Application/Assets/js/cleave.js'); ?>"></script>

<!-- Custom JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/custom.js'); ?>"></script>
<script src="<?= URL::asset('Application/Assets/js/cropper.js'); ?>"></script>
<!-- Toaster JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/toasterjs-umd.js'); ?>"></script>
<script src="<?= URL::asset('Application/Assets/js/jquery.toast.min.js'); ?>"></script>


<!-- Datatables -->
<script src="<?= URL::asset('Application/Assets/Admin/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= URL::asset('Application/Assets/Admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= URL::asset('Application/Assets/Admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= URL::asset('Application/Assets/Admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= URL::asset('Application/Assets/Admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= URL::asset('Application/Assets/Admin/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
<script src="<?= URL::asset('Application/Assets/Admin/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
<script src="<?= URL::asset('Application/Assets/Admin/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>

<script>
   cConfirm.labels.yes = '<?= $lang('yes') ?>';
   cConfirm.labels.no = '<?= $lang('no') ?>';

   // Workshop labels
   Workshop.labels.deleted = '<?= $lang('deleted') ?>';
   Workshop.labels.delete_title = '<?= $lang('are_you_sure') ?>';
   Workshop.labels.error_title = '<?= $lang('error') ?>';
   Workshop.labels.error_cancel_confirm = '<?= $lang('workshop_error_cancel_confirm') ?>';
   Workshop.labels.cancel_title = '<?= $lang('are_you_sure') ?>';
   Workshop.labels.cancel_placeholder = '<?= $lang('workshop_cancel_placeholder') ?>';
   Workshop.labels.yes = '<?= $lang('yes') ?>';
   Workshop.labels.no = '<?= $lang('no') ?>';
   Workshop.labels.window_cant_open = '<?= $lang('window_cant_open') ?>';

   Call.labels.error_title = '<?= $lang('error') ?>';
   Call.labels.error_cancel_confirm = '<?= $lang('workshop_error_cancel_confirm') ?>';
   Call.labels.cancel_title = '<?= $lang('are_you_sure') ?>';
   Call.labels.cancel_placeholder = '<?= $lang('workshop_cancel_placeholder') ?>';
   Call.labels.yes = '<?= $lang('yes') ?>';
   Call.labels.no = '<?= $lang('no') ?>';
   Call.labels.window_cant_open = '<?= $lang('window_cant_open') ?>';

   Feed.labels.delete_sure = '<?= $lang('are_you_sure') ?>';
   Feed.labels.yes = '<?= $lang('yes') ?>';
   Feed.labels.no = '<?= $lang('no') ?>';
</script>

<script>
   $.ajaxSettings.dataType = 'JSON';
   $.ajaxSettings.accepts = 'JSON';
   $.ajaxSettings.type = 'POST';
</script>

<!-- Putting ping after ajaxSettings, should be here -->
<script src="<?= URL::asset('Application/Assets/js/ping.js'); ?>"></script>

<call footer_js />

<script>
   (function() {

      var oldCount = 0;

      // call ping here.
      var before = function(data) {
         // console.log(data);
      };

      var after = function(count, value) {

         valueService = parseInt(value.service);
         valueSocial = parseInt(value.social);

         var totalValue = valueService + valueSocial;

         $(".total-noti-count").text(totalValue);
         $(".service-noti-count").text(valueService);
         $(".social-noti-count").text(valueSocial);

         if (totalValue > 0) {
            $('.notification-item span.dots').removeClass('d-none');

            if (totalValue !== oldCount && count > 1) {

               var $toast = $('.notification-item .notification-toast');
               $toast.removeClass('d-none');

               // Also show the notification toast
               var timeoutId = setTimeout(function() {
                  $toast.addClass('d-none');
               }, 5000);

            }

         } else {
            $('.notification-item span.dots').addClass('d-none');
         }

         oldCount = totalValue;
      };

      ping.subscribe('notification.unreadCount', before, after);

   })(window);
</script>

<?php if ($session->has('toast_header')) : ?>
   <script>
      toast('<?= $session->take('toast_type') ?>', '<?= $session->take('toast_header'); ?>', '<?= $session->take('toast_body'); ?>');
   </script>
   <?php
   $session->delete('toast_header');
   $session->delete('toast_body');
   ?>
<?php endif; ?>

</body>

</html>