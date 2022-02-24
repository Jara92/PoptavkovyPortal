<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FileSizeExtension extends AbstractExtension
{
    const KILOBYTE = 1024;
    const MEGABYTE = 1048576;
    const GIBABYTE = 1073741824;
    const TERABYTE = 1099511627776;

    public function getFilters()
    {
        return [
            new TwigFilter('file_size', [$this, 'fileSize']),
        ];
    }

    /**
     * Returns file size in reasonable unit in format %value% %unit%
     * @param int $bytes File size in bytes
     * @param int $precision File size rounding precision
     * @return string
     */
    public function fileSize(int $bytes, int $precision = 2): string
    {
        if ($bytes < self::KILOBYTE) {
            return $this->formatSize($bytes, "B");
        } else if ($bytes < self::MEGABYTE) {
            return $this->formatSize(round($bytes / self::KILOBYTE, $precision), "KB");
        } else if ($bytes < self::GIBABYTE) {
            return $this->formatSize(round($bytes / self::MEGABYTE, $precision), "MB");
        } else if ($bytes < self::TERABYTE) {
            return $this->formatSize(round($bytes / self::GIBABYTE, $precision), "GB");
        } else {
            return $this->formatSize(round($bytes / self::TERABYTE, $precision), "TB");
        }
    }

    /**
     * Builds formatted file size.
     */
    private function formatSize(float $number, string $unit): string
    {
        return $number . " " . $unit;
    }
}