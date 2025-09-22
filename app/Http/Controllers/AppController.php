<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidatureRequest;
use App\Http\Requests\OffreRequest;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\CandidatureResource;
use App\Http\Resources\OffreDetailResource;
use App\Http\Resources\OffreResource;
use App\Http\Resources\PublicationResource;
use App\Http\Resources\UserCandidature;
use App\Http\Resources\UserResource;
use App\Models\Application;
use App\Models\Publication;
use App\Models\PublicationApplication;
use App\Models\PublicationFile;
use App\Models\Recrutement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AppController extends Controller
{
    
    
    public function index(){

        $publications = Publication::where('is_closed', false)
            ->where(function($query) {
                // $query->whereNull('expires_at')
                //     ->orWhere('expires_at', '>', Carbon::now());
            })
            ->orderBy('created_at', 'desc')
            ->with('job','files')
            ->get();
            
        return Inertia::render('HomePage',[
            'user'=>Auth::user(),
            'publications'=>PublicationResource::collection($publications)
        ]);
    }

    public function applyJob($uuid){
        $publication = Publication::with('job')
            ->where('uuid', $uuid)
            ->where('is_closed', false)
            // ->where('expires_at', '>=', now())
            ->first();

        return Inertia::render('ApplyPage', [
            'publication' => $publication,
            'uuid' => $publication ? $publication->uuid : null,
            'user'=>Auth::user(),
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


    public function storeOrUpdate(CandidatureRequest $request)
    {
        try {
                $validated = $request->validated();

            DB::beginTransaction();

            $data = $request->all(); // le JSON que tu envoies

        $userId = Auth::user()->id; 

         // Vérifier si la publication existe via UUID
        $publication = Publication::where('uuid', $request->input('uuid'))->first();

        if (!$publication) {
            return response()->json([
                'message' => 'Job publication not found.'
            ],404);
        }

        $application = Application::firstOrCreate(
            [
                'user_id' => $userId,
            ],
            [
                'uuid' => Str::uuid(),
                'user_id' => $userId,
            ]
        );

        // 1. Vérifie si la candidature existe déjà
        $existingApplication = PublicationApplication::where('user_id', $userId)
        ->where('publication_id', $publication->id)
        ->first();

        if ($existingApplication) {
            return response()->json([
                'message' => 'Vous avez déjà postulé à cette offre.',
                'application' => $existingApplication,
            ], 409); // 409 Conflict
        }

        //2 Créer une nouvelle application
        $applicationApplication = PublicationApplication::create([
            'user_id'=>$userId,
            'publication_id'=>$publication->id
        ]);
                                        // 3. Diplomas
                        if (!empty($data['diplomas'])) {
                            foreach ($data['diplomas'] ?? [] as $d) {
                                $application->diplomas()->updateOrCreate(
                                    [
                                        'application_id' => $application->id,
                                        'id'             => $d['id'] ?? null, // sécurité si pas d'id
                                    ],
                                    $d
                                );
                            }
                        }

                        // 4. Experiences
                        if (!empty($data['experience'])) {
                            foreach ($data['experience'] ?? [] as $e) {
                                $current = filter_var($e['current'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

                                $application->experiences()->updateOrCreate(
                                    [
                                        'application_id' => $application->id,
                                        'id'             => $e['id'] ?? null,
                                    ],
                                    [
                                        'company_name' => $e['company_name'] ?? null,
                                        'position'     => $e['position'] ?? null,
                                        'start_date'   => $e['start_date'] ?? null,
                                        'end_date'     => $e['end_date'] ?? null,
                                        'current'      => $current,
                                    ]
                                );
                            }
                        }

                        // 5. References
                        if (!empty($data['reference'])) {
                            foreach ($data['reference'] ?? [] as $r) {
                                $application->references()->updateOrCreate(
                                    [
                                        'application_id' => $application->id,
                                        'id'             => $r['id'] ?? null,
                                    ],
                                    $r
                                );
                            }
                        }

                        // 6. CGIAR Information
                        if (!empty($data['cgiar_information'])) {
                            $cgiarData = $data['cgiar_information'] ?? [];

                            // sécuriser les booléens
                            if (isset($cgiarData['current'])) {
                                $cgiarData['current'] = filter_var($cgiarData['current'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
                            }

                            $application->cgiarInformation()->updateOrCreate(
                                ['application_id' => $application->id],
                                $cgiarData
                            );
                        }

                        // 7. Identification
                        if (!empty($data['identification'])) {
                            $application->identification()->updateOrCreate(
                                ['application_id' => $application->id],
                                $data['identification'] ?? []
                            );
                        }

                        // 8. Origin
                        if (!empty($data['origin'])) {
                            $application->origin()->updateOrCreate(
                                ['application_id' => $application->id],
                                $data['origin'] ?? []
                            );
                        }

                        // 9. Documents
                        if ($request->has('documents')) {
                            foreach ($request->input('documents', []) as $index => $doc) {
                                $uploadedFile = $request->file("documents.$index.file");

                                if ($uploadedFile && $uploadedFile->isValid()) {
                                    $path = $uploadedFile->store('applications', 'public');

                                    $application->documents()->updateOrCreate(
                                        [
                                            'id'             => $doc['id'] ?? null,
                                            'application_id' => $application->id,
                                        ],
                                        [
                                            'path' => $path,
                                            'name' => $uploadedFile->getClientOriginalName(),
                                        ]
                                    );
                                }
                            }
                        }


            DB::commit();
            return response()->json([
                'success' => true,
                'application_id' => $application->id,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'erreur interne',
                'erreur' => $th->getMessage(),
            ],500);
            //throw $th;
        }
    }

    public function manager($step=null)
    {
        $data = [];
        switch ($step) {
            case 'offres':
                $data = OffreResource::collection(Publication::with(['job','files', 'candidatures.user'])->orderBy('created_at','desc')->get()) ;
                break;
            case 'candidatures':
                    $data = CandidatureResource::collection(PublicationApplication::with(['publication.job','user.application.origin','user.application.documents'])->orderBy('created_at','desc')->get()) ;
                break;
            case 'candidat':
                    $data = UserCandidature::collection(user::with(['application.origin','application.documents'])->orderBy('created_at','desc')->get()) ;
                break;
            case 'documents':
                $data = ApplicationResource::collection(Application::orderBy('created_at','desc')->get());
                break;
            default:
                $data = 'dashboard';
                break;
        }

        return Inertia::render('ManagerPage',[
            'step'=>$step,
            'user'=>Auth::user(),
            'data'=>$data
        ]);
    }

    public function show($step=null,$uuid)
    {
        $data = [];
        switch ($step) {
            case 'offres':
                $data = OffreDetailResource::collection(
                    Publication::with([
                    'job',
                    'candidatures.user' // toutes les candidatures avec leurs candidats
                ])
                ->where('uuid', $uuid)
                ->get()
                );
                break;
            case 'candidatures':
                    $data = CandidatureResource::collection(PublicationApplication::with(['publication.job','user.application.origin','user.application.documents'])->get()) ;
                break;
            case 'candidat':
                    $data =UserResource::collection(user::with([
                        'application.origin' ,
                        'application.documents',
                        'application.diplomas',
                        'application.cgiarInformation',
                        'application.experiences',
                        'application.identification',
                        ])->where('uuid', $uuid)->get())  ;
                break;
            case 'documents':
                $data = ApplicationResource::collection(Application::get());
                break;
            default:
                $data = 'dashboard';
                break;
        }

        return Inertia::render('DetailPage',[
            'step'=>$step,
            'user'=>Auth::user(),
            'data'=>$data
        ]);
    }

    public function storeOffre(OffreRequest $request)
    {
        try {
                $validated = $request->validated();
            //   return response()->json([
            //     'message' => 'erreur interne',
            //     'erreur' => $request->offre,
            // ],500);

            DB::beginTransaction();
            // Validation
        $validated = $request->validate([
            'offre.position_title' => 'required|string|max:255',
            'offre.country_duty_station' => 'required|string|max:255',
            'offre.published_at' => 'required|date',
            'offre.expires_at' => 'required|date|after:offre.published_at',
            'offre.is_published' => 'nullable|string',
            // 'offre.is_closed' => 'nullable|boolean',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:2048',
        ]);

        // enregistrer le job

        $recuretement = Recrutement::create([
            'position_title'=> $request->offre['position_title'],
            'country_duty_station'=>$request->offre['country_duty_station'],
        ]);

        // creer une publication
        $publication =Publication::create([
        'type'=>'public',
        'recrutement_id'=>$recuretement->id,
        'published_at'=>$request->offre['published_at'],
        'expires_at'=> $request->offre['expires_at'],
        'is_published'=>!empty($request->offre['is_published']) ? 1 : 0
        ]);

        // Exemple : sauvegarde des documents
        $paths = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                // $paths[] = $file->store('documents', 'public'); // en storage/app/public/documents
                PublicationFile::create([
                    'publication_id'=> $publication->id,
                    'path'=> $file->store('documents', 'public'),
                    'name'=> $file->getClientOriginalName()
                ]);
            }
        }

        DB::commit();
            return response()->json([
                'success' => true,
                'application_id' => 'kj',
            ]);
        } catch (\Throwable $th) {
            
             DB::rollBack();
            return response()->json([
                'message' => 'erreur interne',
                'erreur' => $th->getMessage(),
            ],500);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login'); // Redirige vers la page de login
    }
}
