<?php

  require_once("../../includes/initialize.php");

  global $session;

  if(!$session->is_logged_in())
  {
      redirect_to("../../index.php");
  }
  else
  {

  }

?>

<script>

  $(function()
  {
    var last_clicked_id = 0;

    var lastSel = 0;

    jQuery("#grid_redeemed_codes").jqGrid({
        url:'public/grids/redeemed_codes_xml.php',
        datatype: 'xml',
        mtype: 'GET',
        colNames:[
        'ACTION', 
        'CODE_ID', 
        'CODE', 
        'ITEM', 
        'VALUE', 
        'MESSAGE', 
        'USER_ID', 
        'USERNAME'
        ],
        colModel :[ 
          {name:'act',index:'act', width:7,sortable:false, search: false},
          {name:'code_id', index:'code_id', width:3, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'code', index:'code', width:5, align:'left', sortable:true, editable:false, editoptions: {size:30}, search:true},
          {name:'item', index:'item', width:5, align:'left', sortable:true, editable:false, editoptions: {size:30}, search:true},
          {name:'value', index:'value', width:5, align:'left', sortable:true, editable:false, editoptions: {size:30}, search:true},
          {name:'message', index:'message', width:5, align:'left', sortable:true, editable:false, editoptions: {size:30}, search:true},
          {name:'user_id', index:'user_id', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'username', index:'username', width:20, align:'left', sortable:true, editable:false, editoptions: {size:30}, search:true}
        ],
        width: 1250,
        height: 400,
        pager: '#nav_redeemed_codes',
        rowNum:30,
        rowList:[10,20,30,40,50,100,200,300,400,500],
        sortname: 'id',
        sortorder: 'desc',
        gridComplete: function()
        {
          var ids = jQuery("#grid_redeemed_codes").jqGrid('getDataIDs');
          for(var i=0;i < ids.length;i++)
          {
            var id = ids[i];
            edit = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_redeemed_codes').editGridRow('"+id+"', {width:300});\"><span class='ui-icon ui-icon-pencil'></span></button>"; 
            del = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_redeemed_codes').delGridRow('"+id+"');\"><span class='ui-icon ui-icon-trash'></span></button>";
            save = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_redeemed_codes').saveRow('"+id+"');\" ><span class='ui-icon ui-icon-check'></span></button>"; 
            jQuery("#grid_redeemed_codes").jqGrid('setRowData',ids[i],{act:edit+del+save});
          }
        },
        editurl: "public/grids/redeemed_codes_manipulate.php",
        viewrecords: true,
        gridview: true,
        caption: 'redeemed_codes',
        multiselect:true,
        onSelectRow: function(id)
        {
         if(id && id!==lastSel)
         { 
            jQuery('#grid_redeemed_codes').restoreRow(lastSel); 
            lastSel=id; 
         }
         jQuery('#grid_redeemed_codes').editRow(id);
       }
    });

  jQuery("#grid_redeemed_codes").jqGrid('navGrid','#nav_redeemed_codes',{edit:true, add:true, del:true}).
    navButtonAdd('#nav_redeemed_codes',{
       caption:"Delete Selected", 
       buttonicon:"ui-icon-add", 
       onClickButton: function(){
          var ids = jQuery("#grid_redeemed_codes").jqGrid('getGridParam','selarrrow');
          if(ids.length > 0)
          {
            if(confirm("Delete selected records?"))
            {
              $.ajax({
                type:"POST",
                url:"public/grids/multi_delete.php",
                data: {ids:ids, what:"redeemed_code"},
                success: function(result)
                {
                    if(result == "success")
                    {
                        jQuery("#grid_redeemed_codes").trigger("reloadGrid");
                        return false;
                    }
                    else
                    {
                        bootbox.alert(result);
                        return false;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    bootbox.alert("error");
                    return false;
                }
              });
            }
          }
          else
          {
            bootbox.alert("please select atleast one");
          }
          return false;
       },
       position:"last"
    });
});

</script>

<table id="grid_redeemed_codes"><tr><td/></tr></table> 
<div id="nav_redeemed_codes"></div>