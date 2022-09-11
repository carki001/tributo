<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $user = new User();

        if (User::where('email', '=', $request->email)->exists()) {
            return response()->json(["message" => "general.error_user_already_exists", "isError" => true], 200);
        }

        if (!isset($request->password)) {
            return response()->json(["message" => "general.error_on_user_store_password_must_be_filled", "isError" => true], 200);
        }

        $user->password = Hash::make($request->password);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        $user->country = $request->country;
        $user->city = $request->city;
        $user->postalcode = $request->postalcode;
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->company = $request->company;
        $user->vat = $request->vat;

        $user->save();
        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'unique:users,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => "general.error_user_already_exists", "isError" => true], 200);
        }

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        $user->country = $request->country;
        $user->city = $request->city;
        $user->postalcode = $request->postalcode;
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->company = $request->company;
        $user->vat = $request->vat;

        if (isset($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return response()->json($user, 200);
    }

    public function delete(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return 204;
    }

    public function getUserRoles()
    {
        return response()->json(config('constants.USER_ROLES'), 200);
    }
}
