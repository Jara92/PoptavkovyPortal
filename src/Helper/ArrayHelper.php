<?php

namespace App\Helper;

use Closure;

class ArrayHelper
{
    /**
     * Map function which returns an associative array [fnKey => fnValue]
     * @param array $array
     * @param Closure $fnKey
     * @param Closure $fnValue
     * @return array
     */
    public static function asociativeArrayMap(array $array, Closure $fnKey, Closure $fnValue): array
    {
        $keys = array_map($fnKey, $array);
        $values = array_map($fnValue, $array);

        return array_combine($keys, $values);
    }
}