<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="./View/js/chart.js"></script>
    <link rel="stylesheet" href="./View/css/css.css">
    <!-- Thư viện jQuery -->
    <script src="./View/js/jquery-3.6.0.min.js"></script>

    <!-- Thư viện jQuery UI -->
    <link rel="stylesheet" href="./View/css/jquery-ui.css">
    <script src="./View/js/jquery-ui.js"></script>

    <!-- Thư viện datetimepicker của jQuery -->
    <link rel="stylesheet" href="./View/css/jquery.datetimepicker.min.css">
    <script src="./View/js/jquery.datetimepicker.full.min.js"></script>
    
    <script>
        var ledon = <?=$ledon?>;
        var ledon1 = <?=$ledon1?>;
        var fanon = <?=$fanon?>;
    </script>
</head>
<body>
    <div class="content">
        <div class="menu">
            <div class="Name">QuangTX</div>
            <div class="listTab">
                <div class="tab">Dashboard</div>
                <div class="tab">Profile</div>
                <div class="tab">Operation history</div>
                <div class="tab">Data history</div>
            </div>
        </div>
        <div class="main">
            <div class="header">
                <div class="menuBt"><i class="fa-solid fa-bars"></i></div>
                <div class="title">IoT và ứng dụng</div>
            </div>
            <div class="dashboard">
                <h1 class="titleTab">Dashboard</h1>
                <p>Dashboard > <span style="color: cyan;">Home</span></p>
                <div class="info">
                    <div class="infoView" id="doBui">
                        <i class="fa-solid fa-temperature-three-quarters"></i>
                        <div>
                            <p>Độ bụi</p>
                            <p><span class="doBui">0</span>%</p>
                        </div>
                    </div>
                    <div class="infoView" id="nhietDo">
                        <i class="fa-solid fa-temperature-three-quarters"></i>
                        <div>
                            <p>Nhiệt độ</p>
                            <p><span class="temperature">0</span> &deg;C</p>
                        </div>
                    </div>
                    <div class="infoView" id="doAm">
                        <i class="fa-solid fa-cloud"></i>
                        <div>
                            <p>Độ ẩm</p>
                            <p><span class="humidity">0</span>%</p>
                        </div>
                    </div>
                    <div class="infoView" id="doSang">
                        <i class="fa-regular fa-sun"></i>
                        <div>
                            <p>Ánh sáng</p>
                            <p><span class="blightness">0</span> lux</p>
                        </div>
                    </div>
                </div>
                <div class="chartAndLedBt">
                    <div class="chart">
                        <div class="chart1">
                            <canvas id="chart1"></canvas>
                        </div>
                        <div class="chart2">
                            <canvas id="chart2"></canvas>
                        </div>
                    </div>
                    <div class="listLedBt">
                        <div class="ledBt">
                            <i class="fa-solid fa-lightbulb" id="iconBt"></i>
                            <div id="ledBt">
                                <div class="dotBt"></div>
                            </div>
                        </div>
                        <div class="ledBt">
                            <i class="fa-solid fa-lightbulb" id="iconBt"></i>
                            <div id="ledBt">
                                <div class="dotBt"></div>
                            </div>
                        </div>
                        <div class="ledBt">
                            <i class="fa-solid fa-fan" id="iconBt"></i>
                            <div id="ledBt">
                                <div class="dotBt"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile" style="display: none; min-height: 100vh;">
                <h1 class="titleProfile">My Profile</h1>
                <div class="profileContent">
                    <div class="avatarAndName">
                        <img class="avatarPhoto" src="./Data/Photos/avatar.jpg" alt="">
                        <h1>Quang Trịnh</h1>
                    </div>
                    <div class="fullInfo">
                        <p>Full Name:  <span>Trịnh Xuân Quang</span></p>
                        <p>Email:  <span>xq01022002@gmail.com</span></p>
                        <p>Phone:  <span>0399361475</span></p>
                        <p>Address:  <span>Ha Noi, Viet Nam</span></p>
                    </div>
                </div>
            </div>
            <div class="operationHistory" style="display: none; min-height: 100vh;">
                <div class="search">
                    <div class="selectTime">
                        <div class="inputBlock" style="margin-bottom: 5px;">
                            <label for="start_date">Ngày bắt đầu:</label>
                            <input type="text" id="start_date1" readonly style="margin-left: 4px;" class="text">
                        </div>

                        <div class="inputBlock">
                            <label for="end_date">Ngày kết thúc:</label>
                            <input type="text" id="end_date1" readonly class="text">
                        </div>
                    </div>
                    <button id="searchOH">Lọc</button>
                    <button id="reset1">reset</button>
                </div>
                <table class="table">
                    <tr>
                        <td>ID</td>
                        <td>Device</td>
                        <td>Action</td>
                        <td>Time</td>
                    </tr>
                </table>
                <div class="pageAction">
                    <div class="previous disable" disabled >previous</div>
                    <div class="next">next</div>
                </div>
            </div>
            <div class="dataHistory" style="display: none; min-height: 100vh;">
                <div class="search">
                    <div class="selectTime">
                        <div class="inputBlock" style="margin-bottom: 5px;">
                            <label for="start_date">Ngày bắt đầu:</label>
                            <input type="text" id="start_date" readonly style="margin-left: 4px;" class="text">
                        </div>

                        <div class="inputBlock">
                            <label for="end_date">Ngày kết thúc:</label>
                            <input type="text" id="end_date" readonly class="text">
                        </div>
                    </div>
                    
                    <div class="searchBy">
                        <div>
                            <label for="inputNhietDo"> Nhiệt độ: </label>
                            <input type="text" id="inputNhietDo" style="margin-left: 4px;" class="text">
                        </div>
                        <div>
                            <label for="inputDoAm">Độ ẩm: </label>
                            <input type="text" id="inputDoAm" style="margin-left: 17px;" class="text">
                        </div>
                        <div>
                            <label for="inputAnhSang">Ánh sáng: </label>
                            <input type="text" id="inputAnhSang" class="text">
                        </div>
                        <div>
                            <label for="inputDoBui">Độ bụi: </label>
                            <input type="text" id="inputDoBui" class="text" style="margin-left: 17px;">
                        </div>
                    </div>
                    <div class="condition">
                        <label for="sortBy">Sắp xếp theo:</label>
                        <select id="sortBy" >
                            <option value="time">Thời gian</option>
                            <option value="temperature">Nhiệt độ</option>
                            <option value="humidity">Độ ẩm</option>
                            <option value="blightness">Ánh sáng</option>
                            <option value="doBui">Độ bụi</option>
                        </select>
                        <select id="optionSort">
                        <option value="desc">Giảm dần</option>
                            <option value="asc">Tăng dần</option>
                        </select>
                        <input type="submit" value="reset" id="reset">
                    </div>
                </div>
                <input type="submit" value="Lọc" id="search">
                <table class="table">
                    <tr>
                        <td>ID</td>
                        <td>ĐỘ Bụi</td>
                        <td>Temperature(&deg;C)</td>
                        <td>Humidity(%)</td>
                        <td>Blightness(Lux)</td>
                        <td>Time</td>
                    </tr>
                </table>
                <div class="pageAction">
                    <div class="previous disable" disabled >previous</div>
                    <div class="next">next</div>
                </div>
            </div>
        </div>
    </div>
    <script src="./View/js/js.js"></script>
</body>
</html>