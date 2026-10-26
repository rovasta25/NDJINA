<?php

namespace App\Support;

class GabonCities
{
    /**
     * Approximate city centres used for demo data and distance fallbacks.
     *
     * @var array<string, array{0: float, 1: float}>
     */
    public const CENTRES = [
        'Libreville' => [0.4162, 9.4673],
        'Akanda' => [0.5231, 9.4902],
        'Owendo' => [0.3000, 9.5000],
        'Ntoum' => [0.3833, 9.7667],
        'Franceville' => [-1.6333, 13.5833],
        'Port-Gentil' => [-0.7193, 8.7815],
        'Oyem' => [1.5993, 11.5793],
        'Lambarene' => [-0.7001, 10.2406],
        'Lambaréné' => [-0.7001, 10.2406],
        'Mouila' => [-1.8686, 11.0556],
        'Koulamoutou' => [-1.1367, 12.4667],
        'Makokou' => [0.5738, 12.8642],
        'Bitam' => [2.0833, 11.5000],
        'Moanda' => [-1.5667, 13.2000],
        'Gamba' => [-2.6500, 10.0000],
        'Tchibanga' => [-2.9333, 11.0000],
    ];

    /**
     * @return array{0: float, 1: float}|null
     */
    public static function coords(string $city): ?array
    {
        return self::CENTRES[$city] ?? null;
    }
}
