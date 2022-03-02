<script>


    var $Binder = {
        plugins:[]
    };

    $Binder.plugins.push(function($el,$data,listeners,$resolver){

        $resolver($el,'b-rating',function(config){

            config.listen(function(){

                config.$el.find('[data-rate]').each(function(){
                    $(this).removeClass('selected');
                });

                config.$el.find('[data-rate="' + config.expression($data) + '"]').addClass('selected');

            });

        },listeners);


    });

</script>