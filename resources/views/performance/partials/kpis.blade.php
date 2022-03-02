      @if(count($kpis)>0)
      @foreach($kpis as $kpi)
      <div class="panel-group panel-group-simple m-b-0" id="exampleAccordionKPI{{$kpi->id}}" aria-multiselectable="true" role="tablist">
        <div class="panel">
          <div class="panel-heading" id="exampleHeadingThree" role="tab">
            <a style="color: #1e88e5;" class="panel-title collapsed" data-parent="#exampleAccordionKPI{{$kpi->id}}" data-toggle="collapse" href="#exampleCollapseThreeKPI{{$kpi->id}}" aria-controls="exampleCollapseThreeKPI{{$kpi->id}}" aria-expanded="false">
             <b> {{$kpi->deliverable}} </b>
           </a>
         </div>
         <div class="panel-collapse collapse" id="exampleCollapseThreeKPI{{$kpi->id}}" aria-labelledby="exampleHeadingThree" role="tabpanel" aria-expanded="false">
          <div class="panel-body">
           
          <!-- PANEL BODY -->
          <table class="table table-striped"> 
              <tr>
                 <td>Target Weight</td>
                 <td>
              <span class="tag tag-info">{{$kpi->targetweight}}</span></td>
                  </tr>
                  <tr>
                 <td>Target Amount</td>
                 <td>
              <span class="tag tag-info">{{$kpi->targetamount}}</span></td>
                  </tr>
              <tr>
                 <td>Amount Achieved</td>
                 <td>{{ $kpi->achieved }}</td>
                  </tr>
               <tr>
                 <td>Status</td>
                 <td>{!! $kpi->html_status !!}</td>
                  </tr>
                 <tr>
                   <td>Comment</td>
                   <td>{{$kpi->comment}}</td>
                 </tr>
                 <tr>
                  <td>Action</td>
                   <td>
                      <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary dropdown-toggle waves-effect" id="exampleIconDropdown1" data-toggle="dropdown" aria-expanded="false">
                      <i class="icon md-apps" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                      @if($employee->id==Auth::user()->id)
                      <a class="dropdown-item" href="javascript:void(0)" onclick="progressReport({{$kpi->id}},'{{$kpi->deliverable}}')" role="menuitem">Add Report</a>
                      @endif
                      <a class="dropdown-item" href="javascript:void(0)"onclick="showModal('viewreport{{$kpi->id}}')" role="menuitem">View Progress Report</a>
                      
                      @if(Auth::user()->role->permissions->contains('constant', 'edit_kpi') && in_array(Auth::user()->role->manages,['dr','all'])){
                      <a class="dropdown-item" href="javascript:void(0)" onclick="fillmodal('{!! $kpi->deliverable !!}','{{$kpi->targetweight}}','{{$kpi->targetamount}}','{{$kpi->quarter}}','{{$kpi->comment}}','{{$kpi->id}}')" role="menuitem">Edit KPI</a>
                      @endif
                    </div>
                  </div>
                   </td>
                 </tr>
                 </table>
                 <!-- PANEL BODY -->
               </div>
             </div>
           </div>
         </div>
         @include('modals.viewKpiReport')
         <hr>
         @endforeach
         @else
             <h3>[No Matching Records Found.]</h3>

         @endif

         @include('modals.kpiProgressReport')

         <script type="text/javascript">
           function progressReport(kpi_id,deliverable){
             $('#review').modal('show');
              $('#progressdel').text(deliverable);
                sessionStorage.setItem('progkpiid',kpi_id);
                sessionStorage.setItem('deliverable',deliverable);
           }
         </script>