<?php

namespace App\Form;

use App\Entity\Inquiry;
use App\Entity\Region;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InquiryForm extends AbstractType
{
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inquiry::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => $this->translator->trans("field_title", [], "inquiries")
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => $this->translator->trans("field_description", [], "inquiries")
            ])
            ->add("region", EntityType::class, [
                'required' => true,
                // looks for choices from this entity
                'class' => Region::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'title',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add("contactEmail", EmailType::class, [
                'label' => $this->translator->trans("field_email", [], "inquiries")
            ])
            // TODO: Create a custom type for a phone number.
            ->add("contactPhone", TextType::class, [
                'label' => $this->translator->trans("field_phone", [], "inquiries")
            ])
            ->add('save', SubmitType::class, [
                'label' => $this->translator->trans("btn_submit", [], "inquiries")
            ])
            ->getForm();
    }
}