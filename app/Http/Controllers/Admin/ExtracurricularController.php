<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExtracurricularController extends Controller
{
    /**
     * Nampilin halaman utama daftar semua ekstrakurikuler.
     */
    public function index()
    {
        // Ambil semua data ekskul beserta data coach dan profilnya, urutkan berdasarkan nama, paginate 5
        $extracurriculars = Extracurricular::with('coach.coachProfile')->orderBy('name')->paginate(5);
        
        return view('admin.extracurriculars.index', compact('extracurriculars'));
    }

    /**
     * Nampilin form kosong buat nambah data ekstrakurikuler baru.
     */
    public function create()
    {
        // Ambil data user yang memiliki role coach untuk dimasukkan ke pilihan dropdown pembina/coach
        $coaches = User::where('role', 'coach')->with('coachProfile')->get();
        
        return view('admin.extracurriculars.create', compact('coaches'));
    }

    /**
     * Menyimpan data dari form tambah ke dalam database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // maksimal 2MB
            'description' => 'nullable|string',
            'schedule' => 'required|string|max:100',
            'coach_id' => 'required|exists:users,id',
            'fee' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Nama ekstrakurikuler wajib diisi.',
            'schedule.required' => 'Jadwal wajib diisi.',
            'coach_id.required' => 'Pembina harus dipilih.',
            'fee.required' => 'Biaya per bulan wajib diisi.',
        ]);

        // 2. Kalau upload banner
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('extracurriculars', 'public');
            $validated['image'] = $path;
        }

        // 3. Simpan ke database
        Extracurricular::create($validated);

        return redirect()->route('extracurriculars.index')
                         ->with('success', 'Berhasil! Data Ekstrakurikuler baru telah ditambahkan.');
    }

    /**
     * Nampilin form edit buat data ekskul yang sudah ada.
     */
    public function edit(string $id)
    {
        $extracurricular = Extracurricular::findOrFail($id);
        $coaches = User::where('role', 'coach')->with('coachProfile')->get();
        
        return view('admin.extracurriculars.edit', compact('extracurricular', 'coaches'));
    }

    /**
     * Menyimpan perubahan dari form edit ke database.
     */
    public function update(Request $request, string $id)
    {
        $extracurricular = Extracurricular::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'schedule' => 'required|string|max:100',
            'coach_id' => 'required|exists:users,id',
            'fee' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Nama ekstrakurikuler wajib diisi.',
            'schedule.required' => 'Jadwal wajib diisi.',
            'coach_id.required' => 'Pembina harus dipilih.',
            'fee.required' => 'Biaya per bulan wajib diisi.',
        ]);

        if ($request->hasFile('image')) {
            if ($extracurricular->image) {
                Storage::disk('public')->delete($extracurricular->image);
            }
            $path = $request->file('image')->store('extracurriculars', 'public');
            $validated['image'] = $path;
        }

        $extracurricular->update($validated);

        return redirect()->route('extracurriculars.index')
                         ->with('success', 'Mantap! Data Ekstrakurikuler berhasil diperbarui.');
    }

    /**
     * Hapus data ekstrakurikuler selamanya dari database.
     */
    public function destroy(string $id)
    {
        $extracurricular = Extracurricular::findOrFail($id);
        
        if ($extracurricular->image) {
            Storage::disk('public')->delete($extracurricular->image);
        }
        
        $extracurricular->delete();

        return redirect()->route('extracurriculars.index')
                         ->with('success', 'Oke, data Ekstrakurikuler telah dihapus selamanya.');
    }
}
