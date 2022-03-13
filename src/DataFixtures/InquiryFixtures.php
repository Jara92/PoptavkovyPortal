<?php

namespace App\DataFixtures;

use App\Entity\AEntity;
use App\Entity\Inquiry\CompanyContact;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\PersonalContact;
use App\Enum\Entity\InquiryState;
use App\Enum\Entity\InquiryType;
use App\Factory\Inquiry\InquiryValueFactory;
use App\Helper\UrlHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Inserts default inquiry values in the database.
 */
class InquiryFixtures extends Fixture implements FixtureGroupInterface
{
    private ObjectManager $manager;

    public function __construct()
    {
    }

    private function buildPersonalInquiry(): Inquiry
    {
        return (new Inquiry())
            ->setType(InquiryType::PERSONAL)->setState(InquiryState::STATE_NEW)
            ->setContactEmail("email@autora.cz")
            ->setContactPhone("+420 123 456 789")
            ->setPersonalContact((new PersonalContact())->setName("Pavel")->setSurname("Novák"));
    }

    private function builCompanyInquiry(): Inquiry
    {
        return (new Inquiry())
            ->setType(InquiryType::COMPANY)->setState(InquiryState::STATE_NEW)
            ->setContactEmail("email@autora.cz")
            ->setContactPhone("+420 123 456 789")
            ->setCompanyContact((new CompanyContact())->setCompanyName("Firma s.r.o")
                ->setIdentificationNumber("123456789"));
    }


    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $inquiries = [
            $this->buildPersonalInquiry()->setTitle("Koupím traktor")->setAlias("koupim-traktor")
                ->setDescription("Lorem ipsum dolor sit amet, consectetuer adipiscing elit.\n Vestibulum fermentum tortor id mi. Donec vitae arcu. Vestibulum fermentum tortor id mi. Nulla pulvinar eleifend sem. \nClass aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos."),

            $this->buildPersonalInquiry()->setTitle("Poptávám tvorbu webové aplikace")->setAlias("tvorba-webove-apliakce")
                ->setDescription("Lorem ipsum dolor sit amet, consectetuer adipiscing elit.\n Vestibulum fermentum tortor id mi. Donec vitae arcu. Vestibulum fermentum tortor id mi. Nulla pulvinar eleifend sem. \nClass aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos."),

            $this->buildPersonalInquiry()->setTitle("Stavba rodiného domu")->setAlias("stavba-rodineho-domu")
                ->setDescription("Lorem ipsum dolor sit amet, consectetuer adipiscing elit.\n Vestibulum fermentum tortor id mi. Donec vitae arcu. Vestibulum fermentum tortor id mi. Nulla pulvinar eleifend sem. \nClass aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos."),

            $this->builCompanyInquiry()->setTitle("Tvorba informačního systému")->setAlias("tvorba-inf-systemu")
                ->setDescription("Lorem ipsum dolor sit amet, consectetuer adipiscing elit.\n Vestibulum fermentum tortor id mi. Donec vitae arcu. Vestibulum fermentum tortor id mi. Nulla pulvinar eleifend sem. \nClass aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos.")
        ];

        foreach ($inquiries as $inquiry) {
            $this->save($inquiry);
        }


    }

    private function save(Inquiry $inquiry)
    {
        $this->manager->persist($inquiry);
        $this->manager->flush();
    }

    /**
     * Groups in which this Fixture belongs.
     * @return string[]
     */
    public static function getGroups(): array
    {
        // Fixtures in static group is not updated often.
        return ['data'];
    }
}
