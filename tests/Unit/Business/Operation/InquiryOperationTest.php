<?php

namespace App\Tests\Unit\Business\Operation;

use App\Business\Operation\InquiryOperation;
use App\Business\Operation\SubscriptionOperation;
use App\Business\Service\DeadlineService;
use App\Business\Service\InquiryAttachmentService;
use App\Business\Service\InquiryService;
use App\Business\Service\InquirySignedRequestService;
use App\Business\Service\InquiryValueService;
use App\Business\Service\OfferService;
use App\Business\Service\SmartTagService;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\InquirySignedRequest;
use App\Entity\User;
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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

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

    private int $inquiryExpirationNotification = 20000;
    private int $inquiryExpirationRemove = 100;

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
                default => "",
            };
        }));

        $this->router->method("generate")->willReturn("http://orul.cz/");
        $this->urlSigner->method("sign")->willReturn("http://orul.cz/?expired=12345&signature=123456");

        // Create testing class instance.
        $this->operation = new InquiryOperation($this->inquiryService, $this->attachmentService, $this->inquiryValueService,
            $this->deadlineService, $this->subscriptionOperation, $this->inquiryFactory, $this->attachmentFactory, $this->filterFactory,
            $this->offerService, $this->offerFactory, $this->smartTagService, $this->inquirySignedRequestService, $this->personalContactFactory,
            $this->companyContactFactory, $this->security, $this->slugger, $this->params, $this->translator, $this->mailer, $this->router,
            $this->urlSigner);
    }

    /**
     * @throws \Exception
     */
    public function testAutoRemoveNotify()
    {
        // Mock current time.
        $timeStampNow = $this->inquiryExpirationNotification;
        $now = (new DateTime("@" . $timeStampNow));
        ClockMock::freeze($now);

        $timeNotice = (new DateTime("@" . $this->inquiryExpirationNotification));
        $timeRemove = (new DateTime("@" . $this->inquiryExpirationNotification + $this->inquiryExpirationRemove));

        $user = (new User())->setId(1)->setEmail("user@email.cz");

        $inquiry = (new Inquiry())->setId(1)->setTitle("P1")->setAuthor($user)
            ->setCreatedAt($now)->setUpdatedAt($now)
            ->setRemoveNoticeAt($timeNotice)->setRemoveAt($timeRemove);

        $inquiryRequest = (new InquirySignedRequest())->setInquiry($inquiry)
            ->setExpireAt($timeRemove)->setCreatedAt($now);

        // Testing private method
        $method = new ReflectionMethod($this->operation, 'autoRemoveNotify');

        $this->inquirySignedRequestService->expects($this->exactly(2))->method("create")
            ->will($this->returnCallback(function (InquirySignedRequest $r) use ($inquiryRequest) {
                $this->assertEquals($inquiryRequest->getExpireAt(), $r->getExpireAt());
                $this->assertEquals($inquiryRequest->getCreatedAt(), $r->getCreatedAt());

                return true;
            }));

        // Invoke the method
        $method->invokeArgs($this->operation, [$inquiry]);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Exception
     */
    public function testHandleOldInquiries()
    {
        // Mock current time.
        $now = (new DateTime("@" . 10));
        ClockMock::freeze($now);

        $timeNotice = (new DateTime("@" . 15));
        $timeRemove = (new DateTime("@" . 20));

        $inquiry = (new Inquiry())->setId(1)->setTitle("P1")
            ->setCreatedAt($now)->setUpdatedAt($now)
            ->setRemoveNoticeAt($timeNotice)->setRemoveAt($timeRemove);

        $inquiryRequest = (new InquirySignedRequest())->setInquiry($inquiry)
            ->setExpireAt(null)->setCreatedAt(null);

        $inquriesToBeNoticed = [$inquiry];


        // Testing private method
        $method = new ReflectionMethod($this->operation, 'handleOldInquiries');

        // Should be true because C1 is in inquiry->categories
        $this->assertEquals(true, $method->invoke($this->operation));
    }
}