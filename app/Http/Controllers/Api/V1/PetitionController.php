<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Filters\V1\PetitionFilter;
use App\Http\Resources\V1\EventResource;
use App\Http\Resources\V1\PetitionResource;
use App\Http\Requests\Api\V1\Petition\StorePetitionRequest;
use App\Http\Requests\Api\V1\Petition\UpdatePetitionRequest;
use App\Models\Petition;
use App\Models\Service;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PetitionController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(PetitionFilter $filters)
    {
        return PetitionResource::collection(Petition::filter($filters)->paginate());
    }

    /**
     * Despliega la informacion para seleccionar el dia y la hora y crear la solicitud 
     */
    public function create($id_service)
    {
        Carbon::setLocale('es'); // Configurar el idioma en español

        //Fecha de hoy
        $today = Carbon::now()->startOfDay();

        //Fecha del proximo miercoles
        $nextWednesday = new Carbon('next wednesday');

        $dates = [];
        $currentDate = $today->copy();

        while ($currentDate->lte($nextWednesday)) {
            $dates[] = [
                'date' => $currentDate->format('Y-m-d'),
                'day' => $currentDate->translatedFormat('l') // Día en español (ej: "miércoles")
            ];
            $currentDate->addDay();
        }
        // $dates es el arreglo con fecha y dia a retornar

        //Busco la duracion aproximada del servicio
        try {
            $service = Service::findOrFail($id_service);
        } catch (ModelNotFoundException) {
            return $this->ok('Servicio no encontrado', [
                'error' => 'La id del servicio no corresponde con ningun servicio.'
            ]);
        }

        $duration = $service->duration;

        $provider = $service->user;
        // Convertir horas a objetos Carbon
        $start_time = Carbon::parse($provider->start_time);
        $end_time = Carbon::parse($provider->end_time);

        foreach ($dates as &$dateItem) {
            $currentDate = Carbon::parse($dateItem['date']);
            $isToday = $currentDate->isToday();

            // Establecer límites del día
            $dayStart = $currentDate->copy()->setTime(
                $start_time->hour,
                $start_time->minute,
                $start_time->second
            );

            $dayEnd = $currentDate->copy()->setTime(
                $end_time->hour,
                $end_time->minute,
                $end_time->second
            );

            // Generar todos los slots posibles
            $slots = [];
            $slotTime = $dayStart->copy();

            while ($slotTime->lte($dayEnd)) {
                $slotEnd = $slotTime->copy()->addMinutes($duration);

                if ($slotEnd->lte($dayEnd)) {
                    $slots[] = $slotTime->copy();
                }

                $slotTime->addMinutes($duration);
            }

            // Filtrar slots para el día actual
            if ($isToday) {
                $now = Carbon::now();
                $slots = array_filter($slots, function ($slot) use ($now, $duration) {
                    return $slot->gte($now->copy()->addMinutes($duration));
                });
            }

            // Obtener eventos existentes
            $events = Event::where('provider_id', $provider->id)
                ->whereDate('date', $currentDate->toDateString())
                ->get();

            // Verificar conflictos
            $availableSlots = [];
            foreach ($slots as $slot) {
                $slotStart = $slot;
                $slotEnd = $slot->copy()->addMinutes($duration);

                $isAvailable = true;

                foreach ($events as $event) {
                    $eventStart = Carbon::parse($event->date . ' ' . $event->time);
                    $eventEnd = $eventStart->copy()->addMinutes($duration);

                    // Verificar superposición
                    if (
                        $slotStart->between($eventStart, $eventEnd) ||
                        $slotEnd->between($eventStart, $eventEnd) ||
                        $eventStart->between($slotStart, $slotEnd)
                    ) {
                        $isAvailable = false;
                        break;
                    }
                }

                if ($isAvailable) {
                    $availableSlots[] = $slot->format('H:i');
                }
            }
            $dateItem['available_slots'] = $availableSlots;
        }

        return response()->json($dates);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePetitionRequest $request)
    {
        //Verifico si el usuario existe
        try {
            $user = User::findOrFail($request->input('data.attributes.id_user'));

        } catch (ModelNotFoundException) {
            return $this->ok('Usuario no encontrado', [
                'error' => 'La id del usuario no corresponde con ningun usuario.'
            ]);
        }

        //Verifico si el servicio existe
        try {
            $service = Service::findOrFail($request->input('data.attributes.id_service'));

        } catch (ModelNotFoundException) {
            return $this->ok('Servicio no encontrado', [
                'error' => 'La id del servicio no corresponde con ningun servicio.'
            ]);
        }

        //Verifico si no existe una solicitud identica en esa fecha
        $petitionExists = Petition::where('id_user', $request->input('data.attributes.id_user'))
            ->where('date', $request->input('data.attributes.date'))
            ->where('time', $request->input('data.attributes.time'))
            ->where('id_service', $request->input('data.attributes.id_service'))
            ->where('status', "Enviada")
            ->exists();

        if ($petitionExists) {
            return response()->json(['error' => 'Ya existe una solicitud identica.'], 409);
        }

        /*TODO: Se debe verificar que la hora este comprendida entre el tiempo de inicio 
                y el de finalizacion segun la duracion aproximada del evento
        */
        //Verifico si no existe un evento en esa fecha y hora
        $service = Service::findOrFail($request->input('data.attributes.id_service'));

        $provider = $service->user;

        $eventExists = Event::where('provider_id', $provider->id)
            ->where('date', $request->input('data.attributes.date'))
            ->where('time', $request->input('data.attributes.time'))
            ->where('service_id', $request->input('data.attributes.id_service'))
            ->exists();

        if ($eventExists) {
            return response()->json(['error' => 'Ya existe un evento programado para esta fecha con el prestador.'], 409);
        }

        //Creo el modelo
        $model = [
            'id_user' => $request->input('data.attributes.id_user'),
            'date' => $request->input('data.attributes.date'),
            'status' => 'Enviada',
            'time' => $request->input('data.attributes.time'),
            'message' => $request->input('data.attributes.message'),
            'id_service' => $request->input('data.attributes.id_service'),
        ];

        //Guardo el modelo
        return new PetitionResource(Petition::create($model));
    }

    /**
     * Display the specified resource.
     */
    public function show(Petition $petition)
    {
        if ($this->include('client')) {
            return new PetitionResource($petition->load('user'));
        }
        return new PetitionResource($petition);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePetitionRequest $request, Petition $petition)
    {
        // HANDLES PATCH

        // return 'patch request';
        // VAlidar que id sea numero
        if (!$request->input('data.id') || !is_numeric($request->input('data.id'))) {
            return response()->json(['error' => 'No se ha encontrado una solicitud con el id ingresado.'], 409);
        }

        // VAlidar que la pet con ese id exista
        $petitionExists = Petition::where('id', $request->input('data.id'))
            ->exists();

        if (!$petitionExists) {
            return response()->json(['error' => 'No existe una solicitud.'], 409);
        }

        // Verificar si un evento
        $eventExists = Event::where('service_id', $request->input('data.attributes.id_service'))
            ->where('date', $request->input('data.attributes.date'))
            ->where('time', $request->input('data.attributes.time'))
            ->exists();

        if ($eventExists) {
            return response()->json(['error' => 'Ya existe un evento programado para esta fecha con el prestador. No se puede aceptar la solicitud.'], 409);
        }

        $petition = Petition::where('id', $request->input('data.id'))->where('status', 'Enviada')->exists();

        // validar el status sea enviada
        if (!$petition) {
            return response()->json(['error' => 'La solicitud no tiene el estado correspondiente para ser aceptada.'], 409);
        } else {
            // Modificar solicitud
            $petition = Petition::findOrFail($request->input('data.id'));
            $petition->update(['status' => 'Aceptada']);

            // Creando el Evento
            $petition = Petition::where('id', $request->input('data.id'))->first();

            $event = Event::create([
                'provider_id' => $petition->provider->id,
                'client_id' => $request->input('data.attributes.id_user'),
                'service_id' => $request->input('data.attributes.id_service'),
                'title' => $petition->service->service_name,
                'date' => $request->input('data.attributes.date'),
                'time' => $request->input('data.attributes.time'),
                'status' => 'Pendiente', // Estado inicial del evento
            ]);
            return new EventResource($event);

        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Petition $petition)
    {
        //
    }
}
