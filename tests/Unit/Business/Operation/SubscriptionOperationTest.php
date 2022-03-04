<?php

namespace App\Tests\Unit\Business\Operation;

use App\Business\Operation\SubscriptionOperation;
use App\Business\Service\InquiryService;
use App\Business\Service\SubscriptionService;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\InquiryCategory;
use App\Entity\Inquiry\Region;
use App\Entity\Inquiry\Subscription;
use App\Entity\User;
use App\Enum\Entity\InquiryType;
use App\Repository\Interfaces\Inquiry\IInquiryIRepository;
use App\Repository\Interfaces\Inquiry\ISubscriptionRepository;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubscriptionOperationTest extends TestCase
{
    private SubscriptionOperation $operation;
    private SubscriptionOperation $operationMock;

    private SubscriptionService $service;
    private MailerInterface $mailer;
    private ContainerBagInterface $params;
    private TranslatorInterface $translator;

    function setUp(): void
    {
        parent::setUp();

        $this->service = $this->createStub(SubscriptionService::class);
        $this->mailer = $this->createStub(MailerInterface::class);
        $this->params = $this->createStub(ContainerBagInterface::class);
        $this->translator = $this->createStub(TranslatorInterface::class);

        $this->operation = new SubscriptionOperation($this->service, $this->mailer, $this->params, $this->translator);

        // Mock application params
        $this->params->method("get")->will($this->returnCallback(function ($key) {
            return match ($key) {
                "app.email" => "email@test.cz",
                "app.name" => "AppName",
                default => "",
            };
        }));

        // $this->operationMock = $this->createPartialMock(SubscriptionOperation::class, ["sendSubscriptionEmail"]);
    }

    function getTestingInquiryWithRegionSet(): Inquiry
    {
        $inquiry = new Inquiry();
        $inquiry
            ->addCategory((new InquiryCategory())->setId(1)->setTitle("C1"))
            ->addCategory((new InquiryCategory())->setId(2)->setTitle("C2"))
            ->addCategory((new InquiryCategory())->setId(3)->setTitle("C3"))
            ->addCategory((new InquiryCategory())->setId(4)->setTitle("C4"))
            ->addCategory((new InquiryCategory())->setId(5)->setTitle("C5"))
            ->addCategory((new InquiryCategory())->setId(6)->setTitle("C6"));

        $inquiry->setType(InquiryType::PERSONAL);
        $inquiry->setRegion((new Region())->setId(1));

        return $inquiry;
    }

    function getTestingInquiryWithRegionUnset(): Inquiry
    {
        $inquiry = new Inquiry();
        $inquiry
            ->addCategory((new InquiryCategory())->setId(1)->setTitle("C1"))
            ->addCategory((new InquiryCategory())->setId(2)->setTitle("C2"))
            ->addCategory((new InquiryCategory())->setId(3)->setTitle("C3"))
            ->addCategory((new InquiryCategory())->setId(4)->setTitle("C4"))
            ->addCategory((new InquiryCategory())->setId(5)->setTitle("C5"))
            ->addCategory((new InquiryCategory())->setId(6)->setTitle("C6"));

        $inquiry->setType(InquiryType::PERSONAL);

        return $inquiry;
    }

    /**
     * Subscription has some categories, regions and types.
     * @throws \ReflectionException
     */
    public function testIsInquiryRelevant()
    {
        $inquiry = $this->getTestingInquiryWithRegionSet();
        $subscription = new Subscription();
        $subscription
            ->addCategory((new InquiryCategory())->setId(1)->setTitle("C1"))
            ->addCategory((new InquiryCategory())->setId(10)->setTitle("C10"));

        // Testing private method
        $method = new ReflectionMethod($this->operation, 'isInquiryRelevant');

        // Should be true because C1 is in inquiry->categories
        $this->assertEquals(true, $method->invokeArgs($this->operation, array($inquiry, $subscription)));

        // Should be true, because region 1 is in inquiry->regions
        $subscription->addRegion((new Region())->setId(1));
        $this->assertEquals(true, $method->invokeArgs($this->operation, array($inquiry, $subscription)));

        // Should be true because the inquiry is personal
        $subscription->addType(InquiryType::PERSONAL);
        $this->assertEquals(true, $method->invokeArgs($this->operation, array($inquiry, $subscription)));
    }

    /**
     * Subscription properties do not match with inquiry data.
     * @throws \ReflectionException
     */
    public function testIsInquiryRelevantNoCommonData()
    {
        $inquiry = $this->getTestingInquiryWithRegionSet();
        $subscription = new Subscription();

        // Testing private method
        $method = new ReflectionMethod($this->operation, 'isInquiryRelevant');

        // No subscribed categories - should be false
        $this->assertEquals(false, $method->invokeArgs($this->operation, array($inquiry, $subscription)));

        // Add one category but not one of the inquiry categories . still false
        $subscription
            ->addCategory((new InquiryCategory())->setId(10)->setTitle("C10"));
        $this->assertEquals(false, $method->invokeArgs($this->operation, array($inquiry, $subscription)));

        // Add one common category - should be true now.
        $subscription
            ->addCategory((new InquiryCategory())->setId(1)->setTitle("C1"));
        $this->assertEquals(true, $method->invokeArgs($this->operation, array($inquiry, $subscription)));

        // Add region but not the same as the inquiry one - should be false now, because inquiry region must be one of my subscribed regions.
        $subscription
            ->addRegion((new Region())->setId(2));
        $this->assertEquals(false, $method->invokeArgs($this->operation, array($inquiry, $subscription)));

        // Add same region as the inquiry has - should be true now.
        $subscription
            ->addRegion($inquiry->getRegion());
        $this->assertEquals(true, $method->invokeArgs($this->operation, array($inquiry, $subscription)));

        // Add inquiry type which is not the same as the inquiry type - should be false.
        $subscription->addType(InquiryType::COMPANY);
        $this->assertEquals(false, $method->invokeArgs($this->operation, array($inquiry, $subscription)));

        // Add inquiry type to my subscription - should be true know.
        $subscription->addType($inquiry->getType());
        $this->assertEquals(true, $method->invokeArgs($this->operation, array($inquiry, $subscription)));
    }

    /**
     * Inquiry has no region set so subscription->regions may be anything.
     * @throws \ReflectionException
     */
    public function testIsInquiryReleventInquiryRegionUnset()
    {
        $inquiry = $this->getTestingInquiryWithRegionSet();
        $subscription = new Subscription();
        $subscription->addCategory((new InquiryCategory())->setId(1)->setTitle("C1"));

        // Testing private method
        $method = new ReflectionMethod($this->operation, 'isInquiryRelevant');

        // Should be true because inquiry region and type is unset and my subscription accepts all regions because no region is set.
        $this->assertEquals(true, $method->invokeArgs($this->operation, array($inquiry, $subscription)));

        // Add random region to my subscription - should be true because the inquiry has no region set.
        $subscription->addRegion((new Region())->setId(1));
        $this->assertEquals(true, $method->invokeArgs($this->operation, array($inquiry, $subscription)));
    }

    /**
     * Handling a new inquiry and appending them to the subscriptions.
     */
    public function testHandleNewInquiry()
    {
        $inquiry = $this->getTestingInquiryWithRegionSet();

        // is relevant for $inquiry
        $subscription1 = (new Subscription())->setId(1);
        $subscription1->addCategory((new InquiryCategory())->setId(1)->setTitle("C1"))
            ->addType($inquiry->getType())->addRegion($inquiry->getRegion());

        // Is not relevant for $inquiry
        $subscription2 = (new Subscription())->setId(2);
        $subscription2->addCategory((new InquiryCategory())->setId(20)->setTitle("C20"))
            ->addType(InquiryType::COMPANY);

        // Mock returned active subscriptions
        $this->service->expects($this->once())->method("findActiveSubscriptions")->willReturn([$subscription1, $subscription2]);

        // Make sure that the subscription1 is updated (and subscription2 is not updated because there is no reason to).
        // TODO: JUnit does not support testing expects multiple times with different parameters using ->with method.
        $this->service->expects($this->exactly(1))->method("update");

        // Call the method
        $this->operation->handleNewInquiry($inquiry);

        // The new inquiry should be the only one in subscription->inquiries.
        $this->assertEquals([$inquiry], $subscription1->getInquiries()->toArray());

        // Second subscription is not interested in this type of inquiries so the inquiry was not added to the array.
        $this->assertEmpty($subscription2->getInquiries()->toArray());
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function testSendNewInquiries()
    {
        $inquiry1 = $this->getTestingInquiryWithRegionSet();
        $inquiry2 = $this->getTestingInquiryWithRegionUnset();

        // Subscription1 has two inquiries to be sent.
        $subscription1 = (new Subscription())->setId(1);
        $subscription1->addCategory((new InquiryCategory())->setId(1)->setTitle("C1"))
            ->setUser((new User())->setId(1)->setEmail("user@seznam.cz"))
            ->addInquiry($inquiry1)->addInquiry($inquiry2);

        // Subscription2 has one inquiry to be sent.
        $subscription2 = (new Subscription())->setId(2);
        $subscription2->addCategory((new InquiryCategory())->setId(20)->setTitle("C20"))
            ->setUser((new User())->setId(2)->setEmail("user2@seznam.cz"))
            ->addInquiry($inquiry1);

        // Subscription3 has no inquiry to be sent.
        $subscription3 = (new Subscription())->setId(3)
            ->setUser((new User())->setId(3)->setEmail("user3@seznam.cz"));

        // Mock returned active subscriptions
        $this->service->expects($this->once())->method("findActiveSubscriptions")->willReturn([$subscription1, $subscription2, $subscription3]);

        // We expect exactly two emails to be sent.
        $this->mailer->expects($this->exactly(2))->method("send");

        // Check that both subscriptions have been updated.
        $this->service->expects($this->exactly(2))->method("update");

        // Call the method and check return value.
        $this->assertEquals(2, $this->operation->sendNewInquiries());

        // Check that subscription inquiries have been cleared.
        $this->assertEmpty($subscription1->getInquiries()->toArray());
        $this->assertEmpty($subscription2->getInquiries()->toArray());
    }
}
