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

<script>
    
    // Some Setting Variables are assigned
    var Config = {
        targetElem: "#imagepool",
        token: "{{csrf_token()}}",
        getExistingImageRoute: "{{route('image.all')}}",
        deleteImageRoute: "{{route('image.delete')}}",
        sortImageRoute: "{{route('image.sort')}}",
        basePath: "{{URL::to(config('general_config.upload.image.thumb.path'))}}"
    };






  /*
    writing the ImagePool Module. 
    using the Module Design Pattern for this.
  */
 var IMAGEPOOL = (function(Dropzone){
   var config = {};




    return {
        // set some config for image routes & token etc.
        setConfig: function(CONFIG){
         config = CONFIG;
         return this;
        },
        
        // We pull the available images when the page is first loaded
        setExistingImages: function(self, callback){
            
            $.ajax({
                url: config.getExistingImageRoute,
                type: 'POST',
                dataType: 'json',
                data: {_token: config.token},
            })
            .done(function(response) {

                
                let mockFile;
                response.images.forEach(function(element, index){

                         
         
                          mockFile = {id:element.id, name: element.name,  status: element.status};
                          self.emit("addedfile", mockFile);
                          self.emit("thumbnail", mockFile, Config.basePath+"/"+element.name);
                          self.emit("complete", mockFile);

                });

           
         
         
                
             callback.call(this);
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
            
        },

        // setting for dropzone

        dropzoneConfig: {
        acceptedFiles: 'image/*',
        dictInvalidFileType: 'This form only accepts images.',
        autoProcessQueue: true,
        parallelUploads: 1,
        addRemoveLinks: true,
        paramName: "file", 
        maxFilesize: 1,

    
      
      init: function() {
         
        

         IMAGEPOOL.setExistingImages(this, function(){IMAGEPOOL.makeSortableimagePool(config.targetElem)});

      
      },

      success: function(file, response){
        if(response.status == "success"){
            file.previewElement.id = 'recordsArray_'+response.fileID;
            IMAGEPOOL.toast("success", 'Upload' , "File Uploaded Successfully!");
        }else{
            IMAGEPOOL.toast("error", 'Upload' , response.message);
        }
      },

      removedfile: function(file){
        var $this = this;
        fileID = parseInt(file.previewElement.id.split("_")[1]);
        if(fileID != 0){
              $.ajax({
                url: config.deleteImageRoute,
                type: 'POST',
                dataType: 'json',
                data: {_token: config.token, fileID: fileID},
            })
            .done(function(response) {


                   if(response.status == "success"){

                      if (file.previewElement != null && file.previewElement.parentNode != null) {
                      file.previewElement.parentNode.removeChild(file.previewElement);
                    }

                   $this._updateMaxFilesReachedClass();

                   IMAGEPOOL.toast("success", 'Delete' , "File Deleted Successfully!");

                   }
                  
                
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
            
        }
       
     
      }

    },

   //When the page is loaded for the first time, the sortable operation is activated after the current images are loaded.
    makeSortableimagePool: function(target){
           var $this = this;
           $(target).sortable({
                opacity: 0.6, cursor: 'move', update: function () {
                    let order = $(this).sortable("serialize") + "&_token="+config.token;
                    console.log(order);
                  

                    $.post(config.sortImageRoute, order, function(theResponse){
                        $this.toast("success", 'İmages Sorted Successfully!', '');
                    });
                   
                   
                }
            });
    },


   //setting toast notification for feedback
    toast: function(icon, heading, message){
        $.toast({
        position: 'top-right',
        heading: heading,
        text: message,
        hideAfter: false,
        icon: icon,
        hideAfter: 5000
    });
    },

    //module starter
    init: function(){
        Dropzone.options.imagepool = this.dropzoneConfig;
    }
    };

 })(Dropzone);



 //Starting the Module with Config
  IMAGEPOOL.setConfig(Config).init();



    




 


          

      
 
        





   
</script>

@endpush

