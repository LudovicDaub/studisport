<?php

namespace App\Form;

use App\Classe\Search;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SearchType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('string', TextType::class, [
        'label' => false,
        'required' => false,
        'attr' => [
          'placeholder' => 'Rechercher...',
        ],
      ]);

    $builder
      ->add('active', CheckboxType::class, [
        'label' => 'Actif',
        'required' => false
      ])
      ->add('inactive', CheckboxType::class, [
        'label' => 'Inactif',
        'required' => false
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Search::class,
      'method' => 'GET',
      'csrf_protection' => false,
    ]);
  }

  public function getBlockPrefix()
  {
    return '';
  }
}
