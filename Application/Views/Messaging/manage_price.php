<?php

use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;

$lang = Model::get('\Application\Models\Language');

$formValidator = FormValidator::instance('manage_price');
?>
<div class="container">
    <div class="row mt-5 mb-2 justify-content-center">
        <div class="col-md-8">
            <a href="<?= URL::full('messaging/a'); ?>" class="text-bold text-secondary"><i class="ri-arrow-left-s-line"></i> <?= $lang('back_to_messaging'); ?></a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title"><?= $lang('manage_message_price'); ?></h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <form method="POST" action="<?= URL::current(); ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="price"><?= $lang('price') ?></label>
                                    <input type="number" class="form-control" name="price" id="price" value="<?= $formValidator->getValue('price', $price) ?>" min="0">
                                    <?php if ($formValidator->hasError('price')) : ?>
                                        <p class="error"><?= $formValidator->getError('price'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">                                                                
                                <input type="checkbox" id="enable" name="enable" value="1" <?= $formValidator->getValue('enable', $enable) == 1 ? 'checked' : ''  ?>>
                                <label for="enable"> <?= $lang('enable_messaging') ?> </label>                                
                                <?php if ($formValidator->hasError('gender')) : ?>
                                    <p class="text-danger"><?= $formValidator->getError('enable'); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-ar-right">
                                <button type="submit" class="btn btn-primary"><?= $lang('submit') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($isSubmitted) : ?>
    <define footer_js>
        <script>
            toast('primary', '<?= $lang('success') ?>', '<?= $lang('price_updated'); ?>');
        </script>
    </define>
<?php endif; ?>