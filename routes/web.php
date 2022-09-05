<?php

use Henrotaym\LaravelTrustupMediaIo\Contracts\Endpoints\MediaEndpointContract;
use Henrotaym\LaravelTrustupMediaIo\Contracts\Transformers\Models\MediaTransformerContract;
use Henrotaym\LaravelTrustupMediaIo\Resources\Models\Media;
use Henrotaym\LaravelTrustupMediaIo\Resources\Responses\Media\StoreMediaResponse;
use Henrotaym\LaravelTrustupMediaIoCommon\Contracts\Models\StorableMediaContract;
use Henrotaym\LaravelTrustupMediaIoCommon\Contracts\Requests\Media\GetMediaRequestContract;
use Henrotaym\LaravelTrustupMediaIoCommon\Contracts\Requests\Media\StoreMediaRequestContract;
use Henrotaym\LaravelTrustupMediaIoCommon\Contracts\Transformers\Models\StorableMediaTransformerContract;
use Henrotaym\LaravelTrustupMediaIoCommon\Enums\Media\MediaCollections;
use Henrotaym\LaravelTrustupMediaIoCommon\Transformers\Models\StorableMediaTransformer;
use Henrotaym\LaravelTrustupMediaIoCommon\Transformers\Requests\Media\StoreMediaRequestTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/media', function (
    GetMediaRequestContract $request,
    MediaEndpointContract $endpoint
) {
    $request
        ->setMediaCollection(MediaCollections::IMAGES)
        ->setModelId(135)
        ->setModelType('professional')
        ->firstOnly(false);
    
    $response = $endpoint->get($request);

    if (!$response->ok()):
        dd("failed", $response->getResponse()->error()->context());
    endif;

    return Media::collection($response->getMedia());   
})->name('media');

Route::post('upload', function(
    Request $request,
    MediaEndpointContract $mediaEndpoint,
    StoreMediaRequestContract $storeMediaRequest,
    StorableMediaTransformerContract $storableMediaTransformer,
) {
    $storeMediaRequest
        ->setMediaCollection(MediaCollections::IMAGES)
        ->setModelId(136)
        ->setModelType('professional')
        ->addMedia($storableMediaTransformer->fromResource($request->file('file')))
        ->useQueue(false);

    $response = $mediaEndpoint->store($storeMediaRequest);

    if (!$response->ok()):
        dd("failed", $response->getResponse()->error()->context());
    endif;

    return Media::collection($response->getMedia());
})->name('upload');
