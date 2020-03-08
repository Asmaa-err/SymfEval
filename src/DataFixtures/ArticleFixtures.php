<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Faker\Factory as Faker;
use App\DataFixtures\ContinentFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker::create();
        $arrayImg= array('bg1.jpg', 'bg3.jpg', 'chine.jpg','maroc.jpg','italie.jpg','LA.jpg');
        for ($i = 0; $i <6; $i++){
            $article=new Article();
            $article->setTitre( $faker->sentence(3) );
            $article->setDescription( $faker->text(200) );
            $article->setImage($arrayImg[$i]);

            $randomContinent = random_int(0, 4);
		    $continent = $this->getReference("c$randomContinent");

		    // associer une catégorie au produit
		    $article->setConti($continent);

            $manager->persist($article);
            }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ContinentFixtures::class
        ];
    }
}


