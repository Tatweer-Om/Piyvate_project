@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.doctor_profile_lang', [], session('locale')) }}</title>
    @endpush


    <div class="content-body">

        <button class="btn btn-primary mt-3 ms-3" data-bs-toggle="offcanvas" data-bs-target="#rightPopup">
            Open Right-Side Popup
        </button>
        <!-- row -->
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class=" active"><a href="javascript:void(0)"> /Doctor Details</a></li>
                </ol>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="profile card card-body px-3 pt-3 text-center">
                        <div class="profile-head d-flex flex-column align-items-center">
                            <div class="profile-photo mb-3">
                                <img src="{{ asset($doctor->doctor_image ? 'images/doctor_images/' . $doctor->doctor_image : 'images/dummy_images/cover-image-icon.png') }}"
                                    class="img-fluid rounded-circle" alt=""
                                    style="width: 100px; height: 100px;">
                            </div>
                            <div class="profile-details">
                                <div class="profile-name">
                                    <h4 class="text-primary mb-0">{{ $doctor->doctor_name ?? 'N/A' }}</h4>
                                    <p class="text-muted small">{{ $special ?? 'N/A' }}</p>
                                </div>
                                <div class="profile-email">
                                    <h5 class="text-muted mb-0">{{ $branch ?? 'N/A' }}</h5>
                                    <p class="small">{{ $doctor->email ?? '' }}</p>
                                </div>
                            </div>
                            <ul class="list-group w-100 text-start mt-3">
                                <li class="list-group-item d-flex justify-content-between align-items-center small">
                                    <span><i class="fas fa-calendar-check text-primary"></i> Appointments</span>
                                    <span class="badge bg-primary rounded-pill">{{ $total_apt ?? '0' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center small">
                                    <span><i class="fas fa-clock text-success"></i> Sessions</span>
                                    <span class="badge bg-success rounded-pill">{{ $total_sessions ?? '0' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center small">
                                    <span><i class="fas fa-user text-danger"></i> Patients</span>
                                    <span class="badge bg-danger rounded-pill">{{ $total_patient ?? '0' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header border-0 pb-0">
                            <h4 class="fs-16 font-w600 mb-0">Today's Appointments</h4>
                        </div>
                        <div class="card-body px-0 pt-3">

                            <div id="DZ_W_Todo2" class="widget-media dz-scroll px-3" style="max-height: 300px; overflow-y: auto;">
                            </div>
                        </div>
                    </div>
                </div>

            </div>


<div class="row">

    <div class="col-lg-6">
        <div class="card h-auto">
            <div class="card-body">
                <div class="profile-tab">
                    <div class="custom-tab-1">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a href="#about-me" data-bs-toggle="tab"
                                    class="nav-link active show">About Me</a>
                            </li>
                            {{-- <li class="nav-item"><a href="#about-me" data-bs-toggle="tab" class="nav-link active show">About
                                    Me</a>
                            </li> --}}
                            <li class="nav-item"><a href="#profile-settings" data-bs-toggle="tab"
                                    class="nav-link">Setting</a>
                            </li>
                        </ul>
                        <div class="tab-content">

                            <div id="about-me" class="tab-pane fade">
                                <div class="profile-about-me">
                                    <div class="pt-4 border-bottom-1 pb-3">
                                        <h4 class="text-primary">About Me</h4>
                                        <p class="mb-2">A wonderful serenity has taken possession of my entire
                                            soul, like these sweet mornings of spring which I enjoy with my whole
                                            heart. I am alone, and feel the charm of existence was created for the
                                            bliss of souls like mine.I am so happy, my dear friend, so absorbed in
                                            the exquisite sense of mere tranquil existence, that I neglect my
                                            talents.</p>
                                        <p>A collection of textile samples lay spread out on the table - Samsa was a
                                            travelling salesman - and above it there hung a picture that he had
                                            recently cut out of an illustrated magazine and housed in a nice, gilded
                                            frame.</p>
                                    </div>
                                </div>

                                <div class="profile-lang  mb-5">
                                    <h4 class="text-primary mb-2">Language</h4>
                                    <a href="javascript:void(0);" class="text-muted pe-3 f-s-16"><i
                                            class="flag-icon flag-icon-us"></i> English</a>
                                    <a href="javascript:void(0);" class="text-muted pe-3 f-s-16"><i
                                            class="flag-icon flag-icon-fr"></i> French</a>
                                    <a href="javascript:void(0);" class="text-muted pe-3 f-s-16"><i
                                            class="flag-icon flag-icon-bd"></i> Bangla</a>
                                </div>

                            </div>
                            <div id="profile-settings" class="tab-pane fade">
                                <div class="pt-3">
                                    <div class="settings-form">
                                        <h4 class="text-primary">Account Setting</h4>
                                        <form>
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" placeholder="Email"
                                                        class="form-control">
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Password</label>
                                                    <input type="password" placeholder="Password"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Address</label>
                                                <input type="text" placeholder="1234 Main St"
                                                    class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Address 2</label>
                                                <input type="text" placeholder="Apartment, studio, or floor"
                                                    class="form-control">
                                            </div>
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">City</label>
                                                    <input type="text" class="form-control">
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">State</label>
                                                    <select class="form-control default-select wide"
                                                        id="inputState">
                                                        <option selected="">Choose...</option>
                                                        <option>Option 1</option>
                                                        <option>Option 2</option>
                                                        <option>Option 3</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-2">
                                                    <label class="form-label">Zip</label>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check custom-checkbox">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="gridCheck">
                                                    <label class="form-check-label form-label" for="gridCheck">
                                                        Check me out</label>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary" type="submit">Sign
                                                in</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="replyModal">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Post Reply</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <textarea class="form-control" rows="4">Message</textarea>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Reply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">All Patients</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="all_patient_doctor" class="display" style="min-width: 845px">
                                    <thead>
                                        <tr>

                                            <th>Appt No.</th>
                                            <th>Patient Name</th>
                                            <th>Appointment Date</th>
                                            <th>Status</th>

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



    <div class="offcanvas offcanvas-end" tabindex="-1" id="rightPopup">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Right-Side Popup</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <p>This is a Bootstrap popup that slides in from the right.</p>
            <button class="btn btn-secondary" data-bs-dismiss="offcanvas">Close</button>
        </div>
    </div>

    @include('layouts.footer')
@endsection
