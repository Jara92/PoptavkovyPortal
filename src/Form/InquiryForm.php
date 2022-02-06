<?php /** @noinspection PhpUnusedAliasInspection */

namespace App\Form;

use App\Business\Operation\InquiryOperation;
use App\Business\Service\UserService;
use App\Entity\Inquiry\CompanyContact;
use App\Entity\Inquiry\Deadline;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\InquiryType;
use App\Entity\Inquiry\InquiryValue;
use App\Entity\Inquiry\PersonalContact;
use App\Entity\Inquiry\Region;
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
    /** @required  */
    public TranslatorInterface $translator;

    /** @required  */
    public InquiryOperation $inquiryOperation;

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
            ->add("region", EntityType::class, [
                'required' => true,
                'label' => "inquiries.field_region",
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
                'label' => "inquiries.field_value",
                // looks for choices from this entity
                'class' => InquiryValue::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'title',
                'choice_translation_domain' => "messages",

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add("deadline", EntityType::class, [
                'required' => true,
                'label' => "inquiries.field_deadline",
                // looks for choices from this entity
                'class' => Deadline::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'title',

                'choice_translation_domain' => "messages",

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add("type", EntityType::class, [
                'required' => true,
                'label' => false,

                'choice_value' => function (?InquiryType $entity) {
                    return $entity ? $entity->getAlias() : '';
                },

                // Set default option
                'data' => $this->inquiryOperation->getNewInquiryDefaultType(),
                // looks for choices from this entity
                'class' => InquiryType::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'title',

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
                    'label' => "inquiries.field_firm_name",
                    'attr' => [
                        "placeholder" => "inquiries.field_firm_name_ph",
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