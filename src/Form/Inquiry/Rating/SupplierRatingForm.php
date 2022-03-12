<?php

namespace App\Form\Inquiry\Rating;

use App\Business\Operation\InquiryOperation;
use App\Business\Service\UserService;
use App\Entity\Inquiry\Rating\InquiringRating;
use App\Entity\Inquiry\Rating\SupplierRating;
use App\Entity\User;
use App\Form\Type\RatingType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierRatingForm extends AbstractType
{
    public function __construct(
        private InquiryOperation $inquiryOperation,
        private UserService      $userService,
    )
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SupplierRating::class,
            'translation_domain' => "messages",
            'label_format' => "ratings.field_%name%",
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var SupplierRating $rating */
        $rating = $builder->getData();

        $builder
            ->add("rating", RatingType::class, [
                'required' => false,
                'label' => "ratings.supplier.field_rating",
                'data' => 3
            ])
            ->add("realizedInquiry", CheckboxType::class, [
                'required' => false
            ])
            ->add("inquiringNote", TextareaType::class, [
                'required' => false,
                'attr' => [
                    "rows" => 5,
                    "placeholder" => "ratings.field_inquiringNote_ph",
                ]
            ])
            ->add('note', TextareaType::class, [
                'required' => false,
                'attr' => [
                    "rows" => 5,
                    "placeholder" => "ratings.field_note_ph",
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "ratings.btn_send_rating",
                'attr' => ["class" => "btn btn-primary btn-center"]
            ])
            ->getForm();
    }
}