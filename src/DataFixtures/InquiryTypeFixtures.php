<?php

namespace App\DataFixtures;

use App\Entity\InquiryType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Inserts default inquiry types in the database.
 */
class InquiryTypeFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $types = [
            InquiryType::create("inquiry_type.personal", "personal"),
            InquiryType::create("inquiry_type.company", "company")
        ];

        foreach ($types as $type){
            $manager->persist($type);
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
