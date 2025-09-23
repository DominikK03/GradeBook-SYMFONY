<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordFormType extends AbstractType
{
    const string CLASS_PATTERN = 'w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500';
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Obecne hasło',
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Proszę podać obecne hasło',
                    ]),
                ],
                'attr' => [
                    'class' => self::CLASS_PATTERN
                ]
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Nowe hasło',
                    'attr' => [
                        'class' => self::CLASS_PATTERN
                    ]
                ],
                'second_options' => [
                    'label' => 'Powtórz nowe hasło',
                    'attr' => [
                        'class' => self::CLASS_PATTERN
                    ]
                ],
                'invalid_message' => 'Nowe hasła muszą być identyczne',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Proszę podać nowe hasło',
                    ]),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Hasło musi mieć co najmniej {{ limit }} znaków',
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
