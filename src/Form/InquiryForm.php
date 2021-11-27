<?php

namespace App\Form;

use App\Entity\Deadline;
use App\Entity\Inquiry;
use App\Entity\InquiryType;
use App\Entity\InquiryValue;
use App\Entity\Region;
use App\Repository\InquiryTypeRepository;
use App\Repository\Interfaces\IInquiryTypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InquiryForm extends AbstractType
{
    protected $translator;
    protected $inquiryRepository;

    public function __construct(TranslatorInterface $translator, IInquiryTypeRepository $inquiryTypeRepository)
    {
        $this->translator = $translator;
        $this->inquiryTypeRepository = $inquiryTypeRepository;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inquiry::class,
            'translation_domain' => "inquiries"
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => "field_title",
                'attr' => [
                    "placeholder" => "field_title_ph",
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => "field_description",
                'attr' => [
                    "placeholder" => "field_description_ph",
                ]
            ])
            ->add("region", EntityType::class, [
                'required' => true,
                'label' => "field_region",
                // looks for choices from this entity
                'class' => Region::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'title',

                // 'translation_domain' => "inquiries",
                // 'choice_translation_domain' => "inquiries",
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add("value", EntityType::class, [
                'required' => true,
                'label' => "field_value",
                // looks for choices from this entity
                'class' => InquiryValue::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'title',

                'translation_domain' => "inquiries",
                'choice_translation_domain' => "inquiries",
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add("deadline", EntityType::class, [
                'required' => true,
                'label' => "field_deadline",
                // looks for choices from this entity
                'class' => Deadline::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'title',

                'translation_domain' => "inquiries",
                'choice_translation_domain' => "inquiries",
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add("type", EntityType::class, [
                'required' => true,
                'label' => false,

                'data' => $this->inquiryTypeRepository->findOneByAlias("personal"),
                // looks for choices from this entity
                'class' => InquiryType::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'title',

                'choice_translation_domain' => "inquiries",
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                'expanded' => true,
            ])->add($builder->create('personalInquiry', FormType::class, [
                'translation_domain' => "inquiries",
                'by_reference' => false
            ])->add('name', TextType::class, [
                'label' => "field_individual_name",
                'attr' => [
                    "placeholder" => "field_individual_name_ph",
                ],
                'required' => true,
            ])
                ->add('surname', TextType::class, [
                    'label' => "field_individual_surname",
                    'attr' => [
                        "placeholder" => "field_individual_surname_ph",
                    ],
                    'required' => true
                ])
            )
            ->add($builder->create('companyInquiry', FormType::class, [
                'by_reference' => false
            ])
                ->add('name', TextType::class, [
                    'label' => "field_firm_name",
                    'attr' => [
                        "placeholder" => "field_firm_name_ph",
                    ],
                    'required' => true
                ])
            )
            ->add("contactEmail", EmailType::class, [
                'label' => "field_email",
                'attr' => [
                    "placeholder" => "field_email_ph",
                ],
            ])
            // TODO: Create a custom type for a phone number.
            ->add("contactPhone", TextType::class, [
                'required' => false,
                'label' => "field_phone",
                'attr' => [
                    "placeholder" => "field_phone_ph",
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "btn_submit",
            ])
            ->add("gdpr", CheckboxType::class, [
                'label' => "field_gdpr_agree",
                'required' => true,
                // This is an extra field which is not represented in Inquiry entity.
                'mapped' => false,
            ])
            ->add("terms", CheckboxType::class, [
                'label' => "field_terms_agree",
                'label_html' => true,
                'required' => true,
                // This is an extra field which is not represented in Inquiry entity.
                'mapped' => false
            ])
            ->getForm();
    }
}