<?php

use Caffeinated\Shinobi\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Usuarios
        Permission::create([
            'name'          =>  'Ver usuarios',
            'slug'          =>  'users.index',
            'description'   =>  'Ver los usuarios del sistema',
        ]);

        Permission::create([
            'name'          =>  'Crear usuarios',
            'slug'          =>  'users.create',
            'description'   =>  'Crear usuarios en el sistema',
        ]);

        Permission::create([
            'name'          =>  'Editar usuarios',
            'slug'          =>  'users.edit',
            'description'   =>  'Editar usuarios en el sistema',
        ]);     

        Permission::create([
            'name'          =>  'Eliminar usuarios',
            'slug'          =>  'users.destroy',
            'description'   =>  'Eliminar los usuarios del sistema',
        ]);


        //Roles
        Permission::create([
            'name'          =>  'Ver roles',
            'slug'          =>  'roles.index',
            'description'   =>  'Ver los roles del sistema',
        ]);

        Permission::create([
            'name'          =>  'Crear roles',
            'slug'          =>  'roles.create',
            'description'   =>  'Crear roles en el sistema',
        ]);

        Permission::create([
            'name'          =>  'Editar roles',
            'slug'          =>  'roles.edit',
            'description'   =>  'Editar roles en el sistema',
        ]);     

        Permission::create([
            'name'          =>  'Eliminar roles',
            'slug'          =>  'roles.destroy',
            'description'   =>  'Eliminar los roles del sistema',
        ]);
    }
}
