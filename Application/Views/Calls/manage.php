<?php

use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

$lang = Model::get('\Application\Models\Language');

?>
<div class="container">
    <div class="row mt-5 mb-2 justify-content-center">
        <div class="col-md-8">           
            <a href="<?= URL::full('calls/a') ?>" class="text-bold text-secondary"><i class="ri-arrow-left-s-line"></i> <?= $lang('back_to_calls'); ?></a>
        </div>
    </div>  
<!--    <div class="row justify-content-center">-->
<!--        <div class="col-md-8">-->
<!--            <div class="iq-card">-->
<!--                <div class="iq-card-body">-->
<!--                    <form action="--><?//= URL::current() ?><!--">-->
<!--                        <div class="row">-->
<!--                            <div class="col-md-6">-->
<!--                                <div class="form-group">-->
<!--                                    <label for="from">--><?//= $lang('from'); ?><!--</label>-->
<!--                                    <input type="date" class="form-control" name="from" id="from" value="--><?//= $from; ?><!--">-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="col-md-6">-->
<!--                                <div class="form-group">-->
<!--                                    <label for="to">--><?//= $lang('to'); ?><!--</label>-->
<!--                                    <input type="date" class="form-control" name="to" id="to" value="--><?//= $to; ?><!--">-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="col-md-12 text-ar-right">-->
<!--                                <button class="btn btn-primary">--><?//= $lang('submit'); ?><!--</button>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </form>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title"><?= $lang('manage_calls') ?></h4>
                    </div>
                    <div class="iq-card-header-toolbar d-flex align-items-center">
                        <p class="m-0"><a class="create-calling d-none"" data-toggle="modal" data-target="#create-call-modal" href="#"><i class="ri-add-line"></i> <?= $lang('create_call') ?></a></p>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="call-cal">
                        <?php foreach ($slots as $key => $items) : ?>
                            <div class="call-cal-item">
                                <h4 class="mt-2 mb-3"><?= date('M jS, Y', strtotime($key)); ?></h4>
                                <!-- <hr class="m-0"> -->
                                <?php foreach ($items as $item) : ?>
                                    <div class="call-cal-sub-item mb-3">

                                        <div class=" alert alert-danger">
                                            <a href="#" class="pull-right" onclick="deleteSlot(<?= $item['id'] ?>);"><i class="ri-delete-bin-7-line"></i></a>
                                            <p><?= $lang('call_slots_available_for', ['time' => date('g:i a', strtotime($item['time'])), 'price' => $item['price']]); ?></p>
                                        </div>
                                        <?php if ( !empty($item['charity']) ): ?>
                                            <?php
                                                $charities = [];
                                                foreach ($item['charity'] as $charity) {
                                                    $charities[] = '<img class="rounded" src="' . URL::media('Application/Uploads/' . $charity['img'], 'fit:32,32') . '"> <strong>' . htmlentities($charity[$lang->current() . '_name']) . '</strong>';
                                                }
                                            ?>
<!--                                            <p class="font-size-12 text-secondary charity-p">-->

<!--                                            </p>-->
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                        <?php if ($dataAvl) : ?>
                            <a href="<?= URL::full('calls/manage?from=' . $from . '&to=' . $to . '&skip=' . $skip); ?>">More ></a>
                        <?php endif; ?>
                        <?php if (empty($slots)) : ?>
                            <div class="row justify-content-center">
                                <div class="col-md-5 mb-5 text-center">
                                    <img src="<?= URL::asset('Application/Assets/images/empty_workshop.png'); ?>" alt="No calls" class="img-fluid" />
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php View::include('Calls/modal', [
    'charities' => $charities,
]) ?>

<define header_css>
    <style>
        .call-cal-sub-item .alert {
            background-color: var(--iq-primary);
            color: var(--iq-white);
            margin-top: 0;
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 5px;
            font-size: 17px;
        }

        .call-cal-sub-item a {
            color: var(--iq-white);
        }

        .lang-ar .call-cal-sub-item a.pull-right {
            float: left;
        }

        .call-cal-item h4 {
            font-size: 12px;
        }

        .call-cal-sub-item p {
            margin: 0;
        }

        .call-cal-sub-item .charity-p {
            margin: 0;
        }

        .charity-p img{
            width: 20px;
        }
    </style>
</define>
<define footer_js>
    <script>
        <?php if ($isCreated) : ?>
            toast('primary', '<?= $lang('success') ?>', '<?= $lang('call_slot_created') ?>');
        <?php endif; ?>

        function deleteSlot(id) {
            cConfirm('<?= $lang('are_you_sure') ?>', function() {
                $.ajax({
                    url: URLS.delete_call_slot,
                    data: {
                        id: id
                    },
                    success: function(data) {
                        if (data.info == 'success') {
                            window.location.reload();
                            // toast('primary', '<?= $lang('success') ?>', '<?= $lang('delete_successful'); ?>');
                            return;
                        }

                        toast('danger', '<?= $lang('error') ?>', data.payload);
                    }
                })
            });
        }
    </script>
</define>