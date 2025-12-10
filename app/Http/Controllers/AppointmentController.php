<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Gate;
use Barryvdh\DomPDF\Facade\Pdf;

class AppointmentController extends SearchableController
{
    const MAX_ITEMS = 5;

    #[\Override]
    function getQuery(): Builder
    {
        return Appointment::query()
            ->orderBy('appointment_date')
            ->orderBy('appointment_time');
    }

    #[\Override]
    function applyWhereToFilterByTerm(Builder $query, string $word): void
    {
        $query->where('description', 'like', "%{$word}%")
            ->orWhere('appointment_code', 'like', "%{$word}%")
            ->orWhereHas('patient', function (Builder $patientQuery) use ($word) {
                $patientQuery->where('patient_name', 'like', "%{$word}%");
            })
            ->orWhereHas('dentist', function (Builder $dentistQuery) use ($word) {
                $dentistQuery->where('dentist_name', 'like', "%{$word}%");
            });
    }

    function list(ServerRequestInterface $request): View
    {

        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria);
        // --- เพิ่ม filter ตาม appointment_date ---
        if (!empty($criteria['date'])) {
            $query->whereDate('appointment_date', $criteria['date']);
        }

        $user = Auth::user();
        if ($user->isDentist()) {
            $query->where('dentist_id', $user->dentist_id);
        } elseif ($user->isPatient()) {
            $query->where('patient_id', $user->patient_id);
        }

        // Pagination ก่อนดึง Collection
        $paginatedAppointments = $query->paginate(self::MAX_ITEMS);

        // Group appointments ตามวัน (ใช้ Collection จาก paginate)
        $appointmentsByDate = $paginatedAppointments->getCollection()
            ->groupBy(function ($appointment) {
                return Carbon::parse($appointment->appointment_date)->toDateString();
            });

        // คืนค่า view
        return view('appointments.list', [
            'appointmentsByDate' => $appointmentsByDate,
            'criteria' => $criteria,
            'paginatedAppointments' => $paginatedAppointments,
        ]);
    }

    function view(Appointment $appointment): View
    {
        Gate::authorize('view', $appointment);

        return view('appointments.view', [
            'appointment' => $appointment,
        ]);
    }

    public function showCreateForm(): View
    {
        $dentists = Dentist::orderBy('dentist_name')->get();
        $patients = Patient::orderBy('patient_code')->get();

        Gate::authorize('create', Appointment::class);

        return view('appointments.create-form', [
            'dentists' => $dentists,
            'patients' => $patients,
        ]);
    }

    public function create(ServerRequestInterface $request): RedirectResponse
    {
        Gate::authorize('create', Appointment::class);

        try {
            $data = $request->getParsedBody();

            $dentist = Dentist::find($data['dentist_id']);
            $patient = Patient::find($data['patient_id']);

            // --- ตรวจสอบ conflict ช่วงเวลา 2 ชั่วโมง ---
            $newStartTime = $data['appointment_time'];
            $newEndTime = Carbon::parse($newStartTime)->addHours(2)->format('H:i:s');

            $conflict = Appointment::where('dentist_id', $dentist->dentist_id)
                ->where('appointment_date', $data['appointment_date'])
                ->where(function ($query) use ($newStartTime, $newEndTime) {
                    $query->where('appointment_time', '<', $newEndTime)
                        ->whereRaw('ADDTIME(appointment_time, "02:00:00") > ?', [$newStartTime]);
                })
                ->exists();

            if ($conflict) {
                return redirect()->back()->withInput()->withErrors([
                    'alert' => 'This time slot overlaps with another appointment for the dentist (each appointment takes 2 hours)'
                ]);
            }
            // -------------------------------------------

            // สร้าง appointment
            $appointment = new Appointment();
            $appointment->fill($data);
            $appointment->dentist()->associate($dentist);
            $appointment->patient()->associate($patient);
            $appointment->save();

            return redirect(session('bookmarks.appointments.create-form') ?? route('appointments.list'))
                ->with('status', "Appointment {$appointment->appointment_code} was created");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    public function showUpdateForm(Appointment $appointment): View
    {
        $dentists = Dentist::orderBy('dentist_name')->get();
        $patients = Patient::orderBy('patient_code')->get();

        Gate::authorize('update', $appointment);

        return view('appointments.update-form', [
            'appointment' => $appointment,
            'dentists' => $dentists,
            'patients' => $patients,
        ]);
    }

    public function update(ServerRequestInterface $request, Appointment $appointment): RedirectResponse
    {
        Gate::authorize('update', $appointment);
        try {
            $data = $request->getParsedBody();

            // หา dentist และ patient ตาม ID
            $dentist = Dentist::find($data['dentist_id']);
            $patient = Patient::find($data['patient_id']);

            // ตรวจสอบว่ามีการเปลี่ยนแปลงช่วงเวลา (วัน, เวลา, หรือทันตแพทย์)
            $timeSlotChanged = $data['appointment_date'] != $appointment->appointment_date ||
                $data['appointment_time'] != $appointment->appointment_time ||
                $data['dentist_id'] != $appointment->dentist_id;

            if ($timeSlotChanged) {
                $newStartTime = $data['appointment_time'];
                $newEndTime = Carbon::parse($newStartTime)->addHours(2)->format('H:i:s');

                $conflict = Appointment::where('dentist_id', $dentist->dentist_id)
                    ->where('appointment_date', $data['appointment_date'])
                    ->where('appointment_id', '!=', $appointment->appointment_id)
                    ->where(function ($query) use ($newStartTime, $newEndTime) {
                        $query->where('appointment_time', '<', $newEndTime)
                            ->whereRaw('ADDTIME(appointment_time, "02:00:00") > ?', [$newStartTime]);
                    })
                    ->exists();

                if ($conflict) {
                    return redirect()->back()->withInput()->withErrors([
                        'alert' => 'This time slot overlaps with another appointment for the dentist (each appointment takes 2 hours)'
                    ]);
                }
            }
            // อัปเดตข้อมูล appointment
            $appointment->fill($data);
            $appointment->dentist()->associate($dentist);
            $appointment->patient()->associate($patient);
            $appointment->save();

            return redirect()->route('appointments.view', ['appointment' => $appointment->appointment_id,])
                ->with('status', "Appointment {$appointment->code} was updated");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    function delete(Appointment $appointment): RedirectResponse
    {
        Gate::authorize('delete', $appointment);

        try {
            $appointment->delete();

            return redirect(session('bookmarks.appointments.view') ?? route('appointments.list'))
                ->with('status', "Appointment {$appointment->code} was deleted");
        } catch (QueryException $excp) {
            // We don't want withInput() here.
            return redirect()->back()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    public function downloadPdf(Appointment $appointment)
    {
        // สร้าง PDF  และส่งข้อมูล appointment ไปด้วย
        $pdf = Pdf::loadView('appointments.pdf', [
            'appointment' => $appointment
        ]);

        // แสดงผล PDF ในเบราว์เซอร์โดยตรง (ไม่ได้บังคับดาวน์โหลด) และ ชื่อไฟล์ที่ดาวน์โหลด
        return $pdf->stream('appointment-slip-' . $appointment->appointment_id . '.pdf');
    }
}
