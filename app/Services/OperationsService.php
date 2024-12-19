<?php

namespace App\Services;

use App\Models\Operation;
use MacropaySolutions\LaravelCrudWizard\Services\BaseResourceService;

class OperationsService extends BaseResourceService
{
    /**
     * @inheritDoc
     */
    protected function setBaseModel(): void
    {
        $this->model = new Operation();
        /**
            see dock block of \App\Models\Attributes\OperationAttributes
            see dock block of \App\Models\Operation

            $this->model->incrementing = 0; // sets the public declared property to 0 because it is not type hinted

            $this->model->setAttribute('incrementing', 0); // sets the model attribute

            $this->model->a->incrementing = 0; // sets the model attribute

            or

            $this->model->exists = 0; // sets the public declared property to 0 because it is not type hinted

            $this->model->setAttribute('exists', 0); // sets the model attribute

            $this->model->a->exists = 0; // sets the model attribute
        */
        /**
            For read only DTO of the model or frozen model see \App\Models\Attributes\OperationFrozenAttributes
            $dto = $this->model->getFrozen(); // se more info in the lib README.md
         */
    }
}
