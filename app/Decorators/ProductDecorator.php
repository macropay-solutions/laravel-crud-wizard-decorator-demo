<?php

namespace App\Decorators;

use MacropaySolutions\LaravelCrudWizardDecorator\Decorators\AbstractResourceDecorator;

class ProductDecorator extends AbstractResourceDecorator
{
    public array $withoutRelations = [
        'operations',
        'clients',
    ];

    public function getResourceMappings(): array
    {
        return [
            'id' => 'ID',
            'ean' => 'EAN',
            'name' => 'productTitle',
            'code' => 'productCode',
            'value' => 'productValue',
            'currency' => 'productCurrency',
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
            'clients' => [
                'id' => 'clientID',
                'name' => 'clientFullName',
                'active' => 'clientIsActive',
                'updated_at' => 'clientUpdatedAt',
                'created_at' => 'clientCreatedAt',
            ],
        ];
    }
}