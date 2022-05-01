<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\cards\updCardsRequest;
use App\Models\Cards;
use Illuminate\Http\Request;

class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cards::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cards = Cards::create($request->all());
        return response()->json([
            'res' => true,
            'msg' => 'Guardado correctamente',
            'data' => Cards::where('cardId', $cards->cardId)->get()
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
    public function update(updCardsRequest $request)
    {
        $cards = Cards::where('cardId', $request->cardId)->first();
        $cards->cardName = $request->cardName;
        $cards->cardPhrases = $request->cardPhrases;
        $cards->cardState = $request->cardState;
        $cards->save();
        return response()->json([
            'res' => true,
            'msg' => 'Tarjeta Actualizado correctamente',
            'data' => Cards::where('cardId', $request->cardId)->get()
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
        $a = Cards::where('cardId', $id)->delete();
        return response()->json([
            'res' => true,
            'msg' => 'Eliminado correctamente.',
            'data' => $a
        ], 200);
    }

    public function stateCards($id)
    {
        $cards = Cards::where('cardId', $id)->first();
        $cards->cardState = $cards->cardState == 1 ? "2" : "1";
        $cards->save();
        return response()->json([
            'res' => true,
            'msg' => 'Actualizado Correctamente.',
        ], 200);
    }
}
