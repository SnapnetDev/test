<form  action="{{ route('process.action.command',['update-user-training-plan']) }}" method="post" enctype="multipart/form-data">

    @csrf
    <input type="hidden" name="id" value="{{ $item->id }}" />

    <div id="update-my-training-plan{{ $item->id }}" class="modal fade" role="dialog">
        <div class="modal-dialog modal-info modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit My Training Plan</h4>
                </div>
                <div class="modal-body">


                    <div class="col-md-12">


                        <div class="row">


                            <div class="col-md-12">
                                <div class="form-group">


                                    {{--data-plugin="switchery"--}}
                                    <div class="checkbox-custom checkbox-primary">
                                        <input  type="checkbox" id="inputUnchecked" name="completed" value="1" {{ ($item->completed == 1)? ' checked="checked" ' : '' }} />
                                        <label style="
    font-weight: bold;
    text-transform: uppercase;
" for="inputUnchecked">Indicate Completion</label>
                                    </div>

                                    {{--<label for="" style="--}}
    {{--font-weight: bold;--}}
    {{--text-transform: uppercase;--}}
{{--">--}}
                                        {{--Indicate Completion--}}
                                        {{--<input type="checkbox" name="completed" value="1" {{ ($item->completed == 1)? ' checked="checked" ' : '' }} />--}}
                                    {{--</label>--}}

                                </div>
                            </div>



                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="" style="
    font-weight: bold;
    text-transform: uppercase;
">
                                        Feedback
                                    </label>
                                    <textarea name="feedback" class="form-control" id="" cols="30" rows="5">{{ $item->feedback }}</textarea>
                                </div>
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="" style="
    font-weight: bold;
    text-transform: uppercase;
">
                                        Rate Training
                                    </label>
                                    <div class="mrating" data-rate-box="{{ $item->rating }}" style="text-align: left;">

                                        <span data-rate="100">☆</span><span data-rate="80">☆</span><span data-rate="60">☆</span><span data-rate="40">☆</span><span data-rate="20">☆</span>

                                        <input type="hidden" name="rating" value="{{ $item->rating }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" style="
    font-weight: bold;
    text-transform: uppercase;
">
                                        Upload document to support
                                    </label>
                                    <label for="" class="form-control">
                                        <input type="file" name="upload1" />
                                    </label>
                                    @if (!empty($item->upload1))
                                        <a href="{{ asset('uploads/' . $item->upload1) }}">Download Attachment</a>
                                    @endif    

                                </div>
                            </div>
                        </div>




                    </div>



                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-sm">Update</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</form>

@include('training_new.rating_style')