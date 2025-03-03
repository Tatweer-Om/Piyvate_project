@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.company_details_lang', [], session('locale')) }}</title>
    @endpush




    @include('layouts.footer')
    @endsection
