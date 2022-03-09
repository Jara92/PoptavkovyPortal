<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TextPreviewExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('text_preview', [$this, "textPreview"])
        ];
    }

    /**
     * Returns preview of the given text.
     * If the $text length is greater than $characters, first $characters letters are returned with "..." as a suffix.
     * @param string $text
     * @param int $characters Maximal number of characters to be returned. (dots not included)
     * @return string
     */
    public function textPreview(string $text, int $characters = 100): string
    {
        if (strlen($text) > $characters) {
            return substr($text, 0, $characters) . "...";
        }

        return $text;
    }
}