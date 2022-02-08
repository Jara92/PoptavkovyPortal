<?php

namespace App\Form;

use App\Business\Operation\InquiryOperation;
use App\Entity\Inquiry\InquiryCategory;
use App\Entity\Inquiry\InquiryType;
use App\Entity\Inquiry\InquiryValue;
use App\Entity\Inquiry\Region;
use App\Filter\InquiryFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
                'label' => "inquiries.field_search_text",
                'attr' => [
                    "placeholder" => "inquiries.field_search_text",
                ]
            ])
            ->add("categories", EntityType::class, [
                'required' => false,
                'multiple' => true,
                'label' => "inquiries.field_categories",
                'class' => InquiryCategory::class,
                'choice_label' => 'title',
                'choice_translation_domain' => "messages",
            ])->add("regions", EntityType::class, [
                'required' => false,
                'multiple' => true,
                'label' => "inquiries.field_region",
                'class' => Region::class,
                'choice_label' => 'title',
                'choice_translation_domain' => "messages",
            ])->add("values", EntityType::class, [
                'required' => false,
                'multiple' => true,
                'label' => "inquiries.field_value",
                'class' => InquiryValue::class,
                'choice_label' => 'title',
                'choice_translation_domain' => "messages",
            ])->add("types", EntityType::class, [
                'required' => false,
                'multiple' => true,
                'label' => "inquiries.field_categories",
                'class' => InquiryType::class,
                'choice_label' => 'title',
                'choice_translation_domain' => "messages",
            ])->add('submit', SubmitType::class, [
                'label' => "inquiries.btn_filter",
            ])->getForm();
    }
}