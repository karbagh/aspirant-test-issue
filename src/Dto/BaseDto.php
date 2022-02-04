<?php

namespace App\Dto;

abstract class BaseDto
{
    abstract function toArray(): array;
}