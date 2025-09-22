<?php
$thongKe = json_decode($data["ThongKeThang"], true);
$thongKeTuan = json_decode($data["ThongKeTuan"], true);
// Lấy ngày hiện tại và xác định tuần hiện tại
date_default_timezone_set('Asia/Ho_Chi_Minh');
$homnay = date('Y-m-d');
$week = date('w', strtotime($homnay));
// Tính ngày đầu tuần và cuối tuần
$dautuan = date('d-m-Y', strtotime($homnay . ' - ' . ($week ? $week - 1 : 6) . ' days'));
$cuoituan =date('d-m-Y', strtotime($dautuan . ' + 6 days'));
// Khởi tạo mảng dữ liệu cho các loại dịch vụ
$dataPoints1 = array(); // Khám bệnh
$dataPoints2 = array(); // Xét nghiệm

// Mảng theo dõi doanh thu cho từng tháng
$monthlyData = [];

// Lặp qua dữ liệu thống kê và phân loại theo tháng và dịch vụ
foreach ($thongKe as $row) {
    $thang = $row['Thang'];
    $tongTien = $row['TongTienTheoThang'] / 1000000; // Chuyển đổi tiền tệ sang triệu VND

    // Tạo mảng doanh thu theo tháng và dịch vụ
    $monthlyData[$thang][$row['DichVu']] = $tongTien;
}

// Lấy tất cả các tháng và sắp xếp theo thứ tự từ 1 đến 12
$months = range(1, 12);

// Điền dữ liệu vào mảng nếu thiếu
foreach ($months as $thang) {
    $dataPoints1[] = array("label" => "$thang", "y" => isset($monthlyData[$thang]['Khám bệnh']) ? $monthlyData[$thang]['Khám bệnh'] : 0);
    $dataPoints2[] = array("label" => "$thang", "y" => isset($monthlyData[$thang]['Xét nghiệm']) ? $monthlyData[$thang]['Xét nghiệm'] : 0);
}

//xử lý biểu đồ tuần
// Khởi tạo mảng để lưu tổng doanh thu theo dịch vụ
$totalByServiceWeek = [
    "Khám bệnh" => 0,
    "Xét nghiệm" => 0,
];

// Lặp qua dữ liệu để cộng dồn tổng doanh thu cho từng dịch vụ
foreach ($thongKeTuan as $row) {
    $dichVu = $row['DichVu'];
    $tongTien = $row['TongTienTheoTuan'] / 1000000;
    $totalByServiceWeek[$dichVu] += $tongTien;
}
$totalRevenueWeek = $totalByServiceWeek["Khám bệnh"] + $totalByServiceWeek["Xét nghiệm"];

// Tính tỷ lệ phần trăm
$percentageWeek = [
    "Khám bệnh" => $totalRevenueWeek > 0 ? ($totalByServiceWeek["Khám bệnh"] / $totalRevenueWeek) * 100 : 0,
    "Xét nghiệm" => $totalRevenueWeek > 0 ? ($totalByServiceWeek["Xét nghiệm"] / $totalRevenueWeek) * 100 : 0,
];
// Chuyển dữ liệu sang dạng dataPoints cho biểu đồ tròn
$dataPointsWeek = [
    ["label" => "Khám bệnh", "y" => $totalByServiceWeek["Khám bệnh"]],
    ["label" => "Xét nghiệm", "y" => $totalByServiceWeek["Xét nghiệm"]],
];
echo '<h2 class="text-center mb-4" style="background-color: #007bff; color: white; font-weight: bold; padding: 5px; border-radius: 5px;">Thống kê doanh thu</h2>';
echo '
    <div class="row mb-3">
    <div id="chartContainerWeek" style="height: 300px; width: 50%; border:1px #CFCFCF solid;"></div>
    <div id="chartsl" style="width: 50%;" class="mt-1">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
            <h4 class="text-center"><b>Tổng doanh thu từ '.$dautuan.' đến '.$cuoituan.'</b></h4>
                <tr>
                    <th scope="col">Dịch vụ</th>
                    <th scope="col">Tổng Doanh Thu</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Xét nghiệm</td>
                    <td>' . number_format($totalByServiceWeek["Xét nghiệm"], 6) . ' Triệu</td> 
                </tr>
                <tr>
                    <td>Khám bệnh</td>
                    <td>' . number_format($totalByServiceWeek["Khám bệnh"], 6) . ' Triệu</td> 
                </tr>
            </tbody>
        </table>
    </div>
    </div>
    <div id="chartContainerYear" style="height: 400px; width: 100%;" class="mb-4 mt-2"></div>';
?>

<script>
    window.onload = function () {
        // Biểu đồ cột cho doanh thu theo năm
        var chartYear = new CanvasJS.Chart("chartContainerYear", {
            animationEnabled: true,
            theme: "light2",
            title:{
                text: "Doanh thu giữa các dịch vụ trong năm 2024"
            },
            axisY:{
                includeZero: true,
                title: "Doanh thu (Triệu VND)"
            },
            legend:{
                cursor: "pointer",
                verticalAlign: "center",
                horizontalAlign: "right",
                itemclick: toggleDataSeries
            },
            data: [{
                type: "column",
                name: "Khám bệnh",
                indexLabel: "{y}Tr",
                yValueFormatString: "#0.##",
                showInLegend: true,
                dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
            },{
                type: "column",
                name: "Xét nghiệm",
                indexLabel: "{y}Tr",
                yValueFormatString: "#0.##",
                showInLegend: true,
                dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chartYear.render();

        // Biểu đồ tròn cho doanh thu theo tuần
        var pieChartWeek = new CanvasJS.Chart("chartContainerWeek", {
            theme: "light2",
            animationEnabled: true,
            title: {
                text: "Tỷ lệ doanh thu giữa các dịch vụ trong tuần"
            },
            data: [{
                type: "pie",
                indexLabel: "{label}: {y}",
                yValueFormatString: "#0.##%",
                // indexLabelPlacement: "inside",
                indexLabelFontColor: "#36454F",
                indexLabelFontSize: 11,
                indexLabelFontWeight: "bolder",
                showInLegend: true,
                legendText: "{label}",
                dataPoints: <?php echo json_encode($dataPointsWeek, JSON_NUMERIC_CHECK); ?>
            }]
        });
        pieChartWeek.render();

        function toggleDataSeries(e){
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            }
            else{
                e.dataSeries.visible = true;
            }
            chartYear.render();
        }
    }
</script>

