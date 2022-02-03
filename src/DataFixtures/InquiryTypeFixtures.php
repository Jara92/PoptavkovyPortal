<?php

namespace App\DataFixtures;

use App\Entity\Inquiry\InquiryType;
use App\Factory\Inquiry\InquiryTypeFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Inserts default inquiry types in the database.
 */
class InquiryTypeFixtures extends Fixture implements FixtureGroupInterface
{
    protected $inquiryTypeFactory;

    public function __construct(InquiryTypeFactory $inquiryTypeFactory){
        $this->inquiryTypeFactory = $inquiryTypeFactory;
    }

    public function load(ObjectManager $manager): void
    {
        $types = [
            $this->inquiryTypeFactory->createInquiryType("inquiry_type.personal", "personal", 1),
            $this->inquiryTypeFactory->createInquiryType("inquiry_type.company", "company", 2)
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
