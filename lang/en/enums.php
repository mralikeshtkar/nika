<?php

use App\Enums\AddressType;
use App\Enums\Discount\DiscountStatus;
use App\Enums\Order\OrderStatus;
use App\Enums\Package\PackageStatus;
use App\Enums\Payment\PaymentStatus;
use App\Enums\Permission;
use App\Enums\Product\ProductStatus;
use App\Enums\RahjooParent\RahjooParentGender;
use App\Enums\Role;
use App\Enums\Ticket\TicketStatus;

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
    PackageStatus::class => [
        PackageStatus::Active => "Active",
        PackageStatus::Inactive => "Inactive",
    ],
    PaymentStatus::class => [
        PaymentStatus::Pending => "Pending",
        PaymentStatus::Success => "Success",
        PaymentStatus::Fail => "Fail",
        PaymentStatus::Canceled => "Canceled",
    ],
    OrderStatus::class => [
        OrderStatus::Preparation => "Preparation",
        OrderStatus::Posted => "Posted",
        OrderStatus::Delivered => "Delivered",
    ],
    DiscountStatus::class => [
        DiscountStatus::Active => "Active",
        DiscountStatus::Inactive => "Inactive",
    ],
    ProductStatus::class => [
        ProductStatus::Active => "Active",
        ProductStatus::Inactive => "Inactive",
    ],
    TicketStatus::class => [
        TicketStatus::Open => "Open",
        TicketStatus::Close => "Close",
        TicketStatus::Canceled => "Canceled",
    ],
];
