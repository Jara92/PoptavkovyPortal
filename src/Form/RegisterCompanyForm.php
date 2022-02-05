<?php

namespace App\Form;

use App\Business\Service\UserTypeService;
use App\Entity\Company;
use App\Entity\Inquiry\InquiryType;
use App\Entity\Person;
use App\Entity\User;
use App\Entity\UserType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegisterCompanyForm extends UserForm
{
    /** @required  */
    public UserTypeService $userTypeService;

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