<?php

// enum ERegle corrigé en PHP
enum ERegle: int
{
    case CREATE_POST = 1;
    case DELETE_POST = 2;
    case UPDATE_POST = 3;
    case READ_POST = 4;
    case CREATE_EVENT = 9;
    case DELETE_EVENT = 10;
    case UPDATE_EVENT = 11;
    case READ_EVENT = 12;
}

class CRegle
{
    // comparateur de l'énumération ERegle et du numero de la regle
    public static function F_bIsAutorise(ERegle $l_iRegle, array $l_lPermission): bool
    {
        foreach ($l_lPermission as $l_iIdR) {
            if ($l_iIdR["idR"] == $l_iRegle->value) {
                return true;
            }
        }
        return false;
    }
}