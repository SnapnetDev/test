@extends('layouts.app')

@section('app_vue_js')
<script src="{{ asset('js/app.js') }}"></script>    
@endsection

@section('csrf_vue')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
  window.laravel = {
    csrfToken:'{{ csrf_token() }}'
  };

  
      window.laravel.userId = {{ Auth::user()->id }};
  
</script>  
@endsection

@section('content')
<style>
.page-content{
    background-color: #fff;
}


.router-link-exact-active , .router-link-exact-active:hover{
     /** background-color: #000; **/
}

</style>



  <div id="app">
          {{-- {{ csrf_field() }} --}}
{{--  {{ dd(Auth::user()->job_id) }}  --}}
            <kpi-menu-link 
                user_id="{{ Auth::user()->id }}"
                workdept_id="{{ (Auth::user()->job_id)? Auth::user()->job_id : 4 }}" 
                linemanager_id="{{ Auth::user()->linemanager_id }}"
                is_linemanager="{{ (Auth::user()->role == 2)? 1 : 0 }}"
                is_admin="{{ (Auth::user()->role == 3)? 1 : 0 }}"
            ></kpi-menu-link>

            <div class="row">
     
                    <div class="col-xs-12">
               
                          {{-- <h2>KPI Manager</h2> --}}
                          <router-view>content here</router-view>
               
                    </div>
               
            </div>
               

    
  </div>





{{-- <script src="{{ asset('js/app.js') }}"></script> --}}
<script>
        jQuery.fn.magnificPopup = function(){};
</script>   
@endsection