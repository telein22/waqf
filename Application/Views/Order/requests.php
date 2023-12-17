<?php

use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Workshop;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');
?>

<div class="container">
    <div class="row mt-5 mb-2">
        <div class="col-md-12">
            <a href="#" class="text-bold text-secondary"><i class="ri-arrow-left-s-line"></i> Back to home</a> / <?= $lang('requests'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="order-request-menu list-group iq-menu">
                <a href="<?= URL::full('/order/requests/?t=' . Workshop::ENTITY_TYPE) ?>" class="list-group-item iq-waves-effect <?= $type == Workshop::ENTITY_TYPE ? 'active' : '' ?>">
                    <i class="ri-slideshow-line"></i> <?= $lang('workshops') ?> <small class="badge order-request-badge  badge-light float-right pt-1"><?= $workshopCount ?></small>
                </a>
                <a class="list-group-item iq-waves-effect <?= $type == Call::ENTITY_TYPE ? 'active' : '' ?>" href="<?= URL::full('/order/requests/?t=' .  CALL::ENTITY_TYPE) ?>">
                    <i class="ri-phone-line"></i> <?= $lang('calls') ?> <small class="badge order-request-badge  badge-light float-right pt-1"><?= $callCount ?></small>
                </a>
                <a class="list-group-item iq-waves-effect <?= $type == Conversation::ENTITY_TYPE ? 'active' : '' ?>" href="<?= URL::full('/order/requests/?t=' . Conversation::ENTITY_TYPE) ?>">
                    <i class="ri-message-2-line"></i> <?= $lang('messaging') ?> <small class="badge order-request-badge  badge-light float-right pt-1"><?= $messageCount ?></small>
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="iq-card">
                <div class="iq-card-body">
                    <?php if (!empty($orders)) : ?>
                        <div class="container">
                            <div class="row request-row">
                                <div class="col-md-12">
                                    <?php foreach ($orders as $order) : ?>
                                        <?php
                                        View::include(
                                            'Order/request_item',
                                            ['item' => $order]
                                        );
                                        ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="row justify-content-center">
                            <div class="col-md-5 mb-5 text-center">
                                <img src="<?= URL::asset('Application/Assets/images/empty_workshop.png'); ?>" alt="No workshop" class="img-fluid" />
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($limit == count($orders)) : ?>
                        <a href="#" id="more-request-btn">More ></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<define footer_js>
    <script>
        var requestSkip = <?= count($orders) ?>;

        (function() {

            var isBusy = false;

            $('#more-request-btn').on('click', function(e) {
                if (isBusy) return;

                e.preventDefault();

                var $self = $(this);

                $.ajax({
                    url: URLS.more_order_requests,
                    data: {
                        skip: requestSkip,
                    },
                    beforeSend: function() {
                        isBusy = true;
                    },
                    success: function(data) {
                        if (data.info !== 'success') return;

                        var r = data.payload.requests;
                        for (var i = 0; i < r.length; i++) {
                            $('.request-row').append('<div class="col-md-12">' + r[i] + '</div>');
                        }

                        requestSkip = data.payload.skip;

                        if (!data.payload.dataAvl) {
                            $self.remove();
                        }
                    },
                    complete: function() {
                        isBusy = false;
                    }
                });

            });
        })();

        var isRequestBusy = false;

        function requestAction(type, orderId) {
            if (isRequestBusy) return;
            
            cConfirm("<?= $lang('are_you_sure') ?>", function() {
                var dialog = bootbox.dialog({
                    message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> <?= $lang('please_wait') ?>...</p>',
                    centerVertical: true,
                    closeButton: false
                });
                $.ajax({
                    url: type == 'accept' ? URLS.accept_order_request : URLS.decline_order_request,
                    data: {
                        id: orderId
                    },
                    beforeSend: function() {
                        isRequestBusy = true;

                    },
                    success: function(data) {
                        if (data.info !== 'success') {
                            toast('danger', '<?= $lang('error') ?>', data.payload);
                            return;
                        }

                        $('#request-item-' + orderId)
                            .find('button')
                            .addClass('d-none');

                        if (type == 'accept') {
                            $('#request-item-' + orderId).find('.accepted-status').removeClass('d-none');
                        } else {
                            $('#request-item-' + orderId).find('.declined-status').removeClass('d-none');
                        }

                        requestSkip -= 1;

                    },
                    complete: function() {
                        dialog.modal('hide');
                        isRequestBusy = false;
                    }
                });
            });

        }
    </script>
</define>