<?php

/*
|--------------------------------------------------------------------------
| tests/Pest.php
|--------------------------------------------------------------------------
| Ce fichier est le point d'entrée de PEST.
| Il configure les tests pour qu'ils utilisent Laravel.
|
| "uses()" dit à PEST : "tous les tests dans ce dossier
| utilisent ces traits Laravel".
|
| RefreshDatabase = remet la base de données à zéro avant chaque test.
|--------------------------------------------------------------------------
*/

uses(
    Tests\TestCase::class,
    Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Feature');

// Les tests unitaires (Unit) n'ont pas besoin de la base de données
uses(Tests\TestCase::class)->in('Unit');
