@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-sm-8 offset-sm-2">
                <h1 class="display-3">Update your profile</h1>
                <form method="post" action="{{ route('user.update', $user->id) }}" enctype="multipart/form-data">
                    @method('PATCH')
                    @csrf
                    <div class="form-group">
                        <label for="first_name">Name:</label>
                        <input required type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name') ?? $user->name }}" />
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input required type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') ?? $user->email }}" />
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-group">
                            <label for="file">Avatar</label>
                            <input type="file" class="form-control-file" id="file" name="file">
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('home') }}" type="submit" class="btn btn-secondary">Back to Chatbox</a>
                </form>
            </div>
        </div>
    </div>
@endsection
