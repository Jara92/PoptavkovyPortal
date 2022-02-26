<?php

namespace App\Form\User;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
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
            'translation_domain' => "messages",
            'label_format' => "profiles.field_%name%"
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('avatar', FileType::class, [
                'multiple' => false,

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new Image([
                        'maxSize' => '4096k',
                    ])
                ]
            ])
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