<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add("name", TextType::class, [
                'label' => 'Nom du produit',
                'attr'  => ['placeholder' => 'Entrer le nom du produit'],
            ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'Descrption',
                'attr'  => ['placeholder' => 'Description courte']
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix du produit en ',
                'attr'  => ['placeholder' => 'Entrer le prix en euros'],
                'divisor' => 100,
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

        /**
         * Modifie l'affichage du prix dans l'edition d'un produit en euro et l'enregistre en centimes dans la BDD  MAIS ON UTILISE NOUS LE DIVISOR A LA LIGNE 36 qui fait   * la meme chose
         */

        /* $builder->get('price')->addModelTransformer(new CallbackTransformer(

        A l'affichage
            function ($value) {
                if (!$value) {
                    return;
                }
                return $value / 100;
            },

        POur la BDD
            function ($value) {

                if (!$value) {
                    return;
                }
                return $value * 100;
            }
        ));*/



        /**
         * Ajouter des ecouteurs d'evenements pour diviser ou multiplier une valeur de la Bdd a l'affichage
         */

        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

        //     /** @var Product */
        //     $product = $event->getData();

        //     if ($product->getPrice()) {
        //         $product->setPrice($product->getPrice() / 100);
        //     }
        // });

        // $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {

        //     /** @var Product */
        //     $product = $event->getData();

        //     if ($product->getPrice()) {
        //         $product->setPrice($product->getPrice() * 100);
        //     }
        // });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
