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
                            <h4>Edit films</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-user fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h1 class="text-success">Films</h1>
                        </div>
                        <form method="POST" action="{{ url('/admin/save') }}" name="edit_film" id="edit_film" enctype="multipart/form-data">
                            <input type="hidden" name="update_film" id="update_film" value="<?php if(isset($filmsData->id) && !empty($filmsData->id)){ echo $filmsData->id; } ?>">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Film Name:<span class="asterisk">*</span></label>
                                    <input type="text" class="form-control" name="film_name" id="film_name" value="<?php if(isset($filmsData->name) && !empty($filmsData->name)){ echo $filmsData->name; } ?>" autocomplete="off">
                                    @if ($errors->has('film_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('film_name') }}</strong>
                                        </span>
                                    @endif
                                </div>



                                <div class="form-group col-md-6">
                                    <label>Release Date:<span class="asterisk">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="release_date" id="release_date" autocomplete="off" value="<?php if(isset($filmsData->release_date) && !empty($filmsData->release_date)){ echo date("d-m-Y", strtotime($filmsData->release_date)); } ?>" required>
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
                                    <select name="rating" class="form-control" id="rating">
                                        <option value="">Select Rating</option>
                                        <option value="1" <?php if(isset($filmsData->rating)){ if($filmsData->rating == "1"){ echo "selected='selected'";} }?>>1</option>
                                        <option value="2" <?php if(isset($filmsData->rating)){ if($filmsData->rating == "2"){ echo "selected='selected'";} }?>>2</option>
                                        <option value="3" <?php if(isset($filmsData->rating)){ if($filmsData->rating == "3"){ echo "selected='selected'";} }?>>3</option>
                                        <option value="4" <?php if(isset($filmsData->rating)){ if($filmsData->rating == "4"){ echo "selected='selected'";} }?>>4</option>
                                        <option value="5" <?php if(isset($filmsData->rating)){ if($filmsData->rating == "5"){ echo "selected='selected'";} }?>>5</option>
                                    </select>
                                    @if ($errors->has('rating'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('rating') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Ticket Price:<span class="asterisk">*</span></label>
                                    <input type="text" class="form-control" name="price" id="price" value="<?php if(isset($filmsData->ticket_price) && !empty($filmsData->ticket_price)){ echo $filmsData->ticket_price; } ?>" autocomplete="off">
                                    @if ($errors->has('price'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('price') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="clearfix"></div>

                                <div class="form-group col-md-6">
                                    <label>Description:<span class="asterisk">*</span></label>
                                    <textarea name="desc" class="form-control" id="desc"><?php if(isset($filmsData->description) && !empty($filmsData->description)){ echo $filmsData->description; } ?></textarea>
                                    @if ($errors->has('desc'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('desc') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Country:<span class="asterisk">*</span></label>
                                    <select name="country" class="form-control" id="country">
                                        <option value="">Select country</option>
                                        <option value="india" <?php if(isset($filmsData->country)){ if($filmsData->country == "india"){ echo "selected='selected'";} }?>>India</option>
                                        <option value="us" <?php if(isset($filmsData->country)){ if($filmsData->country == "us"){ echo "selected='selected'";} }?>>US</option>
                                    </select>
                                    @if ($errors->has('country'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group col-md-6">
                                    <?php $genereVal = explode(',',$filmsData->genre); ?>
                                    <label>Genre:<span class="asterisk">*</span></label>
                                    Action <input type="checkbox" name="genre[]" value="action" <?php if(in_array('action',$genereVal)){ echo "checked='checked'";}?>>
                                    Comedy <input type="checkbox" name="genre[]" value="comedy" <?php if(in_array('comedy',$genereVal)){ echo "checked='checked'";}?>>
                                    Horror <input type="checkbox" name="genre[]" value="horror" <?php if(in_array('horror',$genereVal)){ echo "checked='checked'";}?>>
                                    @if ($errors->has('genre'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('genre') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Photo:<span class="asterisk">*</span></label>
                                    <input type="file" class="form-control" name="photo" id="photo" value="<?php if(isset($filmsData->photo) && !empty($filmsData->photo)){ echo $filmsData->photo; } ?>"><span><img width="100" height="100" src="{{ url('uploads/'.$filmsData->photo) }}"/></span>
                                    @if ($errors->has('photo'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('photo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group col-md-12 text-right">
                                    <button type="submit" class="btn btn-success">Update Film</button>
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
