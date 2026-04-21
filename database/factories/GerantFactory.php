<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class GerantFactory extends Factory
{
    // ── Données ivoiriennes ──────────────────────────────────────

    private static array $noms = [
        'Koné', 'Coulibaly', 'Traoré', 'Diallo', 'Ouattara',
        'Bamba', 'Touré', 'Konaté', 'Cissé', 'Diabaté',
        'Koффi', 'Assi', 'Yao', 'Amon', 'N\'Guessan',
        'Aké', 'Gnagne', 'Brou', 'Aka', 'Kouassi',
        'Dembélé', 'Sanogo', 'Fofana', 'Keïta', 'Camara',
    ];

    private static array $prenoms = [
        'Amara', 'Ibrahima', 'Moussa', 'Seydou', 'Drissa',
        'Adama', 'Boubacar', 'Mamadou', 'Souleymane', 'Yacouba',
        'Kouadio', 'Koffi', 'Yao', 'Kouamé', 'Aya',
        'Adjoua', 'Akissi', 'Affoué', 'Amenan', 'Bintou',
        'Fatoumata', 'Mariam', 'Rokia', 'Kadidiatou', 'Nana',
    ];

    private static array $contacts = [
        '+225 07', '+225 05', '+225 01',
    ];

    private static array $villes = [
        'Abidjan', 'Bouaké', 'Daloa', 'Korhogo', 'Man',
        'Yamoussoukro', 'San-Pédro', 'Gagnoa', 'Abengourou',
    ];

    public function definition(): array
    {
        $prefixe = $this->faker->randomElement(self::$contacts);
        $numero  = $prefixe . ' ' . $this->faker->numerify('## ## ## ##');

        return [
            'user_id'        => User::factory(),
            'nom'            => $this->faker->randomElement(self::$noms),
            'prenoms'        => $this->faker->randomElement(self::$prenoms),
            'contact'        => $numero,
            'piece_identite' => 'documents/cni_placeholder.pdf',
        ];
    }
}
