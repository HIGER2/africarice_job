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
use App\Mail\CandidateSubmittedToTeam;
use App\Mail\CandidateSubmittedToUser;
use App\Models\Application;
use App\Models\Email;
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


    public function index()
    {

        $publications = Publication::Date()
            ->orderBy('created_at', 'desc')
            ->with('job', 'files')
            ->get();

        return Inertia::render('HomePage', [
            'user' => Auth::user(),
            'publications' => PublicationResource::collection($publications)
        ]);
    }

    public function updateOffreStatus(Request $request)
    {
        $request->validate([
            // 'status' => 'required|in:open,closed',
        ]);

        $publication = Publication::where('uuid', $request->uuid)->first();
        if (!$publication) {
            return response()->json(['message' => 'Publication not found'], 404);
        }

        switch ($request->type) {
            case 'is_closed':
                $publication->is_closed = $request->status;
                break;
            default:
                $publication->is_published = $request->status;
                break;
        }
        $publication->save();
        return back()->with('message', 'Publication status updated successfully');
        // return response()->json(['message' => 'Publication status updated successfully']);
    }

    public function profile()
    {
        $user = Auth::user();
        return Inertia::render('ProfilePage', [
            'user' => new UserResource($user->load(
                'application.diplomas',
                'application.cgiarInformation',
                'application.experiences',
                'application.identification',
                'application.origin',
                'application.references',
                'application.documents'
            )),
        ]);
    }

    public function applyJob($uuid)
    {
        $user = Auth::user();
        $publication = Publication::with('job')
            ->where('uuid', $uuid)
            ->where('is_closed', false)
            // ->where('expires_at', '>=', now())
            ->first();

        return Inertia::render('ApplyPage', [
            'publication' => $publication,
            'uuid' => $publication ? $publication->uuid : null,
            'user' => new UserResource(
                $user->load(
                    'application.diplomas',
                    'application.cgiarInformation',
                    'application.experiences',
                    'application.identification',
                    'application.origin',
                    'application.references',
                    'application.documents'
                )
            ),
        ]);
    }


    // Inscription
    public function register(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'last_name' => 'required',
                'phone' => 'required|numeric',
                'email' => 'required|email|unique:users,email',
            ],
            [
                'name.required' => 'Le nom est requis',
                'last_name.required' => 'Le prénom est requis',
                'phone.required' => 'Le téléphone est requis',
                'phone.numeric' => 'Le téléphone doit être numérique',
                'email.required' => 'L\'email est requis',
                'email.email' => 'Format d\'email invalide',
                'email.unique' => 'Cet email est déjà utilisé',
            ]
        );

        $user = User::create($request->all());
        // Générer code PIN
        $pin = rand(1000, 9999);
        $hash = Hash::make($pin);
        $user->pin = $pin;
        $user->save();
        session(['user_email' => $user->email]);
        // Envoyer email avec code PIN
        Mail::raw("Votre code PIN pour se connecter : $pin", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Code PIN de connexion');
        });
        // return redirect()->route('verify');
        return response()->json(['message' => 'Inscription réussie. Vérifiez votre email pour le code PIN.']);
    }


    // Login (email)
    public function verifyEmail(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
            ],
            [
                'email.required' => 'L\'email est requis',
                'email.email' => 'Format d\'email invalide',
            ]
        );

        $user = User::where('email', $request->email)->first();
        if (!$user) return response()->json(['message' => 'Utilisateur non trouvé'], 404);

        session(['user_email' => $user->email]);
        $pin = rand(1000, 9999);
        $hash = Hash::make($pin);
        $user->pin = $pin;
        $user->save();
        Mail::raw("Votre code PIN pour se connecter : $pin", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Code PIN de connexion');
        });

        return response()->json(['message' => 'Email envoyé avec le code PIN']);
    }

    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:4',
        ]);

        $email = session('user_email');
        $user = User::where('email', $email)->where('pin', $request->pin)->first();
        if (!$user) return response()->json(['message' => 'Code PIN incorrect'], 401);
        // if(!$user || !Hash::check($request->pin, $user->pin)) return response()->json(['message'=>'Code PIN incorrect'], 401);

        // Effacer le PIN
        $user->pin = null;
        $user->save();
        Auth::login($user);
        // Retourner succès / token
        return response()->json(['message' => 'Connexion réussie', 'user' => $user]);
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
                ], 404);
            }

            if ($publication->is_closed) {
                return response()->json([
                    'message' => 'Cette offre est clôturée.'
                ], 403);
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
                'user_id' => $userId,
                'publication_id' => $publication->id
            ]);
            // 3. Diplomas
            if (!empty($data['diplomas'])) {
                foreach ($data['diplomas'] ?? [] as $d) {
                    $application->diplomas()->updateOrCreate(
                        [
                            'application_id' => $application->id,
                            'uuid'             => $d['uuid'] ?? null, // sécurité si pas d'id
                        ],
                        $d
                    );
                }
            }

            // 4. Experiences
            if (!empty($data['experiences'])) {
                foreach ($data['experiences'] ?? [] as $e) {
                    $current = filter_var($e['current'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

                    $application->experiences()->updateOrCreate(
                        [
                            'application_id' => $application->id,
                            'uuid'             => $e['uuid'] ?? null,
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
            if (!empty($data['references'])) {
                foreach ($data['references'] ?? [] as $r) {
                    $application->references()->updateOrCreate(
                        [
                            'application_id' => $application->id,
                            'uuid'             => $r['uuid'] ?? null,
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
                foreach ($request['documents'] as $index => $doc) {
                    $uploadedFile = $request->file("documents.$index.file");

                    if ($uploadedFile && $uploadedFile->isValid()) {
                        $path = '/storage/' . $uploadedFile->store('applications', 'public');

                        $application->documents()->updateOrCreate(
                            [
                                'id'             => $doc['id'] ?? null,
                                // 'application_id' => $application->id,
                            ],
                            [
                                'path' => $path,
                                'name' => $uploadedFile->getClientOriginalName(),
                            ]
                        );
                    }
                }
            }



            $primaryEmail = Email::where('is_primary', 1)->first();
            if ($primaryEmail) {
                Mail::to($primaryEmail->email)
                    ->cc(Email::where('is_primary', 0)->pluck('email')->toArray())
                    ->send(new CandidateSubmittedToTeam($applicationApplication));
            }

            // 3️⃣ Envoyer mail au candidat
            Mail::to(Auth::user()->email)->send(new CandidateSubmittedToUser($applicationApplication));

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
            ], 500);
            //throw $th;
        }
    }

    public function manager(Request $request, $step = 'offres')
    {
        $data = [];
        $search = $request->search ?? null;

        switch ($step) {
            case 'offres':
                $data = OffreResource::collection(Publication::with(['job', 'files', 'candidatures.user'])->orderBy('created_at', 'desc')->get());
                break;
            case 'candidatures':
                $data = CandidatureResource::collection(PublicationApplication::with(['publication.job', 'user.application.origin', 'user.application.diplomas', 'user.application.documents'])->orderBy('created_at', 'desc')->get());
                break;
            case 'candidat':
                $data = UserCandidature::collection(
                    User::with(['application.origin', 'application.documents'])
                        ->when($search, function ($query, $search) {
                            $terms = explode(' ', $search);
                            $query->where(function ($q) use ($terms) {
                                foreach ($terms as $term) {
                                    $q->orWhere('name', 'like', "%{$term}%")
                                        ->orWhere('last_name', 'like', "%{$term}%");
                                }
                            });
                        })
                        ->orderBy('created_at', 'desc')
                        ->get()
                );
                break;
            case 'documents':
                $data = ApplicationResource::collection(Application::orderBy('created_at', 'desc')->get());
                break;
            case 'parametres':
                $emails = Email::orderBy('created_at', 'desc')->get();
                $admins =  user::where('role', 'admin')->orderBy('created_at', 'desc')->get();
                $data = (object)[
                    'admins' => $admins,
                    'emails' => $emails,
                ];
                break;
            default:
                $data = OffreResource::collection(Publication::with(['job', 'files', 'candidatures.user'])->orderBy('created_at', 'desc')->get());
                break;
        }

        return Inertia::render('ManagerPage', [
            'step' => $step,
            'user' => Auth::user(),
            'data' => $data,
            'search' => $search ?? null
        ]);
    }

    public function show($step = null, $uuid)
    {
        $data = [];
        switch ($step) {
            case 'offres':
                $data = OffreDetailResource::collection(
                    Publication::with([
                        'job',
                        'candidatures',
                        'files'
                    ])
                        ->where('uuid', $uuid)
                        ->get()
                );
                break;
            case 'candidatures':
                $data = CandidatureResource::collection(PublicationApplication::with(['publication.job', 'user.application.origin', 'user.application.documents'])
                    ->where('uuid', $uuid)
                    ->get());
                break;
            case 'candidat':
                $data = new UserResource(
                    user::with([
                        'application.origin',
                        'application.documents',
                        'application.diplomas',
                        'application.cgiarInformation',
                        'application.experiences',
                        'application.identification',
                    ])->where('uuid', $uuid)->first()
                );
                break;
            case 'documents':
                $data = ApplicationResource::collection(Application::get());
                break;
            default:
                $data = 'dashboard';
                break;
        }

        return Inertia::render('DetailPage', [
            'step' => $step,
            'user' => Auth::user(),
            'data' => $data
        ]);
    }



    public function storeOffre(OffreRequest $request)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();

            $dataPublication = [
                'type'          => 'public',
                'published_by'  => Auth::id(),
                'published_at'  => $request->offre['published_at'],
                'expires_at'    => $request->offre['expires_at'],
                'is_published'  => filter_var($request->offre['is_published'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            ];

            if (!empty($request->offre['uuid'])) {
                // 🔹 Mise à jour
                $publication = Publication::where("uuid", $request->offre["uuid"])->firstOrFail();

                $publication->update($dataPublication);

                $publication->job()->update([
                    'position_title'       => $request->offre['position_title'],
                    'country_duty_station' => $request->offre['country_duty_station'],
                ]);
            } else {
                // 🔹 Création recrutement
                $recrutement = Recrutement::create([
                    'position_title'       => $request->offre['position_title'],
                    'country_duty_station' => $request->offre['country_duty_station'],
                ]);

                // 🔹 Création publication
                $publication = Publication::create(array_merge($dataPublication, [
                    'recrutement_id' => $recrutement->id,
                ]));
            }

            // 🔹 Sauvegarde des documents
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    PublicationFile::create([
                        'publication_id' => $publication->id,
                        'path'           => 'storage/' . $file->store('documents', 'public'),
                        'name'           => $file->getClientOriginalName(),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success'        => true,
                'publication_id' => $publication->id,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Erreur interne',
                'erreur'  => $th->getMessage(),
            ], 500);
        }
    }


    public function storeEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'is_primary' => 'boolean',
        ]);

        // Si on ajoute un email principal, on enlève l’ancien principal
        if ($request->is_primary) {
            Email::where('is_primary', true)->update(['is_primary' => false]);
        }

        $email = Email::updateOrcreate(
            [
                'email' => $request->email,
            ],
            [
                'email' => $request->email,
                'is_primary' => $request->is_primary ?? false,
            ]
        );

        return back()->with('message', 'Publication status updated successfully');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        $email = User::create(array_merge(['role' => 'admin'], $request->all()));
        return back()->with('message', 'Publication status updated successfully');
    }
    // Supprimer un email
    public function destroyEmail($id)
    {
        $email = Email::findOrFail($id);
        $email->delete();

        return response()->json(['message' => 'Email supprimé avec succès']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login'); // Redirige vers la page de login
    }
}
