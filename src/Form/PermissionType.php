<?php

namespace App\Form;

use App\Entity\Permission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PermissionType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('isNewsletter', CheckboxType::class, [
        'label' => false,
        'label_attr' => ['class' => 'switch-custom'],
        'required' => false,
      ])
      ->add('isVenteBoisson', CheckboxType::class, [
        'label' => false,
        'label_attr' => ['class' => 'switch-custom'],
        'required' => false,
      ])
      ->add('isOffreSac', CheckboxType::class, [
        'label' => false,
        'label_attr' => ['class' => 'switch-custom'],
        'required' => false,
      ])
      ->add('isBadgePerso', CheckboxType::class, [
        'label' => false,
        'label_attr' => ['class' => 'switch-custom'],
        'required' => false,
      ])
      ->add('isVenteMerch', CheckboxType::class, [
        'label' => false,
        'label_attr' => ['class' => 'switch-custom'],
        'required' => false,
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Permission::class,
    ]);
  }
}
