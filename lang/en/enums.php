<?php

use App\Enums\AddressType;
use App\Enums\Permission;
use App\Enums\RahjooParent\RahjooParentGender;
use App\Enums\Role;

return [
    Role::class => [
        Role::SUPER_ADMIN => "Super admin",
        Role::PERSONNEL => "Personnel",
        Role::RAHJOO => "Rahjoo",
        Role::RAHNAMA => "Rahnama",
        Role::RAHYAB => "Rahyab",
        Role::SUPPORT => "Support",
        Role::STOREKEEPER => "Storekeeper",
        Role::AGENT => "Agent",
    ],
    Permission::class => [
        Permission::MANAGE_PERMISSIONS => "Manage permissions",
        Permission::VIEW_PERMISSIONS => "View permissions",
        Permission::CREATE_PERMISSIONS => "Create permissions",
        Permission::EDIT_PERMISSIONS => "Update permissions",
        Permission::DELETE_PERMISSIONS => "Delete permissions",
    ],
    AddressType::class => [
        AddressType::Home => "Home",
        AddressType::Office => "Office",
    ],
    RahjooParentGender::class => [
        RahjooParentGender::Male => "Male",
        RahjooParentGender::Female => "Female",
    ],
];
