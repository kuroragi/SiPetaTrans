<?php

namespace App\Http\Controllers;

use App\Models\Trayek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrayekController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view trayeks', only: ['index', 'show']),
            new Middleware('permission:create trayeks', only: ['create', 'store']),
            new Middleware('permission:edit trayeks', only: ['edit', 'update']),
            new Middleware('permission:delete trayeks', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Trayek::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filter by classification
        if ($request->filled('classification')) {
            $query->where('classification', strtolower($request->classification));
        }

        $trayeks = $query->latest()->paginate(10);
        $classifications = Trayek::select('classification')->distinct()->whereNotNull('classification')->pluck('classification');

        if ($request->ajax()) {
            return response()->json([
                'html' => view('trayeks.table_body', compact('trayeks'))->render(),
                'pagination' => (string) $trayeks->links()
            ]);
        }

        return view('trayeks.index', compact('trayeks', 'classifications'));
    }

    public function create()
    {
        return view('trayeks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:trayeks,code',
            'name' => 'required|string|max:255',
            'distance' => 'nullable|numeric',
            'coordinate' => 'nullable|json',
            'classification' => 'nullable|string',
            'color' => 'nullable|string',
            'route_type' => 'required|in:loop,one_way',
        ]);

        $data = $request->all();
        if (isset($data['classification'])) {
            $data['classification'] = strtolower($data['classification']);
        }
        
        // Ensure coordinate is decoded to array to be casted correctly, or just save the raw json if cast is removed.
        // Wait, since cast is 'array', we should decode the JSON string to array before saving.
        if (isset($data['coordinate'])) {
            $data['coordinate'] = json_decode($data['coordinate'], true);
        }

        Trayek::create($data);

        return redirect()->route('trayeks.index')->with('success', 'Data trayek berhasil ditambahkan');
    }

    public function edit(Trayek $trayek)
    {
        return view('trayeks.edit', compact('trayek'));
    }

    public function update(Request $request, Trayek $trayek)
    {
        $request->validate([
            'code' => 'required|unique:trayeks,code,' . $trayek->id,
            'name' => 'required|string|max:255',
            'distance' => 'nullable|numeric',
            'coordinate' => 'nullable|json',
            'classification' => 'nullable|string',
            'color' => 'nullable|string',
            'route_type' => 'required|in:loop,one_way',
        ]);

        $data = $request->all();
        if (isset($data['classification'])) {
            $data['classification'] = strtolower($data['classification']);
        }

        if (isset($data['coordinate'])) {
            $data['coordinate'] = json_decode($data['coordinate'], true);
        }

        $trayek->update($data);

        return redirect()->route('trayeks.index')->with('success', 'Data trayek berhasil diperbarui');
    }

    public function destroy(Trayek $trayek)
    {
        $trayek->delete();

        return redirect()->route('trayeks.index')->with('success', 'Data trayek berhasil dihapus');
    }
}
