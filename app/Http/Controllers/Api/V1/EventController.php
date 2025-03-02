<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\V1\EventResource;
use App\Http\Requests\Api\V1\Event\StoreEventRequest;
use App\Http\Requests\Api\V1\Event\UpdateEventRequest;
use App\Models\Event;
use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EventController extends ApiController
{
    /**
     * Listar eventos con paginación.
     */
    public function index()
    {
        return EventResource::collection(Event::paginate());
    }

    /**
     * Almacenar un nuevo evento.
     */
    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();

        // Validar existencia de proveedor, cliente y servicio
        $provider = $this->findOrFailResponse(User::class, $data['data']['relationships']['provider']['data']['id'], 'Proveedor');
        $client = $this->findOrFailResponse(User::class, $data['data']['relationships']['client']['data']['id'], 'Cliente');
        $service = $this->findOrFailResponse(Service::class, $data['data']['relationships']['service']['data']['id'], 'Servicio');

        // Verificar si ya existe un evento en esa fecha y hora con el mismo proveedor
        if ($this->eventExists($provider->id, $data['data']['attributes']['date'], $data['data']['attributes']['time'])) {
            abort(409, 'Ya existe un evento programado para esta fecha y hora con el prestador.');
        }

        // Crear el evento
        $event = Event::create([
            'provider_id' => $provider->id,
            'client_id' => $client->id,
            'service_id' => $service->id,
            'date' => $data['data']['attributes']['date'],
            'time' => $data['data']['attributes']['time'],
            'status' => $data['data']['attributes']['status'],
        ]);

        return new EventResource($event);
    }

    /**
     * Mostrar un evento específico.
     */
    public function show(Event $event)
    {
        return new EventResource($event->load(['provider', 'user', 'service']));
    }

    /**
     * Actualizar un evento existente.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $data = $request->validated();

        // Verificar si el nuevo horario está ocupado por otro evento
        if ($this->eventExists($event->provider_id, $data['data']['attributes']['date'], $data['data']['attributes']['time'], $event->id)) {
            abort(409, 'Otro evento ya está programado en esta fecha y hora con el prestador.');
        }

        $event->update([
            'date' => $data['data']['attributes']['date'],
            'time' => $data['data']['attributes']['time'],
            'status' => $data['data']['attributes']['status'],
        ]);

        return new EventResource($event);
    }

    /**
     * Eliminar un evento.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(['message' => 'Evento eliminado con éxito.']);
    }

    /**
     * Encuentra un modelo o responde con error si no existe.
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
     * Verifica si ya existe un evento en la misma fecha y hora con el mismo proveedor.
     */
    private function eventExists($providerId, $date, $time, $excludeId = null)
    {
        return Event::where('provider_id', $providerId)
            ->where('date', $date)
            ->where('time', $time)
            ->when($excludeId, fn($query) => $query->where('id', '!=', $excludeId))
            ->exists();
    }
}
