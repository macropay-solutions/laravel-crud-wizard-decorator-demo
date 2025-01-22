<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$callback = function (\Illuminate\Routing\Router $router): void {
    try {
        foreach (
            \MacropaySolutions\LaravelCrudWizard\Helpers\ResourceHelper::getResourceNameToControllerFQNMap(
                \App\Support\DbCrudMap::MODEL_FQN_TO_CONTROLLER_MAP
            ) as $resource => $controller
        ) {
            Route::get('/' . $resource, [$controller, 'list'])->name('apiinfodecorated.list_' . $resource)->middleware(
                'decorate-' . $resource . ':list'
            );
            Route::post('/' . $resource . '/l/i/s/t', [$controller, 'list'])->name('apiinfodecorated.post_list_' .
                $resource)->middleware('decorate-' . $resource . ':list');
            Route::post('/' . $resource, [$controller, 'create'])->name(
                'apiinfodecorated.create_' . $resource
            )->middleware('decorate-' . $resource . ':create');
            Route::put('/' . $resource . '/{identifier}', [$controller, 'update'])->name(
                'apiinfodecorated.update_' . $resource
            )->middleware('decorate-' . $resource . ':update');
            Route::get('/' . $resource . '/{identifier}', [$controller, 'get'])->name(
                'apiinfodecorated.get_' . $resource
            )->middleware('decorate-' . $resource . ':get');
            Route::delete('/' . $resource . '/{identifier}', [$controller, 'delete'])->name(
                'apiinfodecorated.delete_' . $resource
            )->middleware('decorate-' . $resource . ':delete');
            Route::match([
                'post',
                'get'
            ], '/' . $resource . '/{identifier}/{relation}', [$controller, 'listRelation'])->middleware(
                'decorate-' . $resource . ':listRelation'
            );
        }
    } catch (Throwable $e) {
        \Illuminate\Support\Facades\Log::error($e->getMessage());
    }
};

Route::domain(\trim(\env('APP_URL')))
    ->middleware('auth:sanctum')
    ->group($callback);
