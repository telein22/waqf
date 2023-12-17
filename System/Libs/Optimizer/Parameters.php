<?php

namespace System\Libs\Optimizer;

class Parameters
{
    private $_strParams;

    public function __construct( $params )
    {
        $this->_strParams = $params;
    }

    public function parse()
    {
        return $this->_parse($this->_strParams);
    }

    private function _parse( $params )
    {
        if ( empty($params) ) return [];

        // First explode the params.
        $commands = explode('|', $params);

        $output = [];

        // Get foreach commands get the parameters with values
        foreach ( $commands as $command )
        {
            if ( empty($command) ) continue;            
            preg_match('/^(\w+)(?:\:(.+))?$/i', $command, $matches);

            if ( empty($matches) ) continue;

            $command = $matches[1];
            $params = isset($matches[2]) ? $matches[2] : '';

            $params = array_map([$this, '_filterParams'], explode(',', $params));

            $output[$command] = $params;
        }

        return $output;
    }

    public function _filterParams( $param )
    {
        if ( $param === '' || $param === 'true' ) $param = true;
        elseif ( $param === 'false' ) $param = false;
        elseif ( $param === 'null' ) $param = null;        
        elseif ( is_numeric($param) ) $param = (int) $param;

        return $param;
    }
}