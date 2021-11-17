<?php

namespace App\DataFixtures;

use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Inserts default regions in the database.
 */
class RegionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $regions = ["Hlavní město Praha", "Středočeský kraj", "Jihočeský kraj", "Plzeňský kraj", "Karlovarský kraj",
            "Ústecký kraj", "Liberecký kraj", "Královéhradecký kraj", "Pardubický kraj", "Kraj Vysočina",
            "Jihomoravský kraj", "Olomoucký kraj", "Zlínský kraj", "Moravskoslezský kraj"];

        for($i = 0; $i < count($regions); $i++){
            $region = Region::create($regions[$i], $i);

            $manager->persist($region);
        }

        $manager->flush();
    }
}
