<?php

namespace App\Http\Controllers;

use App\Companies;
use App\Employee;
use App\Mail\NotifyAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $companies = Companies::get(['id','name']);
        $data = Employee::join('companies','employees.company_id','companies.id')
            ->select('employees.*','companies.name as company_name')
            ->paginate(10);
        return view('employee.index',compact('data','companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Companies::get(['id','name']);
        return view('employee.create',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name_n' => 'required|max:255',
            'email_n' => 'required|string|email|max:255',





        ]);
        $CheckEmploye = Employee::where('company_id',$request->company)->where('email',$request->email_n)->first();
        if(!$CheckEmploye){
            $comapany = new Employee();
            $comapany->company_id = $request->company;
            $comapany->full_name = $request->name_n;
            $comapany->email = $request->email_n;
            $comapany->phone = $request->phone_n;
            $comapany->save();
            return response()->json(['status'=>true,'success'=>'employee created successfully']);
        }else{
            return response()->json(['status'=>false,'error'=>'employee already exist']);
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
        $employee = Employee::join('companies','employees.company_id','companies.id')->where('employees.id',$id)->first(['companies.id as company_id','companies.name as company','employees.*']);

        return view('employee.show',compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Companies::get(['id','name']);
        $employee = Employee::join('companies','employees.company_id','companies.id')->where('employees.id',$id)->first(['companies.id as company_id','companies.name as company','employees.*']);


       return view('employee.edit',compact('employee','data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {


        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required',

        ]);
        $employee = Employee::where('id',$request->employee_id)->first();
        $check = Employee::whereNotIn('id',[$request->employee_id])->where('email',$request->email)->where('company_id',$request->company_u)->first();

        if($employee && empty($check)){

            $employee->company_id = $request->company_u;
            $employee->full_name = $request->name;
            $employee->email = $request->email;
            $employee->phone = $request->phone;

            $employee->save();
            return response()->json(['status'=>true,'success'=>'Employee updated successfully']);
        }else{
            return response()->json(['status'=>false,'error'=>'Employee not updated']);
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {


       $employee =  Employee::where('id',$request->employee_id)->first();

        $employee->delete();
        return response()->json(array('status'=>true));
    }

    public function getEmployeeDetails(Request $request){
        $employee = Employee::where('id',$request->employee_id)->first();
        return $employee;
    }
}
