<?php /** @noinspection PhpUnusedAliasInspection */

namespace App\Form\Auth;

use App\Entity\Company;
use App\Entity\Inquiry\InquiryType;
use App\Entity\Person;
use App\Entity\User;
use App\Enum\Entity\UserType;
use App\Form\User\CompanyForm;
use App\Form\Auth\UserForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegisterCompanyForm extends UserForm
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