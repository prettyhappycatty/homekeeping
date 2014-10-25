
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<link rel="stylesheet" type="text/css" href="../../../lib/extjs-4.1.1/resources/css/ext-all.css" />
<script type="text/javascript" src="../../../lib/jquery-ui-1.7.3.custom/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../../lib/extjs-4.1.1/ext-all.js"></script>

<script src="../../../lib/jquery-ui-1.7.3.custom/js/jquery-ui-1.7.3.custom.min.js"></script>
<script type="text/javascript" src="../../../lib/extjs-4.1.1/locale/ext-lang-ja.js"></script>

<script type="text/javascript">
<!--
Ext.require([
             'Ext.data.*',
             'Ext.grid.*'
         ]);
Ext.require('Ext.chart.*');

Ext.onReady(function() {

    Ext.define('Month',{
        extend: 'Ext.data.Model',
        fields: [
            'month', 'category1', 'category2','category3'
        ]
    });

	var store = Ext.create('Ext.data.Store', {
	     model: 'Month',
	     proxy: {
	         type: 'ajax',
	         url: './getData2.php',
	         reader: {
	             type: 'xml',
	             root: 'months',
	             record:'month_data'
	         }
	     },
	     autoLoad: true
	 });
	var chart = Ext.create('Ext.chart.Chart', {
        id: 'chartCmp1',
        xtype: 'chart',
        style: 'background:#fff',
        animate: true,
        store: store,
        legend: {
            position: 'bottom'
        },
        axes: [{
            type: 'Numeric',
            grid: true,
            position: 'left',
            fields: ['category1', 'category2', 'category3'],
            title: 'Number of Hits',
            grid: {
                odd: {
                    opacity: 1,
                    fill: '#ddd',
                    stroke: '#bbb',
                    'stroke-width': 1
                }
            },
            minimum: 0,
            adjustMinimumByMajorUnit: 0
        }, {
            type: 'Category',
            position: 'bottom',
            fields: ['month'],
            title: 'Month of the Year',
            grid: true,
            label: {
                rotate: {
                    degrees: 315
                }
            }
        }],
        series: [{
            type: 'area',
            highlight: false,
            axis: 'left',
            xField: 'month',
            yField: ['category1', 'category2', 'category3'],
            style: {
                opacity: 0.93
            }
        }]
    });

    var win = Ext.create('Ext.Window', {
        width: 800,
        height: 600,
        minHeight: 400,
        minWidth: 550,
        hidden: false,
        shadow: false,
        maximizable: true,
        title: 'Area Chart',
        renderTo: Ext.getBody(),
        layout: 'fit',
        tbar: [{
            text: 'Save Chart',
            handler: function() {
                Ext.MessageBox.confirm('Confirm Download', 'Would you like to download the chart as an image?', function(choice){
                    if(choice == 'yes'){
                        chart.save({
                            type: 'image/png'
                        });
                    }
                });
            }
        }, {
            text: 'Reload Data',
            handler: function() {
            	xhr(success);
            }
        }, {
            enableToggle: true,
            pressed: true,
            text: 'Animate',
            toggleHandler: function(btn, pressed) {
                var chart = Ext.getCmp('chartCmp');
                chart.animate = pressed ? { easing: 'ease', duration: 500 } : false;
            }
        }],
        items: chart
    });
  // Ext.grid.GridPanelオブジェクトの生成
  /*
  var grid = new Ext.grid.GridPanel({
    store: store,   // 表示するデータ
    columns: [      // 各列の設定
      {header: "月", width: 80, dataIndex: "month"},
      {header: "食費", width: 120, sortable: true, dataIndex: "category1"},
      {header: "光熱費", width: 120, sortable: true, dataIndex: "category2"},
      {header: "医療費", width: 120, sortable: true, dataIndex: "category3"}
    ],
    width: 600,     // グリッドの幅
    height: 300,    // グリッドの高さ
    title: "太陽系",  // グリッドのタイトル
    renderTo: "gridcontainer"
  });
	  */

});
//-->
</script>
<script type="text/javascript">
<!--
var containerEl = Ext.get("container");  // id="container"要素
var resultEl = Ext.get("result");  // id="result"要素

Ext.require(['Ext.data.*']);
Ext.require(['Ext.tabPanel.*']);
Ext.require('Ext.chart.*');
Ext.require(['Ext.Window', 'Ext.fx.target.Sprite', 'Ext.layout.container.Fit', 'Ext.window.MessageBox']);

/*
 * Ajax通信成功時の処理
 */
function handleSuccess(response) {
    var data1 = [];
    if (response.responseXML !== undefined) {

        var s="";
    	 for(var i=0; response.responseXML.childNodes[i]!=null; i++){
             if(response.responseXML.childNodes[i].nodeName=="months"){
				 var monthsNode = response.responseXML.childNodes[i];
                 for(var j=0; monthsNode.childNodes[j]!=null; j++){
                     if(monthsNode.childNodes[j].nodeName=="month_data"){
						 var monthNode = monthsNode.childNodes[j];
  	                	 var monthdata = {};
                         for(var k=0; monthNode.childNodes[k]!=null; k++){
                            if(monthNode.childNodes[k].nodeName=="month"){
        	                	//alert(monthNode.childNodes[i].nodeValue);
                    			s+= monthNode.childNodes[k].firstChild.nodeValue;
                    			monthdata.month = monthNode.childNodes[k].firstChild.nodeValue;
                    		}

                            if(monthNode.childNodes[k].nodeName=="category_data"){
       							var categoryNode = monthNode.childNodes[k];
                                for(var l=0; categoryNode.childNodes[l]!=null; l++){
                                    var category_id,category_sum;
                                    if(categoryNode.childNodes[l].nodeName=="category_id"){
                                        category_id = "category" + categoryNode.childNodes[l].firstChild.nodeValue;
                                    }
                                    if(categoryNode.childNodes[l].nodeName=="category_sum"){
                                        category_sum = parseInt(categoryNode.childNodes[l].firstChild.nodeValue);
                                    }
                                }
                                monthdata[category_id] = category_sum;

                            }
                         }
                       	data1.push(monthdata);
                     }
	             }
             }
         }

    	 window.data2 = Ext.create('Ext.data.JsonStore', {
        	 fields: ['month','category1', 'category2', 'category3', 'category4', 'category5', 'category6', 'category7'],
        	 data: data1
    	 });
/*
    	 var MyDataStore = new Ext.data.XmlStore({
    		    url: './getData2.php',
    		    record: 'month_data',
    		    totalRecords: 'total',
    		    fields: [
    		        { name:'month' },
    		        { name:'category1' },
    		        { name:'category2' },
    		        { name:'category3' },
    		        { name:'category4' },
    		        { name:'category5' },
    		        { name:'category6' },
    		        { name:'category7' },
    		        { name:'category8' },
    		        { name:'category9' },
    		        { name:'category10' }
    		    ]
    		});
    		*/
    		/*
    	 var MyDataStore = new Ext.data.Store({
    		    proxy: new Ext.data.HttpProxy({
        		    url: './getData2.php'
    		    }),
    		    reader: new Ext.data.XmlReader({
        		    record: 'month_data',
        		    totalRecords: 'total',
    		        fields: [
    		    		        { name:'month' },
    		    		        { name:'category1' },
    		    		        { name:'category2' },
    		    		        { name:'category3' },
    		    		        { name:'category4' },
    		    		        { name:'category5' },
    		    		        { name:'category6' },
    		    		        { name:'category7' },
    		    		        { name:'category8' },
    		    		        { name:'category9' },
    		    		        { name:'category10' }
    		        ]
    		    })
    		});*/
    		window.MyDataStore = new Ext.data.JsonStore({
    		    proxy: new Ext.data.HttpProxy({
        		    url: './getData3json.php'
    		    }),
    		    reader: new Ext.data.JsonReader({
    		        root: 'month_data',
    		        totalProperty: 'total',
    		        fields: [
 		    		        { name:'month' },
		    		        { name:'category1' },
		    		        { name:'category2' },
		    		        { name:'category3' },
		    		        { name:'category4' },
		    		        { name:'category5' },
		    		        { name:'category6' },
		    		        { name:'category7' },
		    		        { name:'category8' },
		    		        { name:'category9' },
		    		        { name:'category10' }
    		        ]
    		    })
    		});
    	window.MyDataStore.load();

    	 var chart = Ext.create('Ext.chart.Chart', {
             id: 'chartCmp',
             xtype: 'chart',
             style: 'background:#fff',
             animate: true,
             store: data2,
             legend: {
                 position: 'bottom'
             },
             axes: [{
                 type: 'Numeric',
                 grid: true,
                 position: 'left',
                 fields: ['category1', 'category2', 'category3', 'category4', 'category5', 'category6', 'category7'],
                 title: 'Number of Hits',
                 grid: {
                     odd: {
                         opacity: 1,
                         fill: '#ddd',
                         stroke: '#bbb',
                         'stroke-width': 1
                     }
                 },
                 minimum: 0,
                 adjustMinimumByMajorUnit: 0
             }, {
                 type: 'Category',
                 position: 'bottom',
                 fields: ['month'],
                 title: 'Month of the Year',
                 grid: true,
                 label: {
                     rotate: {
                         degrees: 315
                     }
                 }
             }],
             series: [{
                 type: 'area',
                 highlight: false,
                 axis: 'left',
                 xField: 'month',
                 yField: ['category1', 'category2', 'category3', 'category4', 'category5', 'category6', 'category7'],
                 style: {
                     opacity: 0.93
                 }
             }]
         });

    var win = Ext.create('Ext.Window', {
        width: 800,
        height: 600,
        minHeight: 400,
        minWidth: 550,
        hidden: false,
        shadow: false,
        maximizable: true,
        title: 'Area Chart',
        renderTo: Ext.getBody(),
        layout: 'fit',
        tbar: [{
            text: 'Save Chart',
            handler: function() {
                Ext.MessageBox.confirm('Confirm Download', 'Would you like to download the chart as an image?', function(choice){
                    if(choice == 'yes'){
                        chart.save({
                            type: 'image/png'
                        });
                    }
                });
            }
        }, {
            text: 'Reload Data',
            handler: function() {
            	xhr(success);
            }
        }, {
            enableToggle: true,
            pressed: true,
            text: 'Animate',
            toggleHandler: function(btn, pressed) {
                var chart = Ext.getCmp('chartCmp');
                chart.animate = pressed ? { easing: 'ease', duration: 500 } : false;
            }
        }],
        items: chart
    });
 // create the grid
    var grid = Ext.create('Ext.grid.Panel', {
        store: data2,
        columns: [
            {text: "month", flex: 1, dataIndex: 'month'},
            {text: "category1", width: 180, dataIndex: 'category1'},
            {text: "category2", width: 115, dataIndex: 'category2'},
            {text: "category3", width: 100, dataIndex: 'category3'}
        ],
        renderTo:'example-grid',
        width: 540,
        height: 200
    });

    Ext.create('Ext.panel.Panel', {
        width: 500,
        height: 300,
        title: 'Border Layout',
        layout: 'border',
        items: [{
            title: 'South Region is resizable',
            region: 'south',     // position for region
            xtype: 'panel',
            height: 100,
            split: true,         // enable resizing
            margins: '0 5 5 5'
        },{
            // xtype: 'panel' implied by default
            title: 'West Region is collapsible',
            region:'west',
            xtype: 'panel',
            margins: '5 0 0 5',
            width: 200,
            collapsible: true,   // make collapsible
            id: 'west-region-container',
            layout: 'fit'
        },{
            title: 'Center Region',
            region: 'center',     // center region is required, no width/height specified
            xtype: 'panel',
            layout: 'fit',
            margins: '5 5 0 0'
        }],
        renderTo: Ext.getBody()
    });
    }
}

/*
 * Ajax通信失敗時の処理
 */
function handleFailure(response) {
    if (response.responseXML !== undefined) {

        // id="container"要素配下に子要素があればそれを削除
        if (containerEl.child("*")) {
            containerEl.child("*").remove();
        }

        // ul要素の生成
        var ulEl = containerEl.createChild({tag: "ul"});

        // li要素の生成
        var liEl1 = ulEl.createChild({tag: "li"});
        liEl1.dom.innerHTML = "HTTP status: " + response.status;

        // li要素の生成
        var liEl2 = ulEl.createChild({tag: "li"});
        liEl2.dom.innerHTML = "Status code message: " + response.statusText;

        resultEl.dom.innerHTML = "";

    }
}

/*
 * XMLレスポンスからサーバの時刻を取得する。
 */
function getTime(xml) {
    var Query = Ext.DomQuery;  // shorthand for Ext.DomQuery

    var yearNode = Query.selectNode("result/time/year", xml);
    var year = yearNode.firstChild.nodeValue;

    var monthNode = Query.selectNode("result/time/month", xml);
    var month = monthNode.firstChild.nodeValue;

    var dayNode = Query.selectNode("result/time/day", xml);
    var day = dayNode.firstChild.nodeValue;

    var hourNode = Query.selectNode("result/time/hour", xml);
    var hour = hourNode.firstChild.nodeValue;

    var minuteNode = Query.selectNode("result/time/minute", xml);
    var minute = minuteNode.firstChild.nodeValue;

    var secondNode = Query.selectNode("result/time/second", xml);
    var second = secondNode.firstChild.nodeValue;

    return year + "年" + month + "月" + day + "日 "
    + hour + "時" + minute + "分" + second + "秒";
}

/*
 * ボタン押下時の処理
 */
function xhr(success) {

        var url = "./getData.php";

    // Ajaxリクエスト送信
    Ext.Ajax.request({
                     url: url,
                     method: "GET",
                     success: handleSuccess,
                     failure: handleFailure,
                     });
}

Ext.onReady(function()
		{
	        var myTabPanel = new Ext.TabPanel(
	        {
	            id: "myTabPanel",
	            width: 800,
	            height: 320,
	            renderTo: "floatmenu",
	            items: [
	            {
	                title: "ひんぬー",
	                html: "ルイズ<br/>エルルゥ<br/>芳野さくら<br/>甲田さつき"
	            },
	            {
	                title: "きょぬー",
	                html: "知らないよ"
	            }]
	        });
	    });
//-->
</script>
</head>
<body>
<?php

    require_once '../service/billlineService.php';
    require_once '../service/billService.php';
    require_once '../entity/billline.php';
    //とりあえず直近２年分を出すことにする。

    //$year[]="2011";
    $year[]="2012";


    //該当する月のデータの検索
    $billService = new BillService();
    $bill_months = $billService->selectDistinctMonth();

    for($i = 0; $i < count($bill_months); $i++){
        $month = $bill_months[$i]->month;
        $bills = $billService->selectByMonth($month);

        //見つかったbill_idを配列にする。
        $bill_ids = array();
        for($k=0; $k < count($bills); $k++){
            $bill_ids[] = $bills[$k]->id;
        }
        //探す。
        $billlineService = new BilllineService();
        $billlineSums = $billlineService->getCategorySumsByBillIds($bill_ids);
        echo $month . "<br>";
        echo "<table border=1><tbody>";
        if(count($billlineSums) > 0){
            echo $billlineSums[0]->getTableHeaderHtml();
        }
        for($k = 0 ; $k < count($billlineSums); $k++){
            echo $billlineSums[$k]->getTableLineHtml();
        }
        echo "</tbody></table>";
        echo "<hr>";
    }

    echo '<div id ="example-grid"></div>';
    echo '<INPUT TYPE="button" VALUE="☆☆☆" ONCLICK="xhr()">';

    echo '<a href="./getData2.php">getData</a>';
    echo '<div class="demo"><id="draggable" class="ui-widget-content">test</div></div>';

    echo '<div id="floatmenu"></div>';
   echo '<div id="chartCmp1"></div>';

?>

<div id="gridcontainer"></div>
</body>
</html>