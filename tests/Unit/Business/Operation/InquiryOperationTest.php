<?php

namespace App\Tests\Unit\Business\Operation;

use App\Business\Operation\InquiryOperation;
use App\Business\Operation\SubscriptionOperation;
use App\Business\Service\Inquiry\DeadlineService;
use App\Business\Service\Inquiry\InquiryAttachmentService;
use App\Business\Service\Inquiry\InquiryService;
use App\Business\Service\Inquiry\InquirySignedRequestService;
use App\Business\Service\Inquiry\InquiryValueService;
use App\Business\Service\Inquiry\OfferService;
use App\Business\Service\Inquiry\Rating\SupplierRatingService;
use App\Business\Service\Inquiry\SmartTagService;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\InquirySignedRequest;
use App\Entity\Inquiry\Rating\InquiringRating;
use App\Entity\Inquiry\Rating\SupplierRating;
use App\Entity\User;
use App\Enum\Entity\InquiryState;
use App\Enum\Entity\UserType;
use App\Factory\Inquiry\CompanyContactFactory;
use App\Factory\Inquiry\InquiryAttachmentFactory;
use App\Factory\Inquiry\InquiryFactory;
use App\Factory\Inquiry\OfferFactory;
use App\Factory\Inquiry\PersonalContactFactory;
use App\Factory\InquiryFilterFactory;
use App\Security\UserSecurity;
use CoopTilleuls\UrlSignerBundle\UrlSigner\UrlSignerInterface;
use DateTime;
use ReflectionMethod;
use SlopeIt\ClockMock\ClockMock;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @covers InquiryOperation
 */
class InquiryOperationTest extends \PHPUnit\Framework\TestCase
{
    private InquiryOperation $operation;

    private InquiryService $inquiryService;
    private InquiryAttachmentService $attachmentService;
    private InquiryValueService $inquiryValueService;
    private DeadlineService $deadlineService;
    private SubscriptionOperation $subscriptionOperation;
    private InquiryFactory $inquiryFactory;
    private InquiryAttachmentFactory $attachmentFactory;
    private InquiryFilterFactory $filterFactory;
    private OfferService $offerService;
    private OfferFactory $offerFactory;
    private SmartTagService $smartTagService;
    private InquirySignedRequestService $inquirySignedRequestService;
    private PersonalContactFactory $personalContactFactory;
    private CompanyContactFactory $companyContactFactory;
    private UserSecurity $security;
    private SluggerInterface $slugger;
    private ContainerBagInterface $params;
    private TranslatorInterface $translator;
    private MailerInterface $mailer;
    private RouterInterface $router;
    private UrlSignerInterface $urlSigner;
    private SupplierRatingService $supplierRatingService;

    private int $inquiryExpirationNotification = 20000;
    private int $inquiryExpirationRemove = 100;
    private int $inquiryRatingLinkExpiration = 50;

    public function setUp(): void
    {
        parent::setUp();

        // Mock all necessary services.
        $this->inquiryService = $this->createStub(InquiryService::class);
        $this->attachmentService = $this->createStub(InquiryAttachmentService::class);
        $this->inquiryValueService = $this->createStub(InquiryValueService::class);
        $this->deadlineService = $this->createStub(DeadlineService::class);
        $this->subscriptionOperation = $this->createStub(SubscriptionOperation::class);
        $this->inquiryFactory = $this->createStub(InquiryFactory::class);
        $this->attachmentFactory = $this->createStub(InquiryAttachmentFactory::class);
        $this->filterFactory = $this->createStub(InquiryFilterFactory::class);
        $this->offerService = $this->createStub(OfferService::class);
        $this->offerFactory = $this->createStub(OfferFactory::class);
        $this->smartTagService = $this->createStub(SmartTagService::class);
        $this->inquirySignedRequestService = $this->createStub(InquirySignedRequestService::class);
        $this->personalContactFactory = $this->createStub(PersonalContactFactory::class);
        $this->companyContactFactory = $this->createStub(CompanyContactFactory::class);
        $this->supplierRatingService = $this->createStub(SupplierRatingService::class);
        $this->security = $this->createStub(UserSecurity::class);
        $this->slugger = $this->createStub(SluggerInterface::class);
        $this->router = $this->createStub(RouterInterface::class);
        $this->urlSigner = $this->createStub(UrlSignerInterface::class);
        $this->mailer = $this->createStub(MailerInterface::class);
        $this->params = $this->createStub(ContainerBagInterface::class);
        $this->translator = $this->createStub(TranslatorInterface::class);

        // Mock application params
        $this->params->method("get")->will($this->returnCallback(function ($key) {
            return match ($key) {
                "app.email" => "email@test.cz",
                "app.name" => "AppName",
                "app.inquiries.auto_remove_notification_delay" => $this->inquiryExpirationNotification,
                "app.inquiries.auto_remove_delay" => $this->inquiryExpirationRemove,
                "app.inquiries.rating_link_expiration" => $this->inquiryRatingLinkExpiration,
                default => "",
            };
        }));

        $this->router->method("generate")->willReturn("http://orul.cz/");
        $this->urlSigner->method("sign")->willReturn("http://orul.cz/?expired=12345&signature=123456");

        // Create testing class instance.
        $this->operation = new InquiryOperation($this->inquiryService, $this->attachmentService, $this->inquiryValueService, $this->supplierRatingService,
            $this->deadlineService, $this->subscriptionOperation, $this->inquiryFactory, $this->attachmentFactory, $this->filterFactory,
            $this->offerService, $this->offerFactory, $this->smartTagService, $this->inquirySignedRequestService,
            $this->personalContactFactory, $this->companyContactFactory, $this->security, $this->slugger, $this->params, $this->translator,
            $this->mailer, $this->router, $this->urlSigner);
    }

    private function getInquiry1()
    {
        $now = new DateTime();

        $timeNotice = (new DateTime("@" . $this->inquiryExpirationNotification));
        $timeRemove = (new DateTime("@" . $this->inquiryExpirationNotification + $this->inquiryExpirationRemove));

        $user = (new User())->setId(1)->setEmail("user@email.cz");

        $inquiry = (new Inquiry())->setId(1)->setTitle("P1")->setAuthor($user)
            ->setCreatedAt($now)->setUpdatedAt($now)
            ->setState(InquiryState::STATE_ACTIVE)
            ->setRemoveNoticeAt($timeNotice)->setRemoveAt($timeRemove);
        return $inquiry;
    }

    private function getInquiry2()
    {
        $now = new DateTime();

        $timeNotice = (new DateTime("@" . $this->inquiryExpirationNotification));
        $timeRemove = (new DateTime("@" . $this->inquiryExpirationNotification + $this->inquiryExpirationRemove));

        $user = (new User())->setId(1)->setEmail("user@email.cz");

        $inquiry = (new Inquiry())->setId(2)->setTitle("P2")->setAuthor($user)
            ->setCreatedAt($now)->setUpdatedAt($now)
            ->setState(InquiryState::STATE_ACTIVE)
            ->setRemoveNoticeAt($timeNotice)->setRemoveAt($timeRemove);
        return $inquiry;
    }

    private function getInquiry3()
    {
        $now = new DateTime();

        $user = (new User())->setId(1)->setEmail("user@email.cz");

        $inquiry = (new Inquiry())->setId(3)->setTitle("P3")->setAuthor($user)
            ->setCreatedAt($now)->setUpdatedAt($now)
            ->setState(InquiryState::STATE_ACTIVE)
            ->setRemoveNoticeAt(null)->setRemoveAt($now);
        return $inquiry;
    }

    private function getSupplier1()
    {
        return (new User())->setId(1)->setEmail("user@email.cz");
    }

    /**
     * A rating with supplier field filled.
     * @return Inquiry|InquiringRating|User
     */
    private function getRating1()
    {
        $supplier = $this->getSupplier1();
        $now = new DateTime();

        return (new InquiringRating())->setRating(1)->setSupplierNote("Note")->setNote("Note")
            ->setCreatedAt($now)->setSupplier($supplier);
    }

    /**
     * A rating with supplier field = null
     * @return Inquiry|InquiringRating|User
     */
    private function getRating2()
    {
        $now = new DateTime();

        return (new InquiringRating())->setRating(1)->setSupplierNote("Note")->setNote("Note")
            ->setCreatedAt($now)->setSupplier(null);
    }


    /**
     * @covers InquiryOperation::autoRemoveNotify()
     * @throws \Exception
     */
    public function testAutoRemoveNotify()
    {
        // Mock current time.
        $timeStampNow = $this->inquiryExpirationNotification;
        $now = (new DateTime("@" . $timeStampNow));
        ClockMock::freeze($now);

        $inquiry = $this->getInquiry1();
        $requestExpiration = new DateTime("@" . $timeStampNow + $this->inquiryExpirationRemove);

        $inquiryRequest = (new InquirySignedRequest())->setInquiry($inquiry)
            ->setExpireAt($requestExpiration)->setCreatedAt($now);

        // Testing private method
        $method = new ReflectionMethod($this->operation, 'autoRemoveNotify');

        // We have to check parameters given to the "create" method
        $this->inquirySignedRequestService->expects($this->exactly(2))->method("create")
            ->will($this->returnCallback(function (InquirySignedRequest $r) use ($inquiryRequest) {
                $this->assertEquals($inquiryRequest->getExpireAt(), $r->getExpireAt());

                return true;
            }));

        // Invoke the method
        $method->invokeArgs($this->operation, [$inquiry]);
    }

    /**
     * @covers InquiryOperation::updateAutoRemoveData()
     * @throws \ReflectionException
     */
    public function testUpdateAutoRemoveData()
    {
        // Mock current time.
        $timeStampNow = $this->inquiryExpirationNotification;
        $now = (new DateTime("@" . $timeStampNow));
        ClockMock::freeze($now);

        $inquiry = $this->getInquiry1();

        // Testing private method
        $method = new ReflectionMethod($this->operation, 'updateAutoRemoveData');

        // Invoke the method
        $method->invokeArgs($this->operation, [$inquiry]);

        // New removeNoticeAt value should be current time + delay between sending a notification.
        $removeNoticeAt = new DateTime("@" . $timeStampNow + $this->inquiryExpirationNotification);
        $this->assertEquals($removeNoticeAt, $inquiry->getRemoveNoticeAt());

        // New removeAt value should be $removeNoticeValue + delay between notice and removing the inquiry.
        $removeAt = new DateTime("@" . $timeStampNow + $this->inquiryExpirationNotification + $this->inquiryExpirationRemove);
        $this->assertEquals($removeAt, $inquiry->getRemoveAt());
    }

    /**
     * @covers InquiryOperation::handleOldInquiries()
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Exception
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function testHandleOldInquiries()
    {
        // Mock current time.
        $timeStampNow = $this->inquiryExpirationNotification + $this->inquiryExpirationRemove;
        $now = (new DateTime("@" . $timeStampNow));
        ClockMock::freeze($now);

        $inquiry = $this->getInquiry1();
        $inquiry2 = $this->getInquiry2();

        $inquiry3 = $this->getInquiry3();

        $this->inquiryService->method("readActiveToBeNotified")->willReturn([$inquiry, $inquiry2]);
        $this->inquiryService->method("readActiveToBeRemoved")->willReturn([$inquiry3]);

        list($removed, $noticed) = $this->operation->handleOldInquiries();

        // Test if returns values match input.
        $this->assertEquals(1, $removed);
        $this->assertEquals(2, $noticed);
    }

    /**
     * @covers InquiryOperation::finishInquiry()
     * @covers InquiryOperation::finishInquiryByRequest()
     */
    public function testFinishInquiry()
    {
        $now = new \DateTime();

        // Build reference obj
        $inquiry1Ref = $this->getInquiry1()->setInquiringRating($this->getRating1());
        $inquiry = $this->getInquiry1();

        // Build obj to be tested
        $inquiry->setInquiringRating($this->getRating1());

        $inquiryRequest = (new InquirySignedRequest())->setInquiry($inquiry)
            ->setExpireAt($inquiry->getRemoveAt())->setCreatedAt($now);

        // We have to check parameters given to the "update" method
        $this->inquiryService->expects($this->exactly(1))->method("update")
            ->will($this->returnCallback(function (Inquiry $inquiry) use ($inquiry1Ref) {
                // Test that supplier rating field are not changed or removed.
                $this->assertEquals(
                    $inquiry1Ref->getInquiringRating()->getRating(),
                    $inquiry->getInquiringRating()->getRating());
                $this->assertEquals(
                    $inquiry1Ref->getInquiringRating()->getSupplierNote(),
                    $inquiry->getInquiringRating()->getSupplierNote());

                return true;
            }));

        // We expect inquiryRequest to be deleted
        $this->inquirySignedRequestService->expects($this->exactly(1))->method("delete")->with($inquiryRequest);

        // Call tested method
        $this->operation->finishInquiryByRequest($inquiryRequest);

        // The inquiry should be finished now
        $this->assertEquals(InquiryState::STATE_FINISHED, $inquiry->getState());
    }

    /**
     * @covers InquiryOperation::finishInquiry()
     * @covers InquiryOperation::finishInquiryByRequest()
     */
    public function testFinishInquiryNoSupplier()
    {
        $now = new \DateTime();

        // Build an object to be tested
        $inquiry = $this->getInquiry1()->setInquiringRating($this->getRating2());

        $inquiryRequest = (new InquirySignedRequest())->setInquiry($inquiry)
            ->setExpireAt($inquiry->getRemoveAt())->setCreatedAt($now);

        // We have to check parameters given to the "update" method
        $this->inquiryService->expects($this->exactly(1))->method("update")
            ->will($this->returnCallback(function (Inquiry $inquiry) {
                // Test that supplier rating field are null
                $this->assertNull($inquiry->getInquiringRating()->getRating());
                $this->assertNull($inquiry->getInquiringRating()->getSupplierNote());

                return true;
            }));

        // We expect inquiryRequest to be deleted
        $this->inquirySignedRequestService->expects($this->exactly(1))->method("delete")->with($inquiryRequest);

        // Call tested method
        $this->operation->finishInquiryByRequest($inquiryRequest);

        // The inquiry should be finished now
        $this->assertEquals(InquiryState::STATE_FINISHED, $inquiry->getState());
    }

    /**
     * @covers InquiryOperation::saveSupplierRating
     */
    public function testSaveSupplierRatingSendEmail()
    {
        $now = new \DateTime();

        // Build an inquiry without an inquiring rating.
        $inquiry = $this->getInquiry1()->setContactEmail("inquiring@email.cz");

        $author = (new User())->setEmail("user@email.cz")->setId(1)->setType(UserType::COMPANY);

        // Create a supplier rating for this inquiry with realized=true
        $rating = (new SupplierRating())->setRating(1)->setRealizedInquiry(true)
            ->setInquiry($inquiry)->setAuthor($author);

        $inquiryRequest = (new InquirySignedRequest())->setInquiry($inquiry)
            ->setExpireAt($inquiry->getRemoveAt())->setCreatedAt($now);

        // We expect the rating to be created
        $this->supplierRatingService->expects($this->once())->method("create")->with($rating);

        // Exactly one email should be sent
        $this->mailer->expects($this->once())->method("send");

        // Request should be deleted
        $this->inquirySignedRequestService->expects($this->once())->method("delete")->with($inquiryRequest);

        // Call tested method
        $this->operation->saveSupplierRating($inquiryRequest, $rating);
    }

    /**
     * @covers InquiryOperation::saveSupplierRating
     */
    public function testSaveSupplierRatingInquiringAlreadyRated()
    {
        $now = new \DateTime();

        // Build an inquiry
        $inquiry = $this->getInquiry1()->setContactEmail("inquiring@email.cz");

        // Create inquiry rating for the inquiry
        $inquiringRating = (new InquiringRating())->setId(1);
        $inquiry->setInquiringRating($inquiringRating);

        $author = (new User())->setEmail("user@email.cz")->setId(1)->setType(UserType::COMPANY);

        // Create a supplier rating for this inquiry with realized=true
        $supplierRating = (new SupplierRating())->setRating(1)->setRealizedInquiry(true)
            ->setInquiry($inquiry)->setAuthor($author);

        $inquiryRequest = (new InquirySignedRequest())->setInquiry($inquiry)
            ->setExpireAt($inquiry->getRemoveAt())->setCreatedAt($now);

        // The rating should be created
        $this->supplierRatingService->expects($this->once())->method("create")->with($supplierRating);

        // No email should be sent because inquiry.inquiringRating is already set.
        $this->mailer->expects($this->never())->method("send");

        // Request should be removed
        $this->inquirySignedRequestService->expects($this->once())->method("delete")->with($inquiryRequest);

        // Invoke tested method
        $this->operation->saveSupplierRating($inquiryRequest, $supplierRating);
    }

    /**
     * @covers InquiryOperation::saveSupplierRating
     */
    public function testSaveSupplierRatingNotRealized()
    {
        $now = new \DateTime();

        // Build an inquiry without an inquiring rating.
        $inquiry = $this->getInquiry1()->setContactEmail("inquiring@email.cz");

        $author = (new User())->setEmail("user@email.cz")->setId(1)->setType(UserType::COMPANY);

        // Create a supplier rating for this inquiry with realized=false
        $supplierRating = (new SupplierRating())->setRating(1)->setRealizedInquiry(false)
            ->setInquiry($inquiry)->setAuthor($author);

        $inquiryRequest = (new InquirySignedRequest())->setInquiry($inquiry)
            ->setExpireAt($inquiry->getRemoveAt())->setCreatedAt($now);

        // The rating should be created
        $this->supplierRatingService->expects($this->once())->method("create")->with($supplierRating);

        // No email should be sent because the rating.realizedInquiry is false
        $this->mailer->expects($this->never())->method("send");

        // Request should be removed
        $this->inquirySignedRequestService->expects($this->once())->method("delete")->with($inquiryRequest);

        // Invoke tested method
        $this->operation->saveSupplierRating($inquiryRequest, $supplierRating);
    }

    /**
     * Testcase for a user who is able to rate the inquiry but the inquiring have not rated the inquiry yet.
     * @covers InquiryOperation::createSupplierRating
     */
    public function testCreateSupplierRatingAuthorized()
    {
        $supplier = (new User())->setId(1)->setEmail("supplier@sezna.cz")->setType(UserType::COMPANY);

        // Build an inquiry with an inquring rating.
        $inquiringRating = (new InquiringRating())->setId(1)->setSupplier(null);

        $inquiry = $this->getInquiry1()->setContactEmail("inquiring@email.cz")
            ->setInquiringRating($inquiringRating);

        // Created rating should contain $supplier as an author
        $ref = (new SupplierRating())->setAuthor($supplier)->setInquiry($inquiry);

        // Is the user is not authorized we should get an exception.
        $this->security->method("getUser")->willReturn($supplier);
        $this->security->method("isLoggedIn")->willReturn(true);

        // The result should be the same
        $this->assertEquals($ref, $this->operation->createSupplierRating($inquiry));
    }

    /**
     * Testcase for a user who is not authenticated.
     * @covers InquiryOperation::createSupplierRating
     */
    public function testCreateSupplierRatingNotAuthorized()
    {
        // Build an inquiry with an inquring rating.
        $inquiringRating = (new InquiringRating())->setId(1)->setSupplier(null);

        $inquiry = $this->getInquiry1()->setContactEmail("inquiring@email.cz")
            ->setInquiringRating($inquiringRating);

        // Is the user is not authorized we should get an exception.
        $this->security->method("getUser")->willReturn(null);
        $this->security->method("isLoggedIn")->willReturn(false);

        // Exception for an unauthenticated user
        $this->expectException(UnauthorizedHttpException::class);

        $this->operation->createSupplierRating($inquiry);
    }
}