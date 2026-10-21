@extends('layouts.admin')

@section('title', 'Create Facility')
@section('header', 'New Facility')

@section('content')
@php
    // Per-visit draft token so photos can be saved BEFORE the facility exists,
    // then claimed when the form is submitted.
    $draftToken = old('draft_token', \Illuminate\Support\Str::uuid()->toString());
@endphp

<form method="POST" action="{{ route('admin.facilities.store') }}" id="facilityForm"
      enctype="multipart/form-data"
      data-mode="create"
      data-draft-token="{{ $draftToken }}"
      data-upload-url="{{ route('admin.facilities.uploadImage') }}"
      data-reorder-url="{{ route('admin.facilities.reorderImages') }}">
    @csrf
    <input type="hidden" name="draft_token" value="{{ $draftToken }}">

    @include('admin.facilities.partials.form', [
        'facility'    => null,
        'categories'  => $categories,
        'tags'        => $tags,
        'submitLabel' => 'Create Facility',
        'submitIcon'  => 'bi-building-add',
    ])
</form>

@include('admin.facilities.partials.form-assets')
@endsection
