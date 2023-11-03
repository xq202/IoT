// const eventSource = new EventSource('/iot/server.php');
// click menu
var menuBt = document.querySelector('.menuBt');
var checkMenu = 0;
var menu = this.document.querySelector('.menu');
function isClickedOutside(element, x, y) {
    var rect = element.getBoundingClientRect();
    return (x < rect.left || x > rect.right || y < rect.top || y > rect.bottom);
}
menuBt.addEventListener('click',function(){
    if(checkMenu==0){
        checkMenu = 1;
        menu.style.display = 'block';
        setTimeout(function(){
            document.body.onclick = function(event){
                if(isClickedOutside(menu,event.clientX,event.clientY)){
                    menu.style.display = 'none';
                    checkMenu = 0;
                    document.body.onclick = function(){
                    };
                }
            };
        },100);
    }
    else{
        checkMenu = 0;
        menu.style.display = 'none';
    }
})
//chart
function getTime(){
    var currentTime = new Date();
    let time = '';
    time+=currentTime.getHours()+':'+currentTime.getMinutes()+':'+currentTime.getSeconds();
    return time;
}
var chart = document.querySelector('#chart2');
var label = [0,0,0,0,0,0,0,0];
var temperature = [0,0,0,0,0,0,0,0];
var humidity = [0,0,0,0,0,0,0,0];
var blightness = [0,0,0,0,0,0,0,0];
var bui = [0,0,0,0,0,0,0,0];
var data = {
    labels: label,
    datasets: [
        {
            label:'Nhiệt độ(°C)',
            data: temperature,
            borderColor: 'red',
            fill: false,
            tension: 0.4
        },
        {
            label: 'Độ ẩm(%)',
            data: humidity,
            borderColor: 'blue',
            fill: false,
            tension: 0.4
        },
        {
            label: 'Ánh sáng(10 lux)',
            data: blightness,
            borderColor: 'yellow',
            fill: false,
            tension: 0.4
        }
    ]
};
var data1 = {
    labels: label,
    datasets: [
        {
            label:'Độ bụi',
            data: bui,
            borderColor: 'brown',
            fill: false,
            tension: 0.4
        }
    ]
};
var options = {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  };
var chartView = new Chart(chart,{
    type: 'line',
    data: data,
    options: options
});
var chartView1 = new Chart(chart1,{
    type:'line',
    data:data1,
    options: options
})
var x = 0;
var y = 0;
var z = 0;
var t = 0;
var start = 0;
var end = 0;
var indexData = 0;
var maxPageData = 1;

// add data history
var dataHistory = document.querySelector('.dataHistory .table');
function updateDataHistory(){
    let http = new XMLHttpRequest();
    let getUrl = `/iot/api/getlistdata?index=${indexData}`;
    let nhietDo = document.querySelector('#inputNhietDo').value;
    let doAm = document.querySelector('#inputDoAm').value;
    let anhSang = document.querySelector('#inputAnhSang').value;
    let doBui = document.querySelector('#inputDoBui').value;
    let sortBy = document.querySelector('#sortBy').value;
    let optionSort = document.querySelector('#optionSort').value;
    if(start!=""){
        getUrl+=`&start=${JSON.stringify(start)}`;
    }
    if(end!=""){
        getUrl+=`&end=${JSON.stringify(end)}`;
    }
    if(doBui.trim()!=''){
        getUrl+=`&dobui=${doBui}`;
    }
    if(nhietDo.trim()!=''){
        getUrl+=`&nhietdo=${nhietDo}`;
    }
    if(doAm.trim()!=''){
        getUrl+=`&doam=${doAm}`;
    }
    if(anhSang.trim()!=''){
        getUrl+=`&anhsang=${anhSang}`;
    }
    getUrl+=`&sortby=${sortBy}&optionsort=${optionSort}`;
    // alert(getUrl);
    http.open('get',getUrl,true);
    http.onload = function(){
        if(http.status == 200){
            let listData = [];
            console.log(http.responseText);
            listData = JSON.parse(http.responseText);
            maxPageData = (listData.count / 25) | 0 ;
            if(maxPageData==0) listAPBt2[1].classList.add('disable');
            else listAPBt2[1].classList.remove('disable');
            let listDataHtml = '';
            listData['listData'].forEach((i) => {
                listDataHtml += 
                `
                <tr>
                    <td>${i.id}</td>
                    <td>${i.dobui.toFixed(3)}</td>
                    <td>${i.temperature.toFixed(3)}</td>
                    <td>${i.humidity.toFixed(3)}</td>
                    <td>${(i.blightness).toFixed(3)}</td>
                    <td>${getDateTime(i.time)}</td>
                </tr>
                `;
            });
            dataHistory.innerHTML = 
            `
            <tr>
                <td>ID</td>
                <td>Độ Bụi</td>
                <td>Temperature(&deg;C)</td>
                <td>Humidity(%)</td>
                <td>Blightness(Lux)</td>
                <td>Time</td>
            </tr>
            `+listDataHtml;
        }
    }
    http.send();
}

var host = "ws://127.0.0.1:8888";
var  ws = new WebSocket(host);
ws.onmessage = function(event){
    console.log(data);
    var listData = JSON.parse(event.data);
        x = listData.temperature;
        y = listData.humidity;
        z = listData.blightness/10;
        t = listData.doBui;
}

function sendData(){
    ws.send("data");
}

function updateChart(){
    // $.ajax({
    //     url: '/iot/waitmess?topic=data',
    //     type: 'get',
    //     success: function(d) {
    //         var listData = JSON.parse(d);
    //         x = listData.temperature;
    //         y = listData.humidity;
    //         z = listData.blightness/10;
    //     },
    // });
    // alert(x);
    // let x = Math.random()*100;
    sendData();
    document.getElementById('nhietDo').style.backgroundColor = 'rgba(255,0,0,'+(x/100).toString()+')';
    temperature.push(x);
    // let y = Math.random()*100;
    document.getElementById('doAm').style.backgroundColor = 'rgba(0,0,255,'+(y/100).toString()+')';
    humidity.push(y);
    // let z = Math.random()*100;
    document.getElementById('doSang').style.backgroundColor = 'rgba(255,255,0,'+(z/100).toString()+')';
    blightness.push(z);
    document.getElementById('doBui').style.backgroundColor = 'rgba(165,42,42,'+(t/100).toString()+')';
    bui.push(t);
    document.querySelector('.temperature').innerText = x.toFixed(1);
    document.querySelector('.humidity').innerText = y.toFixed(1);
    document.querySelector('.blightness').innerText = (z*10).toFixed(1);
    document.querySelector('.doBui').innerText = (t).toFixed(1);


    label.push(getTime());
    chartView.data.labels = label;
    chartView.data.datasets[0].data = temperature;
    chartView.data.datasets[1].data = humidity;
    chartView.data.datasets[2].data = blightness;
    chartView1.data.datasets[0].data = bui;
    // chartView.width = 1500;
    // chartView.height = 500;
    chartView.update();
    chartView1.update();
    if(temperature.length>8) temperature.shift();
    if(humidity.length>8) humidity.shift();
    if(blightness.length>8) blightness.shift();
    if(bui.length>8) bui.shift();
    if(label.length>8) label.shift();
}

setInterval(updateChart,2000);

//button led fan
var listLedBt = document.querySelectorAll('#ledBt');
var listDot = document.querySelectorAll('.dotBt');
var listIconBt = document.querySelectorAll('#iconBt');
var listCheckBt = [(ledon==1) ? true:false, (ledon1==1) ? true:false, (fanon==1)?true:false];
var listAnimation = ['ledOn','ledOn','fa-spin']


async function deviceControl(led, led1, fan,i){
    $.ajax({
        url:`/iot/api/devicecontrol?led=${led}&led1=${led1}&fan=${fan}`,
        type:'get',
        success: function(data){
            if(data=='ok'){
                actionBt(i);
            }
            else{
                alert('fail');
                isClickBt[i] = true;
            }
        }
    })
}

for(let i=0;i<listCheckBt.length;i++){
    if(listCheckBt[i]){
        listDot[i].classList.add('btOn');
        listIconBt[i].classList.add(listAnimation[i]);
    }
    else{
        listDot[i].classList.remove('btOn');
        listIconBt[i].classList.remove(listAnimation[i]);
    }
}
var isClickBt = [true,true,true];

// animation on off bt
function actionBt(i){
    let s = '';
    if(i==0) s = 'led ';
    else if(i==1) s = 'led1 ';
    else s = 'fan ';
    if(listCheckBt[i]==true){
        listIconBt[i].classList.remove(listAnimation[i])
        listDot[i].classList.remove('btOn');
        addAction(s,'off');
    }
    else{
        listIconBt[i].classList.add(listAnimation[i])
        listDot[i].classList.add('btOn');
        addAction(s,'on');
    }
    listCheckBt[i] = !listCheckBt[i];
    isClickBt[i] = true;
    // alert('set isClickBt='+isClickBt[i]);
}
// add event
for(let i=0;i<listLedBt.length;i++){
    listLedBt[i].addEventListener('click',function(){
        // alert('isClickBt:'+isClickBt[i]);
        if(isClickBt[i]==true){
            isClickBt[i] = false;
            checkNhapNhay = !checkNhapNhay;
            if(listCheckBt[i]==false){
                if(i==0) ledon = 1;
                else if(i==1) ledon1 = 1;
                else fanon = 1;
            }
            else{
                if(i==0) ledon = 0;
                else if(i==1) ledon1 = 0;
                else fanon = 0;
            }
            deviceControl(ledon,ledon1,fanon,i);
        }
    })
}

//click tab
var listTab = document.querySelectorAll('.tab');
function closeAllTab(){
    listTabName.forEach(function(tab){
        document.querySelector(tab).style.display = 'none';
    })
}
var listTabName = ['.dashboard','.profile','.operationHistory','.dataHistory'];
for(let i=0;i<listTab.length;i++){
    listTab[i].addEventListener('click',function(){
        closeAllTab();
        document.querySelector(listTabName[i]).style.display = 'block';
        if(i==2) updateHistory();
        if(i==3) updateDataHistory();
    })
}

function getDateTime(d){
    // alert(d);
    let parts = d.split(" ");
    let datePart = parts[0];
    let timePart = parts[1];
    let [year, month, day] = datePart.split("-");
    let [hour, minute, second] = timePart.split(":");
    res = `${hour}:${minute}:${second} ${day}/${month}/${year}`;
    return res;
}
// hien thi o chon ngay
$(function() {
    $("#start_date").datetimepicker({
        format: 'Y-m-d H:i:s', // Định dạng ngày và giờ
        step: 1, // Bước thời gian (1 giây)
        defaultTime: '00:00:00', // Giờ mặc định
    });

    // Kích hoạt datetimepicker cho ô chọn thời gian kết thúc
    $("#end_date").datetimepicker({
        format: 'Y-m-d H:i:s', // Định dạng ngày và giờ
        step: 1, // Bước thời gian (1 giây)
        defaultTime: '23:59:59', // Giờ mặc định
    });
});

// click loc sau khi chon ngay
var searchData = document.querySelector('#search');
function getDate(date){
    let list = date.split('/');
    if(list.length<3){
        return '';
    }
    return `${list[2]}-${list[0]}-${list[1]}`;
}
searchData.onclick = function(){
    start = document.querySelector('#start_date').value;
    end = document.querySelector('#end_date').value;
    indexData=0;
    listAPBt2[0].classList.add('disable');
    updateDataHistory();
}

// click reset
var resetBt = document.getElementById('reset');
var listInputText = document.querySelectorAll('.text');
resetBt.onclick = function(){
    listInputText.forEach((i)=>{
        i.value = '';
    });
}
listInputText.forEach((i)=>{
    i.addEventListener('keydown',function(event){
        if(event.key == 'Enter'){
            searchData.onclick();
        }
    })
})

// button next and previous page
var listAPBt2 = document.querySelectorAll('.dataHistory .pageAction div');
for(let i=0;i<listAPBt2.length;i++){
    listAPBt2[i].onclick = function(){
        if(i==0 && indexData>0){
            indexData-=1;
            if(indexData==0){
                // listBtAP[i].style.color = 'brown';
                listAPBt2[i].disabled = true;
                listAPBt2[i].classList.add('disable');
            }
            else{
                // listBtAP[1].style.color = 'blue';
                listAPBt2[1].disabled = false;
                listAPBt2[1].classList.remove('disable');
            }
            updateDataHistory();
        }
        if(i==1 && indexData<maxPageData){
            indexData+=1;
            updateDataHistory();
            if(indexData<maxPageData){
                // listBtAP[0].style.color = 'blue';
                listAPBt2[0].disabled = false;
                listAPBt2[0].classList.remove('disable');
            }
            else{
                // listBtAP[i].style.color = 'brown';
                listAPBt2[i].disabled = true;
                listAPBt2[i].classList.add('disable');
            }
        }
    }
}
// operationHistory
$(function() {
    $("#start_date1").datetimepicker({
        format: 'Y-m-d H:i:s', // Định dạng ngày và giờ
        step: 1, // Bước thời gian (1 giây)
        defaultTime: '00:00:00', // Giờ mặc định
    });

    // Kích hoạt datetimepicker cho ô chọn thời gian kết thúc
    $("#end_date1").datetimepicker({
        format: 'Y-m-d H:i:s', // Định dạng ngày và giờ
        step: 1, // Bước thời gian (1 giây)
        defaultTime: '23:59:59', // Giờ mặc định
    });
});
var operationHistory = document.querySelector(".operationHistory .table");
var indexAction = 0;
function updateHistory(){
    let start = document.querySelector('.operationHistory #start_date1').value;
    let end = document.querySelector('.operationHistory #end_date1').value;
    let url = '/iot/api/getlistaction?';
    if(start.trim()!=''){
        url+=`start=${JSON.stringify(start)}`;
    }
    if(end.trim()!=''){
        url+=`&end=${JSON.stringify(end)}`
    }
    url+=`&index=${indexAction}`;
    // alert(url);
    $.ajax({
        url:url,
        type:'get',
        success: function(data){
            console.log(data);
            let listData = JSON.parse(data);
            let listAction = listData['listAction'];
            maxPageAction = (listData['count']/20) | 0; 
            if(maxPageAction==0) listAPBt2[1].classList.add('disable');
            else listAPBt2[1].classList.remove('disable');
            let listHtml = `
            <tr>
                <td>ID</td>
                <td>Device</td>
                <td>Action</td>
                <td>Time</td>
            </tr>
            `;
            listAction.forEach(function(i){
                listHtml+=`
                <tr>
                    <td>${i.id}</td>
                    <td>${i.device}</td>
                    <td>${i.action}</td>
                    <td>${getDateTime(i.time)}</td>
                </tr>
                `
            });
            operationHistory.innerHTML = listHtml;
        }
    })
}
function addAction(device, action){
    // alert(`/iot/api/addaction?device=${JSON.stringify(device)}&action=${JSON.stringify(action)}`);
    $.ajax({
        url:`/iot/api/addaction?device=${JSON.stringify(device)}&action=${JSON.stringify(action)}`,
        type:'get',
        success: function(d){

        }
    })
}
var searchActionBt = document.querySelector('#searchOH');
var maxPageAction = 1;
searchActionBt.onclick = function(){
    indexAction = 0;
    listAPBt1[0].classList.add('disable');
    updateHistory();
};
var listAPBt1 = document.querySelectorAll('.operationHistory .pageAction div');
for(let i=0;i<listAPBt1.length;i++){
    listAPBt1[i].onclick = function(){
        if(i==0 && indexAction>0){
            // alert(i);
            indexAction-=1;
            if(indexAction==0){
                // listBtAP[i].style.color = 'brown';
                listAPBt1[i].disabled = true;
                listAPBt1[i].classList.add('disable');
            }
            else{
                // listBtAP[1].style.color = 'blue';
                listAPBt1[1].disabled = false;
                listAPBt1[1].classList.remove('disable');
            }
            updateHistory();
        }
        if(i==1 && indexAction<maxPageAction){
            // alert(i);
            indexAction+=1;
            updateHistory();
            if(indexAction==maxPageAction){
                // listBtAP[0].style.color = 'blue';
                listAPBt1[1].disabled = true;
                listAPBt1[1].classList.add('disable');
            }
            else{
                // listBtAP[i].style.color = 'brown';
                listAPBt1[0].disabled = false;
                listAPBt1[0].classList.remove('disable');
            }
        }
    }
}
var resetBt1 = document.querySelector('#reset1');
resetBt1.onclick = function(){
    let listInput = document.querySelectorAll(".operationHistory input");
    listInput.forEach(i => {
        i.value = '';
    })
}
var check = false;
var checkNhapNhay = false;
var BRListLedBt = document.querySelector('.listLedBt');
var sendOne = true;
setInterval(function(){
    if(bui[bui.length-1]>80){
        if(check) BRListLedBt.style.backgroundColor = 'red';
        else BRListLedBt.style.backgroundColor = 'aliceblue';
        check = !check;
        if(sendOne){
            sendOne = !sendOne;
            $.ajax({
                url:`/iot/api/sendmess?topic=nhapnhay&mess=${JSON.stringify(1)}`,
                type:"get",
                success: function(data){

                }
            });
        }
    }
    else{
        if(sendOne==false){
            sendOne = !sendOne;
            $.ajax({
                url:`/iot/api/sendmess?topic=nhapnhay&mess=${JSON.stringify(0)}`,
                type:"get",
                success: function(data){

                }
            });
        }
        BRListLedBt.style.backgroundColor = 'aliceblue';
    }
},500);