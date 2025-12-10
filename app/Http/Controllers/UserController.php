<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dentist;
use App\Models\Patient;
use Illuminate\Database\QueryException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
    

class UserController extends SearchableController
{
    const MAX_ITEMS = 5;

    #[\Override]
    function getQuery(): Builder
    {
        return User::orderBy('id');
    }

    #[\Override]
    function applyWhereToFilterByTerm(Builder $query, string $word): void
    {
        $query
            ->orWhere('name', 'LIKE', "%{$word}%")
            ->orWhere('role', 'LIKE', "%{$word}%");
    }

    function list(ServerRequestInterface $request): View
    {
        Gate::authorize('list', User::class);

        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria);

        return view('users.list', [
            'criteria' => $criteria,
            'users' => $query->paginate(self::MAX_ITEMS),
        ]);
    }

    function view(string $userEmail): View
    {
        $user = User
            ::where('email', $userEmail)->firstOrFail();

        Gate::authorize('view', $user);

        return view('users.view', [
            'user' => $user,
        ]);
    }

    function showCreateForm(ServerRequestInterface $request): View
    {
        Gate::authorize('create', User::class);

        $role = $request->getQueryParams()['role'] ?? null;
        $names = [];
    
        if ($role === 'DENTIST') {
            $names = Dentist::whereDoesntHave('user')->orderBy('dentist_name')->pluck('dentist_name', 'dentist_id');
        } elseif ($role === 'PATIENT') {
            $names = Patient::whereDoesntHave('user')->orderBy('patient_name')->pluck('patient_name', 'patient_id');
        }

        return view('users.create-form', compact('role', 'names'));
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        try {
        $data = $request->getParsedBody();
        $role = $data['role'] ?? null;
        $name = $data['name'] ?? null;

        if ($role === 'PATIENT') {
            $patient = Patient::find($name);
            $realName = $patient ? $patient->patient_name : null;
            $patientId = $patient ? $patient->patient_id : null;
        } elseif ($role === 'DENTIST') {
            $dentist = Dentist::find($name);
            $realName = $dentist ? $dentist->dentist_name : null;
            $dentistId = $dentist ? $dentist->dentist_id : null;
        } else {
            // ADMIN
            $realName = $name; // ถ้าเป็น admin เก็บตรง ๆ
        }

        $user = User::create([
            'email' => $data['email'],
            'password' => $data['password'], // ตอนนี้ยังไม่ hash
            'name' => $realName,
            'role' => $role,
        ]);

        if ($role === 'PATIENT' && !empty($patientId)) {
            $user->patient_id = $patientId;
        } elseif ($role === 'DENTIST' && !empty($dentistId)) {
            $user->dentist_id = $dentistId;
        }
        $user->save();

        return redirect(
            session()->get('bookmarks.users.create-form', route('users.list'))
        )
            ->with('status', "User {$user->name} was created.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    function showUpdateForm(string $userEmail): View
    {
        $user = User::where('email', $userEmail)->firstOrFail();
        Gate::authorize('update', $user);

        return view('users.update-form', [
            'user' => $user,
            'users' => User::orderBy('email')->get(),
        ]);
    }

    function update(
        ServerRequestInterface $request,
        string $userEmail
    ): RedirectResponse {
        $user = User::where('email', $userEmail)->firstOrFail();
        Gate::authorize('update', $user);

        try {
        $data = $request->getParsedBody();

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->fill($data);
        $user->save();

        return redirect()
            ->route('users.view', [
                'user' => $user->email,
            ])
            ->with('status', "User {$user->email} was updated.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    function selfView(): View
    {
        $user = Auth::user();
        if ($user->role === 'DENTIST' && !$user->dentist) {
            $user->dentist = null;
        } elseif ($user->role === 'PATIENT' && !$user->patient) {
            $user->patient = null;
        }

        return view('users.selves.view', [
            'user' => $user,
        ]);
    }

}
