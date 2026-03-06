<?php

namespace App\Http\Controllers;
use App\Models\Project;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query();

        // Searching by judul
        if ($request->has('search')) {
            $query->where('title', 'ilike', '%' . $request->search . '%'); // ilike untuk PostgreSQL (case-insensitive)
        }

        return response()->json([
            'data' => $query->latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ], [
            'name.required' => 'Nama project wajib diisi.',
            'description.required' => 'Deskripsi project wajib diisi.',
        ]);

        // ambil user_id dari token yang sedang digunakan
        $validated['created_by'] = $request->user()->id;

        $project = Project::create($validated);

        return response()->json(['message' => 'Project berhasil dibuat', 'data' => $project], 201);
    }

    public function show($id)
    {
        // Load project beserta tasks dan kategorinya
        $project = Project::with('tasks.category')->findOrFail($id);
        return response()->json(['data' => $project]);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:active,archived',
        ], [
            'title.required' => 'Judul project wajib diisi.',
            'description.required' => 'Deskripsi project wajib diisi.',
            'status.in' => 'Status hanya boleh active atau archived.',
        ]);

        $project->update($validated);

        return response()->json(['message' => 'Project berhasil diupdate', 'data' => $project]);
    }
}
