<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Todolist;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TodolistType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('title', TextType::class, [
        'label' => 'Titre',
      ])
      ->add('category', EntityType::class, [
        'class' => Category::class,
        'choice_label' => fn(Category $category) => ucfirst($category->getName()),
        'label' => 'Catégorie',
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Todolist::class,
    ]);
  }
}
