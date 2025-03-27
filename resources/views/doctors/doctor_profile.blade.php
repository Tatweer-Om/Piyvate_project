@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.doctor_profile_lang', [], session('locale')) }}</title>
    @endpush


    <div class="content-body">

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
                    <div class="profile card card-body px-3 pt-3 text-center "> <!-- Added text-center -->
                        <div class="profile-head d-flex flex-column align-items-center"> <!-- Center alignment -->
                            <div class="profile-photo mb-3">
                                <img src="images/profile/profile.png" class="img-fluid rounded-circle" alt="" style="width: 100px; height: 100px;">
                            </div>
                            <div class="profile-details">
                                <div class="profile-name">
                                    <h4 class="text-primary mb-0">Mitchell C. Shay</h4>
                                    <p>UX / UI Designer</p>
                                </div>
                                <div class="profile-email">
                                    <h4 class="text-muted mb-0">info@example.com</h4>
                                    <p>Email</p>
                                </div>
                                <div class="dropdown mt-2">
                                    <a href="javascript:void(0);" class="btn btn-primary light sharp" data-bs-toggle="dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 24 24">
                                            <g fill="none">
                                                <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                                                <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                                                <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                                            </g>
                                        </svg>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="dropdown-item"><a href="#"><i class="fa fa-user-circle text-primary me-2"></i>View profile</a></li>
                                        <li class="dropdown-item"><a href="#"><i class="fa fa-users text-primary me-2"></i>Add to close friends</a></li>
                                        <li class="dropdown-item"><a href="#"><i class="fa fa-plus text-primary me-2"></i>Add to group</a></li>
                                        <li class="dropdown-item"><a href="#"><i class="fa fa-ban text-primary me-2"></i> Block</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">

                    <div class="card">
                        <div class="card-body">
                            <div class="profile-statistics">
                                <div class="text-center">
                                    <div class="row">
                                        <div class="col">
                                            <h3 class="m-b-0">150</h3><span>Follower</span>
                                        </div>
                                        <div class="col">
                                            <h3 class="m-b-0">140</h3><span>Place Stay</span>
                                        </div>
                                        <div class="col">
                                            <h3 class="m-b-0">45</h3><span>Reviews</span>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <a href="javascript:void(0);" class="btn btn-primary mb-1 me-1">Follow</a>
                                        <a href="javascript:void(0);" class="btn btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#sendMessageModal">Send Message</a>
                                    </div>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="sendMessageModal">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Send Message</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="comment-form">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="text-black font-w600 form-label">Name <span class="required">*</span></label>
                                                                <input type="text" class="form-control" value="Author" name="Author" placeholder="Author">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="text-black font-w600 form-label">Email <span class="required">*</span></label>
                                                                <input type="text" class="form-control" value="Email" placeholder="Email" name="Email">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="mb-3">
                                                                <label class="text-black font-w600 form-label">Comment</label>
                                                                <textarea rows="8" class="form-control" name="comment" placeholder="Comment"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="mb-3 mb-0">
                                                                <input type="submit" value="Post Comment" class="submit btn btn-primary" name="submit">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
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
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0 pb-0">
                            <h4 class="fs-16 font-w600 mb-0">Appointment Schedule</h4>
                        </div>
                        <div class="card-body px-0 pt-3">
                            <div id="DZ_W_Todo2" class="widget-media dz-scroll px-3" style="max-height: 300px; overflow-y: auto;">
                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="timeline-panel bgl-dark border-0 p-2 rounded text-center m-1" style="width: 32%;">
                                        <p class="mb-0 fs-14 text-dark">Cive Slauw</p>
                                        <small class="text-muted d-block fs-12">Sat, 23/08/2020<br>08:00 - 09:30 AM</small>
                                    </div>
                                    <div class="timeline-panel bgl-dark border-0 p-2 rounded text-center m-1" style="width: 32%;">
                                        <p class="mb-0 fs-14 text-dark">John Doe</p>
                                        <small class="text-muted d-block fs-12">Mon, 25/08/2020<br>10:00 - 11:00 AM</small>
                                    </div>
                                    <div class="timeline-panel bgl-dark border-0 p-2 rounded text-center m-1" style="width: 32%;">
                                        <p class="mb-0 fs-14 text-dark">Sarah Lee</p>
                                        <small class="text-muted d-block fs-12">Wed, 27/08/2020<br>02:00 - 03:30 PM</small>
                                    </div>
                                    <div class="timeline-panel bgl-dark border-0 p-2 rounded text-center m-1" style="width: 32%;">
                                        <p class="mb-0 fs-14 text-dark">Mark Henry</p>
                                        <small class="text-muted d-block fs-12">Fri, 28/08/2020<br>11:00 - 12:30 PM</small>
                                    </div>
                                    <div class="timeline-panel bgl-dark border-0 p-2 rounded text-center m-1" style="width: 32%;">
                                        <p class="mb-0 fs-14 text-dark">Emma Watson</p>
                                        <small class="text-muted d-block fs-12">Sun, 30/08/2020<br>03:00 - 04:30 PM</small>
                                    </div>
                                    <div class="timeline-panel bgl-dark border-0 p-2 rounded text-center m-1" style="width: 32%;">
                                        <p class="mb-0 fs-14 text-dark">Chris Brown</p>
                                        <small class="text-muted d-block fs-12">Tue, 01/09/2020<br>09:00 - 10:30 AM</small>
                                    </div>
                                    <div class="timeline-panel bgl-dark border-0 p-2 rounded text-center m-1" style="width: 32%;">
                                        <p class="mb-0 fs-14 text-dark">Sarah Lee</p>
                                        <small class="text-muted d-block fs-12">Wed, 27/08/2020<br>02:00 - 03:30 PM</small>
                                    </div>
                                    <div class="timeline-panel bgl-dark border-0 p-2 rounded text-center m-1" style="width: 32%;">
                                        <p class="mb-0 fs-14 text-dark">Mark Henry</p>
                                        <small class="text-muted d-block fs-12">Fri, 28/08/2020<br>11:00 - 12:30 PM</small>
                                    </div>
                                    <div class="timeline-panel bgl-dark border-0 p-2 rounded text-center m-1" style="width: 32%;">
                                        <p class="mb-0 fs-14 text-dark">Emma Watson</p>
                                        <small class="text-muted d-block fs-12">Sun, 30/08/2020<br>03:00 - 04:30 PM</small>
                                    </div>
                                    <div class="timeline-panel bgl-dark border-0 p-2 rounded text-center m-1" style="width: 32%;">
                                        <p class="mb-0 fs-14 text-dark">Chris Brown</p>
                                        <small class="text-muted d-block fs-12">Tue, 01/09/2020<br>09:00 - 10:30 AM</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card abilities-chart">
                        <div class="card-header border-0 pb-0">
                            <h4 class="fs-20 font-w600">Doctors Abilities</h4>
                        </div>
                        <div class="card-body">
                            <div id="pie-chart" class="ct-chart ct-golden-section chartlist-chart"></div>
                            <div class="chart-point">
                                <div><span class="a"></span> <span class="text-ov px-1 fs-15">Operation</span></div>
                                <div><span class="b"></span> <span class="text-ov px-1 fs-15">Therapy</span></div>
                                <div><span class="c"></span> <span class="text-ov px-1 fs-15">Medication</span></div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>


    @include('layouts.footer')
@endsection
