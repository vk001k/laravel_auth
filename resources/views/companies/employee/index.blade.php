@extends('layouts.app')

@section('content')


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Employee</div>

                    <div class="card-body">
                        <button type="button" data-toggle="modal" data-target="#createModal" class="btn btn-success mb-2">Create new Employee</button>
                       <table class="table" id="employee">
                           <thead>
                           <tr>
                               <th>Full name</th>
                               <th>Company</th>
                               <th>Email</th>
                               <th>Phone</th>
                                <th>Action</th>
                           </tr>
                           </thead>
                           <tbody>

                           @foreach($data as $employee)
                               <tr>
                                   <td>{{$employee->full_name}}</td>
                                   <td>{{$employee->company_name}}</td>
                                   <td>{{$employee->email}}</td>
                                   <td>{{$employee->phone}}</td>
                                   <td>
                                       <button  type="button"  onclick="updateModal({{$employee->id}})" class="btn btn-primary">Edit</button>
                                       <button  onclick="deleteCompany('{{$employee->id}}')"  class="btn btn-danger">Delete</button>
                                       <a href="{{url('company/employees/show/'.$employee->id)}}"    class="btn btn-success">show</a>

                                   </td>
                               </tr>
                               @endforeach

                           </tbody>
                       </table>
                        {{$data->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>





    <!-- Create Modal -->

    <div id="createModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Employee</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="print-error-msg" id="response">
                        <ul></ul>
                    </div>
                    <form method="POST" id="employeeCreateForm"  enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="company" class="col-md-4 col-form-label text-md-right">Company</label>

                            <div class="col-md-6">
                                <select name="company" class="form-control" readonly>
                                    <option value="{{$company->id}}">{{$company->name}}</option>
                                </select>


                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Full name</label>

                            <div class="col-md-6">
                                <input id="name_n" type="text" class="form-control" name="name_n" value="" required autofocus>

                                @error('name_n')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email_n" type="email" class="form-control @error('email') is-invalid @enderror" name="email_n" value="{{ old('email') }}" required autocomplete="email_n">

                                @error('email_n')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone_n" class="col-md-4 col-form-label text-md-right">Phone</label>

                            <div class="col-md-6">
                                <input id="phone_n" type="number"  class="form-control @error('phone_n') is-invalid @enderror"  name="phone_n" required >

                                @error('phone_n')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="storeEmployee()">
                        Create
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <!-- update Modal -->

    <div id="updateModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Employee</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="print-error-msg" id="response_u">
                        <ul></ul>
                    </div>
                    <form method="POST" id="employeeUpdateForm"  enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="employee_id" id="employee_id">
                        <div class="form-group row">
                            <label for="company_u" class="col-md-4 col-form-label text-md-right">Company</label>

                            <div class="col-md-6">
                                <select name="company_u" id="company_u" class="form-control" readonly required>
                                    {{--<option value="">Select company</option>--}}
                                    {{--@foreach($companies as $company)--}}
                                        {{--<option value="{{$company->id}}">{{$company->name}}</option>--}}
                                    {{--@endforeach--}}
                                </select>


                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Full name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="" required autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email_n">

                                @error('email_n')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone_n" class="col-md-4 col-form-label text-md-right">Phone</label>

                            <div class="col-md-6">
                                <input id="phone" type="number"  class="form-control @error('phone') is-invalid @enderror"  name="phone" required >

                                @error('phone_n')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="updateEmployee()">
                        Update
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

<script>


    function storeEmployee() {

        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        var email = reg.test($('#email_n').val());
        if(email == false ){
            alert('Invalid Email Address');
            return false;
        }
        if($('#phone_n').val().length  != 10 ){
            alert('Invalid mobile number');
            return false;
        }
        var form = $('#employeeCreateForm')[0];

        var formdata = new FormData(form);
        $.ajax({
            url:'{{route('company.employees.store')}}',
            type:'post',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            processData: false,  // Important!
            contentType: false,
            cache: false,
            {{--data:{--}}
                    {{--_token:'{{csrf_token()}}',--}}
                    {{--company_id:$('#company_id').val(),--}}
                    {{--name:$('#name').val(),--}}
                    {{--email:$('#email').val(),--}}
                    {{--website:$('#website').val(),--}}
                    {{--},--}}
            data: formdata,

            success:function(data){
                if(data.status){
                    $('#createModal').modal('hide');
                    $("#employee").load(window.location + " #employee");
                    alert(data.success);
                }else{
                    alert(data.error);
                }
            },
            error: function(data){// this are default for ajax errors
                if( data.status === 422 ) {
                    var errors = $.parseJSON(data.responseText);
                    $.each(errors, function (key, value) {

                        // console.log(key+ " " +value);
                        $('#response').addClass("alert alert-danger");

                        if($.isPlainObject(value)) {
                            $.each(value, function (key, value) {
                                console.log(key+ " " +value);
                                $('#response').show().append(value+"<br/>");

                            });
                        }else{
                            $('#response').show().append(value+"<br/>"); //this is my div with messages
                        }
                    });
                }
            }

        });
    }




    function updateEmployee(){
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        var email = reg.test($('#email').val());

        if(email == false ){
            alert('Invalid Email Address');
            return false;
        }
        if($('#phone').val().length  != 10 ){
            alert('Invalid mobile number');
            return false;
        }
        var form = $('#employeeUpdateForm')[0];

        var formdata = new FormData(form);
        $.ajax({
            url:'{{url('company/employees/update')}}',
            type:'post',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            processData: false,  // Important!
            contentType: false,
            cache: false,
            {{--data:{--}}
                    {{--_token:'{{csrf_token()}}',--}}
                    {{--company_id:$('#company_id').val(),--}}
                    {{--name:$('#name').val(),--}}
                    {{--email:$('#email').val(),--}}
                    {{--website:$('#website').val(),--}}
                    {{--},--}}
            data: formdata,

            success:function(data){
                if(data.status){
                    console.log(data);
                    $('#updateModal').modal('hide');
                    $("#employee").load(window.location + " #employee");
                    alert(data.success);
                }else{
                    alert(data.error);
                }
            },
            error: function(data){// this are default for ajax errors
                if( data.status === 422 ) {
                    var errors = $.parseJSON(data.responseText);
                    $.each(errors, function (key, value) {

                        // console.log(key+ " " +value);
                        $('#response_u').addClass("alert alert-danger");

                        if($.isPlainObject(value)) {
                            $.each(value, function (key, value) {
                                console.log(key+ " " +value);
                                $('#response_u').show().append(value+"<br/>");

                            });
                        }else{
                            $('#response_u').show().append(value+"<br/>"); //this is my div with messages
                        }
                    });
                }
            }

        });
    }

    function updateModal(id){
        $('#updateModal').modal('show');
        $.ajax({
            url:'{{url('company/employees/getEmployeeDetails')}}',
            type:'post',
            data:{employee_id:id,_token:'{{csrf_token()}}'},
            success:function (data) {

                if(data){
                    $('#employee_id').val(data.id);
                    var e ='';
                    e +='<option value="'+data.company_id+'">'+data.company+'</option>';
                    $('#company_u').html(e);
                    $('#name').val(data.full_name);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                }
            }
        });

    }


    var msg;
        @if(session()->has('error'))

                msg = '{{session()->get('error')}}';
        alert(msg);
        @endif
                @if(session()->has('success'))

                msg = '{{session()->get('success')}}';
        alert(msg);
        @endif


        function deleteCompany(id){

            $.ajax({
               url:'{{url('company/employees/destroy')}}',
                type:'get',
                data:{employee_id:id},
                success:function(e){

                    if(e.status){
                        alert('Employee deleted successfully');
                        $("#employee").load(window.location + " #employee");
                    }
                }
            });
        }

    </script>
@endsection


