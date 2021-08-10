@extends('layouts.app')
   
@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Category') }}</div>

                <div class="card-body">

    <div class="row">
        <div class="col-lg-12 margin-tb">
 
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('categories.index') }}"> Back</a>
            </div>
        </div>
    </div>
   
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
  
    <form action="{{ route('categories.update',$category->id) }}" method="POST">
        @csrf
        @method('PUT')
   
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $category->name }}" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Parent </strong>
                
                <select class="form-control"  name="category_id">
                <option value="" selected >  </option>
                @foreach ($categories as $categoriesVal)

                <option value="{{ $categoriesVal->id }}"  {{ ($category->category_id) ==  $categoriesVal->id ? 'selected' : '' }}> {{ $categoriesVal->path ?? '' }}</option>
                
                @endforeach             
                </select>
       
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Sort Order</strong>
                <input type="integer" name="sort_by" value=" {{ $category->sort_by }}" class="form-control" placeholder="0">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Status</strong>
                <select name="status" class="form-control">
                <option value="enable" {{ ($category->status) == 'enable' ? 'selected' : '' }}>Enabled</option>
                <option value="disable" {{ ($category->status) == 'disable' ? 'selected' : '' }}>Disabled</option>
                </select>
            </div>
        </div>
       
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
   
    </form>
		  </div>
            </div>
        </div>
    </div>
</div>      

@endsection
