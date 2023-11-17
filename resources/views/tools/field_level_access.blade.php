<?php
$permission = getPagePermission(request('mid') ?? 0);
?>
@extends('layouts.app')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-12">
            <center><h1 class="m-0 align-center"><strong>Field Level Access</strong></h1></center>
        </div>
    </div><!-- /.row -->
@endsection()
@section('content')
<div class="row">
    <div class="col-lg-12">
        <center>
            <div class="card" style="background-color: #F5FFFA;justify-content:center;text-align:center;width:80%">
                <div class="card-body" style="justify-content:center;">
                    <div class="card-text" style="justify-content:center;">
                        <!-- #EBF4FA; -->
                        <div class="card" style="background-color: #F5FFFA;justify-content:center;text-align:center">
                            <form name="fieldlevelaccess_1" id="fieldlevelaccess_1" autocomplete="off" style="padding: 10px;">
                                
                                <div class="row">
                                    <table width="800" class="table table-bordered table-stripped"  id="tbl_mst" >
                                        <thead>
                                            <th width="250">Company &nbsp;&nbsp;<?php echo create_drop_down( "cbo_company_name", 150, "select id,company_name from lib_company order by company_name", "id,company_name",1, "-- Select Company --", "", "",0,"" ); ?></th>
                                            <th width="250">
                                                User &nbsp;&nbsp;
                                                <input type="text" name="txt_user_name" id="txt_user_name" class="text_boxes" style="width:140px;" placeholder="Browse" onDblClick="loadEntryForm();" readonly/>
                                                <input type="hidden" name="text_user_id" id="text_user_id" />
                                                <input type="hidden" name="txt_update_data_dtls" id="txt_update_data_dtls" />
                                                <input type="hidden" name="button_status_check" id="button_status_check" />
                                            </th>
                                            <th>Page Name &nbsp;&nbsp;<?php echo create_drop_down("cbo_page_id",220,get_entry_form(),"",1,"-- Select --","","fn_set_item( this.value );","",implode(',',array_keys(fieldlevel_access_arr())),"","","98","","","cbo_page_id[]"); ?></th>
                                        </thead>
                                    </table>
                                </div>
                                <div class="row mt-10">
                                    <table width="70%" class="table table-bordered table-stripped" cellpadding="0" cellspacing="0" border="1" rules="all" id="tbl_dtls" align="center" style="margin-top: 20px;">
                                        <thead>
                                            <th width="150" id="user_name_th" style="display:none;" >User Name</th>
                                            <th width="180">Field Name</th>
                                            <th width="150">Keep Disable</th>
                                            <th width="120">Set Default Value</th>
                                            <th></th>
                                        </thead>
                                        <tbody id="dtls_body">
                                            <tr>
                                                
                                                <td align="center" id="fieldtd">
                                                <?php echo create_drop_down("cboFieldId_1",180,blank_array(),"",1,"----Select----",0,"","","","","","","","","cbo_field_id[]"); ?>
                                                </td>
                                                <td align="center">
                                                    <?php echo create_drop_down("cboIsDisable_1",150,yes_no(),"",1,"-- Select --",0,"","","","","","","","","cbo_permission_id[]"); ?> 
                                                </td>
                                                <td align="center" id="tdId_1">
                                                    <input type="text" id="setDefaultVal_1" name="" style="width:100px" class="text_boxes" />
                                                    <input type="hidden" id="hideDtlsId_1" name="hideDtlsId[]" style="width:100px;" value="" /> 

                                                </td>
                                                <td align="center" id="increment_1">
                                                    <input style="width:30px;" type="button" id="incrementfactor_1" name="incrementfactor_1"  class="formbutton" value="+" onClick="add_factor_row(1)"/>
                                                    <input style="width:30px;" type="button" id="decrementfactor_1" name="decrementfactor_1"  class="formbutton" value="-" onClick="javascript:fn_deletebreak_down_tr(1)"/>&nbsp;
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" align="center" style="padding-top:10px;" class="button_container">
                                                    <?php 
                                                        echo load_submit_buttons( $permission, "fnc_field_level_access", 0,0 ,"reset_form('fieldlevelaccess_1','','','','','cbo_user_id')",1); 
                                                    ?>
                                                    <input type="hidden" id="txtDeleteRow" value="" />
                                                    <input type="hidden" id="update_id" name="update_id" class="text_boxes" readonly />
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </center>
    </div>
</div>

@endsection

@section('script')
<script>
    var permission ='{{$permission}}';
    function fnc_field_level_access( operation )
    {
        if (form_validation('cbo_page_id','Page Name')==false)
        {
            return;
        }
		else
		{
		
			var field_id_arr=new Array();
			var row_num=$('#tbl_dtls tbody tr').length;
			var field='cbo_page_id,cbo_company_name,text_user_id';
			for (var i=1; i<=row_num; i++)
			{
				var cboFieldId=$('#cboFieldId_'+i).val();
				if(cboFieldId!=0)
                {
					if( jQuery.inArray( $('#cboFieldId_' + i).val(), field_id_arr ) == -1 )
					{
						field_id_arr.push( $('#cboFieldId_' + i).val() );
					}
					else
					{
						alert("Duplicate Field Name Not Allow");return;
					}
				}
				field+=',cboFieldId_'+i+',cboIsDisable_'+i+',setDefaultVal_'+i+',txtFieldName_'+i;
			}
            var formData = get_form_data(field);
            //formData.append('cboFieldId_'+i, $("#"+ex).val());
            var method ="POST";
           
            formData.append('_token', '{{csrf_token()}}');
            formData.append('total_row', row_num);
            formData.append('operation', operation);
            var url = `{{URL::to('/tools/field_level_access')}}`;
            var requestData = {
                method: method,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                body: formData
            };

            save_update_delete(operation,url,requestData);
            fn_set_item($('#cbo_page_id').val());
        
		}
        
    }

    function loadEntryForm()
    {
        
		var title = 'Page List View';
		var page_link='/tools/field_level_access_user';
		emailwindow=dhtmlmodal.open('EmailBox', 'iframe', page_link, title, 'width=500px,height=370px,center=1,resize=1,scrolling=1','../');
		emailwindow.onclose= function()
		{
            var user_name = this.contentDoc.getElementById('user_name').value;
            var user_id = this.contentDoc.getElementById('user_id').value;
			$("#txt_user_name").val(user_name);
			$("#text_user_id").val(user_id);
           //fn_set_item(entry_form_id);
		}
    }
    
    function fn_set_item(val)
	{	
        load_drop_down( 'tools/load_drop_down_field_level_access', val, 'tools/load_drop_down_field_level_access', 'fieldtd' );
        var url = `{{URL::to('tools/field_level_action_user_data')}}`;
        var company_id=$('#cbo_company_name').val();
		var user_id=$('#text_user_id').val();
        var param = company_id+'**'+user_id+'**'+val;
        url = `${url}?data=${param}`;
        fetch(url,{
                method: 'GET' ,
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{csrf_token()}}'// Add the CSRF token to the headers
                }
            })
            .then(response => response.json())
            .then(data => 
            {
                try
                {
                    
                    if(data.length > 0 )
                    {
                        $('#button_status_check').val(1);
                        set_button_status(1, permission, 'fnc_field_level_access',1);

                        var i = 1;
                        for(row of data)
                        {
                            
                            $('#cboFieldId_'+i).val(row.field_id);
                            set_hide_data( row.field_id+"**"+i );
                            $('#cboIsDisable_'+i).val(row.is_disable);
                            $('#txtFieldName_'+i).val(row.field_name);
                            $('#setDefaultVal_'+i).val(row.defalt_value);
                            $('#hideDtlsId_1'+i).val(row.id);
                            $('#update_id').val(row.mst_id);
                            if(i < data.length)
                            {
                                add_factor_row( i );
                            } 
                            i++;
                        }
                    }
                    else
                    {
                        
                        $('#button_status_check').val(0);
                        set_button_status(0, permission, 'fnc_field_level_access',1);
                    }
                }
                catch (error)
                {
                    throw new Error(error);
                }
            })
            .catch(error =>
            {
                $('#button_status_check').val(0);
                set_button_status(0, permission, 'fnc_field_level_access',1);
                showNotification(error,'error');
            });
	}

    function add_factor_row( i) 
	{	
		var chargefor=0;
		var row_num=$('#tbl_dtls tbody tr').length;
		//alert(row_num);
		if (row_num!=i)
		{
			return false;
		}
		i++;
		
		if(form_validation('cbo_page_id','Page Name')==false)
		{
			alert("Please Select Page Name Field");return;
			return;
		}
		
		$("#tbl_dtls tbody tr:last").clone().find("input,select").each(function() {
			$(this).attr({
			'id': function(_, id) { var id=id.split("_"); return id[0] +"_"+ i; },
			'name': function(_, name) { var name=name.split("_"); return name[0]; },
			'value': function(_, value) { return value ; }              
			});
			
		}).end().appendTo("#tbl_dtls");

		$('#tbl_dtls tbody tr:last td:eq(3)').removeAttr('id').attr('id','tdId_'+i);
		$("#tbl_dtls tbody tr:last ").removeAttr('id').attr('id','tr_'+i);
		$("#tbl_dtls tbody tr td:last ").removeAttr('id').attr('id','increment_'+i);
		$("#tbl_dtls tbody tr:last").find(':input:not(:button)','select').val("");
		$('#tbl_dtls tbody tr:last td:eq(3)').removeAttr('id').attr('id','tdId_'+i);
        var k=i-1;
        $('#incrementfactor_'+k).hide();
        $('#decrementfactor_'+k).hide();
	
		  
        $('#incrementfactor_'+i).removeAttr("onClick").attr("onClick","add_factor_row("+i+");");
        $('#decrementfactor_'+i).removeAttr("onClick").attr("onClick","fn_deletebreak_down_tr("+i+");");
        $('#cboFieldId_'+i).removeAttr("onChange").attr("onChange","set_hide_data(this.value"+"+'**'+"+i+");");
		  
	}
	
	function fn_deletebreak_down_tr(rowNo) 
	{
		
		var numRow = $('#tbl_dtls tbody tr').length;
		if(numRow==rowNo && rowNo!=1)
		{
			var delete_row=$('#txtDeleteRow').val();
			var current_delete_row=$('#hideDtlsId_'+rowNo).val();
			if(delete_row!="")
			{
				var tot_delete=delete_row+','+current_delete_row;
			}
			else
			{
				var tot_delete=current_delete_row;
			}
			$('#txtDeleteRow').val(tot_delete);
			var k=rowNo-1;
			$('#incrementfactor_'+k).show();
			$('#decrementfactor_'+k).show();
			
			$('#tbl_dtls tbody tr:last').remove();
		}
		else
        {
            return false;
        }
	}
    function set_hide_data(ref)
	{
		var ref_arr=ref.split('**');
		var page_id=$('#cbo_page_id').val();
        var company_id=$('#cbo_company_name').val();
        var user_id=$('#text_user_id').val();
		//load_drop_down( 'tools/set_field_name', page_id+'**'+ref_arr[0]+'**'+ref_arr[1]+'**'+$('#cbo_company_name').val(), 'tools/set_field_name', 'tdId_'+ref_arr[1]);
	}
    
</script>
@endsection
