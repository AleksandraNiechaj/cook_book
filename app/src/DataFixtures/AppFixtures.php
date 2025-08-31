<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 *
 * PHP version 8.3
 *
 * @author    Aleksandra Niechaj <aleksandra.niechaj@example.com>
 *
 * @copyright 2025 Aleksandra Niechaj
 *
 * @license   For educational purposes (course project).
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
