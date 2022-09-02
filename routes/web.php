<?php

use Henrotaym\LaravelTrustupMediaIo\Contracts\Endpoints\MediaEndpointContract;
use Henrotaym\LaravelTrustupMediaIo\Contracts\Transformers\Models\MediaTransformerContract;
use Henrotaym\LaravelTrustupMediaIo\Resources\Models\Media;
use Henrotaym\LaravelTrustupMediaIo\Resources\Responses\Media\StoreMediaResponse;
use Henrotaym\LaravelTrustupMediaIoCommon\Contracts\Models\StorableMediaContract;
use Henrotaym\LaravelTrustupMediaIoCommon\Contracts\Requests\Media\GetMediaRequestContract;
use Henrotaym\LaravelTrustupMediaIoCommon\Contracts\Requests\Media\StoreMediaRequestContract;
use Henrotaym\LaravelTrustupMediaIoCommon\Contracts\Transformers\Models\StorableMediaTransformerContract;
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
    $request->setAppKey('invoicing')
        ->setCollection('test')
        ->setModelId(135)
        ->setModelType('professional')
        ->firstOnly(true);
    
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
    $storeMediaRequest->setAppKey('invoicing')
        ->setCollection('test')
        ->setModelId(135)
        ->setModelType('professional')
        ->addMedia($storableMediaTransformer->fromResource($request->file('file')))
        // ->addMedia($storableMediaTransformer->fromResource("https://static.remove.bg/remove-bg-web/45b4adb99db629ba364dd1649ab6e33dfec34929/assets/start_remove-c851bdf8d3127a24e2d137a55b1b427378cd17385b01aec6e59d5d4b5f39d2ec.png"))
        ->useQueue(true);

    $response = $mediaEndpoint->store($storeMediaRequest);

    if (!$response->ok()):
        dd("failed", $response->getResponse()->error()->context());
    endif;

    return Media::collection($response->getMedia());
})->name('upload');
