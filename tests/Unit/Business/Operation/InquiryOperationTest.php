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
use App\Factory\Inquiry\CompanyContactFactory;
use App\Factory\Inquiry\InquiryAttachmentFactory;
use App\Factory\Inquiry\InquiryFactory;
use App\Factory\Inquiry\OfferFactory;
use App\Factory\Inquiry\PersonalContactFactory;
use App\Factory\InquiryFilterFactory;
use App\Security\UserSecurity;
use CoopTilleuls\UrlSignerBundle\UrlSigner\UrlSignerInterface;
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
                "app.inquiries.auto_remove_notification_delay" => 20000,
                "app.inquiries.auto_remove_delay" => 100,
                default => "",
            };
        }));

        // Create testing class instance.
        $this->operation = new InquiryOperation($this->inquiryService, $this->attachmentService, $this->inquiryValueService,
            $this->deadlineService, $this->subscriptionOperation, $this->inquiryFactory, $this->attachmentFactory, $this->filterFactory,
            $this->offerService, $this->offerFactory, $this->smartTagService, $this->inquirySignedRequestService, $this->personalContactFactory,
            $this->companyContactFactory, $this->security, $this->slugger, $this->params, $this->translator, $this->mailer, $this->router,
            $this->urlSigner);
    }

    public function testPostponeExpiration()
    {
        // $inquiry = (new Inquiry())->setId(1)->setTitle("P1")->setRemoveAt((new \DateTime())->setTimestamp(1))
        //$inquiryRequest = (new InquirySignedRequest())->
    }
}