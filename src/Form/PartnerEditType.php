<?php

namespace App\Form;

use App\Entity\Partner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class PartnerEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name', TextType::class, [
        'mapped' => false,

        'label' => 'Nom de l\'établissement Partenaire',
        'required' => true,
        'constraints' => new Length([
          'min' => 2,
          'max' => 30
        ]),
        'attr' => [
          'placeholder' => 'Merci de saisir le nom du Partenaire',
          'mapped' => false
        ]
      ])

      ->add('isNewsletter', CheckboxType::class, [
        'mapped' => false,
        'required' => false,
        'label' => false,
        'label_attr' => ['class' => 'switch-custom'],

      ])
      ->add('isVenteBoisson', CheckboxType::class, [
        'mapped' => false,
        'required' => false,
        'label' => false,
        'label_attr' => ['class' => 'switch-custom'],

      ])
      ->add('isOffreSac', CheckboxType::class, [
        'mapped' => false,
        'required' => false,
        'label' => false,
        'label_attr' => ['class' => 'switch-custom'],

      ])
      ->add('isBadgePerso', CheckboxType::class, [
        'mapped' => false,
        'required' => false,
        'label' => false,
        'label_attr' => ['class' => 'switch-custom'],

      ])
      ->add('isVenteMerch', CheckboxType::class, [
        'mapped' => false,
        'required' => false,
        'label' => false,
        'label_attr' => ['class' => 'switch-custom'],

      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Partner::class,
    ]);
  }
  /**
   */
  function __construct()
  {
  }
}
