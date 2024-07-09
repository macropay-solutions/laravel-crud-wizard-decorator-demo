<?php

namespace App\Decorators;

use MacropaySolutions\LaravelCrudWizardDecorator\Decorators\AbstractResourceDecorator;

class OperationProductPivotDecorator extends AbstractResourceDecorator
{
    public function getResourceMappings(): array
    {
        return [
            'id' => 'ID',
            'operation_id' => 'operationID',
            'product_id' => 'productID',
            'created_at' => 'createdAt',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getRelationMappings(): array
    {
        return [
            'products' => [
                'ean' => 'productEAN',
                'name' => 'productTitle',
                'code' => 'productCode',
                'value' => 'productValue',
                'currency' => 'productCurrency',
                'updated_at' => 'productUpdatedAt',
                'created_at' => 'productCreatedAt',
            ],
            'operation' => [
                'parent_id' => 'operationParentID',
                'currency' => 'operationCurrency',
                'value' => 'operationValue',
                'updated_at' => 'operationUpdatedAt',
                'created_at' => 'operationCreatedAt',
            ],
        ];
    }
}