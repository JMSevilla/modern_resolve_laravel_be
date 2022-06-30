<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DevRegistrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'username' => $this->username,
            'password' => $this->password,
            'userType' => $this->userType,
            'isLock' => $this->isLock,
            'imgURL' => $this->imgURL,
            'occupationStatus' => $this->occupationStatus,
            'occupationDetails' => $this->occupationDetails,
            'occupationPositionWork' => $this->occupationPositionWork,
            'nameofschool' => $this->nameofschool,
            'degree' => $this->degree,
            'address' => $this->address,
            'updated_at' => $this->update_at,
            'created_at' => $this->created_at
        ];
    }
}
