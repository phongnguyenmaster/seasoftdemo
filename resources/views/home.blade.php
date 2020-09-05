@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <input id="appUrl" value="{{ env('APP_URL') }}" hidden>
        <input id="socketUrl" value="{{ env('SOCKET_URL') }}" hidden>
        <input id="userLoginId" value="{{ Auth::user()->id }}" hidden>
        <div id="app">
            <chat-layout></chat-layout>
        </div>
    </div>
@endsection
