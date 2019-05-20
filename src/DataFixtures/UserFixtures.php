<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $interface) {
        $this->passwordEncoder = $interface;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setEmail('admin@localhost');
        $user->setUsername('root');
        $user->setPassword($this->passwordEncoder->encodePassword($user,'root'));
        $manager->persist($user);

        $manager->flush();
    }
}
