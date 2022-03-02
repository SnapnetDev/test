<div class="tab-pane animation-slide-left"  id="finger_prints" role="tabpanel">
    <br>
    <div class="row">
        @php($prints=\App\UserFingerPrint::where('user_id',$user->id)->orderBy('finger_no','asc')->get())
        <div class="col-lg-6 masonry-item">
            <div class="card card-shadow">
                <div class="card-header bg-blue-600 white p-15 clearfix">
                    <div class="font-size-18">{{ $user->name }} Finger prints ({{$prints->count()}})</div>
                </div>

                <ul class="list-group list-group-bordered mb-0">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Finger No</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($prints as $print)
                            <tr>
                                <td> {{ $print->finger_no }} </td>
                                <td>Delete</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </ul>
            </div>
        </div>
    </div>
</div>
