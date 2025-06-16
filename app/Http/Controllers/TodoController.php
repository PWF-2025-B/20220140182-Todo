<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Todo;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Catch_;
class TodoController extends Controller
{
    public function index()
    {
        // $todos = Todo::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();

        $todos = Todo::with('category')
        ->where('user_id', auth::id())
        ->orderBy('is_complete', 'asc')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        
        $todosCompleted = Todo::where('user_id', auth()->user()->id)
         ->where('is_complete', true)
         ->count();
 
         return view('todo.index', compact('todos', 'todosCompleted'));
    }

public function store(Request $request)
{
    try {
        $request->validate([
            'title' => 'required|string|max:25',
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                }),
            ],
        ]);

        $todo = Todo::create([
            'title' => ucfirst($request->title),
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
        ]);

        $todo = Todo::with('category')->find($todo->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Todo created successfully.',
            'data' => [
                'todo' => $todo,
            ]
        ], 201);

    } catch (ValidationException $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed.',
            'errors' => $e->errors(),
        ], 422);
    }
}
    public function create()
    {
        $categories = Category::where('user_id', Auth::id())->get();
        return view('todo.create',compact('categories'));
    }

    public function edit(Todo $todo)
    {
        // Cek apakah user yang login adalah pemilik todo
        if (auth()->user()->id == $todo->user_id) {
            $categories = Category::where('user_id', Auth::id())->get();
            return view('todo.edit', compact('todo','categories'));
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to edit this todo!');
        }
    }

    public function update(Request $request, Todo $todo)
     {
         // Validasi input
         $request->validate([
             'title' => 'required|max:255',
             'category_id' => 'nullable|exists:categories,id'
         ]);
 
         $todo->update([
            'title' => ucfirst($request->title),
            'category_id' => $request->category_id,
        ]);
 
         return redirect()->route('todo.index')->with('success', 'Todo updated successfully!');
     }


     public function complete(Todo $todo)
     {
         if (Auth::id() == $todo->user_id) {
             $todo->is_complete = true;
             $todo->save();
     
             return redirect()->route('todo.index')->with('success', 'Todo marked as completed.');
         }
     
         return redirect()->route('todo.index')->with('danger', 'Unauthorized action.');
     }
     
     public function uncomplete(Todo $todo)
     {
         if (Auth::id() == $todo->user_id) {
             $todo->is_complete = false;
             $todo->save();
     
             return redirect()->route('todo.index')->with('success', 'Todo marked as uncompleted.');
         }
     
         return redirect()->route('todo.index')->with('danger', 'Unauthorized action.');
     }
     
     

    public function destroy(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $todo->delete();
            return redirect()->route('todo.index')->with('success', 'Todo deleted successfully!');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to delete this todo!');
        }
    }

    public function destroyCompleted()
    {
        $todosCompleted = Todo::where('user_id', auth()->user()->id)
            ->where('is_complete', true)
            ->get();

        foreach ($todosCompleted as $todo) {
            $todo->delete();
        }

        return redirect()->route('todo.index')->with('success', 'All completed todos deleted successfully!');
    }

    

}