<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Rodo visų vartotojo kategorijų sąrašą.
     */
    public function index()
    {
        $categories = Category::where('user_id', Auth::id())->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Rodo formą naujai kategorijai sukurti.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Išsaugo naują kategoriją duomenų bazėje.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        Category::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return redirect()->route('categories.index')
                         ->with('success', 'Kategorija sukurta sėkmingai.');
    }

    /**
     * Rodo vieną kategoriją (nebūtinas jei nenaudoji).
     */
    public function show(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        return view('categories.show', compact('category'));
    }

    /**
     * Rodo formą kategorijai redaguoti.
     */
    public function edit(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        return view('categories.edit', compact('category'));
    }

    /**
     * Atnaujina esamą kategoriją.
     */
    public function update(Request $request, Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        $category->update([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return redirect()->route('categories.index')
                         ->with('success', 'Kategorija atnaujinta sėkmingai.');
    }

    /**
     * Ištrina kategoriją.
     */
    public function destroy(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        $category->delete();

        return redirect()->route('categories.index')
                         ->with('success', 'Kategorija ištrinta.');
    }
}
