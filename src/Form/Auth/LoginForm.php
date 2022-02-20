<?php /** @noinspection PhpUnusedAliasInspection */

namespace App\Form\Auth;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', EmailType::class, [
                'required' => true,
                'label' => "auth.field_email",
            ])
            ->add('_password', PasswordType::class, [
                'required' => true,
                'label' => "auth.field_password"
            ])
            ->add('_remember_me', CheckboxType::class, [
                'required' => false,
                'data' => false,
                'label' => "auth.field_remember_me"
            ])
            ->add('submit', SubmitType::class, [
                'label' => "auth.log_in"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => 'authenticate',
        ));
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
