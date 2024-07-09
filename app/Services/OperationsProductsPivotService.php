<?php

namespace App\Services;

use App\Models\OperationProductPivot;
use MacropaySolutions\LaravelCrudWizard\Services\BaseResourceService;

class OperationsProductsPivotService extends BaseResourceService
{
    /**
     * @inheritDoc
     */
    protected function setBaseModel(): void
    {
        $this->model = new OperationProductPivot();
    }

    /**
     * @inheritDoc
     */
    protected function extractIdentifierConditions(string $identifier): array
    {
        $exploded = \explode('_', $identifier);

        return [
            ['operation_id', \reset($exploded)],
            ['product_id', \next($exploded)],
        ];
    }
}
