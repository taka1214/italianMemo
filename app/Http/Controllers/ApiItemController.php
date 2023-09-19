<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use Google_Client;
use Google_Service_Sheets;

class ApiItemController extends Controller
{
    public function getSpreadsheetItems()
    {
        $items = Item::where('category', 'spreadsheet')->get();;
        // 各アイテムに対してS3のURLを生成
        $items->transform(function ($item) {
            if ($item->voice_script) {
                $item->voice_script_url = Storage::disk('s3')->url($item->voice_script);
            }
            return $item;
        });

        return response()->json($items);
    }

    public function getKenteiItems()
    {
        $items = Item::where('category', 'kentei')->get();

        // 各アイテムに対してS3のURLを生成
        $items->transform(function ($item) {
            if ($item->voice_script) {
                $item->voice_script_url = Storage::disk('s3')->url($item->voice_script);
            }
            return $item;
        });

        return response()->json($items);
    }

    public function shuffleItems()
    {
        $items = Item::where('category', 'spreadsheet')->get()->shuffle();

        // 各アイテムに対してS3のURLを生成
        $items->transform(function ($item) {
            if ($item->voice_script) {
                $item->voice_script_url = Storage::disk('s3')->url($item->voice_script);
            }
            return $item;
        });

        return response()->json($items);
    }

    public function shuffleItemsKentei()
    {
        $items = Item::where('category', 'kentei')->get()->shuffle();

        // 各アイテムに対してS3のURLを生成
        $items->transform(function ($item) {
            if ($item->voice_script) {
                $item->voice_script_url = Storage::disk('s3')->url($item->voice_script);
            }
            return $item;
        });

        return response()->json($items);
    }

    public function getItem($id)
    {
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['error' => 'Item not found.'], 404);
        }
        if ($item->voice_script === null) {
            $item->voice_script_url = null;
        } else {
            // それ以外の場合は、voice_script に関する処理を行う
            $item->voice_script_url = Storage::disk('s3')->url($item->voice_script);// 何らかの処理や変換
        }
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        // アイテムの取得
        $item = Item::find($id);

        // アイテムが存在しない場合のエラーレスポンス
        if (!$item) {
            return response()->json(['error' => 'Item not found.'], 404);
        }

        // バリデーション
        $request->validate([
            'italian' => 'required',
            'japanese' => 'required',
            'voice_script' => 'nullable|file|mimes:mp3,wav,m4a,ogg|max:8000',
            'memo' => 'nullable',
            'category' => 'required|in:spreadsheet,kentei'
        ]);

        // ボイススクリプトのアップロード処理
        if ($request->hasFile('voice_script')) {
            if ($item->voice_script) {
                Storage::disk('s3')->delete($item->voice_script);
            }

            $voiceScriptPath = $request->file('voice_script')->store('voice_scripts', 's3');
            $item->voice_script = $voiceScriptPath;
        }

        // 他のフィールドの更新
        $item->italian = $request->input('italian');
        $item->japanese = $request->input('japanese');
        $item->memo = $request->input('memo');
        $item->category = $request->input('category');

        // データの保存
        $item->save();

        // 成功レスポンスの返却
        return response()->json(['success' => 'Item updated successfully.']);
    }

    public function delete($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['error' => 'Item not found.'], 404);
        }

        // S3からvoice_scriptを削除するロジック
        if ($item->voice_script) {
            Storage::disk('s3')->delete($item->voice_script);
        }

        $item->delete();

        return response()->json(['message' => 'Item successfully deleted.'], 200);
    }
}
