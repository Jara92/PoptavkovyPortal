<?php

namespace App\Form\User;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfileForm extends AbstractType
{
    protected TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
            'translation_domain' => "messages"
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("description", TextareaType::class, [
                "label" => "profiles.field_description",
                "required" => false,
                "empty_data" => ""
            ])
            //  ->add("avatar", )
            ->add("web", UrlType::class, [
                "label" => "profiles.field_web",
                "required" => false,
            ])
            ->add("facebook", UrlType::class, [
                "label" => "profiles.field_facebook",
                "required" => false
            ])
            ->add("linkedin", UrlType::class, [
                "label" => "profiles.field_linkedin",
                "required" => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => "btn_save",
                'attr' => ["class" => "btn btn-primary"]
            ])->getForm();
    }
}