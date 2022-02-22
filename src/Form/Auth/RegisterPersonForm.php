<?php

namespace App\Form\Auth;

use App\Entity\Person;
use App\Form\User\PersonForm;
use App\Form\Auth\UserForm;
use Symfony\Component\Form\FormBuilderInterface;

class RegisterPersonForm extends UserForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($builder->create('person', PersonForm::class, [
            'by_reference' => true,
            'data_class' => Person::class
        ]))->getForm();

        parent::buildForm($builder, $options);
    }
}