<?php

namespace App\Http\Middleware;

use App\Decorators\ClientDecorator;
use App\Decorators\OperationDecorator;
use App\Decorators\ProductDecorator;
use App\Models\Client;
use MacropaySolutions\LaravelCrudWizardDecorator\Http\Middleware\Decorators\AbstractDecoratorMiddleware;

class ClientsMiddleware extends AbstractDecoratorMiddleware
{
    protected string $decoratorClass = ClientDecorator::class;
    protected array $relatedDecoratorClassMap = [
        'operations' => OperationDecorator::class,
        'products' => ProductDecorator::class,
    ];

    public function setResourceModel(): void {
        $this->resourceModel = new Client();
    }
}