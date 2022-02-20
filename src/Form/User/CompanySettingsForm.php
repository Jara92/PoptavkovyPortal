<?php

namespace App\Form\User;

use App\Entity\Company;
use App\Form\User\CompanyForm;
use Symfony\Component\Form\FormBuilderInterface;

class CompanySettingsForm extends UserSettingsForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($builder->create('company', CompanyForm::class, [
                'by_reference' => true,
                'data_class' => Company::class
            ]))->getForm();

        parent::buildForm($builder, $options);
    }
}