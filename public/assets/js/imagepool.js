  /*
    writing the ImagePool Module. 
    using the Module Design Pattern for this.
  */
 var IMAGEPOOL = (function(Dropzone){
   var config = {};

   // set some config for image routes & token etc.
   var setConfig = function(CONFIG){
         config = CONFIG;
         return this;
        };


   // We pull the available images when the page is first loaded
    var setExistingImages = function(self, callback){
            
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
                          self.emit("thumbnail", mockFile, config.basePath+"/"+element.name);
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
            
        };


      // setting for dropzone
      var dropzoneConfig = {
        acceptedFiles: 'image/*',
        dictInvalidFileType: 'This form only accepts images.',
        autoProcessQueue: true,
        parallelUploads: 1,
        addRemoveLinks: true,
        paramName: "file", 
        maxFilesize: 50, // MB

    
      
      init: function() {
         
        

         setExistingImages(this, function(){makeSortableimagePool(config.targetElem)});

      
      },

      success: function(file, response){
        if(response.status == "success"){
            file.previewElement.id = 'recordsArray_'+response.fileID;
            toast("success", 'Upload' , "File Uploaded Successfully!");
        }else{
            toast("error", 'Upload' , response.message);
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

                   toast("success", 'Delete' , "File Deleted Successfully!");

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

    };



   //When the page is loaded for the first time, the sortable operation is activated after the current images are loaded.
    var makeSortableimagePool =  function(target){
           var $this = this;
           $(target).sortable({
                opacity: 0.6, cursor: 'move', update: function () {
                    let order = $(this).sortable("serialize") + "&_token="+config.token;
                    console.log(order);
                  

                    $.post(config.sortImageRoute, order, function(theResponse){
                        toast("success", 'İmages Sorted Successfully!', '');
                    });
                   
                   
                }
            });
    };


    //setting toast notification for feedback
    var toast = function(icon, heading, message){
        $.toast({
        position: 'top-right',
        heading: heading,
        text: message,
        hideAfter: false,
        icon: icon,
        hideAfter: 5000
    });
    };


   //module starter
   var init = function(){
        Dropzone.options.imagepool = dropzoneConfig;
    };

    return {
        
        setConfig: setConfig,
        init: init
     
    };

 })(Dropzone);