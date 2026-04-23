<?php

namespace App\Http\Controllers;

use Caffeinated\Shinobi\Models\Role;
use Caffeinated\Shinobi\Models\Permission;
use Illuminate\Http\Request;

class RolesController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isEditor = auth()->user()->hasPermissionTo('roles.index');
        if($isEditor){
            $roles = Role::orderBy('name','ASC')->get();
            return view('admin.roles.index', compact('roles'));
       }else{
            abort(403, 'Unauthorized action.');
       }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEditor = auth()->user()->hasPermissionTo('roles.create');
        if( $isEditor){
            $permissions = Permission::get();
            return view('admin.roles.create', compact('permissions'));
        }else{
            abort(403, 'Unauthorized action.');
        }
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $isEditor = auth()->user()->hasPermissionTo('roles.create');
        if( $isEditor){
            $role = Role::create($request->all());
            $role->permissions()->sync($request->get('permissions'));
            return redirect()->route('roles.index')->withSuccess('Rol creado correctamente');
        }else{
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $isEditor = auth()->user()->hasPermissionTo('roles.edit');
        if( $isEditor){
            $permissions = Permission::get();
            return view('admin.roles.edit', compact('role','permissions'));
        }else{
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $isEditor = auth()->user()->hasPermissionTo('roles.edit');
        if( $isEditor){
        // actualizar rol
        $role->update($request->all());

        // actualizar permisos
        $role->permissions()->sync($request->get('permissions'));
            return redirect()->route('roles.edit', $role->id)->with('success','Rol modificado correctamente');
        }else{
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('roles.edit');
        if( $isEditor){
        $role = Role::find($id);
        $role->delete();
        return redirect()->back()->with('info','Rol eliminado satisfactoriamente');
        }else{
            abort(403, 'Unauthorized action.');
        }
    }
}
