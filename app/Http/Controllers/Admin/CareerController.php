<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    // Elimina o comenta este bloque:
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:admin']);
    // }

    // Listar carreras
    public function index()
    {
        $careers = Career::orderBy('nombre')->paginate(15);
        return view('admin.careers.index', compact('careers'));
    }

    // Mostrar formulario para crear
    public function create()
    {
        return view('admin.careers.create');
    }

    // Guardar nueva carrera
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:careers,nombre',
            'facultad' => 'nullable|string|max:100',
        ]);

        Career::create([
            'nombre' => $request->nombre,
            'facultad' => $request->facultad,
        ]);

        return redirect()->route('admin.careers.index')
            ->with('success', 'Carrera creada exitosamente.');
    }

    // Mostrar formulario para editar
    public function edit($id)
    {
        $career = Career::findOrFail($id);
        return view('admin.careers.edit', compact('career'));
    }

    // Actualizar carrera
    public function update(Request $request, $id)
    {
        $career = Career::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100|unique:careers,nombre,' . $id,
            'facultad' => 'nullable|string|max:100',
        ]);

        $career->update([
            'nombre' => $request->nombre,
            'facultad' => $request->facultad,
        ]);

        return redirect()->route('admin.careers.index')
            ->with('success', 'Carrera actualizada exitosamente.');
    }

    // Eliminar carrera
    public function destroy($id)
    {
        $career = Career::findOrFail($id);
        
        // Verificar si hay usuarios asociados
        if ($career->users()->count() > 0) {
            return redirect()->route('admin.careers.index')
                ->with('error', 'No se puede eliminar la carrera porque tiene usuarios asociados.');
        }

        $career->delete();

        return redirect()->route('admin.careers.index')
            ->with('success', 'Carrera eliminada exitosamente.');
    }
}