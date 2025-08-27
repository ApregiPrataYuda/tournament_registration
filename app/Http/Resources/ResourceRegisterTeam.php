<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceRegisterTeam extends JsonResource
{
     public function toArray(Request $request): array
    {
        return [
            'team_id' => $this->team_id,
            'team_name' => $this->team_name,
            'nama_captain' => $this->nama_captain,
            'email_captain' => $this->email_captain,
            'captain_gender' => $this->captain_gender,
            'phone_number_captain' => $this->phone_number_captain,
            'full_name_member' => $this->full_name_member,
            'phone_number_member' => $this->phone_number_member,
            'gender_member' => $this->gender_member,
            'created_at' => $this->created_at?->toDateString() ?? '-',
            'updated_at' => $this->updated_at?->toDateString() ?? '-',
        ];
    }
}
