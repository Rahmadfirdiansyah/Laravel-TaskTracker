<?php

namespace App\Http\Controllers;

use App\Models\Task;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // Ganti 'ilike' jadi 'like' jika pakai MySQL (Laragon)
        $query = Task::with(['project', 'category']);

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        return response()->json(['data' => $query->latest()->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
        ], [
            'project_id.required' => 'Project wajib dipilih.',
            'category_id.required' => 'Kategori task wajib dipilih.',
            'title.required' => 'Judul task wajib diisi.',
            'description.required' => 'Deskripsi task wajib diisi.',
            'due_date.required' => 'Tenggat waktu wajib diisi.',
            'due_date.date' => 'Format tanggal tidak valid.'
        ]);

        // Ambil ID user yang sedang login
        $validated['created_by'] = $request->user()->id;

        $task = Task::create($validated);

        return response()->json(['message' => 'Task berhasil ditambahkan', 'data' => $task], 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'project_id'  => 'required|exists:projects,id',
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'due_date'    => 'required|date',
            'status'      => 'required|in:todo,in_progress,done', // TAMBAHKAN INI
        ]);

        $task->update($validated);

        // PENTING: Load ulang kategorinya agar Vue tidak kehilangan data kategori setelah edit
        $task->load('category');

        return response()->json([
            'message' => 'Task berhasil diupdate',
            'data' => $task
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Isi deleted_by dengan ID user yang sedang login sebelum dihapus
        $task->update(['deleted_by' => $request->user()->id]);

        // Lakukan soft delete
        $task->delete();

        return response()->json(['message' => 'Task berhasil dihapus.']);
    }
}
