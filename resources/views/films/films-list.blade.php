@extends('layouts.front')
@section('content')
    <script src="{{url('js/jquery.min.js')}}"></script>
    <link rel="stylesheet" href="{{url('css/owl.carousel.min.css')}}">
    <div class="content animate-panel">

        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel stats hyellow">
                    <div class="panel-body h-150 list">
                        <h1 class="text-success">Films</h1>
                        <div id="filmsList"></div>
                    </div>
                    <br>
                        <div>
                            <button id="load" class="btn btn-success">Load More</button>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{url('js/owl.carousel.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            showFilms();
            $('#load').click(function(){
                showFilms();
            });
        });

        /* Function to load film */
        function showFilms(){
            var offset = $('.item').length;
            $('.splash').show();
            // ajax call for loading films
            $.ajax({
                url: "{{ url('/api/v1/films') }}",
                type: "POST",
                data:{'offset':offset},
                success:function(response){
                    $('.splash').hide();
                    var tableData ='';
                    tableData += '<div class="">';
                    var arr = $.map(response.data, function(result) {
                        var ratingImage ='';
                        for(var i=1;i<=result.rating;i++){
                            ratingImage += '<i class="glyphicon glyphicon-star rating"></i>';
                        }
                        var url = "{{ url('uploads') }}/"+result.photo;
                        tableData += '<div class="item col-md-3"><a href="{{url('/films')}}/'+result.slug+'" class="thumbnail"><img style="height:225px; width:100%;" src="'+url+'" /></a><div class="caption"><p>Name: '+result.name+'</p><p>Release Date: '+result.release_date+'</p><p>Rating: '+ratingImage+'</p></div></div>';
                    });
                        tableData += '</div>';
                        $("#filmsList").append(tableData);
                }
            });
        }
    </script>
@endsection
