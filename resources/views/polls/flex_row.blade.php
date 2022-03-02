<style>
    .row.is-flex {
        display: flex;
        flex-wrap: wrap;
    }
    .row.is-flex > [class*='col-'] {
        display: flex;
        flex-direction: column;
    }

    /*
    * And with max cross-browser enabled.
    * Nobody should ever write this by hand.
    * Use a preprocesser with autoprefixing.
    */
    .row.is-flex {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-flex-wrap: wrap;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
    }

    .row.is-flex > [class*='col-'] {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -webkit-flex-direction: column;
        -ms-flex-direction: column;
        flex-direction: column;
    }
    .row.make-columns {
        -moz-column-width: 19em;
        -webkit-column-width: 19em;
        -moz-column-gap: 1em;
        -webkit-column-gap:1em;
    }

    .row.make-columns > div {
        display: inline-block;
        padding:  .5rem;
        width:  100%;
    }

</style>