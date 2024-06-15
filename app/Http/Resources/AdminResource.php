<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    protected $status;
    protected $message;
    protected $token;
    protected $tokenType;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @param  bool  $status
     * @param  string  $message
     * @param  string  $token
     * @param  string  $tokenType
     * @return void
     */
    public function __construct($resource, $token = NULL, $tokenType = NULL, $message = 'Success', $status = true)
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
        $this->token = $token;
        $this->tokenType = $tokenType;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            // 'status' => $this->status,
            // 'message' => $this->message,
            // 'header' => [
            //     'accessToken' => $this->token,
            //     'tokenType' => $this->tokenType,
            // ],
            // 'data' => [
               
                'admin' => [
                    'id' => $this->id,
                    'email' => $this->email,
                    'password' => $this->password,
                    'is_super_admin' => $this->is_super_admin,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                ],
            // ],
        ];
    }
}