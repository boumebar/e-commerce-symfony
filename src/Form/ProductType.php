<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("name", TextType::class, [
                'label' => 'Nom du produit',
                'attr'  => ['placeholder' => 'Entrer le nom du produit']
            ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'Descrption',
                'attr'  => ['placeholder' => 'Description courte']
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix du produit en ',
                'attr'  => ['placeholder' => 'Entrer le prix en euros']
            ])
            ->add('mainPicture', UrlType::class, [
                'label' => 'Url',
                'attr'  => ['placeholder' => "Entrer une adresse url d'image"]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'placeholder' => '-- Choisir une catégorie --',
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return strtoupper($category->getName());
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
