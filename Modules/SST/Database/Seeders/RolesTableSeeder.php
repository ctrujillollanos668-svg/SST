<?php

namespace Modules\SST\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\SICA\Entities\App;
use Modules\SICA\Entities\EPS;
use Modules\SICA\Entities\PensionEntity;
use Modules\SICA\Entities\Person;
use Modules\SICA\Entities\PopulationGroup;
use Modules\SICA\Entities\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Buscamos o creamos el aplicativo SST en la tabla 'apps'
        $app = App::where('name', 'Sst')->orWhere('name', 'SST')->first();

        if (!$app) {
            $app = App::create([
                'name' => 'Sst',
                'url' => '/Sst',
                'color' => '#28a745',
                'icon' => 'fas fa-hard-hat',
                'description' => 'Módulo de Seguridad y Salud en el Trabajo',
                'description_english' => 'Occupational Health and Safety Module'
            ]);
        }

        // 2. CREAR O ACTUALIZAR LOS 2 ROLES
        $role_admin = Role::updateOrCreate(['slug' => 'sst.admin'], [
            'name' => 'Administrador SST',
            'description' => 'Rol Administrador del módulo SST',
            'description_english' => 'SST Module Administrator Role',
            'full_access' => 'No',
            'app_id' => $app->id
        ]);

        $role_funcionario = Role::updateOrCreate(['slug' => 'sst.funcionario'], [
            'name' => 'Funcionario SST',
            'description' => 'Rol Funcionario del módulo SST',
            'description_english' => 'SST Module Official Role',
            'full_access' => 'No',
            'app_id' => $app->id
        ]);

        // 3. ASIGNAR ROL AL ADMINISTRADOR
        // Desvincular a damendez de SST si lo tenía asignado
        $user_damendez = User::where('nickname', 'damendez')->orWhere('email', 'ing.diego.mendez@gmail.com')->first();
        if ($user_damendez) {
            $user_damendez->roles()->detach($role_admin->id);
        }

        // Consultar o crear la Persona vinculada a Olga (obligatorio en la BD del SENA)
        $eps = EPS::firstOrCreate(['name' => 'NO REGISTRA']);
        $pension = PensionEntity::firstOrCreate(['name' => 'NO REGISTRA']);
        $population = PopulationGroup::firstOrCreate(['name' => 'NINGUNA']);

        $person_olga = Person::firstOrCreate(['document_number' => 1234567890], [
            'document_type' => 'Cédula de ciudadanía',
            'first_name' => 'OLGA LUCIA',
            'first_last_name' => 'ADMINISTRADORA',
            'eps_id' => $eps->id,
            'population_group_id' => $population->id,
            'pension_entity_id' => $pension->id
        ]);

        // Crear o actualizar a Olga con su contraseña y asignarle el rol de Administrador SST
        $user_admin = User::updateOrCreate(
            ['person_id' => $person_olga->id],
            [
                'nickname' => 'olga patricia',
                'email' => 'ing.olgapatricia@gmail.com',
                'password' => Hash::make('12345678')
            ]
        );
        $user_admin->roles()->syncWithoutDetaching([$role_admin->id]);

        // 4. ASIGNAR ROL AL FUNCIONARIO
        $user_funcionario = User::where('nickname', 'DiegoT')->orWhere('email', 'jdguevara01@soy.sena.edu.co')->first();
        if ($user_funcionario) {
            $user_funcionario->roles()->syncWithoutDetaching([$role_funcionario->id]);
        }
    }
}
