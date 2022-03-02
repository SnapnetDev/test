<script>



  const LoanTypeModule = (function(doFetch,$loanTypeGrade){

     var storeApi = '{{ route('loan_type.store') }}';
     var updateApi = '{{ route('loan_type.update',['']) }}';
     var destroyApi = '{{ route('loan_type.destroy',['']) }}';
     var csrfToken = '{{ csrf_token() }}';
     var fetchApi = '{{ route('loan_type.index') }}';
     var salaryComponentFetchApi = '{{ route('fetch.salary.component',[ companyId() ]) }}';
     var fetchGradesApi = '{{ route('ajax.get.component',['Grade']) }}?company_id={{ companyId() }}';

     var $loanTypeList = null;
     var $table = null;

     var fields = ['name','required_duration_in_months','duration_comparator','requires_confirmation','multiplier_index','pace_salary_component_id','repayment_period','interest_rate','open_to_grade_id','grade_id','specific_salary_component_type_id'];

     var $createForm = null;
     var $createBtn = null;

     var $editForm = null;
     var $editBtn = null;

     // function doFetch(url,type,data){
     //    return $.ajax({
     //         url:url,
     //         type:type,
     //         data:data
     //     });
     // }


      return {

          init(){

              $loanTypeList = $('#loan-type-list');
              $table = $('<table class="table" style="border-bottom: 2px solid #aaa;"></table>');
              $createBtn = $('#create-loan-type-button');
              $createForm = $('#create-loan-type-form'); //create-loan-type-form

              $editForm = $('#edit-loan-type-form');
              $editBtn = $('#edit-loan-type-button');

              this.initCreateBindings();
              this.initEditBindings();
              // this.initEditBindings();
              this.loadLoanTypeList();
          },

          setSalaryComponent(vl){

          },

          loadSalaryComponent($parent,vl){

              var $el = $parent.find('[name=pace_salary_component_id]');

              doFetch(salaryComponentFetchApi,'GET',{}).then((response)=>{
                  $el.html('');
                  response.list.forEach((item)=>{
                      $el.append('<option value="' + item.id + '">' + item.name + '</option>');
                  });

                  $el.append('<option value="0">Default Loan Value</option>');

                  if (vl)$el.val(vl);

              });

          },


          loadGrades($parent,vl){

               var $el = $parent.find('[name=open_to_grade_id]');

               doFetch(fetchGradesApi,'GET',{}).then((response)=>{

                   $el.html('');

                   response.list.forEach((item)=>{
                       $el.append('<option value="' + item.id + '">' + item.level + '</option>');
                   });

                   $el.append('<option value="0">Any Grade</option>');

               });


          },

          setGradeComponent(vl){

          },

          getInputs($parent){



              var r = {};

              fields.forEach((field)=>{

                  if (!$parent.find('[name="' + field + '"]').is('[name="' + field + '"]'))return;

                  if ($parent.find('[name="' + field + '"]').is('[type=checkbox]')){

                      if ($parent.find('[name="' + field + '"]').is(':checked')){
                          r[field] = 1;
                          return;
                      }
                      r[field] = 0;
                      return;
                  }

                  // console.log(r);

                  if ($parent.find('[name="' + field + '"]').is('[data-array]')){

                      $parent.find('[data-array]').each((k,$el)=>{

                         if ($($el).is('[name="' + field + '"]')){
                             r[field] = r[field] || [];
                             // console.log($el);
                             r[field].push($($el).val());
                         }

                      });

                      // console.log('arr-seen',field);

                      return;

                   }

                  r[field] = $parent.find('[name="' + field + '"]').val();


              });

              return r;

          },

          setInputs(data){

              let $parent = $editForm;

              $editForm.modal();

              updateApi = '{{ route('loan_type.update',['']) }}/' + data.id;

              var $el = $loanTypeGrade();

              $el.mount($editForm.find('#grade-outlet'));

              this.mountSpecificSalaryComponentType($editForm.find('#specific_salary_component_type_id'),data.specific_salary_component_type_id);

               data.loan_type_grade.forEach((itm)=>{
                   // console.log(itm);
                   $el.appendGrade({
                       level:itm.grade.level,
                       id:itm.grade.id
                   });
               });

              //.find('#grade')

               fields.forEach((field)=>{
                  $check = '[name="' + field + '"]';

                  if (!$parent.find($check).is($check))return;
                  if (data[field] || data[field] == 0)
                   $parent.find($check).val(data[field]);
                  if ($parent.find($check).is('[type="checkbox"]')){
                      if (+data[field] == 1){
                          $parent.find($check).prop("checked", true);
                          return;
                      }

                      $parent.find($check).prop("checked", false);
                  }


               });

          },

          //specific_salary_component_type_id
          mountSpecificSalaryComponentType($el,vl){

              $el.html('');

              var $eel = $(`<select name="specific_salary_component_type_id" class="form-control">
                                  <option value="">--Select--</option>
                                </select>`);

              doFetch('{{ route('ajax.get.component','SpecificSalaryComponentType') }}?company_id={{ companyId() }}')
                  .then((response)=>{

                      response.list.forEach((item)=>{

                          var $opt = `<option value="${item.id}">${item.name}</option>`;

                          $eel.append($opt);

                      });

                      if (vl){
                          $eel.val(vl);
                      }


                  });

              $el.append($eel);

          },

          initCreateBindings(){

              this.loadSalaryComponent($createForm,null);

              $loanTypeGrade().mount($createForm.find('#grade'));

              this.loadGrades($createForm,null);

              this.mountSpecificSalaryComponentType($createForm.find('#specific_salary_component_type_id'),null);

              $createBtn.on('click',(event)=>{
                  var data = this.getInputs($createForm);

                  data._token = csrfToken;

                  // console.log(data);
                  this.createLoanType(data);

                  return false;
              });

          },

          initEditBindings(){

              this.loadSalaryComponent($editForm,null);
              this.loadGrades($editForm,null);

              $editBtn.on('click',(event)=>{
                  var data = this.getInputs($editForm);

                  data._token = csrfToken;

                  // console.log(data);
                  this.updateLoanType(data);

                  return false;
              });

          },

          initDeleteBindings(){



          },

          initTable(){
            $table.html('');
            $loanTypeList.append($table);
            this.initTableHeader($table);
          },

          initTableHeader($el){
              $el.append($(`
                <tr>
                   <th>
                     Name
                   </th>
                   <th>
                     Duration In Months
                   </th>
                   <th>Salary Component</th>
                   <th>
                     Interest Rate
                   </th>
                   <th>Required Grade(s)</th>
                   <th>Actions</th>
                </tr>
              `));
          },

          eachLoanTypeList(item){

              var gradeList = [];
              item.loan_type_grade.forEach((itm)=>{
                  gradeList.push(`<span style="
    background-color: #03a9f4;
    color: #fff;
    padding: 5px;
    border-radius: 8px;
    margin-bottom: 7px;
    display: inline-block;
" class="badge badge-primary">Grade - ${itm.grade.level}</span>`);
              });

              var $el = `
                <tr>
                   <td>
                     ${item.name}
                   </td>
                   <td>
                     ${item.required_duration_in_months}
                   </td>
                   <td>${item.pace_salary_component? item.multiplier_index + ' X ' +  item.pace_salary_component.name : 'Default Loan Value'}</td>
                   <td>
                     ${item.interest_rate}
                   </td>
                   <td style="
    height: 50px;
    overflow-y: scroll;
    display: inline-block;
    width: 206px;
">${gradeList.join(' ')}</td>
                   <td>
                      <a id="edit" class="btn btn-sm btn-warning" href="#">Edit</a>
                      <a id="remove" class="btn btn-sm btn-danger" href="#">Remove</a>
                   </td>
                </tr>
              `;

              $el = $($el);

              $el.find('#edit').on('click',(evt)=>{
                  this.setInputs(item);
              });

              $el.find('#remove').on('click',(evt)=>{
                  // this.setInputs(item);
                  destroyApi = '{{ route('loan_type.destroy',['']) }}/' + item.id;
                  if (confirm('Do You want to confirm action?')){
                      this.removeLoanType(item);
                  }
              });

              $table.append($el);

              //setInputs
              // data-dismiss="modal"
          },

          loadLoanTypeList(){
              doFetch(fetchApi,'GET',{}).then((response)=>{

                  $loanTypeList.html(''); //clear the list.

                  this.initTable();

                  response.list.forEach((item)=>{
                      this.eachLoanTypeList(item);
                  });

                  console.log(response);

              });
          },

          createLoanType(data){


            doFetch(storeApi,'POST',data).then((response)=>{

                if (response.message){

                    if (response.error)toastr.error(response.message);
                    if (!response.error)toastr.success(response.message);

                    this.loadLoanTypeList();

                    $createForm.find('[data-dismiss="modal"]').trigger('click');

                }

            });
          },

          updateLoanType(data){
              data._method = 'PUT';
              doFetch(updateApi,'POST',data).then((response)=>{

                  if (response.message){

                      if (response.error)toastr.error(response.message);
                      if (!response.error)toastr.success(response.message);

                      this.loadLoanTypeList();

                      $editForm.find('[data-dismiss="modal"]').trigger('click');
                  }

              });

          },

          removeLoanType(data){
              data._method = 'DELETE';
              data._token = csrfToken;
              doFetch(destroyApi,'POST',data).then((response)=>{

                  if (response.message){

                      if (response.error)toastr.error(response.message);
                      if (!response.error)toastr.success(response.message);

                      this.loadLoanTypeList();

                      // $editForm.find('[data-dismiss="modal"]').trigger('click');
                  }

              });


          }

      }




  })(doFetch_,loanTypeGrade);



  LoanTypeModule.init();

</script>