<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class Permission extends Enum implements LocalizedEnum
{
    /* Permissions */
    const MANAGE_PERMISSIONS = "manage permissions";
    const VIEW_PERMISSIONS = "view permissions";
    const CREATE_PERMISSIONS = "create permissions";
    const EDIT_PERMISSIONS = "edit permissions";
    const DELETE_PERMISSIONS = "delete permissions";

    /* Provinces */
    const MANAGE_PROVINCES = "manage provinces";
    const VIEW_PROVINCES = "view provinces";
    const CREATE_PROVINCES = "create provinces";
    const EDIT_PROVINCES = "edit provinces";
    const DELETE_PROVINCES = "delete provinces";

    /* Cities */
    const MANAGE_CITIES = "manage cities";
    const VIEW_CITIES = "view cities";
    const CREATE_CITIES = "create cities";
    const EDIT_CITIES = "edit cities";
    const DELETE_CITIES = "delete cities";

    /* Cities */
    const MANAGE_GRADES = "manage grades";
    const VIEW_GRADES = "view grades";
    const CREATE_GRADES = "create grades";
    const EDIT_GRADES = "edit grades";
    const DELETE_GRADES = "delete grades";

    /* Addresses */
    const MANAGE_ADDRESSES = "manage addresses";
    const VIEW_ADDRESSES = "view addresses";
    const CREATE_ADDRESSES = "create addresses";
    const EDIT_ADDRESSES = "edit addresses";
    const DELETE_ADDRESSES = "delete addresses";

    /* Majors */
    const MANAGE_MAJORS = "manage majors";
    const VIEW_MAJORS = "view majors";
    const CREATE_MAJORS = "create majors";
    const EDIT_MAJORS = "edit majors";
    const DELETE_MAJORS = "delete majors";

    /* Jobs */
    const MANAGE_JOBS = "manage jobs";
    const VIEW_JOBS = "view jobs";
    const CREATE_JOBS = "create jobs";
    const EDIT_JOBS = "edit jobs";
    const DELETE_JOBS = "delete jobs";

    /* Users */
    const MANAGE_USERS = "manage users";
    const VIEW_USERS = "view users";
    const CREATE_USERS = "create users";
    const EDIT_USERS = "edit users";
    const DELETE_USERS = "delete users";

    /* Personnels */
    const MANAGE_PERSONNELS = "manage personnels";
    const VIEW_PERSONNELS = "view personnels";
    const STORE_PERSONNELS = "store personnels";
    const DELETE_PERSONNELS = "delete personnels";

    /* Rahjoos */
    const MANAGE_RAHJOOS = "manage rahjoos";
    const VIEW_RAHJOOS = "view rahjoos";
    const STORE_RAHJOOS = "store rahjoos";
    const DELETE_RAHJOOS = "delete rahjoos";

    /* Rahjoo parents */
    const MANAGE_RAHJOO_PARENTS = "manage rahjoo parents";
    const VIEW_RAHJOO_PARENTS = "view rahjoo parents";
    const CREATE_RAHJOO_PARENTS = "create rahjoo parents";
    const DELETE_RAHJOO_PARENTS = "delete rahjoo parents";

    /* Rahjoo courses */
    const MANAGE_RAHJOO_COURSES = "manage rahjoo courses";
    const VIEW_RAHJOO_COURSES = "view rahjoo courses";
    const CREATE_RAHJOO_COURSES = "create rahjoo courses";
    const DELETE_RAHJOO_COURSES = "delete rahjoo courses";

    /* Psychological questions */
    const MANAGE_PSYCHOLOGICAL_QUESTIONS = "manage psychological questions";
    const VIEW_PSYCHOLOGICAL_QUESTIONS = "view psychological questions";
    const CREATE_PSYCHOLOGICAL_QUESTIONS = "create psychological questions";
    const DELETE_PSYCHOLOGICAL_QUESTIONS = "delete psychological questions";

    /* Skills */
    const MANAGE_SKILLS = "manage skills";
    const VIEW_SKILLS = "view skills";
    const CREATE_SKILLS = "create skills";
    const EDIT_SKILLS = "edit skills";
    const DELETE_SKILLS = "delete skills";

    /* Packages */
    const MANAGE_PACKAGES = "manage packages";
    const VIEW_PACKAGES = "view packages";
    const SHOW_PACKAGES = "show packages";
    const CREATE_PACKAGES = "create packages";
    const EDIT_PACKAGES = "edit packages";
    const DELETE_PACKAGES = "delete packages";

    /* Intelligences */
    const MANAGE_INTELLIGENCES = "manage intelligences";
    const VIEW_INTELLIGENCES = "view intelligences";
    const CREATE_INTELLIGENCES = "create intelligences";
    const EDIT_INTELLIGENCES = "edit intelligences";
    const DELETE_INTELLIGENCES = "delete intelligences";
}
