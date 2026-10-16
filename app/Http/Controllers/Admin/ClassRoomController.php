<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\ClassRoom;
use App\Http\Requests\Admin\StoreClassRoomRequest;
use App\Http\Requests\Admin\UpdateClassRoomRequest;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    // Nampilin halaman utama daftar Data Kelas
    public function index()
    {
        // Diurutin berdasarkan tingkat kelas (grade_level) terus nama kelas
        $classes = ClassRoom::orderBy('grade_level')
            ->orderBy('name')
            ->paginate(5);

        return view('admin.classes.index', compact('classes'));
    }

    public function store(StoreClassRoomRequest $request)
    {
        ClassRoom::create($request->validated());

        return redirect()->back()->with('success', 'Data kelas berhasil ditambahkan.');
    }

    // Nampilin halaman khusus buat form Edit Data Kelas
    public function edit($id)
    {
        $classRoom = ClassRoom::findOrFail($id);

        return view('admin.classes.edit', compact('classRoom'));
    }

    public function update(UpdateClassRoomRequest $request, $id)
    {
        $classRoom = ClassRoom::findOrFail($id);
        $classRoom->update($request->validated());

        // Balikin lagi ke halaman index tabel kelas
        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    // Menghapus data kelas dari database
    public function destroy($id)
    {
        $classRoom = ClassRoom::findOrFail($id);
        
        // Buat jaga-jaga di masa depan, kita bisa nambahin proteksi 
        // biar kelas gak bisa dihapus kalo udah ada siswa di dalemnya.
        
        $classRoom->delete();

        return redirect()->back()->with('success', 'Data kelas berhasil dihapus secara permanen.');
    }
}
