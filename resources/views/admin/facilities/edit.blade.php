@extends('layouts.admin')

@section('title', 'Edit Facility')
@section('header', 'Edit Facility')

@section('content')
<form method="POST" action="{{ route('admin.facilities.update', $facility->id) }}" id="facilityForm"
      enctype="multipart/form-data"
      data-mode="edit"
      data-upload-url="{{ route('admin.facilities.uploadImage') }}"
      data-reorder-url="{{ route('admin.facilities.reorderImages') }}">
    @csrf
    @method('PUT')

    @include('admin.facilities.partials.form', [
        'facility'    => $facility,
        'categories'  => $categories,
        'tags'        => $tags,
        'submitLabel' => 'Save Changes',
        'submitIcon'  => 'bi-floppy',
    ])
</form>

@include('admin.facilities.partials.form-assets')
@endsection
