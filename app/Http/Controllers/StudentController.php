<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Student;
use App\Http\Resources\Student as StudentResource;


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($perList)
    {
        if($perList == null || ''){
            $perList = '10';
        }
        //get Students
        $Students = Student::orderBy('created_at', 'desc')->paginate($perList);
        
        //return the collection of Students as a resource
        return StudentResource::collection($Students);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Student = $request ->isMethod('put') ? Student::findOrfail
        ($request->id): new Student;

        $Student->id = $request->input('id');
        $Student->firstName = $request->input('firstName');
        $Student->lastName = $request->input('lastName');
        $Student->status = $request->input('status');
        $Student->phone = $request->input('phone');

        if($Student->save()){
            return new StudentResource($Student);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Student = Student::findOrFail($id);

        //return single Student
        return new StudentResource($Student);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Student = Student::findOrFail($id);

        if($Student->delete()){
            return new StudentResource($Student);
        }
    }
}
