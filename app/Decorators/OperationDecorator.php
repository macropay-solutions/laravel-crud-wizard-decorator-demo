<?php

namespace App\Decorators;

use MacropaySolutions\LaravelCrudWizardDecorator\Decorators\AbstractResourceDecorator;

class OperationDecorator extends AbstractResourceDecorator
{
    public array $withoutRelations = [
        'children',
    ];

    public function getResourceMappings(): array
    {
        return [
            'id' => 'ID',
            'parent_id' => 'parentID',
            'client_id' => 'clientID',
            'currency' => 'operationCurrency',
            'value' => 'operationValue',
            'updated_at' => 'updatedAt',
            'created_at' => 'createdAt',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getRelationMappings(): array
    {
        return [
            'parent' => [
                'client_id' => 'parentClientID',
                'currency' => 'parentOperationCurrency',
                'value' => 'parentOperationValue',
                'updated_at' => 'parentUpdatedAt',
                'created_at' => 'parentCreatedAt',
            ],
            'children' => [
                'id' => 'childID',
                'client_id' => 'childClientID',
                'currency' => 'childOperationCurrency',
                'value' => 'childOperationValue',
                'updated_at' => 'childUpdatedAt',
                'created_at' => 'childCreatedAt',
            ],
            'client' => [
                'name' => 'clientFullName',
                'active' => 'clientIsActive',
                'updated_at' => 'clientUpdatedAt',
                'created_at' => 'clientCreatedAt',
            ],
            'products' => [
                'id' => 'productID',
                'ean' => 'productEAN',
                'name' => 'productTitle',
                'code' => 'productCode',
                'value' => 'productValue',
                'currency' => 'productCurrency',
                'updated_at' => 'productUpdatedAt',
                'created_at' => 'productCreatedAt',
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getComposedColumns(): array
    {
        return [
            'hasProducts' => fn(array $row): bool => $this->hasProducts($row),
        ];
    }

    private function hasProducts(array $row): bool
    {
        return isset($row['products']);
    }
}