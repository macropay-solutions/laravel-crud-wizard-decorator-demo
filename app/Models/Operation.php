<?php

namespace App\Models;

use App\Models\Attributes\OperationAttributes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use MacropaySolutions\LaravelCrudWizard\Eloquent\CustomRelations\HasManyThrough2LinkTables;
use MacropaySolutions\LaravelCrudWizard\Models\BaseModel;

/**
 * @property-read Operation|null $parent
 * @property-read Collection<int, Operation> $children
 * @property-read Client|null $client
 * @property-read Collection<int, Product> $products
 *
 * @property OperationAttributes $a
 * @mixin OperationAttributes
 */
class Operation extends BaseModel
{
    public const RESOURCE_NAME = 'operations';
    public const WITH_RELATIONS = [
        'parent',
        'children',
        'client',
        'products',
        'productsValueScopeIssue51825'
    ];
    public const BOTH_WAY_RELATIONS_MAP = [
        'parent' => 'children',
        'children' => 'parent',
    ];
    public const SUMMABLE_COLUMNS = [
        'value',
    ];
    public const MIN_MAX_ABLE_ADDITIONAL_COLUMNS = [
        'created_at',
    ];
    protected array $ignoreUpdateFor = [
        'client_id',
        'currency',
        'value',
        'created_at',
    ];
    protected $table = 'operations';
    protected $fillable = [
        'parent_id',
        'client_id',
        'currency',
        'value',
        'created_at',
        'updated_at',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function products(): HasManyThrough | HasManyThrough2LinkTables
    {
        if ($this->exists && $this->children()->exists()) {
            return (new HasManyThrough2LinkTables(
                $this->newRelatedInstance(Product::class)->newQuery(),
                $this,
                ($f = (new static()))->setTable($f->getTable() . ' as f'),
                'f.parent_id',
                'id',
                'id',
                'f.id',
                ($ff = (new OperationProductPivot()))->setTable($ff->getTable() . ' as ff'),
                'ff.operation_id',
                'ff.product_id',
                $this
            ));
        }

        return $this->hasManyThrough(
            Product::class,
            OperationProductPivot::class,
            'operation_id',
            'id',
            'id',
            'product_id'
        );
    }

    public function productsValueScopeIssue51825(): HasManyThrough
    {
        return $this->products()->where(
            fn ($query) => $query->where('value', '>',  10)->when(
                true,
                fn($query) => $query->orWhereIn('id', Operation::query()->where('id', 2)->first()->children->pluck('id')->toArray())
            )
        );
    }
}
