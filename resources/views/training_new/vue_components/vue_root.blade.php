<script>

    Vue.mixin({

        methods:{
            callAjax:function(config){
                var $this = this;
                // alert('Called ajax');
                // return;
                $.ajax({
                    url:config.url,
                    type:config.type,
                    data:config.data,
                    success:function (response) {
                        config.success.apply($this,[response]);
                    },
                    error:function(error){
                        config.error.apply($this,[{
                            message:'Error in connection',
                            error:true
                        }]);
                    }
                });
            }
        }

    });

    (new Vue({
        el:'#app'
    }));

</script>