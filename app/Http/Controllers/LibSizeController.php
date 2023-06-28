<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\LibSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreLibSizeRequest;
use App\Http\Requests\UpdateLibSizeRequest;

class LibSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $menu_id = $request->query('mid') ?? 0;
        $permission = getPagePermission($menu_id);
        return view('lib.general.size',compact('permission'));
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
        DB::beginTransaction();
        try
        {
            $lib_size=LibSize::create([
                'size_name'=>$request->input('txt_size_name')
            ]);

            DB::commit();
            return response()->json([
                'code'=>0,
                'message'=>'success',
                'data'=>$lib_size
            ]);
        }
        catch(Exception $e)
        {
            DB::rollBack();
            $error_message ="Error: ".$e->getMessage()." in ".$e->getFile()." at line ".$e->getLine();
            return response()->json([
                'code'=>10,
                'message'=>$error_message,
                'data'=> [
                ]
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LibSize $libSize)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LibSize $libSize)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LibSize $size)
    {
        DB::beginTransaction();
        try
        {
            $size->update([
                'size_name'=>$request->input('txt_size_name')
            ]);
    
            DB::commit();
            return response()->json([
                'code'=>1,
                'message'=>'success',
                'data'=>$size
            ]);
        }
        catch(Exception $e)
        {
            $error_message ="Error: ".$e->getMessage()." in ".$e->getFile()." at line ".$e->getLine();
            return response()->json([
                'code'=>10,
                'message'=>$error_message,
                'data'=> [
                ]
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LibSize $size)
    {
        DB::beginTransaction();
        try
        {
            $size->delete();
            DB::commit();
            return response()->json([
                'code'=>2,
                'message'=>'success',
                'data'=>[]
            ]);
        }
        catch(Exception $e)
        {
            DB::rollBack();
            $error_message ="Error: ".$e->getMessage()." in ".$e->getFile()." at line ".$e->getLine();
            return response()->json([
                'code'=>10,
                'message'=>$error_message,
                'data'=> [
                ]
            ]);
        }
    }
}
