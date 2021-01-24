<?php

namespace Parzibyte;

use Parzibyte\BD;
use Parzibyte\Seguridad;

class Usuarios
{
    public static function agregarUsuario($correo, $palabraSecreta)
    {
        $palabraSecretaHasheada = Seguridad::hashearPalabraSecreta($palabraSecreta);
        $bd = BD::obtener();
        $sentencia = $bd->prepare("INSERT INTO usuarios(correo, palabra_secreta) VALUES(?, ?)");
        return $sentencia->execute([$correo, $palabraSecretaHasheada]);
    }

    static function obtenerUnoPorCorreo($correo)
    {
        $db = BD::obtener();
        $sentencia = $db->prepare("SELECT id, correo, palabra_secreta FROM usuarios WHERE correo = ?");
        $sentencia->execute([$correo]);
        return $sentencia->fetchObject();
    }

    static function login($correo, $palabraSecreta)
    {
        $usuario = self::obtenerUnoPorCorreo($correo);
        if (!$usuario) {
            return false;
        }
        return Seguridad::verificarPalabraSecreta($palabraSecreta, $usuario->palabra_secreta);
    }
}