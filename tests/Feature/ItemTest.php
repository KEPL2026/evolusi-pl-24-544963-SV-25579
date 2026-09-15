<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_item(): void
    {
        $item = \App\Models\Item::create([
            'name' => 'Test Item',
            'description' => 'This is a test item.',
        ]);

        $this->assertDatabaseHas('items', [
            'name' => 'Test Item',
        ]);
    }
}
