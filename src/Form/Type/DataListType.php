<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataListType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault("choices", []);
        $resolver->setDefault("list_suffix", "list");
    }

    public function getParent(): string
    {
        return TextType::class;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars["choices"] = $options["choices"];
        $view->vars["list_id"] = $view->vars["attr"]["list"] = $view->vars["id"] . "_" . $options["list_suffix"];
    }
}