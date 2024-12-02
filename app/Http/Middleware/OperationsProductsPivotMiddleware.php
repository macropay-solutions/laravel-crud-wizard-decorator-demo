<?php

namespace App\Http\Middleware;

use App\Decorators\ClientDecorator;
use App\Decorators\OperationDecorator;
use App\Decorators\OperationProductPivotDecorator;
use App\Models\OperationProductPivot;
use App\Support\DbCrudMap;
use MacropaySolutions\LaravelCrudWizardDecorator\Http\Middleware\Decorators\AbstractDecoratorMiddleware;

class OperationsProductsPivotMiddleware extends AbstractDecoratorMiddleware
{
    protected string $decoratorClass = OperationProductPivotDecorator::class;
    protected array $upsertOneToOneRelationsDbCrudMap = DbCrudMap::MODEL_FQN_TO_CONTROLLER_MAP;
    protected array $relatedDecoratorClassMap = [
        'operation' => OperationDecorator::class,
        'client' => ClientDecorator::class,
    ];

    public function setResourceModel(): void {
        $this->resourceModel = new OperationProductPivot();
    }
}