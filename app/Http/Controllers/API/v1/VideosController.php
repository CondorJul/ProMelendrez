<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\videos\addVideosRequest;
use App\Http\Requests\videos\updVideosRequest;
use App\Models\Videos;
use Illuminate\Http\Request;

class VideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Videos::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(addVideosRequest $request)
    {
        $videos = Videos::create($request->all());
        return response()->json([
            'res' => true,
            'msg' => 'Guardado correctamente',
            'data' => Videos::where('vidId', $videos->vidId)->get()
        ], 200);
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
    public function update(updVideosRequest $request)
    {
        $videos = Videos::where('vidId', $request->vidId)->first();
        $videos->vidName = $request->vidName;
        $videos->vidLink = $request->vidLink;
        $videos->vidState = $request->vidState;
        $videos->save();
        return response()->json([
            'res' => true,
            'msg' => 'Video Actualizado correctamente',
            'data' => Videos::where('vidId', $request->vidId)->get()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $a = Videos::whereIn('vidId', explode(',', $id),)->delete();
        return response()->json([
            'res' => true,
            'msg' => 'Eliminado correctamente.',
            'data' => $a
        ], 200);
    }

    public function stateVideo($id)
    {
        $videos = Videos::where('vidId', $id)->first();
        $videos->vidState = $videos->vidState == 1 ? "2" : "1";
        $videos->save();
        return response()->json([
            'res' => true,
            'msg' => 'Actualizado Correctamente.',
        ], 200);
    }
}
