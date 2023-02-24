<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConsultantCreationFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('firstName', TextType::class, [
        'label' => false,
        'mapped' => false
      ])
      ->add('lastName', TextType::class, [
        'label' => false,
        'mapped' => false
      ])
      ->add('email', EmailType::class, [
        'label' => false
      ])
      ->add('plainPassword', PasswordType::class, [
        'label' => false,
        'mapped' => false,
        'attr' => ['autocomplete' => 'new-password'],
        'constraints' => [
          new NotBlank([
            'message' => 'Veuillez entrer un mot de passe',
          ]),
          new Length([
            'min' => 6,
            'minMessage' => 'Le mot de passe doit avoir au moins {{ limit }} caractères.',
            'max' => 4096,
          ]),
        ],
      ])
    ;
  }
}