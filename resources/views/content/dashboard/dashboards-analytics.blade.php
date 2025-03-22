@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
  @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
  @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
  @vite('resources/assets/js/dashboards-analytics.js')
  @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection


<!-- Include ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  var monthlySales = @json($monthlySales);
  var chartElement = document.getElementById("weeklyOverviewChart");


  if (chartElement) {
    var ctx = chartElement.getContext("2d");
  } else {
    console.error("Chart element not found!");
  }
  var salesChart;

  $(document).ready(function () {
    fetchSalesData(); // Fetch sales data when the page loads
    fetchTotalEarnings();
    fetchWeeklyProfit();
    fetchNewOrderStats();
  });

  // If no data, show a placeholder message
  if (!monthlySales || monthlySales.length === 0) {
    document.getElementById('weeklyOverviewChart').innerHTML = '<p class="text-center">No sales data available.</p>';
  } else {
    var labels = monthlySales.map(data => data.month);
    var sales = monthlySales.map(data => parseFloat(data.total_sales));

    let lastMonthSales = sales.length > 1 ? sales[sales.length - 2] : 0;
    let currentMonthSales = sales.length > 0 ? sales[sales.length - 1] : 0;
    let percentageChange = lastMonthSales > 0 ? ((currentMonthSales - lastMonthSales) / lastMonthSales * 100).toFixed(2) : 0;

    document.getElementById('salesPercentage').innerText = percentageChange + "%";
    document.getElementById('salesComparison').innerText = percentageChange > 0
      ? `Your sales performance is ${percentageChange}% 😎 better compared to last month`
      : `Your sales performance decreased by ${percentageChange}% 😞 compared to last month`;

    var options = {
      series: [{
        name: "Total Sales (RM)",
        data: sales
      }],
      chart: {
        type: 'bar',
        height: 350,
        animations: {
          enabled: true,
          easing: 'easeinout',
          speed: 800,
          animateGradually: {
            enabled: true,
            delay: 150
          },
          dynamicAnimation: {
            enabled: true,
            speed: 350
          }
        }
      },
      xaxis: {
        categories: labels
      }
    };

    var chart = new ApexCharts(document.querySelector("#weeklyOverviewChart"), options);
    chart.render();
  }

  function fetchSalesData() {
    $.ajax({
      url: "/sales-data",
      type: "GET",
      dataType: "json",
      success: function (response) {

        if (!response || response.length === 0) {
          console.error("No data received for the chart.");
          return;
        }

        let labels = response.map(data => "Week " + data.week);
        let sales = response.map(data => parseFloat(data.total_sales));

        let sortedData = response.sort((a, b) => a.week - b.week);
        labels = sortedData.map(data => "Week " + data.week);
        sales = sortedData.map(data => parseFloat(data.total_sales));

        let lastWeekSales = sales[sales.length - 1] || 0;
        let prevWeekSales = sales[sales.length - 2] || 0;
        let percentageChange = prevWeekSales > 0 ? ((lastWeekSales - prevWeekSales) / prevWeekSales * 100) : 0;
        percentageChange = Math.round(percentageChange);

        let salesPercentageElem = $("#salesPercentage");
        let salesComparisonElem = $("#salesComparison");

        salesPercentageElem.text(percentageChange + "%");

        if (percentageChange > 0) {
          salesPercentageElem.css("color", "green");
          salesComparisonElem.html(`Your sales performance is <strong>${percentageChange}%</strong> 😎 better compared to last week`);
        } else if (percentageChange < 0) {
          salesPercentageElem.css("color", "red");
          salesComparisonElem.html(`Your sales performance decreased by <strong>${Math.abs(percentageChange)}%</strong> 😞 compared to last week`);
        } else {
          salesPercentageElem.css("color", "gray");
          salesComparisonElem.html(`Your sales performance remained the same as last week.`);
        }

        // Destroy previous chart if it exists
        if (salesChart) {
          salesChart.destroy();
        }

        var ctx = document.getElementById("weeklyOverviewChart").getContext("2d");

        salesChart = new Chart(ctx, {
          type: "bar",
          data: {
            labels: labels,
            datasets: [{
              label: "Weekly Sales",
              data: sales,
              backgroundColor: sales.map((value, index) =>
                index === sales.length - 1 ? "rgba(112, 48, 160, 1)" : "rgba(112, 48, 160, 0.3)"
              ),
              borderRadius: 10, // Rounded corners
              barThickness: 20, // Set bar width (Adjust this value)
              borderSkipped: false, // Makes all bars rounded
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
              padding: {
                top: 10,
                bottom: 10 // Reduce spacing at the bottom
              }
            },
            scales: {
              x: {
                grid: {
                  display: false, // Remove X-axis grid lines
                },

              },
              y: {
                beginAtZero: true,
                ticks: {
                  display: false // Hide Y-axis labels
                },
              }
            },
            plugins: {
              legend: {
                display: false // Hide legend
              }
            }
          }
        });
      },
      error: function (xhr, status, error) {
        console.error("Error fetching sales data:", error);
      }
    });
  }

  function fetchTotalEarnings() {
    $.ajax({
      url: "/getTotalEarnings",
      type: "GET",
      dataType: "json",
      success: function (data) {

        // Update Total Earnings
        $("#totalEarningsAmount").text("RM " + parseFloat(data.currentMonthEarnings).toFixed(2));
        $("#totalEarningsAmount2").text("RM " + parseFloat(data.currentMonthEarnings).toFixed(2));

        // Update Percentage Change
        let percentageChange = parseFloat(data.percentageChange);
        let percentageElement = $("#percentageChange");

        if (percentageChange >= 0) {
          percentageElement.html('<i class="ri-arrow-up-s-line"></i> <span>' + percentageChange + '%</span>');
          percentageElement.css("color", "green");
        } else {
          percentageElement.html('<i class="ri-arrow-down-s-line"></i> <span>' + Math.abs(percentageChange) + '%</span>');
          percentageElement.css("color", "red");
        }

        // Update Last Month Comparison
        $("#lastMonthComparison").text("Compared to RM " + parseFloat(data.lastMonthEarnings).toFixed(2) + " last month");
        $("#lastMonth").text("RM" + parseFloat(data.lastMonthEarnings).toFixed(2));

        // Update Top Brands List
        let topBrandsList = $("#topBrandsList");
        topBrandsList.empty();

        data.topBrands.forEach((brand, index) => {
          let brandPercentage = (brand.total_sales / data.currentMonthEarnings) * 100;

          let progressBarColor = "bg-primary";
          if (index === 0) progressBarColor = "bg-warning";
          else if (index === 1) progressBarColor = "bg-secondary";
          else if (index === 2) progressBarColor = "bg-danger";

          let brandImage = `assets/img/icons/misc/${brand.BrandName.toLowerCase()}.png`;

          topBrandsList.append(`
            <li class="d-flex mb-6">
              <div class="avatar flex-shrink-0 bg-lightest rounded me-3">
                <img src="${brandImage}" alt="${brand.BrandName}" onerror="this.src='assets/img/icons/misc/default.png'">
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-0">${brand.BrandName}</h6>
                  <p class="mb-0">Total Sold: ${brand.total_sold}</p>
                </div>
                <div>
                  <h6 class="mb-2">RM ${parseFloat(brand.total_sales).toFixed(2)}</h6>
                  <div class="progress" style="height: 4px;">
                    <div class="progress-bar ${progressBarColor}" style="width: ${brandPercentage}%;"
                      role="progressbar" aria-valuenow="${brandPercentage}" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
            </li>
          `);
        });
      },
      error: function (xhr, status, error) {
        console.error("Error fetching earnings data:", error);
      }
    });
  }

  function fetchWeeklyProfit() {
    fetch("{{ route('weekly-profit') }}")
      .then(response => response.json())
      .then(data => {
        console.log("API Response:", data); // Check what the backend is returning

        let totalSales = data.totalSales;
        let currentWeekSales = data.currentWeekSales;

        console.log("Raw totalSales:", totalSales); // Debugging
        console.log("Raw currentWeekSales:", currentWeekSales);

        // Ensure the numbers are properly converted
        let converterTotalSales = totalSales.toString().replace(/,/g, ""); // Remove commas
        let convertercurrentWeekSales = currentWeekSales.toString().replace(/,/g, ""); // Remove commas
        console.log("Number without commas:", converterTotalSales);
        totalSales = totalSales !== undefined && totalSales !== null ? parseFloat(converterTotalSales) : 0;
        currentWeekSales = currentWeekSales !== undefined && currentWeekSales !== null ? parseFloat(convertercurrentWeekSales) : 0;

        let totalSalesBeforeThisWeek = totalSales - currentWeekSales;
        let percentageProfit = ((currentWeekSales / totalSalesBeforeThisWeek) * 100).toFixed(2);



        document.getElementById("currentWeekSales").textContent = `RM ${totalSales}`;
        document.getElementById("lastWeekSales").textContent = `RM ${totalSalesBeforeThisWeek}`;
        document.getElementById("profitGrowth").textContent = `(${percentageProfit}%)`;

      })
      .catch(error => console.error("Error fetching weekly profit:", error));

  };


  function fetchNewOrderStats() {
    $.ajax({
      url: "/getNewOrderStats",
      type: "GET",
      dataType: "json",
      success: function (data) {
        $("#newOrderCount").text(data.currentMonthOrders);

        let orderPercentageElem = $("#orderPercentage");
        let percentageChange = parseFloat(data.percentageChange);

        orderPercentageElem.text(percentageChange + "%");

        if (percentageChange > 0) {
          orderPercentageElem.css("color", "green").html(`<i class="ri-arrow-up-s-line"></i> ${percentageChange}%`);
        } else if (percentageChange < 0) {
          orderPercentageElem.css("color", "red").html(`<i class="ri-arrow-down-s-line"></i> ${Math.abs(percentageChange)}%`);
        } else {
          orderPercentageElem.css("color", "gray").text("0%");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error fetching new order stats:", error);
      }
    });
  }

</script>


@section('content')
  <div class="row gy-6">
    <!-- Congratulations card -->
    <div class="col-md-12 col-lg-4">
    <div class="card">
      <div class="card-body text-nowrap">
      <h5 class="card-title mb-0 flex-wrap text-nowrap">Congratulations Norris! 🎉</h5>
      <p class="mb-2">Best seller of the month</p>
      <h4 class="text-primary mb-0" id="lastMonth">Loading...</h4>
      <p class="mb-2">78% of target 🚀</p>
      <a href="javascript:;" class="btn btn-sm btn-primary">View Sales</a>
      </div>
      <img src="{{asset('assets/img/illustrations/trophy.png')}}" class="position-absolute bottom-0 end-0 me-5 mb-5"
      width="83" alt="view sales">
    </div>
    </div>
    <!--/ Congratulations card -->

    <!-- Transactions -->
    <div class="col-lg-8">
    <div class="card h-100">
      <div class="card-header">
      <div class="d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0 me-2">Transactions</h5>
        <div class="dropdown">
        <button class="btn text-muted p-0" type="button" id="transactionID" data-bs-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <i class="ri-more-2-line ri-24px"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
          <a class="dropdown-item" href="javascript:void(0);">Share</a>
          <a class="dropdown-item" href="javascript:void(0);">Update</a>
        </div>
        </div>
      </div>

      </div>
      <div class="card-body pt-lg-10">
      <div class="row g-6">
        <div class="col-md-3 col-6">
        <div class="d-flex align-items-center">
          <div class="avatar">
          <div class="avatar-initial bg-primary rounded shadow-xs">
            <i class="ri-pie-chart-2-line ri-24px"></i>
          </div>
          </div>
          <div class="ms-3">
          <p class="mb-0">Sales</p>
          @if(isset($totalSales))
        <h5 class="mb-0" id="totalEarningsAmount2">RM {{ number_format($totalSales, 2) }}</h5>
      @else
      <h5 class="mb-0">RM 0.00</h5>
    @endif
          </div>
        </div>
        </div>
        <div class="col-md-3 col-6">
        <div class="d-flex align-items-center">
          <div class="avatar">
          <div class="avatar-initial bg-success rounded shadow-xs">
            <i class="ri-group-line ri-24px"></i>
          </div>
          </div>
          <div class="ms-3">
          <p class="mb-0">Customers</p>
          <h5 class="mb-0">{{ $totalCustomers }}</h5>

          </div>
        </div>
        </div>
        <div class="col-md-3 col-6">
        <div class="d-flex align-items-center">
          <div class="avatar">
          <div class="avatar-initial bg-warning rounded shadow-xs">
            <i class="ri-macbook-line ri-24px"></i>
          </div>
          </div>
          <div class="ms-3">
          <p class="mb-0">Product</p>
          <h5 class="mb-0">{{ $totalProducts }}</h5>
          </div>
        </div>
        </div>
        <div class="col-md-3 col-6">
        <div class="d-flex align-items-center">
          <div class="avatar">
          <div class="avatar-initial bg-info rounded shadow-xs">
            <i class="ri-money-dollar-circle-line ri-24px"></i>
          </div>
          </div>
          <div class="ms-3">
          <p class="mb-0">Total Order</p>
          <h5 class="mb-0">{{ $totalOrders }}</h5>
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
    <!--/ Transactions -->

    <!-- Weekly Overview Chart -->
    <div class="col-xl-4 col-md-6">
    <div class="card">
      <div class="card-header">
      <div class="d-flex justify-content-between">
        <h5 class="mb-1">Weekly Sales Overview</h5>
        <div class="dropdown">
        <button class="btn text-muted p-0" type="button" id="weeklyOverviewDropdown" data-bs-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <i class="ri-more-2-line ri-24px"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="weeklyOverviewDropdown">
          <a class="dropdown-item" href="javascript:void(0);" onclick="fetchSalesData()">Refresh</a>
          <a class="dropdown-item" href="javascript:void(0);">Share</a>
          <a class="dropdown-item" href="javascript:void(0);">Update</a>
        </div>
        </div>
      </div>
      </div>
      <div style="height: 335px; width: 100%;" class="card-body pt-lg-2"> <!-- Adjusted height -->
      <div style="height: 65%; width: 100%;">
        <canvas id="weeklyOverviewChart"></canvas>
      </div>
      <div class="mt-1 mt-md-3">
        <div class="d-flex align-items-center gap-4">
        <h4 class="mb-0" id="salesPercentage">0%</h4>
        <p class="mb-0" id="salesComparison"></p>
        </div>
        <div class="d-grid mt-3 mt-md-4">
        <button class="btn btn-primary" type="button">Details</button>
        </div>
      </div>
      </div>
    </div>
    </div>
    <!--/ Weekly Overview Chart -->

    <!-- Total Earnings -->
    <div class="col-xl-4 col-md-6">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="card-title m-0 me-2">Total Earning</h5>
      <div class="dropdown">
        <button class="btn text-muted p-0" type="button" id="totalEarnings" data-bs-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="ri-more-2-line ri-24px"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalEarnings">
        <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
        <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
        <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
        </div>
      </div>
      </div>

      <div class="card-body pt-lg-8">
      <div class="mb-5 mb-lg-12">
        <div class="d-flex align-items-center">
        <h3 class="mb-0" id="totalEarningsAmount">RM 0.00</h3>
        <span class="ms-2" id="percentageChange">
          <i class="ri-arrow-up-s-line"></i> <span>0%</span>
        </span>
        </div>
        <p class="mb-0" id="lastMonthComparison">Compared to RM 0.00 last month</p>
      </div>

      <!-- Top Brands List -->
      <ul id="topBrandsList" class="p-0 m-0">
        <!-- Dynamically Populated -->
      </ul>
      </div>
    </div>
    </div>

    <!-- Four Cards -->
    <div class="col-xl-4 col-md-6">
    <div class="row gy-6">
      <!-- Total Profit line chart -->
      <div class="col-sm-6">
      <div class="card h-100">
        <div class="card-header pb-0">
        @if(isset($totalSales))
      <h5 class="mb-0">RM {{ number_format($totalSales, 2) }}</h5>
    @else
    <h5 class="mb-0">RM 0.00</h5>
  @endif
        </div>
        <div class="card-body">
        <div id="totalProfitLineChart" class="mb-3" style="height: 100px;"></div>
        <h6 class="text-center mb-0">Total Profit</h6>
        </div>
      </div>
      </div>
      <!--/ Total Profit line chart -->
      <!-- Total Profit Weekly Project -->
      <div class="col-sm-6">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
        <div class="avatar">
          <div class="avatar-initial bg-secondary rounded-circle shadow-xs">
          <i class="ri-pie-chart-2-line ri-24px"></i>
          </div>
        </div>
        <div class="dropdown">
          <button class="btn text-muted p-0" type="button" id="totalProfitID" data-bs-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <i class="ri-more-2-line ri-24px"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalProfitID">
          <a class="dropdown-item" href="javascript:void(0);" onclick="fetchWeeklyProfit()">Refresh</a>
          </div>
        </div>
        </div>
        <div class="card-body">
        <h6 class="mb-1">Total Profit</h6>
        <div class="d-flex flex-wrap mb-1 align-items-center">
          @if(isset($totalSales))
        <h5 class="mb-0">RM {{ number_format($totalSales, 2) }}</h5>
      @else
      <h5 class="mb-0">RM 0.00</h5>
    @endif
          <span id="profitGrowth"
          style="font-weight: bold; margin-left: 115px; margin-top: -30px; color: green;">Loading...</span>
        </div>
        <p hidden>Total Sales This Week: <strong id="currentWeekSales">Loading...</strong></p>
        <p>Last Month Total Profit: <strong id="lastWeekSales">Loading...</strong></p>

        </div>
      </div>
      </div>
      <!--/ Total Profit Weekly Project -->
      <!-- New Order Project -->
      <div class="col-sm-6">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
        <div class="avatar">
          <div class="avatar-initial bg-primary rounded-circle shadow-xs">
          <i class="ri-file-word-2-line ri-24px"></i>
          </div>
        </div>
        <div class="dropdown">
          <button class="btn text-muted p-0" type="button" id="newProjectID" data-bs-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <i class="ri-more-2-line ri-24px"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="newProjectID">
          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
          </div>
        </div>
        </div>
        <div class="card-body">
        <h6 class="mb-1">New Order</h6>
        <div class="d-flex flex-wrap mb-1 align-items-center">
          <h4 class="mb-0 me-2" id="newOrderCount">0</h4>
          <p class="mb-0" id="orderPercentage">0%</p>
        </div>
        <small>Monthly Project</small>
        </div>
      </div>
      </div>
      <!--/ New Order Project -->

      <!-- Sessions chart -->
      <div class="col-sm-6">
      <div class="card h-100">
        <div class="card-header pb-0">
        <h4 class="mb-0">2,856</h4>
        </div>
        <div class="card-body">
        <div id="sessionsColumnChart" class="mb-3"></div>
        <h6 class="text-center mb-0">Sessions</h6>
        </div>
      </div>
      </div>
      <!--/ Sessions chart -->
    </div>
    </div>
    <!--/ four cards -->

  </div>
@endsection