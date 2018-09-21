@extends('layouts.app')
@section('content')
    <script src="{{url('js/jquery.min.js')}}"></script>
    <link rel="stylesheet" href="{{url('css/jquery-ui.css')}}">
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel stats hyellow">
                    <div class="panel-body h-150 list">
                        <div class="stats-title pull-left">
                            <h4>Add films</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-user fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h1 class="text-success">Films</h1>
                        </div>
                        <form method="POST" action="{{ url('/admin/save') }}" name="add_film" id="add_film" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Film Name:<span class="asterisk">*</span></label>
                                    <input type="text" class="form-control" name="film_name" id="film_name" value="" autocomplete="off" required>
                                    @if ($errors->has('film_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('film_name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                 <div class="form-group col-md-6">
                                     <label>Release Date:<span class="asterisk">*</span></label>
                                     <div class="input-group">
                                         <input type="text" class="form-control" name="release_date" id="release_date" autocomplete="off" value="" required>
                                         <span class="input-group-addon" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                     </div>
                                    @if ($errors->has('release_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('release_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group col-md-6">
                                    <label>Rating:<span class="asterisk">*</span></label>
                                    <select class="form-control" name="rating" id="rating" required>
                                        <option value="">Select Rating</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                    @if ($errors->has('rating'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('rating') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Ticket Price:<span class="asterisk">*</span></label>
                                    <input type="text" class="form-control" name="price" id="price" value="" autocomplete="off" required>
                                    @if ($errors->has('price'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('price') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group col-md-6">
                                    <label>Description:<span class="asterisk">*</span></label>
                                    <textarea name="desc" class="form-control" id="desc" required></textarea>
                                    @if ($errors->has('desc'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('desc') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Country:<span class="asterisk">*</span></label>
                                    <select class="form-control" name="country" id="country" required>
                                        <option value="">Select country</option>
                                        <option value="india">India</option>
                                        <option value="us">US</option>
                                    </select>
                                    @if ($errors->has('country'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group col-md-6">
                                    <label>Genre:<span class="asterisk">*</span></label>
                                    Action <input type="checkbox" name="genre[]" value="action" required>
                                    Comedy <input type="checkbox" name="genre[]" value="comedy">
                                    Horror <input type="checkbox" name="genre[]" value="horror">
                                    @if ($errors->has('genre'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('genre') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Photo:<span class="asterisk">*</span></label>
                                    <input type="file" class="form-control" name="photo" id="photo" value="" required>
                                    @if ($errors->has('photo'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('photo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group col-md-12 text-right">
                                    <button type="submit" class="btn btn-success">Add Film</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
            $( function() {
                $( "#release_date" ).datepicker({
                    //maxDate: 0,
                    dateFormat: "dd-mm-yy"
                });
            });
    </script>
@endsection
