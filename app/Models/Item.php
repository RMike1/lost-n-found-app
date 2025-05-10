<?php

namespace App\Models;

use App\Enums\PostTypeEnum;
use App\Observers\ItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Znck\Eloquent\Traits\BelongsToThrough;

#[ObservedBy(ItemObserver::class)]
class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use BelongsToThrough, HasFactory, HasUlids, SoftDeletes;

    protected $casts = [
        'post_type' => PostTypeEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function itemImages(): HasMany
    {
        return $this->hasMany(ItemImage::class, 'item_id');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function isFavoritedBy(User $user): bool
    {
        return $this->favorites()->where('user_id', $user->id)->exists();
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function cell()
    {
        return $this->belongsToThrough(Cell::class, Village::class);
    }

    public function sector()
    {
        return $this->belongsToThrough(Sector::class, [Cell::class, Village::class]);
    }

    public function district()
    {
        return $this->belongsToThrough(District::class, [Sector::class, Cell::class, Village::class]);
    }

    #[Scope]
    public function getFiltered(Builder $q, $req)
    {
        return $q->when($req->category, function ($q, $category) {
            $q->whereRelation('category', 'id', $category);
        })->when($req->postStatus, function ($q, $postStatus) {
            $q->where('post_type', $postStatus);
        })->when($req->search, function ($q, $search) {
            $q->where(function ($q) use ($search) {
                $q->whereAny([
                    'title',
                    'description',
                    'post_type',
                ], 'like', "%{$search}%")
                    ->orWhereRelation('category', 'name', 'like', "%{$search}%");
            });
        });
    }
}
