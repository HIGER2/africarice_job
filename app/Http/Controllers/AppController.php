<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidatureRequest;
use App\Http\Requests\OffreRequest;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\CandidatureResource;
use App\Http\Resources\OffreDetailResource;
use App\Http\Resources\OffreResource;
use App\Http\Resources\PublicationResource;
use App\Http\Resources\PublicationTracking;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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

    public function applySuccess($uuid)
    {
        $publication = Publication::with('job')
            ->where('uuid', $uuid)
            ->first();
        if ($publication) {
            return Inertia::render('ApplySuccess', ['publication' => $publication]);
        }
        return redirect()->route('home')->with('error', 'Publication introuvable');
    }

    public function offreDetail($uuid)
    {

        $publications = Publication::with('job')->where("uuid", $uuid)
            ->first();
        return response()->json(["data" => $publications]);
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

    public function applyJob(Request $request, $uuid = null)
    {
        $user = Auth::user();
        $publication = null;
        $application_type = $request->query('application_type');

        if ($uuid) {
            $publication = Publication::with('job')
                ->where('uuid', $uuid)
                ->where('status', 'open')
                // ->where('expires_at', '>=', now())
                ->first();
        }

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

    public function completedProfile(Request $request)
    {
        $user = Auth::user();
        // return Inertia::render('CompletedPage');

        return Inertia::render('CompletedPage', [
            // 'publication' => $publication,
            // 'uuid' => $publication ? $publication->uuid : null,
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


    public function deleteApplicationData(Request $request)
    {
        try {
            $user = Auth::user();
            $uuid = $request->uuid;
            $type = $request->type;

            // Vérifie que le type est valide
            if (!in_array($type, ['experiences', 'references', 'diplomas'])) {
                return response()->json(['message' => 'Type invalide.'], 400);
            }

            // Vérifie si l'élément existe
            $exists = $user->application->$type()->where('uuid', $uuid)->exists();

            if (!$exists) {
                return response()->json(['message' => 'Élément non trouvé.'], 404);
            }

            // Supprime l'élément
            $user->application->$type()->where('uuid', $uuid)->delete();

            return response()->json([
                'message' => 'Suppression réussie. / Deletion successful.'
            ]);
        } catch (\Exception $e) {
            // Retourne une erreur si quelque chose ne va pas
            return response()->json([
                'message' => 'Une erreur est survenue. / An error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Inscription
    public function registerOrUpdate(Request $request)
    {
        $user = Auth::user();
        // ✅ Règles de validation de base
        $rules = [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'country_code' => 'required|string|max:255',
        ];

        if ($user) {
            // ✅ Si connecté : autoriser la modification mais vérifier unicité si email/téléphone changent
            $rules['phone'] = [
                'required',
                'numeric',
                Rule::unique('users', 'phone')->ignore($user->id),
            ];
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ];
        } else {
            // ✅ Si non connecté : création stricte
            $rules['phone'] = 'required|numeric|unique:users,phone';
            $rules['email'] = 'required|email|unique:users,email';
        }

        $messages = [
            'name.required' => 'Le nom est requis / Name is required',
            'last_name.required' => 'Le prénom est requis / Last name is required',
            'phone.required' => 'Le téléphone est requis / Phone is required',
            'phone.numeric' => 'Le téléphone doit être numérique / Phone must be numeric',
            'email.required' => 'L\'email est requis / Email is required',
            'email.email' => 'Format d\'email invalide / Invalid email format',
            'email.unique' => 'Cet email est déjà utilisé / This email is already taken',
            'phone.unique' => 'Ce numéro de téléphone est déjà utilisé / This phone number is already taken',
        ];

        $validated = $request->validate($rules, $messages);

        // ✅ Si utilisateur connecté → mise à jour
        if ($user) {
            $user->update($validated);
            return response()->json(['message' => 'Profil mis à jour avec succès / Profile updated successfully.']);
        }
        // ✅ Sinon → création + génération du PIN
        $user = User::create($validated);
        $pin = rand(1000, 9999);
        $user->pin = $pin;
        $user->save();
        // ✅ Sauvegarde email en session
        session(['user_email' => $user->email]);

        // ✅ Envoi du PIN par email
        Mail::raw("Votre code PIN pour vous connecter : $pin / Your login PIN is: $pin", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Code PIN de connexion / Login PIN');
        });

        return response()->json(['message' => 'Inscription réussie. Vérifiez votre email pour le code PIN. / Registration successful. Check your email for the PIN.']);
    }


    // Login (email)
    public function verifyEmail(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
            ],
            [
                'email.required' => "L'email est requis / Email is required",
                'email.email' => "Format d'email invalide / Invalid email format",
            ]

        );

        $user = User::where('email', $request->email)->first();
        if (!$user) return response()->json(['message' => 'Utilisateur non trouvé / User not found'], 404);

        session(['user_email' => $user->email]);


        $pin = rand(1000, 9999);
        $hash = Hash::make($pin);
        $user->pin = $pin;
        $user->save();

        if (env('APP_ENV') !== 'local') {
            Mail::raw("Votre code PIN pour se connecter : $pin / Your login PIN is: $pin", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Code PIN de connexion / Login PIN');
            });
        }





        return response()->json(['message' => 'Email envoyé avec le code PIN']);
    }

    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:4',
        ]);

        $IsCompleted = $request->query('is_complete');


        $email = session('user_email');

        if (env('APP_ENV') === 'local') {
            $user = User::where('email', $email)->first();
        } else {
            $user = User::where('email', $email)->where('pin', $request->pin)->first();
            if (!$user) return response()->json(['message' => 'Code PIN incorrect | Incorrect PIN'], 401);
        }

        // if(!$user || !Hash::check($request->pin, $user->pin)) return response()->json(['message'=>'Code PIN incorrect'], 401);

        // Effacer le PIN
        $user->pin = null;
        $user->save();
        Auth::login($user);
        // Retourner succès / token
        $redirect = $user->is_active == 'active' ? ($user->role == 'admin' ? '/manager/offres' : '/') : '/completed';
        return response()->json([
            'message' => 'Connexion réussie',
            'user' => $user,
            'redirect' => $redirect
        ]);
    }


    public function storeOrUpdate(CandidatureRequest $request)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();

            $data = $request->all(); // le JSON que tu envoies

            $user = Auth::user();
            $userId = $user->id;
            $applicationApplication = null;
            // Vérifier si la publication existe via UUID
            if (isset($request->uuid)) {

                $publication = Publication::where('uuid', $request->input('uuid'))->first();

                if (!$publication) {
                    return response()->json([
                        'message' => 'Job publication not found.'
                    ], 404);
                }

                if ($publication->is_closed) {
                    return response()->json([
                        'message' => "Cette offre est clôturée. | This job posting is closed."
                    ], 403);
                }

                // 1. Vérifie si la candidature existe déjà
                $existingApplication = PublicationApplication::where('user_id', $userId)
                    ->where('publication_id', $publication->id)
                    ->first();

                if ($existingApplication) {
                    return response()->json([
                        'message' => 'You have already applied for this position. | Vous avez déjà postulé à cette offre.',
                        'application' => $existingApplication,
                    ], 409); // 409 Conflict
                }

                //2 Créer une nouvelle application
                $applicationApplication = PublicationApplication::create([
                    'user_id' => $userId,
                    'publication_id' => $publication->id
                ]);
            }

            // 2. Application principale
            $application = Application::firstOrCreate(
                [
                    'user_id' => $userId,
                ],
                [
                    'uuid' => Str::uuid(),
                    'user_id' => $userId,
                ]
            );

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
                    // $current = filter_var($e['current'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
                    $current = filter_var($e['current'] ?? false, FILTER_VALIDATE_BOOLEAN);

                    $application->experiences()->updateOrCreate(
                        [
                            'application_id' => $application->id,
                            'uuid'             => $e['uuid'] ?? null,
                        ],
                        [
                            'company_name' => $e['company_name'] ?? null,
                            'position'     => $e['position'] ?? null,
                            'start_date'   => $e['start_date'] ?? null,
                            'end_date'     => $current ? null : ($e['end_date'] ?? null),
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
                        $path = '/storage/' . $uploadedFile->store('cv', 'public');

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

            if ($applicationApplication) {
                $primaryEmail = Email::where('is_primary', 1)->first();
                if ($primaryEmail) {
                    Mail::to($primaryEmail->email)
                        ->cc(Email::where('is_primary', 0)->pluck('email')->toArray())
                        ->send(new CandidateSubmittedToTeam($applicationApplication));
                }
                // 3️⃣ Envoyer mail au candidat
                Mail::to(Auth::user()->email)->send(new CandidateSubmittedToUser($applicationApplication));
            }


            DB::commit();
            return response()->json([
                'success' => true,
                'application_id' => $application->id,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erreur interne | Internal error',
                'erreur' => $th->getMessage(),
            ], 500);
            //throw $th;
        }
    }

    public function spontanousApplication(Request $request)
    {
        try {
            // $validated = $request->validated();


            DB::beginTransaction();

            // $userId = Auth::user()->id;
            // // Vérifier si la publication existe via UUID
            // // 2. Application principale
            // $application = Application::create(
            //     [
            //         'uuid' => Str::uuid(),
            //         'user_id' => $userId,
            //         'application_type' => 'spontaneous'
            //     ]
            // );

            return Inertia::render('SpontaneousPage');

            DB::commit();


            return inertia::render('SpontaneousPage');
            // return response()->json([
            //     'success' => true,
            //     'application_id' => $application->id,
            // ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erreur interne | Internal error',
                'erreur' => $th->getMessage(),
            ], 500);
            //throw $th;
        }
    }

    public function storeCompletedProfile(CandidatureRequest $request)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();

            $data = $request->all(); // le JSON que tu envoies
            $user = Auth::user();
            $userId = $user->id;
            // 2. Application principale
            $application = Application::firstOrCreate(
                [
                    'user_id' => $userId,
                ],
                [
                    'uuid' => Str::uuid(),
                    'user_id' => $userId,
                ]
            );
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
                        $path = '/storage/' . $uploadedFile->store('cv', 'public');

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

            // Auth::user()->update(['is_active' => 'active']);
            $user['is_active'] = 'active';
            $user->save();

            DB::commit();

            return response()->json([
                'success' => "lololo",
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erreur interne | Internal error',
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
                Publication::whereDate('expires_at', '<', Carbon::now('Africa/Abidjan')->toDateString())
                    ->where('status', '!=', 'closed')
                    ->update(['status' => 'closed']);
                $offres = OffreResource::collection(
                    Publication::with(['job', 'files', 'candidatures.user', 'lastTracking'])
                        ->when($search, function ($q) use ($search) {
                            $q->whereHas('job', function ($jobQuery) use ($search) {
                                $jobQuery->where('position_title', 'like', "%{$search}%")
                                    ->orWhere('reference', 'like', "%{$search}%");
                            });
                        })
                        ->orderBy('created_at', 'desc')
                        ->get()
                );
                $admins = User::where('role', 'admin')
                    ->select('id', 'name', 'last_name')
                    ->selectRaw("CONCAT(name, ' ', last_name) as fullname")
                    ->get();

                $data = (object)[
                    "offer" => $offres,
                    "assign" => $admins
                ];

                break;
            case 'candidatures':
                $data = CandidatureResource::collection(PublicationApplication::with(['publication.job', 'publication', 'user.application.origin', 'user.application.diplomas', 'user.application.documents'])->orderBy('created_at', 'desc')->get());
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
            case 'offerTracker':
                $data = new PublicationTracking(
                    Publication::with([
                        'trakings',
                    ])
                        ->where('uuid', $uuid)
                        ->first()
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

    public function offorTracker($uuid)
    {
        $data = [];
        $data = OffreDetailResource::collection(
            Publication::with([
                'job',
                'candidatures',
                'files'
            ])
                ->where('uuid', $uuid)
                ->get()
        );

        return Inertia::render('DetailPage', [
            'step' => 3,
            'user' => Auth::user(),
            'data' => $data
        ]);
    }

    // public function storeOffre(OffreRequest $request)
    // {
    //     try {
    //         $validated = $request->validated();

    //         DB::beginTransaction();

    //         $dataPublication = [
    //             'type'          => $request->offre['type'],
    //             'published_by'  => Auth::id(),
    //             'expires_at'    => $request->offre['expires_at'],
    //             'is_published'  => filter_var($request->offre['is_published'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
    //             'is_closed' => Carbon::parse($request->offre['expires_at'], 'Africa/Abidjan')->toDateString() < Carbon::now('Africa/Abidjan')->toDateString() ? true : false,
    //         ];

    //         $publication = Publication::where("uuid", $request->offre["uuid"])->first();

    //         if ($publication) {
    //             // Ancienne date déjà enregistrée
    //             $newDate = Carbon::parse($request->offre['published_at']);
    //             $oldDate = Carbon::parse($publication->published_at);
    //             if (!$newDate->eq($oldDate)) {
    //                 // Vérifie si la date de publication actuelle n'est pas encore passée
    //                 if ($oldDate->lt(Carbon::now())) {
    //                     return response()->json([
    //                         'status' => 'error',
    //                         'message' => 'Unable to update: the publication date has already passed and cannot be modified.',
    //                     ], 400);
    //                 }
    //                 $dataPublication["published_at"] = $request->offre['published_at'];
    //             }

    //             $publication->update($dataPublication);

    //             $publication->job()->update([
    //                 'position_title'       => $request->offre['position_title'],
    //                 'country_duty_station' => $request->offre['country_duty_station'],
    //             ]);
    //         } else {
    //             // 🔹 uuid introuvable → créer une nouvelle publication
    //             $dataPublication["published_at"] = $request->offre['published_at'];
    //             $recrutement = Recrutement::create([
    //                 'position_title'       => $request->offre['position_title'],
    //                 'country_duty_station' => $request->offre['country_duty_station'],
    //             ]);

    //             $publication = Publication::create(array_merge($dataPublication, [
    //                 'recrutement_id' => $recrutement->id,
    //             ]));
    //         }

    //         // 🔹 Sauvegarde des documents
    //         if ($request->hasFile('documents')) {
    //             // 🔹 3. Récupérer le premier document (ex: CV)
    //             $document = $publication?->files()->first();

    //             $file = $request->file('documents')[0];
    //             // 🔹 5. Enregistrer le nouveau fichier
    //             $path = '/storage/' . $file->store('documents', 'public');
    //             $filename = $file->getClientOriginalName();

    //             // 🔹 6. Mettre à jour ou créer le document
    //             if ($document) {
    //                 // 🔹 4. Supprimer l'ancien fichier si existe
    //                 $relativePath = str_replace('/storage/', '', $document->path);
    //                 if ($relativePath && Storage::disk('public')->exists($relativePath)) {
    //                     Storage::disk('public')->delete($relativePath);
    //                 }

    //                 $document->update([
    //                     'name' => $filename,
    //                     'path' => $path,
    //                     // 'type' => 'cv', // optionnel
    //                 ]);
    //             } else {
    //                 $document = $publication->files()->create([
    //                     'name' => $filename,
    //                     'path' => $path,
    //                     // 'type' => 'cv',
    //                 ]);
    //             }

    //             // foreach ($request->file('documents') as $file) {
    //             //     PublicationFile::create([
    //             //         'publication_id' => $publication->id,
    //             //         'path'           => 'storage/' . $file->store('documents', 'public'),
    //             //         'name'           => $file->getClientOriginalName(),
    //             //     ]);
    //             // }
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'success'        => true,
    //             'publication_id' => $publication->id,
    //         ]);
    //     } catch (\Throwable $th) {
    //         DB::rollBack();

    //         return response()->json([
    //             'message' => 'Erreur interne',
    //             'erreur'  => $th->getMessage(),
    //         ], 500);
    //     }
    // }

    public function storeOffre(OffreRequest $request)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();

            $offre = $request->offre;

            // Déterminer le statut
            $status = $offre['status'] ?? 'pending';
            if (Carbon::parse($offre['expires_at'], 'Africa/Abidjan')
                ->lt(Carbon::now('Africa/Abidjan'))
            ) {
                $status = 'closed';
            }

            // Vérifier si la publication existe
            $publication = Publication::where("uuid", $offre["uuid"])->first();

            if ($publication) {
                // 🔹 Mise à jour de la publication
                $publishedAt = Carbon::parse($offre['published_at'], 'Africa/Abidjan');
                $expiresAt  = Carbon::parse($offre['expires_at'], 'Africa/Abidjan');

                $publication->update([
                    'type'         => $offre['type'] ?? null,
                    'published_by' => Auth::id(),
                    'published_at' => $publishedAt,
                    'expires_at'   => $expiresAt,
                    'status'       => $status,
                ]);

                // 🔹 Mise à jour du recrutement lié
                $publication->job()->update([
                    // 'reference'           => $offre['reference'],
                    'position_title'      => $offre['position_title'],
                    'country_duty_station' => $offre['country_duty_station'],
                    'center'              => $offre['center'],
                    'manager'             => $offre['manager'],
                    'reason'              => $offre['reason'],
                    'reason_replacement'  => $offre['reason_replacement'] ?? null,
                    'city_duty_station'     => $offre['city_duty_station'] ?? null,
                    'division'           => $offre['division'] ?? null,
                    'grade'           => $offre['grade'] ?? null,
                    'program'           => $offre['program'] ?? null,
                    'assign_by'           => $offre['assign_by'] ?? null,
                ]);
            } else {
                // 🔹 Création du recrutement
                $recrutement = Recrutement::create([
                    'reference'           => $offre['reference'],
                    'position_title'      => $offre['position_title'],
                    'country_duty_station' => $offre['country_duty_station'],
                    'center'              => $offre['center'],
                    'manager'             => $offre['manager'],
                    'reason'              => $offre['reason'],
                    'reason_replacement'  => $offre['reason_replacement'] ?? null,
                    'assign_by'           => $offre['assign_by'] ?? null,
                    'city_duty_station'     => $offre['city_duty_station'] ?? null,
                    'division'           => $offre['division'] ?? null,
                    'grade'           => $offre['grade'] ?? null,
                    'program'           => $offre['program'] ?? null,
                ]);
                // 🔹 Création de la publication
                $publication = Publication::create([
                    'uuid'         => $offre['uuid'],
                    'reference'    => $offre['reference'],
                    'type'         => $offre['type'] ?? null,
                    'published_by' => Auth::id(),
                    'published_at' => $offre['published_at'],
                    'expires_at'   => $offre['expires_at'],
                    'status'       => $status,
                    'recrutement_id' => $recrutement->id,
                ]);
            }

            // 🔹 Sauvegarde ou mise à jour du fichier
            if ($request->hasFile('documents')) {
                $file = $request->file('documents')[0];
                $path = '/storage/' . $file->store('documents', 'public');
                $filename = $file->getClientOriginalName();

                $document = $publication->files()->first();

                if ($document) {
                    // Supprime l’ancien
                    $relativePath = str_replace('/storage/', '', $document->path);
                    if ($relativePath && Storage::disk('public')->exists($relativePath)) {
                        Storage::disk('public')->delete($relativePath);
                    }

                    $document->update([
                        'name' => $filename,
                        'path' => $path,
                    ]);
                } else {
                    $publication->files()->create([
                        'name' => $filename,
                        'path' => $path,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'publication_id' => $publication->id,
                'status' => $status,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Erreur interne',
                'erreur' => $th->getMessage(),
            ], 500);
        }
    }


    public function storeOffreTranking(Request $request)
    {
        try {
            // $validated = $request->validated();

            $publication = Publication::where("uuid", $request["uuid"])->first();

            if (!$publication) {
                return response()->json([
                    'message' => 'Job publication not found.'
                ], 404);
            }

            // Si tu veux mettre à jour ou créer un tracking
            $tracking = $publication->updateStatus($request->all());


            return response()->json([
                'success'        => true,
                'tracking' => $tracking,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Erreur interne',
                'erreur'  => $th->getMessage(),
            ], 500);
        }
    }

    public function uploadCv(Request $request)
    {
        // 🔹 1. Validation du fichier
        $request->validate([
            'cv' => 'required|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5 MB max
        ]);

        $user = Auth::user();

        // 🔹 2. Vérifier si l'utilisateur a une application
        if (!$user->application) {
            return response()->json(['message' => 'No application found for this user.'], 404);
        }

        // 🔹 3. Récupérer le premier document (ex: CV)
        $document = $user->application->documents()->first();
        // 🔹 4. Supprimer l'ancien fichier si existe
        if ($document && $document->path) {
            $relativePath = str_replace('/storage/', '', $document->path);
            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            }
        }

        // 🔹 5. Enregistrer le nouveau fichier
        $path = '/storage/' . $request->file('cv')->store('cvs', 'public');
        $filename = $request->file('cv')->getClientOriginalName();

        // 🔹 6. Mettre à jour ou créer le document
        if ($document) {
            $document->update([
                'name' => $filename,
                'path' => $path,
                // 'type' => 'cv', // optionnel
            ]);
        } else {
            $document = $user->application->documents()->create([
                'name' => $filename,
                'path' => $path,
                // 'type' => 'cv',
            ]);
        }

        // 🔹 7. Réponse JSON
        return response()->json([
            'message' => 'CV uploaded successfully!',
            'document' => $document,
        ]);
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

        $email = User::create(array_merge(['role' => 'admin', 'is_active' => 'active'], $request->all()));
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

    /**
     * Gère les relations multiples (diplomas, references, etc.)
     */
    private function syncData($application, string $relation, array $items)
    {
        foreach ($items as $item) {
            $application->$relation()->updateOrCreate(
                ['uuid' => $item['uuid'] ?? null],
                $item
            );
        }
    }

    /**
     * Gère les expériences (avec booléen current)
     */
    private function syncExperiences($application, array $experiences)
    {
        foreach ($experiences as $e) {
            $application->experiences()->updateOrCreate(
                ['uuid' => $e['uuid'] ?? null],
                [
                    'company_name' => $e['company_name'] ?? null,
                    'position'     => $e['position'] ?? null,
                    'start_date'   => $e['start_date'] ?? null,
                    'end_date'     => $e['end_date'] ?? null,
                    'current'      => filter_var($e['current'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
                ]
            );
        }
    }

    /**
     * Gère les relations simples (1-to-1)
     */
    private function syncSingle($application, string $relation, array $data)
    {
        if (!empty($data)) {
            if (isset($data['current'])) {
                $data['current'] = filter_var($data['current'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            }
            $application->$relation()->updateOrCreate(['application_id' => $application->id], $data);
        }
    }

    /**
     * Gère l’upload et la sauvegarde des documents
     */
    private function syncDocuments($application, Request $request)
    {
        if ($request->has('documents')) {
            foreach ($request['documents'] as $index => $doc) {
                $file = $request->file("documents.$index.file");

                if ($file && $file->isValid()) {
                    $path = '/storage/' . $file->store('cv', 'public');

                    $application->documents()->updateOrCreate(
                        ['id' => $doc['id'] ?? null],
                        [
                            'path' => $path,
                            'name' => $file->getClientOriginalName(),
                        ]
                    );
                }
            }
        }
    }
}
