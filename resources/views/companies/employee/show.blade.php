@extends('layouts.app')

@section('content')


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Employee Details </div>

                    <a href="{{url()->previous()}}" class="btn btn-default">Back</a>
                    <div class="card-body">

                       <table class="table table-bordered" id="company">
                           <tbody>
                            <tr>

                                <td>Employee Name: <br>{{$employee->full_name}}</td>
                                <td>Company Name: <br>{{$employee->company}}</td>

                                <td>Phone: <br>{{$employee->phone?$employee->phone:'NA'}}</td>

                                <td>Email: <br>{{$employee->email}}</td>


                                     
                            </tr>
                           </tbody>
                       </table>
                    </div>
                </div>
            </div>
        </div>
    </div>






@endsection


