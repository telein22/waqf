<?php

use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

$formValidator = FormValidator::instance("edit_social");

?>
<div class="tab-pane active" id="chang-pwd" role="tabpanel">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title"><?= $lang('social_links'); ?></h4>
            </div>
        </div>
        <div class="iq-card-body">
            <form method="POST" action="<?= URL::full('profile/edit/social') ?>">
                <div class="form-group">
                    <label for="snapchat"><?= $lang('snapchat') ?></label>
                    <input type="text" class="form-control" id="snapchat" name="snapchat" value="<?= htmlentities($formValidator->getValue('snapchat', $snapchat)); ?>">
                    <?php if ($formValidator->hasError('snapchat')) : ?>
                        <p class="text-danger"><?= $formValidator->getError('snapchat'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="linkedin"><?= $lang('linkedin') ?></label>
                    <input type="text" class="form-control" id="linkedin" name="linkedin" value="<?= htmlentities($formValidator->getValue('linkedin', $linkedIn)); ?>">
                    <?php if ($formValidator->hasError('linkedin')) : ?>
                        <p class="text-danger"><?= $formValidator->getError('linkedin'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="insta"><?= $lang('insta') ?></label>
                    <input type="text" class="form-control" id="insta" name="insta" value="<?= htmlentities($formValidator->getValue('insta', $insta)); ?>">
                    <?php if ($formValidator->hasError('insta')) : ?>
                        <p class="text-danger"><?= $formValidator->getError('insta'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="youtube"><?= $lang('youtube') ?></label>
                    <input type="text" class="form-control" id="youtube" name="youtube" value="<?= htmlentities($formValidator->getValue('youtube', $youtube)); ?>">
                    <?php if ($formValidator->hasError('youtube')) : ?>
                        <p class="text-danger"><?= $formValidator->getError('youtube'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="facebook"><?= $lang('facebook') ?></label>
                    <input type="text" class="form-control" id="facebook" name="facebook" value="<?= htmlentities($formValidator->getValue('facebook', $facebook)); ?>">
                    <?php if ($formValidator->hasError('facebook')) : ?>
                        <p class="text-danger"><?= $formValidator->getError('facebook'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="telegram"><?= $lang('telegram') ?>(<?= $lang('username') ?>)</label>
                    <input type="text" class="form-control" id="telegram" name="telegram" value="<?= htmlentities($formValidator->getValue('telegram', $telegram)); ?>">
                    <?php if ($formValidator->hasError('telegram')) : ?>
                        <p class="text-danger"><?= $formValidator->getError('telegram'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="twitter"><?= $lang('twitter') ?></label>
                    <input type="text" class="form-control" id="twitter" name="twitter" value="<?= htmlentities($formValidator->getValue('twitter', $twitter)); ?>">
                    <?php if ($formValidator->hasError('twitter')) : ?>
                        <p class="text-danger"><?= $formValidator->getError('twitter'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="website"><?= $lang('website') ?></label>
                    <input type="text" class="form-control" id="website" name="website" value="<?= htmlentities($formValidator->getValue('website', $website)); ?>">
                    <?php if ($formValidator->hasError('website')) : ?>
                        <p class="text-danger"><?= $formValidator->getError('website'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="<?php if ($lang->current() == 'ar') echo 'text-left'; else echo 'text-right';?>">
                    <button type="submit" class="btn btn-primary mr-2"><?= $lang('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>