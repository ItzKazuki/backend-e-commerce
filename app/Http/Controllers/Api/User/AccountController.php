<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * @group Account
 *
 * API for view, edit, or delete account.
 * @authenticated
 */

class AccountController extends Controller
{
    /**
     * Show user details
     *
     * show your account details
     */
    public function index(Request $request)
    {
        return $this->sendRes([
            'user' => $request->user()
        ]);
    }

    /**
     * Update account details
     *
     * you can update account detail here with spesific request.
     * @urlParam id required The id user. Example: user-ajnav
     * @bodyParam name string required The name of user. Example: Kazuki
     * @bodyParam email string required Email of user. Example: kazuki@account.com
     * @bodyParam phone string required Phone number of user. Example: 081234567890
     * @bodyParam password string required Password of user. Example: password
     */
    public function update(Request $request)
    {
        try {
            $userData = $request->validate([
                'name' => 'required|min:3|max:70',
                'about' => 'string|max:255',
                'email' => 'required|string|unique:users',
                'phone' => 'required|string|unique:users'
            ]);

            $user = $request->user();

            if(!$user) throw new Exception('User not found', 404);

            $user->update($userData);

            return $this->sendRes([
                'message' => 'Account updated successfully'
            ]);

        } catch (Exception $e) {
            return $this->sendFailRes($e);
        }
    }

    /**
     * Remove Account
     *
     * this API for remove your account from database.
     */
    public function destroy(Request $request)
    {
        // delete account
        try {
            $request->user()->delete();

            return $this->sendRes([
                'message' => 'Account deleted successfully'
            ]);
        } catch(\Exception $e) {
            return $this->sendFailRes($e, 400);
        }
    }

    /**
     * Get all Addresses
     *
     * this API for get all addresses of user.
     */
    public function getAddress(Request $request)
    {
        return $this->sendRes([
            'addresses' => $request->user()->address()->get()
        ]);
    }

    /**
     * Add Address
     *
     * this API for add address of user.
     * @bodyParam address_line1 required addres1 for user. Example: jln. kp baru no 55
     * @bodyParam city required city for user. Example: jakarta
     * @bodyParam state required state for user. Example: jakarta
     * @bodyParam country required country for user. Example: indonesia
     * @bodyParam postal_code required postal code for user. Example: 12345
     * */
    public function addAddress(Request $request)
    {
        try {
            $addressData = $request->validate([
                'address_line1' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'postal_code' => 'required|string|max:20',
            ]);

            if($request->user()->address()->count() >= 5) throw new Exception(
                'You can only add 5 addresses', 400
            );

            if($request->user()->address()->count() == 0) {
                $addressData['is_primary'] = true;
            }

            $request->user()->address()->create($addressData);

            return $this->sendRes([
                'message' => 'Address added successfully'
            ]);
        } catch (\Exception $e) {
            return $this->sendFailRes($e, 400);
        }
    }

    /**
     * Set Primary Address
     *
     * this API for set primary address of user.
     * @urlParam id required The id address. Example: address-1
     */
    public function setPrimaryAddress(Request $request, string $id)
    {
        try {
            $user = $request->user();
            $user->addresses()->update(['is_primary' => false]);

            $address = $user->addresses()->find($id);

            if ($address) {
                $address->update(['is_primary' => true]);
                return $this->sendRes([
                    'message' => 'Address set as primary successfully'
                ]);
            }

            throw new Exception('Address not found', 404);
        } catch (Exception $e) {
            return $this->sendFailRes($e);
        }
    }

    /**
     *  Update Address
     *
     * this API use for update address details
     * @urlParam id required The id address. Example: address-1
     * @bodyParam address_line1 required addres1 for user. Example: jln. kp baru no 55
     * @bodyParam city required city for user. Example: jakarta
     * @bodyParam state required state for user. Example: jakarta
     * @bodyParam country required country for user. Example: indonesia
     * @bodyParam postal_code required postal code for user. Example: 12345
     */
    public function editAddress(Request $request, string $id)
    {
        try {
            $address = $request->user()->addresses()->find($id);

            if (!$address) throw new Exception('Address not found', 404);

            $addressData = $request->validate([
                'address_line1' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'postal_code' => 'required|string|max:20',
            ]);

            $request->user()->address()->create($addressData);

            return $this->sendRes([
                'message' => 'Address added successfully'
            ]);
        } catch(Exception $e) {
            return $this->sendFailRes($e);
        }

    }

    /**
     * Delete Address
     *
     * this API use for delete address
     * @urlParam id required The id address. Example: address-1
     */
    public function deleteAddress(Request $request, string $id)
    {
        try {
            $user = $request->user();

            $address = $user->addresses()->find($id);

            if ($address) {
                $address->delete();
                return $this->sendRes([
                    'message' => 'Address deleted successfully'
                ]);
            }

            throw new Exception('Address not found', 404);
        } catch(Exception $e) {
            return $this->sendFailRes($e);
        }
    }
}
