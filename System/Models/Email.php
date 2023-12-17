<?php

namespace System\Models;

use PHPMailer\PHPMailer\PHPMailer;
use System\Core\Exceptions\SystemError;
use System\Core\Model;
use System\Responses\View;

class Email extends Model
{
    public static $dependencies = [
        '\PHPMailer\PHPMailer\PHPMailer' => [
            'name' => 'PHPmailer',
            'install' => 'composer require phpmailer/phpmailer',
            'link' => 'https://github.com/PHPMailer/PHPMailer'
        ]
    ];

    /**
     * @var \PHPMailer\PHPMailer\PHPMailer
     */
    protected $_mailer;

    public function __construct( $options )
    {
        parent::__construct($options);
        $this->_mailer = new \PHPMailer\PHPMailer\PHPMailer(true);

        if ( isset($options['use_smtp']) && $options['use_smtp'] )
        {
            if ( isset($options['debug']) && $options['debug'] )
            {
                $this->_mailer->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
            }

            $this->_mailer->isSMTP();
            $this->_mailer->Host       = $options['smtp_host'];
            $this->_mailer->SMTPAuth   = true;
            $this->_mailer->Username   = $options['smtp_username'];
            $this->_mailer->Password   = $options['smtp_password'];
            $this->_mailer->SMTPSecure = $options['smtp_encryption'];
            $this->_mailer->Port       =  $options['smtp_port'];
            $this->_mailer->CharSet    = PHPMailer::CHARSET_UTF8;
        }

        // set from email
        $this->_mailer->setFrom(
            isset($options['from_email']) ? $options['from_email'] : 'framework@twiplo.com',
            isset($options['from_name']) ? $options['from_name'] : ''
        );
        $this->_mailer->XMailer = "Twiplo Mail Engine 0.0.1 (https://www.twiplo.com)";
    }

    public function new()
    {
        return new Mail( clone $this->_mailer );
    }
}

class Mail
{
     /**
     * @var \PHPMailer\PHPMailer\PHPMailer
     */
    private $_mailer;
 
    public function __construct( $mailer )
    {
        $this->_mailer = $mailer;   
        $this->_mailer->isHTML(true);
    }

    public function to( $to )
    {
        $to = (array) $to;
        $name = isset($to[1]) ? $to[1] : '';

        $this->_mailer->addAddress($to[0], $name);
    }

    public function body( $template, $data = null )
    {
        $view = new View();
        $view->set($template, $data);
        $content = $view->content();
        $this->_mailer->Body = $content;

        return $this;
    }

    public function addAttachments( $files )
    {
        foreach ( $files as $file )
        {
            if ( $path = $file->get() ) $this->_mailer->addAttachment($path);
        }

        return $this;
    }

    public function addAttachment( $path )
    {
        $this->_mailer->addAttachment($path);
        return $this;
    }

    public function subject( $sub )
    {
        $this->_mailer->Subject = $sub;

        return $this;
    }

    public function send()
    {
        try {
            return $this->_mailer->send();
        } catch( \PHPMailer\PHPMailer\Exception $ex )
        {
            throw new SystemError($ex->getMessage(), $ex->getCode());
        }
        
    }
}