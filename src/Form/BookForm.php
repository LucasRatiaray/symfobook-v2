<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('coverImage')
            ->add('pageCount')
            ->add('publishedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'id',
            ])
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
