<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Http\Request;
use League\CommonMark\CommonMarkConverter;
use Illuminate\Http\JsonResponse;
use App\Serializers\ItemSerializer;


class ItemService
{
    
    public function createItem(Request $request)
    {
        $converter = new CommonMarkConverter(['html_input' => 'escape', 'allow_unsafe_links' => false]);
        $item = Item::create([
            'name' => $request->get('name'),
            'price' => $request->get('price'),
            'url' => $request->get('url'),
            'description' => $converter->convert($request->get('description'))->getContent(),
        ]);
        $serializer = new ItemSerializer($item);

        return new JsonResponse(['item' => $serializer->getData()]);
    }

    public function updateItem(Request $request, int $id)
    {
        $converter = new CommonMarkConverter(['html_input' => 'escape', 'allow_unsafe_links' => false]);

        $item = Item::findOrFail($id);
        $item->name = $request->get('name');
        $item->url = $request->get('url');
        $item->price = $request->get('price');
        $item->description = $converter->convert($request->get('description'))->getContent();
        $item->save();

        return new JsonResponse(
            [
                'item' => (new ItemSerializer($item))->getData()
            ]
        );
    }

}
