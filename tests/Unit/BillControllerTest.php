<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\BillStage;
use App\Models\User;
use Tests\TestCase;

class BillControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Run the migrations to ensure the database is in a fresh state
        $this->artisan('migrate');
    }
    public function test_get_bills()
    {
        $response = $this->getJson('/api/v1/Bills');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'total_submitted',
                'total_approved',
                'total_on_hold',
                'user_stats',
            ]);
    }

    public function test_add_bill()
    {
        $stage = BillStage::first();
        $response = $this->postJson('/api/v1/addBill', [
            'bill_reference' => 'Test Bill',
            'bill_date' => '2023-01-01',
            'bill_stage_id' => $stage->id,
        ]);

        $response->assertStatus(201)
                    ->assertJson([
                        'data' => [
                            'bill_reference' => 'Test Bill',
                            'bill_date' => '2023-01-01',
                            'stage' => [
                                'label' => 'Draft',
                                'color_name' => 'gray',
                            ],
                        ],
                    ]);
    }
}
