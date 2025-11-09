<?php

namespace App\Http\Controllers;

use App\Models\PengajuanLimbah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanLimbahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengajuan = PengajuanLimbah::where('user_id', Auth::id())->latest()->get();
        return view('pengajuan.index', compact('pengajuan'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengajuan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_limbah' => 'required|string|max:100',
            'berat' => 'required|numeric|min:0.1',
        ]);

        PengajuanLimbah::create([
            'user_id' => Auth::id(),
            'jenis_limbah' => $request->jenis_limbah,
            'berat' => $request->berat,
            'poin_didapat' => 0,
            'status' => 'pending',
        ]);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dikirim!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PengajuanLimbah $pengajuan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PengajuanLimbah $pengajuan)
    {
        if ($pengajuan->status != 'pending' || $pengajuan->user_id != Auth::id()) {
            abort(403);
        }

        return view('pengajuan.edit', compact('pengajuan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PengajuanLimbah $pengajuan)
    {
        $request->validate([
            'jenis_limbah' => 'required|string|max:100',
            'berat' => 'required|numeric|min:0.1',
        ]);

        $pengajuan->update([
            'jenis_limbah' => $request->jenis_limbah,
            'berat' => $request->berat,
        ]);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengajuanLimbah $pengajuan)
    {
        if ($pengajuan->user_id != Auth::id()) {
            abort(403);
        }

        $pengajuan->delete();
        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dihapus!');
    }
}
