<?php

namespace App\Decorators;

use MacropaySolutions\LaravelCrudWizardDecorator\Decorators\AbstractResourceDecorator;

class ClientDecorator extends AbstractResourceDecorator
{
    public function getResourceMappings(): array
    {
        return [
            'id' => 'ID',
            'name' => 'fullName',
            'active' => 'isActive',
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
            'operations' => [
                'id' => 'operationID',
                'parent_id' => 'operationParentID',
                'currency' => 'operationCurrency',
                'value' => 'operationValue',
                'updated_at' => 'operationUpdatedAt',
                'created_at' => 'operationCreatedAt',
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
            'hasOperations' => fn(array $row): bool => $this->hasOperations($row),
        ];
    }

    private function hasOperations(array $row): bool
    {
        return isset($row['operations']);
    }
}