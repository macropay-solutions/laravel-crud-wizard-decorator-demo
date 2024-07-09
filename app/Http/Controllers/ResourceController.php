<?php

namespace App\Http\Controllers;

use App\Requests\ResourceRequest;
use App\Support\DbCrudMap;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use MacropaySolutions\LaravelCrudWizard\Exceptions\CrudValidationException;
use MacropaySolutions\LaravelCrudWizard\Helpers\GeneralHelper;
use MacropaySolutions\LaravelCrudWizard\Http\Controllers\ResourceControllerTrait;

class ResourceController extends Controller
{
    use ResourceControllerTrait;

    /**
     * @throws \Throwable
     */
    public function __construct()
    {
        $this->init(100);
    }

    public function update(string $identifier, Request $request): JsonResponse
    {
        try {
            $this->throwIfForbidden($this->forbidUpdate);
            $all = $request->all();

            try {
                $model = $this->resourceService->get($identifier, appendIndex: false);
                $model->fill(GeneralHelper::filterDataByKeys($all, \array_diff(
                    $this->resourceService->getModelColumns(),
                    $this->resourceService->getIgnoreExternalUpdateFor()
                )));
                /** $request can contain also files so, overwrite this function to handle them */
                $request->replace($model->getDirty());

                return GeneralHelper::app(JsonResponse::class, [
                    'data' => $this->resourceService->update($identifier, $this->validateUpdateRequest($request))
                        ->toArray(),
                    'status' => 200
                ]);
            } catch (ModelNotFoundException $e) {
                if (!$this->resourceService->isUpdateOrCreateAble($all)) {
                    throw $e;
                }

                $request->replace($all);
                $request->server->set('REQUEST_METHOD', 'POST');

                return $this->create($request);
            }
        } catch (ValidationException | CrudValidationException $e) {
            return GeneralHelper::app(JsonResponse::class, [
                'data' => [
                    'message' => $e->getMessage(),
                    'errors' => $e->errors()
                ],
                'status' => 400
            ]);
        } catch (\Throwable $e) {
            Log::error($this->label . ' update for identifier: ' . $identifier . ', error = ' . $e->getMessage());

            return GeneralHelper::app(JsonResponse::class, [
                'data' => ['message' => GeneralHelper::getSafeErrorMessage($e)],
                'status' => 400
            ]);
        }
    }

    protected function setModelFqnToControllerMap(): void
    {
        $this->modelFqnToControllerMap = DbCrudMap::MODEL_FQN_TO_CONTROLLER_MAP;
    }

    /**
     * @throws \Throwable
     */
    protected function validateCreateRequest(Request $request): array
    {
        return \resolve(ResourceRequest::class)->validated();
    }

    /**
     * @throws \Throwable
     */
    protected function validateUpdateRequest(Request $request): array
    {
        return $this->validateCreateRequest($request);
    }
}
