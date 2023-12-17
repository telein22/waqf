<?php

use System\Core\Model;

$lang = Model::get('\Application\Models\Language');
?>


<option value="0"><?= $lang('select_an_advisor') ?></option>
<?php foreach ($advisors as $advisor) : ?>
    <option value="<?= $advisor['id'] ?>"> <?= $advisor['name'] ?> </option>
<?php endforeach; ?>

