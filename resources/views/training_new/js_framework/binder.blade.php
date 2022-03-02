<script>

    // alert('seen...');

    (function($){

        $(document).ajaxStart(function(){

        });

        $(document).ajaxComplete(function(){

        });


        var globalListeners = {};

        var $helpers = {};

        var $pubsub = {
            publish:function(subject,data){
                if (globalListeners[subject]){
                    globalListeners[subject].forEach(function($listener,index){
                        $listener(data);
                    });
                }
            },
            subscribe:function(subject,cb){
                globalListeners[subject] = globalListeners[subject] || [];
                globalListeners[subject].push(cb);
            }
        };

        $helpers['pubsub'] = $pubsub;


        function scanContext($parent){
            $parent.find('[data-context]').each(function(){



                scanContextDataScope($(this),{},{});

            });
        }

        function scanContextDataScope($parent,data,observer){


            function notify(key,newVal){

                // console.log(key,observer[key]);
              if (observer[key]){
                  observer[key].forEach(function(v,k){
                      v(newVal);
                  });
              }else{
                  console.log('Observer key not found',key,observer);
                  if (observer['$others']){
                      observer['$others'].forEach(function(v,k){
                          v(newVal);
                      });
                  }
              }

            }


            function makeVar(config){

                var readonly = false;
                var value = null;
                var name  = null;

                // if (config.readonly){
                readonly = config.readonly;
                // }

                value = config.value;

                name = config.name;


                return {
                    get:function(){
                        return value;
                    },
                    set:function(vl){
                       if (!readonly){
                           value = vl;
                           notify(name,vl);
                       }else{
                           throw "Readonly value : " + name;
                       }
                    },
                    notify:function(tag){
                        notify(tag);
                    }
                };

            }

            function makeExpression(expr){
                return new Function('arg1,arg2,arg3,arg4,arg5,arg6,arg7',expr);
            }

            $parent.find('[data-store]').each(function(){

                var storeR = $(this).attr('data-store').split('=');
                var left = storeR[0].trim();
                var right = storeR[1];
                // data[left] = makeExpression('with(arg1){ return ' + right + '}')(data);
                data[left] = makeVar({
                    name:left,
                    readonly:false,
                    value:makeExpression('with(arg1){ return ' + right + '}')(data)
                });

            });

            $parent.find('[data-var]').each(function(){
                var key = $(this).attr('data-var');
               data[key] = makeVar({
                   name:key,
                   value:$(this),
                   readonly:true
               });
            });

            $parent.find('[data-html]').each(function(){
                // alert('called...');
                var left = $(this).attr('data-html');
                $(this).html(data[left].get());
                var $this = $(this);
                observer[left] = observer[left] || [];
                observer[left].push(function(vl){
                    if (!isNaN(vl)){
                      vl = vl * 1;
                      vl = vl.toLocaleString();
                    }
                    $this.html(vl);
                });
            });

            $parent.find('[data-val]').each(function(){
                var key = $(this).attr('data-val');
                var left = key;
                var $this = $(this);

                $(this).val(data[key].get());

                if ($(this).is('[type="checkbox"]')){

                    observer[left] = observer[left] || [];
                    observer[left].push(function(vl){

                        $this.prop('checked',vl);

                    });

                    notify(key,data[key].get());


                    $(this).on('click',function(){
                        // data[key] = $(this).is(':checked');
                        data[key].set($(this).is(':checked'));
                        // data[key] = makeVar({
                        //     name:key,
                        //     readonly:false,
                        //     value:$(this).is(':checked')
                        // });

                        notify(key,data[key].get());

                    });


                }

                if ($(this).is('[type="radio"]')){

                    observer[left] = observer[left] || [];
                    observer[left].push(function(vl){

                        $this.prop('checked',vl);

                    });

                    notify(key,data[key].get());


                    $(this).on('click',function(){
                        // data[key] = $(this).is(':checked');
                        data[key].set($(this).is(':checked'));
                        // data[key] = makeVar({
                        //     name:key,
                        //     readonly:false,
                        //     value: $(this).is(':checked')
                        // });
                        notify(key,data[key].get());
                    });

                }


                if ($(this).is('[type="text"]') || $(this).is('textarea')) {

                    $(this).on('keyup', function () {

                        data[key].set($(this).val());

                        notify(key, $(this).val());

                    });


                    observer[left] = observer[left] || [];
                    observer[left].push(function(vl){


                        $this.val(vl);

                    });

                }

            });

            $parent.find('[data-text]').each(function(){
                // alert($(this).attr('data-text'));
                var $this = $(this);
                var left = $(this).attr('data-text');

                console.log(data);
                // alert(left);


                var vl_ = data[left].get();
                if (!isNaN(vl_)){
                    vl_ = vl_ * 1;
                    vl_ = vl_.toLocaleString();
                    console.log(vl_,'fmt',left);
                }else{
                    console.log(vl_,'nnfmt',left);
                }


                $(this).text(vl_);
                observer[left] = observer[left] || [];
                // console.log(observer);
                observer[left].push(function(vl){
                    if (!isNaN(vl)){
                        vl = vl * 1;
                        vl = vl.toLocaleString();
                        console.log(vl,'fmt',left);
                    }else{
                        console.log(vl,'nnfmt',left);
                    }

                    $this.text(vl);
                });

            });

            $parent.find('[data-show]').each(function(){
                var $this = $(this);
                var left = $this.attr('data-show');


                observer[left] = observer[left] || [];
                observer[left].push(function(vl){
                    if (data[left].get()){
                        $this.show();
                    }else{
                        $this.hide();
                    }
                });
                console.log(data);

                notify(left,data[left].get());


            });

            $parent.find('[data-custom]').each(function(){

                var $this  = $(this);

                makeExpression('return (function(data,$el){  return ' + $this.attr('data-custom') + '(data,$el);})(arg1,arg2)')(data,$this);


                observer['$others'] = observer['$others']  || [];
                observer['$others'].push(function(vl){
                    makeExpression('return (function(data,$el){  return ' + $this.attr('data-custom') + '(data,$el);})(arg1,arg2)')(data,$this);
                });


            });

            $parent.find('[data-href]').each(function(){

                var $this = $(this);
                var left = $this.attr('data-href');

                observer[left] = observer[left] || [];
                observer[left].push(function(vl){
                    $this.attr('href',vl);
                });

                notify(left,data[left].get());

            });

            $parent.find('[data-evt]').each(function(){

                var xconfig = makeExpression('return ' + $(this).attr('data-evt') + '(arg1);')(data);

                // data[$(this).attr('data-var')] = $(this);
                // var evtR = $(this).attr('data-evt').split('|');
                var eventName = xconfig.event;  //evtR[0];
                var eventExpr = xconfig.cb;  //evtR[1];
                $(this).on(eventName,function(){
                    console.log(data);
                    // makeExpression('with(arg1){' + eventExpr + '}')(data);
                    eventExpr($helpers,data);

                });
            });


            $parent.find('[data-loop]').each(function(){

                // alert('ok');

                var $this = $(this);
                var $outlet = $this.find('#outlet');

                var key = $this.attr('data-loop');



                // alert(key);

                observer[key] = observer[key] || [];

                observer[key].push(function(arr){

                    $outlet.html('');

                    arr.forEach(function(v,k){



                        if (typeof v != 'object'){
                           v = {
                               $data:makeVar({
                                   name:'$data',
                                   value:v,
                                   readonly:false
                               })
                           };
                        }else{
                            for (var i in v){
                                if (v.hasOwnProperty(i)){
                                    v[i] = makeVar({
                                        name:i,
                                        value:v[i],
                                        readonly:false
                                    });
                                }
                            }
                        }

                        // console.log(v);

                        var $template = $($this.find('#template').html());
                        $outlet.append($template);
                        console.log($template);
                        scanContextDataScope($template,v,{});

                    });



                });

                console.log(data[key].get());

                notify(key,data[key].get());

            });


            $parent.find('[data-ajax-get]').each(function(){

                var $this = $(this);

                var old = '';

                var xconfig = makeExpression( 'return ' + $this.attr('data-ajax-get') + '(arg1);')(data);

                if (xconfig.init){
                    xconfig.init($helpers,xconfig);
                }


                function wrapFn($el){

                    // xconfig = makeExpression( 'return ' + $this.attr('data-ajax-get') + '(arg1);')(data);

                    if ($el){
                        $old = $el.html();
                    }

                    var url_ = xconfig.url; //makeExpression('with(arg1){ return ' + url + ';}')(data);
                    data__ = xconfig.data; // makeExpression('with(arg1){ return ' + data_ + ';}')(data);

                    // alert(url);

                    if ($el)$el.html('Loading...');

                    $.ajax({
                        url:url_,
                        type:xconfig.type,
                        data:data__,
                        success:function(response){
                            if ($el)$el.html(old);
                            xconfig.transform(response);
                            // makeExpression('var response = arg2; with(arg1){' + transform + '}')(data,response);

                        }
                    });
                }

                if (xconfig.$ref){
                    data[xconfig.$ref] = makeVar({
                        name:xconfig.$ref,
                        value:function(){
                            return wrapFn()
                        },
                        readonly:true
                    });
                }


                if ($this.is('button') || $this.is('[type="button"]')){
                    $this.on('click',function(){
                        wrapFn();
                        return false;
                    });
                }else{
                    wrapFn();
                    observer[xconfig.tag] = observer[xconfig.tag] || [];
                    observer[xconfig.tag].push(function(vl){

                        wrapFn();

                    });
                }



            });

        }






        $(function(){


            scanContext($('body'));


        });


    })(jQuery);






</script>