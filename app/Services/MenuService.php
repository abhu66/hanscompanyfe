<?php

namespace App\Services;

use App\Models\User;

class MenuService
{
    public static function hasAccess(array $functions, string $functionName): bool
    {
            foreach ($functions as $roleFunction) {
                if ($roleFunction['name'] === $functionName) {
                    return true;
                }
            }

        return false;
    }
}
