<?php

namespace App\Http\Controllers;

use App\Models\BB40;
use Illuminate\Http\Request;

class BB40Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = BB40::where('jenis','BC 4.0')->orderBy('datedoc','desc')->get();
        return response()->json([
            'status' => true,
            'message' => 'Data BC 4.0 ditemukan',
            'asalData' => 'S',
            'asuransi' => '0.00',
            'data' => $data
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BB40 $bB40)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BB40 $bB40)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BB40 $bB40)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BB40 $bB40)
    {
        //
    }
}
