<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

use App\User;

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
        if ($request->file('lattes')->isValid()){
            $name = $request->input('name');
            $year = $request->input('year');
            $file = utf8_encode(file_get_contents($request->file('lattes')->getRealPath()));
            $user = new User($name, $file, $year);
            $user->makeReport();
            return Redirect::action('ReportsController@show')
                                    ->with('user', $user);
        }
    }

    /**
     * Show the points acquired by the user.
     *
     * @return Response
     */
    public function show()
    {
        $user = Session::get('user');
        return view('reports.show', compact('user'));
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
