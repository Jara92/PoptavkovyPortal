<?php

namespace App\Form\Auth;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserForm extends AbstractType
{
    protected TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => "messages"
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => "auth.field_email",
                'attr' => [
                    "placeholder" => "auth.field_email_ph",
                ]
            ])
            // TODO: Make phone Type
            ->add('phone', TextType::class, [
                'required' => false,
                'label' => "auth.field_phone",
                'attr' => [
                    "placeholder" => "auth.field_phone_ph",
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'invalid_message' => 'passwords_must_match',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'auth.field_password'],
                'second_options' => ['label' => 'auth.field_password_again'],
                'constraints' => [
                    new NotBlank([

                    ]),
                    new Length([
                        'min' => 8,

                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "auth.register"
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
            ])->getForm();
    }
}