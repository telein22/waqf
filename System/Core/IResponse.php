<?php

namespace System\Core;

interface IResponse
{
    public function contentType();

    public function content();
}