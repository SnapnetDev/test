<script>
    (function($){

        $.fn.selectData = function(settings){
            settings = $.extend({
                url:'url',
                key:'id',
                label:'name'
            },settings);
            var $this = this;

            fetch(settings.url,{
                method:'GET'
            }).then((res)=>res.json()).then((res)=>{
                if (res.list){
                    res.list.forEach(function(v,k){
                        $this.append(`<option value="${v[settings.key]}">${v[settings.label]}</option>`);
                    });
                }
            });
        };

        $.fn.selectAppend = function(settings){

            settings = $.extend({
                transform:function (val) {
                    return `<span style="display: inline-block;paddding: 3px;background-color: #eee;">Appended</span>`;
                },
                $elOther:'#other'
            },settings);

            // $.fn.selectAppedLoad  = function(config){
            // };

            $(this).on('change',function(){
                // alert('called');
                console.log(settings.$elOther);
                var name = $(this).find('option:selected').text();
                var vl = $(this).val();
                // alert(vl);

                if (!vl){
                    settings.$elOther.html('');
                    return;
                }

                var $delBtn = $(`<span style="cursor: pointer;">&nbsp;X&nbsp;</span>`);
                var $result = $(settings.transform({
                    label:name,
                    value:vl
                }));

                $delBtn.on('click',function () {
                    $result.remove();
                });

                $result.append($delBtn);

                console.log($result);
                settings.$elOther.append($result);
            });

        };

        $.fn.useTemplate = function(){

            var html = this.html();

            return {

                mount:function(sel){
                    var $el = $(sel);
                    $el.html(html);
                    return $el;
                }

            };

        };

        $.fn.formSubmit = function(settings){

            settings = $.extend({

                method:'POST',
                url:'',
                loading:function(){},
                loaded:function(){}

            },settings);


            var frmData = new FormData($(this).get(0));
            frmData.append('_method',settings.method);

            frmData.append('_token','{{ csrf_token() }}');

            settings.loading();

            fetch(settings.url,{
                method:'POST',
                // headers:settings.headers,
                body:frmData
            }).then((res)=>res.json()).then((res)=>{

                if (res.error){
                    toastr.error(res.message);
                    return;
                }

                // closeEditModal();
                settings.loaded();
                toastr.success(res.message);
                // renderTable();

            });

        };

        $.fn.crudTable = function(settings){

            var $obj = this.get(0);

            var $ref = {};

            // if ($obj.bound)return;




            /**
             * Settings config
             *
             * tableSelector
             * createModalFormButtonSelector
             * createModalFormSelector
             * editModalFormSelector
             * fetchUrl
             * createUrl
             * updateUrl
             * deleteUrl
             *
             * ---Events----
             * onSelectRow
             * --Mutators----
             * onAppendRow
             *
             */

            settings = $.extend({
                //  tableSelector:'table',
                //  createModalFormButtonSelector:'#create-modal-button',
                createModalFormSelector:'#create-modal',
                editModalFormSelector:'#edit-modal',
                fetchUrl:function(){return 'fetch-url';},
                createUrl:function(){return 'create-url';},
                updateUrl:function(data){return 'update-url';},
                deleteUrl:function(data){return 'delete-url';},
                onSelectRow:function($el,data){},
                onAppendRow:function($el,data){},
                onFillForm:function($el,data){},
                onAjaxFinished:function(){},
                headers:{
                    "X-CSRF-TOKEN": '{{ csrf_token() }}',
                    "Content-Type": "application/json"
                }
            },settings);

            var $tableSelector = this.find('table');   //$(settings.tableSelector);
            var $createFormButtonSelector =  this.find('[data-create-form]'); //$(settings.createModalFormButtonSelector);
            var $createModalFormSelector = $(settings.createModalFormSelector);
            var $editModalFormSelector = $(settings.editModalFormSelector);

            // if ($obj.bound){
            //     return;
            // }



            var $tableSlot = $tableSelector.html();
            $tableSelector.html('');

            $createFormButtonSelector.on('click',function(){
                $createModalFormSelector.modal();
            });

            var $createForm = $createModalFormSelector.find('form');

            settings.onFillForm($createModalFormSelector,{});

            $createForm.off('submit');
            $createForm.on('submit',function(){

                $createForm.formSubmit({
                    method:'POST',
                    url:settings.createUrl(),
                    loading:function(){
                        toastr.info('Submitting...');
                    },
                    loaded:function(){
                        closeCreateModal();
                        renderTable();
                    }
                });

                return false;

            });

            function renderTable(){

                console.log($ref);

                $filterEl = $ref.hook('filter_container')();
                fetch(settings.fetchUrl() + $ref.hook('filter')($filterEl),{
                    method:'GET',
                    headers:settings.headers
                }).then((res)=>res.json()).then((res)=>{

                    $tableSelector.html('');
                    $tableSelector.append($tableSlot);

                    if (res.list){
                        res.list.forEach(function(v,k){
                            var $el = $(settings.onAppendRow(v));
                            var data = v;
                            settings.onSelectRow($el,data,function(){ //show edit form
                                fillUpEditForm(data);
                                $editModalFormSelector.modal();
                            },function(){ //remove record
                                removeRecord(data);
                            });
                            $tableSelector.append($el);
                        });
                    }

                    settings.onAjaxFinished();

                });
            }

            function removeRecord(data){
                toastr.info('Removing ... ');
                fetch(settings.deleteUrl(data),{
                    method:'POST',
                    body:JSON.stringify({
                        _method:'DELETE'
                    }),
                    headers:settings.headers
                }).then((res)=>res.json()).then((res)=>{
                    if (res.error){
                        toastr.error(res.message);
                        return;
                    }
                    toastr.success(res.message);
                    renderTable();
                });
            }

            function getFormData($el){
                var data = {};
                data._token = '{{ csrf_token() }}';
                $el.find('[data-input]').each(function(){

                    var key,value;

                    key = $(this).attr('name');
                    value = $(this).val();

                    if ($(this).is('[type=checkbox]') || $(this).is('[type=radio]')){

                        value = undefined;

                        if ($(this).is(':checked')){
                            value = $(this).val();
                            // data[$(this).attr('name')] = $(this).val();
                        }
                        // return;
                    }

                    if ($(this).is('[data-array]')){
                      data[key] = data[key] || [];
                      data[key].push(value);
                      return;
                    }

                    if (value != undefined){
                        data[key] = value;
                        return;
                    }

                    // data[key] = value;

                    // data[$(this).attr('name')] = $(this).val();
                });
                console.log('form-data',data);
                return data;
            }

            function closeEditModal(){
                $editModalFormSelector.find('[data-dismiss]').trigger('click');
            }

            function closeCreateModal(){
                $createModalFormSelector.find('[data-dismiss]').trigger('click');
            }

            function fillUpEditForm(data){

                for (var i in data){
                    (function($i,$data){

                        var $el = $editModalFormSelector.find('[name=' + $i + ']');
                        // console.log($el);
                        $el.val($data);
                        // console.log($data,$i,$el,$el.is('[type=checkbox]'));
                        if (($el.is('[type=checkbox]') || $el.is('[type=radio]'))){
                            $el.prop('checked',false);
                            // console.log(data[i],i,$el);
                            $el.val(1);
                            if ($data*1 == 1){
                                $el.prop('checked',true);
                                // console.log('checked',$el);
                            }
                        }

                        if ($el.is('select')){
                            $el.trigger('change');
                        }

                    })(i,data[i]);

                }

                settings.onFillForm($editModalFormSelector,data);


                var $updateForm = $editModalFormSelector.find('form');
                $updateForm.off('submit');
                $updateForm.on('submit',function(){

                    $updateForm.formSubmit({
                      method:'PUT',
                      url:settings.updateUrl(data),
                      loading:function(){
                          toastr.info('Saving ... ');
                      },
                      loaded:function(){
                          closeEditModal();
                          // toastr.success(res.message);
                          renderTable();
                      }
                    });

                    return false;
                });

                // $editModalFormSelector.find();

            }








            var $listeners = {};
            // var $ref = {};

            $ref = {

                hook(evt,cb){

                    if (cb){
                        $listeners[evt] = cb;
                        return $ref;
                    }

                    if ($listeners[evt]){
                        return $listeners[evt];
                    }

                    return function(){ return '';};

                },

                setFetchUrl:function(url){

                    settings.fetchUrl = function(){
                        return url;
                    };

                    renderTable();
                },
                refresh:function(){
                    renderTable();
                }
            };

            $ref.hook('init',function(){
                var $filterEl = $ref.hook('filter_container')();
                $ref.hook('filter_init')($filterEl,renderTable);
            });


            renderTable();


            return $ref;

        };





        //MVVM (Mv2M)
        class Mv2M{


            constructor($this){
                this.listeners = {};
                this.loaded = false;
                this.$el = $($this.html());
            }

            init(){

               if (!this.loaded){
                   this.$headerText = this.$el.find(this.hook('container')()).html();
                   this.$container = this.$el.find(this.hook('container')());
                   this.loaded = true;
               }
                // this.fetch();
                console.log(this);

                var $createModal = this.hook('createModal')(this.$el);

                this.hook('onFillForm')($createModal,{});

                this.initFormSubmission();

                return this;

            }



            hook(evt,cb){
                if (cb){
                    this.listeners[evt] = cb;
                    return this;
                }

                if (this.listeners[evt]){
                    return this.listeners[evt];
                }

                return function(){return '';};
            }

            initFormSubmission(){

               var $createModal = this.hook('createModal')(this.$el);
               var $frm = $createModal.find('form');
               $frm.attr('action',this.hook('storeUrl')());
               var $editModal = this.hook('editModal')(this.$el);

               this.handleFormSubmissionEvent($frm);

               $frm = $editModal.find('form');

               this.handleFormSubmissionEvent($frm);

            }

            handleFormSubmissionEvent($frm){

                $frm.off('submit');
                $frm.on('submit',(evt)=>{
                    this.submitForm($frm.get(0));
                    return false;
                });
            }


            fetch(){

                //before.indexUrl
                this.hook('before.indexUrl')(this.$el);

                var $filterContainer = this.hook('filter_container')();
                var filter = this.hook('filter')($filterContainer);
                var indexUrl = this.hook('indexUrl')('?' + filter);

                // + '?' + filter
                this.callApi(indexUrl).then(res=>res.json()).then((res)=>{

                    if (res.error){
                        toastr.error(res.message);
                        return;
                    }

                    var list = res;

                    if (res.list){
                      list = res.list;
                    }

                    var $container = this.$container; // this.hook('container')();
                    $container.html('');

                    console.log(this.$headerText);

                    var $header = $(this.$headerText);

                    $container.append($header);

                    list.forEach((v,k)=>{

                        var $el = $(this.hook('onAppendRow')(v));
                        this.hook('onSelectRow')((sel)=>{ //find global
                            //$el
                            return this.$el.find(sel);

                        },(sel)=>{

                            return $el.find(sel);

                        },v,()=>{ //showEditForm
                             var $editModal = this.hook('editModal')(this.$el);

                             var $frm = $editModal.find('form');
                             $frm.attr('action',this.hook('updateUrl')(v));
                             // var $editModal = this.hook('editModal')(this.$el);

                             this.fillForm($editModal,v);
                             $editModal.modal();
                             this.hook('onFillForm')($editModal,v);
                        },($frm)=>{ //submitForm
                            this.submitForm($frm);
                        });

                        $container.append($el);

                    });

                });

                return this;
            }

            fillForm($el,data){

                // console.log($el,data);

                // window.gData = data;

                var that = this;


                for (var i in data){

                    (function(k,v){


                        if (data.hasOwnProperty(i)){

                            // console.log(i,data[i]);

                            if ($el.find(`[name=${i}]`).is('input') || $el.find(`[name=${i}]`).is('select') ||
                                $el.find(`[name=${i}]`).is('textarea')){
                                $el.find(`[name=${i}]`).val('');
                                console.log(i,v);
                                $el.find(`[name=${i}]`).val(v);
                                // console.log('found');
                                return;
                            }
                            $el.find(`[name=${i}]`).html('');
                            $el.find(`[name=${i}]`).html(v);
                            // console.log('html',i,data[i]);

                        }


                    })(i,data[i]);

                    // ((k,v)=>{
                    //     console.log(k,v);
                    // })(i,data[i]);

                    // console.log(i,data[i]);

                }

            }

            callApi(url){
                return fetch(url,{
                    method:'GET'
                });
            }

            submitForm($form){

                // console.log($form);
                // alert('0.0' + JSON.stringify($form));

                fetch($form.action,{
                    method:$form.method,
                    body:(new FormData($form))
                }).then((res)=>res.json()).then((res)=>{

                    if (res.error){
                        toastr.error(res.message);
                        return;
                    }

                    toastr.success(res.message);

                    this.fetch();

                    return;

                }).catch((res)=>{
                    toastr.error('Something went wrong!');
                    return;
                });

                // alert('1.0');

                return this;
            }

            el(){
              return this.$el;
            }

            mount(cb){
                cb(this.el());
                return this;
            }


        }



        $.fn.mv2m = function(){

            return new Mv2M($(this));

        };



    })(jQuery);



























</script>