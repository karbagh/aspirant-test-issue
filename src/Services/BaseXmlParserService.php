<?php

namespace App\Services;

abstract class BaseXmlParserService
{
    abstract function parse(string $data);
}