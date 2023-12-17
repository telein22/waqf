<?php
if ( !isset($notification['preparedData']['message']) ) return;
use System\Helpers\Strings;

?>
<p class="card-text text-secondary">
    <?= htmlentities(Strings::limit( $notification['preparedData']['message']['message'], 100)) ?>
</p>