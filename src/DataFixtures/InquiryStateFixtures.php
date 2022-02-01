<?php

namespace App\DataFixtures;

use App\Entity\Inquiry\InquiryState;
use App\Factory\Inquiry\InquiryStateFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Inserts default inquiry states in the database.
 */
class InquiryStateFixtures extends Fixture implements FixtureGroupInterface
{
    protected $inquiryStateFactory;

    public function __construct(InquiryStateFactory $inquiryStateFactory){
        $this->inquiryStateFactory = $inquiryStateFactory;
    }

    public function load(ObjectManager $manager): void
    {
        $types = [
            $this->inquiryStateFactory->createInquiryState("inquiry_state.new", "new", "inquiry_state.new_desc", 1),
            $this->inquiryStateFactory->createInquiryState("inquiry_state.active", "active", "inquiry_state.active_desc", 2),
            $this->inquiryStateFactory->createInquiryState("inquiry_state.processing", "processing", "inquiry_state.processing_desc", 3),
            $this->inquiryStateFactory->createInquiryState("inquiry_state.archived", "archived", "inquiry_state.archived_desc", 4),
            $this->inquiryStateFactory->createInquiryState("inquiry_state.deleted", "deleted", "inquiry_state.deleted_desc", 5),
        ];

        foreach ($types as $type) {
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
