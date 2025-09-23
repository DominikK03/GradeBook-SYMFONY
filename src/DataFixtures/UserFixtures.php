<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USERS = [
            'admin' => [
                    'email' => 'admin@poczta.pl',
                    'password' => 'abc123',
                    'roles' => [Role::ADMIN->value],
            ],
            'teacher' => [
                    'email' => 'teacher@poczta.pl',
                    'password' => 'abc123',
                    'roles' => [Role::TEACHER->value],
            ],
            'student' => [
                    'email' => 'student@poczta.pl',
                    'password' => 'abc123',
                    'roles' => [Role::STUDENT->value],
            ],
            'parent' => [
                    'email' => 'parent@poczta.pl',
                    'password' => 'abc123',
                    'roles' => [Role::PARENT->value],
            ],
    ];

    public const REFERENCE_PATTERN = 'user-%s';
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $userKey => $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $userData['password']));
            $user->setRoles($userData['roles']);
            $manager->persist($user);
            $this->addReference(
                    sprintf(
                            self::REFERENCE_PATTERN,
                            $userKey
                    ),
                    $user
            );
        }
        $manager->flush();
    }
}
