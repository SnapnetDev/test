<script>

    (function ($) {

        $(function(){


            var requests = {!! json_encode(request()->all())  !!};

            for (var i in requests){
                if (requests.hasOwnProperty(i)){
                    $('[name="' + i + '"]').val(requests[i]);
                }
            }

            //console.log(requests);


        });

    })(jQuery);


</script>