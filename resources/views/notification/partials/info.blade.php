<div class="card card-shadow card-inverse white bg-facebook">
            <div class="card-block p-20 h-full">
              <h3 class="white m-t-0">{{isset($noti->data['subject'])?$noti->data['subject']:""}}</h3>
              
              <div class="m-t-30">
                <i class="icon {{isset($noti->data['icon'])?$noti->data['icon']:''}} font-size-40"></i>
                
              </div>
            </div>
          </div>

<section class="slidePanel-inner-section">
    <div class="card card-shadow card-inverse ">
            <div class="card-block p-20 h-full">
               <p>{!!isset($noti->data['message'])?$noti->data['message']:""!!}</p>
                      @if(isset($noti->data['details']))
                      {!!$noti->data['details']!!}
                      @endif
                    </div>
                    <br>
                    @if(isset($noti->data['action']))
                    <center>
                   <a href="{{$noti->data['action']}}" class="btn bg-facebook white btn-lg"> View {{isset($noti->data['type'])?$noti->data['type']:'Action'}}</a>
                 </center>
                   @endif
            </div>
          </div>
    <div >
    
     
  </section>