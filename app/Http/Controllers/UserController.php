<?php

namespace App\Http\Controllers;

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
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|min:5|max:100',
            'password' => 'required|min:8|max:50',
        ]);

        $user = User::where('email', $request->get('email'))->first();

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!Auth::attempt($credentials)){
            $json['message'] = 'Invalid data';
            return response()->json($json, 500);
        }

        return response()->json([
            'status' => 'success',
            'user' => $user
        ], 200);
    }

    /**
     * @return JsonResponse
     */
    public function show()//: JsonResponse
    {
//        try {
//            $user = Auth::user();
//            return response()->json(['status' => 'success', 'user' => $user], 200);
//        } catch (Exception $e) {
//            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 401);
//        }
        if(Auth::check() == true){
//            return response()->json(['message' => 'login já feito'], 401);
            //return redirect()-
            echo 'ok';
        } else {
            $code = strtoupper(substr(bin2hex(random_bytes(4)), 1));
            echo $code;
        }
    }

    private function authenticated()
    {
        $user = User::where('id', Auth::user()->id);
        $user->udpdate([
            ''
        ]);
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
//        dd(env('MAIL_USERNAME'));
        try {
            //generate hexadecimal //transaction
            $code = strtoupper(substr(bin2hex(random_bytes(4)), 1));
            $confirmation_code = new ConfirmationCode();
            $confirmation_code->code = $code;
            $confirmation_code->email = $user->email;
            $confirmation_code->save();
            $user->save();
//            Mail::send(new checkEmail($user, $code));
            Mail::send('email', ['code' => $code], function ($m) use ($user) {
                $m->from(array(env('MAIL_USERNAME') => 'Ezequiel'));
                $m->to($user->email, $user->name)->subject('Seu código de verificação é...');
            });
            return response()->json(['status' => 'success'], 201);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
