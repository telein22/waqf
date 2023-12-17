<?php

use System\Core\Model;
use System\Helpers\URL;
$lang = Model::get('\Application\Models\Language');
?>
<div class="container">
    <div class="row mt-5 justify-content-center align-items-center">
        <div class="col-md-8 text-center mt-5 pt-5">
            <img src="<?= URL::asset('\Application\Assets\images\link.png') ?>" width="300px" class="mb-4" alt="">
            <h3><?= $lang('page_not_available_404') ?></h3>
            <p><?= $lang('error_text_404') ?></p>
            <a href="<?= URL::full('dashboard') ?>" class="btn btn-primary"><?= $lang('back_to_home') ?></a>
        </div>
    </div>
</div>