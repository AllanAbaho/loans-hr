<?php

namespace App\Http\Controllers;

use App\Mail\NewUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::all();
        return response()->json(['success' => true, 'data' => $users]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
            'organization_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()]);
        }

        $input = $request->all();
        $input['password'] = bcrypt('password');
        $user = User::create($input);
        $success['name'] =  $user->name;

        Mail::to($user->email)->later(now()->addMinutes(10), new NewUser($user));
        return response()->json(['success' => true, 'data' => $user, 'message' => 'User registered successfully',]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json(['success' => false, 'message' => 'user not found.']);
        }

        return response()->json(['success' => true, 'data' => $user]);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['success' => false, 'message' => 'User not found']);
        }
        $user->fill($input)->save();

        return response()->json(['success' => true, 'data' => $user, 'message' => 'User updated successfully']);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }
}
