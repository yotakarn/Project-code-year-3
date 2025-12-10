<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class PatientController extends SearchableController
{
    const MAX_ITEMS = 5;

    #[\Override]
    function applyWhereToFilterByTerm(Builder $query, string $word): void
    {
        parent::applyWhereToFilterByTerm($query, $word);

        $query->orWhere('patient_code', 'LIKE', "%{$word}%");
    
    }

    #[\Override]
    function getQuery(): Builder
    {
        return Patient::orderBy('patient_code');
    }

    #[\Override]
    function find(string $id): Model
    {
        return $this->getQuery()
        ->orWhere('patient_id', $id)
        ->firstOrFail();
    }

    function list(ServerRequestInterface $request): View
    {
        Gate::authorize('list', Patient::class);

        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria);

        return view('patients.list', [
            'criteria' => $criteria,
            'patients' => $query->paginate(self::MAX_ITEMS),
        ]);
    }

    function view(string $patientID): View
    {
        $patient = patient
            ::where('patient_id', $patientID)->firstOrFail();

        Gate::authorize('view', $patient);

        return view('patients.view', [
            'patient' => $patient,
        ]);
    }

    function showAddPatientForm(): View
    {
        Gate::authorize('add', Patient::class);

        return view('patients.addPatient-form');
    }

    function add(ServerRequestInterface $request): RedirectResponse
    {
        Gate::authorize('add', Patient::class);

        try {
        $patient = Patient::create($request->getParsedBody());
        return redirect(
            session()->get('bookmarks.patients.addPatient-form', route('patients.list'))
        )
            ->with('status', "Patient {$patient->patient_name} was added.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    function showUpdateForm(string $patientID): View
    {
        $patient = $this->find($patientID);
        Gate::authorize('update', $patient);

        return view('patients.update-form', [
            'patient' => $patient,
        ]);
    }

    function update(
        ServerRequestInterface $request,
        string $patientID,
    ): RedirectResponse {
        $patient = $this->find($patientID);
        Gate::authorize('update', $patient);

        try {
        $data = $request->getParsedBody();
        $patient->fill($data);
        $patient->save();

        return redirect()
            ->route('patients.view', [
                'patient' => $patient->patient_id,
            ])
            ->with('status', "Patient {$patient->patient_name} was edited.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    function delete(string $patientID): RedirectResponse
    {
        $patient = $this->find($patientID);
        Gate::authorize('delete', $patient);

        try {
        $patient->delete();

        return redirect(
            session()->get('bookmarks.patients.view', route('patients.list'))
        )
            ->with('status', "Patient : {$patient->patient_name} was deleted.");
        } catch (QueryException $excp) {
            // We don't want withInput() here.
            return redirect()->back()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    function viewDentists(
        ServerRequestInterface $request,
        DentistController $dentistController,
        string $patientID
    ): View {
        $patient = $this->find($patientID);
        Gate::authorize('view', $patient);

        $criteria = $dentistController->prepareCriteria($request->getQueryParams());
        $query = $dentistController
            ->filter($patient->dentists(), $criteria);

        return view('patients.view-dentists', [
            'patient' => $patient,
            'criteria' => $criteria,
            'dentists' => $query->paginate($dentistController::MAX_ITEMS),
        ]);
    }

    function viewAppointments(
        ServerRequestInterface $request,
        AppointmentController $appointmentController,
        string $patientID
    ): View {
        $patient = $this->find($patientID);
        Gate::authorize('view', $patient);

        $criteria = $appointmentController->prepareCriteria($request->getQueryParams());
        $query = $appointmentController->filter($patient->appointments(), $criteria);
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
         return view('patients.view-appointments', [
            'patient' => $patient,
             'appointmentsByDate' => $appointmentsByDate,
             'criteria' => $criteria,
             'paginatedAppointments' => $paginatedAppointments, 
        ]);
    }
}
