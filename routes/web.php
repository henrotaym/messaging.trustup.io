<?php

use App\Api\MediaTrustupIo\Endpoints\Media;
use Henrotaym\LaravelTrustupMediaIo\Contracts\Endpoints\MediaEndpointContract;
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

// fopen($request->file('file')->getPathname(), 'r')

Route::post('upload', function(
    Request $request,
    MediaEndpointContract $mediaEndpoint,
    StoreMediaRequestContract $storeMediaRequest,
    StorableMediaTransformerContract $transformer,
    StoreMediaRequestTransformer $requestTransformer
) {
    $storeMediaRequest->setAppKey('invoicing')
        ->setCollection('testing')
        ->setModelId(135)
        ->setModelType('professional')
        ->addMedia($transformer->fromResource($request->file('file')))
        ->useQueue(false);

    dd($response = $mediaEndpoint->store($storeMediaRequest)->getResponse()->response()->get(), 'success boyz');

    // return $response->body();

    // return $response->body();
    // // dd($response->json());
    // // return response(['response' => $response->json()]);
    // $response = Http::attach(
    //     'tests[0][path]',
    //     $request->file('file')->get(),
    //     $request->file('file')->getClientOriginalName(),
    // )->asMultipart()->post(env('MEDIA_URL'). '/api/media', [
    //     'model_id' => 25,
    //     'model_type' => 'professional',
    //     'collection' => 'images-strange-boyz',
    //     'app_key' => 'messaging',
    //     // 'files[0][path]' => $request->file('file')->get(),
    //     // 'files[0][name]' => $request->file('file')->getClientOriginalName(),
    //     'files[0][custom_properties][hello]' => 'Bonjour',
    //     // 'files' => [
    //     //     [
    //     //         'path' => $request->file('file')->get(),
    //     //         'name' => $request->file('file')->getClientOriginalName(),
    //     //         'custom_properties' => ['test' => "being custom"]
    //     //     ]
    //     // ]
    // ]);
    
    // $response = Http::asMultipart()->post(env('MEDIA_URL'). '/api/media', [
    //     'model_id' => 25,
    //     'model_type' => 'professional',
    //     'collection' => 'images-strange-boyz',
    //     'app_key' => 'messaging',
    //     'files[0][path]' => $request->file('file')->get(),
    //     'files[0][name]' => $request->file('file')->getClientOriginalName(),
    //     // 'files' => [
    //     //     [
    //     //         'path' => $request->file('file')->get(),
    //     //         'name' => $request->file('file')->getClientOriginalName(),
    //     //         'custom_properties' => ['test' => "being custom"]
    //     //     ]
    //     // ]
    // ]);
    // return dd($response->body());

    // $response = $mediaEnpoint->store($request->file('file'));

    // if ($response->failed()):
    //     dd($response->error()->context());
    // endif;

})->name('upload');
