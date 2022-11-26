<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Recipe;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
        $this->faker= Factory::create('fr-FR');
    }
    public function load(ObjectManager $manager,): void
    {
        //ingredient
        $ingredients=[];
        for($i=0; $i<100; $i++){

            $ingredient= new Ingredient();
            $ingredient->setName($this->faker->name());
            $ingredient->setPrice($this->faker->randomNumber(2));
            $ingredients[]=$ingredient;
            $manager->persist($ingredient);
        }
        // recipe

        for($j=0; $j<25; $j++)
        {
            $recipe= new Recipe();

            $recipe->setName($this->faker->name)
                    ->setTime(mt_rand(0,1)==1? mt_rand(1,1441):null)
                    ->setNbPeople(mt_rand(0,1)==1? mt_rand(1,51):null)
                    ->setDificulty(mt_rand(0,1)==1? mt_rand(1,5):null)
                    ->setDescription($this->faker->text(200))
                    ->setPrice(mt_rand(0,1)==1? mt_rand(1,1001):0)
                    ->setIsFavorite(mt_rand(0,1)==1? true:false);

                for($k=0; $k< mt_rand(5,15); $k++)
                {
                    $recipe->addIngredient($ingredients[mt_rand(0,count($ingredients)-1)]);
                }

                $manager->persist($recipe);
        }  
        //user
        for ($i=0; $i <10 ; $i++) { 
            $user=new User();
            $user->setFullName($this->faker->name)
                ->setPseudo(mt_rand(0,1) === 1 ? $this->faker->firstName():'null')
                ->setEmail($this->faker->email)
                ->setPassword($this->hasher->hashPassword($user,'password'))
                ->setRoles(['ROLE_USER']);
                $manager->persist($user);
            # code...
        }
        $manager->flush();
    }
}
