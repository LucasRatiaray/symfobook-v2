<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Discussion;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content')
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
        //     ->add('updatedAt', null, [
        //         'widget' => 'single_text',
        //     ])
        //     ->add('discussion', EntityType::class, [
        //         'class' => Discussion::class,
        //         'choice_label' => 'id',
        //     ])
        //     ->add('user', EntityType::class, [
        //         'class' => User::class,
        //         'choice_label' => 'id',
        //     ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
