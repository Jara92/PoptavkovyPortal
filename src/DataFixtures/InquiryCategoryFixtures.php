<?php

namespace App\DataFixtures;

use App\Factory\Inquiry\InquiryCategoryFactory;
use App\Repository\Inquiry\InquiryCategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use App\Entity\Inquiry\InquiryCategory;

/**
 * Inserts default inquiry categories in the database.
 */
class InquiryCategoryFixtures extends Fixture implements FixtureGroupInterface
{
    #[Required]
    public InquiryCategoryFactory $inquiryCategoryFactory;

    private ObjectManager $manager;

    #[Required]
    public ParameterBagInterface $params;

    #[Required]
    public InquiryCategoryRepository $repository;

    /**
     * Groups in which this Fixture belongs.
     * @return string[]
     */
    public static function getGroups(): array
    {
        // Fixtures in static group is not updated often.
        return ['categories', 'data'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

       // $this->repository->createQueryBuilder("c")->delete()->getQuery()->getSingleScalarResult();

        $this->loadJsonData();
    }

    private function loadJsonData(): void
    {
        $dataFile = $this->params->get("kernel.project_dir") . "/resources/categories.json";
        $data = file_get_contents($dataFile);

        $categories = json_decode($data, true);

        foreach ($categories as $category) {
            $this->loadJsonCategory($category["title"], $category["subcategories"]);
        }
    }

    private function loadJsonCategory(string $parentName, array $subCategories): void
    {
        // Create parent category
        $parent = $this->inquiryCategoryFactory->createBaseCategory($parentName);
        $this->manager->persist($parent);

        // Create child categories
        foreach ($subCategories as $subCategory) {
            $item = $this->inquiryCategoryFactory->createSubCategory($parent, $subCategory["title"]);
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
