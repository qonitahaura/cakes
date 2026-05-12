<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customization;

class CustomizationController extends Controller
{
    public function index()
    {
        return Customization::with('options')->get();
    }
}
