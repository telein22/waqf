<?php

namespace System\Commands;

use System\Core\CLICommand;

class RunController extends CLICommand
{
    public function run( $command = null, $param = null )
    {
        var_dump($command);
    }
}