<?php

use Application\Models\User;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

$lang = Model::get('\Application\Models\Language');

/**
 * @var User
 */
$userM = Model::get(User::class);

?>

<div class="container mt-5 workshop-page">
    <div class="row">
        <div class="col-md-12">
            <div class="iq-card">

                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title"><?= $lang('customize_calls', array(
                                                    'filter' => $user['name']
                                                )); ?></h4>
                    </div>
                    <div class="iq-card-header-toolbar d-flex align-items-center"></div>
                </div>
            </div>
            <div class="iq-card">
                <div class="iq-card-body">
                    <form action="<?= URL::current() ?>" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input  type="datetime-local" class="form-control" name="date1" value="<?= $date; ?>" min="<?= date('Y-m-d', strtotime('today')); ?>" required>
                                </div>
                                <div class="form-group">
                                    <input type="datetime-local" class="form-control" name="date2" value="<?= $date; ?>" min="<?= date('Y-m-d', strtotime('today')); ?>" required>
                                </div>
                                <div class="form-group">
                                    <input  type="datetime-local" class="form-control" name="date3" value="<?= $date; ?>" min="<?= date('Y-m-d', strtotime('today')); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-12 text-ar-right">
                                <button class="btn btn-primary"><?= $lang('send_request'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
<!--            <div class="iq-card">-->
<!--                <div class="iq-card-body">-->
<!--                    <form action="--><?php //echo URL::current() ?><!--" method="POST">-->
<!--                        <div class="row">-->
<!--                            <div class="col-md-12">-->
<!--                                <div class="form-group">-->
<!--                                    <label for="date" class="sr-only">--><?php //echo $lang('from'); ?><!--</label>-->
<!--                                    <input type="date" class="form-control" name="date" id="date" value="--><?php //echo $date; ?><!--" min="--><?php //echo date('Y-m-d', strtotime('today')); ?><!--">-->
<!--                                </div>-->
<!--                            </div>                            -->
<!--                            <div class="col-md-12 text-ar-right">-->
<!--                                <button class="btn btn-primary">--><?php //echo $lang('submit'); ?><!--</button>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </form>-->
<!--                </div>-->
<!--            </div>-->
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title"><?= $lang('booking_calls', array(
                                'filter' => $user['name']
                            )); ?></h4>
                    </div>
                    <div class="iq-card-header-toolbar d-flex align-items-center"></div>
                </div>
            </div>
            <div class="row workshop-list">
                <div class="col-12">
                    <?php if (!empty($slots)) : ?>
                        <?php foreach ($slots as $key => $items) : ?>
                            <div class="iq-card">
                                <div class="iq-card-body">
                                    <div class="call-cal-item">
                                        <h4 class="badge badge-secondary mt-2 mb-3" style="font-size: 16px"><?= date('M jS, Y', strtotime($key)); ?></h4>
                                        <!-- <hr class="m-0"> -->
                                        <?php foreach ($items as $item) : ?>
                                            <div class="call-cal-sub-item mb-3">                                                

                                                <div class=" alert alert-primary">
                                                    <?php if ( $userM->isLoggedIn() ): ?>
                                                    <form action="<?= URL::full('calls/checkout') ?>" method="POST">
                                                        <button type="submit" class="pull-right btn btn-primary" onclick="deleteSlot(<?= $item['id'] ?>);"><?= $lang('book_now') ?></button>
                                                        <input type="hidden" value="<?= $item['id'] ?>" name="slot"/>
                                                    </form>
                                                    <?php else: ?>
                                                        <a href="<?= URL::full('login') ?>" class="btn btn-primary pull-right"><?= $lang('book_now'); ?></a>
                                                    <?php endif; ?>
                                                    <p><?= $lang('call_slots_available_for', ['time' => date('g:i a', strtotime($item['time'])), 'price' => $item['price']]); ?></p>
                                                </div>
                                                <?php if (!empty($item['charity'])) : ?>
                                                    <?php
                                                    $charities = [];
                                                    foreach ($item['charity'] as $charity) {
                                                        $charities[] = '<img class="rounded" src="' . URL::media('Application/Uploads/' . $charity['img'], 'fit:32,32') . '"> <strong>' . htmlentities($charity[$lang->current() . '_name']) . '</strong>';
                                                    }
                                                    ?>
<!--                                                    <p class="font-size-12 text-secondary charity-p">-->

<!--                                                    </p>-->
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <!-- <?php // if ($dataAvl) : ?>
                        <a href="<?php // echo URL::full('calls/manage?from=' . $from . '&to=' . $to . '&skip=' . $skip); ?>">More ></a>
                    <?php // endif; ?> -->
                    <?php if (empty($slots)) : ?>
                        <div class="iq-card">
                            <div class="iq-card-body">
                                <div class="row justify-content-center">
                                    <div class="col-md-5 mb-5 text-center">
                                        <img src="<?= URL::asset('Application/Assets/images/empty_workshop.png'); ?>" alt="No calls" class="img-fluid" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php View::include('Checkout/modal'); ?>
<define header_css>
    <style>
        .workshop-page .col-md-12 {
            /* border-bottom: 1px solid var(--iq-border-light); */
        }

        .workshop-page .col-md-12:last-child {
            border-bottom: none;
        }

        .lang-ar .call-cal-item button.pull-right,
        .lang-ar .call-cal-item a.pull-right {
            float: left;
        }

        .lang-ar .call-cal-item {
            text-align: right;
        }
    </style>
</define>
<?php if (!$userM->isLoggedIn()) : ?>
    <define header_css>
        <style>
            .btn-primary {
                background-color: #3f4aaa;
                border-color: #3f4aaa;
            }


            .iq-card {
                margin-bottom: 15px;
                background-color: white;
                padding: 15px;
                border-radius: 5px;
                -webkit-box-shadow: 0px 0px 20px 0px rgba(44, 101, 144, 0.1);
                box-shadow: 0px 0px 20px 0px rgba(44, 101, 144, 0.1);
            }


            .form-group {
                margin-bottom: 15px;
            }

            body {
                background: #fafafb;
            }

        </style>
    </define>
<?php endif; ?>


<define footer_js>
    <script>
        <?php if ($isCallRequestCreated) : ?>
        toast('primary', '<?= $lang('success') ?>', '<?= $lang('call_request_created') ?>');
        <?php endif; ?>
    </script>
</define>
