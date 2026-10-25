<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // GET /api/items?status=available&category=..&search=..&per_page=12
    public function index(Request $request): JsonResponse
    {
        $items = Item::query()
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->category, fn ($q, $v) => $q->where('category', $v))
            ->when($request->search, fn ($q, $v) => $q->where('name', 'like', "%{$v}%"))
            ->latest('id')
            ->paginate((int) $request->get('per_page', 12));

        return response()->json([
            'message' => 'Daftar barang titipan.',
            'data' => $items->items(),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'total' => $items->total(),
            ],
        ], 200);
    }

    // GET /api/items/{item}
    public function show(Item $item): JsonResponse
    {
        return response()->json(['message' => 'Detail barang.', 'data' => $item], 200);
    }

    // POST /api/items
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|max:50',
            'image_url' => 'nullable|url',
            'origin_city' => 'required|string|max:80',
            'status' => 'nullable|in:available,sold',
        ]);

        $item = Item::create($data + ['status' => 'available']);

        return response()->json(['message' => 'Barang berhasil ditambahkan.', 'data' => $item], 201);
    }

    // PUT/PATCH /api/items/{item}
    public function update(Request $request, Item $item): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|integer|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'category' => 'sometimes|required|string|max:50',
            'image_url' => 'nullable|url',
            'origin_city' => 'sometimes|required|string|max:80',
            'status' => 'sometimes|required|in:available,sold',
        ]);

        $item->update($data);

        return response()->json(['message' => 'Barang berhasil diubah.', 'data' => $item], 200);
    }

    // DELETE /api/items/{item}
    public function destroy(Item $item): JsonResponse
    {
        $item->delete();

        return response()->json(['message' => 'Barang berhasil dihapus.'], 200);
    }
}
