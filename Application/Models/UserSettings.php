<?php

namespace Application\Models;

use System\Core\Model;

class UserSettings extends Model
{

    const KEY_COVER = 'cover';
    const KEY_AVATAR = 'avatar';
    const KEY_GENDER = 'gender';
    const KEY_PHONE = 'phone';
    const KEY_DOB = 'dob';
    const KEY_COUNTRY = 'country';
    const KEY_CITY = 'city';
    const KEY_BIO = 'bio';
    const KEY_ACHIEVEMENTS = 'achievements';
    const KEY_BANK1 = 'bank1';
    const KEY_BANK2 = 'bank2';
    const KEY_BANK3 = 'bank3';
    const KEY_SOCIAL_SNAPCHAT = 'snapchat';
    const KEY_SOCIAL_LINKEDIN = 'linkedin';
    const KEY_SOCIAL_INSTAGRAM = 'insta';
    const KEY_SOCIAL_YOUTUBE = 'youtube';
    const KEY_SOCIAL_FACEBOOK = 'facebook';
    const KEY_SOCIAL_TELEGRAM = 'telegram';
    const KEY_SOCIAL_TWITTER = 'twitter';
    const KEY_SOCIAL_WEBSITE = 'website';
    const KEY_LANGUAGE = 'language';
    const KEY_SKIP_INSTRUCTION = 'skip_instruction';
    const KEY_MESSAGING_PRICE = 'messaging_price';
    const KEY_MESSAGING_ENABLE = 'messaging_enable';
    const KEY_BILLING_ADDRESS = 'billing_address';
    const KEY_FREELANCE_DOCUMENT = 'freelance_document';

    private $_table = 'user_settings';

    public function __construct( $options = null )
    {
        parent::__construct($options);
    }

    public function getTable()
    {
        return $this->_table;
    }

    public function put( $userId, $key, $value )
    {
        return $this->_db->insert($this->_table, [
            'key' => $key,
            'value' => $value,
            'user_id' => $userId,
            'updated_at' => time()
        ], true);
    }

    public function take( $userId, $key, $default = null )
    {
        $SQL = "SELECT `value` FROM `{$this->_table}`
            WHERE `user_id` = ? AND `key` = ?";

        $result = $this->_db->query($SQL, [$userId, $key])->get();

        return $result ? $result['value'] : $default;
    }

    public function delete( $userId, $key )
    {
        $SQL = "DELETE FROM `{$this->_table}`
        WHERE `user_id` = ? AND `key` = ?";

        $result = $this->_db->query($SQL, [$userId, $key]);

        return (bool) $result->rowCount();
    }
}