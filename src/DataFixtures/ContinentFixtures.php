<?php

namespace App\DataFixtures;

use App\Entity\Continent;
use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;

class ContinentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker::create();
        // création de plusieurs catégories
        $arrayCont= array('Asie', 'Europe', 'Afrique','Amérique','Océanie');
        for($i = 0; $i < 5; $i++) {
            // instanciation d'une entité
            $continent = new Continent();
            
            $continent->setName($arrayCont[$i]);

            
            $this->addReference("c$i", $continent);

            // doctrine : méthode persist permet de créer un enregistrement (INSERT INTO)
            $manager->persist($continent);
        }

        // doctrine : méthode flush permet d'exécuter les requêtes SQL (à exécuter une seule fois)
        $manager->flush();
    }
}