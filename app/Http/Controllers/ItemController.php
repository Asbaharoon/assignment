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

}
