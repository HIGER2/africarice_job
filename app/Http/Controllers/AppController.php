<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class AppController extends Controller
{
    
    public function index(){
        return Inertia::render('HomePage',[
            'user'=>Auth::user()
        ]);
    }


    // Inscription
    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'last_name'=>'required',
            'phone'=>'required|numeric',
            'email'=>'required|email|unique:users,email',
        ]);

        $user = User::create($request->all());
        // Générer code PIN
        $pin = rand(1000,9999);
        $hash = Hash::make($pin);
        $user->pin =$pin ;
        $user->save();
        session(['user_email' => $user->email]);
        // Envoyer email avec code PIN
        // Mail::raw("Votre code PIN pour se connecter : $pin", function($message) use ($user){
        //     $message->to($user->email)
        //             ->subject('Code PIN de connexion');
        // });
        // return redirect()->route('verify');
        return response()->json(['message'=>'Inscription réussie. Vérifiez votre email pour le code PIN.']);
    }


    // Login (email)
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if(!$user) return response()->json(['message'=>'Utilisateur non trouvé'], 404);

        session(['user_email' => $user->email]);
        $pin = rand(1000,9999);
        $hash = Hash::make($pin);
        $user->pin = $pin;
        $user->save();

        // Mail::raw("Votre code PIN pour se connecter : $pin", function($message) use ($user){
        //     $message->to($user->email)
        //             ->subject('Code PIN de connexion');
        // });

        return response()->json(['message'=>'Email envoyé avec le code PIN']);
    }

    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin'=>'required|digits:4',
        ]);

        $email = session('user_email'); 
        $user = User::where('email',$email)->where('pin',$request->pin)->first();
        if(!$user) return response()->json(['message'=>'Code PIN incorrect'], 401);
        // if(!$user || !Hash::check($request->pin, $user->pin)) return response()->json(['message'=>'Code PIN incorrect'], 401);

        // Effacer le PIN
        $user->pin = null;
        $user->save();
        Auth::login($user);
        // Retourner succès / token
        return response()->json(['message'=>'Connexion réussie','user'=>$user]);
    }
}
