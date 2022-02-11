<?php

namespace App\DataFixtures;

use App\Factory\Inquiry\DeadlineFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Inserts default deadlines in the database.
 */
class DeadlineFixtures extends Fixture implements FixtureGroupInterface
{
    protected DeadlineFactory $deadlineFactory;

    public function __construct(DeadlineFactory $deadlineFactory){
        $this->deadlineFactory = $deadlineFactory;
    }

    public function load(ObjectManager $manager): void
    {
        $deadlines = [
            $this->deadlineFactory->createDeadline("deadline.agreement", "agreement", 1),
            $this->deadlineFactory->createDeadline("deadline.now", "now", 2),
            $this->deadlineFactory->createDeadline("deadline.as_soon_as_possible", "as_soon_as_possible", 3),
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
