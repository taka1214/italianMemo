<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class PostController extends Controller
{

    public function index(Request $request)
    {
        $posts = Item::select('id', 'italian', 'japanese', 'voice_script', 'memo', 'category')->get();
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
            'voice_script' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:8000',
            'memo' => 'nullable',
        ]);

        // S3にファイルを保存
        $voiceScriptPath = null;
        if ($request->hasFile('voice_script')) {
            $voiceScriptPath = $request->file('voice_script')->store('voice_scripts', 's3'); // 's3'を指定して保存先を変更
        }

        Item::create([
            'id' => $request->id,
            'italian' => $request->italian,
            'japanese' => $request->japanese,
            'voice_script' => $voiceScriptPath,
            'memo' => $request->memo,
            'category' => $request->category,
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

    public function shuffleReverse()
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

        return view('showReverse', ['post' => $post]);
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
            'voice_script' => 'nullable|file|mimes:mp3,wav,m4a,ogg|max:8000',
            'memo' => 'nullable',
        ]);

        // 新しい音声ファイルがアップロードされたかを確認
        if ($request->hasFile('voice_script')) {
            // 既存の音声ファイルが存在する場合は削除
            if ($post->voice_script) {
                Storage::disk('s3')->delete($post->voice_script); // 's3'を指定して削除対象を指定
            }

            // 新しい音声ファイルを保存し、そのパスを取得
            $voiceScriptPath = $request->file('voice_script')->store('voice_scripts', 's3'); // 's3'を指定して保存先を変更
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

        // S3から音声ファイルを削除
        if ($post->voice_script) {
            Storage::disk('s3')->delete($post->voice_script);
        }

        // データベースから投稿を削除
        $post->delete();

        return redirect()
            ->route('post.index')
            ->with([
                'message' => '投稿を削除しました',
            ]);
    }
}
