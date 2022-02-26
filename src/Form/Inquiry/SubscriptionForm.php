<?php

namespace App\Form\Inquiry;

use App\Business\Service\InquiryCategoryService;
use App\Entity\Inquiry\InquiryCategory;
use App\Entity\Inquiry\Subscription;
use App\Entity\Inquiry\Region;
use App\Helper\InquiryTypeHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionForm extends AbstractType
{
    public function __construct(
        private InquiryCategoryService $inquiryCategoryService
    )
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subscription::class,
            'translation_domain' => "messages",
            'label_format' => "subscriptions.field_%name%",
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('newsletter', CheckboxType::class, [
                'required' => false,
            ])
            ->add("categories", EntityType::class, [
                'required' => false,
                'multiple' => true,
                'class' => InquiryCategory::class,
                'choices' => $this->inquiryCategoryService->readAllSubCategories(),
                'choice_label' => fn(InquiryCategory $category) => $category->getParent()->getTitle() . " | " . $category->getTitle(),
                'choice_translation_domain' => false,
            ])
            ->add("regions", EntityType::class, [
                'required' => false,
                'multiple' => true,
                'class' => Region::class,
                'choice_label' => 'title',
                'choice_translation_domain' => false,
            ])
            ->add("types", ChoiceType::class, [
                'required' => false,
                'multiple' => true,

                'choices' => InquiryTypeHelper::translationCases(),

                // Use Enums item value in select
                'choice_value' => 'value',
                'choice_translation_domain' => "messages",
            ])
            ->add('submit', SubmitType::class, [
                'label' => "subscriptions.btn_submit",
                'attr' => ["class" => "btn btn-primary"]
            ])
            ->getForm();
    }
}