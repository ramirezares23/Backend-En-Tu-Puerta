<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Filters\V1\PetitionFilter;
use App\Http\Resources\V1\PetitionResource;
use App\Http\Requests\Api\V1\Petition\StorePetitionRequest;
use App\Http\Requests\Api\V1\Petition\UpdatePetitionRequest;
use App\Models\Petition;
use App\Models\Service;
use App\Models\Event;
use App\Models\User;
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
     * Store a newly created resource in storage.
     */
    public function store(StorePetitionRequest $request)
    {
        $data = $request->validated();

        $user = $this->findOrFailResponse(User::class, $data['data']['relationships']['client']['data']['id'], 'Usuario');
        $service = $this->findOrFailResponse(Service::class, $data['data']['relationships']['service']['data']['id'], 'Servicio');

        // Verifico si existe una solicitud o evento en la misma fecha
        if ($this->petitionExists($data) || $this->eventExists($data)) {
            abort(409, 'Ya existe una solicitud o evento para esta fecha con el prestador.');
        }

        // Creo la petición
        $petition = Petition::create([
            'id_user' => $user->id,
            'amount_cents' => $data['data']['attributes']['amount_cents'],
            'description' => $data['data']['attributes']['description'],
            'address' => $data['data']['attributes']['address'],
            'type' => $data['data']['attributes']['type'],
            'area' => $data['data']['attributes']['area'],
            'date' => $data['data']['attributes']['date'],
            'status' => $data['data']['attributes']['status'],
            'id_service' => $service->id,
        ]);

        return new PetitionResource($petition);
    }

    /**
     * Display the specified resource.
     */
    public function show(Petition $petition)
    {
        return new PetitionResource($this->include('client') ? $petition->load('user') : $petition);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePetitionRequest $request, Petition $petition)
    {
        $data = $request->validated();

        if ($data['data']['attributes']['status'] !== 'Enviada') {
            abort(409, 'La solicitud no tiene el estado correspondiente para ser aceptada.');
        }

        if ($this->eventExists($data)) {
            abort(409, 'Ya existe un evento programado para esta fecha con el prestador. No se puede aceptar la solicitud.');
        }

        // Actualizo la solicitud
        $petition->update(['status' => 'Aceptada']);

        // Creación del evento
        $service = $this->findOrFailResponse(Service::class, $data['data']['relationships']['service']['data']['id'], 'Servicio');

        Event::create([
            'provider_id' => $service->id_provider,
            'client_id' => $petition->id_user,
            'service_id' => $petition->id_service,
            'date' => $petition->date,
            'time' => $petition->time,
            'status' => 'Pendiente',
        ]);

        return response()->json(['message' => 'Evento creado con éxito.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Petition $petition)
    {
        $petition->delete();
        return response()->json(['message' => 'Solicitud eliminada con éxito.']);
    }

    /**
     * Encuentra un modelo o responde con un mensaje de error si no existe.
     */
    private function findOrFailResponse($model, $id, $resourceName)
    {
        try {
            return $model::findOrFail($id);
        } catch (ModelNotFoundException) {
            abort(404, "$resourceName no encontrado.");
        }
    }

    /**
     * Verifica si existe una solicitud en la misma fecha con el mismo usuario y servicio.
     */
    private function petitionExists($data)
    {
        return Petition::where('id_user', $data['data']['relationships']['client']['data']['id'])
            ->where('date', $data['data']['attributes']['date'])
            ->where('id_service', $data['data']['relationships']['service']['data']['id'])
            ->exists();
    }

    /**
     * Verifica si existe un evento en la misma fecha con el mismo prestador y servicio.
     */
    private function eventExists($data)
    {
        return Event::where('provider_id', $data['data']['relationships']['client']['data']['id'])
            ->where('date', $data['data']['attributes']['date'])
            ->where('service_id', $data['data']['relationships']['service']['data']['id'])
            ->exists();
    }
}