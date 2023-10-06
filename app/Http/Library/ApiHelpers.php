<?php
namespace App\Http\Library;

use Illuminate\Http\JsonResponse;

trait ApiHelpers
{
    protected function can($permission): bool
    {
        $user = auth('sanctum')->user();
		
		$permissions_arr = [];
		
		$permissions_details = $user->load('roles.permissions')->toArray();
		
		foreach($permissions_details['roles'][0]['permissions'] as $perm){
			$permissions_arr[] = $perm['title'];
		}		

		if (!in_array($permission, $permissions_arr)) {
            return true;
        }
        return false;
    }    
}
?>