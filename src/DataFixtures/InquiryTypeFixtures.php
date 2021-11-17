<?php

namespace App\DataFixtures;

use App\Entity\InquiryType;
use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Inserts default inquiry types in the database.
 */
class InquiryTypeFixtures extends Fixture
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
}
