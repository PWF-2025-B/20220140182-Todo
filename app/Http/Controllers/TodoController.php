<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::where('user_id', auth()->id())->get();
        dd($todos);
        return view('Todo.index');
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