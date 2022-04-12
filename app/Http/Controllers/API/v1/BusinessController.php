<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\business\ExistRucRequest;
use App\Http\Resources\bussinesResource;
use Illuminate\Http\Request;
use App\Models\Business;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return bussinesResource::collection(Business::all())
            ->additional(['msg' => "lista", 'res' => true])
            ->response()
            ->setStatusCode(200);
        //return Business::all();
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

    public function existRuc(ExistRucRequest $request)
    {
        return $user = Business::where('bussRUC', $request->bussRUC)->first();
    }
    /*Añadido por Ricardo */
    public function search(Request $request){
        $l='%';
        if($request->like){
            $l='%'.$request->like.'%';
        }

        return response()->json([
            'res'=>true,
            'msg'=>'Listado correctamente',
            'data'=>Business::whereRaw(' "bussRUC" like ? or "bussName" like ? or "bussEmail" like ? ' ,[$l,$l,$l])
                ->limit(50)
                ->get()
        ],200);
    }
}
