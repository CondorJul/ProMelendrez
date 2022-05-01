<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'res'=>true,
            'msg'=>'Listado correctamente',
            'data'=>Role::all()
        ],200);   
    }

    public function getPermissions($roleName){
        return response()->json([
            'res'=>true,
            'msg'=>'Listado correctamente',
            'data'=>Role::findByName($roleName)->permissions
        ],200);  
         
    }

    public function syncPermissions($roleName, Request $request){
        
        //return $request->all();
        return Role::findByName($roleName)->permissions()->sync($request->all());
        /*return response()->json([
            'res'=>true,
            'msg'=>'Listado correctamente',
            'data'=>Role::findByName($roleName)->permissions
        ],200);*/
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
