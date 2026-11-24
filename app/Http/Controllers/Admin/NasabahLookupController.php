<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NasabahLookupController extends Controller
{
    public function index(Request $request): View
    {
        $nasabah = Nasabah::query()
            ->with('user')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('no_hp', 'like', "%{$search}%");
                    })->orWhere('id', $search);
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.nasabah.index', compact('nasabah'));
    }

    public function show(Nasabah $nasabah): View
    {
        $nasabah->load('user');

        $setoran = $nasabah->transaksiSetor()->with('item')->latest('tanggal')->paginate(10, ['*'], 'setoran_page');
        $tarik = $nasabah->transaksiTarik()->latest('tanggal')->paginate(10, ['*'], 'tarik_page');

        return view('admin.nasabah.show', compact('nasabah', 'setoran', 'tarik'));
    }
}
