@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Dashboard')])

@section('content')
  <div class="content" id="dashboard">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
              <div class="card-icon">
                <i class="material-icons">mobile_screen_share</i>
              </div>
              <p class="card-category">Total Request</p>
              <h3 class="card-title">@{{dashboard.total_request}}
                {{-- <small>GB</small> --}}
              </h3>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons text-primary">arrow_right</i>
                <a href="#">Learn more...</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-danger card-header-icon">
              <div class="card-icon">
                <i class="material-icons">report_problem</i>
              </div>
              <p class="card-category">Total Failed</p>
              <h3 class="card-title">@{{dashboard.total_failed}}</h3>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons text-primary">arrow_right</i>
                <a href="#">Learn more...</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-success card-header-icon">
              <div class="card-icon">
                <i class="material-icons">info_outline</i>
              </div>
              <p class="card-category">Fail Rate</p>
              <h3 class="card-title">@{{dashboard.fail_rate}}%</h3>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons text-primary">arrow_right</i>
                <a href="#">Learn more...</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
              <div class="card-icon">
                <i class="material-icons">person_add</i>
              </div>
              <p class="card-category">Unique Requests</p>
              <h3 class="card-title">@{{dashboard.unique_request}}</h3>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons text-primary">arrow_right</i>
                <a href="#">Learn more...</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="card card-chart">
            <div class="card-header card-header-success">
              <div class="ct-chart" id="dailySalesChart"></div>
            </div>
            <div class="card-body">
              <h4 class="card-title">Daily Requests</h4>
              <p class="card-category">
                <span class="text-success"><i class="fa fa-long-arrow-up"></i> 55% </span> increase in today requests.
              </p>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons">access_time</i> updated 4 minutes ago
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-chart">
            <div class="card-header card-header-warning">
              <div class="ct-chart" id="websiteViewsChart"></div>
            </div>
            <div class="card-body">
              <h4 class="card-title">Daily Failed</h4>
              <p class="card-category">Last Campaign Performance</p>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons">access_time</i> failed sent 2 days ago
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-chart">
            <div class="card-header card-header-danger">
              <div class="ct-chart" id="completedTasksChart"></div>
            </div>
            <div class="card-body">
              <h4 class="card-title">Unique requests</h4>
              <p class="card-category">
                <span class="text-success"><i class="fa fa-long-arrow-up"></i> 55% </span> increase in today requests.
              </p>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons">access_time</i> campaign sent 2 days ago
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();
    });

    const app =new Vue({
      el: '#dashboard',
      data:{
        dashboard:{
          'total_request': 0,
          'total_failed': 0,
          'fail_rate': 0,
          'unique_request': 0,
        },
      },
      methods:{
        getDashboardData (){
          axios.get('{{route("dashboard")}}')
                .then(res=>{
                  console.log(res.data)
                  this.dashboard = res.data
                })
                .catch(e=>alert(e))
        },
        updatedDashboardData () {
          axios.get('{{route("dashboard")}}')
                .then(res=>{
                  // console.log(res.data)
                  this.dashboard = res.data
                })
                .catch(e=>alert(e))
        }
      },
      created(){
        this.getDashboardData()
        this.interval = setInterval(() => this.updatedDashboardData(),60000);
      }
    })
  </script>
@endpush