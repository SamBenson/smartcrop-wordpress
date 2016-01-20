(function($){
	$(window).load(function(){
		$('.smartcrop').each(function(){
			var t = $(this),
				img = t.find('img'),
				width = t.attr('data-width'),
				height = t.attr('data-height');

			SmartCrop.crop(img[0], {width: width, height: height}, function(result){ 
				var crop = result.topCrop,
                    canvas = t.find('canvas')[0],
                    ctx = canvas.getContext('2d');

                canvas.width = width;
                canvas.height = height;
                ctx.drawImage(img[0], crop.x, crop.y, crop.width, crop.height, 0, 0, canvas.width, canvas.height);
               
                img.after(canvas).closest('.smartcrop').addClass('cropped');
			});
		});
	});
})(jQuery)