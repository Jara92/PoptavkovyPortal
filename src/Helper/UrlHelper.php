<?php

namespace App\Helper;

class UrlHelper
{
    public static function createAlias(string $title, string $divider = "-"):string{
        // replace non letter or digits by divider
        $title = preg_replace('~[^\pL\d]+~u', $divider, $title);

        // transliterate
        setlocale(LC_ALL, "en_US.utf8");
        $title = iconv("utf-8", "ascii//TRANSLIT", $title);

        // remove unwanted characters
        $title = preg_replace('~[^-\w]+~', '', $title);

        // trim
        $title = trim($title, $divider);

        // remove duplicate divider
        $title = preg_replace('~-+~', $divider, $title);

        // lowercase
        $title = strtolower($title);

        if (empty($title)) {
            return 'n-a';
        }

        return $title;
    }

    public static function createIdAlias(int $id, string $title, string $divider = "-"): string
    {
        return self::createAlias($id . $divider . $title);
    }
}