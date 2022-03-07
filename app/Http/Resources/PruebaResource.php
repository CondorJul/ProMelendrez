<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PruebaResource extends JsonResource
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
            'id' => $this->id,
            'nombres' => Str::of($this->nombres)->upper(),
            'apellidos' => Str::of($this->apellidos)->upper(),
            'dni' => $this->dni,
            'telefono' => $this->telefono,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d-m-Y')
        ];
    }

    public function with($request)
    {
        return [
            'res' => true
        ];
    }
}
