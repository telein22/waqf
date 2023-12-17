<?php

use System\Core\Model;
use System\Helpers\URL;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');
?>
<!-- Main content -->
<section class="content">
    <!-- <h3>Revenue</h3>
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= number_format($adminRevenue) ?>SR</h3>

                    <p>Admin</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-color1">
                <div class="inner">
                    <h3><?= number_format($advisorRevenue) ?>SR</h3>

                    <p>Advisor (Transferred Amount)</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-color2">
                <div class="inner">
                    <h3><?= number_format($charityRevenue) ?>SR</h3>

                    <p>Charity (Transferred Amount)</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
            </div>
        </div>
    </div> -->
    <h3><?= $lang('stats') ?></h3>
    <div class="row">
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-color3">
                <div class="inner">
                    <h3><?= $activeUsersCount ?></h3>

                    <p><?= $lang('users') ?> (Active)</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-color4">
                <div class="inner">
                    <h3><?= $activeFeedsCount ?></h3>

                    <p><?= $lang('feeds') ?></p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-color5">
                <div class="inner">
                    <h3><?= $charitiesCount ?></h3>

                    <p><?= $lang('charities') ?></p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <h3><?= $lang('message_stats') ?></h3>
    <div class="row">
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $convoCompleteCount ?></h3>

                    <p><?= $lang('completed') ?></p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?= $convCurrentCount ?></h3>

                    <p><?= $lang('current') ?></p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?= $convoCancelledCount ?></h3>

                    <p><?= $lang('cancelled_expired') ?></p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <h3><?= $lang('call_stats') ?></h3>
    <div class="row">
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $callCompleteCount ?></h3>

                    <p><?= $lang('completed') ?></p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?= $callCurrentCount ?></h3>

                    <p><?= $lang('current') ?></p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?= $callCancelledCount ?></h3>

                    <p><?= $lang('cancelled_expired') ?></p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <h3><?= $lang('workshop_stats') ?></h3>
    <div class="row">
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $workshopCompleteCount ?></h3>

                    <p><?= $lang('completed') ?></p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?= $workshopCurrentCount ?></h3>

                    <p><?= $lang('current') ?></p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?= $workshopCancelledCount ?></h3>

                    <p><?= $lang('cancelled_expired') ?></p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
    </div>
</section>
<!-- /.content -->