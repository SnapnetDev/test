<div class="site-menubar bg-white blue-grey-800">
    <div class="site-menubar-body">
        <div>
            <div>
                <ul class="site-menu" data-plugin="menu">
                    @if(Auth::user()->role->manages=='none')
                        <li class="site-menu-item ">
                            <a href="{{ route('home') }}" dropdown-tag="false">
                                <i class="site-menu-icon md-home" aria-hidden="true"></i>
                                <span class="site-menu-title">Home</span>
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->role->manages=='all')
                        <li class="dropdown site-menu-item has-sub">
                            <a data-toggle="dropdown" href="javascript:void(0)" data-dropdown-toggle="false">
                                <i class="site-menu-icon fa fa-sitemap" aria-hidden="true"></i>
                                <span class="site-menu-title">Home</span>
                                <span class="site-menu-arrow"></span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="site-menu-scroll-wrap is-list">
                                    <div>
                                        <div>
                                            <ul class="site-menu-sub site-menu-normal-list" style="overflow-y:scroll !important;">
                                                @if(Auth::user()->role->permissions->contains('constant', 'run_payroll'))
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{ url('compensation') }}">
                                                            <span class="site-menu-title">Payroll</span>
                                                        </a>
                                                    </li>
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{ url('items') }}">
                                                            <span class="site-menu-title">Off Payroll Items</span>
                                                        </a>
                                                    </li>
                                                      <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{ url('leave/view_leave_allowances') }}">
                                                            <span class="site-menu-title">Leave Allowances</span>
                                                        </a>
                                                    </li>
                                                @endif


                                                @if ($can_access_onboarding_settings())
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{ route('checklistSettings.index') }}">
                                                            <span class="site-menu-title">Onboard Settings</span>
                                                        </a>
                                                    </li>
                                                @endif




                                                  @if ($can_access_onboarding_employess())
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{ route('employeeChecklists.index') }}">
                                                            <span class="site-menu-title">Onboard Employee</span>
                                                        </a>
                                                    </li>
                                                  @endif




                                                @include('training_new.navs.nav_include')



                                                @if(Auth::user()->role->permissions->contains('constant', 'manage_user'))
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{url('users')}}">
                                                            <span class="site-menu-title">Manage Employees</span>
                                                        </a>
                                                    </li>
                                                     <li class="site-menu-item ">
                                                            <a class="animsition-link" href="{{url('confirmation')}}">
                                                                <span class="site-menu-title">Manage Employee Confirmation</span>
                                                            </a>
                                                        </li>
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{url('userprofile/change_approval')}}">
                                                            <span class="site-menu-title">Approve Employee Profile Change</span>
                                                        </a>
                                                    </li>
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{url('bsc/hr')}}">
                                                            <span class="site-menu-title">Manage Employees Performance</span>
                                                        </a>
                                                    </li>
                                                    <li class="site-menu-item">
                                                        <a class="animsition-link" href="{{url('leave/hrrequests')}}">
                                                            <span class="site-menu-title">All Leave requests</span>
                                                        </a>
                                                    </li>
                                                        <li class="site-menu-item">
                                                            <a class="animsition-link" href="{{url('leave/leave_recall_view')}}">
                                                                <span class="site-menu-title">Recall Leave requests</span>
                                                            </a>
                                                        </li>
                                                    <li class="site-menu-item">
                                                        <a class="animsition-link" href="{{url('leave/graph_report')}}">
                                                            <span class="site-menu-title">Leave Report</span>
                                                        </a>
                                                    </li>
                                                    <li class="site-menu-item">
                                                        <a class="animsition-link" href="{{url('leave/leave_spillovers')}}">
                                                            <span class="site-menu-title">Leave Spillovers</span>
                                                        </a>
                                                    </li>
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{ url('leave/comp_leave_plan_calendar') }}">
                                                            <span class="site-menu-title">Leave Plan Calendar</span>
                                                        </a>
                                                    </li>
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href=" {{url('document_requests/index')}}">
                                                            <span class="site-menu-title">Document Requests</span>
                                                        </a>
                                                    </li>
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href=" {{url('company_documents/index')}}">
                                                            <span class="site-menu-title">Company Documents</span>
                                                        </a>
                                                    </li>
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href=" {{url('employee_reimbursements/index')}}">
                                                            <span class="site-menu-title">Expense Reimbursements</span>
                                                        </a>
                                                    </li>
                                                    <li class="site-menu-item">
                                                        <a class="animsition-link" href="{{url('bsc/hr')}}">
                                                            <span class="site-menu-title">Balance Scorecard</span>
                                                        </a>
                                                    </li>
                                                    <li class="site-menu-item">
                                                        <a class="animsition-link" href="{{url('separation')}}">
                                                            <span class="site-menu-title">Separations</span>
                                                        </a>
                                                    </li>
                                                    <li class="site-menu-item">
                                                        <a class="animsition-link" href="{{url('separation/suspensions')}}">
                                                            <span class="site-menu-title">Suspensions</span>
                                                        </a>
                                                    </li>

                                                    <li class="site-menu-item">
                                                        <a class="animsition-link" href="{{url('unions')}}">
                                                            <span class="site-menu-title">Worker Unions</span>
                                                        </a>
                                                    </li>
                                                    <li class="site-menu-item">
                                                        <a class="animsition-link" href="{{url('sections')}}">
                                                            <span class="site-menu-title">Staff Sections</span>
                                                        </a>
                                                    </li>
                                                    @if(Auth::user()->role->permissions->contains('constant', 'issue_query'))
                                                        <li class="site-menu-item">
                                                            <a class="animsition-link" href="{{url('query')}}/allqueries">
                                                                <span class="site-menu-title">All User Queries </span>
                                                            </a>
                                                        </li>
                                                    @endif
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
                                                @if(isset(\App\Setting::where('name','uses_tams')->where('company_id',companyId())->first()->value) && \App\Setting::where('name','uses_tams')->where('company_id',companyId())->first()->value=='1')
                                                    @if(Auth::user()->role->permissions->contains('constant', 'view_attendance'))
                                                        <li class="site-menu-item ">
                                                            <a class="animsition-link" href="{{route('daily.attendance.report')}}">
                                                                <span class="site-menu-title">View Attendance</span>
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if(Auth::user()->role->permissions->contains('constant', 'view_shift_schedule'))
                                                        <li class="site-menu-item ">
                                                            <a class="animsition-link" href="{{ route('employeeShiftSchedules') }}">
                                                                <span class="site-menu-title">Shift Schedules</span>
                                                            </a>
                                                        </li>
                                                    @endif


                                                @endif


                                                @if (Auth::user()->role->permissions->contains('constant', 'manage_organogram'))
                                                    <li class="site-menu-item ">
                                                        <a href="{{url('organograms/settings')}}"  class="animsition-link">
                                                            <span class="site-menu-title">Organogram Setup</span>
                                                        </a>
                                                    </li>


                                                @endif
                                                @if (Auth::user()->role->permissions->contains('constant', 'view_audit_report'))
                                                    <li class="site-menu-item ">
                                                        <a href="{{url('audits/index')}}" type="button"  class="animsition-link">
                                                            <span class="site-menu-title">View Audit Log</span>
                                                        </a>
                                                    </li>
                                                @endif





                                                @if (Auth::user()->role->permissions->contains('constant', 'view_audit_report'))
                                                    <li class="site-menu-item ">
                                                        <a href="{{url('/hmo-directory')}}" type="button"  class="animsition-link">
                                                            <span class="site-menu-title">HMO Service</span>
                                                        </a>
                                                    </li>
                                                    <li class="site-menu-item ">
                                                        <a href="{{url('/hmo-setup')}}" type="button"  class="animsition-link">
                                                            <span class="site-menu-title">HMO Setup</span>
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

                    <li class="dropdown site-menu-item has-sub">
                        <a data-toggle="dropdown" href="javascript:void(0)" data-dropdown-toggle="false">
                            <i class="site-menu-icon fa fa-sitemap" aria-hidden="true"></i>
                            <span class="site-menu-title">Self Service</span>
                            <span class="site-menu-arrow"></span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="site-menu-scroll-wrap is-list">
                                <div>
                                    <div>
                                        <ul class="site-menu-sub site-menu-normal-list" style="overflow-y:scroll;height:383px;">


{{--                                            @include('training_new.navs.nav_include_self_service_section')--}}


                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{ route('onboard.start') }}?employee_id={{ Auth::user()->id }}">
                                                    <span class="site-menu-title">Onboarding</span>
                                                </a>
                                            </li>

                                            @if(isset(\App\Setting::where('name','uses_tams')->where('company_id',companyId())->first()->value) && \App\Setting::where('name','uses_tams')->where('company_id',companyId())->first()->value=='1')
                                                <li class="site-menu-item ">
                                                    <a class="animsition-link" href="{{ route('shift_schedule.user.my') }}">
                                                        <span class="site-menu-title">My Shift Schedule</span>
                                                    </a>
                                                </li>
                                                <li class="site-menu-item ">
                                                    <a class="animsition-link" href="{{ route('attendance.user.cal') }}">
                                                        <span class="site-menu-title">My Attendance</span>
                                                    </a>
                                                </li>
                                            @endif

                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{ url('leave/myrequests') }}">
                                                    <span class="site-menu-title">Leave Requests</span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{ url('leave/relieve_approvals') }}">
                                                    <span class="site-menu-title">Leave Relieve Approvals</span>
                                                </a>
                                            </li>
                                           
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href=" {{url('document_requests/my_document_requests')}}">
                                                    <span class="site-menu-title">My Document Requests </span>
                                                </a>
                                            </li>
                                             
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href=" {{url('company_documents/my_company_documents')}}">
                                                    <span class="site-menu-title">My Company Documents </span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href=" {{url('employee_reimbursements/my_expense_reimbursements')}}">
                                                    <span class="site-menu-title">My Expense Reimbursements </span>
                                                </a>
                                            </li>
                                             <li class="site-menu-item ">
                                                <a class="animsition-link" href=" {{url('confirmation/my_confirmation_request')}}">
                                                    <span class="site-menu-title">My Confirmation </span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{ url('bsc/my_evaluations') }}">
                                                    <span class="site-menu-title">Performance Evaluation</span>
                                                </a>
                                            </li>

                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href=" {{url('loan/my_loan_requests')}}">
                                                    <span class="site-menu-title">Loan Requests </span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{ url('compensation/user_payroll_list') }}">
                                                    <span class="site-menu-title">View payslip </span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{ url('document/mydocument') }}">
                                                    <span class="site-menu-title">My Documents </span>
                                                </a>
                                            </li>

                                            {{--
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
                                                <a class="animsition-link" href="{{url('recruits/myjobs')}}">
                                                    <span class="site-menu-title">Job Openings </span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{url('organogram')}}">
                                                    <span class="site-menu-title">Organogram</span>
                                                </a>
                                            </li>
                                          
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{url('directory')}}">
                                                    <span class="site-menu-title">Employee Directory </span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{url('polls')}}">
                                                    <span class="site-menu-title">Polls </span>
                                                </a>
                                            </li>

                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{ route('employee.performace.kpi')}}">
                                                    <span class="site-menu-title">Performance KPI</span>
                                                </a>
                                            </li>

                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{ route('DocumentManagement') }}">
                                                    <span class="site-menu-title">Document Management</span>
                                                </a>
                                            </li>

                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{ route('MailManagement') }}">
                                                    <span class="site-menu-title">Mail Management</span>
                                                </a>
                                            </li>

                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{ route('HMOSelfService')}}">
                                                    <span class="site-menu-title">HMO Service</span>
                                                </a>
                                            </li>


                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @if( Auth::user()->role->permissions->contains('constant', 'approve_performance'))
                        <li class="dropdown site-menu-item has-sub">
                            <a data-toggle="dropdown" href="javascript:void(0)" data-dropdown-toggle="false">
                                <i class="site-menu-icon fa fa-check-square" aria-hidden="true"></i>
                                <span class="site-menu-title">Approvals</span>
                                <span class="site-menu-arrow"></span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="site-menu-scroll-wrap is-list">
                                    <div>
                                        <div>
                                            <ul class="site-menu-sub site-menu-normal-list">
                                                 <li class="site-menu-item ">
                                                <a class="animsition-link" href=" {{url('leave/approvals')}}">
                                                    <span class="site-menu-title">Leave Approvals </span>
                                                </a>
                                            </li>

                                             
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href=" {{url('compensation/approvals')}}">
                                                    <span class="site-menu-title">Payroll Approvals </span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href=" {{url('document_requests/approvals')}}">
                                                    <span class="site-menu-title">Document Requests Approvals </span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href=" {{url('employee_reimbursements/approvals')}}">
                                                    <span class="site-menu-title">Expense Reimbursement Approvals </span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href=" {{url('separation/approvals')}}">
                                                    <span class="site-menu-title">Separation Approvals </span>
                                                </a>
                                            </li>
                                                <li class="site-menu-item ">
                                                    <a class="animsition-link" href=" {{url('bsc/get_approvals')}}">
                                                        <span class="site-menu-title">Performance Approvals </span>
                                                    </a>
                                                </li>


                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>


                    @endif

                        <li class="dropdown site-menu-item has-sub">
                            <a data-toggle="dropdown" href="javascript:void(0)" data-dropdown-toggle="false">
                                <i class="site-menu-icon fa fa-area-chart" aria-hidden="true"></i>
                                <span class="site-menu-title">Evaluation</span>
                                <span class="site-menu-arrow"></span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="site-menu-scroll-wrap is-list">
                                    <div>
                                        <div>
                                            <ul class="site-menu-sub site-menu-normal-list">


                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{ url('bsc')}}">
                                                            <span class="site-menu-title">Balance Scorecard</span>
                                                        </a>
                                                    </li>



                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                    @if(Auth::user()->role->permissions->contains('constant', 'edit_performance'))
                        <li class="dropdown site-menu-item has-sub">
                            <a data-toggle="dropdown" href="javascript:void(0)" data-dropdown-toggle="false">
                                <i class="site-menu-icon fa fa-circle-o-notch" aria-hidden="true"></i>
                                <span class="site-menu-title">Review 360</span>
                                <span class="site-menu-arrow"></span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="site-menu-scroll-wrap is-list">
                                    <div>
                                        <div>
                                            <ul class="site-menu-sub site-menu-normal-list">
                                                @if(Auth::user()->role->permissions->contains('constant', 'upload_review_questions'))
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{ url('e360settings/template') }}">
                                                            <span class="site-menu-title">Review Questions</span>
                                                        </a>
                                                    </li>
                                                @endif

                                                @if(Auth::user()->role->permissions->contains('constant', 'view_review_report'))
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{ url('e360/report_index')}}">
                                                            <span class="site-menu-title">Employee Review Report</span>
                                                        </a>
                                                    </li>
                                                @endif
                                                <li class="site-menu-item ">
                                                    <a class="animsition-link" href="{{ url('e360')}}">
                                                        <span class="site-menu-title">Employee 360 Review</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                    <li class="dropdown site-menu-item has-section has-sub">
                        <a data-toggle="dropdown" href="javascript:void(0)" data-dropdown-toggle="false">
                            <i class="site-menu-icon fa fa-ellipsis-h" aria-hidden="true"></i>
                            <span class="site-menu-title">More</span>
                            <span class="site-menu-arrow"></span>
                        </a>
                        <ul class="dropdown-menu site-menu-sub site-menu-section-wrap blocks-md-4" >
                            @if(Auth::user()->role->permissions->contains('constant', 'view_hr_reports')||Auth::user()->role->permissions->contains('constant', 'view_attendance_report')||Auth::user()->role->permissions->contains('constant', 'view_leave_report'))
                                <li class="site-menu-section site-menu-item has-sub">
                                    <header>
                                        <i class="site-menu-icon fa fa-users" aria-hidden="true"></i>
                                        <span class="site-menu-title">Talent Management</span>
                                        <span class="site-menu-arrow"></span>
                                    </header>
                                    <div class="site-menu-scroll-wrap is-section">
                                        <div>
                                            <div>
                                                <ul class="site-menu-sub site-menu-section-list">
                                                    <li class="site-menu-item">
                                                        <a class="animsition-link" href="{{url('jobs_departments')}}">
                                                            <span class="site-menu-title">Jobs</span>
                                                        </a>
                                                    </li>
                                                    <li class="site-menu-item">
                                                        <a class="animsition-link" href="{{url('recruits')}}">
                                                            <span class="site-menu-title">Recruit</span>
                                                        </a>
                                                    </li>
                                                    {{--                          <li class="site-menu-item has-sub">--}}
                                                    {{--                            <a href="javascript:void(0)">--}}
                                                    {{--                              <span class="site-menu-title">Training And Development</span>--}}
                                                    {{--                              <span class="site-menu-arrow"></span>--}}
                                                    {{--                            </a>--}}
                                                    {{--                            <ul class="site-menu-sub">--}}
                                                    {{--                              <li class="site-menu-item ">--}}
                                                    {{--                                <a class="animsition-link" href="{{url('newtraining')}}">--}}
                                                    {{--                                  <span class="site-menu-title">New Training</span>--}}
                                                    {{--                                </a>--}}
                                                    {{--                              </li>--}}
                                                    {{--                              <li class="site-menu-item ">--}}
                                                    {{--                                <a class="animsition-link" href="{{url('training')}}">--}}
                                                    {{--                                  <span class="site-menu-title">Recommended Training</span>--}}
                                                    {{--                                </a>--}}
                                                    {{--                              </li>--}}
                                                    {{--                              <li class="site-menu-item ">--}}
                                                    {{--                                <a class="animsition-link" href="{{url('mytraining')}}">--}}
                                                    {{--                                  <span class="site-menu-title">My Training</span>--}}
                                                    {{--                                </a>--}}
                                                    {{--                              </li>--}}
                                                    {{--                              <li class="site-menu-item ">--}}
                                                    {{--                              <a class="animsition-link" href="{{url('training/budget')}}">--}}
                                                    {{--                                <span class="site-menu-title">Training Budget</span>--}}
                                                    {{--                              </a>--}}
                                                    {{--                            </li>--}}
                                                    {{--                            </ul>--}}
                                                    {{--                          </li>--}}

                                                    {{-- <li class="site-menu-item">
                                                      <a class="animsition-link" href="uikit/dropdowns.html">
                                                        <span class="site-menu-title">Successiion Planning</span>
                                                      </a>
                                                    </li> --}}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            @if(Auth::user()->role->permissions->contains('constant', 'view_hr_reports')||Auth::user()->role->permissions->contains('constant', 'view_attendance_report')||Auth::user()->role->permissions->contains('constant', 'view_leave_report'))
                                <li class="site-menu-section site-menu-item has-sub">
                                    <header>
                                        <i class="site-menu-icon md-view-compact" aria-hidden="true"></i>
                                        <span class="site-menu-title">People Analytics</span>
                                        <span class="site-menu-arrow"></span>
                                    </header>
                                    <div class="site-menu-scroll-wrap is-section">
                                        <div>
                                            <div>
                                                <ul class="site-menu-sub site-menu-section-list">

                                                    @if(Auth::user()->role->permissions->contains('constant', 'view_attendance_report'))
                                                        {{-- <li class="site-menu-item ">
                                                          <a class="animsition-link" href="{{url('bi-report')}}?page=demographics">
                                                            <span class="site-menu-title">Employee</span>
                                                          </a>
                                                        </li> --}}
                                                        <li class="site-menu-item ">
                                                            <a class="animsition-link" href="{{url('people_analytics_hr')}}">
                                                                <span class="site-menu-title">Demographics</span>
                                                            </a>
                                                        </li>
                                                        <li class="site-menu-item ">
                                                            <a class="animsition-link" href="{{url('people_analytics_payroll')}}">
                                                                <span class="site-menu-title">Payroll</span>
                                                            </a>
                                                        </li>
                                                        <li class="site-menu-item ">
                                                            <a class="animsition-link" href="{{url('people_analytics_performance')}}">
                                                                <span class="site-menu-title">Performance</span>
                                                            </a>
                                                        </li>
                                                    @endif

                                                    @if(Auth::user()->role->permissions->contains('constant', 'view_leave_report'))
                                                        {{-- <li class="site-menu-item ">
                                                          <a class="animsition-link" href="{{url('bi-report')}}?page=payroll">
                                                            <span class="site-menu-title">Payroll</span>
                                                          </a>
                                                        </li> --}}
                                                    @endif

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            <li class="site-menu-section site-menu-item has-sub" style="z-index: 9999999999;">
                                <header>
                                    <i class="site-menu-icon md-view-compact" aria-hidden="true"></i>
                                    <span class="site-menu-title">Projects</span>
                                    <span class="site-menu-arrow"></span>
                                </header>
                                <div class="site-menu-scroll-wrap is-section">
                                    <div>
                                        <div>
                                            <ul class="site-menu-sub site-menu-section-list">
                                                @if(Auth::user()->role->permissions->contains('constant', 'manage_user'))
                                                <li class="site-menu-item ">
                                                    <a class="animsition-link" href="{{url('projects')}}">
                                                        <span class="site-menu-title">Projects</span>
                                                    </a>
                                                </li>
                                                @endif
                                                <li class="site-menu-item ">
                                                    <a class="animsition-link" href="{{url('projects/timesheet')}}">
                                                        <span class="site-menu-title">Timesheet management</span>
                                                    </a>
                                                </li>
                                                @if(Auth::user()->role->permissions->contains('constant', 'manage_user'))
                                                <li class="site-menu-item ">
                                                    <a class="animsition-link" href="{{url('projects/get_month_report')}}">
                                                        <span class="site-menu-title">Timesheet Report</span>
                                                    </a>
                                                </li>
                                                @endif


                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            {{-- <li class="site-menu-section site-menu-item ">


                               <a class="animsition-link" href="">
                                 <i class="site-menu-icon  fa fa-list" aria-hidden="true"></i>
                                 <span class="site-menu-title">E-Learning</span>
                               </a>

                             </li>--}}
                            @if(Auth::user()->role->permissions->contains('constant', 'view_attendance_report')||Auth::user()->role->permissions->contains('constant', 'view_attendance_report')))
                            @if(isset(\App\Setting::where('name','uses_tams')->where('company_id',companyId())->first()->value) && \App\Setting::where('name','uses_tams')->where('company_id',companyId())->first()->value=='1')
                                <li class="site-menu-section site-menu-item has-sub">
                                    <header>
                                        <i class="site-menu-icon md-view-compact" aria-hidden="true"></i>
                                        <span class="site-menu-title">TAMS</span>
                                        <span class="site-menu-arrow"></span>
                                    </header>
                                    <div class="site-menu-scroll-wrap is-section">
                                        <div>
                                            <div>
                                                <ul class="site-menu-sub site-menu-section-list">
                                                    @if(Auth::user()->role->permissions->contains('constant', 'view_attendance_report'))
                                                        <li class="site-menu-item ">
                                                            <a class="animsition-link" href="{{route('daily.attendance.report')}}">
                                                                <span class="site-menu-title">Daily Attendance Report</span>
                                                            </a>
                                                        </li>
                                                        <li class="site-menu-item ">
                                                            <a class="animsition-link" href="{{route('monthly.attendance.report')}}">
                                                                <span class="site-menu-title">Monthly Attendance Report</span>
                                                            </a>
                                                        </li>
                                                        <li class="site-menu-item ">
                                                            <a class="animsition-link" href="{{route('lateness.report')}}">
                                                                <span class="site-menu-title">Lateness Report</span>
                                                            </a>
                                                        </li>
                                                        <li class="site-menu-item ">
                                                            <a class="animsition-link" href="{{route('employeeShiftSchedules')}}">
                                                                <span class="site-menu-title">Schedule Shifts</span>
                                                            </a>
                                                        </li>
                                                        <li class="site-menu-item ">
                                                            <a class="animsition-link" href="{{route('attendance.payroll')}}">
                                                                <span class="site-menu-title">Attendance Payroll</span>
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            @endif
                            @if(Auth::user()->role->permissions->contains('constant', 'manage_import'))
                                <li class="site-menu-section site-menu-item has-sub" style="z-index: 9999999999;">
                                    <header>
                                        <i class="site-menu-icon md-view-compact" aria-hidden="true"></i>
                                        <span class="site-menu-title">Import Data</span>
                                        <span class="site-menu-arrow"></span>
                                    </header>
                                    <div class="site-menu-scroll-wrap is-section">
                                        <div>
                                            <div>
                                                <ul class="site-menu-sub site-menu-section-list">

                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{url('import')}}">
                                                            <span class="site-menu-title">Import Data</span>
                                                        </a>
                                                    </li>


                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </li>
                <li class="dropdown site-menu-item ">
                    <a data-toggle="dropdown"
                       href="{{env('VISITOR_URL','http://localhost:8000')}}/hcmatrix-sync?email={{ Auth::user()->email }}&url=home&role={{ Auth::user()->role->permissions->contains('constant', 'admin_dashboard') ? 1 : (Auth::user()->role->permissions->contains('constant', 'front_desk') ? 3 : 2) }}"
                       target="_blank">
                        <i class="site-menu-icon fa fa-address-book" aria-hidden="true"></i>
                        <span class="site-menu-title">Visitor Module</span>
                        <!-- <span class="site-menu-arrow"></span> -->
                    </a>
                </li>

                    <!-- support handle -->
                                <li class="dropdown site-menu-item ">
              <a data-toggle="dropdown" href="https://support.snapnet.com.ng/hcmatrixlogin?email={{Auth::user()->email}}" target="_blank" >
                <i class="site-menu-icon fa fa-phone" aria-hidden="true"></i>
                <span class="site-menu-title">Support</span>
                <!-- <span class="site-menu-arrow"></span> -->
              </a>
            </li>


                </ul>
            </div>
        </div>
    </div>
</div>
