<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Serializers\ItemSerializer;
use App\Serializers\ItemsSerializer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\ItemService;
use App\Http\Requests\StoreItemRequest;
use App\Events\NewItemRegistered;
use Illuminate\Support\Carbon;


class ItemController extends Controller
{
    public function index()
    {
        $items = Item::All();
        return JsonResponse::create(['items' => (new ItemsSerializer($items))->getData()]);
    }

    public function store(StoreItemRequest $request, ItemService $itemService)
    {
        return $itemService->createItem($request);
    }

    public function show(Item $item)
    {
        $serializer = new ItemSerializer($item);
        return new JsonResponse(['item' => $serializer->getData()]);
    }

    public function update(StoreItemRequest $request, int $id, ItemService $itemService): JsonResponse
    {
        return $itemService->updateItem($request, $id);
    }

    public function delete(int $id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
    }


    //////////////////////////////////////

    public function totalItemsCount()
    {
        $totalItemsCount = Item::count();
        return $totalItemsCount;
    }

    public function averagePrice($item)
    {
        $item = Item::findOrFail($item);
        $averagePrice = Item::where('name', $item->name)
            ->avg('price');
        return $averagePrice;
    }

    public function websiteHighestTotalPrice()
    {
        $items = Item::selectRaw('SUBSTRING_INDEX(url, \'/\', 3) as website, SUM(price) as sumprice')
            ->groupBy('website')
            ->orderByDesc('sumprice')
            ->first();
        return $items->website;
    }

    public function totalPriceThisMonth()
    {
        $now = Carbon::now();
        $totalPrice = Item::whereMonth('created_at', $now->month)
            ->sum('price');
        return $totalPrice;
    }
}
