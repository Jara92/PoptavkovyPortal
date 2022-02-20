<?php

namespace App\Form\User;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class CompanyForm extends AbstractType
{
    protected TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
            'translation_domain' => "messages"
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => "user.field_company_name",
            'attr' => [
                "placeholder" => "user.field_company_name_ph",
            ],
            'required' => true
        ])
            // TODO: identificationNumber form type
            ->add("identificationNumber", TextType::class, [
                'label' => "user.field_identification_number",
                'attr' => [
                    "placeholder" => "user.field_identification_number_ph",
                ],
                'required' => true
            ])
            // TODO: taxIdentificationNumber form type
            ->add("taxIdentificationNumber", TextType::class, [
                'label' => "user.field_tax_identification_number",
                'attr' => [
                    "placeholder" => "user.field_tax_identification_number_ph",
                ],
                'required' => false
            ])
            ->add("addressStreet", TextType::class, [
                'label' => "user.field_address_street",
                'attr' => [
                    "placeholder" => "user.field_address_street_ph",
                ],
                'required' => true
            ])
            ->add("addressCity", TextType::class, [
                'label' => "user.field_address_city",
                'attr' => [
                    "placeholder" => "user.field_address_city_ph",
                ],
                'required' => true
            ])->add("addressZipCode", TextType::class, [
                'label' => "user.field_address_zip_code",
                'attr' => [
                    "placeholder" => "user.field_address_zip_code_ph",
                ],
                'required' => true
            ]);
    }
}