<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemCollection;
use App\Http\Resources\Item as ItemResource;
use Illuminate\Http\Request;

use App\Item;


class ItemController extends Controller
{
    public function index()
    {
        return new ItemCollection(Item::latest()->get());
    }

    public function store(Request $request)
    {
        $item = request()->user()->items()->create($request->all());

        return (new ItemResource($item))->response()->setStatusCode(201);
    }

    public function show(Item $item)
    {
        return new ItemResource($item);
    }

    public function update(Request $request, Item $item)
    {
        $item->update($request->all());

        return (new ItemResource($item))->response()->setStatusCode(201);
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return response('Deleted', 204);
    }
}
