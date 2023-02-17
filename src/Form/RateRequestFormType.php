<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;

class RateRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('currencyChar', TextType::class)
            ->add('baseChar', TextType::class)*/
            ->add('currencyChar', CurrencyType::class)
            ->add('baseChar', CurrencyType::class)
            ->add('date', DateTimeType::class, ['widget' => 'single_text'])
            ->add('getRate', SubmitType::class);
        ;
    }
}