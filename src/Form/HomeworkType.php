<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\Homework;
use App\Entity\Teacher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HomeworkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject')
            ->add('dueDate', null, [
                'widget' => 'single_text',
            ])
            ->add('description')
            ->add('topic')
            ->add('teacher', EntityType::class, [
                'class' => Teacher::class,
                'choice_label' => 'id',
            ])
            ->add('class', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Homework::class,
        ]);
    }
}
