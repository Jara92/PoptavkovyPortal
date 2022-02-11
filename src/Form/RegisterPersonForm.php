<?php

namespace App\Form;

use App\Business\Service\UserTypeService;
use App\Entity\Person;
use Symfony\Component\Form\FormBuilderInterface;

class RegisterPersonForm extends UserForm
{
    /** @required  */
    public UserTypeService $userTypeService;

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