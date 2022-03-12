<?php

namespace App\Form\Inquiry\Rating;

use App\Business\Operation\InquiryOperation;
use App\Business\Service\UserService;
use App\Entity\Inquiry\Rating\InquiringRating;
use App\Entity\User;
use App\Form\Type\RatingType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InquiringRatingForm extends AbstractType
{
    /** @required */
    public TranslatorInterface $translator;

    public function __construct(
        private InquiryOperation $inquiryOperation,
        private UserService      $userService
    )
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InquiringRating::class,
            'translation_domain' => "messages",
            'label_format' => "ratings.field_%name%",
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var InquiringRating $rating */
        $rating = $builder->getData();

        $builder
            ->add("supplier", EntityType::class, [
                "class" => User::class,
                'required' => false,
                'placeholder' => "ratings.field_supplier_ph",
                'help' => "ratings.field_supplier_help",
                'choices' => $this->inquiryOperation->getInquiryOffersSuppliers($rating->getInquiry()),
                'choice_label' => fn(User $user) => $this->userService->getFormatedUserName($user),
            ])
            ->add("rating", RatingType::class, [
                'data' => 3
            ])
            ->add("supplierNote", TextareaType::class, [
                'required' => false,
                'attr' => [
                    "rows" => 5,
                    "placeholder" => "ratings.field_supplierNote_ph",
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