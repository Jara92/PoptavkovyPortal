<?php

namespace App\Factory\Inquiry;

use App\Entity\Inquiry\Region;

class RegionFactory
{
    /**
     * Create a new region.
     * @param string $title Region title.
     * @param int $ordering Region order.
     * @return Region Region entity.
     */
    public function createRegion(string $title, int $ordering): Region
    {
        $region = new Region();
        $region->setTitle($title)->setOrdering($ordering);

        return $region;
    }
}