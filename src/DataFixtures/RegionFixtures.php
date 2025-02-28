<?php

namespace App\DataFixtures;

use App\Factory\Inquiry\RegionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Inserts default regions in the database.
 */
class RegionFixtures extends Fixture implements FixtureGroupInterface
{
    protected RegionFactory $regionFactory;

    public function __construct(RegionFactory $regionFactory){
        $this->regionFactory = $regionFactory;
    }

    public function load(ObjectManager $manager): void
    {
        $regions = ["Hlavní město Praha", "Středočeský kraj", "Jihočeský kraj", "Plzeňský kraj", "Karlovarský kraj",
            "Ústecký kraj", "Liberecký kraj", "Královéhradecký kraj", "Pardubický kraj", "Kraj Vysočina",
            "Jihomoravský kraj", "Olomoucký kraj", "Zlínský kraj", "Moravskoslezský kraj"];

        for($i = 0; $i < count($regions); $i++){

            $region = $this->regionFactory->createRegion($regions[$i], $i);

            $manager->persist($region);
        }

        $manager->flush();
    }

    /**
     * Groups in which this Fixture belongs.
     * @return string[]
     */
    public static function getGroups(): array
    {
        // Fixtures in static group is not updated often.
        return ['static'];
    }
}
