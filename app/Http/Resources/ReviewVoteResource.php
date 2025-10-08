<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data suara ulasan
 */
class ReviewVoteResource extends JsonResource
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
            'id_review' => $this->id_review,
            'id_user' => $this->id_user,
            'tipe_vote' => $this->tipe_vote,
            'dibuat_pada' => $this->dibuat_pada,
            'created_at' => $this->created_at,
        ];
    }
}