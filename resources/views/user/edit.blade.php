@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-8 offset-sm-2">
            <h1 class="display-3">Update your profile</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <br />
            @endif
            <form method="post" action="{{ route('user.update', $user->id) }}" enctype="multipart/form-data">
                @method('PATCH')
                @csrf
                <div class="form-group">

                    <label for="first_name">Name:</label>
                    <input type="text" class="form-control" name="name" value="{{ $user->name }}" />
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" class="form-control" name="email" value="{{ $user->email }}" />
                </div>

                <div class="form-group">
                    <div class="form-group">
                        <label for="file">Avatar</label>
                        <input type="file" class="form-control-file" id="file" name="file">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{route('home')}}" type="submit" class="btn btn-secondary">Back to Chatbox</a>
            </form>
        </div>
    </div>
@endsection
