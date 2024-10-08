<?php

namespace App\DataFixtures;

use App\Entity\Person;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeamFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $team = [
  [
      'firstname' => 'Fabien',
    'lastname' => 'Potencier',
    'pseudo' => 'FabPot',
    'dateOfBirth' => new \DateTime('1979-02-22'),
    'creatorOf' => 'Symfony',
    'picture' => 'https://pbs.twimg.com/profile_images/1126770148092698625/nI58pL5O_200x200.png',
    'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam, cumque facilis harum id ipsam necessitatibus veniam! Adipisci alias delectus harum impedit ipsum, iste laboriosam natus possimus provident ratione velit voluptatibus? '
  ],
  [
      'firstname' => 'Taylor',
    'lastname' => 'Otwell',
    'pseudo' => 'Titi',
    'dateOfBirth' => new \DateTime('1982-10-10'),
    'creatorOf' => 'Laravel',
    'picture' => 'https://unavatar.vercel.app/github/taylorotwell',
    'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam, cumque facilis harum id ipsam necessitatibus veniam! Adipisci alias delectus harum impedit ipsum, iste laboriosam natus possimus provident ratione velit voluptatibus? '
  ],
  [
    'firstname' => 'Jordi',
    'lastname' => 'Boggiano',
    'pseudo' => 'JD',
    'dateOfBirth' => new \DateTime('1986-01-30'),
    'creatorOf' => 'Composer',
    'picture' => 'https://avatars.githubusercontent.com/u/183678?v=4',
    'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam, cumque facilis harum id ipsam necessitatibus veniam! Adipisci alias delectus harum impedit ipsum, iste laboriosam natus possimus provident ratione velit voluptatibus? '
  ]
];

        foreach ($team as $personData) {
            $person = new Person();
            $person ->setFirstname($personData['firstname'])
                    ->setLastname($personData['lastname'])
                    ->setPseudo($personData['pseudo'])
                    ->setDateOfBirth($personData['dateOfBirth'])
                    ->setCreatorOf($personData['creatorOf'])
                    ->setPicture($personData['picture'])
                    ->setDescription($personData['description']);
            $manager->persist($person);
        }

        $manager->flush();
    }
}
