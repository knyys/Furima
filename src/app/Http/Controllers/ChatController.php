<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SendMessageRequest;
use App\Models\Chat;
use App\Models\Item;
use App\Models\Sold;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ChatController extends Controller
{
    /**
     * Show the chat view.
     *
     * @return \Illuminate\View\View
     */

    // 購入者用チャット画面
    public function chatView($id)
    {
        $item = Item::with(['solds', 'user.profile'])->findOrFail($id);
    $sold = $item->solds->first();
    $items = Item::with('solds')
    ->where('user_id', auth()->id())
    ->whereHas('solds') // 購入された（売れた）商品だけ
    ->get();

    // ログインチェック＆出品者または購入者であることの確認
    if (!auth()->check() || (auth()->id() !== $item->user_id && auth()->id() !== $sold->user_id)) {
        abort(403, 'このページにはアクセスできません');
    }

    // 相手ユーザーID（自分が出品者なら購入者、購入者なら出品者）
    $opponentUserId = auth()->id() === $item->user_id ? $sold->user_id : $item->user_id;

    // チャット一覧（自分が送信 or 相手が送信）
    $chats = Chat::with('user.profile')
        ->where('item_id', $item->id)
        ->where(function ($query) use ($opponentUserId) {
            $query->where('user_id', auth()->id())
                  ->orWhere('user_id', $opponentUserId);
        })
        ->orderBy('created_at', 'asc')
        ->get();

    return view('chat', [
        'item' => $item,
        'sold' => $sold,
        'chats' => $chats,
        'items' => $items,
    ]);
    }

    public function sendMessage(SendMessageRequest $request)
    {
        $request->validated();
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_images', 'public');
        }

        Chat::create([
            'message' => $request->input('message'),
            'image' => $imagePath,
            'user_id' => auth()->id(),
            'item_id' => $request->input('item_id'),
            'to_user_id' => $request->input('to_user_id'),
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('chatView', ['item' => $request->input('item_id')])
            ;
    }





    public function completeDeal(Request $request)
    {
        // 取引完了の処理をここに実装
        // 例: データベースの更新、通知の送信など

        return redirect()->back()->with('status', '取引が完了しました。');
    }
}
