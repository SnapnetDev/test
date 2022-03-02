<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addCompanyModal" aria-hidden="true"
     aria-labelledby="addCompanyModal" role="dialog" tabindex="-1">
    <div class="modal-dialog ">
        <form class="form-horizontal" id="addCompanyForm" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Add New Company</h4>
                </div>
                <div class="modal-body">
                    <div class="row row-lg col-xs-12">
                        <div class="col-xs-12">
                            @csrf
                            <div class="form-group">
                                <h4 class="example-title">Name</h4>
                                <input type="text" name="name" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <h4 class="example-title">Email</h4>
                                        <input type="text" name="email" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <h4 class="example-title">Manager</h4>
                                        <select class="form-control" name="user_id">
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <h4 class="example-title">Biometric Serial</h4>
                                        <input type="number" min="0" name="biometric" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <h4 class="example-title">Colour</h4>
                                        <input type="text" name="color" class="jscolor form-control" value="#03a9f4">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <h4 class="example-title">Address</h4>
                                        <textarea class="form-control" name="address" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <h4 class="example-title">Logo</h4>
                                        <img src="" width="100" height="100" id='img-uploade'>

                                        <div class="input-group">
                                          <span class="input-group-btn">
                                              <span class="btn btn-small btn-file">
                                                  Browse… <input type="file" id="imgInp" name="logo" accept="image/*">
                                              </span>
                                          </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix hidden-sm-down hidden-lg-up"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-12">
                        <div class="form-group">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-info pull-left">Save</button>
                            <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                        </div>
                        <!-- End Example Textarea -->
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>