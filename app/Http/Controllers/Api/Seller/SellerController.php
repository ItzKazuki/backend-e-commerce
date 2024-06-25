<?php

namespace App\Http\Controllers\Api\Seller;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * @group Seller
 *
 * API for create, view, all product and order.
 */

class SellerController extends Controller
{
    /**
     * Find sellers by id
     *
     * show seller with spesific id in apps
     * @urlParam id required The id product. Example: 9c5e925b-9d7e-4d5f-9502-151522a72683
     */
    public function show(string $id)
    {
        try {
            $seller = User::where('role', User::SELLER)->findOrFail($id);

            return $this->sendRes([
                'seller' => $seller
            ]);
        } catch (\Exception $e) {
            return $this->sendFailRes($e);
        }
    }
}
