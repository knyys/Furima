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
     * 支払い方法選択画面で変更が即時反映されることを確認するテスト
     *
     * @return void
     */
    public function testPaymentMethodIsReflectedImmediatelyOnSubtotalScreen()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーがログイン
        $this->actingAs($user);

        // 商品購入画面に遷移（仮のルート）
        $response = $this->get(route('payment.method.select'));
        $response->assertStatus(200); // 支払い方法選択画面が表示されることを確認

        // プルダウンメニューから支払い方法を選択（仮の支払い方法 'credit_card'）
        $response = $this->post(route('payment.method.store'), [
            'payment_method' => 'credit_card'
        ]);

        // 変更が即時反映されることを確認
        $response->assertSessionHas('payment_method', 'credit_card'); // セッションに選択した支払い方法が保存されていることを確認

        // 小計画面（仮のルート）を表示し、選択した支払い方法が反映されているか確認
        $response = $this->get(route('payment.subtotal'));
        $response->assertSee('credit_card'); // 小計画面に選択した支払い方法が表示されることを確認
    }
}

