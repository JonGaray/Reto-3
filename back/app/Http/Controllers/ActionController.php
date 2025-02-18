<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActionController extends Controller
{
    //Creacion y validacion de una actividad
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'date_init' => 'required|date',
            'date_end' => 'required|date',
            'age' => 'required|integer',
            'languaje' => 'required|string|max:255',
            'duration' => 'required|integer',
            'start_time' => 'required|date',
            'capacity' => 'required|integer',
            'price' => 'required|integer',
            'center_id' => 'required|integer',
            'category' => 'required|string|max:255'
        ]);
        //En caso de error de validacion, saltará un error
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $action = Action::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'date_init' => $request->get('date_init'),
            'date_end' => $request->get('date_end'),
            'age' => $request->get('age'),
            'languaje' => $request->get('languaje'),
            'duration' => $request->get('duration'),
            'start_time' => $request->get('start_time'),
            'capacity' => $request->get('capacity'),
            'price' => $request->get('price'),
            'center_id' => $request->get('center_id'),
            'category' => $request->get('category')
        ]);
        return response()->json(['message' => 'Accion creada', 'data' => $action], 200);
    }
    //Metodo para mostrar todas las actividades
    public function showAll()
    {
        $actions = Action::with('center')
            ->orderBy('id', 'desc') // Order by 'id' in ascending order (oldest first)
            ->get();
        return response()->json(['message' => '', 'data' => $actions], 200);
    }
    //Metodo para mostrar una actividad mediante su ID
    public function show($id)
    {
        //En caso de que no encuentre la actividad, devolverá un error
        $action = Action::findOrFail($id);
        return response()->json(['message' => '', 'data' => $action], 200);
    }
    //Metodo para actualizar los datos de una actividad
    public function update(Request $request, $id)
    {
        $action = Action::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:255',
            'date_init' => 'sometimes|date',
            'date_end' => 'sometimes|date',
            'age' => 'sometimes|integer',
            'duration' => 'sometimes|integer',
            'languaje' => 'sometimes|string|max:255',
            'start_time' => 'sometimes|date',
            'capacity' => 'sometimes|integer',
            'price' => 'sometimes|integer',
            'center_id' => 'sometimes|integer',
            'category' => 'sometimes|string|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $action->name = $request->get('name', $action->name);
        $action->description = $request->get('description', $action->description);
        $action->date_init = $request->get('date_init', $action->date_init);
        $action->date_end = $request->get('date_end', $action->date_end);
        $action->age = $request->get('age', $action->age);
        $action->duration = $request->get('duration', $action->duration);
        $action->languaje = $request->get('languaje', $action->languaje);
        $action->start_time = $request->get('start_time', $action->start_time);
        $action->capacity = $request->get('capacity', $action->capacity);
        $action->price = $request->get('price', $action->price);
        $action->category = $request->get('category', $action->category);
        $action->center_id = $request->get('center_id', $action->center_id);
        $action->save();
        return response()->json(['message' => 'Acción actualizada', 'data' => $action], 200);
    }
    //Metodo para eliminar una actividad
    public function destroy($id)
    {
        $action = Action::findOrFail($id);
        $action->delete();
        return response()->json(['message'=>'Accion eliminada', 'data' => $action], 200);
    }
    //Metodo para recoger el nombre y direccion del centro de una actividad
    public function center()
    {
        $actions = Action::select(
            'actions.*',
            'centers.name as center_name',
            'centers.address as center_address'
        )
            ->join('centers', 'actions.center_id', '=', 'centers.id')
            ->get();
        return response()->json(['message' => '', 'data' => $actions], 200);
    }
    //Metodo para reducir las plazas tras la asociacion de un usuario a una actividad
    public function reducirPlazas(Request $request)
    {
        $activity = Action::find($request->action_id);
        if (!$activity) {
            return response()->json(['success' => false, 'message' => 'Actividad no encontrada.'], 404);
        }
        // Verificar que haya plazas disponibles
        if ($activity->capacity <= 0) {
            return response()->json(['success' => false, 'message' => 'No hay plazas disponibles para esta actividad.'], 400);
        }
        // Restar una plaza disponible
        $activity->decrement('capacity', 1);
        return response()->json(['success' => true, 'message' => 'Plazas actualizadas correctamente.']);
    }
    //Metodo para aumentar las plazas tras la desinscripcion de un usuari a la actividad
    public function aumentarPlazas(Request $request)
    {
        try {
            $action = Action::find($request->action_id);

            if (!$action) {
                return response()->json(['success' => false, 'message' => 'Actividad no encontrada'], 404);
            }

            $action->capacity += 1; // Aumentar en 1 la capacidad
            $action->save();

            return response()->json(['success' => true, 'message' => 'Plaza añadida correctamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al aumentar plazas'], 500);
        }
    }

}
