<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    /**
     * Mostrar Aviso Legal
     */
    public function avisoLegal()
    {
        return view('legal.aviso-legal');
    }

    /**
     * Mostrar Política de Privacidad
     */
    public function privacidad()
    {
        return view('legal.privacidad');
    }

    /**
     * Mostrar Política de Cookies
     */
    public function cookies()
    {
        return view('legal.cookies');
    }

    /**
     * Mostrar Términos y Condiciones
     */
    public function terminos()
    {
        return view('legal.terminos');
    }

    /**
     * Mostrar Términos de Contratación
     */
    public function terminosContratacion()
    {
        return view('legal.terminos-contratacion');
    }

    /**
     * Mostrar Condiciones de Pago y Cancelación
     */
    public function condicionesPago()
    {
        return view('legal.condiciones-pago');
    }
}
