<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ResponseHandler;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller {

    use ResponseHandler;

    protected function user(): ?User {
        return $this->getUser();
    }

    public function getUser() {
        $userObj = Auth::user() ?? null;
        return $userObj;
    }
}
