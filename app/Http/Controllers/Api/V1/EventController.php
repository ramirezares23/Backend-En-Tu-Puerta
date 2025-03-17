<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Filters\V1\EventFilter;
use App\Http\Resources\V1\EventResource;
use App\Http\Requests\Api\V1\Event\StoreEventRequest;
use App\Http\Requests\Api\V1\Event\UpdateEventRequest;
use App\Models\Event;
use App\Models\User;

class EventController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(EventFilter $filters)
    {
        return EventResource::collection(Event::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        // Verificar si el usuario existe
        $provider = User::find($request->input('data.attributes.provider_id'));
        if (!$provider) {
            return response()->json(['error' => 'El prestador de servicios no existe.'], 404);
        }

        // Verificar que el usuario es prestador
        if ($provider->type == null) {
            return response()->json(['error' => 'El usuario no es un prestador de servicios.'], 403);
        }

        // Verificar si no existe un evento en esa fecha y hora
        $eventExists = Event::where('provider_id', $request->input('data.attributes.provider_id'))
            ->where('date', $request->input('data.attributes.date'))
            ->where('time', $request->input('data.attributes.time'))
            ->exists();

        if ($eventExists) {
            return response()->json(['error' => 'Ya existe un evento en esa fecha y hora.'], 409);
        }

        // Crear el evento
        $event = Event::create([
            'provider_id' => $request->input('data.attributes.provider_id'),
            'title' => $request->input('data.attributes.title'),
            'date' => $request->input('data.attributes.date'),
            'time' => $request->input('data.attributes.time'),
            'status' => 'Enviada', // O el estado que desees asignar
            'client_id' => null, // No se asigna cliente
            'service_id' => null, // No se asigna servicio
        ]);
        
        //Guardo el modelo
        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
