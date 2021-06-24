<div class="row mb-6">
    <div>
    {!! Form::hidden('login_user_type', session('login_user_type'), ['class'=>'form-control']) !!}
    {!! Form::hidden('login_user_id', auth()->user()->id, ['class'=>'form-control']) !!}
    </div>
    <div class="col-sm-4">
        <div class="round_img">
            <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="{{$user->image['proxy_url'].'400/400'.$user->image['image_path']}}" />
        </div>
        <p class="text-muted text-center mt-2 mb-0">Upload Profile Picture</p>
    </div>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group" id="nameInputEdit">
                    {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
                    {!! Form::text('name', $user->name, ['class'=>'form-control', 'required' => 'required']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group" id="phoneInputEdit">
                    {!! Form::label('title', 'Phone Number',['class' => 'control-label']) !!}
                    {!! Form::text('phone_number', $user->phone_number, ['class'=>'form-control', 'required' => 'required']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group" id="descriptionInputEdit">
                    {!! Form::label('title', 'About Me',['class' => 'control-label']) !!}
                    {!! Form::textarea('description', $user->description, ['class'=>'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>