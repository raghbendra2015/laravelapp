@extends('layouts.app')
@section('content')
    <script src="{{url('js/jquery.min.js')}}"></script>
    <link rel="stylesheet" href="{{url('css/owl.carousel.min.css')}}">
    <div class="content animate-panel">

        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel stats hyellow">
                    <div class="panel-body h-150 list">
                        <div class="">
                            <h1 class="text-success">Film Listing</h1>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right" style="margin-bottom: 10px;"> <a href="{{url('/admin/add')}}" class="btn btn-success">Add Film</a></div>
                        </div>
                        <table id="film_table" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Release Date</th>
                                    <th>Rating</th>
                                    <th>Ticket Price</th>
                                    <th>Photo</th>
                                    <th>Country</th>
                                    <th>Genre</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                             </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{url('js/owl.carousel.min.js')}}"></script>
    <script type="text/javascript">
        var film_table = '';
        $(document).ready(function(){
            /* Datatable for film table */
            film_table = $('#film_table').DataTable({
                order: [ [0, 'desc'] ],
                processing: true,
                serverSide: true,
                ajax: "{{ url('admin/film-list') }}",
                columns: [
                    {data: 'id', name: 'id', visible:false},
                    {data: 'name', name: 'name'},
                    {data: 'description', name: 'description'},
                    {data: 'release_date', name: 'release_date'},
                    {data: 'rating', name: 'rating'},
                    {data: 'ticket_price', name: 'ticket_price'},
                    {data: 'photo', name: 'photo'},
                    {data: 'country', name: 'country'},
                    {data: 'genre', name: 'genre'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
            });

            // ajax call for loading films
            $.ajax({
                url: "{{ url('/api/v1/films') }}",
                type: "POST",
                success:function(response){

                    var tableData ='';
                    tableData += '<div class="">';
                    var arr = $.map(response.data, function(result) {
                        var ratingImage ='';
                        for(var i=1;i<=result.rating;i++){
                            ratingImage += '<i class="glyphicon glyphicon-star rating"></i>';
                        }
                        tableData += '<div class="item col-lg-4"><a href="{{url('/films')}}/'+result.slug+'" class="thumbnail"><img src="/uploads/'+result.photo+'" /></a><div class="caption"><p>Name: '+result.name+'</p><p>Release Date: '+result.release_date+'</p><p>Rating: '+ratingImage+'</p></div></div>';
                    });
                        tableData += '</div>';
                        $("#filmsList").html(tableData);
                }
            });

            $("body").delegate(".delete_group", "click", function(){
                var filmId = $(this).attr('cstm');
                var cnfrm = confirm("Are you sure you want to delete ?");
                if(cnfrm){
                    $.ajax({
                        url: "{{ url('/admin/delete') }}",
                        type: "POST",
                        data: { "filmId": filmId, "_token": "{{ csrf_token() }}"},
                        success:function(response){
                            if(response.status = true){
                                toastr.success(response.message);
                                film_table.draw();
                            }else{
                                toastr.error(response.message);
                            }
                        }
                    });
                }

            });

        });
    </script>
@endsection
