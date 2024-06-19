<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private readonly Generator $faker;

    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $this->addUsers(50, $manager);
//        $this->addSeries(50, $manager);
    }

    private function addUsers(int $number, ObjectManager $manager)
    {
        for ($i = 0; $i < $number; $i++) {
            $user = new User();
            $user->setFirstname($this->faker->firstName())
                ->setLastname($this->faker->lastName())
                ->setRoles(['ROLE_USER'])
                ->setEmail($this->faker->email())
                ->setPassword(
                    $this->userPasswordHasher->hashPassword(
                        $user,
                        '1234'
                    )
                );

            $manager->persist($user);
        }
        $manager->flush();
    }

    private function addSeries(int $number, ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < $number; $i++) {

            $serie = new Serie();
            $serie
                ->setName("Serie $i")
                ->setBackdrop("backdrop.png $i")
                ->setDateCreated($faker->dateTimeBetween("-2 year", "-6 month"))
                ->setGenres($faker->randomElement(["Fantasy", "Polar", "Western", "Action", "Aventure"]))
                ->setFirstAirDate($faker->dateTimeBetween("-2 year", "-1 year"));
            $serie->setLastAirDate($faker->dateTimeBetween($serie->getFirstAirDate(), "now"))
                ->setPopularity($faker->numberBetween(0, 1000))
                ->setPoster("poster.png")
                ->setStatus($faker->randomElement(["returning", "canceled", "ended"]))
                ->setTmdbId($faker->randomDigit())
                ->setVote($faker->numberBetween(1, 9));

            $manager->persist($serie);
        }
        $manager->flush();

    }


}
