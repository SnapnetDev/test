
    <li><div class="form-cont" ><div class="form-group users-div"><label for="">Employee Name</label><select class="form-control users" name="user_id[]" >@forelse ($users as $user)<option value="{{$user->id}}">{{$user->name}}</option>@empty<option value="">No Users Created</option>@endforelse </select> </div><div class="form-group"> <button type="button" class="btn btn-icon btn-danger " id="remStage"><i class="fa fa-close"></i></button> </div> </div> </li>

