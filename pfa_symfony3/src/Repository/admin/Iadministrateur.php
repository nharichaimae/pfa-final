<?php 
namespace App\Repository\admin;

use App\Dto\admin\AdminUpdateDto;

interface Iadministrateur
{
 
    public function getAdminProfile(int $id): ?array;
    public function updateAdminProfile(int $id, AdminUpdateDto $updateDto): ?array;
}