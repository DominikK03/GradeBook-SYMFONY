<?php

namespace App\Form;

use App\Entity\Grade;
use App\Entity\Student;
use App\Entity\Teacher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GradeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value')
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('type')
            ->add('subject')
            ->add('student', EntityType::class, [
                'class' => Student::class,
                'choice_label' => 'student',
            ])
            ->add('teacher', EntityType::class, [
                'class' => Teacher::class,
                'choice_label' => 'teacher',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Grade::class,
        ]);
    }
}
