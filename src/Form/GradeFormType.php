<?php

namespace App\Form;

use App\Entity\Grade;
use App\Enum\GradeType;
use App\Enum\Subject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class GradeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', IntegerType::class, [
                'label' => 'Ocena',
                'constraints' => [
                    new NotBlank(['message' => 'Proszę wprowadzić ocenę']),
                    new Range([
                        'min' => 1,
                        'max' => 6,
                        'notInRangeMessage' => 'Ocena musi być z zakresu 1-6',
                    ])
                ],
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500',
                    'min' => 1,
                    'max' => 6,
                    'placeholder' => 'Wprowadź ocenę (1-6)'
                ]
            ])
            ->add('type', EnumType::class, [
                'class' => GradeType::class,
                'label' => 'Typ oceny',
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500'
                ]
            ])
            ->add('student_id', HiddenType::class)
            ->add('teacher_id', HiddenType::class)
            ->add('subject', EnumType::class, [
                'class' => Subject::class,
                'attr' => ['class' => 'hidden']
            ])
            ->add('date', HiddenType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Dodaj Ocenę',
                'attr' => [
                    'class' => 'w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Grade::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'grade_form'
        ]);
    }
}
