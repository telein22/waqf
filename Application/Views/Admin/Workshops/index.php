<?php

use Application\Helpers\WorkshopHelper;
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
                    <div class="card-body">
                        <table class="table datatable" >
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
                                <th><?= $lang('action') ?></th>
                            </tr>
                            </thead>
                            <?php foreach ( $workshops as $workshop ): ?>                                
                                <tr>
                                    <td><?= $workshop['id']; ?></td>
                                    <td><?= htmlentities($workshop['name']); ?></td>
                                    <td><a href="<?= URL::full('profile/' . $workshop['user']['id']) ?>"><?= $workshop['user']['name'] . ' (' . $workshop['user']['email'] . ')' ?></a></td>
                                    <td><?= $workshop['date'] ?></td>
                                    <td><?= $workshop['duration'] ?></td>
                                    <td><?= $lang('c_price', ['p' => $workshop['price'] ]); ?></td>
                                    <td><?= $workshop['capacity']; ?></td>
                                    <td><?= $workshop['participant_count'] ?></td>
                                    <td>
                                        <?php
                                            if ( $workshop['status'] == Workshop::STATUS_NOT_STARTED )
                                            {
                                                // Then this could be expired.
                                                if ( WorkshopHelper::isExpired($workshop['date'], $workshop['duration']) )
                                                {
                                                    echo $lang('yes');
                                                } else {
                                                    echo '-';
                                                }
                                            } else if ( $workshop['status'] == Workshop::STATUS_CURRENT ) {
                                                // This could expire due not not closed.
                                                $wConfig = Config::get("Website");
                                                if ( strtotime($workshop['date']) + (($workshop['duration'] - $wConfig->join_padding) * 60) <= time() ) {
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
                                    <td>
                                        <?php if($userInfo['type'] == \Application\Models\User::TYPE_ENTITY) : ?>
                                        <a href="<?= URL::full('entities/service-log/' . Workshop::ENTITY_TYPE . '/' . $workshop['id']) ?>"><?= $lang('logs'); ?></a>
                                        <?php elseif($userInfo['type'] == \Application\Models\User::TYPE_ADMIN) : ?>
                                        <a href="<?= URL::full('admin/service-log/' . Workshop::ENTITY_TYPE . '/' . $workshop['id']) ?>"><?= $lang('logs'); ?></a>
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
                "order": [[ 0, "desc" ]],
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            })
        });
    </script>
</define>