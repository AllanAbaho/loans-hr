<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = User::where(['email' => $request->email])->first();
            $data['token'] =  $user->createToken('MyApp')->plainTextToken;
            $data['name'] =  $user->name;

            return response()->json(['success' => true, 'data' => $data]);
        } else {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }
    }

    public function redirectToProvider($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!$validated) {
            return response()->json(['success' => false, 'message' => $provider . ' not recognized for this app']);
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }
    public function handleProviderCallback($provider): JsonResponse
    {
        $validated = $this->validateProvider($provider);
        if (!$validated) {
            return response()->json(['success' => false, 'message' => $provider . ' not recognized for this app']);
        }

        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        $newUser = User::firstOrCreate(['email' => $user->getEmail()], ['name' => $user->getName(), 'email_verified_at' => now(), 'organization_id' => 1, 'role' => 'Employee']);
        $newUser->providers()->updateOrCreate(['provider' => $provider, 'provider_id' => $user->getId(),], ['avatar' => $user->getAvatar(),]);
        $token = $newUser->createToken('MyApp')->plainTextToken;

        return response()->json(['success' => true, 'token' => $token, 'user' => $user]);
    }


    protected function validateProvider($provider): bool
    {
        if (!in_array($provider, ['google'])) {
            return false;
        }
        return true;
    }
}
