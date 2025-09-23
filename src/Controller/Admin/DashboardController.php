<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Person;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\Grade;
use App\Entity\Group;
use App\Entity\Address;
use App\Entity\StudentParent;
use App\Entity\Homework;
use App\Entity\Schedule;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin-panel', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        // Przekierowanie do zarządzania użytkownikami jako domyślna strona
        return $this->redirectToRoute('admin_user_index');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('GradeBook - Panel Administracyjny')
            ->setFaviconPath('favicon.ico')
            ->generateRelativeUrls()
            ->setDefaultColorScheme('auto');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Zarządzanie użytkownikami');
        yield MenuItem::linkToCrud('Użytkownicy', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Osoby', 'fas fa-user', Person::class);
        yield MenuItem::linkToCrud('Adresy', 'fas fa-map-marker-alt', Address::class);

        yield MenuItem::section('Szkoła');
        yield MenuItem::linkToCrud('Uczniowie', 'fas fa-graduation-cap', Student::class);
        yield MenuItem::linkToCrud('Nauczyciele', 'fas fa-chalkboard-teacher', Teacher::class);
        yield MenuItem::linkToCrud('Rodzice/Opiekunowie', 'fas fa-user-friends', StudentParent::class);
        yield MenuItem::linkToCrud('Klasy/Grupy', 'fas fa-users-class', Group::class);

        yield MenuItem::section('Edukacja');
        yield MenuItem::linkToCrud('Oceny', 'fas fa-star', Grade::class);
        yield MenuItem::linkToCrud('Zadania domowe', 'fas fa-tasks', Homework::class);
        yield MenuItem::linkToCrud('Plan zajęć', 'fas fa-calendar', Schedule::class);
    }
}
