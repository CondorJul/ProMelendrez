<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\category\AddCategoryRequest;
use App\Http\Requests\category\UpdCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function find($id){
        return Category::find($id);
    }

    public function searchByCode($code){
        return Category::where('catCode', $code)->first();
    }

    public function index()
    {
        /*return response()->json([
            'status'=>200,
            'data'=>Category::all(),
            'message'=>'Obtenido correctamente'
        ], 200);*/

        return Category::all();
        /*Category::create(["catCode"=>'34',
    "catName"=>"fjasdfjñlasd"]);*/
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCategoryRequest $request)
    {
         $category=Category::create($request->all());
        return response()->json([
            'res'=>true,
            'msg'=>'Guardado correctamente',
            'data'=>Category::where('catId', $category->catId)->get()



        ]);
       // return $request;
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
    public function update(UpdCategoryRequest $request)
    {
        /*$category=Category::where('catId', $request->catId)
            ->update($request->all());*/
        $category=Category::where('catId', $request->catId)->first();
        $category->catCode=$request->catCode;
        $category->catName=$request->catName;
        //$category->catNameLong=$request->catNameLong;
        $category->catDescription=$request->catDescription;
        $category->catAuth=$request->catAuth;
        $category->catIdParent=$request->catIdParent;
        $category->save();
        return response()->json([
            'res' => true,
            'msg' => 'Categoria actualizada con exito',
            'data'=>Category::where('catId', $category->catId)->get()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   $category=Category::destroy($id);
        return response()->json([
            'res' => true,
            'msg' => 'Eliminado correctamente.',
            'data'=>[]
        ], 200);
    }
}
