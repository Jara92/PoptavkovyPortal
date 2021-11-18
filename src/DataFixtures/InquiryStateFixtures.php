<?php

namespace App\DataFixtures;

use App\Entity\InquiryState;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Inserts default inquiry states in the database.
 */
class InquiryStateFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $types = [
            InquiryState::create("inquiry_state.new", "new", "inquiry_state.new_desc", 1),
            InquiryState::create("inquiry_state.active", "active", "inquiry_state.active_desc", 2),
            InquiryState::create("inquiry_state.processing", "processing", "inquiry_state.processing_desc", 3),
            InquiryState::create("inquiry_state.archived", "archived", "inquiry_state.archived_desc", 4),
            InquiryState::create("inquiry_state.deleted", "deleted", "inquiry_state.deleted_desc", 5),
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
