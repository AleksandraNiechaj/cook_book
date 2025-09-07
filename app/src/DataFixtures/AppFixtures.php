<?php

declare(strict_types=1);

/**
 * Klasa odpowiedzialna za ładowanie przykładowych danych do bazy.
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Klasa odpowiedzialna za ładowanie przykładowych danych do bazy.
 */
class AppFixtures extends Fixture
{
    /**
     * Ładuje dane do bazy danych.
     *
     * @param ObjectManager $manager menedżer encji Doctrine
     */
    public function load(ObjectManager $manager): void
    {
        // przykładowe dane
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
