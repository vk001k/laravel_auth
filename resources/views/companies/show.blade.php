@extends('layouts.app')

@section('content')


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Companies Details </div>

                    <a href="{{url()->previous()}}" class="btn btn-default">Back</a>
                    <div class="card-body">

                       <table class="table table-bordered" id="company">
                           <tbody>
                            <tr>

                                <td>Company Name: <br>{{$company->name}}</td>

                                <td>Website: <br>{{$company->website?$company->website:'NA'}}</td>

                                <td>Email: <br>{{$company->email}}</td>
                                <td>Company logo: <br><img src="{{url('logo/'.$company->logo)}}" width="100px" height="100px"></td>

                                     
                            </tr>
                           </tbody>
                       </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


