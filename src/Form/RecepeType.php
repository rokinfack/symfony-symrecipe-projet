<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Repository\IngredientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecepeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,
            [
                'attr'=>['class'=>'form-control'],
                'label'=>'Nom',
                'label_attr'=>['class'=>'form-label mt-4']
            ])
            ->add('time',IntegerType::class,[
                'attr'=>['class'=>'form-control',
                'min'=>1,
                'max'=>1441],
                'label'=>'temps (en minutes',
                'label_attr'=>['class'=>'form-label mt-4'],
                'constraints'=>[
                    new Assert\Positive(),
                    new Assert\LessThan(1441)
                ]
            ])
            ->add('nbPeople',Integer::class,
            [
                'attr'=>['class'=>'form-control',
                'min'=>1,
                'max'=>51],
                'label'=>'temps (en minutes',
                'label_attr'=>['class'=>'form-label mt-4'],
                'constraints'=>[
                    new Assert\Positive(),
                    new Assert\LessThan(51)
                ]
            ])
            ->add('dificulty',RangeType::class,
            [
                'attr'=>['class'=>'form-range',
                'min'=>1,
                'max'=>5],
                'label'=>'Difficulté',
                'label_attr'=>['class'=>'form-label mt-4'],
                'constraints'=>[
                    new Assert\Positive(),
                    new Assert\LessThan(5)
                ]
            ])
            ->add('description',TextareaType::class,[
                'attr'=>['class'=>'form-control'],
                'label'=>'Description',
                'label_attr'=>['class'=>'form-label mt-4'],
                'constraints'=>[
                    new Assert\NotBlank,
                    new Assert\LessThan(255)
                ]

            ])
            ->add('isFavorite',CheckboxType::class,[
                'attr'=>['class'=>'form-control'],
                'label'=>'Favoris ?',
                'label_attr'=>['class'=>'form-label mt-4'],
                'constraints'=>[
                    new Assert\NotNull(),
                   
                ]
            ])
            ->add('price',MoneyType::class,
            [
                'attr'=>['class'=>'form-control'],
                'label'=>'Prix',
                'label_attr'=>['class'=>'form-label mt-4'],
                'constraints'=>[
                    new Assert\Positive(),
                    new Assert\LessThan(1001)
                ]
            ])
            ->add('ingredients',EntityType::class,[
                'attr'=>['class'=>'form-control'],
                'class'=>Ingredient::class,
                'label'=>'Les ingrédients',
                'label_attr'=>['class'=>'form-label mt-4'],
                'query_builder'=>function(IngredientRepository $er){
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.name', 'ASC');
                },
                'choice_label'=>'name',
                'multiple'=>true,
                'expanded'=>false

            ])
            ->add('submit',SubmitType::class,[
                'attr'=>['class'=>'btn btn-primary mt-4'],
            'label'=>'Créer ma recette',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
