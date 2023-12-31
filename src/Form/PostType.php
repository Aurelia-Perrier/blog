<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,
            [
                'label' => 'Titre du post',
                'attr' => ['class' => 'bg-beige mt-0 block w-full px-0.5 border-0 border-b-2 border-gray-200 focus:ring-0 focus:border-green'],
            ])
            ->add('body', TextareaType::class,
            [
                'label' => 'Contenu du post',
                'attr' => ['class' => 'bg-beige mt-0 block w-full px-0.5 border-0 border-b-2 border-gray-200 focus:ring-0 focus:border-green']
            ])
            ->add('category',EntityType::class, 
            [
                'choice_label' => 'name',
                'class' => Category::class,
                'multiple' => true,
                'expanded' => true,
                'label' => 'Associez votre article avec au moins une catégorie',
                'attr' => [
                    'class' => 'flex justify-between my-8 focus:border-green'
                ],
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
