<?php

namespace App\Form\Auth;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("oldPassword", PasswordType::class, [
                'mapped' => false,
                'label' => "auth.field_old_password",
                'attr' => ['autocomplete' => 'old-password', 'class' => 'password-field'],
                'required' => true,
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'invalid_message' => 'passwords_must_match',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'auth.field_new_password'],
                'second_options' => ['label' => 'auth.field_new_password_again'],
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
                'label' => "auth.btn_change_password",
                'attr' => ["class" => "btn btn-primary"]
            ]);
    }
}