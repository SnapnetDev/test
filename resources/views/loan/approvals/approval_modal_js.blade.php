<script>

    var approvalForm = (function($mixin){

        var template;




        return {


            mount($el){

                template = $(`<div id="loan-approval-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-info">

                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Review Approval</h4>
                        </div>
                    <div class="modal-body">

                         <div class="col-md-12">

                            <div class="row">
                               <div class="col-md-12" style="margin-top: 11px;">
                                 <textarea class="form-control" placeholder="Comment" id="comment"></textarea>
                               </div>
                               <div class="col-md-12" style="margin-top: 11px;">
                               <label for="">
                                  Select Status
                                </label>
                                 <select class="form-control" name="" id="status">
                                    <option value="">--Select Status--</option>
                                    <option value="approve">Approve</option>
                                    <option value="reject">Cancel</option>
                                 </select>
                               </div>
                            </div>

                          </div>
                    </div>
                    <div style="clear: both;"></div>
                    <div class="modal-footer" style="margin-top: 11px;">
                      <button id="btn-review" type="button" class="btn btn-success">Submit</button>
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>

                    </div>
</div>
`);


                $el.html('');
                $el.append(template);


            },

            attachBind(data,refreshTable){

                $('#loan-approval-modal').modal(); //data-dismiss

                 template.find('#comment').val(data.comment);
                 template.find('#status').val(data.status);

                 template.find('#btn-review').off('click');
                 template.find('#btn-review').on('click',(evt)=>{

                   // alert('click');

                     var $data = {
                         comment:template.find('#comment').val(),
                         loan_approval_id:data.id
                     };



                     if (template.find('#status').val() == 'approve'){
                         toastr.info('Approving...');
                         $mixin.doPost('{{ route('loan.approve')  }}',$data).then((response)=>{

                             toastr.success('Approved');
                             refreshTable();

                             $('#loan-approval-modal').find('[data-dismiss]').trigger('click'); //.modal(); //data-dismiss
                         });
                     }

                     if (template.find('#status').val() == 'reject'){
                         toastr.info('Cancelling...');
                         $mixin.doPost('{{ route('loan.reject')  }}',$data).then((response)=>{

                             toastr.info('Cancelled');
                             refreshTable();

                             $('#loan-approval-modal').find('[data-dismiss]').trigger('click'); //.modal(); //data-dismiss
                         });
                     }






                 });



                 refreshTable();

            }

        };

    })(mixinCrud);

</script>