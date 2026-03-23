<?php

namespace App\Services;

use App\Models\Persona;
use App\Models\User;
use App\Models\Empleado;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;

class PersonaService
{
    public function crearEmpleado(array $datos): Empleado
    {
        // 1. Crear la persona
        $persona = Persona::create([
            'nombre'           => $datos['nombre'],
            'apellido'         => $datos['apellido'],
            'documento'        => $datos['documento'] ?? null,
            'telefono'         => $datos['telefono'] ?? null,
            'fecha_nacimiento' => $datos['fecha_nacimiento'] ?? null,
            'direccion'        => $datos['direccion'] ?? null,
        ]);

        // 2. Crear el usuario del sistema
        $user = User::create([
            'name'     => $datos['nombre'] . ' ' . $datos['apellido'],
            'email'    => $datos['email'],
            'password' => Hash::make($datos['password']),
        ]);

        // 3. Asignar rol empleado
        $user->assignRole('empleado');

        // 4. Crear el empleado vinculando persona y usuario
        $empleado = Empleado::create([
            'persona_id'      => $persona->id,
            'user_id'         => $user->id,
            'especialidad'    => $datos['especialidad'] ?? null,
            'tasa_comision'   => $datos['tasa_comision'] ?? 0,
            'activo'          => $datos['activo'] ?? true,
        ]);

        return $empleado;
    }

    public function actualizarEmpleado(Empleado $empleado, array $datos): Empleado
    {
        // 1. Actualizar persona
        $empleado->persona->update([
            'nombre'           => $datos['nombre'],
            'apellido'         => $datos['apellido'],
            'documento'        => $datos['documento'] ?? null,
            'telefono'         => $datos['telefono'] ?? null,
            'fecha_nacimiento' => $datos['fecha_nacimiento'] ?? null,
            'direccion'        => $datos['direccion'] ?? null,
        ]);

        // 2. Actualizar nombre del usuario (opcional, para mantener coherencia)
        $empleado->user->update([
            'name' => $datos['nombre'] . ' ' . $datos['apellido'],
        ]);

        // 3. Actualizar datos laborales del empleado
        $empleado->update([
            'especialidad'  => $datos['especialidad'] ?? null,
            'tasa_comision' => $datos['tasa_comision'] ?? 0,
            'activo'        => $datos['activo'] ?? true,
        ]);

        return $empleado;
    }

    public function crearCliente(array $datos): Cliente
    {
        // Crear la persona
        $persona = Persona::create([
            'nombre'           => $datos['nombre'],
            'apellido'         => $datos['apellido'],
            'documento'        => $datos['documento'] ?? null,
            'telefono'         => $datos['telefono'] ?? null,
            'fecha_nacimiento' => $datos['fecha_nacimiento'] ?? null,
            'direccion'        => $datos['direccion'] ?? null,
            'email'            => $datos['email'] ?? null,
            'activo'           => $datos['activo'] ?? true,
        ]);

        // Crear el cliente
        $cliente = Cliente::create([
            'persona_id' => $persona->id,
            'notas'      => $datos['notas'] ?? null,
            'activo'     => $datos['activo'] ?? true,
        ]);

        return $cliente;
    }

    public function actualizarCliente(Cliente $cliente, array $datos): Cliente
    {
        // Actualizar persona
        $cliente->persona->update([
            'nombre'           => $datos['nombre'],
            'apellido'         => $datos['apellido'],
            'documento'        => $datos['documento'] ?? null,
            'telefono'         => $datos['telefono'] ?? null,
            'fecha_nacimiento' => $datos['fecha_nacimiento'] ?? null,
            'direccion'        => $datos['direccion'] ?? null,
            'email'            => $datos['email'] ?? null,
            'activo'           => $datos['activo'] ?? true
        ]);

        // Actualizar datos del cliente
        $cliente->update([
            'notas'  => $datos['notas'] ?? null,
            'activo' => $datos['activo'] ?? true,
        ]);

        return $cliente;
    }
}