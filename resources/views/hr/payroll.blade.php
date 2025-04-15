@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.payroll_lang', [], session('locale')) }}</title>
    @endpush
     
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class=""><a href="javascript:void(0)">{{ trans('messages.dashboard_lang', [], session('locale')) }}/</a></li>
                <li class="active"><a href="javascript:void(0)">{{ trans('messages.payroll_lang', [], session('locale')) }}</a></li>
            </ol>
        </div>
        <div class="form-head d-flex mb-3 mb-md-4 align-items-start flex-wrap">
            <div class="me-auto mb-3 mb-md-0">
                <a href="javascript:void();" class="btn btn-primary btn-rounded add-payroll" data-bs-toggle="modal" data-bs-target="#add_payroll_modal">+ {{ trans('messages.add_payroll_lang',[],session('locale')) }}</a>
                <a href="javascript:void();" class="btn btn-warning btn-rounded payroll-data" data-bs-toggle="modal" data-bs-target="#payroll_data_modal"> {{ trans('messages.payroll_data_lang',[],session('locale')) }}</a>
                {{-- <a href="javascript:void();" class="btn btn-primary btn-rounded add-employee" data-bs-toggle="modal" data-bs-target="#add_employee_modal">+ Add employee</a> --}}
            </div>


        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_payroll" class="table table-striped  mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>{{ trans('messages.employee_name_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.basic_salary_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.transport_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.utilities_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.residence_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.gross_salary_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.no_of_pat_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.visa_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.moh_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.medical_insurance_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.annual_leaves_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.medical_bills_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.extra_income_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.bls_training_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.pasi_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.air_fare_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.other_salary_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.total_deductions_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.net_salary_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.remarks_lang',[],session('locale')) }}</th>
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


    <div class="modal fade" id="add_payroll_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">{{ trans('messages.add_payroll_lang',[],session('locale')) }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
              </button>
            </div>
            <div class="modal-body">
              <form class="add_payroll">
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
                            <label class="col-form-label">{{ trans('messages.payroll_type_lang',[],session('locale')) }}</label>
                            <select class="payroll_type form-control default-select wide mb-3" name="payroll_type">
                               <option value="1">{{ trans('messages.basic_salary_lang',[],session('locale')) }}</option>
                               <option value="2">{{ trans('messages.transport_lang',[],session('locale')) }}</option>
                               <option value="3">{{ trans('messages.utilities_lang',[],session('locale')) }}</option>
                               <option value="4">{{ trans('messages.residence_lang',[],session('locale')) }}</option>
                               <option value="5">{{ trans('messages.visa_lang',[],session('locale')) }}</option>
                               <option value="6">{{ trans('messages.moh_lang',[],session('locale')) }}</option>
                               <option value="7">{{ trans('messages.medical_insurance_lang',[],session('locale')) }}</option>
                               <option value="8">{{ trans('messages.medical_bills_lang',[],session('locale')) }}</option>
                               <option value="9">{{ trans('messages.extra_income_lang',[],session('locale')) }}</option>
                               <option value="10">{{ trans('messages.bls_training_lang',[],session('locale')) }}</option>
                               <option value="11">{{ trans('messages.pasi_lang',[],session('locale')) }}</option>
                               <option value="12">{{ trans('messages.air_fare_lang',[],session('locale')) }}</option>
                               <option value="13">{{ trans('messages.other_salary_lang',[],session('locale')) }}</option>
                              
                            </select>
                        </div>
                    </div>
  
                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">{{ trans('messages.amount_lang',[],session('locale')) }}</label>
                            <input type="text" class="form-control isnumber amount"   name="amount">
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">{{ trans('messages.date_lang',[],session('locale')) }}</label>
                            <input type="text" class="form-control datepicker pay_date" readonly value="<?php echo date('Y-m-d'); ?>"  name="pay_date">
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">{{ trans('messages.upload_file_lang',[],session('locale')) }}</label>
                            <input type="file" class="form-control payroll_image"   name="payroll_image">
                        </div>
                    </div>
                    <div class="row mt-3"><!-- Note Section -->
                        <div class="col-12 col-md-8">
                            <div class="form-group">
                                <label class="col-form-label">{{ trans('messages.remarks_lang',[],session('locale')) }}</label>
                                <textarea class="form-control notes" rows="4" name="notes"></textarea>
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


    <div class="modal fade" id="payroll_data_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">{{ trans('messages.payroll_lang',[],session('locale')) }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
              </button>
            </div>
            <div class="modal-body"> 
                <div class="row pt-4 table-responsive">
                    <table id="all_payroll_data" style="width:100%" class="table table-striped  mb-4 dataTablesCard fs-14">
                        <thead>
                            <tr>
                                <th>{{ trans('messages.employee_name_lang',[],session('locale')) }}</th>
                                <th>{{ trans('messages.payroll_type_lang',[],session('locale')) }}</th>
                                <th>{{ trans('messages.amount_lang',[],session('locale')) }}</th>
                                <th>{{ trans('messages.date_lang',[],session('locale')) }}</th>
                                <th>{{ trans('messages.remarks_lang',[],session('locale')) }}</th>
                                <th>{{ trans('messages.action_lang',[],session('locale')) }}</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
  
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ trans('messages.close_lang',[],session('locale')) }}</button>
                </div> 
            </div>
  
          </div>
        </div>
    </div>
   
</div>

@include('layouts.footer')
@endsection
