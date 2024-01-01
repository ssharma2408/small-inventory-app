<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolesApiController extends Controller
{
    public function index()
    {
        $roles = Role::get();

        return new RoleResource($roles);
    }    
}
