<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TokenizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    protected  $primaryKey = 'userID';
    public function toArray($request)
    {
        return [
            'tokenID' => $this->tokenID,
            'userID' => $this->userID,
            'token' => $this->token,
            'lastRoute' => $this->lastRoute,
            'isDestroyed' => $this->isDestroyed,
            'isvalid' => $this->isvalid,
            'updated_at' => $this->update_at,
            'created_at' => $this->created_at
        ];
    }
}
