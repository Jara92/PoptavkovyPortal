<?php

namespace App\Tests\Unit\Business\Service;

use App\Business\Service\Inquiry\InquiryService;
use App\Entity\Inquiry\Inquiry;
use App\Repository\Interfaces\Inquiry\IInquiryIRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class InquiryServiceTest extends TestCase
{
    /** IRepository mock */
    protected IInquiryIRepository $repository;

    /** ObjectManager mock */
    protected EntityManagerInterface $em;

    protected InquiryService $service;

    protected Inquiry $entity;

    protected array $entities;

    function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createStub(IInquiryIRepository::class);
        $this->em = $this->createStub(EntityManagerInterface::class);

        // Create a service using mocked dependencies.
        $this->service = new \App\Business\Service\Inquiry\InquiryService($this->repository);
        $this->service->setObjectManager($this->em);

        $this->entity = (new Inquiry())->setId(13);
        $this->entities = [
            (new Inquiry())->setId(1)->setTitle("Test"),
            (new Inquiry())->setId(2)->setTitle("Test 2")
        ];
    }

    public function testReadById(): void
    {
        $entity = $this->entity;

        // Id to be used
        $id = $entity->getId();

        // Mock repository find method - should return the entity.
        $this->repository->expects($this->once())->method("find")->with($id)->willReturn($entity);

        $this->assertEquals($entity, $this->service->readById($id));
    }

    public function testReadByIdNotExist(): void
    {
        // Id to be used
        $id = 23;

        // Mock repository find method - should return null because the ID does not exist.
        $this->repository->expects($this->once())->method("find")->with($id)->willReturn(null);

        $this->assertNull($this->service->readById($id));
    }

    public function testReadAll(): void
    {
        $entities = $this->entities;

        // Mock repository find method - should return null because the ID does not exist.
        $this->repository->expects($this->once())->method("findAll")->willReturn($entities);

        $this->assertEquals($entities, $this->service->readAll());
    }

    public function testReadBy(): void
    {
        $entities = [$this->entities[0]];
        $params = ["title" => "Test"];

        // Mock repository find method - should return null because the ID does not exist.
        $this->repository->expects($this->once())->method("findBy")->with($params)->willReturn($entities);

        $this->assertEquals($entities, $this->service->readBy($params));
    }

    public function testExistsById(): void
    {
        $id = $this->entity->getId();
        $this->repository->expects($this->once())->method("find")->with($id)->willReturn($this->entity);

        $this->assertEquals(true, $this->service->existsById($id));
    }

    public function testExistsByIdIdNotExist(): void
    {
        $id = 23;
        $this->repository->expects($this->once())->method("find")->with($id)->willReturn(null);

        $this->assertEquals(false, $this->service->existsById($id));
    }

    public function testCreate(): void
    {
        $this->em->expects($this->once())->method("persist")->with($this->entity);
        $this->em->expects($this->once())->method("flush");

        $this->assertEquals(true, $this->service->create($this->entity));
    }
}
