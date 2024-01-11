<?php

namespace App\DataFixtures;

use App\Factory\TreasureFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TreasureFactory::createMany(40);
    }
}