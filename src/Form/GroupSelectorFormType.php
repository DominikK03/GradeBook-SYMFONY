<?php

namespace App\Form;

use App\Service\GradeBookService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupSelectorFormType extends AbstractType
{
    public function __construct(private readonly GradeBookService $gradeBookService)
    {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $groupChoices = [];
        $groups = $this->gradeBookService->getGroupsAssignedToTeacher($options['teacherID']);
        foreach ($groups as $group) {
            $groupChoices[$group->getName()->value] = $group->getName()->value;
        }
        $builder
            ->add('group', ChoiceType::class, [
                'choices' => $groupChoices,
                'placeholder' => 'Wybierz grupę'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('teacherID');
    }
}
