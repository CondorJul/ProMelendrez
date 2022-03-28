<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class bussinesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'bussId' => $this->bussId,
            'bussKind' => $this->bussKind,
            'bussName' => Str::of($this->bussName)->upper(),
            'bussRUC' => $this->bussRUC,
            'bussAdress' => $this->bussAdress,
            'bussSunatUser' => $this->bussSunatUser,
            'bussSunatPass' => $this->bussSunatPass,
            'bussCodeSend' => $this->bussCodeSend,
            'bussCodeRNP' => $this->bussCodeRNP,
            'bussDateMembership' => $this->bussDateMembership,
            'bussDateStartedAct' => $this->bussDateStartedAct,
            'bussState' => $this->bussState,
            'bussStateDate' => $this->bussStateDate,
            'bussFileKind' => $this->bussFileKind,
            'bussFileNumber' => $this->bussFileNumber,
            'bussRegime' => $this->bussRegime,
            'bussKindBookAcc' => $this->bussKindBookAcc,
            'bussTel' => $this->bussTel,
            'bussEmail' => $this->bussEmail,
            'bussObservation' => $this->bussObservation,
            'perId' => $this->perId
        ];
    }

    public function with($request)
    {
        return [
            'res' => true
        ];
    }
}
