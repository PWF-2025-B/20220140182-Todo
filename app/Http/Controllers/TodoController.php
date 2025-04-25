<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('Todo.index', compact('todos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Todo::create([
            'title' => ucfirst($request->title),
            'user_id' => Auth::id(), // ⬅️ Ini penting
        ]);

        return redirect()->route('todo.index')->with('success', 'Todo created successfully.');
    }



    public function create()
    {
        return view('Todo.create');
    }

    public function edit()
    {
        return view('Todo.edit');
    }
}