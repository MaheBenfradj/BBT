<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $admin = new User();
        $admin->setPassword($this->passwordHasher->hashPassword(
            $admin, 'bbbrunes225'));
        $admin->setUsername('Administrateur BBT');
        $admin->setRoles(["ROLE_ADMIN", "ROLE_USER"]);
        $admin->setEmail("bestbodytravel@gmail.com");
        //  $admin->setDateInscription( new \DateTime('now'));
        $manager->persist($admin);
        $manager->flush();
    }
}
