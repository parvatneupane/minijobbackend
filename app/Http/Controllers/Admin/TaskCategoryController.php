<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskModel as Task;
use App\Models\TaskCategoriesModel as TaskCategory;

use Illuminate\Http\Request;

class TaskCategoryController extends Controller
{
    public function index()
    {
        $categories = TaskCategory::withCount('tasks')->latest()->paginate(15);

        return view('admin.task-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.task-categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|integer',
        ]);

        TaskCategory::create($data);

        return redirect()->route('admin.task-categories.index')->with('success', 'Category created.');
    }

    public function edit(TaskCategory $taskCategory)
    {
        return view('admin.task-categories.edit', ['category' => $taskCategory]);
    }

    public function update(Request $request, TaskCategory $taskCategory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|integer',
        ]);

        $taskCategory->update($data);

        return redirect()->route('admin.task-categories.index')->with('success', 'Category updated.');
    }

    public function destroy(TaskCategory $taskCategory)
    {
        $taskCategory->delete();

        return back()->with('success', 'Category deleted.');
    }
}
