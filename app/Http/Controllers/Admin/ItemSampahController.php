<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemSampah;
use App\Models\KategoriSampah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemSampahController extends Controller
{
    public function index(KategoriSampah $kategori): View
    {
        $items = $kategori->items()->orderBy('nama_item')->paginate(15);

        return view('admin.kategori-sampah.item-index', compact('kategori', 'items'));
    }

    public function store(Request $request, KategoriSampah $kategori): RedirectResponse
    {
        $data = $request->validate([
            'nama_item' => [
                'required', 'string', 'max:150',
                'unique:item_sampah,nama_item,NULL,id,kategori_id,'.$kategori->id,
            ],
            'harga_per_kg' => ['required', 'numeric', 'min:0'],
            'satuan' => ['required', 'string', 'max:20'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['kategori_id'] = $kategori->id;

        ItemSampah::create($data);

        return back()->with('success', 'Item sampah berhasil ditambahkan.');
    }

    public function update(Request $request, KategoriSampah $kategori, ItemSampah $item): RedirectResponse
    {
        $data = $request->validate([
            'nama_item' => [
                'required', 'string', 'max:150',
                'unique:item_sampah,nama_item,'.$item->id.',id,kategori_id,'.$kategori->id,
            ],
            'harga_per_kg' => ['required', 'numeric', 'min:0'],
            'satuan' => ['required', 'string', 'max:20'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $item->update($data);

        return back()->with('success', 'Item sampah berhasil diperbarui.');
    }

    public function destroy(KategoriSampah $kategori, ItemSampah $item): RedirectResponse
    {
        if ($item->transaksiSetor()->exists()) {
            return back()->with('error', 'Item ini sudah dipakai di transaksi setoran dan tidak bisa dihapus. Nonaktifkan saja item ini.');
        }

        $item->delete();

        return back()->with('success', 'Item sampah berhasil dihapus.');
    }
}
