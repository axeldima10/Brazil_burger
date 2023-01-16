<?php

namespace App\DataFixtures;

use App\Entity\Commande;
use App\Entity\Gestionnaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $gestionnaire = new Gestionnaire();
            $gestionnaire->setEmail("gestionnaire" . $i . "@gmail.com")
            ->setNomComplet("gestionnaire " . $i)
            ->setPassword($this->encoder->hashPassword($gestionnaire, "passe"))
            ->setRoles(["ROLE_GESTIONNAIRE", "ROLE_CLIENT"]);
            $manager->persist($gestionnaire);
            $this->addReference("gestionnaire " . $i, $gestionnaire);
        }

        $manager->flush();
    }
}
