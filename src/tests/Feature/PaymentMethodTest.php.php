<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @return void
     */
    public function testPaymentMethodIsReflectedImmediatelyOnSubtotalScreen()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('purchase'));
        $response->assertStatus(200); 

        $response = $this->post(route('purchase'), [
            'payment_method' => 'credit_card'
        ]);

        $response->assertSessionHas('payment_method', 'credit_card'); 

        $response = $this->get(route('purchase'));
        $response->assertSee('credit_card'); 
    }
}

