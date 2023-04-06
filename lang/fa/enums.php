<?php

use App\Enums\AddressType;
use App\Enums\Package\PackageStatus;
use App\Enums\Payment\PaymentStatus;
use App\Enums\Permission;
use App\Enums\RahjooParent\RahjooParentGender;
use App\Enums\Role;
use App\Enums\UserStatus;

return [
    Role::class => [
        Role::SUPER_ADMIN => "مدیرکل",
        Role::PERSONNEL => "پرسنل",
        Role::RAHJOO => "رهجو",
        Role::RAHNAMA => "رهنما",
        Role::RAHYAB => "رهیاب",
        Role::SUPPORT => "پشتیبان",
        Role::STOREKEEPER => "انباردار",
        Role::AGENT => "نماینده",
    ],
    Permission::class => [
        Permission::MANAGE_PERMISSIONS => "مدیریت نقش‌‌های کاربری",
        Permission::VIEW_PERMISSIONS => "مشاهده نقش‌‌های کاربری",
        Permission::CREATE_PERMISSIONS => "ایجاد نقش‌‌های کاربری",
        Permission::EDIT_PERMISSIONS => "بروزرسانی نقش‌‌های کاربری",
        Permission::DELETE_PERMISSIONS => "حذف نقش‌‌های کاربری",
    ],
    AddressType::class => [
        AddressType::Home => "خانه",
        AddressType::Office => "دفتر",
    ],
    UserStatus::class => [
        UserStatus::Active => "فعال",
        UserStatus::Inactive => "غیرفعال",
    ],
    RahjooParentGender::class => [
        RahjooParentGender::Male => "آقا",
        RahjooParentGender::Female => "خانم",
    ],
    PackageStatus::class => [
        PackageStatus::Active => "فعال",
        PackageStatus::Inactive => "غیرفعال",
    ],
    PaymentStatus::class => [
        PaymentStatus::Pending => "در حال پرداخت",
        PaymentStatus::Success => "موفق",
        PaymentStatus::Fail => "ناموفق",
        PaymentStatus::Canceled => "لغو شده",
    ],
];
