<?php

namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Total project dengan status 'active'
        $totalActiveProjects = Project::where('status', 'active')->count();

        // 2. Total task belum selesai (Kategori bukan 'Done')
        $totalUnfinishedTasks = Task::whereHas('category', function ($query) {
            $query->where('name', '!=', 'Done');
        })->count();

        // 3. Daftar task yang mendekati due date (Belum selesai, diurutkan dari deadline terdekat)
        $upcomingTasks = Task::with(['project', 'category'])
            ->whereHas('category', function ($query) {
                $query->where('name', '!=', 'Done');
            })
            // Opsional: Jika ingin hanya menampilkan yang due_date-nya hari ini ke depan
            ->where('due_date', '>=', Carbon::today()) 
            ->orderBy('due_date', 'asc')
            ->take(5) // Ambil 5 task terdekat saja agar dashboard tidak penuh
            ->get();

        return response()->json([
            'message' => 'Data dashboard berhasil diambil',
            'data' => [
                'total_active_projects' => $totalActiveProjects,
                'total_unfinished_tasks' => $totalUnfinishedTasks,
                'upcoming_tasks' => $upcomingTasks
            ]
        ], 200);
    }
}
