<script>

    Vue.component('ajax-text',{

        props:['text'],

        methods:{
            getText:function(){
                return this.text;
            },

            textIsEmpty:function(){
                return this.getText() == '';
            }
        },

        template:`<span>
            <div v-text="getText()" v-show="!textIsEmpty()"></div>
            <div v-show="textIsEmpty()">
              <img src="{{ asset('loader.gif') }}" style="height: 20px;" alt="" />
</div>
</span>`


    });
</script>