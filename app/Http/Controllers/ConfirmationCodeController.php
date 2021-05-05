<?php

namespace App\Http\Controllers;

use App\Mail\checkEmail;
use App\Models\ConfirmationCode;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ConfirmationCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function validation(Request $request): JsonResponse
    {
//        $request->validate([
//            'email' => 'required|email|min:5|max:200|',
//            'code' => 'required|min:7|max:10'
//        ]);
        $confirmation_code = ConfirmationCode::where('code', '=', $request->get('code'))->first();
        if(!$confirmation_code || ($confirmation_code->email != $request->get('email'))){
            throw ValidationException::withMessages([
                'code' => ['O código utilizado é inválido!'],
            ]);
        }
        $user = User::where('email', $request->get('email'))->first();
        $user->email_verified_at = Carbon::now()->timestamp;
        $confirmation_code->delete();
        $user->save();
        return response()->json(['status' => 'success'], 201);
    }

    public function resend(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|min:5|max:200|'
        ]);
        //cria objeto de user de acordo com a request enviada
        $user = User::where('email', $request->get('email'))->first();

        //verifica se email existe na verificação e se existe apaga
        $delete_code = ConfirmationCode::where('email', '=', $user->email)->first();
        $delete_code && $delete_code->delete();

        // cria codigo de verificação e adiciona um objeto com
        // os dados do email e codigo e também os salva.
        try {
            $code = strtoupper(substr(bin2hex(random_bytes(4)), 1));
        } catch (Exception $e) {
            return response()->json(['status' => 'erro', 'message' => $e->getMessage()]);
        }
        $confirmation_code = new ConfirmationCode();
        $confirmation_code->code = $code;
        $confirmation_code->email = $user->email;
        $confirmation_code->save();

        // classe instanciada da pasta mail que envia o email de
        // acordo com o template criado na view
        Mail::send(new checkEmail($user, $code));
        return response()->json(['status' => 'success'], 201);
    }
}
