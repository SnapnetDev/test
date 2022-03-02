<script>

    function doFetch_(url,type,data){
        return $.ajax({
            url:url,
            type:type,
            data:data
        });
    }


    //bindInputs()
    //doFetch()
    //doLoop()
    //doStore()
    //doUpdate()
    //doDelete()

    var mixinCrud = {

        bindInputs(cb){
            cb();
        },

        doFetch(url){
            return doFetch_(url,'GET',{});
        },

        doLoop(collection,cb){
            collection.forEach(cb);
        },

        doStore(url,data){
            data._token = '{{ csrf_token() }}';
            return doFetch_(url,'POST',data);
        },

        doUpdate(url,data){
            data._token = '{{ csrf_token() }}';
            data._method = 'PUT';
            return doFetch_(url,'POST',data);
        },

        doPost(url,data){
            data._token = '{{ csrf_token() }}';
            // data._method = 'PUT';
            return doFetch_(url,'POST',data);
        },

        doDestroy(url){
            data._token = '{{ csrf_token() }}';
            data._method = 'DELETE';
            return doFetch_(url,'POST',{});
        }

    };


</script>