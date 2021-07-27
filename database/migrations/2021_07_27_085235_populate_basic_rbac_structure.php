<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Exceptions\RoleAlreadyExists;

class PopulateBasicRbacStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sysAdmin = Role::create(['title' => 'Системный администратор', 'name' => 'system_administrator']);
        $usersSectionPermission = Permission::create(['title' => 'Доступ к разделу "Пользователи"', 'name' => 'users_section']);
        $usersSectionPermission->assignRole($sysAdmin);

        $contentManager = Role::create(['title' => 'Контент-менеджер', 'name' => 'content_manager']);
        $goodsSectionPermission = Permission::create(['title' => 'Доступ к разделу "Товары"', 'name' => 'goods_section']);
        $goodsSectionPermission->assignRole($contentManager);

        $cook = Role::create(['title' => 'Повар', 'name' => 'cook']);
        $kitchenSectionPermission = Permission::create(['title' => 'Доступ к разделу "Кухня"', 'name' => 'kitchen_section']);
        $kitchenSectionPermission->assignRole($cook);

        $manager = Role::create(['title' => 'Управляющий', 'name' => 'manager']);
        $locationSectionPermission = Permission::create(['title' => 'Доступ к разделу "Точка"', 'name' => 'location_section']);
        $locationSectionPermission->assignRole($manager);

        $courier = Role::create(['title' => 'Курьер', 'name' => 'courier']);
        $deliverySectionPermission = Permission::create(['title' => 'Доступ к разделу "Доставка"', 'name' => 'delivery_section']);
        $deliverySectionPermission->assignRole($courier);

        $callCenterOperator = Role::create(['title' => 'Оператор Call Center', 'name' => 'call_center_operator']);
        $callCenterSectionPermission = Permission::create(['title' => 'Доступ к разделу "Колл центр"', 'name' => 'call_center_section']);
        $callCenterSectionPermission->assignRole($callCenterOperator);

        $analyst = Role::create(['title' => 'Аналитик', 'name' => 'analyst']);
        $reportSectionPermission = Permission::create(['title' => 'Доступ к разделу "Отчеты"', 'name' => 'reports_section']);
        $reportSectionPermission->assignRole($analyst);

        $admin = Role::create(['title' => 'Администратор', 'name' => 'administrator']);
        $locationSectionPermission->assignRole($admin);
        $reportSectionPermission->assignRole($admin);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $rolesToDelete = [
            'system_administrator',
            'administrator',
            'cook',
            'content_manager',
            'manager',
            'courier',
            'call_center_operator',
            'analyst',
        ];

        foreach ($rolesToDelete as $roleName) {
            try {
                Role::findByName($roleName)->delete();
            } catch (RoleAlreadyExists) {
                //ok
            }
        }

        $permissionsToDelete = [
            'users_section',
            'goods_section',
            'kitchen_section',
            'location_section',
            'delivery_section',
            'call_center_section',
            'reports_section',
        ];

        foreach ($permissionsToDelete as $permissionName) {
            try {
                Permission::findByName($permissionName)->delete();
            } catch (RoleAlreadyExists) {
                //ok
            }
        }
    }
}
