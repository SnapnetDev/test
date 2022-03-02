<style type="text/css">
  .bg-back{
    background: #4c4c4c; /* Old browsers */
background: -moz-linear-gradient(-45deg, #4c4c4c 0%, #595959 12%, #000000 16%, #000000 16%, #666666 25%, #000000 38%, #474747 47%, #2c2c2c 50%, #111111 71%, #2b2b2b 76%, #1c1c1c 91%, #131313 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(-45deg, #4c4c4c 0%,#595959 12%,#000000 16%,#000000 16%,#666666 25%,#000000 38%,#474747 47%,#2c2c2c 50%,#111111 71%,#2b2b2b 76%,#1c1c1c 91%,#131313 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(135deg, #4c4c4c 0%,#595959 12%,#000000 16%,#000000 16%,#666666 25%,#000000 38%,#474747 47%,#2c2c2c 50%,#111111 71%,#2b2b2b 76%,#1c1c1c 91%,#131313 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#4c4c4c', endColorstr='#131313',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
  }
</style>
<header class="slidePanel-header overlay .bg-back" >
  <div class="overlay-panel overlay-background vertical-align">
    <div class="slidePanel-actions">
      <div class="btn-group btn-group-flat">
        <button type="button" class="btn btn-pure btn-inverse icon md-folder" aria-hidden="true"></button>
        <button type="button" class="btn btn-pure btn-inverse icon md-delete" aria-hidden="true"></button>
        <button type="button" class="btn btn-pure btn-inverse slidePanel-close icon md-close"
          aria-hidden="true"></button>
      </div>
    </div>
    <div class="vertical-align-middle">
      <a class="avatar" href="javascript:void(0)">
        <img src="{{ file_exists(public_path('uploads/avatar'.$user->image))?asset('uploads/avatar'.$user->image):($user->sex=='M'?asset('global/portraits/male-user.png'):asset('global/portraits/female-user.png'))}}" alt="...">
      </a>
      <h3 class="name">{{$user->name}}</h3>
      <h4 class="name">({{$user->role->name}})</h4>
      <div class="tags">

        <a type="button" target="_blank" href="{{ route('app.get',['course-training']) }}?id={{ $user->id }}" class="btn btn-inverse"><i class="icon fa fa-book"></i>View Training</a>

        <a type="button" target="_blank" href="{{ url('user_attendance/'.$user->id) }}" class="btn btn-inverse"><i class="icon fa fa-calendar"></i>Attendance</a>
        <a type="button" target="_blank" href="{{ url('performances/employee?id='.$user->id) }}"  class="btn btn-inverse"><i class="icon fa fa-bar-chart"></i>Performance</a>
          @if(Auth::user()->role->permissions->contains('constant', 'issue_query'))
        <a type="button" target="_blank" href="javascript::void(0)" data-toggle="modal" data-target="#queryEmployee" onclick="queryEmployee({{$user->id}},'{{$user->name}}')" class="btn btn-inverse"><i class="icon fa fa-warning"></i>Query Employee</a>
          @endif
        <a type="button" target="_blank" class="btn btn-inverse" href="{{ url('user_shift_schedules/'.$user->id) }}"><i class="icon fa fa-warining"></i>Shift Schedule</a>
      </div>
    </div>
    <a href="{{ route('users.edit',['id'=>$user->id]) }}"  class="edit btn btn-success btn-floating" >
      <i class="icon md-eye animation-scale-up" aria-hidden="true"></i>
    </a>
  </div>
</header>
<div class="slidePanel-inner">
  <table class="user-info">
    <tbody>
      <tr>
        <td class="info-label">Gender:</td>
        <td>
          <span> {{$user->sex=='M'?'Male':($user->sex=='F'?'Female':'')}}</span>
          <div class="form-group form-material floating">
            <input type="email" class="form-control empty" name="inputFloatingEmail" value="mazhesee@gmail.com">
          </div>
        </td>
       </tr>
        <tr>
        <td class="info-label">Email:</td>
        <td>
          <span>{{$user->email}}</span>
          <div class="form-group form-material floating">
            <input type="email" class="form-control empty" name="inputFloatingEmail" value="mazhesee@gmail.com">
          </div>
        </td>
      </tr>
      <tr>
        <td class="info-label">Phone:</td>
        <td>
          <span>{{$user->phone}}</span>
          <div class="form-group form-material floating">
            <input type="text" class="form-control empty" name="inputFloatingPhone" value="{{$user->phone}}">
          </div>
        </td>
      </tr>
      <tr>
        <td class="info-label">Address:</td>
        <td>
          <span>{{$user->address}}</span>
          <div class="form-group form-material floating">
            <input type="text" class="form-control empty" name="inputFloatingAddress" value="Fuzhou">
          </div>
        </td>
      </tr>
      <tr>
        <td class="info-label">Position:</td>
        <td>
          <span> @if(count($user->jobs)>0)
              {{$user->jobs()->latest()->first()->title}}
              @endif</span>
          <div class="form-group form-material floating">
            <input type="text" class="form-control empty" name="inputFloatingBirthday" value="1990/2/15">
          </div>
        </td>
      </tr>
      <tr>
        <td class="info-label">With Us Since:</td>
        <td>
          <span>{{\Carbon\Carbon::parse($user->hiredate)->diffForHumans()}}</span>
          <div class="form-group form-material floating">
            <input type="text" class="form-control empty" name="inputFloatingURL" value="http://amazingSurge.com">
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</div>
<script>
    function queryEmployee(id,name) {
        $('#employee_name').text(name);
        $('#queried_user_id').val(id);

    }


</script>

