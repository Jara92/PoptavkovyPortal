<?php

namespace App\Controller;

use App\Entity\Inquiry;
use App\Form\InquiryForm;
use App\Services\InquiryService;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class InquiryController extends AbstractController
{
    protected $inquiryService;
    protected $translator;

    public function __construct(InquiryService $inquiryService, TranslatorInterface $translator)
    {
        $this->inquiryService = $inquiryService;
        $this->translator = $translator;
    }

    /**
     * Show inquiries list.
     * @return Response
     */
    public function index()
    {
        $inquiries = $this->inquiryService->readAll();

        return $this->render("inquiry/index.html.twig", ["inquiries" => $inquiries]);
    }

    /**
     * Show inquiry detail page.
     * @param string $alias
     * @return Response
     */
    public function detail(string $alias)
    {
        return $this->render("inquiry/detail.html.twig");
    }

    /**
     * Show and handle a new inquiry form.
     * @return Response
     */
    public function create(Request $request)
    {
        $inquiry = new Inquiry();

        $form = $this->createForm(InquiryForm::class, $inquiry);

        // Same as $this->render('...', ['form' => $form->createView()])
        return $this->renderForm("inquiry/create.html.twig", ["form" => $form]);
    }

    /**
     * Edit an existing inquiry action.
     * @param Request $request
     * @param string $alias
     * @return Response
     */
    public function edit(Request $request, string $alias)
    {
        return $this->render("inquiry/edit.html.twig");
    }


}