<?php

namespace App\Http\Controllers;

use App\Companies;
use App\Mail\NotifyAdmin;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CompaniesController extends Controller
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
        $data = Companies::orderBy('id','desc')->paginate(10);
        return view('companies.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:companies,email,',
            'website' => 'required',
            'logo' => 'required|mimes:jpeg,png|dimensions:max_width=100,max_height=100',


        ]);

        if ($validator->fails()) {
            if($request->ajax())
            {
                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorect values in the form!',
                    'errors' => $validator->getMessageBag()->toArray()
                ), 422);
            }
        }

        $user = new User();
        $user->name =  $request->name;
        $user->email =  $request->email;
        $user->password =  Hash::make('Password@123');
        $user->role =  2;
        $user->save();
        $comapany = new Companies();
        $comapany->users_id = $user->id;
        $comapany->name = $request->name;
        $comapany->email = $request->email;
        $comapany->website = $request->website;
        if($request->hasFile('logo')){
            $coverImage = $request->file('logo');
            $extension = $coverImage->getClientOriginalExtension();
            $path = public_path('logo');
            $coverImage->move($path,$coverImage->getClientOriginalName());
            $comapany->logo = $coverImage->getClientOriginalName();
        }

        if($comapany->save()){
            new \stdClass();
            Mail::to(Auth::user()->email)
                ->send(new NotifyAdmin($comapany));
            return response()->json(['status'=>true,'success'=>'company created successfully']);
        }else{
            return response()->json(['status'=>false,'error'=>'company not created']);
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
        $company = Companies::where('id',$id)->first();

        return view('companies.show',compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Companies::find($id);
       return view('companies.edit',compact('company'));
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
            'logo' => 'required|mimes:jpeg,png|dimensions:max_width=100,max_height=100',
            'email' => 'unique:companies,email,'.$request->company_id
        ]);
        $company = Companies::where('id',$request->company_id)->first();
        if($company){
            $company->name = $request->name;
            $company->email = $request->email;
            $company->website = $request->website;
            if($request->hasFile('logo')){
                $coverImage = $request->file('logo');
                $extension = $coverImage->getClientOriginalExtension();
                $path = public_path('logo');
                $coverImage->move($path,$coverImage->getClientOriginalName());
                $company->logo = $coverImage->getClientOriginalName();
            }
            $company->save();
            $user = User::where('id',$company->users_id)->first();
            $user->name = $company->name;
            $user->email = $company->email;
            $user->save();
            return response()->json(['status'=>true,'success'=>'company updated successfully']);
        }else{
            return response()->json(['status'=>false,'error'=>'company not updated']);
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
        $company =  Companies::where('id',$request->company_id)->first();

        $user = User::where('id',$company->users_id)->first();
        $user->delete();
        $company->delete();
        return response()->json(array('status'=>true));
    }

    public function getCompany(Request $request){
        $company = Companies::where('id',$request->company_id)->first();
        if($company){
            return response()->json(['status'=>true,'company'=>$company]);
        }else{
            return response()->json(['status'=>false,'company'=>null]);
        }
    }
}
