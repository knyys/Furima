<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SendMessageRequest;
use App\Models\Chat;
use App\Models\Item;
use App\Models\Sold;
use App\Models\User;
use App\Mail\RatingCompletedMail;
use Illuminate\Support\Facades\Mail;

class ChatController extends Controller
{
    /**
     * Show the chat view.
     *
     * @return \Illuminate\View\View
     */

    // チャット画面
    public function chatView($id)
    {
        $item = Item::with(['solds', 'user.profile'])->findOrFail($id);
        $sold = $item->solds->first();
        $items = Item::with('solds')
        ->where('user_id', auth()->id())
        ->whereHas('solds')
        ->get();

        // 出品者か購入者か確認
        if (!auth()->check() || (auth()->id() !== $item->user_id && auth()->id() !== $sold->user_id)) {
            abort(403, 'このページにはアクセスできません');
        }

        $opponentUserId = auth()->id() === $item->user_id ? $sold->user_id : $item->user_id;

        Chat::where('item_id', $item->id)
        ->where('user_id', $opponentUserId) 
        ->where('is_read', false)
        ->update(['is_read' => true]);

        $chats = Chat::with('user.profile')
            ->where('item_id', $item->id)
            ->where(function ($query) use ($opponentUserId) {
                $query->where('user_id', auth()->id())
                    ->orWhere('user_id', $opponentUserId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
            
        $isSeller = auth()->id() === $item->user_id;
        // 購入者が出品者を評価したか
        $buyerRatedSeller = \App\Models\Rating::where('item_id', $item->id)
            ->where('rater_id', $sold->user_id)
            ->where('rated_id', $item->user_id)
            ->exists();
        // 出品者が購入者を評価したか
        $alreadyRated = \App\Models\Rating::where('item_id', $item->id)
        ->where('rater_id', auth()->id())
        ->exists();

        $isBuyer = auth()->id() === $sold->user_id;

        // モーダル表示
        $showModal = $isSeller && $buyerRatedSeller && !$alreadyRated;

        return view('chat', [
            'item' => $item,
            'sold' => $sold,
            'chats' => $chats,
            'items' => $items,
            'alreadyRated' => $alreadyRated,
            'buyerRatedSeller' => $buyerRatedSeller,
            'showModal' => $showModal,
            'isBuyer' => $isBuyer,
        ]);
    }

    // チャット送信
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

        return redirect()->route('chatView', ['item' => $request->input('item_id')]);
    }

    // メッセージ削除
    public function deleteMessage(Request $request)
    {
        $chat = Chat::findOrFail($request->input('chat_id'));

        $chat->delete();

        return redirect()->route('chatView', ['item' => $request->input('item_id')]);
    }

    // メッセージ編集
    public function updateMessage(SendMessageRequest $request)
    {
        $request->validated();

        $chat = Chat::findOrFail($request->input('chat_id'));

        $chat->message = $request->input('message');
        $chat->save();

        return redirect()->route('chatView', ['item' => $request->input('item_id')]);
    }

    // 取引完了(評価）
    public function rating(SendMessageRequest $request)
    {
        $item = Item::findOrFail($request->input('item_id'));
        $sold = Sold::where('item_id', $item->id)->firstOrFail();

        //相手のユーザー
        $opponentUserId = auth()->id() === $item->user_id ? $sold->user_id : $item->user_id;
        $opponentUser = User::findOrFail($opponentUserId);

        $rating = $request->input('rating');

        // nullだったら0
        if (is_null($rating)) {
            $rating = 0;
        }

        // レーティングを保存
        $opponentUser->receivedRatings()->create([
            'rating' => $rating,
            'item_id' => $item->id,
            'rater_id' => auth()->id(),
            'rated_id' => $opponentUser->id,
        ]);

        if (auth()->id() === $sold->user_id) {
            Mail::to($item->user->email)->send(new RatingCompletedMail($item, auth()->user()));
        }

        return redirect()->route('home');
    }
}