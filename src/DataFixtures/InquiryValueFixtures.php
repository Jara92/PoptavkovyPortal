<?php

namespace App\DataFixtures;

use App\Entity\Inquiry\InquiryValue;
use App\Factory\Inquiry\InquiryValueFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Inserts default inquiry values in the database.
 */
class InquiryValueFixtures extends Fixture implements FixtureGroupInterface
{
    protected $inquiryValueFactory;

    public function __construct(InquiryValueFactory $inquiryValueFactory){
        $this->inquiryValueFactory = $inquiryValueFactory;
    }

    public function load(ObjectManager $manager): void
    {
        $types = [
            $this->inquiryValueFactory->createInquiryValue("inquiry_value.agreement", 0),
            $this->inquiryValueFactory->createInquiryValue("inquiry_value.make_offer", 0),
            $this->inquiryValueFactory->createInquiryValue("inquiry_value.according_offers", 0),
            $this->inquiryValueFactory->createInquiryValue("inquiry_value.max_5k", 5000),
            $this->inquiryValueFactory->createInquiryValue("inquiry_value.max_50k", 50000),
            $this->inquiryValueFactory->createInquiryValue("inquiry_value.max_200k", 200000),
            $this->inquiryValueFactory->createInquiryValue("inquiry_value.max_1M", 1000000),
            $this->inquiryValueFactory->createInquiryValue("inquiry_value.more", 1500000)
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
