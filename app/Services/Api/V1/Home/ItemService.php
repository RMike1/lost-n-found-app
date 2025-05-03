<?php

namespace App\Services\Api\V1\Home;

use App\Exceptions\AppException;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemService
{
    public function index(Request $req): array
    {
        return [
            $items = Item::query()
                ->where('active', true)
                ->with(['category', 'user', 'village', 'cell', 'sector', 'district', 'itemImages' => fn ($query) => $query->primaryImage()->take(1)])
                ->getFiltered($req)
                ->latest()
                ->get()
                ->toResourceCollection(),
            $items->count(),
        ];
    }

    public function store($req): ItemResource
    {
        return DB::transaction(function () use ($req) {
            $data = $req->safe()->except('itemImages');
            $data['user_id'] = Auth::id();
            $item = Item::create($data);
            if ($req->hasFile('itemImages')) {
                foreach ($req->file('itemImages') as $image) {
                    $path = Storage::disk('images')->put('items_images', $image);
                    $item->itemImages()->create([
                        'url' => $path,
                        'is_primary' => true,
                    ]);
                }
            }

            return $item->load(['user', 'category', 'itemImages', 'village', 'cell', 'sector', 'district', 'itemImages'])->toResource();
        });
    }

    public function show(string $id): ItemResource
    {
        $item = Item::with([
            'user.village.cell.sector.district',
            'category',
            'village.cell.sector.district',
            'itemImages',
        ])->find($id);

        throw_unless($item, AppException::recordNotFound('Item not found'));

        return $item->toResource();
    }

    public function toggleFavorite(string $itemId): array
    {
        $item = Item::find($itemId);
        throw_if(! $item, AppException::recordNotFound('this item doesn\'t exists'));
        $user = Auth::user();

        $favorite = $item->favorites()->where('user_id', $user->id)->first();

        if ($favorite) {
            $favorite->delete();
            $isFavorited = false;
        } else {
            $item->favorites()->create([
                'user_id' => $user->id,
            ]);
            $isFavorited = true;
        }

        return [
            $isFavorited,
            $item->favorites()->count(),
        ];
    }

    public function getUserFavoritesItems(): array
    {
        $items = Auth::user()
            ->favorites()
            ->with(['item.category', 'item.user', 'item.village', 'item.cell', 'item.sector', 'item.district', 'item.itemImages' => fn ($query) => $query->primaryImage()->take(1)])
            ->latest()
            ->get()
            ->pluck('item');

        return [
            $items->toResourceCollection(),
            $items->count(),
        ];
    }

    public function getUserItems(): array
    {
        $items = Item::query()
            ->where('user_id', Auth::id())
            ->with(['category', 'user', 'village', 'cell', 'sector', 'district', 'itemImages' => fn ($query) => $query->primaryImage()->take(1)])
            ->latest()
            ->get();
        throw_if($items->isEmpty(), AppException::noItemsFound());

        return [
            $items->toResourceCollection(),
            $items->count(),
        ];
    }
}
