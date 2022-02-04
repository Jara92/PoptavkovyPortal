<?php

namespace App\DataFixtures;

use App\Factory\UserTypeFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Inserts default regions in the database.
 */
class UserTypeFixtures extends Fixture implements FixtureGroupInterface
{
    protected UserTypeFactory $userTypeFactory;

    public function __construct(UserTypeFactory $userTypeFactory){
        $this->userTypeFactory = $userTypeFactory;
    }

    public function load(ObjectManager $manager): void
    {
        $types = [
            $this->userTypeFactory->createUserType("user_type.personal", "personal"),
            $this->userTypeFactory->createUserType("user_type.company", "company")
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
