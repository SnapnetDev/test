<script>
    $('#so_do_you_smoke').bootstrapToggle({
        on: 'I Smoke',
        off: "I don't smoke",
        onstyle:'info',
        offstyle:'default',
        width:'50%'
    });
    $('#so_illegal_drugs').bootstrapToggle({
        on: 'I take Drugs',
        off: "I don't take Drugs",
        onstyle:'info',
        offstyle:'default',
        width:'50%'
    });
</script>
<script>
    function saveMedicalHistory(type){
        formData = {
            _token: '{{csrf_token()}}',
            social_history:{}
        };
        if (type=='cmc'){
            formData.date=$('#cmc_date').val();
            formData.name=$('#cmc_name').val();
        }
        else if(type=='pmc'){
            formData.date=$('#pmc_date').val();
            formData.name=$('#pmc_name').val();
        }
        else if(type=='sh'){
            formData.date=$('#sh_date').val();
            formData.name=$('#sh_name').val();
        }
        else if(type=='med'){
            formData.name=$('#med_name').val();
        }
        else if(type=='medall'){
            formData.name=$('#medall_name').val();
        }
        else if(type=='fam'){
            formData.name=$('#fam_name').val();
            formData.member=$('#fam_member').val();
        }
        else if(type=='so'){

            formData.social_history.do_you_smoke='no';
            formData.social_history.illegal_drugs='no';
            if ($('#so_do_you_smoke').is(":checked")) {
                formData.social_history.do_you_smoke='yes';
            }
            if ($('#so_illegal_drugs').is(":checked")) {
                formData.social_history.illegal_drugs='yes';
            }

            formData.social_history.cigarettes_per_day=$('#so_cigarettes_per_day').val();
            formData.social_history.cigarettes_per_week=$('#so_cigarettes_per_week').val();
            formData.social_history.alcoholic_drinks_per_day=$('#so_alcoholic_drinks_per_day').val();
            formData.social_history.alcoholic_drinks_per_week=$('#so_alcoholic_drinks_per_week').val();

            formData.social_history.minutes_of_exercise_per_day=$('#so_minutes_of_exercise_per_day').val();
            formData.social_history.exercise_days_week=$('#so_exercise_days_week').val();
            formData.social_history.hours_of_television_per_day=$('#so_hours_of_television_per_day').val();
            formData.social_history.fast_food_per_week=$('#so_fast_food_per_week').val();
        }
        formData.type= type;
        makeCall(type,formData)
    }

    function deleteMedicalHistory(type,id) {
        alertify.confirm('Are you sure you want to delete this Medical History?', function () {
            alertify.success('Processing this request. Please wait...');
            formData = {
                _token: '{{csrf_token()}}',
                type:type,
                id:id,
                task:'delete',
            };
            makeCall(type,formData)
        }, function () {
            alertify.error('Cancelled')
        })

    }

    function closeCollapse(type){
        $("#"+type+"Collapse").collapse('hide')
    }
    function makeCall(type,formData){
        $.ajax({
            url: '{{url('medical/store')}}',
            data: formData,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            success: function (data, textStatus, jqXHR) {
                closeCollapse(type)
                toastr.success("Changes saved Medical History Record", 'Success');
               setTimeout(function(){
                    window.location.hash="#medical_history";
                    window.location.reload();
                },2000);
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
        console.log(window.location);
        var tab = window.location.hash;
        $('#tabs').find('a[href="'+tab+'"]').trigger('click');
    });
</script>