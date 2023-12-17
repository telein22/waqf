<?php

namespace Application\Models;

use System\Core\Model;
use System\Models\Email as SystemEmail;
use System\Models\Mail;

class Email extends SystemEmail
{
    public function __construct( $options = null )
    {
        parent::__construct($options);
    }

    public function new()
    {
        /**
         * @var Language
         */
        $lang = Model::get(Language::class);
        return new CustomMail(clone $this->_mailer, $lang);
    }
}

class CustomMail extends Mail
{
    /**
     * @var Language
     */
    private $_lang;

    public function __construct( $mailer, $lang )
    {
        parent::__construct($mailer);

        $this->_lang = $lang;
    }

    public function subject($sub, $vars = null, $lang = null)
    {
        $lang = ! $lang ? $this->_lang->current() : $lang;        
        parent::subject($this->_lang->take($sub, $vars, $lang));
    }

    public function body( $template, $data = null, $lang = null )
    {
        $lang = ! $lang ? $this->_lang->current() : $lang;        
        parent::body($template . '.' . $lang, $data);
    }
}