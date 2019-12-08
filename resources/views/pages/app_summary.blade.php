@extends('layouts.app', ['activePage' => 'app_summary', 'titlePage' => "App Summary"])

@section('content')
<div class="content" id="app_summary">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-warning">
            <h4 class="card-title ">Today's Summary</h4>
            <p class="card-category"> Appwise today's request summary</p>
          </div>
      
          <div class="card-body">
            <div class="table-responsive">
              <table class="table" id="today_table">
                <thead class=" text-warning">
                  <th>
                    Sl
                  </th>
                  <th>
                    App Name
                  </th>
                  <th>
                    Version
                  </th>
                  <th>
                    Total
                  </th>
                </thead>
                <tbody>
                  <tr v-for="(app, index) in apps" :key='index'>
                    <td>@{{index+1}}</td>
                    <td>@{{app.app}}</td>
                    <td>@{{app.version}}</td>
                    <td>@{{app.total}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
    {{-- Hourly Summary  --}}
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-warning">
            <h4 class="card-title ">Filter Summary</h4>
            <p class="card-category"> Filter summary through country, app_name etc.</p>
          </div>
      
          <div class="card-body">
            <form @submit.prevent="getHourlySummary()">
              <div class="row mb-10">
                <div class="col-lg-3">
                  <div class="form-group">
                    <input v-model="form.from" type="date" name="from" :class="{ 'is-invalid': form.errors.has('from') }" class="form-control">
                    <has-error :form="form" field="from"></has-error>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <input v-model="form.to" type="date" name="to" :class="{ 'is-invalid': form.errors.has('to') }" class="form-control">
                    <has-error :form="form" field="to"></has-error>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                     <select  v-model="form.country"  name="country" class="form-control" :class="{ 'is-invalid': form.errors.has('country') }">
                          <option v-for='country in countryList' :key='country.country_name' :value='country.country_name'>@{{country.country_name}}</option>
                      </select>
                      <has-error :form="form" field="country"></has-error>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <button v-show="disabled" type="submit" disabled class="btn btn-warning btn-block">Search</button>
                    <button v-show="!disabled" type="submit"  class="btn btn-warning btn-block">Search</button>
                  </div>
                </div>
              </div>
            </form>

            <div v-show="filterSummary" class="table-responsive">
              <table class="table">
                <thead class=" text-warning">
                  <th>
                    Sl
                  </th>
                  <th>
                    Date
                  </th>
                  <th>
                   App Name
                  </th>
                  <th>
                    Version
                  </th>
                  <th>
                    Total
                  </th>
                </thead>
                <tbody>
                  <tr v-if="!filterSummary.length">
                    <td colspan="5" class="text-center"> No data found</td>
                  </tr>
                  <tr v-if="filterSummary.length" v-for="(data, index) in filterSummary" :key='index'>
                    <td>@{{index+1}}</td>
                    <td>@{{data.date}}</td>
                    <td>@{{data.app_name}}</td>
                    <td>@{{data.app_version}}</td>
                    <td>@{{data.total}}</td>
                  </tr>
                </tbody>
              </table>
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
    const app =new Vue({
      el: '#app_summary',
      data:{
        countryList:[],
        apps: [],
        filterSummary: false,
        form : new Form({
          from : '',
          to : '',
          country : ''
        }),
        disabled: false,
      },
      filters: {
        lowercase: function (value) {
          return value.toLowerCase();
        }
      },
      methods:{
        getSummary(){
          axios.get('{{route("app_summary.get")}}')
                .then(res=>{
                  this.apps = res.data
                  this.$nextTick(function () {
                    $('#today_table').DataTable({
                      "lengthChange": false,
                        "pageLength": 10,
                        "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
                      });
                  })
                })
                .catch(e=>alert(e));
        },
        getHourlySummary(){
          this.disabled = true
          this.form.post('{{route("app_summary.filter")}}')
                    .then(res=>{
                      console.log(res.data)
                      this.filterSummary = res.data
                      this.disabled = false
                    })
                    .catch(e=>{
                      alert(e)
                      this.disabled = false
                    })
        },
        getCountryList(){
          axios.get('{{route("app_summary.country_list")}}')
                .then(res=>{
                  this.countryList = res.data
                })
                .catch(e=>alert(e))
        }
      },
      created(){
        this.getSummary();
        this.getCountryList();
      }
    })
  </script>
@endpush