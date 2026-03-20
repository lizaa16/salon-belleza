<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesYPermisosSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        $admin    = Role::create(['name' => 'administrador']);
        $empleado = Role::create(['name' => 'empleado']);
        $cliente  = Role::create(['name' => 'cliente']);

        // Permisos agrupados por módulo
        $permisos = [
            // Servicios
            'ver servicios', 'crear servicios', 'editar servicios', 'eliminar servicios',

            // Empleados
            'ver empleados', 'crear empleados', 'editar empleados', 'eliminar empleados',

            // Clientes
            'ver clientes', 'crear clientes', 'editar clientes', 'eliminar clientes',

            // Agenda
            'ver citas', 'crear citas', 'editar citas', 'cancelar citas',

            // Ventas
            'ver ventas', 'crear ventas', 'anular ventas',

            // Facturación
            'ver facturas', 'emitir facturas',

            // Cursos
            'ver cursos', 'crear cursos', 'editar cursos', 'eliminar cursos',

            // Reportes
            'ver reportes',
        ];

        // Crear todos los permisos
        foreach ($permisos as $permiso) {
            Permission::create(['name' => $permiso]);
        }

        // Administrador obtiene TODOS los permisos
        $admin->givePermissionTo(Permission::all());

        // Crear usuario administrador inicial
        $usuario = User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@salonbelleza.com',
            'password' => bcrypt('admin1234'),
        ]);

        $usuario->assignRole('administrador');
    }
}