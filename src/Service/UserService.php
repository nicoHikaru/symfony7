<?php

namespace App\Service;


use Faker\Factory;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(private UserRepository $userRepository){}

    public function insertFakeData(EntityManagerInterface $entityManager,$limit = 1000):void
    {
        $faker = Factory::create();
        $batchSize = 100;

        for ($i = 0 ; $i < $limit ; $i++) {
            
            $user = new User();
            $user->setEmail($faker->unique()->safeEmail);
            $user->setName($faker->name());
            $user->setScore($faker->optional()->passthrough(mt_rand(5, 1500)));
            $user->setPassword($faker->password());

            $entityManager->persist($user);

            // Flusher par lot
            if (($i + 1) % $batchSize === 0) {
                $entityManager->flush();
                $entityManager->clear(); // Libère la mémoire
            }
        }

        // Flusher les utilisateurs restants
        $entityManager->flush();
        $entityManager->clear();
    }

    public function getAllUser():array
    {
        return $this->userRepository->getAllUser();
    }

    public function getUserById(int $id):array
    {
        return $this->userRepository->getUserById($id);
    }

    public function getUserSById(array $id):array
    {
        return $this->userRepository->getUserSById($id);
    }
}