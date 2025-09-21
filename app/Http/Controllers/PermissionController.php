<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::active()->get()->groupBy('group');
        return view('app.permission', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Permission::active()->distinct()->pluck('group')->filter();
        return view('app.permission_tambah', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:permissions,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'group' => 'required|string|max:255'
        ]);

        Permission::create([
            'name' => Str::slug($request->name),
            'display_name' => $request->display_name,
            'description' => $request->description,
            'group' => $request->group,
            'is_active' => true
        ]);

        return redirect()->route('permission')->with('success', 'Permission berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        $permission->load('roles');
        return view('app.permission_detail', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        $groups = Permission::active()->distinct()->pluck('group')->filter();
        return view('app.permission_edit', compact('permission', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'group' => 'required|string|max:255'
        ]);

        $permission->update([
            'name' => Str::slug($request->name),
            'display_name' => $request->display_name,
            'description' => $request->description,
            'group' => $request->group,
        ]);

        return redirect()->route('permission')->with('success', 'Permission berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        // Check if permission is being used by roles
        if ($permission->roles()->count() > 0) {
            return redirect()->route('permission')->with('error', 'Tidak dapat menghapus permission yang sedang digunakan oleh role');
        }

        $permission->delete();

        return redirect()->route('permission')->with('success', 'Permission berhasil dihapus');
    }
}
