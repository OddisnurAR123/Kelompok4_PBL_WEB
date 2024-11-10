<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriKegiatan;

class KategoriKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = KategoriKegiatan::all();
        return view('kategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_kategori_kegiatan' => 'required|string|max:50',
            'nama_kategori_kegiatan' => 'required|string|max:100',
        ]);

        KategoriKegiatan::create($request->only(['kode_kategori_kegiatan', 'nama_kategori_kegiatan']));

        return redirect()->route('kategori.index')->with('success', 'Kategori kegiatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kategori = KategoriKegiatan::findOrFail($id);
        return view('kategori.show', compact('kategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kategori = KategoriKegiatan::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kode_kategori_kegiatan' => 'required|string|max:50',
            'nama_kategori_kegiatan' => 'required|string|max:100',
        ]);

        $kategori = KategoriKegiatan::findOrFail($id);
        $kategori->update($request->only(['kode_kategori_kegiatan', 'nama_kategori_kegiatan']));

        return redirect()->route('kategori.index')->with('success', 'Kategori kegiatan berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = KategoriKegiatan::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori kegiatan berhasil dihapus.');
    }
}
