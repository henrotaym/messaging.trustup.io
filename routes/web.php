<?php

use App\Api\MediaTrustupIo\Endpoints\Media;
use Henrotaym\LaravelTrustupMediaIo\Contracts\Endpoints\MediaEndpointContract;
use Henrotaym\LaravelTrustupMediaIo\Contracts\Transformers\Models\MediaTransformerContract;
use Henrotaym\LaravelTrustupMediaIoCommon\Contracts\Models\StorableMediaContract;
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

Route::post('upload', function(
    Request $request,
    MediaEndpointContract $mediaEndpoint,
    StoreMediaRequestContract $storeMediaRequest,
    StorableMediaTransformerContract $storableMediaTransformer,
    MediaTransformerContract $mediaTransformer
) {
    $storeMediaRequest->setAppKey('invoicing')
        ->setCollection('testing')
        ->setModelId(135)
        ->setModelType('professional')
        ->addMedia($storableMediaTransformer->fromResource($request->file('file')))
        ->useQueue(false);

    $media = $mediaEndpoint->store($storeMediaRequest)->getFirstMedia();
    return response($mediaTransformer->toArray($media));
})->name('upload');
