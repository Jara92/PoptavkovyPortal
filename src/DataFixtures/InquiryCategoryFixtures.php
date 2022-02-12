<?php

namespace App\DataFixtures;

use App\Entity\Inquiry\InquiryCategory;
use App\Factory\Inquiry\InquiryCategoryFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Inserts default inquiry categories in the database.
 */
class InquiryCategoryFixtures extends Fixture implements FixtureGroupInterface
{
    /** @required */
    public InquiryCategoryFactory $inquiryCategoryFactory;

    protected ObjectManager $manager;

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
        $this->manager = $manager;

        $this->loadAutoMoto();
        $this->loadTransportation();
        $this->loadElectro();
        $this->loadTelecomunication();
        //$this->loadOthers();
        $this->loadConsulting();
        $this->loadRealities();
        $this->loadGlass();
        $this->loadBuildingMaterial();
    }

    protected function loadAutoMoto(): void
    {
        $autoMoto = $this->inquiryCategoryFactory->createBaseCategory("Auto-moto");
        $this->manager->persist($autoMoto);

        $categories = [
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Autobazary"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Autobazary | nákladní vozy"),
            $this->inquiryCategoryFactory->createSubCategory($autoMoto, "Autobazary | osobní vozy"),

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
            $this->manager->persist($category);
        }

        $this->manager->flush();
    }

    protected function loadTransportation(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Doprava");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dopravní služby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dopravní služby | nákladní přeprava"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dopravní služby | odtahová služba"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dopravní služby | spedice"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dopravní služby | stěhovací služba"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Autobusová doprava"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Autobusová doprava | mezinárodní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Autobusová doprava | pravidelné linky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Autobusová doprava | vnitrostátní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Autobusová doprava | zakázková doprava"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Automobilová nákladní doprava | do 3,5t"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Automobilová nákladní doprava | logistika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Automobilová nákladní doprava | mezistátní přeprava"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Automobilová nákladní doprava | vnitrostátní přeprava"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Automobilová osobní doprava"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Letecká nákladní doprava"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Letecká osobní doprava"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Lodní nákladní doprava"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Lodní osobní doprava"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Železniční nákladní doprava"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Železniční osobní doprava"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Dopravní inspektorát"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    protected function loadElectro(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Elektronika");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Bezpečnost | docházkové systémy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Bezpečnost | kamerové systémy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Bezpečnost | poplašné systémy"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektrárny | instalace a servis"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektroinstalace | prodej"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektroinstalace | servis"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektroinstalace | jiné služby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektroinstalace | výroba"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektronika | elektronické součástky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektronika | baterie"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektronika | prodej"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektronika | servis"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Energetika | alternativní zdroje"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Export & import"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kabely a izolovaná vedení"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Mobilní telefony"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Osvětlení a svítidla | venkovní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Osvětlení a svítidla | vnitřní"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Průmyslové televize a obrazovky"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    protected function loadTelecomunication(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Telekomunikace");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Export & import"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kabelové televize"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Radiostanice a radiotelefonní komunikace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Poradenství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zavádění systémů"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    protected function loadOthers(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Ostatní");
        $this->manager->persist($parent);

        $items = [];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    protected function loadConsulting(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Poradenství");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Akcie a cenné papíry"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Daňové poradenství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ekologie"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Energetika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Finanční poradenství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Účetní poradenství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Investiční poradenství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Obchodní poradenství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Obchodní zastoupení"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Obchodní společnosti"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Informační systémy a databáze"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Informatika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Jazykové kurzy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Manažerské poradenství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Personální poradenství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Podnikatelské poradenství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Právní poradenství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Technické poradenství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zahraniční obchod"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zavádění jakosti"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zdraví"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zemědělské poradenství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Soudy a státní zastupitelství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stavební poradenství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Školení a kurzy | stavebnictví"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Školení a kurzy | výpočetní technika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Školení a kurzy | zákony a předpisy"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    protected function loadRealities(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Reality");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Garáže a parkoviště"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Garáže a parkoviště | pronájem"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Garáže a parkoviště | prodej"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Sklady | pronájem"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Sklady | prodej"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Byty | pronájem"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Byty | prodej"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Pozemky | pronájem"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pozemky | prodej"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Rodinné domy | pronájem"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Rodinné domy | prodej"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Kanceláře | pronájem"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kanceláře | prodej"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Nebytové prostory | pronájem"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Nebytové prostory | prodej"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní | prodej"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní | pronájem"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    protected function loadGlass(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Sklo");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Láhve"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Sklárny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Skleněné výrobky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dekorace a umění"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ploché sklo"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Užitkové a průmyslové sklo"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Skleníky"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    protected function loadTemplate(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("title");
        $this->manager->persist($parent);

        $items = [];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    protected function loadBuildingMaterial(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Stavební materiál");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Armatury"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Bezpečnost | dveře, okna a mříže"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Žaluzie a stínící technika"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Dveře a vrata | dřevěné | prodej"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dveře a vrata | plastové | prodej"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dveře a vrata | ostatní | prodej"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Dveře a vrata | dřevěné | opravy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dveře a vrata | plastové | opravy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dveře a vrata | ostatní | opravy"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Dveře a vrata | dřevěné | výroba"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dveře a vrata | plastové | výroba"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dveře a vrata | ostatní | výroba"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Beton, cihly a stavební materiály"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Trubky a potrubí"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Garáže a parkoviště | vybavení"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Instalatérský materiál"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Izolace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kamenictví"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Keramika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Koupelnová studia"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kuchyně"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Okna dřevěná | prodej"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Okna dřevěná | výroba"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Okna plastová | prodej"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Okna plastová | výroba"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Okna ostatní | prodej"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Okna ostatní | výroba"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Ploty"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Sádrokartonové konstrukce"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Sanitární zařízení a technika"),

            $this->inquiryCategoryFactory->createSubCategory($parent, "Spojovací materiál a technika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Materiály (písky, štěrky, drtě...)"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stavebniny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Těsnění"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }
}
