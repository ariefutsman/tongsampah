<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriSampah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KategoriSampahController extends Controller
{
    public function index(Request $request): View
    {
        $kategori = KategoriSampah::query()
            ->withCount('items')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('nama_kategori', 'like', '%'.$request->string('search').'%');
            })
            ->orderBy('nama_kategori')
            ->paginate(10)
            ->withQueryString();

        return view('admin.kategori-sampah.index', compact('kategori'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategori_sampah,nama_kategori'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        KategoriSampah::create($data);

        return back()->with('success', 'Kategori sampah berhasil ditambahkan.');
    }

    public function update(Request $request, KategoriSampah $kategori): RedirectResponse
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategori_sampah,nama_kategori,'.$kategori->id],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $kategori->update($data);

        return back()->with('success', 'Kategori sampah berhasil diperbarui.');
    }

    public function destroy(KategoriSampah $kategori): RedirectResponse
    {
        if ($kategori->items()->exists()) {
            return back()->with('error', 'Kategori ini masih punya item sampah. Hapus atau pindahkan item-nya dulu.');
        }

        $kategori->delete();

        return back()->with('success', 'Kategori sampah berhasil dihapus.');
    }
}
