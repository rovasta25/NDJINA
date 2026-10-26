<?php

namespace Tests\Unit;

use App\Models\Alerte;
use PHPUnit\Framework\TestCase;

class AlerteDistanceTest extends TestCase
{
    public function test_distance_between_two_libreville_points_is_in_expected_range(): void
    {
        $km = Alerte::distanceKm(0.4162, 9.4673, 0.5200, 9.5000);

        $this->assertNotNull($km);
        $this->assertGreaterThan(10.0, $km);
        $this->assertLessThan(14.0, $km);
    }

    public function test_distance_is_null_without_coordinates(): void
    {
        $this->assertNull(Alerte::distanceKm(0.4162, 9.4673, null, null));
        $this->assertNull(Alerte::distanceKm(null, null, 0.4162, 9.4673));
    }

    public function test_distance_formatting(): void
    {
        $this->assertSame('450 m', Alerte::formatDistance(0.45));
        $this->assertSame('2,3 km', Alerte::formatDistance(2.34));
        $this->assertSame('12,0 km', Alerte::formatDistance(12.0));
    }
}
