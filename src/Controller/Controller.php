<?php

namespace App\Controller;

use Carbon\Carbon;
use JetBrains\PhpStorm\NoReturn;

abstract class Controller
{
    #[NoReturn] protected function mergeWithData(
        array $data,
        string $method,
    ): array
    {
        $class = new \ReflectionClass(get_called_class());

        return array_merge($data, [
            'currentTime' => Carbon::now()->toDateTimeString(),
            'currentController' => $class->name,
            'currentMethod' => $method,
        ]);
    }
}