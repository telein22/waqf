<?php

use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;

$lang = Model::get('\Application\Models\Language');

$formValidator = FormValidator::instance("filter");
// asdasd
?>
<div class="iq-card">
    <div class="iq-card-body">
        <form method="GET" action="<?= URL::current() ?>">

            <div class="form-group">
                <label><?= $lang('specialties'); ?></label>
                <select class="form-control select2 special-select" multiple name="spec[]" required>
                    <?php foreach ($specs as $s) : ?>
                        <option <?= in_array($s['id'], $spec) ? 'selected' : ''; ?> value="<?= $s['id'] ?>"><?= $s['specialty_' . $lang->current()] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label><?= $lang('sub_specialties'); ?></label>
                <select class="form-control select2 sub-special-select" name="subSpec[]" multiple required>
                    <?php foreach ($subSpecs as $s) : ?>
                        <option <?= in_array($s['id'], $subSpec) ? 'selected' : ''; ?> value="<?= $s['id'] ?>"><?= $s['specialty_' . $lang->current()] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="text-ar-right">
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary"><?= $lang('submit') ?></button>
                    <a href="<?= URL::full('search?q=' . htmlentities($q)); ?>" class="btn btn-info"><?= $lang('reset'); ?></a>
                </div>
            </div>

            <input type="hidden" value="<?= htmlentities($q); ?>" name="q" />
        </form>
    </div>
</div>
<define footer_js>
    <script>
        $('.special-select').on('change', function(e) {
            e.preventDefault();
            var value = $(this).val();
            var $subSpecialty = $('.sub-special-select');

            console.log(value);

            if (value === null) return $subSpecialty.html('');

            $.ajax({
                url: URLS.specialty_get_sub_specialties,
                type: 'POST',
                dataType: 'JSON',
                accepts: 'JSON',
                data: {
                    specialtyId: value
                },
                beforeSend: function() {},
                success: function(data) {
                    if (data.payload.length >= 1) {
                        var i = 0;
                        $subSpecialty.html('');
                        for (; i < data.payload.length; i++) {
                            $subSpecialty.append('<option value="' + data.payload[i].id + '">' + toText(data.payload[i].specialty_<?= $lang->current(); ?>) + '</option>');
                        }
                        $subSpecialty[0].disabled = false;
                    }
                }
            });
        });
    </script>
</define>