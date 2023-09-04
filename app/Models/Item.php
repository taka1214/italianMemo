<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'italian',
        'japanese',
        'voice_script',
        'memo',
    ];

    private function getS3Client()
    {
        return new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    public function uploadVoiceScriptToS3($voice_script_file)
    {
        $s3Client = $this->getS3Client();

        try {
            $result = $s3Client->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key' => 'voice_scripts/' . $voice_script_file->getClientOriginalName(),
                'SourceFile' => $voice_script_file->getRealPath(),
            ]);

            return $result['ObjectURL'];
        } catch (Aws\S3\Exception\S3Exception $e) {
            // エラーが発生した場合、詳細をログに記録
            Log::error($e->getMessage());
            return null; // ここでnullを返すか、または適切なエラーメッセージを返すかを決定する必要があります。
        }
    }
}
