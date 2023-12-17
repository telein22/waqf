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
                <label><?= $lang('users'); ?></label>
                <select class="form-control user-select" multiple name="users[]" required>
                    <?php foreach ( $users as $user ): ?>
                        <option value="<?= $user['id'] ?>" selected><?= htmlentities($user['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary"><?= $lang('submit') ?></button>
                <a href="<?= URL::full('search?q=' . htmlentities($q)); ?>" class="btn btn-info"><?= $lang('reset'); ?></a>
            </div>

            <input type="hidden" value="<?= htmlentities($q); ?>" name="q" />
        </form>
    </div>
</div>
<define footer_js>
    <script>
        $('.user-select').select2({
            ajax: {
                url: URLS.user_search,
                type: 'POST',
                data: function( param ) {                    
                    return param;
                },
                processResults: function( data ) {

                    var final = { results: [] };
                    for( var i = 0; i < data.payload.length; i++ )
                    {
                        final.results.push({id: data.payload[i].id, text: data.payload[i].name });
                    }
                    
                    return final;
                }
            }
        });
    </script>
</define>