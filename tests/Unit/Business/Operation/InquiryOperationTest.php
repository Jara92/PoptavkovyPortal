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
use App\Entity\Company;
use App\Entity\Inquiry\CompanyContact;
use App\Entity\Inquiry\Deadline;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\InquirySignedRequest;
use App\Entity\Inquiry\InquiryValue;
use App\Entity\Inquiry\Offer;
use App\Entity\Inquiry\PersonalContact;
use App\Entity\Inquiry\Rating\InquiringRating;
use App\Entity\Inquiry\Rating\SupplierRating;
use App\Entity\Person;
use App\Entity\User;
use App\Enum\Entity\InquiryState;
use App\Enum\Entity\InquiryType;
use App\Enum\Entity\UserType;
use App\Exception\AlreadyMadeOfferException;
use App\Factory\Inquiry\CompanyContactFactory;
use App\Factory\Inquiry\InquiryAttachmentFactory;
use App\Factory\Inquiry\InquiryFactory;
use App\Factory\Inquiry\OfferFactory;
use App\Factory\Inquiry\PersonalContactFactory;
use App\Factory\InquiryFilterFactory;
use App\Helper\UrlHelper;
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

    private function getNewInquiry1()
    {
        $inquiry = (new Inquiry())->setTitle("Titulek poptávky")
            ->setDescription("Popis poptávky delší než 20 znaků, protože to je minimum")
            ->setContactEmail("user@email.cz");

        return $inquiry;
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
            ->setRemoveNoticeAt($timeNotice)->setRemoveAt($timeRemove)
            ->setContactEmail("user@email.cz");
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
            ->setRemoveNoticeAt($timeNotice)->setRemoveAt($timeRemove)
            ->setContactEmail("user@email.cz");
        return $inquiry;
    }

    private function getInquiry3()
    {
        $now = new DateTime();

        $user = (new User())->setId(1)->setEmail("user@email.cz");

        $inquiry = (new Inquiry())->setId(3)->setTitle("P3")->setAuthor($user)
            ->setCreatedAt($now)->setUpdatedAt($now)
            ->setState(InquiryState::STATE_ACTIVE)
            ->setRemoveNoticeAt(null)->setRemoveAt($now)
            ->setContactEmail("user@email.cz");
        return $inquiry;
    }

    private function getInquiry4()
    {
        $now = new DateTime();

        $user = (new User())->setId(1)->setEmail("user@email.cz");

        $inquiry = (new Inquiry())->setId(4)->setTitle("P3")->setAuthor($user)
            ->setCreatedAt($now)->setUpdatedAt($now)
            ->setState(InquiryState::STATE_NEW)
            ->setPublishedAt(null)
            ->setRemoveNoticeAt(null)->setRemoveAt(null)
            ->setContactEmail("user@email.cz");
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

        return (new InquiringRating())->setRating(1)->setTargetNote("Note")->setNote("Note")
            ->setCreatedAt($now)->setTarget($supplier);
    }

    /**
     * A rating with supplier field = null
     * @return Inquiry|InquiringRating|User
     */
    private function getRating2()
    {
        $now = new DateTime();

        return (new InquiringRating())->setRating(1)->setTargetNote("Note")->setNote("Note")
            ->setCreatedAt($now)->setTarget(null);
    }

    /**
     * @covers InquiryOperation::getNewInquiryDefaultType
     */
    public function testGetNewInquiryDefaultType()
    {
        // No user - personal inquiry is defualt
        $user = null;
        $this->assertEquals(InquiryType::PERSONAL, $this->operation->getNewInquiryDefaultType($user));

        // Personal - personal inquiry is default
        $user = (new User())->setId(1)->setEmail("user@seznam.cz")->setType(UserType::PERSON);
        $this->assertEquals(InquiryType::PERSONAL, $this->operation->getNewInquiryDefaultType($user));

        // Company - company inquiry is default
        $user->setType(UserType::COMPANY);
        $this->assertEquals(InquiryType::COMPANY, $this->operation->getNewInquiryDefaultType($user));
    }

    /**
     * @covers InquiryOperation::createInquiry
     */
    public function testCreatePersonalInquiry()
    {
        $inquiry = $this->getNewInquiry1()
            ->setType(InquiryType::PERSONAL)
            ->setPersonalContact((new PersonalContact())->setName("Pavel")->setSurname("Novak"));

        // Prepare expected output
        $inquiryRef = $this->getNewInquiry1()
            ->setType(InquiryType::PERSONAL)
            ->setPersonalContact((new PersonalContact())->setName("Pavel")->setSurname("Novak"))
            ->setAlias("1-titulek-poptavky")
            ->setId(1)
            ->setAuthor(null)
            ->setState(InquiryState::STATE_NEW);

        // We expect the inquiry to be created by the servic
        $this->inquiryService->expects($this->once())->method("create")->with($inquiry)
            ->will($this->returnCallback(function (Inquiry $i) {
                $i->setId(1);

                return true;
            }));

        // The inquiry should be updated then
        $this->inquiryService->expects($this->once())->method("update")->with($inquiry);

        // The "createInquiry" method must return true
        $this->assertEquals(true, $this->operation->createInquiry($inquiry, []));

        // Actual inquiry must be equal to the reference one.
        $this->assertEquals($inquiryRef, $inquiry);

        // Company contact should be null because the inquiry is personal
        $this->assertNull($inquiry->getCompanyContact());

        // Personal contact cannot be null
        $this->assertNotNull($inquiry->getPersonalContact());
    }

    /**
     * @covers InquiryOperation::createInquiry
     */
    public function testCreateCompanyInquiry()
    {
        $inquiry = $this->getNewInquiry1()
            ->setType(InquiryType::COMPANY)
            ->setCompanyContact((new CompanyContact())->setCompanyName("Firma")->setIdentificationNumber("1123456789"));

        // Prepare expected output
        $inquiryRef = $this->getNewInquiry1()
            ->setType(InquiryType::COMPANY)
            ->setCompanyContact((new CompanyContact())->setCompanyName("Firma")->setIdentificationNumber("1123456789"))
            ->setAlias("1-titulek-poptavky")
            ->setId(1)
            ->setAuthor(null)
            ->setState(InquiryState::STATE_NEW);

        // We expect the inquiry to be created by the servic
        $this->inquiryService->expects($this->once())->method("create")->with($inquiry)
            ->will($this->returnCallback(function (Inquiry $i) {
                $i->setId(1);

                return true;
            }));

        // The inquiry should be updated then
        $this->inquiryService->expects($this->once())->method("update")->with($inquiry);

        // The "createInquiry" method must return true
        $this->assertEquals(true, $this->operation->createInquiry($inquiry, []));

        // Actual inquiry must be equal to the reference one.
        $this->assertEquals($inquiryRef, $inquiry);

        // Personal contact should be null because the inquiry is personal
        $this->assertNull($inquiry->getPersonalContact());

        // Company contact cannot be null
        $this->assertNotNull($inquiry->getCompanyContact());
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
     * @covers InquiryOperation::guessValue
     * @throws \ReflectionException
     */
    public function testGuessValueEmpty()
    {
        $textValue = "";
        $inquiry = $this->getNewInquiry1()
            ->setValueText($textValue);

        // Testing private method
        $method = new ReflectionMethod($this->operation, 'guessValue');

        // Invoke the method
        $method->invokeArgs($this->operation, [$inquiry]);

        $this->assertNull($inquiry->getValue());
        $this->assertNull($inquiry->getValueNumber());
    }

    /**
     * @covers InquiryOperation::guessValue
     * @throws \ReflectionException
     */
    public function testGuessValueEntityFound()
    {
        $textValue = "Dohodou";
        $inquiry = $this->getNewInquiry1()
            ->setValueText($textValue);

        $expectedInquiryValue = (new InquiryValue())->setId(1)->setTitle($textValue)->setValue(0);

        $this->inquiryValueService->method("figureOut")->with($textValue)
            ->willReturn($expectedInquiryValue);

        // Testing private method
        $method = new ReflectionMethod($this->operation, 'guessValue');

        // Invoke the method
        $method->invokeArgs($this->operation, [$inquiry]);

        $this->assertEquals($expectedInquiryValue, $inquiry->getValue());
        $this->assertNull($inquiry->getValueNumber());
    }

    /**
     * @covers InquiryOperation::guessValue
     * @throws \ReflectionException
     */
    public function testGuessValueIntegerFound()
    {
        $textValue = "50000";
        $refValue = 50000;
        $inquiry = $this->getNewInquiry1()
            ->setValueText($textValue);

        $this->inquiryValueService->method("figureOut")->with($textValue)
            ->willReturn(null);

        // Testing private method
        $method = new ReflectionMethod($this->operation, 'guessValue');

        // Invoke the method
        $method->invokeArgs($this->operation, [$inquiry]);

        $this->assertNull($inquiry->getValue());
        $this->assertEquals($refValue, $inquiry->getValueNumber());
    }

    /**
     * @covers InquiryOperation::guessValue
     * @throws \ReflectionException
     */
    public function testGuessValueIntegerWithCurrency()
    {
        // The text contains "Kč" which should be handeled too
        $textValue = "50000 Kč";
        $refValue = 50000;

        $inquiry = $this->getNewInquiry1()
            ->setValueText($textValue);

        $this->inquiryValueService->method("figureOut")->with($textValue)
            ->willReturn(null);

        // Testing private method
        $method = new ReflectionMethod($this->operation, 'guessValue');

        // Invoke the method
        $method->invokeArgs($this->operation, [$inquiry]);

        $this->assertNull($inquiry->getValue());
        $this->assertEquals($refValue, $inquiry->getValueNumber());
    }

    /**
     * @covers InquiryOperation::guessDeadline
     * @throws \ReflectionException
     */
    public function testGuessDeadlineEmpty()
    {
        $textValue = "";
        $inquiry = $this->getNewInquiry1()
            ->setDeadlineText($textValue);

        // Testing private method
        $method = new ReflectionMethod($this->operation, 'guessValue');

        // Invoke the method
        $method->invokeArgs($this->operation, [$inquiry]);

        $this->assertNull($inquiry->getDeadline());
    }

    /**
     * @covers InquiryOperation::guessDeadline
     * @throws \ReflectionException
     */
    public function testGuessDeadlineEntityFound()
    {
        $textDeadline = "Co nejdřív";
        $inquiry = $this->getNewInquiry1()
            ->setDeadlineText($textDeadline);

        $expectedDeadline = (new Deadline())->setId(1)->setTitle($textDeadline);

        $this->deadlineService->method("figureOut")->with($textDeadline)
            ->willReturn($expectedDeadline);

        // Testing private method
        $method = new ReflectionMethod($this->operation, 'guessDeadline');

        // Invoke the method
        $method->invokeArgs($this->operation, [$inquiry]);

        $this->assertEquals($expectedDeadline, $inquiry->getDeadline());
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

    // TODO: test InquiryOperation::saveAttachments somehow

    /**
     * @covers InquiryOperation::createBlankInquiry
     */
    public function testCreateBlankInquiryNoUser()
    {
        $user = null;

        $this->inquiryFactory->method("createBlank")->willReturn(new Inquiry());

        $inquiry = $this->operation->createBlankInquiry($user);

        $this->assertNull($inquiry->getContactEmail());
        $this->assertNull($inquiry->getContactPhone());

        $this->assertNull($inquiry->getPersonalContact());
        $this->assertNull($inquiry->getCompanyContact());
    }

    /**
     * @covers InquiryOperation::createBlankInquiry
     */
    public function testCreateBlankInquiryPerson()
    {
        $user = (new User())->setId(1)->setEmail("user@seznam.cz")->setPhone("123456789")
            ->setType(UserType::PERSON)
            ->setPerson((new Person())->setId(1)->setName("User")->setSurname("Userov"));

        $this->inquiryFactory->method("createBlank")->willReturn(new Inquiry());

        $inquiry = $this->operation->createBlankInquiry($user);

        // COntact information must be filled
        $this->assertEquals($user->getEmail(), $inquiry->getContactEmail());
        $this->assertEquals($user->getPhone(), $inquiry->getContactPhone());

        // Personal contact msut not be null
        $this->assertNotNull($inquiry->getPersonalContact());

        // Check filled name and surname
        $this->assertEquals($user->getPerson()->getName(), $inquiry->getPersonalContact()->getName());
        $this->assertEquals($user->getPerson()->getSurname(), $inquiry->getPersonalContact()->getSurname());

        // COmpany contact must be null
        $this->assertNull($inquiry->getCompanyContact());
    }

    /**
     * @covers InquiryOperation::createBlankInquiry
     */
    public function testCreateBlankInquiryCompany()
    {
        $user = (new User())->setId(1)->setEmail("user@company.cz")->setPhone("123456789")
            ->setType(UserType::COMPANY)
            ->setCompany((new Company())->setId(1)->setName("User")->setIdentificationNumber("123456789"));

        $this->inquiryFactory->method("createBlank")->willReturn(new Inquiry());

        $inquiry = $this->operation->createBlankInquiry($user);

        // Contact information must be filled
        $this->assertEquals($user->getEmail(), $inquiry->getContactEmail());
        $this->assertEquals($user->getPhone(), $inquiry->getContactPhone());

        // Personal contact must not be null
        $this->assertNotNull($inquiry->getCompanyContact());

        // Check filled company name and identification number
        $this->assertEquals($user->getCompany()->getName(), $inquiry->getCompanyContact()->getCompanyName());
        $this->assertEquals($user->getCompany()->getIdentificationNumber(), $inquiry->getCompanyContact()->getIdentificationNumber());

        // Personal contact must be null
        $this->assertNull($inquiry->getPersonalContact());
    }

    /**
     * @covers InquiryOperation::createOffer
     */
    public function testCreateOfferNoUser()
    {
        $inquiry = $this->getInquiry1();
        $user = null;

        $this->security->method("getUser")->willReturn($user);
        $this->security->method("isLoggedIn")->willReturn(false);

        // Unauthenticated user is not able to make offers.
        $this->expectException(UnauthorizedHttpException::class);
        $this->operation->createOffer($inquiry);
    }

    /**
     * @covers InquiryOperation::createOffer
     */
    public function testCreateOfferAlreadyMadeAnOffer()
    {
        $inquiry = $this->getInquiry1();
        $user = (new User())->setId(1)->setEmail("user@company.cz")->setPhone("123456789")
            ->setType(UserType::COMPANY)
            ->setCompany((new Company())->setId(1)->setName("User")->setIdentificationNumber("123456789"));

        // The user already sent his offer
        $offer = (new Offer())->setId(1)->setAuthor($user)->setInquiry($inquiry)->setText("Text anbdiky");
        $this->offerService->method("readOneByInquiryAndAuthor")->with($inquiry, $user)->willReturn($offer);

        // User is authenticated
        $this->security->method("getUser")->willReturn($user);
        $this->security->method("isLoggedIn")->willReturn(true);

        // Check if ref offer is equal to the offer returned by "createOffer"
        $this->assertEquals($offer, $this->operation->createOffer($inquiry));
    }

    /**
     * @covers InquiryOperation::createOffer
     */
    public function testCreateOfferCreated()
    {
        $inquiry = $this->getInquiry1();
        $user = (new User())->setId(1)->setEmail("user@company.cz")->setPhone("123456789")
            ->setType(UserType::COMPANY)
            ->setCompany((new Company())->setId(1)->setName("User")->setIdentificationNumber("123456789"));

        // The user has not made an offer yet
        $this->offerService->method("readOneByInquiryAndAuthor")->with($inquiry, $user)->willReturn(null);

        // User is authenticated
        $this->security->method("getUser")->willReturn($user);
        $this->security->method("isLoggedIn")->willReturn(true);

        // Check if ref offer is equal to the offer returned by "createOffer"
        $newOffer = $this->operation->createOffer($inquiry);
        $this->assertEquals($inquiry, $newOffer->getInquiry());
        $this->assertEquals($user, $newOffer->getAuthor());
    }

    /**
     * @covers InquiryOperation::sendOffer()
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function testSendOfferExists()
    {
        $inquiry = $this->getInquiry1();
        $user = (new User())->setId(1)->setEmail("user@company.cz")->setPhone("123456789")
            ->setType(UserType::COMPANY)
            ->setCompany((new Company())->setId(1)->setName("User")->setIdentificationNumber("123456789"));

        // The user already sent his offer
        $offer = (new Offer())->setId(1)->setAuthor($user)->setInquiry($inquiry)->setText("Text anbdiky");

        // The user already sent his offer
        $this->offerService->method("readOneByInquiryAndAuthor")->with($inquiry, $user)->willReturn(new Offer());

        // We expect the offer not to be saved!
        $this->offerService->expects($this->never())->method("create")->with($offer);

        // No emails expected
        $this->mailer->expects($this->never())->method("send");

        // We expect an exception to be thrown
        $this->expectException(AlreadyMadeOfferException::class);

        $this->operation->sendOffer($offer);
    }

    /**
     * @covers InquiryOperation::sendOffer()
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws AlreadyMadeOfferException
     */
    public function testSendOfferCreated()
    {
        $inquiry = $this->getInquiry1();
        $user = (new User())->setId(1)->setEmail("user@company.cz")->setPhone("123456789")
            ->setType(UserType::COMPANY)
            ->setCompany((new Company())->setId(1)->setName("User")->setIdentificationNumber("123456789"));

        // The user already sent his offer
        $offer = (new Offer())->setId(1)->setAuthor($user)->setInquiry($inquiry)->setText("Text anbdiky");

        // The user already sent his offer
        $this->offerService->method("readOneByInquiryAndAuthor")->with($inquiry, $user)->willReturn(null);

        // We expect the offer not to be saved!
        $this->offerService->expects($this->once())->method("create")->with($offer);

        // One email expected
        $this->mailer->expects($this->once())->method("send");

        $this->operation->sendOffer($offer);
    }

    /**
     * @covers InquiryOperation::sendOffer()
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws AlreadyMadeOfferException
     */
    public function testSendOfferCreatedWithCopy()
    {
        $inquiry = $this->getInquiry1();
        $user = (new User())->setId(1)->setEmail("user@company.cz")->setPhone("123456789")
            ->setType(UserType::COMPANY)
            ->setCompany((new Company())->setId(1)->setName("User")->setIdentificationNumber("123456789"));

        // The user already sent his offer
        $offer = (new Offer())->setId(1)->setAuthor($user)->setInquiry($inquiry)->setText("Text anbdiky");

        // The user already sent his offer
        $this->offerService->method("readOneByInquiryAndAuthor")->with($inquiry, $user)->willReturn(null);

        // We expect the offer not to be saved!
        $this->offerService->expects($this->once())->method("create")->with($offer);

        // One email expected
        $this->mailer->expects($this->exactly(2))->method("send");

        $this->operation->sendOffer($offer, true);
    }

    /**
     * @covers InquiryOperation::updateInquiry
     */
    public function testUpdateInquiryNoPublishEvent()
    {
        $inquiry = $this->getInquiry4();
        $refInquiry = $this->getInquiry4();

        // The inquiry is not published so we do not expect "handleNewInquiry" method to be called.
        $this->subscriptionOperation->expects($this->never())->method("handleNewInquiry")->with($inquiry);

        // We expect every call updates the inquiry.
        $this->inquiryService->expects($this->exactly(5))->method("update")->with($inquiry);

        // Call updateInquiry for each state (not for active) and check that the inquiry is not changed.
        $this->operation->updateInquiry($inquiry->setState(InquiryState::STATE_NEW));
        $this->assertEquals($refInquiry->setState(InquiryState::STATE_NEW), $inquiry);

        $this->operation->updateInquiry($inquiry->setState(InquiryState::STATE_FINISHED));
        $this->assertEquals($refInquiry->setState(InquiryState::STATE_FINISHED), $inquiry);

        $this->operation->updateInquiry($inquiry->setState(InquiryState::STATE_ARCHIVED));
        $this->assertEquals($refInquiry->setState(InquiryState::STATE_ARCHIVED), $inquiry);

        $this->operation->updateInquiry($inquiry->setState(InquiryState::STATE_DELETED));
        $this->assertEquals($refInquiry->setState(InquiryState::STATE_DELETED), $inquiry);

        $this->operation->updateInquiry($inquiry->setState(InquiryState::STATE_PROCESSING));
        $this->assertEquals($refInquiry->setState(InquiryState::STATE_PROCESSING), $inquiry);
    }

    /**
     * @covers InquiryOperation::updateInquiry
     */
    public function testUpdateInquiryActiveButAlreadyPublished()
    {
        $inquiry = $this->getInquiry4()
            ->setState(InquiryState::STATE_ACTIVE)
            // Already published
            ->setPublishedAt(new DateTime());

        $refInquiry = $this->getInquiry4()
            ->setState(InquiryState::STATE_ACTIVE)
            // Already published
            ->setPublishedAt(new DateTime());

        // The inquiry is not published so we do not expect "handleNewInquiry" method to be called.
        $this->subscriptionOperation->expects($this->never())->method("handleNewInquiry")->with($inquiry);

        // We expect update to be called
        $this->inquiryService->expects($this->once())->method("update")->with($inquiry);

        $this->operation->updateInquiry($inquiry);

        // The inquiry must not be changed
        $this->assertEquals($refInquiry, $inquiry);
    }

    /**
     * @covers InquiryOperation::updateInquiry
     */
    public function testUpdateInquiryNewlyPublished()
    {
        // Mock current time.
        $timeStampNow = $this->inquiryExpirationNotification;
        $now = (new DateTime("@" . $timeStampNow));
        ClockMock::freeze($now);

        // Expected values for notice and removing the inquiry.
        $expectedRemoveNoticeAt = (new DateTime("@" . ($timeStampNow + $this->inquiryExpirationNotification)));
        $expectedRemoveAt = (new DateTime("@" . ($timeStampNow + $this->inquiryExpirationNotification + $this->inquiryExpirationRemove)));

        $inquiry = $this->getInquiry4()
            ->setState(InquiryState::STATE_ACTIVE)
            // Not published yet
            ->setPublishedAt(null);

        // The inquiry is published now so we expect the event to be triggered.
        $this->subscriptionOperation->expects($this->once())->method("handleNewInquiry")->with($inquiry);

        // We expect update to be called
        $this->inquiryService->expects($this->once())->method("update")->with($inquiry);

        $this->operation->updateInquiry($inquiry);

        // Check published date and remove datetime values.
        $this->assertEquals($now, $inquiry->getPublishedAt());
        $this->assertEquals($expectedRemoveNoticeAt, $inquiry->getRemoveNoticeAt());
        $this->assertEquals($expectedRemoveAt, $inquiry->getRemoveAt());
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
                    $inquiry1Ref->getInquiringRating()->getTargetNote(),
                    $inquiry->getInquiringRating()->getTargetNote());

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
                $this->assertNull($inquiry->getInquiringRating()->getTargetNote());

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
        $inquiringRating = (new InquiringRating())->setId(1)->setTarget(null);

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
        $inquiringRating = (new InquiringRating())->setId(1)->setTarget(null);

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