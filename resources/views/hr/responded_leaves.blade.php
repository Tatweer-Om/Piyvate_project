@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.responded_leaves_lang', [], session('locale')) }}</title>
    @endpush
     
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class=""><a href="javascript:void(0)">{{ trans('messages.dashboard_lang', [], session('locale')) }}/</a></li>
                <li class="active"><a href="javascript:void(0)">{{ trans('messages.responded_leaves_lang', [], session('locale')) }}</a></li>
            </ol>
        </div>
        <div class="form-head d-flex mb-3 mb-md-4 align-items-start flex-wrap">
            <div class="me-auto mb-3 mb-md-0">
                <div class="form-group">
                    <label class="col-form-label">{{ trans('messages.status_lang',[],session('locale')) }}</label>
                    <select class="status form-control default-select wide mb-3" onchange="get_responded_leaves()" name="status" id="status">
                       <option value="">{{ trans('messages.choose_lang',[],session('locale')) }}</option>
                       <option value="2">{{ trans('messages.accepted_lang',[],session('locale')) }}</option>
                       <option value="3">{{ trans('messages.rejected_lang',[],session('locale')) }}</option> 
                    </select>
                </div>
            </div>


        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_responded_leaves" class="table table-striped  mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>{{ trans('messages.employee_name_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.leaves_type_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.status_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.total_leaves_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.from_date_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.to_date_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.reason_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.action_lang',[],session('locale')) }}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

 


    
   
</div>

@include('layouts.footer')
@endsection
