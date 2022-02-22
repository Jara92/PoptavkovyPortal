<?php

namespace App\Form\User;

use App\Entity\Company;
use App\Entity\Person;
use App\Entity\User;
use App\Enum\Entity\UserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class UserSettingsForm extends AbstractType
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
            ->add('submit', SubmitType::class, [
                'label' => "btn_save",
                'attr' => ["class" => "btn btn-primary"]
            ])->getForm();
    }
}