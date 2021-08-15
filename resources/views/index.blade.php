@extends("layouts.app")

@section('content')

<div class="container">





<form action="{{route("image.upload")}}" class="dropzone" id="imagepool">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
  <div class="fallback">
    <input name="file"  type="file" multiple />
  </div>
</form>
    



</div>

@endsection




@push('css')

<link rel="stylesheet" href="{{asset('assets')}}/css/dropzone.css">
<link rel="stylesheet" href="{{asset('assets')}}/css/jquery.toast.min.css">

<style>
    
    header, footer{
        width: 100%;
        background-color: #c2c2c2;
        min-height: 80px;
        text-align: center;
        display: flex;
        justify-content: center;
        flex-direction: column;
    }

    footer{
        position: fixed;
        bottom: 0;
       
    }


    .dropzone{
      width: 100%;
      min-height: 400px;
      border: dashed 4px #ccc;
      margin-top: 5%;
    }


    .dropzone .dz-message .dz-button{
        font-size: 50px;
        color: #c2c2c2;
    }

    .dropzone .dz-preview .dz-image{
        background-cover: contain;
    }

    .dropzone .dz-preview .dz-image img{
        width: 160px;
        
    }


</style>

@endpush



@push('js')


<script src="{{asset('assets')}}/js/dropzone.js"></script>
<script src="{{asset('assets')}}/js/jquery.toast.min.js"></script>
<script src="{{asset('assets')}}/js/imagepool.js"></script>

<script>





    (function(IMAGEPOOL){

          // Some Setting Variables are assigned
            var Config = {
                targetElem: "#imagepool",
                token: "{{csrf_token()}}",
                getExistingImageRoute: "{{route('image.all')}}",
                deleteImageRoute: "{{route('image.delete')}}",
                sortImageRoute: "{{route('image.sort')}}",
                basePath: "{{URL::to(config('general_config.upload.image.thumb.path'))}}"
            };



        //Starting the Module with Config
        IMAGEPOOL.setConfig(Config).init();



    })(IMAGEPOOL);
    
  




        
        
   
    

    
    






    




 


          

      
 
        





   
</script>

@endpush

