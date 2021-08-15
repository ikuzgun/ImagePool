<?php
return [



    "upload" => [
    
    	 "image" => [
    	"original" => [
    		"path" => "uploads/images/original",
    		//no need to pass size for original image size
    	],
    	
    	"medium" => [
    		"path" => "uploads/images/medium",
    		"size" => [512, 450]
    	],
    	"thumb" => [
    		"path" => "uploads/images/thumb",
    		"size" => [256, 225]
    	]
      


    ]



    ]
];
