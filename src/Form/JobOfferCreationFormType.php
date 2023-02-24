<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class JobOfferCreationFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('title', TextType::class, [
        'label' => "Titre",
        'mapped' => false
      ])
      ->add('description', TextareaType::class, [
        'label' => "Description",
        'mapped' => false
      ])
    ;
  }
}