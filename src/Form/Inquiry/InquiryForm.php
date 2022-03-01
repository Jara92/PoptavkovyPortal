<?php

namespace App\Form\Inquiry;

use App\Business\Operation\InquiryOperation;
use App\Business\Service\DeadlineService;
use App\Business\Service\InquiryValueService;
use App\Business\Service\UserService;
use App\Entity\Inquiry\CompanyContact;
use App\Entity\Inquiry\Deadline;
use App\Entity\Inquiry\Inquiry;
use App\Enum\Entity\InquiryType;
use App\Entity\Inquiry\InquiryValue;
use App\Entity\Inquiry\PersonalContact;
use App\Entity\Inquiry\Region;
use App\Form\Type\DataListType;
use App\Helper\InquiryTypeHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InquiryForm extends AbstractType
{
    /** @required */
    public TranslatorInterface $translator;

    /** @required */
    public InquiryOperation $inquiryOperation;

    public function __construct(
        private InquiryValueService $inquiryValueService,
        private DeadlineService     $deadlineService
    )
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inquiry::class,
            'translation_domain' => "messages"
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => "inquiries.field_title",
                'attr' => [
                    "placeholder" => "inquiries.field_title_ph",
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => "inquiries.field_description",
                'attr' => [
                    "placeholder" => "inquiries.field_description_ph",
                ]
            ])
            ->add('attachments', FileType::class, [
                // TODO: fill accept attribute.
                'label' => 'inquiries.field_attachments',

                'multiple' => true,

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => new All([
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'application/msword',
                            'application/zip',
                            'image/png',
                            'image/gif',
                            'image/jpeg'
                        ],
                        // 'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ]),
            ])
            ->add("region", EntityType::class, [
                'required' => false,
                'label' => "inquiries.field_region",
                // looks for choices from this entity
                'class' => Region::class,
                'placeholder' => "inquiries.field_region_ph",

                // uses the User.username property as the visible option string
                'choice_label' => 'title',

                // 'translation_domain' => "inquiries",
                // 'choice_translation_domain' => "inquiries",
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add("city", TextType::class, [
                'required' => false,
                'label' => "inquiries.field_city",
                'attr' => [
                    "placeholder" => "inquiries.field_city_ph",
                ]
            ])
            ->add("valueText", DataListType::class, [
                'required' => false,
                'label' => "inquiries.field_value",
                "attr" => [
                    "placeholder" => "inquiries.field_value_ph"
                ],
                "choices" => $this->inquiryValueService->readAll()
            ])
            ->add("deadlineText", DataListType::class, [
                'required' => false,
                'label' => "inquiries.field_deadline",
                "attr" => [
                    "placeholder" => "inquiries.field_deadline_ph"
                ],
                "choices" => $this->deadlineService->readAll()
            ])
            ->add("type", ChoiceType::class, [
                'required' => true,
                'label' => false,

                'choices' => InquiryTypeHelper::convertToTranslationCases($this->inquiryOperation->getAvailableInquiryTypesToCreate()),

                // Use Enums item value in select
                'choice_value' => 'value',

                'choice_translation_domain' => "messages",

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                'expanded' => true,
            ])->add($builder->create('personalContact', FormType::class, [
                'by_reference' => true,
                'data_class' => PersonalContact::class
            ])->add('name', TextType::class, [
                'label' => "inquiries.field_person_name",
                'attr' => [
                    "placeholder" => "inquiries.field_person_name_ph",
                ],
                'required' => true,
            ])
                ->add('surname', TextType::class, [
                    'label' => "inquiries.field_person_surname",
                    'attr' => [
                        "placeholder" => "inquiries.field_person_surname_ph",
                    ],
                    'required' => true
                ])
            )
            ->add($builder->create('companyContact', FormType::class, [
                'by_reference' => true,
                'data_class' => CompanyContact::class
            ])
                ->add('companyName', TextType::class, [
                    'label' => "user.field_company_name",
                    'attr' => [
                        "placeholder" => "user.field_company_name_ph",
                    ],
                    'required' => true
                ])
                ->add('identificationNumber', TextType::class, [
                    'label' => "user.field_identification_number",
                    'attr' => [
                        "placeholder" => "user.field_identification_number_ph",
                    ],
                    'required' => true
                ])
            )
            ->add("contactEmail", EmailType::class, [
                'label' => "inquiries.field_email",
                'attr' => [
                    "placeholder" => "inquiries.field_email_ph",
                ],
            ])
            // TODO: Create a custom type for a phone number.
            ->add("contactPhone", TextType::class, [
                'required' => false,
                'label' => "inquiries.field_phone",
                'attr' => [
                    "placeholder" => "inquiries.field_phone_ph",
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "inquiries.btn_submit",
            ])
            ->add("gdpr", CheckboxType::class, [
                'label' => "inquiries.field_gdpr_agree",
                'required' => true,
                // This is an extra field which is not represented in Inquiry entity.
                'mapped' => false,
            ])
            ->add("terms", CheckboxType::class, [
                'label' => "inquiries.field_terms_agree",
                'label_html' => true,
                'required' => true,
                // This is an extra field which is not represented in Inquiry entity.
                'mapped' => false
            ])
            ->getForm();
    }
}