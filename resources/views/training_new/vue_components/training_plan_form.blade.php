<script>
    Vue.component('training-plan-form',{

        props:['cost_per_head',
            'number_of_enrollees',
            'grand_total',
            'name',
            'locked',
            'roles',
            'role_id',
            'departments',
            'dep_id',
            'year_of_training',
            'train_start',
            'train_stop',
            'groups',
            'training_groups',
            'status',
            'reason',
            'mode'],

        data: function(){
            return {
                version: '1.0',
                cost_per_head_: 0,
                number_of_enrollees_: 0,
                grand_total_:0,
                name_:'',
                roles_:[],
                role_id_:0,
                departments_:[],
                dep_id_:0,
                train_start_:'',
                train_stop_:'',
                groups_:[],
                group_list_:[],
                group_id_:null,
                training_groups_:[],
                show_reason:false
            };
        },

        mounted:function(){

            this.cost_per_head_ = this.cost_per_head;
            this.number_of_enrollees_ = this.number_of_enrollees;
            this.grand_total_ = this.grand_total;
            this.name_ = this.name;
            this.roles_ = JSON.parse(this.roles);
            console.log(this.roles_);
            this.role_id_ = this.role_id;
            this.departments_ = JSON.parse(this.departments);
            console.log(this.departments_);
            this.dep_id_ = this.dep_id;
            this.train_start_ = this.train_start;
            this.train_stop_ = this.train_stop;
            this.groups_ = JSON.parse(this.groups);
            var collection1 = JSON.parse(this.training_groups);

            var collection2 = [];
            collection1.forEach(function(v,k){
                collection2.push(v.group.id);
            });

            this.group_list_ = collection2;

        },
        methods:{

            removeFromGroupList:function(index){
                this.group_list_.splice(index,1);
            },
            addGroup:function(){
                // alert('added.');
                // console.log(this.group_id_);
                this.group_list_.push(this.group_id_);
                console.log(this.group_list_,'...');

            }

        },

        template:`<span><div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">
                                    Name Of Training
                                </label>
                                <input  :disabled="locked == 1" :readonly="locked == 1" type="text" name="name" v-model="name_" class="form-control" placeholder="Name Of Training" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">
                                    Cost Per Head
                                </label>
                                <input :disabled="locked == 1" :readonly="locked == 1" v-model="cost_per_head_" type="text" name="cost_per_head" class="form-control" placeholder="Cost Per Head" />
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">
                                    Number Of Enrollees
                                </label>
                                <input :disabled="locked == 1" :readonly="locked == 1" v-model="number_of_enrollees_" type="text" name="number_of_enrollees" class="form-control" placeholder="Number Of Enrollees" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">
                                    Grand Total
                                </label>
                                <label for="" class="form-control" v-text="(number_of_enrollees_ * cost_per_head_).toLocaleString()">
                                </label>

                            </div>
                        </div>
                    </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Training Start Date
                                    </label>
                                    <input :disabled="locked == 1" :readonly="locked == 1"  type="date" v-model="train_start_" name="train_start" class="form-control" placeholder="Training Start Date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Training Stop Date
                                    </label>
                                    <input :disabled="locked == 1" :readonly="locked == 1" type="date" v-model="train_stop_" name="train_stop" class="form-control" placeholder="Training Stop Date" />
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Year Of Training
                                    </label>
                                    <label :disabled="locked == 1" :readonly="locked == 1" for="" class="form-control" v-text="year_of_training"></label>

                                </div>
                            </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="">
                    Role
                </label>
                <select :disabled="locked == 1" :readonly="locked == 1" name="role_id" v-model="role_id_" id="" class="form-control">
                    <option value="">Select Role</option>
                    <option v-for="role in roles_" :value="role.id" v-text="role.name"></option>
                </select>

            </div>
        </div>
    </div>


                    <div class="row">


                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">
                                    Department
                                </label>

                                <select :disabled="locked == 1" :readonly="locked == 1" name="dep_id" v-model="dep_id_" id="" class="form-control">
                                    <option value="">Select Department</option>
                                    <option v-for="department in departments_" :value="department.id" v-text="department.name"></option>
                                 </select>

            </div>
        </div>

    </div>


<div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">
                                    Groups
                                </label>

                                <div class="col-md-12">

                                        <span v-for="(group_id_number,index) in group_list_">
                                         <span v-for="group in groups_">
                                           <span v-if="group.id == group_id_number">
                                        <div class="row" style="margin-bottom: 5px;margin-top: 5px;background-color: #eee;padding: 5px;">

                                           <div v-text="group.name" class="col-md-8"></div>
                                           <div class="col-md-4" style="text-align: right;padding: 0;">
                                              <button  type="button" class="btn btn-danger btn-sm" @click.prevent="removeFromGroupList(index)">X</button>
                                           </div>

                                        </div>
                                         <input name="groupId[]" type="hidden" :value="group_id_number" />
                                             </span>
                                          </span>
                                      </span>
                                </div>


    <select id="" class="form-control" v-model="group_id_">
        <option value="0">Select Group</option>
        <option v-for="group in groups_" :value="group.id" v-text="group.name"></option>
    </select>


    <div>
        <br />
        <button @click.prevent="addGroup()" type="button"> + Add Group</button>
    </div>

    </div>
    </div>

    </div>



                            <div class="row" v-show="status == 2">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">
                                            Reason For Rejection
                                        </label>
                                        <textarea readonly disabled name="reason" for="" class="form-control" v-text="reason"></textarea>
                                    </div>
                                </div>
                            </div>






                            <div class="row" v-show="(mode == 'hr') && status*1 != 1">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">
                                            Approve
                                            <input value="1" @click="show_reason = false" type="radio" name="status"  />
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">
                                            Reject
                                            <input value="2" @click="show_reason = true" value="2" type="radio" name="status"  />
                                        </label>

                                    </div>
                                </div>

                            </div>



                            <div class="row" v-show="show_reason">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">
                                            State Reason
                                        </label>
                                        <textarea name="reason" for="" class="form-control"></textarea>

                                    </div>
                                </div>
                            </div>



</span>`

    });


</script>
