@extends('layouts.app')

@section('content')

<!-- Main Wrapper -->
    <div class="normalheader transition animated fadeIn small-header">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    Film Details
                </h2>
            </div>
        </div>
    </div>
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12" style="">
                <div class="hpanel">
                    <div class="panel-body">
                        @if(!empty($filmData))
                            <div>
                                <img src="{{ url('uploads/'.$filmData->photo)}}" width="300" height="300" alt="no-image" title="{{$filmData->name}}" style="cursor:pointer;">
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>Name: {{$filmData->name}}</label>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Description: {{$filmData->description}}</label>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Release Date: {{date('d-m-Y',strtotime($filmData->release_date))}}</label>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Rating: {{$filmData->rating}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>Ticket Price: {{$filmData->ticket_price}}</label>
                                </div>
                            </div>     
                        @endif
                        <div id="commentSection">
                            @if(!empty($filmComments))
                                @foreach($filmComments as $value)
                                    <div class="row">
                                        <div class="form-group col-md-3 col-sm-6">
                                            <label>{{$value->comments}}</label>
                                            <p>{{$value->name}}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <form method="POST" action="javascript:void(0);" id="frm_comment_save">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-md-6 m-n">
                                    <label>Comments:<span class="asterisk">*</span></label>
                                    <input id="comment" type="text" class="form-control" name="comment" value="" required  data-parsley-maxlength="250" maxlength="250" data-parsley-required-message="{{ $validationMessages['comment.required'] }}" data-parsley-maxlength-message="{{ $validationMessages['comment.max'] }}">
                                    <input type="hidden" id="film_id" name="film_id" value="{{ !empty($filmData) ? $filmData->id : '' }}">
                                </div>
                                <div class="form-group col-md-6 m-n">
                                    <br>
                                    <button type="submit" class="btn btn-sm btn-success" id="save" style="margin-top: 6px;" title="Save">Save</button>
                                </div>
                            </div>
                           </form>
                           <br>  
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            /* Add group form validation */
            var frm_comment_save = $("#frm_comment_save").parsley();

            $('#save').click(function() {
                if(frm_comment_save.isValid()){
                    var thisElement  = $(this);
                    thisElement.prop('disabled', true);
                    saveData(thisElement); 
                }
            });
        });
        /* Save comment to the database using ajax */
        function saveData(currentElement){
            $('.splash').show();
            $.ajax({
                url:"{{url('films/save-comment')}}",
                type:"POST",
                data:$("#frm_comment_save").serialize(),
                dataType:'json',
                success:function(response){
                    $('.splash').hide();
                    if(response.status){
                        $('#commentSection').append(response.result);
                        $('#comment').value(' ');
                        toastr.success(response.message);
                        currentElement.prop('disabled', false);
                    }else{
                        currentElement.prop('disabled', false);
                        toastr.error(response.message);
                    }
                }
            });
        } 
    </script>
@endsection