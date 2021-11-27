<?php

namespace App\DataFixtures;

use App\Entity\InquiryValue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Inserts default inquiry values in the database.
 */
class InquiryValueFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $types = [
            InquiryValue::create("inquiry_value.agreement", 0),
            InquiryValue::create("inquiry_value.make_offer", 0),
            InquiryValue::create("inquiry_value.according_offers", 0),
            InquiryValue::create("inquiry_value.max_5k", 5000),
            InquiryValue::create("inquiry_value.max_50k", 50000),
            InquiryValue::create("inquiry_value.max_200k", 200000),
            InquiryValue::create("inquiry_value.max_1M", 1000000),
            InquiryValue::create("inquiry_value.more", 1500000)
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
