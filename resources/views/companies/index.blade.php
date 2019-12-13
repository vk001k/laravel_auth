@extends('layouts.app')

@section('content')


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Companies</div>

                    <div class="card-body">
                        <button type="button"  id="createCompany" class="btn btn-success mb-2" data-toggle="modal" data-target="#createModal">Create new company</button>
                       <table class="table" id="company">
                           <thead>
                           <tr>
                               <th>Name</th>
                               <th>Email</th>
                               <th>logo</th>
                               <th>Website</th>
                                <th>Action</th>
                           </tr>
                           </thead>
                           <tbody>

                           @foreach($data as $company)
                               <tr>
                                   <td>{{$company->name}}</td>

                                   <td>{{$company->email}}</td>
                                   <td><img src="{{url('logo/'.$company->logo)}}" width="50px" height="50px"> </td>
                                   <td>{{$company->website?$company->website:'NA'}}</td>
                                   <td>
                                       <a type="button"  class="btn btn-primary" data-toggle="modal"  onclick="updateModal({{$company->id}})">Edit</a>
                                       <button  onclick="deleteCompany('{{$company->id}}')"  class="btn btn-danger">Delete</button>
                                       <a href="{{url('companies/show/'.$company->id)}}"    class="btn btn-success">show</a>

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
                    <h4 class="modal-title">Create Company</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="print-error-msg" id="response">
                        <ul></ul>
                    </div>
                    <form method="POST"  id="companyCreateForm"  enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name_n" type="text" class="form-control " name="name" value="" required  autofocus>

                                @error('name_n')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email_n" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email_n" type="email" class="form-control" name="email" value="" required >
                                @error('email_n')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="logo" class="col-md-4 col-form-label text-md-right">Logo</label>


                            <div class="col-md-6">
                               <input id="logo_n" type="file" class="form-control" name="logo" >
                                <span style="font-size: 9px;font-weight: 900;">Image dimension should be or less than 100x100</span>
                            </div>
                            @error('logo_n')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <label for="website" class="col-md-4 col-form-label text-md-right">Website</label>

                            <div class="col-md-6">
                                <input id="website_n" type="url" value="" class="form-control"  placeholder="https://example.com" name="website" required >

                                @error('website_n')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>




                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="storeCompany()">
                        Create
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>


    <!-- update Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Company</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="print-error-msg" id="response_u">
                        <ul></ul>
                    </div>
                    <form method="POST"  id="companyUpdateForm"  enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="company_id" name="company_id" value="">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control " name="name" value="" required  autofocus>

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
                                <input id="email" type="email" class="form-control" name="email" value="" required >
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="logo" class="col-md-4 col-form-label text-md-right">Logo</label>


                            <div class="col-md-6">
                                <div class="compnayLogo">

                                </div>

                                <input id="logo" type="file" class="form-control" name="logo" >
                                <span style="font-size: 9px;font-weight: 900;">Image dimension should be or less than 100x100</span>
                            </div>
                            @error('logo')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <label for="website" class="col-md-4 col-form-label text-md-right">Website</label>

                            <div class="col-md-6">
                                <input id="website" type="url" value="" class="form-control"  placeholder="https://example.com" name="website" required >

                                @error('website')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>




                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="updateCompany()">
                        Update
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <script>

        function storeCompany() {

            var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            var email = reg.test($('#email_n').val());
            if(email == false ){
                alert('Invalid Email Address');
                return false;
            }
            var form = $('#companyCreateForm')[0];

            var formdata = new FormData(form);
            $.ajax({
                url:'{{url('companies/store')}}',
                type:'post',
                enctype: 'multipart/form-data',
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
                        $("#company").load(window.location + " #company");
                        alert(data.success);
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
        function updateModal(id) {
            $('#myModal').modal('show');
            $.ajax({
               url:'{{url('companies/getCompany')}}',
               type:'post',
               data:{company_id:id,_token:'{{csrf_token()}}'},
                success:function (data) {
                   var img = '{{url('logo')}}/'+data.company.logo;
                    if(data.status){
                        $('#company_id').val(data.company.id);
                        $('#name').val(data.company.name);
                        $('#email').val(data.company.email);
                        $('.compnayLogo').html('<img src="'+img+'" class="compnayLogo" width="150px" height="100px">');
                        $('#website').val(data.company.website);
                    }
                }
            });
        }



        function updateCompany() {
            var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            var email = reg.test($('#email').val());
            if(email == false ){
                alert('Invalid Email Address');
                return false;
            }
            var form = $('#companyUpdateForm')[0];

            var formdata = new FormData(form);
            $.ajax({
                url:'{{url('companies/update')}}',
                type:'post',
                enctype: 'multipart/form-data',
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
                    if(data.success == false){
                        alert(data.message);
                    }
                  if(data.status){
                      $('#myModal').modal('hide');
                      $("#company").load(window.location + " #company");
                      alert(data.success);
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
               url:'{{url('companies/destroy')}}',
                type:'get',
                data:{company_id:id},
                success:function(e){

                    if(e.status){
                        alert('company deleted successfully');
                        $("#company").load(window.location + " #company");
                    }
                }
            });
        }



    </script>
@endsection


