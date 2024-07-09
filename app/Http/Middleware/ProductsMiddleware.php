<?php

namespace App\Http\Middleware;

use App\Decorators\ClientDecorator;
use App\Decorators\OperationDecorator;
use App\Decorators\ProductDecorator;
use App\Models\Product;
use MacropaySolutions\LaravelCrudWizardDecorator\Http\Middleware\Decorators\AbstractDecoratorMiddleware;

class ProductsMiddleware extends AbstractDecoratorMiddleware
{
    protected string $decoratorClass = ProductDecorator::class;
    protected array $relatedDecoratorClassMap = [
        'operations' => OperationDecorator::class,
        'clients' => ClientDecorator::class,
    ];

    public function setResourceModel(): void {
        $this->resourceModel = new Product();
    }
}