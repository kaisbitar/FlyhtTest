<?php

namespace App\Http\Resources;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class Student extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $image = Storage::url('public/person-placeholder.png');

        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'phone' => $this->phone,
            'status' => $this->status,
            'image'=> $image
        ];
    }

    public function with($request){
        return[
            'version'  =>  '1.0.0',
            'author_url'  => url('dirtt.net')
        ];
    }
}
