<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        //$this->addSeries();
        $this->addUsers(50);
    }

    public function addSeries(){
        for ($i=0; $i<50; $i++){
            $serie = new Serie();
            $serie
                ->setName(implode($this->faker->words(3)))
                ->setVote($this->faker->numberBetween(0, 10))
                ->setStatus($this->faker->randomElement(["ended", "returning", "canceled"]))
                ->setPoster("poster.png")
                ->setTmdbId(123)
                ->setPopularity("150.3")
                ->setFirstAirDate($this->faker->dateTimeBetween("-6 months"))
                ->setLastAirDate($this->faker->dateTimeBetween($serie->getFirstAirDate()))
                ->setGenres($this->faker->randomElement(['Comedy', 'Western', 'Drama']))
                ->setBackdrop("backdrop.png");
            $this->entityManager->persist($serie);
        }
        $this->entityManager->flush();
    }

    public function addUsers(int $nb)
    {
        //bonne pratique: on crée 1 utilisateur admin avec attributs que l'on connaît
        /*$user = new User();
        $user
            ->setRoles(['ROLE_ADMIN'])
            ->setEmail('abc@mail.com')
            ->setFirstName('abc')
            ->setLastName('abc')
            ->setPassword($this->passwordHasher->hashPassword($user, '123'));
        $this->entityManager->persist($user);*/

        for ($i=0; $i<$nb; $i++){
            $user = new User();
            $user
                ->setRoles(['ROLE_USER'])
                ->setEmail($this->faker->email)
                ->setFirstName($this->faker->firstName)
                ->setLastName($this->faker->lastName)
                ->setPassword($this->passwordHasher->hashPassword($user, '123'));
            $this->entityManager->persist($user);
        }
        $this->entityManager->flush();
    }


}
