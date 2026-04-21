<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Gerant;

class EntrepriseFactory extends Factory
{
    // ── Données ivoiriennes ──────────────────────────────────────

    private static array $nomsEntreprises = [
        'SIMAT CI', 'PRODEX Abidjan', 'SOGECOM Ivoire', 'COTEXI SARL',
        'BATISCO', 'IVOIRE TECH', 'AGRIPLUS CI', 'TRANSIVOIRE',
        'CIBTP Côte d\'Ivoire', 'AFRICOM Services', 'SAVANE Négoce',
        'COOPEC Distribution', 'NÉKÉDIÉ Import-Export', 'ABISSA Commerce',
        'GOLFE BTP', 'CAURI Holding', 'NIOZAN Industries', 'KOTIA Santé',
        'TIASSALÉ Agricole', 'ÉBURNIE Services', 'LAGUNE Tourisme',
        'GRAND BASSAM Négoce', 'MANDÉ Commerce', 'AKAN Industrie',
        'DIOULA Trading', 'ABOKOME SARL', 'PALMERAIE Industries',
        'CACAO & CO', 'CAFÉ IVOIRE', 'ANACARDE Export',
    ];

    private static array $adresses = [
        'Zone Industrielle de Yopougon, Abidjan',
        'Plateau, Avenue Terrasson de Fougères, Abidjan',
        'Treichville, Rue 12, Abidjan',
        'Marcory Résidentiel, Abidjan',
        'Cocody 2 Plateaux, Abidjan',
        'Adjamé, Boulevard Nangui Abrogoua, Abidjan',
        'Koumassi, Zone Industrielle, Abidjan',
        'Port-Bouët, Quartier Aviation, Abidjan',
        'Centre commercial, Bouaké',
        'Avenue Houphouët-Boigny, Daloa',
        'Quartier Commerce, Korhogo',
        'Zone Franche, San-Pédro',
        'Centre-ville, Yamoussoukro',
        'Quartier Résidentiel, Gagnoa',
        'Boulevard du Commerce, Abengourou',
    ];

    public function definition(): array
    {
        return [
            'gerant_id'        => Gerant::factory(),
            'nom'              => $this->faker->randomElement(self::$nomsEntreprises)
                                  . ' ' . $this->faker->numerify('##'),
            'rccm'             => 'CI-ABJ-' . now()->year . '-B-' . $this->faker->unique()->numerify('#####'),
            'adresse'          => $this->faker->randomElement(self::$adresses),
            'type_entreprise'  => $this->faker->randomElement([
                'SARL', 'SA', 'SAS', 'Entreprise individuelle', 'GIE', 'SUARL',
            ]),
            'secteur_activite' => $this->faker->randomElement([
                'Commerce', 'Industrie', 'BTP', 'Informatique',
                'Santé', 'Tourisme', 'Agriculture', 'Transport',
                'Communication', 'Service', 'Éducation', 'Finance',
            ]),
        ];
    }
}
