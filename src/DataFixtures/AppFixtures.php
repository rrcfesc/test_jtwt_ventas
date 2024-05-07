<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $user = (new User())->setUsername('test')->setRoles(['ROLE_STUDENT'])->setName('Student');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
        $manager->persist($user);
        $manager->flush();

        $faker = Factory::create();

        for($i = 0; $i < 10; $i++) {
            $product = (new Product())
                ->setName(sprintf('product name %s', $i))
                ->setCode(sprintf('CODE_%s', $i))
                ->setStock($i)
                ->setInitialPrice((float) $i)
                ->setEndPrice((float) $i)
            ;
            $manager->persist($product);
        }

        $manager->flush();
    }
}
