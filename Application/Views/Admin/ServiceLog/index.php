<?php

use Application\Helpers\WorkshopHelper;
use Application\Models\Call;
use Application\Models\Language;
use Application\Models\Workshop;
use System\Core\Config;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get(Language::class);
?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <strong><?= $lang($entityType) ?></strong>
                    </div>
                    <div class="card-body">
                        <div class="details">
                            <?php if ($entityType == Workshop::ENTITY_TYPE) : ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?= $lang('id') ?></th>
                                            <th><?= $lang('name') ?></th>
                                            <th><?= $lang('owner') ?></th>
                                            <th><?= $lang('date') ?></th>
                                            <th><?= $lang('workshop_duration') ?></th>
                                            <th><?= $lang('price') ?></th>
                                            <th><?= $lang('workshop_capacity') ?></th>
                                            <th><?= $lang('bookings') ?></th>
                                            <th><?= $lang('is_expired') ?></th>
                                            <th><?= $lang('status') ?></th>
                                        </tr>
                                    </thead>
                                    <?php foreach ([$entity] as $workshop) : ?>
                                        <tr>
                                            <td><?= $workshop['id']; ?></td>
                                            <td><?= htmlentities($workshop['name']); ?></td>
                                            <td><a href="<?= URL::full('profile/' . $workshop['user']['id']) ?>"><?= $workshop['user']['name'] . ' (' . $workshop['user']['email'] . ')' ?></a></td>
                                            <td><?= $workshop['date'] ?></td>
                                            <td><?= $workshop['duration'] ?></td>
                                            <td><?= $lang('c_price', ['p' => $workshop['price']]); ?></td>
                                            <td><?= $workshop['capacity']; ?></td>
                                            <td><?= $workshop['participant_count'] ?></td>
                                            <td>
                                                <?php
                                                if ($workshop['status'] == Workshop::STATUS_NOT_STARTED) {
                                                    // Then this could be expired.
                                                    if (WorkshopHelper::isExpired($workshop['date'], $workshop['duration'])) {
                                                        echo $lang('yes');
                                                    } else {
                                                        echo '-';
                                                    }
                                                } else if ($workshop['status'] == Workshop::STATUS_CURRENT) {
                                                    // This could expire due not not closed.
                                                    $wConfig = Config::get("Website");
                                                    if (strtotime($workshop['date']) + (($workshop['duration'] - $wConfig->join_padding) * 60) <= time()) {
                                                        echo $lang('yes');
                                                    } else {
                                                        echo '-';
                                                    }
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>
                                            <td><?= $lang($workshop['status']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            <?php elseif ($entityType == Call::ENTITY_TYPE) : ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?= $lang('id') ?></th>
                                            <th><?= $lang('owner') ?></th>
                                            <th><?= $lang('date') ?></th>
                                            <th><?= $lang('bookings') ?></th>
                                            <th><?= $lang('is_expired') ?></th>
                                            <th><?= $lang('status') ?></th>
                                        </tr>
                                    </thead>
                                    <?php foreach ([$entity] as $slot) : ?>
                                        <tr>
                                            <td><?= $slot['id'] ?></td>
                                            <td><a href="<?= URL::full('profile/' . $slot['user']['id']) ?>"><?= htmlentities($slot['user']['name']) ?> (<?= htmlentities($slot['user']['email']) ?>)</a></td>
                                            <td><?= $slot['date'] ?> <?= $slot['time'] ?></td>
                                            <td><?= $slot['booking'] ?></td>
                                            <td>
                                                <?php
                                                $call = $slot['call'];
                                                if ($call) {
                                                    if ($call['status'] == Call::STATUS_NOT_STARTED) {
                                                        // Then this could be expired.
                                                        if (WorkshopHelper::isExpired($call['date'], $call['duration'])) {
                                                            echo $lang('yes');
                                                        } else {
                                                            echo '-';
                                                        }
                                                    } else if ($call['status'] == Call::STATUS_CURRENT) {
                                                        // This could expire due not not closed.
                                                        $wConfig = Config::get("Website");
                                                        if (strtotime($call['date']) + (($call['duration'] - $wConfig->join_padding) * 60) <= time()) {
                                                            echo $lang('yes');
                                                        } else {
                                                            echo '-';
                                                        }
                                                    } else {
                                                        echo '-';
                                                    }
                                                } else {
                                                    echo '-';
                                                }

                                                ?>
                                            </td>
                                            <td><?= $slot['call'] ? $lang($slot['call']['status']) : '-' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            <?php endif; ?>
                            <hr>
                        </div>
                        <div class="participants">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th><?= $lang('user') ?></th>
                                        <th><?= $lang('action') ?></th>
                                        <th><?= $lang('time') ?></th>
                                    </tr>
                                </thead>
                            
                                <?php foreach ($logs as $log) : ?>
                                    <tr>
                                        <td>
                                            <a href="<?= URL::full('profile/' . $log['action_by']['id']) ?>"><?= $log['action_by']['name'] ?></a>
                                        </td>
                                        <td>
                                            <?= $lang($log['action']) ?>
                                        </td>
                                        <td>
                                            <span class="d-none"><?= $log['created_at'] ?></span><?= date('d-m-Y H:i', $log['created_at']); ?>
                                        </td>
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

<define footer_js>
    <script>
        $(function() {
            $(".datatable").DataTable({
                "order": [[ 2, "desc" ]],
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            })
        });
    </script>
</define>