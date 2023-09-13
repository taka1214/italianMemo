<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

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
}
