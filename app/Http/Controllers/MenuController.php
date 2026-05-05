<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with('category');

        // Filter by category
        if ($request->has('category') && $request->category !== 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('category', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        $menus = $query->latest()->paginate(10);
        $categories = Category::all();

        // Stats
        $totalItems = Menu::count();
        $availableItems = Menu::where('is_available', true)->count();
        $outOfStockItems = Menu::where('is_available', false)->count();
        $totalCategories = Category::count();

        return view('menu.index', compact('menus', 'categories', 'totalItems', 'availableItems', 'outOfStockItems', 'totalCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_url' => 'nullable|url|max:2048',
            'is_available' => 'nullable'
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . time();
        $validated['is_available'] = $request->has('is_available') ? true : false;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
            $validated['image'] = $path;
        } elseif ($request->filled('image_url')) {
            $validated['image'] = $request->image_url;
        }

        unset($validated['image_url']);
        Menu::create($validated);

        return redirect()->route('menu')->with('success', 'Menu item created successfully.');
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_url' => 'nullable|url|max:2048',
            'is_available' => 'nullable'
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . time();
        $validated['is_available'] = $request->has('is_available') ? true : false;

        if ($request->hasFile('image')) {
            // Delete old image if exists and not url
            if ($menu->image && !Str::startsWith($menu->image, 'http')) {
                Storage::disk('public')->delete($menu->image);
            }
            $path = $request->file('image')->store('menus', 'public');
            $validated['image'] = $path;
        } elseif ($request->filled('image_url')) {
            if ($menu->image && !Str::startsWith($menu->image, 'http')) {
                Storage::disk('public')->delete($menu->image);
            }
            $validated['image'] = $request->image_url;
        }

        unset($validated['image_url']);
        $menu->update($validated);

        return redirect()->route('menu')->with('success', 'Menu item updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        $menu->delete();

        return redirect()->route('menu')->with('success', 'Menu item deleted successfully.');
    }

    public function toggle(Menu $menu)
    {
        $menu->update([
            'is_available' => !$menu->is_available
        ]);

        return redirect()->route('menu')->with('success', 'Menu status updated.');
    }
}
