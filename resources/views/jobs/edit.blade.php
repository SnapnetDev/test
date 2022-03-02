@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('global/vendor/summernote/summernote.css') }}">
  <style media="screen">
    .form-cont{
      border: 1px solid #cccccc;
      padding: 10px;
      border-radius: 5px;
    }
    #sklcont {
      list-style: none;
    }
    #sklcont li{
      margin-bottom: 10px;
    }
  </style>

@endsection
@section('content')
<div class="page ">
    <div class="page-header">
      <h1 class="page-title">Jobs</h1>
      <div class="page-header-actions">
    <div class="row no-space w-250 hidden-sm-down">

      <div class="col-sm-6 col-xs-12">
        <div class="counter">
          <span class="counter-number font-weight-medium">{{date("M j, Y")}}</span>

        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="counter">
          <span class="counter-number font-weight-medium" id="time">{{date('h:i s a')}}</span>

        </div>
      </div>
    </div>
  </div>
    </div>
    <div class="page-content container-fluid">
          @if (session('error'))
                    <div class="alert alert-danger alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                        {{ session('success') }}
                    </div>
                @endif
              <form class="form-horizontal" method="POST" action="{{ route('jobs.update',$job->id) }}">
              {{ csrf_field() }}
              @method('PUT')
              <div class="panel panel-info panel-line">
                <div class="panel-heading main-color-bg">
                  <h3 class="panel-title">Job Details</h3>
                </div>

                <div class="panel-body">


                  <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{$job->title}}" placeholder="" required>
                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
                  </div>
                  {{-- <div class="form-group">
                    <label for="">Personnel</label>
                    <input type="number" class="form-control" id="personnel" name="personnel" value="{{$job->personnel}}" placeholder="" required>
                    @if ($errors->has('personnel'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('personnel') }}</strong>
                                        </span>
                                    @endif
                  </div> --}}
                  <div class="form-group">
                    <label for="">Description</label>
                    <textarea class="form-control" id="description" name="description">{!!$job->description!!}</textarea>
                  </div>
                  <div class="form-group skills-div" >
                              <label for="">Parent Job</label>
                              <select class="form-control parent" name="parent_id" >
                                @if($job->parent)
                                  <option value="{{$job->parent->id}}" >{{$job->parent->title}}</option>
                                 @endif
                               
                              </select>

                            </div>
                            <div class="form-group " >
                      <label class="form-control-label" for="select">Least Qualification</label>
                      <select class="form-control " id="qualification" name="qualification" required>
                         @if($job->qualification)
                                  <option value="{{$job->qualification->id}}" >{{$job->qualification->name}}</option>
                                 @endif
                        
                      </select>
                    </div>

                </div>

                </div>

                <div class="panel panel-info panel-line">
                  <div class="panel-heading main-color-bg">
                    <h3 class="panel-title">Skill Requirements</h3>
                  </div>

                  <div class="panel-body">
                    <ul id="sklcont">
                      @foreach ($job->skills as $skill)
                        <li>
                          <div class="form-cont" >
                           
                            
                            
                               
                              <div class="form-group skills-div" >
                              <label for="">Skill</label>
                              <select class="form-control skills" name="skill[]" >
                                
                                  <option value="{{$skill->id}}" >{{$skill->name}}</option>
                                 
                               
                              </select>

                            </div>
                            <div class="form-group competencies-div"  > 
                              <label for="">Competencies</label> 
                              <select class="form-control roles" name="competency_id[]" > 
                              @forelse ($competencies as $competency)
                                 <option value="{{$competency->id}}" {{$skill->pivot->competency_id==$competency->id}} >{{$competency->proficiency}}</option>
                                 @empty
                                  <option value="">No Competencies Created</option>
                               @endforelse 
                                
                              </select>
                               </div>
                               
                               
                            <div class="form-group">
                              <button type="button" class="btn btn-primary " id="remSkill">Remove Skill</button>
                            </div>
                          </div>
                        </li>
                      @endforeach

                    </ul>
                    <button type="button" id="addSkill" name="button" class="btn btn-primary">New Skill</button>
                  </div>
                  </div>
                  <input type="hidden" name="department_id" value="{{$job->department->id}}">
                  <button type="submit" class="btn btn-primary">
                      Save Changes
                  </button>
                </form>


  </div>
</div>
 @endsection

@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('global/vendor/summernote/summernote.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
 $('#description').summernote();
       $('.skills').select2({
    ajax: {
     delay: 250,
     processResults: function (data) {
          return {        
    results: data
      };
    },
    url: function (params) {
    return '{{url('job_skill_search')}}';
    } 
    },
  tags: true
});
       $('#descripton').summernote();

       $('.parent').select2({
    ajax: {
     delay: 250,
     processResults: function (data) {
          return {        
    results: data
      };
    },
    url: function (params) {
    return '{{url('job_search')}}';
    } 
    }
});
        $('#qualification').select2({
    ajax: {
     delay: 250,
     processResults: function (data) {
          return {        
    results: data
      };
    },
    url: function (params) {
    return '{{url('job_qualification_search')}}';
    } 
    },
  tags: true
});

  var sklcont = $('#sklcont');
        var i = $('#sklcont li').length + 1;

        $('#addSkill').on('click', function() {
          //console.log('working');
          $('.skills').select2('destroy');
               $(' <li><div class="form-cont" > <div class="form-group users-div"> <label for="">Skills</label> <select class="form-control skills" name="skill[]" ></select> </div> <div class="form-group roles-div"> <label for="">Competency</label> <select class="form-control roles" name="competency_id[]" >   @forelse ($competencies as $competency) <option value="{{$competency->id}}">{{$competency->proficiency}}</option> @empty <option value="">No Competency Created</option> @endforelse </select> </div> <div class="form-group"> <button type="button" class="btn btn-primary " id="remSkill">Remove Skill</button> </div> </div> </li>').appendTo(sklcont);

               $('.skills').select2({
                          ajax: {
                           delay: 250,
                           processResults: function (data) {
                                return {        
                          results: data
                            };
                          },
                          url: function (params) {
                          return '{{url('job_skill_search')}}';
                          } 
                          },
                        tags: true
                      });
                //console.log('working'+i);
              
                //console.log('working'+i);
                i++;
                return false;
        });

        $(document).on('click',"#remSkill",function() {
          //console.log('working'+i);
                if( i > 1 ) {
                   console.log('working'+i);
                        $(this).parents('li').remove();
                        i--;
                }
                return false;
    });
         
    
});
// function prepareEditData(salary_component_id){
//     $.get('{{ url('/payrollsettings/salary_component') }}/',{ salary_component_id: salary_component_id },function(data){
      
//      $('#editscname').val(data.name);
//      $('#editsccomment').val(data.comment);
//      $('#editscformula').val(data.formula);
//      $('#editscconstant').val(data.constant);
//       $("#editscexemptions").find('option')
//     .remove();
//     console.log(data.type);
//     if (data.type==1) {
//       $("#editscallowance").prop("checked", true);
//       $("#editscdeduction").prop("checked", false);
//     }else{
//       $("#editscdeduction").prop("checked", true);
//       $("#editscallowance").prop("checked", false);
//     }
    
//      jQuery.each( data.exemptions, function( i, val ) {
//        $("#editscexemptions").append($('<option>', {value:val.id, text:val.name,selected:'selected'}));
//        // console.log(val.name);
//               }); 
//      $('#editscid').val(data.id);
//     });
//     $('#editSalaryComponentModal').modal();
//   }
</script>

@endsection