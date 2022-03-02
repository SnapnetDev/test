<div class="site-menubar bg-white blue-grey-800">
    <div class="site-menubar-body">
      <div>
        <div>
          <ul class="site-menu" data-plugin="menu">
             <li class="site-menu-item ">
              <a href="{{ route('home') }}" dropdown-tag="false">
                <i class="site-menu-icon md-home" aria-hidden="true"></i>
                <span class="site-menu-title">Home</span>
              </a>
            </li>



            <li class="dropdown site-menu-item has-sub">
              <a data-toggle="dropdown" href="javascript:void(0)" data-dropdown-toggle="false">
                <i class="site-menu-icon fa fa-sitemap" aria-hidden="true"></i>
                <span class="site-menu-title">Self Service.</span>
                <span class="site-menu-arrow"></span>
              </a>
              <div class="dropdown-menu">
                <div class="site-menu-scroll-wrap is-list">
                  <div>
                    <div>
                      <ul class="site-menu-sub site-menu-normal-list">

                        @include('training_new.navs.nav_include_self_service_section')


                         <li class="site-menu-item ">
                          <a class="animsition-link" href="{{ route('shift_schedule.user',['user_id'=>Auth::user()->id]) }}">
                            <span class="site-menu-title">My Shift Schedule</span>
                          </a>
                        </li>
                        @if(Auth::user()->has('my_departments'))
                        <li class="site-menu-item ">
                          <a class="animsition-link" href="{{ url('leave/dept_leave_plan_calendar') }}">
                            <span class="site-menu-title">My Department Leave Plan Calendar</span>
                          </a>
                        </li>
                        @endif
                         <li class="site-menu-item ">
                          <a class="animsition-link" href="{{ route('attendance.user',['user_id'=>Auth::user()->id]) }}">
                            <span class="site-menu-title">My attendance</span>
                          </a>
                        </li>
                        <li class="site-menu-item ">
                          <a class="animsition-link" href="{{ url('leave/myrequests') }}">
                            <span class="site-menu-title">Leave Requests</span>
                          </a>
                        </li>
                        <li class="site-menu-item ">
                          <a class="animsition-link" href="{{ url('leave/relieve_approvals') }}">
                            <span class="site-menu-title">Relieve Approvals</span>
                          </a>
                        </li>
                        <li class="site-menu-item ">
                          <a class="animsition-link" href="{{ url('bsc/my_evaluations') }}">
                            <span class="site-menu-title">Performance Evaluation</span>
                          </a>
                        </li>
                        {{--
                        <li class="site-menu-item ">
                          <a class="animsition-link" href=" {{url('loan/my_loan_requests')}}">
                            <span class="site-menu-title">Loan Requests </span>
                          </a>
                        </li> --}}
                        <li class="site-menu-item ">
                          <a class="animsition-link" href="{{ url('compensation/user_payroll_list') }}">
                            <span class="site-menu-title">View payslip </span>
                          </a>
                        </li>
                        <li class="site-menu-item ">
                          <a class="animsition-link" href="{{ url('document/mydocument') }}">
                            <span class="site-menu-title">My Documents </span>
                          </a>
                        </li>{{--
                        <li class="site-menu-item ">
                          <a class="animsition-link" href="#">
                            <span class="site-menu-title">My Expenses </span>
                          </a>
                        </li> --}}
                          <li class="site-menu-item">
                              <a class="animsition-link" href="{{url('query')}}/allqueries?queried_user_id={{request()->user()->id}}">
                                  <span class="site-menu-title">My Queries </span>
                              </a>
                          </li>
                        <li class="site-menu-item ">
                          <a class="animsition-link" href="#">
                            <span class="site-menu-title">Job Openings </span>
                          </a>
                        </li>
                        <li class="site-menu-item ">
                          <a class="animsition-link" href="{{url('organogram')}}">
                            <span class="site-menu-title">Organogram</span>
                          </a>
                        </li>
                        <li class="site-menu-item ">
                          <a class="animsition-link" href="{{url('dept-organogram')}}">
                            <span class="site-menu-title">Department Organogram</span>
                          </a>
                        </li>
                        <li class="site-menu-item ">
                          <a class="animsition-link" href="{{url('team-organogram')}}">
                            <span class="site-menu-title">Team Organogram</span>
                          </a>
                        </li>
                        <li class="site-menu-item ">
                          <a class="animsition-link" href="{{url('myteam-organogram')}}">
                            <span class="site-menu-title">My Team Organogram</span>
                          </a>
                        </li>
                        <li class="site-menu-item ">
                          <a class="animsition-link" href="{{url('directory')}}">
                            <span class="site-menu-title">Employee Directory </span>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </li>

             @if(Auth::user()->role->permissions->contains('constant', 'manage_user')||Auth::user()->role->permissions->contains('constant', 'view_timesheet')||Auth::user()->role->permissions->contains('constant', 'export_timesheet')||Auth::user()->role->permissions->contains('constant', 'view_attendance')||Auth::user()->role->permissions->contains('constant', 'view_shift_schedule')||Auth::user()->role->permissions->contains('constant', 'approve_shift_swap')||Auth::user()->role->permissions->contains('constant', 'succession_planning'))
            <li class="dropdown site-menu-item has-sub">
              <a data-toggle="dropdown" href="javascript:void(0)" data-dropdown-toggle="false">
                <i class="site-menu-icon fa fa-address-book" aria-hidden="true"></i>
                <span class="site-menu-title">Manage Subordinates</span>
                <span class="site-menu-arrow"></span>
              </a>
              <div class="dropdown-menu">
                <div class="site-menu-scroll-wrap is-list">
                  <div>
                    <div>
                      <ul class="site-menu-sub site-menu-normal-list">
                         @if(Auth::user()->role->permissions->contains('constant', 'manage_user'))
                <li class="site-menu-item ">
                  <a class="animsition-link" href="{{url('users')}}">
                    <span class="site-menu-title">Manage Subordinates</span>
                  </a>
                </li>
                @endif
                 @if(Auth::user()->role->permissions->contains('constant', 'manage_user'))
                <li class="site-menu-item ">
                  <a class="animsition-link" href="{{url('groups')}}">
                    <span class="site-menu-title">Manage User Groups</span>
                  </a>
                </li>
                @endif
                @if(Auth::user()->role->permissions->contains('constant', 'view_timesheet')||Auth::user()->role->permissions->contains('constant', 'export_timesheet'))
                <li class="site-menu-item ">
                  <a class="animsition-link" href="{{ url('timesheets') }}">
                    <span class="site-menu-title">TimeSheets</span>
                  </a>
                </li>
                @endif
                @if(Auth::user()->role->permissions->contains('constant', 'view_attendance'))
                <li class="site-menu-item ">
                  <a class="animsition-link" href="{{url('attendance/reports')}}">
                    <span class="site-menu-title">View Attendance</span>
                  </a>
                </li>
                @endif
                @if(Auth::user()->role->permissions->contains('constant', 'view_shift_schedule'))
                <li class="site-menu-item ">
                  <a class="animsition-link" href="{{ route('shift_schedules') }}">
                    <span class="site-menu-title">Shift Schedules</span>
                  </a>
                </li>
                @endif
                @if(Auth::user()->role->permissions->contains('constant', 'edit_performance'))
                   <li class="site-menu-item ">
                          <a class="animsition-link" href="{{ url('performances') }}">
                            <span class="site-menu-title">Perfomance</span>
                          </a>
                        </li>
                        <li class="site-menu-item ">
                          <a class="animsition-link" href="{{ url('bsc')}}">
                            <span class="site-menu-title">Balance Scorecard</span>
                          </a>
                        </li>
                 @endif
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            @endif




          </ul>
        </div>
      </div>
    </div>
  </div>
