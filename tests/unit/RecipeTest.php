<?php

namespace App\Tests\Unit;

use App\Entity\Recipe;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeTest extends KernelTestCase
{
    public function testSomething(): void
    {
        self::bootKernel();
        $container= static::getContainer();
        $recipe=new Recipe();

        $recipe->setName('recipe#1')
                ->setDescription('Description#1')
                ->setIsFavorite(true)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());
        $error=$container->get('validator')->validator($recipe);

        $this->assertCount(1, $error);
    }
}
