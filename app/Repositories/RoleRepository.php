<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository extends BaseRepository
{
    public function __construct(Role $role)
    {
        parent::__construct($role);
    }
}
