<?php

use System\Helpers\Strings;

?>
<p class="card-text text-secondary">
    <?= htmlentities(Strings::limit( $notification['preparedData']['text'], 100)) ?>
</p>