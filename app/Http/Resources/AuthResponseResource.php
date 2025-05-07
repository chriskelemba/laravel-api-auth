<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResponseResource extends JsonResource
{
    protected $token;
    protected $message;

    public function __construct($resource, $token, $message = 'Login Successful')
    {
        parent::__construct($resource);
        $this->token = $token;
        $this->message = $message;
    }

    public function toArray(Request $request): array
    {
        return [
            'user' => new AuthResource($this->resource),
            'token' => $this->token,
            'message' => $this->message,
        ];
    }
}
