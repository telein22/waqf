<?php

use Application\Helpers\UserHelper;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

$userM = Model::get('\Application\Models\User');

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

?>
<div id="post-modal-data" class="iq-card iq-card-block iq-card-stretch">
    <div class="iq-card-header d-flex justify-content-between">
        <div class="iq-header-title">
            <h4 class="card-title"><?= $lang('create_post'); ?></h4>
        </div>
    </div>
    <!-- <div class="iq-card-body" data-toggle="modal" data-target="#post-modal"> -->
    <div class="iq-card-body">
        <form class="post-text w-100" id="feed_textarea_post_form" action="#">
            <div class="d-flex align-items-center">
                <div class="user-img">
                    <img src="<?= UserHelper::getAvatarUrl('fit:300,300'); ?>" alt="userimg" class="avatar-60 rounded-circle">
                </div>
                <textarea type="text" class="form-control rounded" id="feed_textarea_text" name="text" class="input" placeholder="<?= $lang('write_something_here') ?>" style="border:none;"></textarea>
                
            </div>
            <div id="feed_textarea_workshop" class="card">                
                <hr>
                <div class="card-body">
                    <div class="card-title">
                        <h4><?= $lang('workshop') ?> <i class="ri-close-line pull-right" onclick="toggleWorkShop();"></i></h4>
                        <p><?= $lang('create_workshop_desc') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="workshop_name"><?= $lang('workshop_name'); ?></label>
                        <input type="text" class="form-control" id="workshop_name" name="name"/>
                    </div>
                    <div class="form-group">
                        <label for="workshop_desc"><?= $lang('workshop_description'); ?></label>
                        <textarea class="form-control" id="workshop_desc" name="desc" ></textarea>
                    </div>
                    <div class="form-group">
                        <label for="workshop_date"><?= $lang('date'); ?></label>
                        <input min="<?= date('Y-m-d', strtotime('+5 minutes')); ?>" type="date" name="date" max="<?= date('Y-m-d', strtotime('+ 5 years')); ?>" id="workshop_date" value="" class="form-control workshop-date" />
                    </div>
                    <div class="form-group">
                        <label for="workshop_time"><?= $lang('time'); ?></label>
                        <input  type="time" name="time" id="workshop_time" value="" class="form-control workshop-time" />
                    </div>
                    <!-- oninvalid="this.setCustomValidity('<?php // echo $lang('c_time_workshop_expired', ['min' => date('H:i', strtotime('+5 minutes'))]) ?>')" oninput="this.setCustomValidity('')" -->
                    <!-- <div class="form-group">
                        <label for="workshop_date"><?php // echo $lang('workshop_date'); ?></label>
                        <input type="datetime-local" min="<?php // echo date('Y-m-d', strtotime('+1 day')); ?>T<?php // echo date('H:i', strtotime('+1 day')); ?>" class="form-control" id="workshop_date" name="date"/>
                    </div>   -->
                    <div class="form-group">
                        <label for="workshop_duration"><?= $lang('workshop_duration'); ?></label>
                        <input type="number" class="form-control" id="workshop_duration" name="duration" />
                    </div>
                    <div class="form-group">
                        <label for="workshop_price"><?= $lang('workshop_price'); ?></label>
                        <input type="text" class="form-control" id="workshop_price" name="price"/>
                    </div>
                    <div class="form-group">
                        <label for="workshop_capacity"><?= $lang('workshop_capacity'); ?></label>
                        <input type="number" class="form-control" id="workshop_capacity" name="capacity"/>
                    </div>
                    <div class="form-group d-none">
                        <label for="workshop_charity"><?= $lang('workshop_charity'); ?></label>
                        <select class="form-control custom-select2" name="charity[]" id="workshop_charity">                            
                            <option data-img="<?= URL::media('Application/Assets/images/no-charity.png', 'fit:32,32') ?>" value=""><?= $lang('select_charity') ?></option>
                            <?php foreach ( $charities as $charity ): ?>
                                <option value="<?= $charity['id'] ?>" data-img="<?= URL::media('Application/Uploads/' . $charity['img'], 'fit:32,32') ?>"><?= $charity[$lang->current() . '_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group d-none">
                        <label for="workshop_invite"><?= $lang('workshop_invite'); ?></label>
                        <select class="form-control" name="invite" id="textarea-workshop-invite">
                        </select>
                        <!-- <input type="text" class="form-control" id="workshop_invite" name="invite"/> -->
                    </div>
                </div>
            </div>
            <div id="feed_textarea_photo" class="card">
                <hr>
                <div class="card-body">
                    <div class="card-title">
                        <h4><?= $lang('image'); ?> <i class="ri-close-line pull-right" onclick="resetImage();"></i></h4>
                        <p><?= $lang('create_workshop_desc') ?></p>
                    </div>
                    <div class="feed_textarea_image_wrapper">
                        <img src="" class="feed_textarea_image_preview"/>
                    </div>
                </div>                
            </div>
            <hr>
            <ul class="post-opt-block d-flex align-items-center list-inline m-0 p-0">
                <?php if ( $userM->canCreateWorkshop() ): ?> 
                    <li class="iq-bg-primary rounded p-2 pointer mr-3"><a href="#" onclick="toggleWorkShop();return false;" data-target="#post-modal" data-toggle="modal"><img src="<?= URL::asset('Application/Assets/images/small/calendar.png'); ?>" alt="icon" class="img-fluid mr-1"><?= $lang('workshop') ?></a></li>
                <?php endif; ?>
                <li class="iq-bg-primary rounded p-2 pointer mr-3"><a href="#" onclick="uploadImage();return false;" data-target="#post-modal" data-toggle="modal"><img src="<?= URL::asset('Application/Assets/images/small/07.png'); ?>" /><?= $lang('image') ?></a></li>
                <li class="iq-bg-primary rounded pointer mr-3 pull-right"><button type="submit" class="btn btn-primary"><?= $lang('submit'); ?></button></li>
            </ul>
            <input type="hidden" name="workshop_active" value="0" id="workshop_active"/>
            <input type="file" name="image" id="feed_textarea_image" accept="image/jpeg,image/png"/>
        </form>
    </div>
</div>

<define footer_js>
    <script>
        $('#textarea-workshop-invite').select2({
            ajax: {
                url: URLS.user_search,
                type: 'POST',
                processResults: function(data) {

                    var final = {
                        results: []
                    };
                    for (var i = 0; i < data.payload.length; i++) {
                        final.results.push({
                            id: data.payload[i].username,
                            text: data.payload[i].name
                        });
                    }

                    return final;
                }
            }
        });
        //  $(".workshop-date").on('change', function(e) {
        //     var selectedDate = $(this).val();
            
        //     var dateObj = new Date();
        //     var year = dateObj.getFullYear();
        //     var month = dateObj.getMonth()+1;
        //     var date = dateObj.getDate();

        //     if(month < 10) {
        //         month = '0' + month;
        //     }

        //     if(date < 10) {
        //         date = '0' + date;
        //     }

        //     var currentDate = year+'-'+(month)+'-'+date;

        //     if( selectedDate > currentDate ) {
        //         $(".workshop-time").removeAttr('min');
        //     } else {
        //         $(".workshop-time").attr('min', '<?php // echo date('H:i', strtotime('+5 minutes')); ?>');
        //     }
        // })

        var textareaHeight = $('#feed_textarea_text').height();
        var file;

        $('#feed_textarea_text').on('input keyup', function() {
            $(this).height(textareaHeight);

            if ( this.scrollHeight >= textareaHeight  ){
                //noinspection JSUnresolvedVariable
                $(this).height(this.scrollHeight);
            }
        });

        $('#feed_textarea_post_form').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            var formData = new FormData($form[0]);   

            $.ajax({
                url: '<?= URL::full('ajax/feed-post'); ?>',
                beforeSend: function() {
                    $form.find('button').text('<?= $lang('posting') ?>')[0].disabled = true;
                },
                type: 'POST',
                dataType: 'JSON',
                accepts: 'JSON',
                data: formData,
                processData: false,
                contentType: false,
                success: function( data ) {
                    if ( data.info === 'error' )
                    {
                        toast('danger', '<?= $lang('feed_textarea_post_error_title') ?>', data.payload);
                        return;
                    }

                    <?= $onPostComplete ?>(data);

                    resetFeed();
                },
                complete: function() {
                    $form.find('button').text('<?= $lang('submit') ?>')[0].disabled = false;
                }
            });

        });

        function toggleWorkShop()
        {
            var isOpen = $('#feed_textarea_post_form #workshop_active').val() === "1";
            if ( isOpen )
            {
                resetWorkshop();
            } else {
                $('#feed_textarea_post_form #workshop_active').val("1");
                $('#feed_textarea_workshop').show();
            }
        }
        function toggleWorkShop()
        {
            var isOpen = $('#feed_textarea_post_form #workshop_active').val() === "1";
            if ( isOpen )
            {
                $('#feed_textarea_post_form #workshop_active').val("0");
                $('#feed_textarea_workshop').hide();
            } else {
                $('#feed_textarea_post_form #workshop_active').val("1");
                $('#feed_textarea_workshop').show();
            }
        }

        function uploadImage()
        {
            $('#feed_textarea_image').trigger('click');
        }
        $('#feed_textarea_image').on('change', function(e) {
            e.preventDefault();
            var files = e.target.files;
            
            if ( files.length >= 1 ) file = files[0];

            var reader = new FileReader();
            reader.readAsDataURL(file);

            reader.onload = function() {
                $('#feed_textarea_photo img').attr('src', reader.result);
                $('#feed_textarea_photo').show();
            };
        });

        function resetImage()
        {
            if ( !file ) file = null;

            $('#feed_textarea_image').val('');
            $('#feed_textarea_photo').hide();
            $('#feed_textarea_photo img').attr('src', '');
        }

        function resetWorkshop()
        {
            $('#workshop_name').val('');
            $('#workshop_date').val('');
            $('#workshop_time').val('');
            $('#workshop_price').val('');
            $('#workshop_charity').val('').trigger('change');
            $('#workshop_invite').val('');
            $('#workshop_desc').val('');
            $('#workshop_capacity').val('');
            $('#workshop_duration').val('');
            $('#feed_textarea_post_form #workshop_active').val("0");
            $('#feed_textarea_workshop').hide();
        }

        function resetFeed()
        {
            $('#feed_textarea_text').val('').trigger('input');
            resetImage();
            resetWorkshop();
        }

        function formatState (state) {            
            var img = $(state.element).data('img');
            var baseUrl = "/user/pages/images/flags";
            var $state = $(
                '<span><img src="' + img + '" class="img-flag" /> ' + state.text + '</span>'
            );
            return $state;
        };

        $('.custom-select2').select2({
            templateResult: formatState
        });
    </script>
</define>
<define header_css>
    <style>
        #feed_textarea_text {
            resize: none;
        }

        
        #feed_textarea_workshop {
            display: none;
        }

        .feed_textarea_image_wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #feed_textarea_photo {
            display: none;
        }

        .feed_textarea_image_preview {
            max-width: 100%;
            max-height: 300px;
        }

        #feed_textarea_image {
            display: none;
        }
    </style>
</define>