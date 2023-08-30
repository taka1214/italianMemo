<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{

    public function index(Request $request)
    {
        $posts = Item::select('id', 'italian', 'japanese', 'voice_script', 'memo')->get();
        return view('index', compact('posts'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'italian' => 'required',
            'japanese' => 'required',
            'voice_script' => 'required|file|mimes:mp3,wav,ogg|max:5000', // 5MB以下のmp3, wav, ogg ファイルを許可
            'memo' => 'nullable',
        ]);

        // ファイルを保存
        $voiceScriptPath = null;
        if ($request->hasFile('voice_script')) {
            $voiceScriptPath = $request->file('voice_script')->store('voice_scripts', 'public');
        }

        Item::create([
            'id' => $request->id,
            'italian' => $request->italian,
            'japanese' => $request->japanese,
            'voice_script' => $voiceScriptPath,
            'memo' => $request->memo,
        ]);

        return redirect()->route('post.index')
            ->with([
                'message' => '保存されました',
            ]);
    }

    public function shuffle()
    {
        // セッションにシャッフルされたアイテムがあれば取得
        if (session()->has('shuffled_posts') && !session('shuffled_posts')->isEmpty()) {
            $shuffledPosts = collect(session('shuffled_posts'));
        } else {
            // セッションにアイテムがない、または空の場合は再度シャッフル
            $shuffledPosts = Item::all()->shuffle()->pluck('id');
            session(['shuffled_posts' => $shuffledPosts]);
        }

        if ($shuffledPosts->isEmpty()) {
            // セッションをクリアして一覧ページに戻る
            session()->forget('shuffled_posts');
            return redirect()->route('post.index')->with('message', 'All posts have been displayed!');
        }

        $postId = $shuffledPosts->first();
        $post = Item::find($postId);

        // セッションから最初のアイテムを削除
        session(['shuffled_posts' => $shuffledPosts->slice(1)->values()]);

        return view('show', ['post' => $post]);
    }

    public function edit($id)
    {
        $post = Item::find($id);
        return view('edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = Item::find($id);

        if (!$post) {
            return redirect()->route('post.index')->with('error', 'Post not found.');
        }

        $request->validate([
            'italian' => 'required',
            'japanese' => 'required',
            'voice_script' => 'nullable|file|mimes:mp3,wav,ogg|max:5000',
            'memo' => 'nullable',
        ]);

        // Check if a new audio file has been uploaded
        if ($request->hasFile('voice_script')) {
            // Delete the old audio file if exists
            if ($post->voice_script) {
                Storage::disk('public')->delete($post->voice_script);
            }

            // Store the new audio file and get its path
            $voiceScriptPath = $request->file('voice_script')->store('voice_scripts', 'public');
            $post->voice_script = $voiceScriptPath;
        }

        $post->italian = $request->input('italian');
        $post->japanese = $request->input('japanese');
        $post->memo = $request->input('memo');
        $post->save();

        return redirect()->route('post.index')->with('success', 'Post updated successfully.');
    }


    public function destroy($id)
    {
        $post = Item::find($id);
        // 投稿を削除
        $post->delete();
        return redirect()
            ->route('post.index')
            ->with([
                'message' => '投稿を削除しました',
            ]);
    }
}
