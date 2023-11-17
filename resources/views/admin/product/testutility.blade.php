@extends('layouts.admin.dashboard')

@section('admin-content')

<div class="container">
    <div class="row">
        @if(count($errors) > 0)
          <ul style="list-style: none;">
            @if(gettype($errors) == 'object')
                @php 
                    $errors = $errors->all()
                @endphp
            @endif

            @foreach($errors as $error)
                @if($error != '')
                    <li class="alert alert-danger">{{$error}}</li>
                @endif
            @endforeach
          </ul>
        @endif
        <div class="col-md-12 d-flex">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Test Utility</h5>
                        <form action="{{ route('search.utility.image') }}" method="POST">
                            @csrf
                            <div class="form-group">
                              <label class="control-label col-sm-4" for="country">Country</label>
                              <div class="col-sm-12">
                                <select name="country_id" id="countries" class="form-control" required>
                                    <option value="">Please Choose Country</option>
                                    @foreach($countries as $key => $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-sm-4" for="upc">Upc</label>
                              <div class="col-sm-12">
                                <input type="number" class="form-control" name="upc" id="upc" placeholder="Enter UPC" value="{{ isset($upc) ? $upc : ''}}" required>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-sm-4" for="generated_upc">Generated Upc</label>
                              <div class="col-sm-12">
                                <input type="text" class="form-control" name="generated_upc" id="generated_upc" value="{{ isset($generated_upc) ? $generated_upc : ''}}" readonly>
                              </div>
                            </div>
                            <div class="form-group">      
                              <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Search</button>
                              </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body" style="padding-bottom: 100px;">
                        <h5 class="card-title">Images</h5>
                        @if(isset($upc))
                              <!--Carousel Wrapper-->
                            <div id="carousel-thumb" class="carousel slide carousel-fade carousel-thumbnails" data-ride="carousel" data-ride="false">
                                <!--Slides-->
                                <div class="carousel-inner" role="listbox">
                                    @forelse($images as $key => $image)
                                        @if($key == 0)
                                            <div class="carousel-item active">
                                              <img class="d-block w-100" src="{{$image}}" alt="First slide">
                                            </div>
                                        @else
                                            <div class="carousel-item">
                                              <img class="d-block w-100" src="{{$image}}" alt="Second slide">
                                            </div>
                                        @endif
                                    @empty
                                    @endforelse
                                </div>
                                <!--/.Slides-->
                                <!--Controls-->
                                <a class="carousel-control-prev" href="#carousel-thumb" role="button" data-slide="prev">
                                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                  <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carousel-thumb" role="button" data-slide="next">
                                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                  <span class="sr-only">Next</span>
                                </a>
                                <!--/.Controls-->
                                <ol class="carousel-indicators">
                                    @forelse($images as $key => $image)
                                        @if($key == 0)
                                            <li data-target="#carousel-thumb" data-slide-to="{{$key}}" class="active">
                                                <img class="d-block w-100" src="{{$image}}" class="img-fluid">
                                            </li>
                                        @else
                                            <li data-target="#carousel-thumb" data-slide-to="{{$key}}">
                                                <img class="d-block w-100" src="{{$image}}" class="img-fluid">
                                            </li>
                                        @endif
                                    @empty
                                        <h1>No Image Found</h1>
                                    @endforelse
                                </ol>
                              </div>
                              <!--/.Carousel Wrapper-->
                        @endif
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $('#upc').change(function(){
        let upc = $(this).val();
        let evenNumsCount = 0;
        let oddNumsCount  = 0;
        if(upc.length === 13){
            for(let i=0; i < upc.length; i++){
                if(i%2 === 0){
                  oddNumsCount = parseInt(oddNumsCount) + parseInt(upc[i]);
                }else{
                  evenNumsCount = parseInt(evenNumsCount) + parseInt(upc[i]);
                }
            }

            let checkDigit = 0;
            let oddNum   = 3 * oddNumsCount;
            let finalNum = oddNum + evenNumsCount;
            finalNum = finalNum % 10;
            
            if(finalNum != 0){
               checkDigit = 10 - finalNum;
            }

            upc = upc.substring(1);
            upc = upc+''+checkDigit;
            $('#generated_upc').val(upc);
        }
    });


    $('.carousel').carousel({
        interval:false
})
</script>
@endsection