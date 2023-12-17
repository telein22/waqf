<?php

use Application\Helpers\WorkshopHelper;
use Application\Models\Call;
use Application\Models\Language;
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
                    <div class="card-body">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th><?= $lang('id') ?></th>
                                    <th><?= $lang('owner') ?></th>
                                    <th><?= $lang('date') ?></th>
                                    <th><?= $lang('bookings') ?></th>
                                    <th><?= $lang('is_expired') ?></th>
                                    <th><?= $lang('status') ?></th>
                                    <th><?= $lang('action') ?></th>
                                </tr>
                            </thead>
                            <?php foreach ($slots as $slot) : ?>
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
                                    <td>

                                        <?php if($userInfo['type'] == \Application\Models\User::TYPE_ENTITY) : ?>
                                            <a href="<?= URL::full('entities/service-log/' . Call::ENTITY_TYPE . '/' . $slot['id']) ?>"><?= $lang('logs'); ?></a>
                                        <?php elseif($userInfo['type'] == \Application\Models\User::TYPE_ADMIN) : ?>
                                            <a href="<?= URL::full('admin/service-log/' . Call::ENTITY_TYPE . '/' . $slot['id']) ?>"><?= $lang('logs'); ?></a>
                                        <?php endif; ?>
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
<define footer_js>
    <script>
        $(function() {
            $(".datatable").DataTable({
                "order": [
                    [0, "desc"]
                ],
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            })
        });
    </script>
</define>