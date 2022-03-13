<?php

namespace App\DataFixtures;

use App\Entity\Person;
use App\Entity\Profile;
use App\Entity\User;
use App\Enum\Entity\UserRole;
use App\Enum\Entity\UserType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Inserts default users to the database.
 */
class UserFixtures extends Fixture implements FixtureGroupInterface
{
    private ObjectManager $manager;

    public function __construct()
    {
    }


    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $users = [
            (new User())->setEmail("jara.fikar@seznam.cz")->setType(UserType::PERSON)
                ->setPassword("$2y$13$/Qci5eSgJF2xuBK5WreGXeZCygaBAVhxwiLqzM0uu17WP15wDHwxK")
                ->setPerson((new Person())->setName("Super")->setSurname("User"))
                ->setProfile((new Profile())->setIsPublic(false))->setIsVerified(true)->setEmailVerifiedAt(new \DateTime())
                ->addRole(UserRole::SUPER_ADMIN)
        ];

        foreach ($users as $ser) {
            $this->save($ser);
        }
    }

    private function save(User $user)
    {
        $this->manager->persist($user);
        $this->manager->flush();
    }

    /**
     * Groups in which this Fixture belongs.
     * @return string[]
     */
    public static function getGroups(): array
    {
        // Fixtures in static group is not updated often.
        return ['users'];
    }
}
