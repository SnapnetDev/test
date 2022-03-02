<table class="table is-indent tablesaw" data-tablesaw-mode="swipe" data-plugin="animateList"
        data-animate="fade" data-child="tr" data-selectable="selectable">
          <thead>
            <tr>
              <th class="pre-cell"></th>
              <th class="cell-30" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">
                <span class="checkbox-custom checkbox-primary checkbox-lg contacts-select-all">
                  <input type="checkbox" class="contacts-checkbox selectable-all" id="select_all"
                  />
                  <label for="select_all"></label>
                </span>
              </th>
              <th  data-priority="1" class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Name</th>
              <th data-priority="2" class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Staff ID</th>
              <th data-priority="3"  class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Gender</th>
              <th data-priority="3"  class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Job</th>
              <th data-priority="4"  class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Email</th>
              <th data-priority="4"  class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Manager</th>
              <th data-priority="4"  class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Address</th>
               <th data-priority="4" class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Role</th>
               <th data-priority="4" class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Hire Date</th>
               <th data-priority="4" class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Payroll Type</th>
               <th data-priority="4" class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Project Payroll Category</th>
              
              <th class="suf-cell">Basic Pay</th>
               <th>Grade</th>
              <th>Employee Status</th>
              <th>Section</th>
            </tr>
          </thead>
          <tbody>
          	@forelse($users as $user)
            <tr >
              <td class="pre-cell"></td>
              <td class="cell-30">
                <span class="checkbox-custom checkbox-primary checkbox-lg">
                  <input type="checkbox" class="contacts-checkbox users-checkbox selectable-item" id="{{$user->id}}"
                  />
                  <label for="contacts_1"></label>
                </span>
              </td>
              <td class="cell-300">

                {{$user->name}}
              </td>
              <td class="cell-300" >{{$user->emp_num}}</td>
               <td class="cell-300" >{{$user->sex=='M'?'Male':($user->sex=='F'?'Female':'')}}</td>
              <td >
              @if(count($user->jobs)>0)
              {{$user->jobs()->latest()->first()->title}}
              @endif
              </td>
              <td class="cell-300" >{{$user->email}}</td>
               <td >
              @if(count($user->managers)>0)
              {{$user->plmanager->name}}
              @endif
              </td>
              <td class="cell-300" >{{$user->address}}</td>
              <td>
              @if($user->role)
              {{$user->role->name}}
              @endif
              </td>
              @if($user->hiredate)
              <td> {{date('m/d/Y',strtotime($user->hiredate))}}</td>
              @endif

              <td> {{ucfirst($user->payroll_type)}}</td>

              @if($user->project_salary_category)
              <td> {{$user->project_salary_category->name}}</td>
              <td> {{$user->project_salary_category->basic_salary}}</td>
              @else
              <td></td>
              <td></td>
              @endif
               <td> {{$user->grade?$user->grade->level:""}}</td>
              <td>{{$user->my_status}}</td>
                 <td>
                 @if($user->section)
                {{$user->section->name}}
                @endif
                </td>
               
            </tr>
            @empty
            @endforelse

          </tbody>
        </table>
