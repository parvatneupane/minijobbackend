<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskModel as Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::with(['client', 'category'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.tasks.index', compact('tasks'));
    }

    public function show(Task $task)
    {
        $task->load(['client', 'category', 'proposals.freelancer', 'contracts']);

        return view('admin.tasks.show', compact('task'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate(['status' => 'required|in:open,in_progress,completed,cancelled']);

        $task->update(['status' => $request->status]);

        return back()->with('success', 'Task status updated.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('admin.tasks.index')->with('success', 'Task removed.');
    }
}
