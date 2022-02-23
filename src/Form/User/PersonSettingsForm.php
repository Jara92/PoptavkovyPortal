<?php

namespace App\Form\User;

use App\Entity\Person;
use App\Form\User\PersonForm;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PersonSettingsForm extends UserSettingsForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($builder->create('person', PersonForm::class, [
                'by_reference' => true,
                'data_class' => Person::class,
                'label' => false
            ]))->getForm();

        parent::buildForm($builder, $options);
    }
}