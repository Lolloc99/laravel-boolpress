@extends('layouts.dashboard')

@section('content')
    <h2>Ciao {{$user->name}}. Questa è la pagina home di backoffice!</h2>
    {{-- @if ($userInfo)
        
    @endif --}}
    <p>Indirizzo: {{ $userInfo->address }} </p>
    <p>Cellulare: {{ $userInfo->phone }} </p>
@endsection