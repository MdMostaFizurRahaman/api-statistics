@extends('layouts.app', ['activePage' => 'country_summary', 'titlePage' => "Country Summary"])

@section('content')
<div class="content" id="country_summary">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title ">Today's Summary</h4>
            <p class="card-category"> Countrywise today's request summary</p>
          </div>
      
          <div class="card-body">
            <div class="table-responsive">
              <table class="table" id="today_table">
                <thead class=" text-primary">
                  <th>
                    Sl
                  </th>
                  <th>
                    ISO
                  </th>
                  <th>
                    Flag
                  </th>
                  <th>
                    Country
                  </th>
                  <th>
                    Total
                  </th>
                </thead>
                <tbody>
                  <tr v-for="(country, index) in countries" :key='index'>
                    <td>@{{index+1}}</td>
                    <td>@{{country.iso}}</td>
                    <td><country-flag :country='country.iso | lowercase' size='normal'/></td>
                    <td>@{{country.country}}</td>
                    <td>@{{country.total}}</td>
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
          <div class="card-header card-header-primary">
            <h4 class="card-title ">Weekly Summary</h4>
            <p class="card-category"> Countrywise weekly unique request summary</p>
          </div>
      
          <div class="card-body">
            <div  class="table-responsive">
              <table class="table" id="weekly_table">
                <thead class=" text-primary">
                  <th>
                    Sl
                  </th>
                  <th>
                    ISO
                  </th>
                  <th>
                    Flag
                  </th>
                  <th>
                    Country
                  </th>
                  <th>
                    Total
                  </th>
                </thead>
                <tbody>
                  <tr v-for="(country, index) in weeklyCountryList" :key='index'>
                    <td>@{{index+1}}</td>
                    <td>@{{country.iso}}</td>
                    <td><country-flag :country='country.iso | lowercase' size='normal'/></td>
                    <td>@{{country.country}}</td>
                    <td>@{{country.total}}</td>
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
          <div class="card-header card-header-primary">
            <h4 class="card-title ">Hourly Summary</h4>
            <p class="card-category"> Countrywise hourly request summary</p>
          </div>
      
          <div class="card-body">
            <form @submit.prevent="getHourlySummary()">
              <div class="row mb-10">
                <div class="col-lg-4">
                  <div class="form-group">
                    <input v-model="form.date" type="date" name="date" :class="{ 'is-invalid': form.errors.has('date') }" class="form-control">
                    <has-error :form="form" field="date"></has-error>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                     <select  v-model="form.country"  name="country" class="form-control" :class="{ 'is-invalid': form.errors.has('country') }">
                          <option v-for='country in countryList' :key='country.country_name' :value='country.country_name'>@{{country.country_name}}</option>
                      </select>
                      <has-error :form="form" field="country"></has-error>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <button v-show="disabled" type="submit" disabled class="btn btn-primary btn-block">Search</button>
                    <button v-show="!disabled" type="submit"  class="btn btn-primary btn-block">Search</button>
                  </div>
                </div>
              </div>
            </form>



            <div v-show="hourlySummary" class="table-responsive">
              <table class="table">
                <thead class=" text-primary">
                  <th>
                    Sl
                  </th>
                  <th>
                    Date
                  </th>
                  <th>
                    Country Name
                  </th>
                  <th>
                    Hour
                  </th>
                  <th>
                    Total
                  </th>
                </thead>
                <tbody>
                  <tr v-if="!hourlySummary.length">
                    <td colspan="5" class="text-center"> No data found</td>
                  </tr>
                  <tr v-if="hourlySummary.length" v-for="(data, index) in hourlySummary" :key='index'>
                    <td>@{{index+1}}</td>
                    <td>@{{data.date}}</td>
                    <td>@{{data.country}}</td>
                    <td>@{{data.hour}}</td>
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
      el: '#country_summary',
      data:{
        countryList:[],
        weeklyCountryList:[],
        countries: [],
        hourlySummary: false,
        form : new Form({
          date : '',
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
          axios.get('{{route("country_summary.get")}}')
                .then(res=>{
                  this.countries = res.data
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
          this.form.post('{{route("country_summary.hourly")}}')
                    .then(res=>{
                      console.log(res.data)
                      this.hourlySummary = res.data
                      this.disabled = false
                    })
                    .catch(e=>{
                      alert(e)
                      this.disabled = false
                    })
        },
        getCountryList(){
          axios.get('{{route("country_summary.country_list")}}')
                .then(res=>{
                  this.countryList = res.data
                })
                .catch(e=>alert(e))
        },
        getContryWeeklyUnquiRequest(){
          axios.get('{{route("country_summary.country_list.weekly")}}')
                .then(res=>{
                  this.weeklyCountryList = res.data
                  this.$nextTick(function () {
                    $('#weekly_table').DataTable({
                      "lengthChange": false,
                        "pageLength": 10,
                        "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
                      });
                  })
                })
                .catch(e=>alert(e))
        }
      },
      created(){
        this.getSummary();
        this.getCountryList();
        this.getContryWeeklyUnquiRequest()
      }
    })
  </script>
@endpush