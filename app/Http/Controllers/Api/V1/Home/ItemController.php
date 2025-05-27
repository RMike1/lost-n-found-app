<?php

namespace App\Http\Controllers\Api\V1\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ItemStoreRequest;
use App\Services\Api\V1\Home\ItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function __construct(private ItemService $itemService) {}

    public function index(Request $req): JsonResponse
    {
        $data = $this->itemService->index($req);

        return response()->json([
            'items_counts' => $data[1],
            'items' => $data[0],
        ])->setStatusCode(200);
    }

    public function store(ItemStoreRequest $request): JsonResponse
    {
        return response()->json([$this->itemService->store($request)])->setStatusCode(201);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json([
            'item' => $this->itemService->show($id),
        ])->setStatusCode(200);
    }

    public function toggleFavorite(string $itemId): JsonResponse
    {
        $data = $this->itemService->toggleFavorite($itemId);

        return response()->json([
            'is_favorited' => $data[0],
            'favorites_count' => $data[1],
        ])->setStatusCode(200);
    }

    public function getFavorites(): JsonResponse
    {
        $data = $this->itemService->getUserFavoritesItems();

        return response()->json([
            'items_counts' => $data[1],
            'favorites_items' => $data[0],
        ])->setStatusCode(200);
    }

    public function getUserItems(): JsonResponse
    {
        $data = $this->itemService->getUserItems();

        return response()->json([
            'items_counts' => $data[1],
            'user_items' => $data[0],
        ])->setStatusCode(200);
    }

    public function markItemAsResolved(string $item): JsonResponse
    {
        $data = $this->itemService->markItemAsResolved($item);

        return response()->json([
            'message' => $data ? 'item marked as resolved!' :
                                'item marked as unresolved!',
        ])->setStatusCode(200);
    }

    public function itemDelete(string $item): JsonResponse
    {
        $this->itemService->itemDelete($item);

        return response()->json([
            'message' => 'item deleted successfully!',
        ])->setStatusCode(200);
    }
}
