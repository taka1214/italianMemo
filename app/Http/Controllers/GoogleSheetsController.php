<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;

class GoogleSheetsController extends Controller
{
    private $client;
    private $service;
    private $spreadsheetId = 'https://script.google.com/macros/s/AKfycbzkGmbX7FbvH4zPGGVPWYIJgQwqW0dyXyv5xE8L9ew4zqvhJyFUI0zvpsutqFRe_P_0rw/exec';

    public function __construct()
    {
        // クライアントの初期化
        $this->client = new Google_Client();
        $this->client->setAuthConfig(storage_path('storage/app/json/credentials.json')); 
        $this->client->addScope(Google_Service_Sheets::SPREADSHEETS);
        $this->client->setAccessType('offline');
        // サービスの初期化
        $this->service = new Google_Service_Sheets($this->client);
    }

    public function update(Request $request, $id)
    {
        $japanese = $request->input('japanese');
        $italian = $request->input('italian');
        $answer = $request->input('answer');

        $range = 'Sheet1!B' . $id . ':E' . $id;
        dd($range);
        $values = [
            [$japanese, $italian, $answer]
        ];
        $body = new Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);
        $params = [
            'valueInputOption' => 'RAW'
        ];

        try {
            $this->service->spreadsheets_values->update($this->spreadsheetId, $range, $body, $params);
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
