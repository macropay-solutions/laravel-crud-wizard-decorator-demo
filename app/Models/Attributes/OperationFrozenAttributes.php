<?php

namespace App\Models\Attributes;

use MacropaySolutions\LaravelCrudWizard\Models\Attributes\BaseModelFrozenAttributes;

/**
 * @property int $id
 * @property ?int $parent_id
 * @property int client_id
 * @property string currency
 * @property string value
 * @property ?string created_at
 * @property ?string updated_at
 */
class OperationFrozenAttributes extends BaseModelFrozenAttributes
{
}
