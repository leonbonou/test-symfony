<?php

namespace App\Form;

use App\Entity\UserClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'required' => false
            ])
            ->add('nom', TextType::class, [
                'required' => false
            ])
            ->add('prenom', TextType::class, [
                'required' => false
            ])
            ->add('date_at', DateType::class, [
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr'  => [
                    'placeholder'   => 'dd/mm/yyyy'
                ]
            ])
            ->add('pays', CountryType::class)
            ->add('email', EmailType::class, [
                'required' => false
            ])
            ->add('numero', TextType::class, [
                'required' => false
            ])
            ->add('profession', TextType::class, [
                'required' => false
            ])
            ->add('device', ChoiceType::class, [
                'choices'   => $this->getChoices(),
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message'   => 'Les mots de passe ne correspondent pas',
                'required'=>false,
                'first_options' => ['label'=>'Mot de passe', 'attr' => ['placeholder' => "Votre mot de passe"]],
                'second_options' => ['label'=>'Confirmation du Mot de passe', 'attr' => ['placeholder' => "Confirmation Mot de passe"]],
            ])
        ;
    }

    private function getChoices() {
        return [
            'XOF'   => 'XOF',
            'EURO'   => 'EURO',
        ];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserClient::class,
        ]);
    }
}
