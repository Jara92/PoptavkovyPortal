<?php

namespace App\DataFixtures;

use App\Factory\Inquiry\InquiryValueFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Inserts default inquiry values in the database.
 */
class InquiryValueFixtures extends Fixture implements FixtureGroupInterface
{
    protected InquiryValueFactory $inquiryValueFactory;

    public function __construct(InquiryValueFactory $inquiryValueFactory){
        $this->inquiryValueFactory = $inquiryValueFactory;
    }

    public function load(ObjectManager $manager): void
    {
        $types = [
            $this->inquiryValueFactory->createInquiryValue("Dohodou", 0, 1),
            $this->inquiryValueFactory->createInquiryValue("Nabídněte", 0, 2),
            $this->inquiryValueFactory->createInquiryValue("Dle nabídek", 0, 3),
            $this->inquiryValueFactory->createInquiryValue("Do 5tisíc", 5000, 4),
            $this->inquiryValueFactory->createInquiryValue("Do 50tisíc", 50000, 5),
            $this->inquiryValueFactory->createInquiryValue("Do 200tisíc", 200000, 6),
            $this->inquiryValueFactory->createInquiryValue("Do milionu", 1000000, 7),
            $this->inquiryValueFactory->createInquiryValue("Nad milion", 1500000, 8)
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
