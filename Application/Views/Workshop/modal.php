<?php

use System\Core\Model;
use System\Helpers\URL;
use Application\Helpers\ProfitsProceedHelper;
use Application\Models\ProfitProceed;

$lang = Model::get('\Application\Models\Language');
$profitsProceeds = ProfitsProceedHelper::getProfitsProceeds();
?>

<div class="modal fade" id="create-workshop-modal" tabindex="-1" role="dialog" aria-labelledby="create-workshop-modal-label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
<!--            <img  id="loader" class="d-none" src="--><?php //= URL::asset("Application/Assets/images/page-img/ajax-loader.gif"); ?><!--" alt="loader" style="width: 75px; height: 75px; margin: 0 auto; position: fixed; top:50%; left: 50%;" >-->
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="create-workshop-modal-label"><?= $lang('create_workshop') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="#" id="workshop-form">
                <div class="modal-body">
                    <div class="form-group required">
                        <label for="workshop_name"><?= $lang('workshop_name'); ?></label>
                        <input type="text" class="form-control" id="workshop_name" name="name" />
                    </div>
                    <div class="form-group required">
                        <label for="workshop_desc"><?= $lang('workshop_description'); ?></label>
                        <textarea class="form-control" id="workshop_desc" name="desc"></textarea>
                    </div>
                    <div class="form-group required">
                        <label for="date"><?= $lang('workshop_date'); ?></label>
                        <input min="<?= date('Y-m-d', strtotime('+ 5 minutes')); ?>" max="<?= date('Y-m-d', strtotime('+ 5 years')); ?>" type="date" name="date" id="date" value="" class="form-control workshop-date" />
                    </div>
                    <div class="form-group required">
                        <label for="time"><?= $lang('workshop_time'); ?></label>
                        <input type="time" name="time" id="time" class="form-control workshop-time" />
                    </div>
                    <!-- <div class="form-group">
                        <label for="workshop_date"><?php // echo $lang('workshop_date'); ?></label>
                        <input type="datetime-local" min="<?php // echo date('Y-m-d', strtotime('+1 day')); ?>T<?php // echo date('H:i', strtotime('+1 day')); ?>" class="form-control" id="workshop_date" name="date" />
                    </div> -->
                    <div class="form-group required">
                        <label for="workshop_duration"><?= $lang('workshop_duration'); ?></label>
                        <input type="number" class="form-control" id="workshop_duration" name="duration" />
                    </div>
                    <div class="form-group required">
                        <label for="workshop_price"><?= $lang('workshop_price'); ?></label>
                        <input type="text" class="form-control" id="workshop_price" name="price" />
                    </div>
                    <div class="form-group required">
                        <label for="workshop_capacity"><?= $lang('workshop_capacity'); ?></label>
                        <input type="number" class="form-control" id="workshop_capacity" name="capacity" />
                    </div>
                    <div class="form-group required">
                        <label for="workshop_charity"><?= $lang('profits_for'); ?></label>
                        <select class="form-control custom-select2" name="profit_proceed_type_id" id="workshop_charity">
                            <?php foreach ($profitsProceeds as $profitsProceed) : ?>
                                <option value="<?= $profitsProceed['id'] ?>" <?= $profitsProceed['code'] == ProfitProceed::TYPE_PERSONAL ? 'selected' : '' ?> data-img="<?= URL::media('Application/Uploads/' . $profitsProceed['icon'], 'fit:32,32') ?>"><?= $profitsProceed["name_{$lang->current()}"] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group d-none">
                        <label for="workshop_invite"><?= $lang('workshop_invite'); ?></label>                        
                        <select class="form-control workshop-invite" name="invite">
                        </select>
                    </div>
                </div>
                <div class="modal-footer custom-align">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $lang('close_modal') ?></button>
                    <button type="submit" class="btn btn-primary"><?= $lang('submit'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<define header_css>
    <style>
        .create-workshop-select {
            z-index: 100000;
        }
        
        .create-workshop-invite-select {
            z-index: 100000;
        }
    </style>
</define>
<define footer_js>
    <script>
        $('.workshop-invite').select2({
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
            },
            dropdownCssClass: 'create-workshop-invite-select'
        });

        // $(".workshop-date").on('change', function(e) {
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

        function formatState(state) {
            var img = $(state.element).data('img');
            var $state = $(
                '<span><img src="' + img + '" class="img-flag" /> ' + state.text + '</span>'
            );
            return $state;
        };

        $('.custom-select2').select2({
            templateResult: formatState,
            dropdownCssClass: 'create-workshop-select'
        });

        $('#workshop-form').on('submit', function(e) {
            e.preventDefault();

            $('#loader').removeClass('d-none');

            var $form = $(this);
            var $btn = $form.find('button[type=submit]');

            var formData = new FormData($form[0]);

            $.ajax({
                url: URLS.workshop_create,
                beforeSend: function() {
                    $btn.text('<?= $lang('submitting') ?>')[0].disabled = true;
                },
                type: 'POST',
                dataType: 'JSON',
                accepts: 'JSON',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.info === 'error') {
                        $('#loader').addClass('d-none');
                        toast('danger', '<?= $lang('error') ?>', data.payload);
                        return;
                    }

                    <?php if($user['phone']): ?>
                        $('body').append('<iframe src="<?= URL::full('workshop-poster') . '?user_id=' . $user['id'] ?>"  style="position: absolute;width:0;height:0;border:0;"></iframe>');
                    <?php else: ?>
                        workshopStorringOnComplete();
                    <?php endif ?>
                },
                complete: function() {
                    $btn.text('<?= $lang('submit') ?>')[0].disabled = false;
                }
            });

            console.log($btn);

        });

        function workshopStorringOnComplete() {
            $('#loader').addClass('d-none');
            $('#workshop-form input, #workshop-form textarea').each(function() {
                $(this).val('');
            })
            $('#create-workshop-modal').modal('hide');

            toast('success', '<?= $lang('success') ?>');
        }
    </script>
</define>