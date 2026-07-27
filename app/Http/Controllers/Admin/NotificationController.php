<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationModel as Notification;
use App\Models\UserModel as User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')->latest()->paginate(15);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get(['id', 'name', 'role']);

        return view('admin.notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target' => 'required|in:all,client,freelancer,single',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $query = User::query();

        if ($data['target'] === 'single') {
            $query->where('id', $data['user_id']);
        } elseif (in_array($data['target'], ['client', 'freelancer'])) {
            $query->where('role', $data['target']);
        }

        $query->get()->each(function ($user) use ($data) {
            Notification::create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'message' => $data['message'],
            ]);
        });

        return redirect()->route('admin.notifications.index')->with('success', 'Notification(s) sent.');
    }
}
