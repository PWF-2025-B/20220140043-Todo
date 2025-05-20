<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
class TodoController extends Controller
{
    public function index()
    {
    // $todos = Todo::all();
    // $todos = Todo::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
    // dd($todos);
    // $todos = Todo::where('user_id', Auth::id())
    //     ->orderBy('is_done', 'asc')
    //     ->orderBy('created_at', 'desc')
    //     ->paginate(10);
        // Ambil semua todo dari user login dengan eager load relasi 'category'
                $todosQuery = Todo::with('category')
            ->where('user_id', Auth::id())
            ->orderBy('is_done', 'asc')
            ->orderBy('created_at', 'desc');

        $allTodos = $todosQuery->get(); // ← INI cuma 1 query karena pakai with

        // Hitung dari collection
        $todoCompleted = $allTodos->where('is_done', true)->count();

        // Manual paginate collection, biar nggak query ulang
        $page = request('page', 1);
        $perPage = 10;
        $todos = new \Illuminate\Pagination\LengthAwarePaginator(
            $allTodos->forPage($page, $perPage),
            $allTodos->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('todo.index', compact('todos', 'todoCompleted'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('todo.create', compact('categories'));
    }

    public function edit(Todo $todo)
    {
        $categories = Category::where('user_id', Auth::user()->id)->get();
        
        if (Auth::user()->id == $todo->user_id) {
            // dd($todo);
            $categories = Category::all();
            return view('todo.edit', compact('todo', 'categories'));
        } else {
            // abort(403);
            // abort(403, 'Not authorized');
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to edit this todo!');
        }
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        // Practical
        // $todo->title = $request->title;
        // $todo->save();

        // Eloquent Way - Readable
        $todo->update([
            'title' => ucfirst($request->title),
            'category_id' => $request->category_id
        ]);

        return redirect()->route('todo.index')->with('success', 'Todo updated successfully!');
    }


    public function complete(Todo $todo)
    {
        if(Auth::user()->id == $todo->user_id){
            $todo->update([
                'is_done' => true,
            ]);
            return redirect()->route('todo.index')->with('success', 'Todo completed successfully!');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to complete this todo!');
        }
    }

    public function uncomplete(Todo $todo)
    {
        if(Auth::user()->id == $todo->user_id){
            $todo->update([
                'is_done' => false,
            ]);
            return redirect()->route('todo.index')->with('success', 'Todo uncompleted successfully!');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to uncomplete this todo!');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        
        ]);
        $todo = Todo::create([
            'title' => ucfirst($request->title),
            'user_id' => Auth::id(),
            'category_id' => $request->category_id
        ]);
        return redirect()->route('todo.index')->with('success', 'Todo created successfully.');
    }

    public function destroy(Todo $todo)
    {
        if (Auth::user()->id == $todo->user_id) {
            $todo->delete();
            return redirect()->route('todo.index')->with('success', 'Todo deleted successfully!');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to delete this todo!');
        }
    }

    public function destroyCompleted()
    {
        $todosCompleted = Todo::where('user_id', Auth::user()->id)
            ->where('is_done', true)
            ->get();

        foreach ($todosCompleted as $todo) {
            $todo->delete();
        }
        //dd($todosCompleted);
        return redirect()->route('todo.index')->with('success', 'All completed todos deleted successfully!');
    }

}