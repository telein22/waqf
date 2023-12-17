<?php

use Application\Helpers\DateHelper;

?>

<div class="col-md-12">
    <div class="iq-card log-card">
        <div class="iq-card-body">
            <div class="earning-logs">
                <span class="pull-right text-secondary"><?= DateHelper::butify( $transaction['created_at'] ) ?></span>
                <p class="m-0"> New Transaction</p>
            </div>
        </div>
    </div>
</div>