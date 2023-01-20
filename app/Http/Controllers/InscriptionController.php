<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscription;

class InscriptionController extends Controller
{
    function update(Request $request){
       
        $inscription  = Inscription::first();
        $inscription->inscription_period = $request->inscription_period;
        $inscription->save();

        return ['success' => true];
    }

    function index(){
        $inscription  = Inscription::first();
        return ['inscription_period' => $inscription->inscription_period];
    }
}
