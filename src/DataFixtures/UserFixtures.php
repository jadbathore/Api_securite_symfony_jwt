<?php

namespace App\DataFixtures;

use App\Factory\CompanyFactory;
use App\Factory\ProjetFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    /**
     * @info : data fixture permettant de crée 2 utilisateur user1@local.host et user2@local.host 
     * crée à part 10 entreprises c'est entreprise auront 1 utilisateur au hazard (pour le test j'ai effectuer un relation ManytoMany en option pour User et company) et c'est 
     * entreprises auront de 1 à 5 projets dans leur répertoire 
     */
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne(['email' => 'user1@local.host']);
        UserFactory::createOne(['email' => 'user2@local.host']);
        CompanyFactory::new()
        ->many(10)
        ->create(function(){
            return [
                'User'=> UserFactory::random(),
                'projets'=> ProjetFactory::new()->range(1,5)
            ];
        });
        $manager->flush();
    }
}
