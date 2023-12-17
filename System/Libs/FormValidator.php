<?php

namespace System\Libs;

use System\Core\AbstractInput;
use System\Core\Database;
use System\Core\Exceptions\SystemError;
use System\Helpers\Strings;

class FormValidator extends AbstractInput
{
    private static $_instances = [];

    /**
     * 
     * 
     * @return FormValidator
     */
    public static function instance( $name )
    {
        if ( !isset(self::$_instances[$name]) )
        {
            self::$_instances[$name] = new self($name);
        }

        return self::$_instances[$name];
    }

    private static $_supportedTypes = [
        'string' => '_validateString',
        'number' => '_validateNumber',
        'select' => '_validateSelect',
        'multiselect' => '_validateMultiSelect',
        'file' => '_validateFile',
    ];

    private $_name;

    private $_rules = [];

    private $_errorLangs = [];

    private $_errors = [];

    private $_values = [];

    private function __construct( $name )
    {
        $this->_name = $name;
    }

    public function setRules( array $rules )
    {
        $this->_rules =  $rules;
        return $this;
    }

    public function setErrors( array $errors )
    {
        $this->_errorLangs = $errors;
    }

    public function setError( $fieldName, $error )
    {
        $this->_errors[$fieldName] = $error;
    }

    public function setValue( $fieldName, $value )
    {
        $this->_values[$fieldName] = $value;
    }

    public function hasError( $fieldName )
    {
        return isset($this->_errors[$fieldName]);
    }

    public function getError( $fieldName, $default = null )
    {
        return $this->hasError($fieldName) ? $this->_errors[$fieldName] : $default;
    }

    public function getValue( $fieldName, $default = null )
    {
        return isset($this->_values[$fieldName]) ? $this->_values[$fieldName] : $default;
    }

    public function validate()
    {
        $isValid = true;

        foreach ( $this->_rules as $key => $rule )
        {
            if (
                !isset($rule['type']) ||
                !isset(self::$_supportedTypes[strtolower($rule['type'])])
            ) {
                $keys = array_keys(self::$_supportedTypes);
                $keys = implode(", ", $keys);

                throw new SystemError("Invalid rule type. Following are only supported `{$keys}`");
            }

            $value = $this->_getValue($rule['type'], $key);
            $this->_values[$key] = $value;

            // First validate for required options
            if ( empty($value) && $value !== '0' )
            {
                // If the value is empty but is required
                // Then we throw this error.
                if ( isset($rule['required']) && $rule['required'] === true )
                {
                    $this->_errors[$key] = $this->_getErrorMessage(
                        $key . '.required',
                        "This field is required"
                    );
                    $isValid = false;
                }
                continue;
            }

            // Validate for unique data
            if ( 
                $rule['type'] !== 'file' &&
                isset($rule['unique']) &&
                ! $this->_isUnique($value, $rule['unique'])
            ) {
                $this->_errors[$key] = $this->_getErrorMessage(
                    $key . '.unique',
                    "This field is not unique"
                );
                $isValid = false;
                continue;
            }
            

            $call = array($this, self::$_supportedTypes[strtolower($rule['type'])]);
            if ( true !== $eKey = call_user_func($call, $rule, $value) ) {                                
                $this->_errors[$key] = $this->_getErrorMessage($key . '.' . $eKey);
                $isValid = false;
            }
        }

        return $isValid;
    }

    private function _getErrorMessage( $key, $msg = "This field is invalid" )
    {
        return isset($this->_errorLangs[$key]) ? $this->_errorLangs[$key] : $msg;
    }

    private function _getValue( $type, $key )
    {
        if ( $type != 'file' ) return $this->post($key);
        if ( !empty($_FILES[$key]['name']) ) return $_FILES[$key];

        return null;
    }

    private function _isUnique( $value, $rule )
    {
        $rule = Strings::explode(",", $rule);
        $table = $rule[0];
        $column = $rule[1];
        $notItem = isset($rule[2]) ? $rule[2] : null;
        /**
         * @var \System\Core\Database;
         */
        $db = Database::get();

        $dbParams = [$value];
        $SQL = "SELECT 1 FROM `{$table}` WHERE `{$column}` = ?";
        if ( $notItem )
        {
            $dbParams[] = $notItem;
            $SQL .= " AND `{$column}` <> ? ";
        }
        $result = $db->query($SQL, $dbParams)->get();

        return ! (bool) $result;
    }

    private function _validateString( $rules, $value )
    {
        if ( !empty($rules['pattern']) )
        {
            return preg_match($rules['pattern'], $value) ? true : "pattern";
        }

        // else woth with other propeties
        if (
            isset($rules['minchar']) &&
            mb_strlen($value) < (int) $rules['minchar']
        ) return "minchar";

        if (
            isset($rules['maxchar']) &&
            mb_strlen($value) > (int) $rules['maxchar']
        ) return "maxchar";

        return true;
    }

    private function _validateNumber( $rules, $value )
    {
        if ( !is_numeric($value) ) return 'type';

        $value = (int) $value;

        if ( isset($rules['min']) && $value < (int) $rules['min'] ) return 'min';
        if ( isset($rules['max']) && $value > (int) $rules['max'] ) return 'max';

        return true;
    }

    private function _validateSelect( $rules, $value )
    {
        if ( isset($rules['values']) && is_array($rules['values']) && !in_array($value, $rules['values']) ) return 'values';

        return true;
    }

    private function _validateMultiSelect( $rules, $value )
    {
        if ( isset($rules['values']) && is_array($rules['values']) )
        {
            foreach ( $value as $item )
            {
                if ( !in_array($item, $rules['values']) ) return 'values';
            }
        }

        return true;
    }

    private function _validateFile( $rules, $value )
    {
        if ( $value['error'] !== 0 ) 
        {
            throw new SystemError(
                "File upload is not possible as server do not support the current file.
                Remember to check upload_max_filesize and post_max_size in php config."
            );
        }

        $file = new File();
        $file->set($value);

        if ( isset($rules['allow']) && !in_array($file->getMime(), $rules['allow']) ) return 'allow';
        if ( isset($rules['maxsize']) && $value['size'] > (int) $rules['maxsize'] ) return 'maxsize';
        if ( isset($rules['minsize']) && $value['size'] < (int) $rules['minsize'] ) return 'minsize';

        return true;
    }
}