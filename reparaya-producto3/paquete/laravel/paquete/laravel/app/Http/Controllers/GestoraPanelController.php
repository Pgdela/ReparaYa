<?php

namespace App\Http\Controllers;

use App\Models\Gestora;
use Illuminate\Support\Facades\Auth;

class GestoraPanelController extends Controller
{
    public function index()
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'gestora',
            403,
            'No tienes permiso para acceder al panel de gestoras.'
        );

        $gestora = Gestora::with(['comunidades.zona'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('gestora.index', compact('gestora'));
    }
}