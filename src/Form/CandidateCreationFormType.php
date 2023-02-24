<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;

class CandidateCreationFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('firstName', TextType::class, [
        'label' => "Prénom",
        'mapped' => false
      ])
      ->add('lastName', TextType::class, [
        'label' => "Nom",
        'mapped' => false
      ])
      ->add('cv', FileType::class, [
        'label' => "C.V. (au format PDF uniquement)",
        'mapped' => false,
        'constraints' => [
          new File([
            'maxSize' => '1024k',
            'mimeTypes' => [
              'application/pdf',
              'application/x-pdf',
            ],
            'mimeTypesMessage' => 'Veuillez sélectionner un fichier .pdf valide.',
          ])
      ]
      ])
    ;
  }
}