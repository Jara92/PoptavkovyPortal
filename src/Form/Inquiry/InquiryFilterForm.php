<?php

namespace App\Form\Inquiry;

use App\Business\Operation\InquiryOperation;
use App\Business\Service\Inquiry\InquiryCategoryService;
use App\Entity\Inquiry\InquiryCategory;
use App\Enum\Entity\InquiryType;
use App\Entity\Inquiry\InquiryValue;
use App\Entity\Inquiry\Region;
use App\Helper\InquiryTypeHelper;
use App\Tools\Filter\InquiryFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class InquiryFilterForm extends AbstractType
{
    /** @required */
    public TranslatorInterface $translator;

    /** @required */
    public InquiryOperation $inquiryOperation;

    /** @required */
    public InquiryCategoryService $inquiryCategoryService;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InquiryFilter::class,
            'translation_domain' => "messages"
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod("GET")
            ->add('text', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    "placeholder" => "inquiries.field_search_text",
                ]
            ])
            ->add("categories", EntityType::class, [
                'required' => false,
                'multiple' => true,
                'label' => "inquiries.field_categories",
                'class' => InquiryCategory::class,
                'choices' => $this->inquiryCategoryService->readAllRootCategories(),
                'choice_label' => 'title',
                // We don't want to translate categories
                'choice_translation_domain' => false,
            ])->add("regions", EntityType::class, [
                'required' => false,
                'multiple' => true,
                'label' => "inquiries.field_region",
                'class' => Region::class,
                'choice_label' => 'title',
                // We don't want to translate regions
                'choice_translation_domain' => false,
            ])->add("values", EntityType::class, [
                'required' => false,
                'multiple' => true,
                'label' => "inquiries.field_value",
                'class' => InquiryValue::class,
                'choice_label' => 'title',
            ])
            ->add("types", ChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'label' => "inquiries.field_type",

                'choices' => InquiryTypeHelper::translationCases(),

                // Use Enums item value in select
                'choice_value' => 'value',
                'choice_translation_domain' => "messages",
            ])
            ->add('submit', SubmitType::class, [
                'label' => "inquiries.btn_filter",
                'icon_before' => "fa-search me-2"
            ])->getForm();
    }
}