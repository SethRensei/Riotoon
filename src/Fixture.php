<?php

require_once '../vendor/autoload.php';

use Riotoon\Repository\UserRepository;

$faker = Faker\Factory::create('fr_FR');

$repository = new UserRepository();
/* 
 If you want to use this file, you need to write your database information
 in the DbConnexion file and not to use environment variables
 Example :
    $host = 'localhost';
    $name = 'your name's database';
    $user = 'root';
    $pass = 'your password';
*/
for ($i = 0; $i < 50; $i++) {
    $repository->setPseudo(replace($faker->unique()->userName()))
        ->setFullname($faker->lastName() . ' ' . $faker->firstName())
        ->setEmail($faker->unique()->email())
        ->setPassword('user@2024')
        ->setRoles(['ROLE_USER'])
        ->updateConfirKey()
        ->setIsVerified(mt_rand(0, 1));
    $repository->add();
}
// To execute the file Fixture.php as a PHP script from the command line
// you can use the following command
// php src/Fixture.php