<?php

namespace Database\Seeders;
use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    $roleSA=Role::create(['name'=>'Super Administrador', 'guard_name'=>'web'])        ;
    $roleA=Role::create(['name'=>'Administrador','guard_name'=>'web']);
    $roleC=Role::create(['name'=>'Contador', 'guard_name'=>'web']);
    $roleTV=Role::create(['name'=>'TV', 'guard_name'=>'web']);
    $roleTD=Role::create(['name'=>'Dispensador de Ticket', 'guard_name'=>'web']);
    $roleB=Role::create(['name'=>'Negocio (Cliente)', 'guard_name'=>'web']);

    /*Principal */
    Permission::create(['name_to_see' => 'Acceso sistema interno', 'name'=>'SI_SEE', 'guard_name'=>'web' ])->syncRoles([$roleSA,$roleA,$roleC]);

    /*Cliente*/
    Permission::create(['name_to_see' => 'Ver cliente', 'name'=>'SI_CLIENTS_SEE', 'guard_name'=>'web' ])->syncRoles([$roleA,$roleC]);
    Permission::create(['name_to_see' => 'Añadir cliente', 'name'=>'SI_CLIENTS_ADD', 'guard_name'=>'web' ])->syncRoles([$roleA,$roleC]);
    Permission::create(['name_to_see' => 'Actualizar cliente', 'name'=>'SI_CLIENTS_UPD', 'guard_name'=>'web' ])->syncRoles([$roleA,$roleC]);
    Permission::create(['name_to_see' => 'Eliminar cliente', 'name'=>'SI_CLIENTS_DEL', 'guard_name'=>'web' ])->syncRoles([$roleA,$roleC]);
    
    /*Linea de espera*/
    Permission::create(['name_to_see' => 'Atención', 'name'=>'SI_CALLS_SEE',  'guard_name'=>'web' ])->syncRoles([$roleSA,$roleA,$roleC]);;
    Permission::create(['name_to_see' => 'Tickets',  'name'=>'SI_TICKETS_SEE', 'guard_name'=>'web'])->syncRoles([$roleSA, $roleA,$roleC]);;
    Permission::create(['name_to_see' => 'Ventanillas',  'name'=>'SI_TELLERS_SEE', 'guard_name'=>'web'])->syncRoles([$roleSA, $roleC]);;
    Permission::create(['name_to_see' => 'Categorias',  'name'=>'SI_CATEGORIES_SEE', 'guard_name'=>'web'])->syncRoles([$roleSA, $roleA,$roleC]);;
    Permission::create(['name_to_see' => 'Videos',  'name'=>'SI_VIDEOS_SEE', 'guard_name'=>'web'])->syncRoles([$roleSA,$roleA,$roleC]);;
    Permission::create(['name_to_see' => 'Tarjetas',  'name'=>'SI_CARDS_SEE', 'guard_name'=>'web'])->syncRoles([$roleSA, $roleA,$roleC]);;

    /*Reportes*/
    Permission::create(['name_to_see' => 'Reporte de linea de espera',  'name'=>'SI_REPORT_WAITING_LINE','guard_name'=>'web'])->syncRoles([$roleA,$roleC]);;
    Permission::create(['name_to_see' => 'Reporte de clientes',  'name'=>'SI_REPORT_CLIENT_SEE', 'guard_name'=>'web'])->syncRoles([$roleA,$roleC]);;
    
    /*Sedes   */
    Permission::create(['name_to_see' => 'Ver sedes',  'name'=>'SI_HEADQUARTER_SEE', 'guard_name'=>'web'])->syncRoles([$roleSA]);;
    Permission::create(['name_to_see' => 'Añadir sedes', 'name'=>'SI_HEADQUARTER_ADD', 'guard_name'=>'web' ])->syncRoles([$roleSA]);
    Permission::create(['name_to_see' => 'Actualizar sedes', 'name'=>'SI_HEADQUARTER_UPD', 'guard_name'=>'web' ])->syncRoles([$roleSA]);
    Permission::create(['name_to_see' => 'Eliminar sedes', 'name'=>'SI_HEADQUARTER_DEL', 'guard_name'=>'web' ])->syncRoles([$roleSA]);
    
    /*Usuarios */
    Permission::create(['name_to_see' => 'Ver usuario', 'name'=>'SI_USERS_SEE', 'guard_name'=>'web'])->syncRoles([$roleSA]);
    Permission::create(['name_to_see' => 'Crear Usuario', 'name'=>'SI_USERS_ADD', 'guard_name'=>'web'])->syncRoles([$roleSA ]);
    Permission::create(['name_to_see' => 'Editar Usuario', 'name'=>'SI_USERS_UPD', 'guard_name'=>'web'])->syncRoles([$roleSA]);
    Permission::create(['name_to_see' => 'Eliminar Usuario', 'name'=>'SI_USERS_DEL', 'guard_name'=>'web'])->syncRoles([$roleSA]);

    Permission::create(['name_to_see' => 'Ver permisos', 'name'=>'SI_PERMISSIONS_SEE', 'guard_name'=>'web'])->syncRoles([$roleSA]);
   
    Permission::create(['name_to_see' => 'Añadir permisos', 'name'=>'SI_PERMISSIONS_ADD', 'guard_name'=>'web'])->syncRoles([$roleSA]);
    Permission::create(['name_to_see' => 'Actualizar permisos', 'name'=>'SI_PERMISSIONS_UPD', 'guard_name'=>'web'])->syncRoles([$roleSA ]);
    Permission::create(['name_to_see' => 'Eliminar permisos', 'name'=>'SI_PERMISSIONS_DEL', 'guard_name'=>'web'])->syncRoles([$roleSA]);



    /*Perfil */
    Permission::create(['name_to_see' => 'Ver perfil', 'name'=>'SI_PROFILE_SEE', 'guard_name'=>'web'])->syncRoles([$roleSA,$roleA, $roleC ]); 
    
    /*TV*/
    Permission::create(['name_to_see' => 'Acceso a TV', 'name'=>'TV_SEE', 'guard_name'=>'web'])->syncRoles([$roleSA,$roleA ]);     
    Permission::create(['name_to_see' => 'Acceso a Dispensador de Ticket', 'name'=>'TD_SEE', 'guard_name'=>'web'])->syncRoles([$roleSA,$roleA ]);     
    
    User::create([
        "name" => "p",
        "email" => "holding.melendres@gmail.com",
        "password" => bcrypt("Melendres10"),
        "perId"=>1,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ])->assignRole($roleSA);

    }
}
