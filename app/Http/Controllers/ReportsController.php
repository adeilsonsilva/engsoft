<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

use App\Professor;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Store a newly created report.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        var_dump($request['professor']['lattes']);
        if ($request['professor']['lattes']->isValid()){
            $professor = new Professor($request['professor']);
            $professor->makeReport();
            return Redirect::action('ReportsController@show')
                                    ->with('professor', $professor);
        }
    }

    /**
     * Show the points acquired by the professor.
     *
     * @return Response
     */
    public function show()
    {
        $professor = Session::get('professor');
        return view('reports.show', compact('professor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
