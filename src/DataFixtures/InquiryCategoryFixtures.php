<?php

namespace App\DataFixtures;

use App\Factory\Inquiry\InquiryCategoryFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Inserts default inquiry categories in the database.
 */
class InquiryCategoryFixtures extends Fixture implements FixtureGroupInterface
{
    #[Required]
    public InquiryCategoryFactory $inquiryCategoryFactory;

    private ObjectManager $manager;

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
        $this->loadBazar();
        $this->loadDoprava();
        $this->loadDrevo();
        $this->loadElektro();
        $this->loadNabytek();
        $this->loadPocitaceASoftware();
        $this->loadPotraviny();
        $this->loadPrumysl();
        $this->loadReality();
        $this->loadReklama();
        $this->loadRemeslnePrace();
        $this->loadServis();
        $this->loadSluzby();
        $this->loadStavby();
        $this->loadStavebniMaterial();
        $this->loadStavebnictvi();
        $this->loadStroje();
        $this->loadStrojirenstvi();
        $this->loadSubdodavky();
        $this->loadOdevy();
        $this->loadUbytovani();
        $this->loadUklid();
        $this->loadVyrobky();
        $this->loadZemedelstvi();
    }

    private function loadAutoMoto(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Auto-moto");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Autopůjčovny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Autoskla"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Autoškoly"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Bazary"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ekologická likvidace vozů"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Leasingy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Lodě a motorové čluny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Montáž, servis a revize LPG"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Motocykly, čtyřkolky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Mytí aut"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Náhradní díly"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Nákladní auta"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Oleje, maziva a filtry"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Osobní auta"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pneuservis"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Prodej pneumatik"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Příslušenství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Přívěsné vozíky, karavany"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Servis"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Služby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Speciální technologická vozidla"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Tažná zařízení"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Užitková vozidla"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadBazar(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Bazar");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektronika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dřevo a kovoobráběcí stroje"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Nábytek"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Oděvy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stavební materiál"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stavební stroje"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stavební stroje | bagry a nakladače"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stavební stroje | dumpery a sklápečky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stavební technika | válce, vibrační desky a pěchy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stavební technika | nářadí"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zahradní technika, nářadí"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zemědělské stroje"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Lesní stroje (traktory, vyvážečky..)"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadDrevo(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Dřevo");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "EURO palety"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Hotové výrobky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kulatiny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Lamino a překližky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dřevěná okna"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Palivové dřevo"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pelety, peletky a brikety"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Řezivo"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Trámy a prkna"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Štěpka"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zakázková výroba"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zpracovávání"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadNabytek(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Nábytek");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Bytové doplňky a dekorace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dveře – vnitřní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dveře – vnější"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Hotelový, restaurační"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kancelářský"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Strarožitný"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kovový"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kuchyně"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Osvětlení"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Matrace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Montáž a demontáž"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Návrh a realizace interiérů"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Postele"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Sedačky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Skříně"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Služby a restaurování"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stoly a židle"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zakázková výroba"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zahradní nábytek"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadDoprava(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Doprava");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Autobusová"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dopravní značení"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kontejnerová"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kurýrní služby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Letecká"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Lodní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Mikrobusová"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Nákladní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Osobní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Doprava potravin"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stěhovací služby"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadElektro(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Elektro");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektrické spotřebiče"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektronika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Solární panely"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektrotechnika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektronické součástky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Měřáky a elektrotechncké přístroje"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Energetika"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadPocitaceASoftware(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Počítače a IT");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Hardware"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kancelářská technika a spotřební materiál"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Monitory a obrazovky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Nosiče dat"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Notebooky a hotové sestavy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Tvorba webových stránek"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Tvorba informačních systémů"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Tvorba eshopů"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Programátorské práce"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Programátorské práce - desktopové aplikace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Programátorské práce - mobilní aplikace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Programátorské práce - webové aplikace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Programátorské práce - informační systémy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pokladny a pokl. systémy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Servis"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Software"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Správa serveru"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Telekomunikace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Výroba"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadPotraviny(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Potraviny");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Cukrářská výroba"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Cukrovinky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Masné výrobky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Mléčné výrobky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Nápoje - alko"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Nápoje - nealko"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Oleje"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ovoce a zelenina"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pečivo"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Výroba potravin a lahůdek"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zdravá výživa a doplňky"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadPrumysl(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Průmysl");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dodávky zemního plynu"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Chladící a mrazící zařízení"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kontejnery"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ocelové a plynové láhve"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pohonné hmoty a biopaliva"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Uhlí"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadReality(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Reality");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Prodej - byty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Prodej - garáže a objekty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Prodej - chaty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Prodej - kanceláře a komerční objekty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Prodej - ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Prodej - pozemky a zahrady"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Prodej - rodinné domy a vily"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pronájem - byty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pronájem - garáže a objekty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pronájem - chaty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pronájem - kanceláře a komerční objekty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pronájem - ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pronájem - pozemky a zahrady"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pronájem - rodinné domy a vily"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Prostory - pro školení"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Prostory - pro prodej"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadReklama(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Reklama a tisk");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Digitální tisk"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Grafika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Katalogy, časopisy, brožury a knihy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Letáky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Marketing - marketingová komunikace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Marketing - online"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Potisk textilu, plastů a předmětů"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Reklamní agentury"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Reklamní předměty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Světelná reklama"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Vazba a zpracování tiskovin"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Velkoformátový tisk a plakáty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Venkovní reklama"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Vizitky"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadRemeslnePrace(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Řemeslné práce");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Betonáři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Čištění kanalizace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dlaždiči"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektrikáři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Hodinový manžel"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Instalatéři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Izolatéři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kameník, mramorář"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Klempíři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kominíci"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kováři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Lešenáři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Malíři, natěrači a tapetáři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Montáž oken, dveří a vrat"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Montáže klimatizací"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Obkladači"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Plyn, voda, topení"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Plynaři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Podlaháři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pokrývači"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Renovace oken a dveří"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Revize - ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Revize elektro"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Revize komíny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Revize kotlů"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Revize plyn"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Sádrokartonáři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Sklenáři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Studnaři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Svářeči"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ševci, čalouníci"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Štukatéři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Tesaři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Topenáři, kamnaři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Truhláři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Výkopové práce"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zahradníci"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zámečníci a strojní zámečníci"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zedníci"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Žaluzie, rolety a markýzy"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadServis(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Servis");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Auto-moto"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Bílá technika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektrické spotřebiče"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kalibrace a měření"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Nářadí"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stroje - průmyslové"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stroje – textilní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stroje – zahradní technika a náhradní díly"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stroje – zemědělská technika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Výtahy a servis"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadSluzby(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Služby");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Bezpečnost, ochrana zdraví a BOZP"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Bezpečnost"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Bezpečnost - kamerové systémy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Bezpečnost - ostraha"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Čalounictví"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Deratizace, dezinsekce a dezinfekce"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Distribuce letáků"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Film a video"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Finanční služby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Finanční služby - hypotéky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Finanční služby - investoři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Finanční služby - půjčky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Foto, video a kamera"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Jazykové kurzy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kácení stromů"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Korektury a editace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Likvidace odpadů"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Notáři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní kurzy a školení"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "PC kurzy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Poradenství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pořádání akcí a konferencí"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Prádelny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Překladatelské služby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Rauty a catering"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Rekvalifikační kurzy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Rozbory vody"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Správa nemovitostí"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Svatební agentury"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Telemarketing"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Účetní služby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Vymáhání pohledávek"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zahradnictví a zahradnické služby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zdravotnictví a zdravotnické služby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Znalci - odhadci - nemovitý majetek"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Znalci - odhadci - ostatní"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadStavby(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Stavby");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Balkony"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Bazény"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Bytová jádra a koupelny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Centrální vysavače"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Čističky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Čištění fasád"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dětská hřiště"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Domovní zvonky a schránky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Fasády a omítky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Gabiony"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Garáže a dílny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Hromosvody"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kamenný koberec"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kanalizace a odpady"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Komíny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kotle"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Krby, pece"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kuchyně, jídelny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Lité podlahy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Odhlučnění prostor"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Opěrné zdi"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Plechové garáže"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ploty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Podlahy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Přípojky plynu, vody"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Přístavby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Rekuperace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Rozvody, instalace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Sauny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Schody"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Sklepní kóje, sklepy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stavební agentury, technický dozor"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stropy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Střechy, půdy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Studny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Terasy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Topení"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Vchodové stříšky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Vyklízení bytů, sklepů, půd…"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Výstavba sportovišť"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Vzduchotechnika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zábradlí"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zahradní domky, přístřešky a pergoly"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zahrady (okrasné, jezírka atd.)"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zámková dlažba"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zasklívání lodžií a balkónů"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zimní zahrady"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Žumpy a jímky"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadStavebniMaterial(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Stavební materiál");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Asfalt a asfaltové směsi"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Bednění"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Beton"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Cihly a tvárnice"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dlažba a zámková dlažba"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dřevoplast"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dveře - bezpečnostní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dveře - protipožární"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dveře - venkovní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektromateriál"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Fasády"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Gabiony"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Hliníková okna"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Chemie"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Interiérová dlažba a obklady"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Izolace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kamenivo, štěrk a písek"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kamenný koberec"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Klempířské prvky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Komíny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kovový"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Lešení - prodej"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Lešení - půjčovna"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Maltové směsi, štuk, cement atd."),
            $this->inquiryCategoryFactory->createSubCategory($parent, "OSB desky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Palubky a prkna"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Panely a betonové prvky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Plastová okna"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ploty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Podlahové krytiny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pro dřevostavby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Sádrokarton"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Sanitární keramika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Sanitární technika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Sítě do oken, rolety, žaluzie, markýzy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Spojovací materiál"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stavební buňky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stavební chemie"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stropní konstrukce a krytiny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Střešní krytiny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Střešní okna a světlíky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Voda, plyn a topení"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Vrata a brány"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zemina/hlína"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadStavebnictvi(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Stavebnictví");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Architekti"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Demoliční práce a likvidace staveb"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Čerpací stanice"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Drenáže"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dřevostavby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stavby na klíč"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektroinstalační práce"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektroprojekty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Geodetické služby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Geologické práce"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Haly"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Hrubé stavby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Hydroizolace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Interiérové přestavby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Inženýrské sítě"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Inženýrské služby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Izolace a zateplení vnější"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Izolace a zateplení vnitřní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Jeřábnické práce"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Městský mobiliář"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Mobilheimy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Nízkoenergetické domy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Novostavby - komerční stavby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Novostavby - obytné stavby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Novostavby - průmyslové stavby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Odvoz odpadů"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Projektanti - ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Projektanti - pozemní stavby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Projektanti - rekonstrukce"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Rekonstrukce"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Revitalizace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Rodinné domy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Silniční stavby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Statika staveb"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stavební dozor"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Těsnění a těsnicí materiály"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Vodohospodářství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Vysušování zdiva a sanace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Výškové práce"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zahradní architekti"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Základové desky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zemní práce"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadStroje(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Stroje");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Balící"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Čerpadla - ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Čerpadla - tepelná"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Čerpadla - vodní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dopravní systémy a dopravníky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektrocentrály"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Elektromotory"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Jeřáby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Lisy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Manipulační a skladová technika - prodej"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Manipulační a skladová technika - půjčovna"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Manipulační a skladová technika - servis"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Mycí stroje"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Na dřevo"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Na kov"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Náhradní díly - ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Náhradní díly - průmyslové"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Náhradní díly - zemědělská technika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Nářadí a nástroje"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Nástrojárny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Potravinářské"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Prodejní automat"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Půjčovna ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Půjčovna stavebních strojů"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Půjčovna nářadí"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Řídicí a automatizační technika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stavební"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stavební mechanizace"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Textilní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Tiskařské"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Váhy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Vybavení pro autodílny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Výroba strojů"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zahradní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zemědělské"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadStrojirenstvi(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Strojírenství");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Broušení"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "CNC obrábění"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dráty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Frézování"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Galvanotechnika a povrchové úpravy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Hutní materiál (profily, plechy, trubky aj.)"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Hydraulika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kovové konstrukce"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kovovýroba - montáž"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kovovýroba - nerez"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Skladové systémy a regály"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Slévárenství"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Soustružení"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Technické kreslení"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zbraně a střelivo"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadSubdodavky(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Subdodávky");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dlaždiči"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Instalatérské práce"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Malíři, natěrači a tapetáři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Montáž oken, dveří a vrat"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Obkladové práce - vnitřní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Obkladové práce - vnější"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Podlaháři"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pokrývačské a lešenářské práce"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Práce elektro"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Stavební práce"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Voda, plyn a topení"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zedník"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadOdevy(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Textil a oděvy");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Bytový textil"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Čalounický materiál"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dámské oděvy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dětské oděvy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Koberce"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Obuv"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pánské oděvy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pracovní oděvy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pracovní oděvy - montérky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pracovní oděvy - rukavice"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Pracovní oděvy - ochranné pomůcky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Sportovní oděvy - indoor"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Sportovní oděvy - outdoor"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Tkanina"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Velkoobchody"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zakázková výroba"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zakázková výroba - krejčí"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadUbytovani(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Ubytování a cestování");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Česká republika - hotely"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Chorvatsko"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Itálie"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kempy ČR"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Lázeňské pobyty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Letenky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Penziony a chaty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Rakousko"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Slovensko"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ubytovny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zahraničí - evropa"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zahraničí - svět"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadUklid(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Úklid");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Bytové a panelové domy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Bytové prostory"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Čištění koberců"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Drogerie, kosmetika a parfémy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Hygienické a čisticí prostředky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Komerční prostory"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Odklízení sněhu a komunikací"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadVyrobky(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Výrobky");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Dětské zboží"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Guma"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Hudební nástroje"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kamna a krbové vložky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kancelářské potřeby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Kožená galanterie"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Mobilní WC"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ochranné pomůcky"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Orientační infosystémy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Papír"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Plachty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Plasty"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Posypová sůl"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Požární technika a systémy"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Sklo"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Sport"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Šperky, hodinky a bižuterie"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Vybavení pro výuku"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zdravotnictví"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadZemedelstvi(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("Zemědělství");
        $this->manager->persist($parent);

        $items = [
            $this->inquiryCategoryFactory->createSubCategory($parent, "Biovýroba"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Chovatelské potřeby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Krmivo"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Obiloviny"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Ostatní"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Technika"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Služby"),
            $this->inquiryCategoryFactory->createSubCategory($parent, "Zvířata"),
        ];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }

    private function loadTemplate(): void
    {
        $parent = $this->inquiryCategoryFactory->createBaseCategory("title");
        $this->manager->persist($parent);

        $items = [];

        foreach ($items as $item) {
            $this->manager->persist($item);
        }

        $this->manager->flush();
    }
}
