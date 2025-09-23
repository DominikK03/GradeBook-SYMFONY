<?php

namespace App\DataFixtures;

use App\DataFixtures\Trait\AddressReferenceTrait;
use App\DataFixtures\Trait\UserReferenceTrait;
use App\Entity\Person;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PersonFixtures extends Fixture implements DependentFixtureInterface
{
    use AddressReferenceTrait;
    use UserReferenceTrait;

    public const FIRST_NAMES = [
            'Jan',
            'Anna',
            'Piotr',
            'Katarzyna',
            'Andrzej',
            'Maria',
            'Tomasz',
            'Magdalena',
            'Marcin',
            'Agnieszka',
    ];
    public const LAST_NAMES = [
            'Kowalski',
            'Nowak',
            'Wiśniewski',
            'Wójcik',
            'Kowalczyk',
            'Kamiński',
            'Lewandowski',
            'Zieliński',
            'Szymański',
            'Woźniak',
    ];
    public const REFERENCE_PATTERN = 'person_%s';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('pl_PL');

        // Dodaj specjalną Person dla admina
        $adminPerson = new Person();
        $adminPerson->setId(1);
        $adminPerson->setFirstName('Adam');
        $adminPerson->setLastName('Administrator');
        $adminPerson->setPesel('85010112345');
        $adminPerson->setBirthDate(new \DateTime('1985-01-01'));
        $adminPerson->setPhoneNumber('123456789');
        $adminPerson->setNationality('Polska');
        $adminPerson->setAddress($this->getAddress(1));

        $manager->persist($adminPerson);
        $this->addReference(sprintf(self::REFERENCE_PATTERN, 1), $adminPerson);

        // Dodaj pozostałe osoby
        for ($i = 2; $i <= 20; $i++) {
            $person = new Person();
            $person->setId($i);
            $person->setFirstName($faker->randomElement(self::FIRST_NAMES));
            $person->setLastName($faker->randomElement(self::LAST_NAMES));
            $person->setPesel("0".$faker->unique()->randomNumber(9)."0");
            $person->setBirthDate($faker->dateTimeBetween('-50 years', '-18 years'));
            $person->setPhoneNumber((string)$faker->randomNumber(9));
            $person->setNationality("Polska");
            $person->setAddress($this->getAddress($i));

            $manager->persist($person);
            $this->addReference(sprintf(self::REFERENCE_PATTERN, $person->getId()), $person);
        }
        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            AddressFixtures::class,
            UserFixtures::class
        ];
    }
}


