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
                            <h4 class="card-title"><?= $lang('schedule_the_following_appointments', array(
                                    'name' => $beneficiary['name']
                                )); ?></h4>
                            <h4 class="card-title"><?= $lang('schedule_the_following_appointments_note'); ?></h4>
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
                                        <?= $lang('date1');?> <span><?= $date1 ?></span>
                                        <input type="text" class="form-control" id="price1" name="price1" placeholder="Price">
                                    </div>
                                    <div class="form-group">
                                        <?= $lang('date2');?>  <span><?= $date2 ?></span>
                                        <input type="text" class="form-control" id="price2" name="price2" placeholder="Price">
                                    </div>
                                    <div class="form-group">
                                        <?= $lang('date3');?> <span><?= $date3 ?></span>
                                        <input type="text" class="form-control" id="price3" name="price3" placeholder="Price">
                                    </div>
                                </div>
                                <div class="col-md-12 text-ar-right">
                                    <button class="btn btn-primary"><?= $lang('schedule'); ?></button>
                                </div>
                            </div>
                        </form>
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