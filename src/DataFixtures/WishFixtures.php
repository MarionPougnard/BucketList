<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WishFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
    public function load(ObjectManager $manager): void
    {
        $wishes = [
            [
                'title' => 'Souhait 1',
                'description' => 'Saut en parachute avant 40 ans',
                'author' => 'FabPot',
                'is_published' => true,
                'date_created' => new \DateTime('2024-08-21 09:59:30'),
                'category'=>3,
            ],
            [
                'title' => 'Souhait 2',
                'description' => 'Faire une promenade à cheval. Prendre contact avec le centre équestre',
                'author' => 'Titi',
                'is_published' => true,
                'date_created' => new \DateTime('2024-08-20 09:59:30'),
                'category'=>2,
            ],
            [
                'title' => 'Souhait 3',
                'description' => 'Aller au japon en voyage lowcost, en prenant le transibérien',
                'author' => 'Titi',
                'is_published' => true,
                'date_created' => new \DateTime('2024-08-20 10:59:30'),
                'category'=>1,
            ],
            [
                'title' => 'Souhait 4',
                'description' => 'Dormir avec une biche (spécial dédicace à Julian)',
                'author' => 'Titi',
                'is_published' => true,
                'date_created' => new \DateTime('2024-08-18 09:59:30'),
                'category'=>1,
            ],
            [
                'title' => 'Souhait 5',
                'description' => 'nager avec un goëlan',
                'author' => 'FabPot',
                'is_published' => false,
                'date_created' => new \DateTime('2024-08-18 09:59:30'),
                'category'=>1,
            ],
            [
                'title' => 'Souhait 6',
                'description' => "Laver les dents d'un crocodile",
                'author' => 'Titi',
                'is_published' => false,
                'date_created' => new \DateTime('2024-08-18 09:59:30'),
                'category'=>3,
            ],
        ];

        foreach ($wishes as $wishData) {
            $wish = new Wish();
            $wish   ->setTitle($wishData['title'])
                    ->setDescription($wishData['description'])
                    ->setAuthor($wishData['author'])
                    ->setPublished($wishData['is_published'])
                    ->setDateCreated($wishData['date_created'])
                    ->setCategory($this->getReference('category_' . $wishData['category']));
            $manager->persist($wish);
        }

        $manager->flush();
    }
}
