<?php

namespace System\Models;

use System\Core\Model;

class Http extends Model
{
    public static $dependencies = [
        '\GuzzleHttp\Client' => [
            'name' => 'GuzzleHttp',
            'install' => 'composer require guzzlehttp/guzzle',
            'link' => 'https://docs.guzzlephp.org/en/stable/'
        ]
    ];

    /**
     * @var \PHPMailer\PHPMailer\PHPMailer
     */
    private $_mailer;

    public function __construct( $options )
    {
        parent::__construct($options);
    }

    public function post()
    {
        
    }

}