<?php

namespace Application\Modules;

interface SendibleAsAttachment
{
    public function getFileName(): string;

    public function getFileCaption(): string;

    public function getFullPath(): string;
}
