<?php

namespace App\Service;
use App\Entity\Person;
use App\Entity\User;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\RuntimeException;
use Symfony\Bundle\SecurityBundle\Security;
use Webmozart\Assert\Assert;

final class UserDataService
{
    public function __construct(private readonly Security $security)
    {
    }

    public function getPerson() : Person
    {
        return $this->getCurrentUser()->getPerson();
    }

    public function parseUserData(Person $person) : array
    {
        return [
            'firstName' => $person->getFirstName(),
            'secondName' => $person->getSecondName(),
            'lastName' => $person->getLastName(),
            'pesel' => $person->getPesel(),
            'birthDate' => $person->getBirthDate()->format("Y-m-d"),
            'phoneNumber' => $person->getPhoneNumber(),
            'email' => $this->getCurrentUser()->getEmail(),
            'role' => $this->getCurrentUser()->getRoles(),
            'country' => $person->getAddress()->getCountry(),
            'voivodeship' => $person->getAddress()->getVoivodeship(),
            'city' => $person->getAddress()->getCity(),
            'street' => $person->getAddress()->getStreet(),
            'postalCode' => $person->getAddress()->getPostalcode(),
            'houseNumber' => $person->getAddress()->getAddressNumber()
        ];
    }

    public function getCurrentUser(): User
    {
        $user = $this->security->getUser();
        if (!$user instanceof User){
            throw new RuntimeException('User not found');
        }
        return $user;
    }
}
