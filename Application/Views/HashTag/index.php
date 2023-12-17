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
                        <li class="font-weight-bold d-flex align-items-center heading"><?= $lang('hashtags'); ?></li>
                        <?php if (!empty($hashtags)) : ?>
                            <?php foreach ($hashtags as $hash) : ?>
                                <li class="d-flex align-items-center ">
                                    <a href="<?= URL::full('search?q=' . rawurlencode($hash['tag'])); ?>"><?= $hash['tag'] ?>
                                        (<?= $hash['count'] ?>)</a>
                                </li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li class="d-flex align-items-center text-center"><?= $lang('no_hashtag_found') ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>