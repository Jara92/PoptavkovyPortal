<?php

namespace App\Form\Auth;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserVerificationForm extends AbstractType
{
    /** @required */
    public TranslatorInterface $translator;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => "messages",
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'mapped' => false,
                'label' => false,
                'attr' => [
                    "placeholder" => "auth.resend_field_email",
                ],
                'constraints' => [
                    new Email(),
                    new NotNull()
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "auth.btn_resend_verification_email",
                'attr' => ["class" => "btn btn-primary btn-center"]
            ])
            ->getForm();
    }
}