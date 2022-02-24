<?php

namespace App\Form\Inquiry;

use App\Business\Operation\InquiryOperation;
use App\Business\Service\UserService;
use App\Entity\Inquiry\CompanyContact;
use App\Entity\Inquiry\Deadline;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\Offer;
use App\Enum\Entity\InquiryType;
use App\Entity\Inquiry\InquiryValue;
use App\Entity\Inquiry\PersonalContact;
use App\Entity\Inquiry\Region;
use App\Helper\InquiryTypeHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferForm extends AbstractType
{
    /** @required */
    public TranslatorInterface $translator;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
            'translation_domain' => "messages",
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextareaType::class, [
                'required' => true,
                //'label' => "offers.field_text",
                'label' => false,
                'attr' => [
                    "rows" => 5,
                    "placeholder" => "offers.field_text_ph",
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "offers.btn_send_offer",
                'attr' => ["class" => "btn btn-primary btn-send-offer"]
            ])
            ->add("sendCopy", CheckboxType::class, [
                'label' => "offers.field_send_copy",
                'required' => false,
                'mapped' => false,
            ])
            ->getForm();
    }
}