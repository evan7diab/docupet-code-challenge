<?php

namespace Tests\Unit;

use App\Traits\NormalizesPagination;
use Tests\TestCase;

class NormalizesPaginationTraitTest extends TestCase
{
    private object $traitUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create anonymous class that uses the trait and exposes the method
        $this->traitUser = new class
        {
            use NormalizesPagination;

            public function testNormalizePerPage(?int $perPage): int
            {
                return $this->normalizePerPage($perPage);
            }
        };

        // Set config values for testing
        config([
            'pagination.default_per_page' => 15,
            'pagination.min_per_page' => 1,
            'pagination.max_per_page' => 100,
        ]);
    }

    public function test_returns_default_when_null(): void
    {
        $result = $this->traitUser->testNormalizePerPage(null);

        $this->assertEquals(15, $result);
    }

    public function test_returns_value_when_within_range(): void
    {
        $result = $this->traitUser->testNormalizePerPage(25);

        $this->assertEquals(25, $result);
    }

    public function test_returns_min_when_below_minimum(): void
    {
        $result = $this->traitUser->testNormalizePerPage(0);

        $this->assertEquals(1, $result);
    }

    public function test_returns_min_when_negative(): void
    {
        $result = $this->traitUser->testNormalizePerPage(-5);

        $this->assertEquals(1, $result);
    }

    public function test_returns_max_when_above_maximum(): void
    {
        $result = $this->traitUser->testNormalizePerPage(200);

        $this->assertEquals(100, $result);
    }

    public function test_returns_exact_min_boundary(): void
    {
        $result = $this->traitUser->testNormalizePerPage(1);

        $this->assertEquals(1, $result);
    }

    public function test_returns_exact_max_boundary(): void
    {
        $result = $this->traitUser->testNormalizePerPage(100);

        $this->assertEquals(100, $result);
    }

    public function test_respects_custom_config_values(): void
    {
        config([
            'pagination.default_per_page' => 20,
            'pagination.min_per_page' => 5,
            'pagination.max_per_page' => 50,
        ]);

        $this->assertEquals(20, $this->traitUser->testNormalizePerPage(null));
        $this->assertEquals(5, $this->traitUser->testNormalizePerPage(1));
        $this->assertEquals(50, $this->traitUser->testNormalizePerPage(100));
        $this->assertEquals(30, $this->traitUser->testNormalizePerPage(30));
    }
}
