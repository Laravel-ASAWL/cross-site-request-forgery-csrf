<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::all();
        return view('comments', compact('comments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación del token CSRF
        if ($request->session()->token() == csrf_token())
        {
            // Validación de entradas
            $validated = $request->validate([
                'comment' => 'required|string',
            ]);

            // Sanitización de entradas
            $comment = e($validated['comment']);

            // Utilización de Eloquent ORM
            Comment::create([
                'comment' => $comment,
            ]);
        }

        // retorno
        return redirect()->route('home');
    }
}
