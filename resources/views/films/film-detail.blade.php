@extends('layouts.front')
@section('content')
<script src="{{url('js/jquery.min.js')}}"></script>
<div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel stats hyellow">
                    <div class="panel-body h-150 list">
                        <h1 class="text-success">Film Details</h1>
                        <div id="filmsList">
                            <a href="{{url('/dashboard')}}" class="btn btn-sm btn-success" style="margin-top:6px; float:right;" title="Save">Back</a>
                            @if(!empty($filmData))
                                <div class="row">
                                    <div class="col-md-3">
                                        <img src="{{ url('uploads/'.$filmData->photo)}}" width="300" height="300" alt="no-image" title="{{$filmData->name}}" style="cursor:pointer;">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>Name: {{$filmData->name}}</label>
                                            </div>
                                            <div class="col-md-12">
                                                <label>Description: {{$filmData->description}}</label>
                                            </div>
                                            <div class="col-md-12">
                                                <label>Release Date: {{date('d-m-Y',strtotime($filmData->release_date))}}</label>
                                            </div>
                                            <div class="col-md-12">
                                                <label>Rating:</label>
                                                <?php if(isset($filmData->rating) && !empty($filmData->rating)){
                                                $ratingImage = '';
                                                for($i=1;$i<=$filmData->rating;$i++){
                                                 $ratingImage .= '<i class="glyphicon glyphicon-star rating"></i>';
                                                } }
                                                echo $ratingImage;
                                                ?>

                                            </div>
                                            <div class="col-md-12">
                                                <label>Ticket Price: {{$filmData->ticket_price}}</label>
                                            </div>
                                        </div>
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
                        @if (Auth::check())
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
                           @endif
                           <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                        toastr.success(response.message);
                        currentElement.prop('disabled', false);
                        $("#comment").val('');
                    }else{
                        currentElement.prop('disabled', false);
                        toastr.error(response.message);
                    }
                }
            });
        } 
    </script>
@endsection