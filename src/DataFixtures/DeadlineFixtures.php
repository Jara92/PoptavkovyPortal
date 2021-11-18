<?php

namespace App\DataFixtures;

use App\Entity\Deadline;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Inserts default deadlines in the database.
 */
class DeadlineFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $deadlines = [
            Deadline::create("deadline.now", "now"),
            Deadline::create("deadline.as_soon_as_possible", "as_soon_as_possible"),
            Deadline::create("deadline.agreement", "agreement"),
        ];

        foreach ($deadlines as $deadline) {
            $manager->persist($deadline);
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
