<?php

namespace App\Models;

use App\Models\Traits\SearchableLocal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Customer extends Model
{
    use HasFactory, Searchable, SearchableLocal, SoftDeletes;

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [
        'id'
    ];

    /**
     * @var int
     */
    public static $addressMaxLength = 512;

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static $searchableLocal = [
        'full_name',
        'address',
        'phone_number',
        'card_status',
    ];

    public static $searchableLocalDates = [
        'date_of_birth',
        'created_at',
        'released_at',
    ];

    public static function getSearchableLocal()
    {
        return self::$searchableLocal;
    }
}
