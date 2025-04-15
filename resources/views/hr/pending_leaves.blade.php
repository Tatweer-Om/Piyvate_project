@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.pending_leave_lang', [], session('locale')) }}</title>
    @endpush
     
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class=""><a href="javascript:void(0)">{{ trans('messages.dashboard_lang', [], session('locale')) }}/</a></li>
                <li class="active"><a href="javascript:void(0)">{{ trans('messages.pending_leave_lang', [], session('locale')) }}</a></li>
            </ol>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_pending_leaves" class="table table-striped  mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>{{ trans('messages.employee_name_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.leaves_type_lang',[],session('locale')) }}</th>
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


    <div class="modal fade" id="add_leave_response_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">{{ trans('messages.add_response_lang',[],session('locale')) }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
              </button>
            </div>
            <div class="modal-body">
              <form class="add_leave_response">
                  @csrf
                   
                  <div class="row">
                    <input type="hidden" class="response_type" name="response_type">
                    <input type="hidden" class="leave_id" name="leave_id">
                    <div class="row mt-3"><!-- Note Section -->
                        <div class="col-12 col-md-8">
                            <div class="form-group">
                                <label class="col-form-label">{{ trans('messages.reason_lang',[],session('locale')) }}</label>
                                <textarea class="form-control reason" rows="4" name="reason"></textarea>
                            </div>
                        </div>
                    </div>
                     

                  </div>
  
                  <div class="modal-footer">
                      <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ trans('messages.close_lang',[],session('locale')) }}</button>
                      <button type="submit" class="btn btn-primary">{{ trans('messages.add_data_lang',[],session('locale')) }}</button>
                    </div>
              </form>
            </div>
  
          </div>
        </div>
    </div>


    
   
</div>

@include('layouts.footer')
@endsection
