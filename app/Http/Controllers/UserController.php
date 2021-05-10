<?php

namespace App\Http\Controllers;

use App\Mail\checkEmail;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\ConfirmationCode;

class UserController extends Controller
{
    public function login(Request $request)//: JsonResponse
    {
        $request->validate([
            'email' => 'required|email|min:8|max:100',
            'password' => 'required|min:4|max:50',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['Os dados estão incorretos'],
            ]);
        } elseif (is_null($user->email_verified_at)) {
            throw ValidationException::withMessages([
                'message' => ['O email ainda não foi validado'],
            ]);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken($request->email)->plainTextToken
        ], 200);
    }

    public function logout(): JsonResponse
    {
        Auth::logout();
        return response()->json(['message' => 'Você está deslogado'], 401);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|min:10|max:200',
            'password' => 'required|min:4|max:24|confirmed',
        ]);

        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));

        try {
            //generate hexadecimal //transaction
            $code = strtoupper(substr(bin2hex(random_bytes(4)), 1));
            $confirmation_code = new ConfirmationCode();
            $confirmation_code->code = $code;
            $confirmation_code->email = $user->email;
            $confirmation_code->save();
            $user->save();

            Mail::send(new checkEmail($user, $code));

            return response()->json([
                'status' => 'success',
                'message' => 'email enviado'
            ], 201);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
