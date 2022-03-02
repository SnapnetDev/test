<script>
    var $Binder = $Binder || {};
    $Binder.plugins = $Binder.plugins || [];
    (function($bnd,$){

        var $instances = [];

        var $globalListeners = {};

        function listen(evt,cb){
            var context = this; //,context
            $globalListeners[evt]= $globalListeners[evt] || [];
            $globalListeners[evt].push({
                cb:cb,
                context:context
            });
        }

        function publish(evt,data){
            var $this = this;
            if ($globalListeners[evt]){
                $globalListeners[evt].forEach(function(v,k){
                    // v.apply($this,[data]);
                    v.cb.apply(v.context,[data]);
                });
            }
        }

        function runBinderPlugins($el,$data,listeners){
            $bnd.plugins.forEach(function(v,k){
                v($el,$data,listeners,resolveBinding);
            });
        }

        function extendObject(from,to){

            for (var i in from){
                if (from.hasOwnProperty(i)){
                    to[i] = from[i];
                }
            }

            return to;
        }


        function ajax_(config){
            var $this = this;
            $.ajax({
                url:config.url,
                type:config.type,
                data:config.data,
                success:function(response){
                    // response = defineData($this,$listeners);
                    config.success.apply($this,[response]);
                },
                error:function(error){
                    config.error.apply($this,[error]);
                }
            });
        }

        function installCorePubSub(obj){
            obj.listen = listen;
            obj.publish = publish;
            return obj;
        }

        function installAjax(obj){
            obj.ajax = ajax_;
            return obj;
        }

        function notify(subject,data,listeners){
            for (var i in listeners){
                if (i.indexOf(subject) >= 0){
                    listeners[i].forEach(function(v,k){
                        v(data);
                    });
                }
            }
        }

        function isArray($v){
            return (typeof($v) == 'object' && typeof($v.length) == 'number');
        }

        function isObject($v){
            return (typeof($v) == 'object' && typeof($v.length) == 'undefined');
        }

        function transformArray(prp,$value,listeners){
            if (isArray($value)){
                console.log('is - array',prp,$value);
                $value = (function($v){
                    $v.push = function(vl){
                        var vl_ = {};

                        if (isObject(vl)){
                            vl_ = extendObject(vl,{});
                            vl_ = defineData(vl_,listeners);
                            // alert('called.');
                        }else{
                            vl_ = {$data:vl};
                            vl_ = defineData(vl_,listeners);
                        }

                        // var currentIndex = $v.length;
                        vl_.$removeMe = function(index){
                            // alert(currentIndex);
                            $v.splice(index,1);
                        };



                        console.log('notified');
                        var rr_ = Array.prototype.push.apply(this,[vl_]);
                        notify(prp,vl_,listeners);
                        return rr_;
                    };
                    $v.splice = function(index,count){

                        console.log('notified');
                        var rr_ = Array.prototype.splice.apply(this,[index,count]);
                        notify(prp,null,listeners);
                        return rr_;
                    };
                    return $v;
                })($value);

            }else{
                console.log('Not array',prp,$value);
            }
        }



        function defineData(data,listeners){

            for (var i in data){
                if (data.hasOwnProperty(i) && typeof(data[i]) != 'function'){

                    (function(prp,$data){

                        var value = null;

                        if ($data[prp] == null){
                            $data[prp] = '';
                        }


                        transformArray(prp,$data[prp],listeners);

                        value = $data[prp];


                        Object.defineProperty($data,prp,{

                            set:function(v){

                                transformArray(prp,v,listeners);

                                value = v;
                                notify(prp,value,listeners);

                            },
                            get:function(){
                                return value;
                            }

                        });


                    })(i,data);


                }
            }

            data = installCorePubSub(data);
            data = installAjax(data);

            return data;

        }

        function getExpression(expr){
            return new Function('a,b,c','with(a){ return function(){ return ' + expr + ' ;}.apply(a,[a,b,c])}');
        }

        function hasChildBinding($el,binding){
            return $el.find('[' + binding + ']').length;
        }


        function resolveBinding($el,binding,cb,listeners){

            $el.find('[' + binding + ']').each(function(){

                var $this = $(this);
                var bcheck = binding.split('-').join('_');

                if (!$this.attr('_bound_' + bcheck)){

                    $this.attr('_bound_' + bcheck,'_bound');

                    var expressionString = $this.attr(binding);
                    var expression = getExpression(expressionString);

                    listeners[expressionString] = listeners[expressionString] || [];
                    // listeners[expressionString].push();

                    cb({
                        expressionString:expressionString,
                        expression:expression,
                        $el:$this,
                        listen:function(cb){
                            console.log(listeners,expressionString);
                            cb(); //init first
                            listeners[expressionString].push(cb);
                        }
                    });


                }

            });
        }

        var attrSelectors = ['name','id','style','class','value','disabled','readonly','checked','href','for'];
        function bindAttrs($el,data,listeners){
            attrSelectors.forEach(function(v,k){

                resolveBinding($el,('b-' + v),function(config){

                    config.listen(function(){

                        config.$el.attr(v,config.expression(data));
                        console.log(v,config.expression(data));

                    });


                    // config.$el.attr(v,config.expression(data));

                },listeners);




            });
        }

        function bindSync($el,data,listeners){
            resolveBinding($el,'b-sync',function(config){

                if (config.$el.is('[type="checkbox"]') || config.$el.is('[type="radio"]')){
                    config.$el.on('click',function(){
                        if ($(this).is(':checked')){
                            if ($(this).attr('valueNotNull')){
                                data[config.expressionString] = getExpression($(this).attr('valueNotNull'))(data);
                            }else{
                                data[config.expressionString] = getExpression($(this).attr('value'))(data);
                            }
                        }else{
                            data[config.expressionString] = $(this).attr('valueNull')? getExpression($(this).attr('valueNull'))(data) : null;
                        }
                    });

                    config.listen(function(v){

                        config.$el.val(data[config.expressionString]);

                    });

                }else if (config.$el.is('select')){

                    config.$el.on('change',function(){
                        data[config.expressionString] = {text:$(this).children("option").filter(":selected").text(),val:$(this).val()}; // $(this).val();
                    });


                    config.listen(function(v){

                        config.$el.val(data[config.expressionString].val);

                    });

                }else{

                    config.$el.on('keyup',function(){
                        data[config.expressionString] = $(this).val();
                    });

                    config.listen(function(v){

                        config.$el.val(data[config.expressionString]);

                    });

                }





            },listeners);
        }

        function bindEvent($el,data,listeners){
            resolveBinding($el,'b-on',function(config){

                var expr = config.expression(data);

                // alert('on');
                // console.log(expr);
                config.$el.on(expr[0],function(){
                    data.$el = config.$el;
                    expr[1].apply(data,[data]);

                });


            },listeners);
        }

        function bindCss($el,data,listeners){
            resolveBinding($el,'b-css',function(config){

                config.listen(function(){

                    config.$el.css(config.expression(data));

                });

                // config.$el.css(config.expression(data));

            },listeners);
        }

        function bindShow($el,data,listeners){
            resolveBinding($el,'b-show',function(config){

                config.listen(function(vl){

                    console.log(config.expression(data),config.expressionString,config.$el);
                    if (config.expression(data)){
                        // alert(1);
                        config.$el.show();
                    }else{
                        // alert(3);
                        config.$el.hide();
                    }

                });

            },listeners);
        }

        function bindHide($el,data,listeners){
            resolveBinding($el,'b-hide',function(config){


                config.listen(function(vl){

                    if (config.expression(data)){
                        config.$el.hide();
                    }else{
                        config.$el.show();
                    }

                });

            },listeners);
        }

        function bindLoop($el,data,listeners){
            resolveBinding($el,'b-loop',function(config){

               var templateString = config.$el.find('script').html();

               // console.log(templateString);

               config.listen(function(){

                   config.$el.html('');

                   var list = config.expression(data);

                   list.forEach(function(value,key){

                       var $innerEl = $(templateString);
                       config.$el.append($innerEl);
                       var $listeners = {};
                       value.$index = key;
                       value.$removeMe = function(index){
                           // alert(currentIndex);
                           list.splice(index,1);
                       };

                       var $value = defineData(value,$listeners);
                       console.log($value);
                       // $bnd.scan($innerEl,$value,$listeners);
                       // bindPrimitives($innerEl,$value,$listeners);

                       scanContext(config.$el,$value,$listeners);

                   });

               });


            },listeners);
        }

        function bindSlideUp($el,data,listeners){

            resolveBinding($el,'b-slide-up',function(config){

                config.listen(function(){

                    if (config.expression(data)){
                        config.$el.slideUp();
                    }else{
                        config.$el.slideDown();
                    }

                });


            },listeners);

        }

        function bindSlideDown($el,data,listeners){
            resolveBinding($el,'b-slide-down',function(config){

                config.listen(function(){

                    if (config.expression(data)){
                        config.$el.slideDown();
                    }else{
                        config.$el.slideUp();
                    }

                });


            },listeners);

        }

        function bindChecked($el,data,listeners){
            resolveBinding($el,'b-checked-state',function(config){

                config.$el.on('click',function(){

                    data[config.expressionString] = $(this).is(':checked');

                });

                config.listen(function(){

                    config.$el.prop('checked',config.expression(data));

                });

            },listeners);
        }


        function bindText($el,$data,listeners){
            resolveBinding($el,'b-text',function(config){

                config.listen(function(){

                    config.$el.text(config.expression($data));

                });

            },listeners);
        }


        function bindHtml($el,$data,listeners){
            resolveBinding($el,'b-html',function(config){

                config.listen(function(){

                    config.$el.html(config.expression($data));

                });

            },listeners);
        }

        function bindPrimitives($el,$data,listeners){
            bindAttrs($el,$data,listeners);

            bindCss($el,$data,listeners);

            bindEvent($el,$data,listeners);

            bindSync($el,$data,listeners);

            bindShow($el,$data,listeners);

            bindHide($el,$data,listeners);

            bindSlideUp($el,$data,listeners);

            bindSlideDown($el,$data,listeners);

            bindChecked($el,$data,listeners);

            bindText($el,$data,listeners);

            bindHtml($el,$data,listeners);

            runBinderPlugins($el,$data,listeners);

            bindLoop($el,$data,listeners);


        }

        function scanContext($el,data,listeners){

            // data = defineData(data,listeners);

            resolveBinding($el,'b-context',function(config){


                var $data = config.expression(data);

                console.log($data);

                $data = extendObject(data,$data);

                $data = defineData($data,listeners);

                if ($data.init){
                    $data.init();
                }

                $instances.push($data);

                bindPrimitives(config.$el,$data,listeners);



            },listeners);

            // bindAttrs($el,data,listeners);


        }


        $bnd.scan = scanContext;
        $bnd.$instances = $instances;

        $(function(){

            $bnd.scan($('body'),{},{});

        });

    })($Binder,jQuery);
</script>