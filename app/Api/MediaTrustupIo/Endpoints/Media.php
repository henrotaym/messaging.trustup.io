<?php
namespace App\Api\MediaTrustupIo\Endpoints;

use Henrotaym\LaravelApiClient\Contracts\ClientContract;
use Henrotaym\LaravelApiClient\Contracts\RequestContract;
use Henrotaym\LaravelApiClient\Contracts\TryResponseContract;
use Illuminate\Http\UploadedFile;

class Media
{
    /** @var ClientContract */
    protected $client;

    public function __construct(ClientContract $client)
    {
        $this->client = $client;
    }


    public function store(UploadedFile $file): TryResponseContract
    {
        /** @var RequestContract */
        $request = app()->make(RequestContract::class);
        $request
            ->setBaseUrl(env('MEDIA_URL') . '/api/media')
            ->addHeaders([
                'X-Requested-With' => "XMLHttpRequest",
                "Accept" => "application/json"
            ])
            ->setUrl('/')
            ->setVerb("POST")
            ->setIsMultipart(true)
            ->addData([
                'model_id' => 25,
                'model_type' => 'professional',
                'collection' => 'images-strange-boyz',
                'app_key' => 'messaging',
                'files' => [
                    [
                        'path' => "coucou",
                        'name' => $file->getClientOriginalName(),
                        'custom_properties' => ['description' => 'Une super description bien cool.']
                    ]
                ]
            ]);

        return $this->client->try($request, 'Failed to store file to media.trustup.io');
    }
}