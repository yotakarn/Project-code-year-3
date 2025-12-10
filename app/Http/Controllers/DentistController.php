<?php

namespace App\Http\Controllers;


use App\Models\Dentist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class DentistController extends SearchableController
{
    const MAX_ITEMS = 5;

    #[\Override]
    function applyWhereToFilterByTerm(Builder $query, string $word): void
    {
        $query->orWhere('dentist_name', 'LIKE', "%{$word}%")
            ->orWhere('dentist_department', 'LIKE', "%{$word}%");
    }


    #[\Override]
    function getQuery(): Builder
    {
        return Dentist::orderBy('dentist_id');
    }

    function list(ServerRequestInterface $request): View
    {
        Gate::authorize('list', Dentist::class);

        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria);

        return view('dentists.list', [
            'criteria' => $criteria,
            'dentists' => $query->paginate(self::MAX_ITEMS),
        ]);
    }

    function view(string $dentistID): View
    {
        $dentist = dentist
            ::where('dentist_id', $dentistID)->firstOrFail();

        Gate::authorize('view', $dentist);

        return view('dentists.view', [
            'dentist' => $dentist,
        ]);
    }

    function showAddDentistForm(): View
    {
        Gate::authorize('add', Dentist::class);

        return view('dentists.addDentist-form');
    }

    function add(ServerRequestInterface $request): RedirectResponse
    {
        Gate::authorize('add', Dentist::class);

        try {
            $dentist = Dentist::create($request->getParsedBody());
            return redirect(
                session()->get('bookmarks.dentists.addDentist-form', route('dentists.list'))
            )
                ->with('status', "Dentist {$dentist->dentist_name} was added.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    function showUpdateForm(string $dentistID): View
    {
        $dentist = $this->find($dentistID);

        Gate::authorize('update', $dentist);

        return view('dentists.update-form', [
            'dentist' => $dentist,
        ]);
    }

    function update(
        ServerRequestInterface $request,
        string $dentistID,
    ): RedirectResponse {
        $dentist = $this->find($dentistID);
        Gate::authorize('update', $dentist);

        try {
            $data = $request->getParsedBody();
            $dentist->fill($data);
            $dentist->save();

            return redirect()
                ->route('dentists.view', [
                    'dentist' => $dentist->dentist_id,
                ])
                ->with('status', "Dentist {$dentist->dentist_name} was edited.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    function delete(string $dentistID): RedirectResponse
    {
        $dentist = $this->find($dentistID);
        Gate::authorize('delete', $dentist);

        try {
            $dentist->delete();

            return redirect(
                session()->get('bookmarks.dentists.view', route('dentists.list'))
            )
                ->with('status', "Dentist : {$dentist->dentist_name} was deleted.");
        } catch (QueryException $excp) {
            // We don't want withInput() here.
            return redirect()->back()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    function viewPatients(
        ServerRequestInterface $request,
        PatientController $patientController,
        string $dentistID
    ): View {
        $dentist = $this->find($dentistID);
        Gate::authorize('view', $dentist);

        $criteria = $patientController->prepareCriteria($request->getQueryParams());
        $query = $patientController
            ->filter($dentist->patients(), $criteria);

        return view('dentists.view-patients', [
            'dentist' => $dentist,
            'criteria' => $criteria,
            'patients' => $query->paginate($patientController::MAX_ITEMS),
        ]);
    }

    function viewAppointments(
        ServerRequestInterface $request,
        AppointmentController $appointmentController,
        string $dentistID
    ): View {
        $dentist = $this->find($dentistID);
        Gate::authorize('view', $dentist);

        $criteria = $appointmentController->prepareCriteria($request->getQueryParams());
        $query = $appointmentController->filter($dentist->appointments(), $criteria);
        // --- เพิ่ม filter ตาม appointment_date ---
        if (!empty($criteria['date'])) {
            $query->whereDate('appointment_date', $criteria['date']);
        }

        // Pagination ก่อนดึง Collection
        $paginatedAppointments = $query->paginate(self::MAX_ITEMS);

        // Group appointments ตามวัน (ใช้ Collection จาก paginate)
        $appointmentsByDate = $paginatedAppointments->getCollection()
            ->groupBy(function ($appointment) {
                return Carbon::parse($appointment->appointment_date)->toDateString();
            });

        // คืนค่า view
        return view('dentists.view-appointments', [
            'dentist' => $dentist,
            'appointmentsByDate' => $appointmentsByDate,
            'criteria' => $criteria,
            'paginatedAppointments' => $paginatedAppointments,
        ]);
    }
}
