<style>

    .mrating {
        unicode-bidi: bidi-override;
        direction: rtl;
        text-align: center;
    }

    /*.rating {*/
    /*unicode-bidi: bidi-override;*/
    /*direction: rtl;*/
    /*}*/

    .mrating > span {
        display: inline-block;
        position: relative;
        width: 2.1em;
        font-size: 26px;
    }
    .mrating > span:hover:before,
    .mrating > span:hover ~ span:before {
        content: "\2605";
        position: absolute;
    }

    .mrating > span.selected:before,
    .mrating > span.selected ~ span:before {
        content: "\2605";
        position: absolute;
    }


</style>