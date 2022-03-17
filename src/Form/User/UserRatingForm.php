<?php

namespace App\Form\User;

use App\Business\Operation\InquiryOperation;
use App\Business\Service\UserService;
use App\Entity\Inquiry\Rating\SupplierRating;
use App\Entity\User\UserRating;
use App\Form\Type\RatingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRatingForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserRating::class,
            'translation_domain' => "messages",
            'label_format' => "ratings.field_%name%",
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("rating", RatingType::class, [
                'required' => false,
                'label' => "ratings.user.field_rating",
                'data' => 3
            ])
            ->add("targetNote", TextareaType::class, [
                'required' => false,
                "label" => "ratings.field_userNote",
                'attr' => [
                    "rows" => 5,
                    "placeholder" => "ratings.field_userNote_ph",
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "ratings.btn_send_rating",
                'attr' => ["class" => "btn btn-primary btn-center"]
            ])
            ->getForm();
    }
}