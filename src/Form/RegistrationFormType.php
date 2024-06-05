<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('email', EmailType::class)
      ->add('username', TextType::class, [
        'label' => 'Pseudonyme',
      ])
      ->add('plainPassword', RepeatedType::class, [
        // instead of being set onto the object directly,
        // this is read and encoded in the controller
        'mapped' => false,
        'type' => PasswordType::class,
        'first_options' => ['label' => 'Mot de passe'],
        'second_options' => ['label' => 'Confirmer le mot de passe'],
        'invalid_message' => 'Les mots de passes doivent correspondre.',
        'constraints' => [
          new NotBlank([
            'message' => 'Veuillez entrer un mot de passe.',
          ]),
          new Length([
            'min' => 6,
            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
            'max' => 4096,
          ]),
        ],
      ])
      ->add('agreeTerms', CheckboxType::class, [
        'mapped' => false,
        'label' => 'Accepter les conditions d\'utilisations',
        'constraints' => [
          new IsTrue([
            'message' => 'Veuillez accepter les conditions d\'utilisations.',
          ]),
        ],
      ])
      ->add('save', SubmitType::class, [
        'label' => 'S\'inscrire',
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}
