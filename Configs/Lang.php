<?php

use System\Core\Config;

$lang = Config::get('Lang');


$lang->set(array(

    'en' => include 'Langs/en.php',
    'ar' => include 'Langs/ar.php',

    // 'hi' => [
    //     ...Hindi language stuff
    // ]
));