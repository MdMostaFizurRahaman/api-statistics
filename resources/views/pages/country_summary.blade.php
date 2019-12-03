@extends('layouts.app', ['activePage' => 'country_summary', 'titlePage' => "Country Summary"])

@section('content')
<div class="content" id="country_summary">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title ">Country Summary</h4>
            <p class="card-category"> Countrywise today's request summary</p>
          </div>
      
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead class=" text-primary">
                  <th>
                    Sl
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
  </div>
</div>
@endsection


@push('js')
  <script>
    const app =new Vue({
      el: '#country_summary',
      data:{
        countries: [],
      },
      methods:{
        getSummary(){
          axios.get('{{route("country_summary.get")}}')
                .then(res=>{
                  this.countries = res.data
                })
                .catch(e=>alert(e));
        }
      },
      created(){
        this.getSummary();
      }
    })
  </script>
@endpush