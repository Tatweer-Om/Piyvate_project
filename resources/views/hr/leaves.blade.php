@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.leave_lang', [], session('locale')) }}</title>
    @endpush

<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class=""><a href="javascript:void(0)">{{ trans('messages.dashboard_lang', [], session('locale')) }}/</a></li>
                <li class="active"><a href="javascript:void(0)">{{ trans('messages.leave_lang', [], session('locale')) }}</a></li>
            </ol>
        </div>
        <div class="form-head d-flex mb-3 mb-md-4 align-items-start flex-wrap">
            <div class="me-auto mb-3 mb-md-0">
                <a href="javascript:void();" class="btn btn-primary btn-rounded add-leave" data-bs-toggle="modal" data-bs-target="#add_leaves_modal">+ {{ trans('messages.add_leave_lang',[],session('locale')) }}</a>
            </div>


        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_leaves" class="table table-striped  mb-4 dataTablesCard fs-14">
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


    <div class="modal fade" id="add_leaves_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">{{ trans('messages.add_leave_lang',[],session('locale')) }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
              </button>
            </div>
            <div class="modal-body">
              <form class="add_leaves">
                  @csrf

                  <div class="row">
                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">{{ trans('messages.employee_name_lang',[],session('locale')) }}</label>
                            <select class="employee_id form-control default-select wide mb-3" id="employee_id" name="employee_id">
                                <option value="">{{ trans('messages.choose_lang',[],session('locale')) }}</option>
                                @foreach($staff as $stf)
                                    <option value="{{ $stf->id }}-1">{{ $stf->employee_name }}</option>
                                @endforeach
                                @foreach($doctor as $doc)
                                    <option value="{{ $doc->id }}-2">{{ $doc->doctor_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">{{ trans('messages.leaves_type_lang',[],session('locale')) }}</label>
                            <select class="leaves_type form-control default-select wide mb-3" name="leaves_type" id="leaves_type">
                               <option value="1">{{ trans('messages.annual_leaves_lang',[],session('locale')) }}</option>
                               {{-- <option value="2">{{ trans('messages.emergency_leaves_lang',[],session('locale')) }}</option> --}}
                               <option value="3">{{ trans('messages.sick_leaves_and_emergency_lang',[],session('locale')) }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">{{ trans('messages.remaining_leaves_lang',[],session('locale')) }}</label>
                            <input type="text"readonly class="form-control isnumber remaining_leaves"   name="remaining_leaves">
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">{{ trans('messages.total_leaves_lang',[],session('locale')) }}</label>
                            <input type="text" class="form-control isnumber total_leaves"   name="total_leaves">
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">{{ trans('messages.from_date_lang',[],session('locale')) }}</label>
                            <input type="text" class="form-control datepicker from_date" readonly value="<?php echo date('Y-m-d'); ?>"  name="from_date">
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">{{ trans('messages.upload_file_lang',[],session('locale')) }}</label>
                            <input type="file" class="form-control leave_image"   name="leave_image">
                        </div>
                    </div>
                    <div class="row mt-3"><!-- Note Section -->
                        <div class="col-12 col-md-8">
                            <div class="form-group">
                                <label class="col-form-label">{{ trans('messages.reason_lang',[],session('locale')) }}</label>
                                <textarea class="form-control reason" rows="4" name="reason"></textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row table-responsive">
                        <table id="all_leaves_data" class="table table-striped  mb-4 dataTablesCard fs-14">
                            <thead>
                                <tr>
                                    <th>{{ trans('messages.leaves_type_lang',[],session('locale')) }}</th>
                                    <th>{{ trans('messages.status_lang',[],session('locale')) }}</th>
                                    <th>{{ trans('messages.total_leaves_lang',[],session('locale')) }}</th>
                                    <th>{{ trans('messages.from_date_lang',[],session('locale')) }}</th>
                                    <th>{{ trans('messages.to_date_lang',[],session('locale')) }}</th>
                                    <th>{{ trans('messages.reason_lang',[],session('locale')) }}</th>

                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
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
