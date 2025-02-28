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
        //Verifico si el usuario existe
        try {
            $user = User::findOrFail($request->input('data.relationships.client.data.id'));

        } catch (ModelNotFoundException) {
            return $this->ok('Usuario no encontrado', [
                'error' => 'La id del usuario no corresponde con ningun usuario.'
            ]);
        }

        //Verifico si el servicio existe
        try {
            $service = Service::findOrFail($request->input('data.relationships.service.data.id'));

        } catch (ModelNotFoundException) {
            return $this->ok('Servicio no encontrado', [
                'error' => 'La id del servicio no corresponde con ningun servicio.'
            ]);
        }

        //Verifico si no existe una solcitud identica en esa fecha
        $petitionExists = Petition::where('id_user', $request->input('data.relationships.client.data.id'))
            ->where('date', $request->input('data.attributes.date'))
            ->where('id_service', $request->input('data.relationships.service.data.id'))
            ->exists();

        if ($petitionExists) {
            return response()->json(['error' => 'Ya existe una solicitud identica.'], 409);
        }

        //Verifico si no existe un evento en esa fecha
        $eventExists = Event::where('provider_id', $request->input('data.relationships.client.data.id'))
            ->where('date', $request->input('data.attributes.date'))
            ->where('service_id', $request->input('data.relationships.service.data.id'))
            ->exists();

        if ($eventExists) {
            return response()->json(['error' => 'Ya existe un evento programado para esta fecha con el prestador.'], 409);
        }

        //Creo el modelo
        $model = [
            'id_user' => $request->input('data.relationships.client.data.id'),
            'amount_cents' => $request->input('data.attributes.amount_cents'),
            'description' => $request->input('data.attributes.description'),
            'address' => $request->input('data.attributes.address'),
            'type' => $request->input('data.attributes.type'),
            'area' => $request->input('data.attributes.area'),
            'date' => $request->input('data.attributes.date'),
            'status' => $request->input('data.attributes.status'),
            'id_service' => $request->input('data.relationships.service.data.id'),
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
    public function update(UpdatePetitionRequest $request)
    {
        // HANDLES PATCH

        // // return 'patch request';

        // VAlidar que id sea numero
        if (!$request->input('data.id') || !is_numeric($request->input('data.id'))){
            return response()->json(['error' => 'No se ha encontrado una solicitud con el id ingresado.'], 409);
        }

        // VAlidar que la pet con ese id exista
        $petitionExists = Petition::where('id', $request->input('data.id'))
            ->exists();

        if(!$petitionExists){
            return response()->json(['error' => 'No existe una solicitud.'], 409);
        }

        // validar el status sea enviada
        if ($request->input('data.attributes.status') != 'Enviada') {
            return response()->json(['error' => 'La solicitud no tiene el estado correspondiente para ser aceptada.'], 409);
        }

        // Verificar si un evento
        $eventExists = Event::where('service_id', $request->input('data.relationships.service.data.id'))
            ->where('date', $request->input('data.attributes.date'))
            ->where('time', $request->input('data.attributes.time'))
            ->exists();

        if ($eventExists) {
            return response()->json(['error' => 'Ya existe un evento programado para esta fecha con el prestador. No se puede aceptar la solicitud.'], 409);
        }
        // Modificar solicitud

        




        // Verifico si la peticion existe

        // try{
        //     $petition = Petition::findOrFail($petition->id);
            


        // }catch (ModelNotFoundException $exception){
        //     return $this->error('No se ha encontrado la solicitud', 404);
        // }

        
        // $petition->update($request->mappedAttributes());
        // return new PetitionResource($petition);


    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Petition $petition)
    {
        //
    }
}
