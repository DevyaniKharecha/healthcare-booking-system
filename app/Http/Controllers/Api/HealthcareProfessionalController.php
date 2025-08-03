<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthcareProfessional;
use OpenApi\Annotations as OA;


class HealthcareProfessionalController extends Controller
{
    /**4
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return HealthcareProfessional::all();
    }
}
