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

    function accessFormat( cellvalue, options, rowObject )
    {
      if(cellvalue == 1)
      {
        return "ENABLED";
      }
      else if(cellvalue == 0)
      {
        return "DISABLED";
      }
    }

    function userLevelFormat( cellvalue, options, rowObject )
    {
      if(cellvalue == 0)
      {
        return "PLAYER";
      }
      else if(cellvalue == 1)
      {
        return "ADMIN";
      }
    }

    function volumeFormat( cellvalue, options, rowObject )
    {
      if(cellvalue == 1)
      {
        return "OFF";
      }
      else if(cellvalue == 2)
      {
        return "LOW";
      }
      else if(cellvalue == 3)
      {
        return "MEDIUM";
      }
      else if(cellvalue == 4)
      {
        return "HIGH";
      }
    }

    function controlFormat( cellvalue, options, rowObject )
    {
      if(cellvalue == 1)
      {
        return "SET 1";
      }
      else if(cellvalue == 2)
      {
        return "SET 2";
      }
      else if(cellvalue == 3)
      {
        return "SET 3";
      }
      else if(cellvalue == 4)
      {
        return "SET 4";
      }
    }

    function languageFormat( cellvalue, options, rowObject )
    {
      if(cellvalue == 1)
      {
        return "ENGLISH";
      }
      else if(cellvalue == 2)
      {
        return "FRENCH";
      }
      else if(cellvalue == 3)
      {
        return "SPANISH";
      }
      else if(cellvalue == 4)
      {
        return "MALAY";
      }
      else if(cellvalue == 5)
      {
        return "PORTUGUESE";
      }
    }

    var lastSel = 0;

    jQuery("#grid_users").jqGrid({
        url:'public/grids/users_xml.php',
        datatype: 'xml',
        mtype: 'GET',
        colNames:[
        'ACTION', 
        'USER', 
        'PASS', 
        'EMAIL', 
        'NAME', 
        'LEVEL', 
        'LIVES', 
        'BULLETS', 
        'COINS', 
        'SHIELDS', 
        'KILLS', 
        'SLOWMOS', 
        'POINTS', 
        'TOP SCORE', 
        'DATE', 
        'VOL', 
        'CTRL', 
        'LANG', 
        'ACCESS',
        'ADMIN'
        ],
        colModel :[ 
          {name:'act',index:'act', width:10,sortable:false, search: false},
          {name:'username', index:'username', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'password', index:'password', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'email', index:'email', width:10, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'name', index:'name', width:10, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'level', index:'level', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'lives', index:'lives', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'bullets', index:'bullets', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'coins', index:'coins', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'shields', index:'shields', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'kills', index:'kills', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'slowmos', index:'slowmos', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'points', index:'points', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'top_score', index:'top_score', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'date', index:'date', width:5, align:'left', sortable:true, editable:false, editoptions: {size:30}, search:true},
          {name:'volume', index:'volume', width:5, align:'left', sortable:true, editable:true, search:true, formatter:volumeFormat, edittype:'select', editoptions:{value:{1:'OFF',2:'LOW',3:'MEDIUM',4:'HIGH'}}},
          {name:'control', index:'control', width:5, align:'left', sortable:true, editable:true, search:true, formatter:controlFormat, edittype:'select', editoptions:{value:{1:'SET 1',2:'SET 2',3:'SET 3',4:'SET 4'}}},
          {name:'language', index:'language', width:5, align:'left', sortable:true, editable:true, search:true, formatter:languageFormat, edittype:'select', editoptions:{value:{1:'ENGLISH',2:'FRENCH',3:'SPANISH',4:'MALAY',5:'PORTUGUESE'}}},
          {name:'enabled', index:'enabled', width:5, align:'left', sortable:true, editable:true, search:true, formatter:accessFormat, edittype:'select', editoptions:{value:{1:'ENABLED',0:'DISABLED'}}},
          {name:'admin', index:'admin', width:5, align:'left', sortable:true, editable:true, search:true, formatter:userLevelFormat, edittype:'select', editoptions:{value:{1:'ADMIN',0:'PLAYER'}}}
        ],
        width: 1290,
        height: 400,
        pager: '#nav_users',
        rowNum:30,
        rowList:[10,20,30,40,50,100,200,300,400,500],
        sortname: 'id',
        sortorder: 'desc',
        gridComplete: function()
        {
          var ids = jQuery("#grid_users").jqGrid('getDataIDs');
          for(var i=0;i < ids.length;i++)
          {
            var id = ids[i];
            edit = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_users').editGridRow('"+id+"', {width:300});\"><span class='ui-icon ui-icon-pencil'></span></button>"; 
            del = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_users').delGridRow('"+id+"');\"><span class='ui-icon ui-icon-trash'></span></button>";
            save = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_users').saveRow('"+id+"');\" ><span class='ui-icon ui-icon-check'></span></button>"; 
            jQuery("#grid_users").jqGrid('setRowData',ids[i],{act:edit+del+save});
          }
        },
        editurl: "public/grids/users_manipulate.php",
        viewrecords: true,
        gridview: true,
        caption: 'Users',
        multiselect:true,
        onSelectRow: function(id)
        {
         if(id && id!==lastSel)
         { 
            jQuery('#grid_users').restoreRow(lastSel); 
            lastSel=id; 
         }
         jQuery('#grid_users').editRow(id);
       }
    });

  jQuery("#grid_users").jqGrid('navGrid','#nav_users',{edit:true, add:true, del:true}).
    navButtonAdd('#nav_users',{
       caption:"Delete", 
       buttonicon:"ui-icon-circle-minus", 
       onClickButton: function(){

          var ids = jQuery("#grid_users").jqGrid('getGridParam','selarrrow');

          if(ids.length > 0)
          {
            if(confirm("Delete selected users?"))
            {
              $.ajax({
                type:"POST",
                url:"public/grids/multi_delete.php",
                data: {ids:ids, what:"user"},
                success: function(result)
                {
                    if(result == "success")
                    {
                        jQuery("#grid_users").trigger("reloadGrid");
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
    }).
    navButtonAdd('#nav_users',{
       caption:"Disable", 
       buttonicon:"ui-icon-circle-close", 
       onClickButton: function(){

          var ids = jQuery("#grid_users").jqGrid('getGridParam','selarrrow');

          if(ids.length > 0)
          {
            if(confirm("Disable selected users?"))
            {
              $.ajax({
                type:"POST",
                url:"public/grids/multi_disable.php",
                data: {ids:ids},
                success: function(result)
                {
                    if(result == "success")
                    {
                        jQuery("#grid_users").trigger("reloadGrid");
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
    }).
    navButtonAdd('#nav_users',{
       caption:"Enable", 
       buttonicon:"ui-icon-circle-check", 
       onClickButton: function(){

          var ids = jQuery("#grid_users").jqGrid('getGridParam','selarrrow');

          if(ids.length > 0)
          {
            if(confirm("Enable selected users?"))
            {
              $.ajax({
                type:"POST",
                url:"public/grids/multi_enable.php",
                data: {ids:ids},
                success: function(result)
                {
                    if(result == "success")
                    {
                        jQuery("#grid_users").trigger("reloadGrid");
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

<table id="grid_users"><tr><td/></tr></table> 
<div id="nav_users"></div>