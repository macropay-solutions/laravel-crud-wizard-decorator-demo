<?php

namespace App\Requests;

use App\Models\Client;
use App\Models\Operation;
use App\Models\OperationProductPivot;
use App\Models\Product;
use App\Support\DbCrudMap;
use App\Support\Currencies;
use Illuminate\Foundation\Http\FormRequest;
use MacropaySolutions\LaravelCrudWizard\Exceptions\CrudValidationException;
use MacropaySolutions\LaravelCrudWizard\Models\BaseModel;


class ResourceRequest extends FormRequest
{
    public const REQUIRED_ON_CREATE = 'requiredOnCreate';

    public function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null,
        protected ?string $resource = null
    ) {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * Get the validation rules that apply to the request.
     * @throws CrudValidationException
     */
    public function rules(): array
    {
        $pathInfo = (array)\explode('/', $this->getPathInfo());

        if (
            '' === (string)$this->resource
            && '' === \reset($pathInfo)
            && 'api' === \next($pathInfo)
        ) {
            $this->resource = \next($pathInfo);
        }

        $result = ([
            Client::RESOURCE_NAME => fn (): array => [
                'name' => 'required|string|min:1|max:256',
                'active' => 'in:0,1',
            ],
            OperationProductPivot::RESOURCE_NAME => fn (): array => [
                'operation_id' => 'integer|integer|exists:' . Operation::class . ',id|unique:' .
                    OperationProductPivot::class . ',operation_id,null,null,product_id,' . $this->get('product_id'),
                'product_id' => 'required|integer|exists:' . Product::class . ',id|unique:' .
                    OperationProductPivot::class . ',product_id,null,null,operation_id,' . $this->get('operation_id'),
            ],
            Operation::RESOURCE_NAME => fn (): array => [
                'parent_id' => 'nullable|integer|exists:' . Operation::class . ',id',
                'client_id' => 'required|integer|exists:' . Client::class . ',id',
                'currency' => 'required|size:3|in:' . \implode(',', Currencies::CURRENCIES),
                'value' => 'required|numeric|gt:0|regex:/^\d+(\.\d{1,2})?$/',
            ],
            Product::RESOURCE_NAME => fn (): array => [
                'ean' => 'required|string|min:1|max:256',
                'name' => 'required|string|min:1|max:256',
                'code' => 'required|string|min:1|max:256',
                'currency' => 'required|size:3|in:' . \implode(',', Currencies::CURRENCIES),
                'value' => 'required|numeric|gt:0|regex:/^\d+(\.\d{1,2})?$/',
            ],
        ][$this->resource ?? ''] ?? fn (): array => [])();

        if ($this->getRealMethod() === 'POST') {
            return $this->getValidatorsForCreate($result);
        }

        return $this->getValidatorsForUpdate($result);
    }

    /**
     * @throws CrudValidationException
     */
    private function getValidatorsForCreate(array $result): array
    {
        $forbiddenKeys = $this->getModel()->getIgnoreExternalCreateFor();

        return \array_filter(
            \array_map(
                function (mixed $val): mixed {
                    $exploded = \is_string($val) ? \explode('|', $val) : $val;

                    if (!\is_array($exploded)) {
                        return $val;
                    }

                    $explodedStrings = \array_map(fn (mixed $v): string => (string)$v, $exploded);

                    return false !== \in_array('required', $explodedStrings, true)
                    || \in_array('present', $explodedStrings, true)
                    || false !== \in_array(self::REQUIRED_ON_CREATE, $explodedStrings, true) ?
                        $exploded :
                        \array_merge(['sometimes'], $exploded);
                },
                $result
            ),
            fn (mixed $value, string $key): bool => !\in_array(\strtok($key, '.'), $forbiddenKeys, true),
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * @throws CrudValidationException
     */
    private function getValidatorsForUpdate(array $result): array
    {
        $forbiddenKeys = $this->getModel()->getIgnoreUpdateFor();

        return \array_filter(
            \array_map(
                function (mixed $val): mixed {
                    $exploded = \is_string($val) ? \explode('|', $val) : $val;

                    if (!\is_array($exploded)) {
                        return $val;
                    }

                    $explodedStrings = \array_map(fn (mixed $v): string => (string)$v, $exploded);

                    return \array_merge(
                        ['sometimes'],
                        \array_values(\array_diff_key($exploded, \array_flip([
                            false !== ($k = \array_search(self::REQUIRED_ON_CREATE, $explodedStrings, true)) ? $k : -1,
                            false !== ($k = \array_search('present', $explodedStrings, true)) ? $k : -2,
                            false !== ($k = \array_search('required', $explodedStrings, true)) ? $k : -3,
                        ])))
                    );
                },
                $result
            ),
            fn (mixed $value, string $key): bool => !\in_array(\strtok($key, '.'), $forbiddenKeys, true),
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * @throws CrudValidationException
     */
    private function getModel(): BaseModel
    {
        if ((string)$this->resource === '') {
            throw new CrudValidationException(['resource' => 'Not found']);
        }

        foreach (DbCrudMap::MODEL_FQN_TO_CONTROLLER_MAP as $modelFqn => $controllerFqn) {
            /** @var BaseModel $modelFqn */
            if ($modelFqn::RESOURCE_NAME === $this->resource) {
                return \resolve($modelFqn);
            }
        }

        throw new CrudValidationException(['resource' => 'Not found']);
    }
}
