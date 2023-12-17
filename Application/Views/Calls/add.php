<?php
//
//use System\Core\Model;
//use System\Helpers\URL;
//use System\Libs\FormValidator;
//use System\Core\Config;
//
//$lang = Model::get('\Application\Models\Language');
//
//$formValidator = FormValidator::instance("call_modify");
//
//?>
<!--<div class="container">-->
<!--    <div class="row justify-content-center mt-5 mb-2">-->
<!--        <div class="col-md-8">            -->
<!--            <a href="--><?//= URL::full('calls/manage') ?><!--" class="text-bold text-secondary"><i class="ri-arrow-left-s-line"></i> --><?//= $lang('back_to_manage_calls'); ?><!--</a>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="row justify-content-center">-->
<!--        <div class="col-md-8">-->
<!--            <div class="iq-card">-->
<!--            <div class="iq-card-header d-flex justify-content-between">-->
<!--                    <div class="iq-header-title">-->
<!--                        <h4 class="card-title">--><?//= $lang('create_call') ?><!--</h4>                        -->
<!--                    </div>                    -->
<!--                </div>-->
<!--                <div class="iq-card-body">-->
<!--                    <form action="--><?//= URL::current() ?><!--" method="POST" id="slot-create-form">-->
<!--                        <div class="form-group">-->
<!--                            <div class="row">-->
<!--                                <input type="radio" class="ml-2 mr-2" required name="creation_type" id="single-slots" value="single_slots" checked />-->
<!--                                <label for="single-slots" class="mb-0">--><?//= $lang('single_slot'); ?><!--</label>-->
<!--                            </div>-->
<!--                            <div class="row">-->
<!--                                <input type="radio" class="ml-2 mr-2" required name="creation_type" id="many-slots" value="many_slots" />-->
<!--                                <label for="many-slots" class="mb-0">--><?//= $lang('many_slots'); ?><!--</label>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="form-group">-->
<!--                            <label for="date">--><?//= $lang('date'); ?><!--</label>-->
<!--                            <input min="--><?//= date('Y-m-d'); ?><!--" type="date" name="date" id="date" value="--><?//= $formValidator->getValue('date') ?><!--" class="form-control workshop-date" />-->
<!--                            --><?php //if ( $formValidator->hasError('date') ): ?>
<!--                                <p class="text-danger">--><?//= $formValidator->getError('date'); ?><!--</p>-->
<!--                            --><?php //endif; ?>
<!--                        </div>-->
<!--                        <div class="form-group" id="time-group">-->
<!--                            <label for="time" id="from-time-label">--><?//= $lang('time'); ?><!--</label>-->
<!--                            <input type="time" required min="--><?//= date('H:i', strtotime('+5 minutes')); ?><!--" name="time" id="time" value="--><?//= $formValidator->getValue('time') ?><!--" class="form-control workshop-time" />-->
<!--                            --><?php //if ( $formValidator->hasError('time') ): ?>
<!--                                <p class="text-danger">--><?//= $formValidator->getError('time'); ?><!--</p>-->
<!--                            --><?php //endif; ?>
<!--                        </div>-->
<!--                        <div class="form-group d-none" id="to-time-group">-->
<!--                            <label for="to_time">--><?//= $lang('to_time'); ?><!--</label>-->
<!--                            <input type="time" min="--><?//= date('H:i', strtotime('+5 minutes')); ?><!--" name="to_time" id="to-time" value="--><?//= $formValidator->getValue('to_time') ?><!--" class="form-control workshop-time" />-->
<!--                            --><?php //if ( $formValidator->hasError('to_time') ): ?>
<!--                                <p class="text-danger">--><?//= $formValidator->getError('to_time'); ?><!--</p>-->
<!--                            --><?php //endif; ?>
<!--                        </div>-->
<!--                        <div class="form-group">-->
<!--                            <label for="price">--><?//= $lang('call_price'); ?><!--</label>-->
<!--                            <input type="price" name="price" id="price" class="form-control" value="--><?//= $formValidator->getValue('price') ?><!--"/>-->
<!--                            --><?php //if ( $formValidator->hasError('price') ): ?>
<!--                                <p class="text-danger">--><?//= $formValidator->getError('price'); ?><!--</p>-->
<!--                            --><?php //endif; ?>
<!--                        </div>-->
<!--                        <div class="form-group">-->
<!--                            <label for="charities">--><?//= $lang('profits_for'); ?><!--</label>-->
<!--                            <select class="form-control custom-select2" name="charities[]" id="charities">-->
<!--                                    <option data-img="--><?//= URL::media('Application/Assets/images/no-charity.png', 'fit:32,32') ?><!--" value="">--><?//= $lang('select_charity') ?><!--</option>-->
<!--                                --><?php //foreach ($charities as $charity) : ?>
<!--                                    <option value="--><?//= $charity['id'] ?><!--" data-img="--><?//= URL::media('Application/Uploads/' . $charity['img'], 'fit:32,32') ?><!--">--><?//= $charity[$lang->current() . '_name'] ?><!--</option>-->
<!--                                --><?php //endforeach; ?>
<!--                            </select>-->
<!--                            --><?php //if ( $formValidator->hasError('charities') ): ?>
<!--                                <p class="text-danger">--><?//= $formValidator->getError('charities'); ?><!--</p>-->
<!--                            --><?php //endif; ?>
<!--                        </div>-->
<!--                        <div class="alert alert-danger">-->
<!--                            --><?//= $lang('call_duration_details', [ 'duration' =>  Config::get('Website')->call_duration ]); ?>
<!--                        </div>-->
<!--                        <div class="modal-footer">                            -->
<!--                            <button type="submit" class="btn btn-primary">--><?//= $lang('submit'); ?><!--</button>-->
<!--                        </div>-->
<!--                    </form>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!---->
<!--<define footer_js>-->
<!--    <script>-->
<!--        var isSubmitted = false;-->
<!--        $('#slot-create-form').on('submit', function(e) {            -->
<!--            if ( isSubmitted ) {-->
<!--                e.preventDefault();-->
<!--                return;-->
<!--            }-->
<!---->
<!--            isSubmitted = true;-->
<!--            $('button[type=submit]').text('--><?//= $lang('submitting') ?>//')[0].disable = true;
//        });
//         $(".workshop-date").on('change', function(e) {
//            var selectedDate = $(this).val();
//
//            var dateObj = new Date();
//            var year = dateObj.getFullYear();
//            var month = dateObj.getMonth()+1;
//            var date = dateObj.getDate();
//
//            if(month < 10) {
//                month = '0' + month;
//            }
//
//            if(date < 10) {
//                date = '0' + date;
//            }
//
//            var currentDate = year+'-'+(month)+'-'+date;
//
//            if( selectedDate > currentDate ) {
//                $(".workshop-time").removeAttr('min');
//            } else {
//                $(".workshop-time").attr('min', '<?//= date('H:i', strtotime('+5 minutes')); ?>//');
//            }
//        })
//
//        function formatState(state) {
//            var img = $(state.element).data('img');
//            var $state = $(
//                '<span><img src="' + img + '" class="img-flag" /> ' + state.text + '</span>'
//            );
//            return $state;
//        };
//
//        $('.custom-select2').select2({
//            templateResult: formatState,
//            // dropdownCssClass: 'create-workshop-select'
//        });
//
//        $('#single-slots').on('click', (e) => {
//            $('#to-time').prop('required',false);
//            $('#to-time-group').addClass('d-none');
//            $('#from-time-label').text("<?//= $lang('time')?>//");
//        });
//
//        $('#many-slots').on('click', (e) => {
//            $('#to-time').prop('required',true);
//            $('#to-time-group').removeClass('d-none');
//            $('#from-time-label').text("<?//= $lang('from_time')?>//");
//        });
//
//    </script>
//</define>