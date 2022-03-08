<?php

namespace App\Form\User;

use App\Entity\Notification;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationForm extends AbstractType
{
    protected TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Notification::class,
            'translation_domain' => "messages",
            'label_format' => 'notifications.field_%name%',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('newsletter', CheckboxType::class, [
            'label_attr' => [
                'class' => 'checkbox-switch',
            ],
        ])
            ->add('submit', SubmitType::class, [
                'label' => "btn_save",
                'attr' => ["class" => "btn btn-primary btn-center"]
            ])->getForm();
    }
}