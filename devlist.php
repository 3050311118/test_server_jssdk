Skip to content
This repository
Search
Pull requests
Issues
Gist
 @3050311118
 Sign out
 Unwatch 1
  Star 0
  Fork 0 3050311118/panel.mogudz.com
 Code  Issues 0  Pull requests 0  Projects 0  Wiki  Pulse  Graphs  Settings
Branch: master Find file Copy pathpanel.mogudz.com/devlist.html
a36a5ec  an hour ago
@3050311118 3050311118 Create devlist.html
1 contributor
RawBlameHistory     
129 lines (124 sloc)  3.06 KB
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">

  <script src="https://unpkg.com/vue@2.1/dist/vue.min.js"></script>
  <script src="https://unpkg.com/vue-scroller@2.1/dist/vue-scroller.min.js"></script>
  <script src="http://static.mogudz.com/js/mqttws31.js"></script>
  <title>我的在线设备列表</title>
  <style>
    html, body {
      margin: 0;
    }
    * {
      box-sizing: border-box;
    }
    .row {
      width: 100%;
      height: 50px;
      padding: 10px 0;
      font-size: 16px;
      line-height: 30px;
      text-align: center;
      color: #444;
      background-color: #fff;
    }
    .grey-bg {
      background-color: #eee;
    }
    .header {
      position: fixed;
      top: 0;
      left: 0;
      height: 44px;
      width: 100%;
      box-shadow: 0 2px 10px 0 rgba(0,0,0,0.1);
      background-color: #fff;
      z-index: 1000;
      color: #666;
    }
    .header > .title {
      font-size: 16px;
      line-height: 44px;
      text-align: center;
      margin: 0 auto;
    }
  </style>
</head>
<body>
<div id="app">
  <div class="header">
    <h1 class="title">下拉刷新列表</h1>
  </div>
  <scroller :on-refresh="refresh"
            ref="my_scroller" style="top: 44px;">
    <div v-for="(item, index) in items" class="row" :class="{'grey-bg': index % 2 == 0}">
    	<div v-if="index==0">点击打开设备页面</div>
		<div v-else>序列号{{item.sn}} 设备名{{item.nickname}}</div>
    </div>
  </scroller>
</div>
<script>
	var client;	
	var message;
	var userid="oHOgqwvXok5LsBNOOpV6jSZzX6Js";
	function pub()
	{
	    message = new Paho.MQTT.Message('{"action":"GETONLINE"}');
	    message.destinationName = userid+"/SUB";
	    client.send(message);   
	}
	function mqtt(){ 
	    try 
	    {
	        client = new Paho.MQTT.Client("www.mogudz.com", 8083, "WEB"+userid);//location.hostname
	        client.onConnectionLost = onConnectionLost;
	        client.onMessageArrived = onMessageArrived;
	        client.connect({onSuccess:onConnect});
	        function onConnect() {
	            client.subscribe("oHOgqwvXok5LsBNOOpV6jSZzX6Js/PUB");
	            pub(); 
	        };
	        function onConnectionLost(responseObject) {
	        };
	        function onMessageArrived(message) {
	            var str=message.payloadString;
	            try{
	            	var json=JSON.parse(str);
	            	app.items.push(json);
	            }catch(e){
	            }
	        };  
	    }catch(e){
	    }
	}
  var app=new Vue({
    el: '#app',
    components: {
      Scroller
    },
    data: {
	    items: []
    },
    mounted: function () {
      mqtt(); 
      setTimeout(() => {
        this.$refs.my_scroller.resize();
      })
    },
    methods: {
      refresh: function () {
      	this.items=[];
      	mqtt();
        setTimeout(() => {
          this.$refs.my_scroller.finishPullToRefresh();
        }, 1500)
      }
    }
  });
</script>
</body>
</html>
Contact GitHub API Training Shop Blog About
© 2017 GitHub, Inc. Terms Privacy Security Status Help