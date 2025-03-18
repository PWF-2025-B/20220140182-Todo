<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
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