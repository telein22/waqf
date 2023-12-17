<?php

use Application\Models\Language;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get(Language::class);
?>

<div class="container">
    <div class="row feed-page-row">
        <div class="col-lg-12 d-sm-none d-md-block">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title"><?= $lang('whats_trending'); ?></h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <ul class="media-story m-0 p-0">
                        <li class="font-weight-bold heading d-flex align-items-center"><?= $lang('category'); ?></li>
                        <?php if (!empty($categories)) : ?>
                            <?php foreach ($categories as $category) : ?>
                                <li class="d-flex align-items-center ">
                                    <a href="<?= URL::full('search?spec[]=' . $category['parent']['id'] . '&subSpec[]=' . $category['id']); ?>"><?= $category['parent']['specialty_' . $lang->current()] ?>/<?= $category['specialty_' . $lang->current()] ?> (<?= $category['count'] ?>)</a>
                                </li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li class="d-flex align-items-center text-center"><?= $lang('no_category_found') ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>