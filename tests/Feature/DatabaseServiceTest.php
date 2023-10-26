<?php
// tests/Feature/DatabaseServiceTest.php

namespace Tests\Feature;

use App\DatabaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test querying the database.
     */
    public function testDatabaseQuery(): void
    {
    }
}
