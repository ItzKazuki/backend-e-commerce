<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Address;

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
            $user = $request->user();

            $userData = $request->validate([
                'name' => 'required|min:3|max:70',
                'about' => 'string',
                'email' => 'required|string|unique:users,email,' . $user->id,
                'phone' => 'required|string'
            ]);

            if(!$user) throw new Exception('User not found', 404);

            $user->update($userData);

            sleep(3);

            $updatedUser = User::find($user->id);

            return $this->sendRes([
                'message' => 'Account updated successfully',
                'user' => $updatedUser
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
            'addresses' => $request->user()->addresses()->get()
        ]);
    }

    /**
     * Add Address
     *
     * this API for add address of user.
     * @bodyParam address_title string required title for identified address. Example: Home
     * @bodyParam address_name string required title for identified address customer name. Example: Adi
     * @bodyParam address_line1 string required addres1 for identified address. Example: jln. kp baru no 55
     * @bodyParam address_line2 string addres2 for identified address. Example: jln. kp baru no 55
     * @bodyParam city string required city for identified address. Example: jakarta
     * @bodyParam state string required state for identified address. Example: jakarta
     * @bodyParam country string required country for identified address. Example: indonesia
     * @bodyParam postal_code string required postal code for identified address. Example: 12345
     * */
    public function addAddress(Request $request)
    {
        try {
            $addressData = $request->validate([
                'address_title' => 'required|string|max:255',
                'address_name' => 'required|string|max:255',
                'address_line1' => 'required|string|max:255',
                'address_line2' => 'max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'postal_code' => 'required|string|max:20',
            ]);

            if($request->user()->addresses()->count() >= 5) throw new Exception(
                'You can only add 5 addresses', 400
            );

            if($request->user()->addresses()->count() == 0) {
                $addressData['is_primary'] = true;
            }

            $request->user()->addresses()->create($addressData);

            sleep(3);

            $user = User::find($request->user()->id);

            return $this->sendRes([
                'message' => 'Address added successfully',
                'user' => $user
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

                sleep(3);

                $user = User::find($request->user()->id);

                return $this->sendRes([
                    'message' => 'Address set as primary successfully',
                    'user' => $user
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
     * @bodyParam address_title string required title for identified address. Example: Home
     * @bodyParam address_name string required title for identified address customer name. Example: Adi
     * @bodyParam address_line1 string required addres1 for identified address. Example: jln. kp baru no 55
     * @bodyParam address_line2 string addres2 for identified address. Example: jln. kp baru no 55
     * @bodyParam city string required city for identified address. Example: jakarta
     * @bodyParam state string required state for identified address. Example: jakarta
     * @bodyParam country string required country for identified address. Example: indonesia
     * @bodyParam postal_code string required postal code for identified address. Example: 12345
     */
    public function updateAddress(Request $request, string $id)
    {
        try {
            $address = $request->user()->addresses()->find($id);

            if (!$address) throw new Exception('Address not found', 404);

            $addressData = $request->validate([
                'address_title' => 'string|max:255',
                'address_name' => 'string|max:255',
                'address_line1' => 'string|max:255',
                'address_line2' => 'max:255',
                'city' => 'string|max:255',
                'state' => 'string|max:255',
                'country' => 'string|max:255',
                'postal_code' => 'string|max:20',
            ]);

            $address = Address::find($id);

            if(!$address) throw new Exception('Address not found', 404);

            $address->update($addressData);

            sleep(3);

            $user = User::find($request->user()->id);

            return $this->sendRes([
                'message' => 'Address update successfully',
                'user' => $user
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

                sleep(3);

                $user = User::find($request->user()->id);

                return $this->sendRes([
                    'message' => 'Address deleted successfully',
                    'user' => $user
                ]);
            }

            throw new Exception('Address not found', 404);
        } catch(Exception $e) {
            return $this->sendFailRes($e);
        }
    }
}
