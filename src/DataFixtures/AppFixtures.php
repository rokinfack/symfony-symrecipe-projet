<?php

namespace App\DataFixtures;


use Faker\Factory;
use Faker\Generator;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private Generator $faker;
    public function __construct()
    {
        $this->faker= Factory::create('fr-FR');
    }
   
    public function load(ObjectManager $manager): void
    {
        
        for($i=0; $i<100; $i++){

            $ingredient= new Ingredient();

            $ingredient->setName($this->faker->name());
            $ingredient->setPrice($this->faker->randomNumber(2));
            $manager->persist($ingredient);
        }
        $manager->flush();
    }
}
