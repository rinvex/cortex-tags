<?php

declare(strict_types=1);

namespace Cortex\Tags\Models;

use Cortex\Tags\Events\TagCreated;
use Cortex\Tags\Events\TagDeleted;
use Cortex\Tags\Events\TagUpdated;
use Spatie\Activitylog\LogOptions;
use Cortex\Tags\Events\TagRestored;
use Rinvex\Support\Traits\Macroable;
use Rinvex\Tags\Models\Tag as BaseTag;
use Cortex\Foundation\Traits\Auditable;
use Rinvex\Support\Traits\HashidsTrait;
use Rinvex\Support\Traits\HasTimezones;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Cortex\Tags\Models\Tag.
 *
 * @property int                 $id
 * @property string              $slug
 * @property array               $name
 * @property array               $description
 * @property int                 $sort_order
 * @property string              $group
 * @property string              $style
 * @property string              $icon
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cortex\Foundation\Models\Log[] $activity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Tags\Models\Tag ordered($direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Tags\Models\Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Tags\Models\Tag whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Tags\Models\Tag whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Tags\Models\Tag whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Tags\Models\Tag whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Tags\Models\Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Tags\Models\Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Tags\Models\Tag whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Tags\Models\Tag whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Tags\Models\Tag whereStyle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Tags\Models\Tag whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Tag extends BaseTag
{
    use Auditable;
    use Macroable;
    use HashidsTrait;
    use HasTimezones;
    use LogsActivity;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TagCreated::class,
        'updated' => TagUpdated::class,
        'deleted' => TagDeleted::class,
        'restored' => TagRestored::class,
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->mergeFillable(['style', 'icon']);

        $this->mergeCasts(['style' => 'string', 'icon' => 'string']);

        $this->mergeRules(['style' => 'nullable|string|strip_tags|max:150', 'icon' => 'nullable|string|strip_tags|max:150']);

        parent::__construct($attributes);
    }

    /**
     * Set sensible Activity Log Options.
     *
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                         ->logFillable()
                         ->logOnlyDirty()
                         ->dontSubmitEmptyLogs();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
