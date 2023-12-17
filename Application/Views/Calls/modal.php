<?php

use Application\Models\ProfitProceed;
use System\Core\Config;
use System\Core\Model;
use System\Helpers\URL;
use Application\Helpers\ProfitsProceedHelper;

$lang = Model::get('\Application\Models\Language');
$profitsProceeds = ProfitsProceedHelper::getProfitsProceeds();
?>
<div class="modal fade" id="create-call-modal" tabindex="-1" role="dialog" aria-labelledby="create-call-modal-label" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="create-call-modal-label"><?= $lang('create_call') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= URL::full('calls/add') ?>" method="POST" id="slot-create-form" class="p-2">
                <div class="form-group">
                    <div class="row">
                        <input type="radio" class="ml-2 mr-2" required name="creation_type" id="single-slots" value="single_slots" checked />
                        <label for="single-slots" class="mb-0"><?= $lang('single_slot'); ?></label>
                    </div>
                    <div class="row">
                        <input type="radio" class="ml-2 mr-2" required name="creation_type" id="many-slots" value="many_slots" />
                        <label for="many-slots" class="mb-0"><?= $lang('many_slots'); ?></label>
                    </div>
                </div>
                <div class="form-group required">
                    <label for="date"><?= $lang('date'); ?></label>
                    <input required min="<?= date('Y-m-d'); ?>" type="date" name="date" id="slot-date" class="form-control call-date" />
                </div>
                <div class="form-group required" id="time-group">
                    <label for="time" id="from-time-label"><?= $lang('time'); ?></label>
                    <input type="time" required name="time" id="slot-time" class="form-control call-time" />

                </div>
                <div class="form-group required d-none" id="to-time-group">
                    <label for="to_time"><?= $lang('to_time'); ?></label>
                    <input type="time" name="to_time" id="slot-to-time" class="form-control call-time" />

                </div>
                <div class="form-group required">
                    <label for="price"><?= $lang('call_price'); ?></label>
                    <input type="price" required name="price" id="slot-price" class="form-control" />

                </div>
                <div class="form-group">
                    <label for="call-charities"><?= $lang('profits_for'); ?></label>
                    <select class="form-control custom-select2" name="profit_proceed_type_id" id="workshop_charity">
                        <?php foreach ($profitsProceeds as $profitsProceed) : ?>
                            <option value="<?= $profitsProceed['id'] ?>" <?= $profitsProceed['code'] == ProfitProceed::TYPE_PERSONAL ? 'selected' : '' ?> data-img="<?= URL::media('Application/Uploads/' . $profitsProceed['icon'], 'fit:32,32') ?>"><?= $profitsProceed["name_{$lang->current()}"] ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
                <div class="alert alert-danger">
                    <?= $lang('call_duration_details', [ 'duration' =>  Config::get('Website')->call_duration ]); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $lang('close_modal') ?></button>
                    <button type="submit" id="submit" class="btn btn-primary"><?= $lang('submit'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<define header_css>
    <style>
    .create-call-select {
        z-index: 100000;
    }
    </style>
</define>
<define footer_js>
    <script>
        function formatState(state) {
            var img = $(state.element).data('img');
            var $state = $(
                '<span><img src="' + img + '" class="img-flag" /> ' + state.text + '</span>'
            );
            return $state;
        };

        $('.custom-select2').select2({
            templateResult: formatState,
            dropdownCssClass: 'create-call-select'
        });

        //$('#call-form').on('submit', function(e) {
        //    e.preventDefault();
        //
        //    var $form = $(this);
        //    var $btn = $form.find('button[type=submit]');
        //
        //    var formData = new FormData($form[0]);
        //
        //    $.ajax({
        //        url: URLS.call_create,
        //        beforeSend: function() {
        //            $btn.text('<?//= $lang('submitting') ?>//')[0].disabled = true;
        //        },
        //        type: 'POST',
        //        dataType: 'JSON',
        //        accepts: 'JSON',
        //        data: formData,
        //        processData: false,
        //        contentType: false,
        //        success: function( data ) {
        //            if ( data.info === 'error' )
        //            {
        //                toast('danger', '<?//= $lang('error') ?>//', data.payload);
        //                return;
        //            }
        //
        //            // <?php //// echo $onPostComplete ?>//(data);
        //
        //            window.location.reload();
        //
        //            // resetFeed();
        //        },
        //        complete: function() {
        //            $btn.text('<?//= $lang('submit') ?>//')[0].disabled = false;
        //        }
        //    });
        //
        //    console.log($btn);
        //
        //});

        $('#single-slots').on('click', (e) => {
            $('#to-time').prop('required',false);
        $('#to-time-group').addClass('d-none');
        $('#from-time-label').text("<?= $lang('time')?>");
        });

        $('#many-slots').on('click', (e) => {
            $('#to-time').prop('required',true);
        $('#to-time-group').removeClass('d-none');
        $('#from-time-label').text("<?= $lang('from_time')?>");
        });

        $('#slot-create-form').on('submit', (e) => {
            if ($('#submit').hasClass('cursor_not_allowed')) {
                e.preventDefault();
            }

            var date = $('#slot-date').val(),
                time = $('#slot-time').val(),
                toTime = $('#slot-to-time').val(),
                slotDatetime1 = new Date(`${date} ${time}`),
                slotDatetime2 = new Date(`${date} ${toTime}`),
                currentDatetime = new Date(),
                isManySlotsCase = $('#many-slots').is(':checked');

            currentDatetime.setMinutes(currentDatetime.getMinutes() + 5); // Add 5 minutes

            if (slotDatetime1 < currentDatetime) {
                e.preventDefault();
                toast('danger', "<?= $lang('invalid_slot_time') ?>")
            }
            else if (isManySlotsCase && slotDatetime2 < slotDatetime1) {
                e.preventDefault();
                toast('danger', "<?= $lang('end_time_must_greater') ?>")
            } else {
                $('#submit').addClass('cursor_not_allowed');
            }
        })
    </script>
</define>