<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    // Menampilkan daftar tahun ajaran
    public function index()
    {
        $academicYears = AcademicYear::orderBy('year_name', 'asc')->paginate(5);
        return view('admin.academic-years.index', compact('academicYears'));
    }

    // Menyimpan tahun ajaran baru
    public function store(Request $request)
    {
        // Validasi pastiin data yang diinputkan user udah bener dan gak kosong
        $request->validate([
            'year_name' => 'required|string|max:20',
            'is_active' => 'required|boolean',
        ]);
        
        // Kalo user nyentang status "Aktif" (is_active = true) buat tahun ajaran baru ini, 
        // berarti tahun ajaran lain yang tadinya aktif harus dimatiin (false).
        if ($request->is_active) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        // Simpan data yang diinputin ke tabel academic_years
        AcademicYear::create([
            'year_name' => $request->year_name,
            'is_active' => $request->is_active,
        ]);

        return redirect()->back()->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    // Nampilin form khusus buat ngedit data
    public function edit($id)
    {
        $academicYear = AcademicYear::findOrFail($id);
        return view('admin.academic-years.edit', compact('academicYear'));
    }

    // Mengupdate (Edit) data tahun ajaran yang udah ada
    public function update(Request $request, $id)
    {
        $academicYear = AcademicYear::findOrFail($id);

        $request->validate([
            'year_name' => 'required|string|max:20',
            'is_active' => 'required|boolean',
        ]);

        if ($request->is_active && !$academicYear->is_active) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        $academicYear->update([
            'year_name' => $request->year_name,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('academic-years.index')->with('success', 'Data tahun ajaran berhasil diperbarui.');
    }

    // Menghapus data tahun ajaran
    public function destroy($id)
    {
        $academicYear = AcademicYear::findOrFail($id);

        if ($academicYear->is_active) {
            return redirect()->back()->with('error', 'Tahun ajaran yang sedang aktif tidak dapat dihapus. Non-aktifkan terlebih dahulu.');
        }

        $academicYear->delete();

        return redirect()->back()->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}