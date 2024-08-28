<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = [
            ['name' => 'Travel & Adventure'],
            ['name' => 'Sport'],
            ['name' => 'Entertainment'],
            ['name' => 'Human Relations'],
            ['name' => 'Others'],
        ];

        $i = 1;

        foreach ($category as $categoryData) {
            $cat = new Category();
            $cat->setName($categoryData['name']);
            $this->addReference('category_' . $i, $cat);
            $manager->persist($cat);

            $i++;
        }

        $manager->flush();
    }
}
