<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RecruiterCreationFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('company', TextType::class, [
        'label' => "Nom de l'entreprise",
        'mapped' => false
      ])
      ->add('address', TextType::class, [
        'label' => "Adresse",
        'mapped' => false
      ])
    ;
  }
}