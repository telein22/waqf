<?php

namespace Application\Models;

use System\Core\Model;

class Settings extends Model
{
    const KEY_VAT = 'vat';
    const KEY_PLATFORM_FEES = 'platform_fees';

    private $_table = 'settings';

    public function take( $key, $default = null )
    {
        $SQL = "SELECT `value` FROM `{$this->_table}`
            WHERE `key` = ?";

        $result = $this->_db->query($SQL, [$key])->get();

        return $result ? $result['value'] : $default;
    }

    public function put( $key, $value )
    {
        return $this->_db->insert($this->_table, [
            'key' => $key,
            'value' => $value,
            'updated_at' => time()
        ], true);
    }
}
