@extends('layouts.admin')

@section('title', 'User Management')
@section('header', 'User Management')

@section('content')
    @include('shared.user-management', ['panel' => 'admin'])
@endsection
