<script>


    var $approvalList = (function($mixin,$approvalForm){


        var $template;



        return {


            attachBindings(){
              $('[data-loan-request]').each((key,$el)=>{

                  var $el = $($el);
                  var vl = $el.attr('data-loan-request');

                  console.log(vl,$el); // vl

                  $el.off('click');

                  $el.on('click',(evt)=>{
                      $('#loan-approvals').modal();
                      this.loadApprovals(+vl);
                      // alert('clicked...');
                      // loan-approvals
                  });

              });
            },

            loadApprovals(loan_request_id){

                var $el = $(`<table class="table">

                    <tr>
                      <th>
                         Stage
                      </th>
                      <th>
                        Comment
                      </th>
                      <th>
                        Status
                      </th>
                      <th>
                        Approved
                      </th>
                      <th>
                        Approved By
                      </th>
                      <th>
                        Date
                      </th>
                      <th>
                        Actions
</th>

</tr>

                    </table>`);


                $mixin.doFetch('{{ route('loan.approval.get') }}?loan_request_id=' + loan_request_id).then((response)=>{

                    response.list.forEach((item)=>{

                        var $each = $(`<tr>
                      <td>
                         ${item.stage.name}
                      </td>
                      <td>
                        ${item.comment? item.comment : 'N/A'}
                      </td>
                      <td>
                        ${item.status}
                      </td>
                      <td>
                        ${item.status == 1? 'Yes':'Pending'}
                      </td>

                      <td>
                        ${item.approver? item.approver.name:'Pending'}
                      </td>

                      <td>
                        ${item.created_at}
                      </td>
                      <td>
                        <a id="review" href="#" class="btn btn-sm btn-success">Review</a>
                        <span id="completed" style="color: green;font-weight: bold;" class="fa fa-check">&nbsp;</span>
                      </td>


</tr>`);


                        $each.find('#completed').hide();
                        if (item.status === 1){
                            $each.find('#review').hide();
                            $each.find('#completed').show();
                        }

                        $each.find('#review').on('click',(evt)=>{

                            //
                            $approvalForm.attachBind(item,()=>{
                                this.loadApprovals(loan_request_id);
                            });

                        });

                        $el.append($each);

                    });


                    $template.find('#outlet').html('');
                    $template.find('#outlet').html($el);

                });


            },

            mount($el){

                $template = $(`
<div id="loan-approvals" class="modal fade" role="dialog">
    <div class="modal-dialog modal-info modal-lg">

                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Approval List</h4>
                        </div>
                    <div class="modal-body" >

                         <div id="outlet"></div>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>

                    </div>
</div>
                    `);

                $el.html('');
                $el.append($template);

                this.attachBindings();

            }




        };


    })(mixinCrud,approvalForm);


</script>