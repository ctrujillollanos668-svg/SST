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

        // Entidades base para vincular personas
        $eps = EPS::firstOrCreate(['name' => 'NO REGISTRA']);
        $pension = PensionEntity::firstOrCreate(['name' => 'NO REGISTRA']);
        $population = PopulationGroup::firstOrCreate(['name' => 'NINGUNA']);

        // 3. ADMINISTRADOR SST (Únicamente Olga Lucía)
        $person_olga = Person::firstOrCreate(['document_number' => 1234567890], [
            'document_type' => 'Cédula de ciudadanía',
            'first_name' => 'OLGA LUCIA',
            'first_last_name' => 'ADMINISTRADORA',
            'eps_id' => $eps->id,
            'population_group_id' => $population->id,
            'pension_entity_id' => $pension->id
        ]);

        $user_admin = User::updateOrCreate(
            ['person_id' => $person_olga->id],
            [
                'nickname' => 'olga patricia',
                'email' => 'ing.olgapatricia@gmail.com',
                'password' => Hash::make('12345678')
            ]
        );

        // sync() asegura que NADIE MÁS tenga el rol de Administrador SST excepto Olga
        $role_admin->users()->sync([$user_admin->id]);

        // 4. FUNCIONARIO SST (Únicamente Yuliana Carolina)
        $person_yuliana = Person::firstOrCreate(['document_number' => 1098765432], [
            'document_type' => 'Cédula de ciudadanía',
            'first_name' => 'YULIANA CAROLINA',
            'first_last_name' => 'FUNCIONARIA',
            'eps_id' => $eps->id,
            'population_group_id' => $population->id,
            'pension_entity_id' => $pension->id
        ]);

        $user_funcionario = User::updateOrCreate(
            ['person_id' => $person_yuliana->id],
            [
                'nickname' => 'Yuliana',
                'email' => 'apr.yulianacarolina@gmail.com',
                'password' => Hash::make('12345678')
            ]
        );

        // sync() asegura que NADIE MÁS tenga el rol de Funcionario SST excepto Yuliana
        $role_funcionario->users()->sync([$user_funcionario->id]);
    }
}
