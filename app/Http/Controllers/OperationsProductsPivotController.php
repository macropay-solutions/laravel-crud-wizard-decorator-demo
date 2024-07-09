<?php

namespace App\Http\Controllers;


use App\Services\OperationsProductsPivotService;

class OperationsProductsPivotController extends ResourceController
{
    protected bool $forbidUpdate = true;
    protected bool $forbidDelete = false;

    /**
     * @inheritDoc
     */
    protected function setResourceService(): void
    {
        $this->resourceService = \resolve(OperationsProductsPivotService::class);
    }
}
