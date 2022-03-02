<script>
    var loanTypeGrade = (function(doFetch,mixin){


        return function(){
            //bindInputs()
            //doFetch()
            //doLoop()
            //doStore()
            //doUpdate()
            //doDelete()

            var $root = null;

            return {


                mount($el){

                    var template = `
                 <div id="root">
                    <span id="grade"></span>
                    <span id="list"></span>
                 </div>
                `;

                    $root = $(template);

                    $el.html('');
                    $el.append($root);

                    this.fetchGrade();

                    var $each = `<!--<button class="btn btn-sm btn-success"></button>-->`;

                },

                loadGrades(list){
                    // alert('Called...');
                    var $el = $(`<select class="form-control" style="margin-bottom: 5px;"></select>`);
                    $el.html('');
                    $el.append(`<option value="">--Select Grade--</option>`);
                    list.forEach((item)=>{

                        console.log(item);

                        $el.append(`<option value="${item.id}">${item.level}</option>`);

                    });

                    $el.on('change',(evt)=>{

                        if ($el.val() == '')return;
                        this.appendGrade({
                            id:$el.val(),
                            level:$el.find(':selected').text()
                        });

                    });

                    $root.find('#grade').html('');
                    $root.find('#grade').append($el);

                    console.log($root,'ROOT');

                },

                fetchGrade(){
                    // var url = '';
                    var url = '{{ route('ajax.get.component',['Grade']) }}?company_id={{ companyId() }}';


                    mixin.doFetch(url).then((response)=>{

                        this.loadGrades(response.list);

                        // var $el = $(`<select></select>`);
                        // $el.html('');
                        //
                        // response.list.forEach((item)=>{
                        //
                        //     console.log(item);
                        //
                        //     $el.append(`<option value="${item.id}">${item.level}</option>`);
                        //
                        // });
                        //
                        // $el.on('change',(evt)=>{
                        //
                        //     this.appendGrade({
                        //         id:$el.val(),
                        //         level:$el.find(':selected').text()
                        //     });
                        //
                        // });
                        //
                        //
                        // $root.find('#grade').html('');
                        // $root.find('#grade').append($el);
                    });


                },

                loadGradeList(list){
                    list.forEach((item)=>{
                        this.appendGrade(item);
                    });
                },

                appendGrade(item){
                    var $el = $(`<span><button class="btn btn-sm btn-primary">Grade - ${item.level}</button>
                   <input type="hidden" name="grade_id" data-array value="${item.id}" />
                   <span id="delete" style="cursor: pointer;">X</span>
</span>`);
                    $root.find('#list').append($el);

                    $el.find('#delete').on('click',()=>{
                        $el.remove();
                    });
                },

                initBinding(){

                }


            };
//<button type="button"></button>
        };









    })(doFetch_,mixinCrud);
</script>