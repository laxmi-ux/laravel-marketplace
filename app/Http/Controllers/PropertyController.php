<?php

namespace App\Http\Controllers;

class PropertyController extends Controller
{
    public function index()
    {
        return view('properties.index');
    }
}