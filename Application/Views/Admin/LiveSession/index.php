<?php

use Application\Models\Language;
use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;

/**
 * @var Language
 */
$lang = Model::get(Language::class);

$formValidator = FormValidator::instance('filter');

?>
<div class="content">
    <div class="container-fluid">
        <!-- <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <?= $lang('workshop'); ?>
                    </div>
                    <div class="card-body">
                        <span class="h1">
                            <?= $workshopNowSlot; ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <?= $lang('call'); ?>
                    </div>
                    <div class="card-body">
                        <span class="h1">
                            <?= $callNowSlot; ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <?= $lang('free'); ?>
                    </div>
                    <div class="card-body">
                        <span class="h1">
                            <?= $totalAllowed - $workshopNowSlot + $callNowSlot; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div> -->
        
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <form method="POST" action="<?= URL::current() ?>">
                            <div class="form-group">
                                <label><?= $lang('date') ?></label>
                                <input type="date" name="date" class="form-control" value="<?= $formValidator->getValue('date', date('Y-m-d')) ?>">
                            </div>
                            <button type="submit" class="btn btn-primary"><?= $lang('submit') ?></button>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?= $lang('time') ?></th>
                                            <th><?= $lang('workshop') ?></th>
                                            <th><?= $lang('call') ?></th>
                                            <th><?= $lang('free') ?></th>
                                        </tr>
                                    </thead>
                                <?php foreach( $data as $item ): ?>
                                    <tr>
                                        <td><?= date('H:i', strtotime($item['from'])) . ' - ' . date('H:i', strtotime($item['to'])) ?></td>
                                        <td><?= $item['workshop'] ?></td>
                                        <td><?= $item['call'] ?></td>
                                        <td><?= $item['free'] < 0  ? 0 : $item['free'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>