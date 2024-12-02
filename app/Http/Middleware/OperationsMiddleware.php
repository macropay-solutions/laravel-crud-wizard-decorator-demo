<?php

namespace App\Http\Middleware;

use App\Decorators\ClientDecorator;
use App\Decorators\OperationDecorator;
use App\Decorators\ProductDecorator;
use App\Models\Operation;
use App\Support\DbCrudMap;
use MacropaySolutions\LaravelCrudWizardDecorator\Http\Middleware\Decorators\AbstractDecoratorMiddleware;

class OperationsMiddleware extends AbstractDecoratorMiddleware
{
    protected string $decoratorClass = OperationDecorator::class;
    protected array $upsertOneToOneRelationsDbCrudMap = DbCrudMap::MODEL_FQN_TO_CONTROLLER_MAP;
    protected array $relatedDecoratorClassMap = [
        'parent' => OperationDecorator::class,
        'children' => OperationDecorator::class,
        'client' => ClientDecorator::class,
        'products' => ProductDecorator::class,
    ];

    public function setResourceModel(): void {
        $this->resourceModel = new Operation();
    }
}