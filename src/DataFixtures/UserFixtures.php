<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    protected $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create("fr_FR");
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \FakerEcommerce\Ecommerce($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));


        $admin = new User;
        $hash = $this->encoder->hashPassword($admin, "password");
        $admin->setEmail('boum@boum.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($hash)
            ->setFullName($faker->name);
        $manager->persist($admin);

        for ($u = 0; $u < 9; $u++) {
            $user = new User;
            $user->setEmail($faker->email)
                ->setPassword($hash)
                ->setFullName($faker->name);
            $manager->persist($user);
        }


        $manager->flush();
    }
}
