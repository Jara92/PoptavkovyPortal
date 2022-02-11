<?php

namespace App\DataFixtures;

use App\Factory\Inquiry\InquiryCategoryFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Inserts default inquiry categories in the database.
 */
class InquiryCategoryFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @required
     */
    public InquiryCategoryFactory $inquiryCategoryFactory;

    /**
     * Groups in which this Fixture belongs.
     * @return string[]
     */
    public static function getGroups(): array
    {
        // Fixtures in static group is not updated often.
        return ['data'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadAutoMoto($manager);
    }

    protected function loadAutoMoto(ObjectManager $manager):void{
        $autoMoto = $this->inquiryCategoryFactory->createBaseCategory("Auto-moto");
        $manager->persist($autoMoto);

        $categories = [
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Autobazary"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Autobazary | nákladní vozy"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Autobazary | osobní vozy")
            ,
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Automobily"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Automobily | servis"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Automobily | služby"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Automobily | doplňky a příslušensví"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Automobily | náhradní díly"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Automobily | tuning"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Automobily | leasing"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Automobily | pneu"),

            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Automobily | prodej"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Automobily | prodej | osobní vozy"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Automobily | prodej | užitkové vozy"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Automobily | prodej | nákladní vozy"),

            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Automobily | půjčovna"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Automobily | půjčovna | osobní vozy"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Automobily | půjčovna | užitkové vozy"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Automobily | půjčovna | nákladní vozy"),

            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Autoškoly"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Servis"),

            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Motocykly"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Motocykly | servis"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Motocykly | služby"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Motocykly | doplňky a příslušensví"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Motocykly | náhradní díly"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Motocykly | tuning"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Motocykly | leasing"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Motocykly | pneu"),

            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Přívěsy a návěsy"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Přívěsy a návěsy | prodej"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Přívěsy a návěsy | bazar"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Přívěsy a návěsy | půjčovna"),
        ];

        foreach ($categories as $category) {
            $manager->persist($category);
        }

        $manager->flush();
    }
}
