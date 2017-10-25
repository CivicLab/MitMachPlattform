$(document).ready(function() {

    //Input Control
       $('.input-box').click(function(e){
           $('.submission-input').addClass("expand");
           //change headline
           $('.submission-headline h1').html("Hallo Brandiser");

         console.log("hier-click?");
           $('.mainview').hide();
           e.stopPropagation();
           
       });
       $('.input-box').mouseleave(function(){
              if($('#new-submission-text').val() == ''){
                $('.submission-input').removeClass('expand');
                //change headline
                $('.submission-headline h1').html('Mitmachen <i class="fa fa-chevron-right" aria-hidden="true"></i>');
               
                $('.mainview').show();
                console.log("hier-mouseleave?");
            }
     });
  //  $('#close-new-submission').click(function(e){
  //      e.preventDefault();
  //      $('.submission-input').removeClass('expand');
  //      $('.submission-headline h1').html('Mitmachen <i class="fa fa-chevron-right" aria-hidden="true"></i>');
  //         
  //      $('.mainview').show();
  //      e.stopPropagation();
  //      
  //  });
     
     //
     $('input').attr('autocomplete','off');
     
     //tags behavior  input
     $(".categorylist-input input[type=checkbox]").click(function () {
         

               if($(this).is(':checked')){
                   $(this).parent().addClass('filter-active');
               }else{
                   if($(this).parent().hasClass('filter-active'))
                        $(this).parent().removeClass('filter-active');
               }
        });

            
  
   
   
   //nun isotope?
   
   var $container = $('.item-wrapper');
   $container.imagesLoaded(function() {
       $container.isotope({
           itemSelector : '.item',
           layoutMode: 'masonry',
           transitionDuration: 600,
           masonry: {
               columnWidth: '.item'
           },
           getSortData: {
              // sortAktuell: '.sortAktuell', // text from querySelector
               //sortRating: '.sortRating'
               
               sortAktuell: function (itemElem) {
                   var sortAktuell = $( itemElem ).find('.sortAktuell').text();
                   //console.log("sorting:"+sortAktuell)
                           return Date.parse(sortAktuell);
                },
              sortRating: function( itemElem ) { // function
               var sortRating = $( itemElem ).find('.sortRating').text();
               //console.log("sorting:"+sortRating)
               return parseFloat( sortRating.replace( /[\(\)]/g, '') );
             }
             },
             sortAscending : false
           
       });
   });

   $container.infinitescroll({
       
       navSelector: 'div.navigation',
       nextSelector: 'div.navigation a:first',
       itemSelector: '.item-wrapper div.item',
       maxPage: maxPages,
       loading: {
           finishedMsg: "Alle Einträge geladen"
          // img: 'ellipsis.gif',
          // msgText: 'lade!'
           
         },
   }, function(newElements) {
       var $newElems = $(newElements).hide();
       $newElems.imagesLoaded(function() {
          
       $newElems.animate({opacity:1});  // width:w , height:h
       $container.isotope('appended', $newElems);
       if(sortorder)
            $container.isotope({ sortBy: sortorder }) 
       App.initItem();
       });
   });
   //filter
   var filterArr = [];
   $('.filter').click(function () {
       
        var selector = $(this).attr('data-filter');
            if($.inArray( selector, filterArr ) === -1){
   		        filterArr.push(selector);
   		        $(this).addClass('filter-active');
   	        }else{
   	            var index = $.inArray( selector, filterArr );
             	  filterArr.splice(index, 1);
                  $( this ).removeClass('filter-active');
            }
           var filterstr = filterArr.join(', ');
           console.log("filter::"+filterstr)
           $container.isotope({
               filter: filterstr
           });
           if(sortorder)
                $container.isotope({ sortBy: sortorder })

           return false;
       });
   
   
     //show Content
     App.initItem = function(){
         console.log("initItem");
         
     
          
         $('.item').unbind( "click" );
         $('.item').bind( "click", function(){

            var postID = $(this).attr('data-postID');
            console.log("PostId::"+postID);
             var o = {};
              o.postID = postID;
              o.action = 'getPostContent';
          
             $.post(my_ajax_object.ajax_url, o, function(response) {
                 if(response.error == "N"){
                
          
                    var container = '<div id="contentOverlay"></div>';
          
                	if ($("#contentOverlay").size() == 0) {
            			$("body").append(container);
            		}else{
           	    
            		    $("#contentOverlay").remove()
           	    }
                    var html = '<div class="close_overlay"><a ><i class="fa fa-times" aria-hidden="true"></i></a></div>';
                        //html += '<h2>'+response.post_title+'</h2>';
                        
                        
                        html += '<div class="post-wrapper">';
                        html += '<p class="image-show">'+response.post_image+'</p>';
                        
                        html += '<p class="content-show">'+response.post_content+'</p>';
                        if(response.post_wo)
                            html += '<p class="wo-show"><b>Wo:</b> '+response.post_wo+'</p>';
                        if(response.post_wann)
                            html += '<p class="wann-show"><b>Wann:</b> '+response.post_wann+'</p>';
                            
                         //mitmachen 
                            html += '<h3 class="participants-header">'+response.participants.length+' Teilnehmer'+'</h3>';
                            html += '<ul class="participants">';
                               $.each(response.participants, function( i, obj ) {

                                   html += '<li>'+obj.teilnehmer_name+'</li>';

                               });
                               html += '</ul>';  
                            html += '<div id="participant-input">';
                            html += ' <input type="text" id="participant" placeholder="Ihr Name">';
                            //html += ' <span class="button" id="mitmachen"><i class="fa fa-user-plus" aria-hidden="true"></i> mitmachen</span>';
                            html += ' <a href="#" title="mitmachen" id="mitmachen"><i class="fa fa-user-plus" aria-hidden="true"></i> mitmachen</a>';
                            html += ' </div>';
                            
                        html += '<div class="post-meta">  ';  
                        html += '<span class="author-show">Beitrag von '+response.post_author+' </span>';
                        html += '<span class="date-show">'+response.post_date+'</span>';
                        if(response.post_tags){
                            html += '<p class="tags-show">';
                            
                            $.each(response.post_tags, function( i, obj ) {
                                html += '<span ><i class="fa fa-tag" aria-hidden="true"></i> '+obj+'</span>';
                            
                            });
                        
                            html += '</p>';
                        }
                        
                        html += '<p class="rating-show">';

                       
                       
                       if(response.rating){
                           var ratingCount = response.rating.length;
                      
                           var sum = 0;
                           $.each(response.rating, function( i, obj ) {
                               sum += parseInt(obj.bewertung);
                           });
                           var tmp = sum / ratingCount; 
                           var ratingAvrg = Math.round(tmp * 10) / 10;
                    
                           console.log("summe:"+sum+'----avrg:'+ratingAvrg);
                           var ratingVal=Math.round(ratingAvrg);
                       }else{
                           var ratingCount = 0;
                           var sum = 0;
                       }
                       html += '<span id="rateit" data-rateit-starwidth="24" data-rateit-starheight="24" data-rateit-value="'+ratingAvrg+'" ></span>';
                       html += '<span class="rate-info">('+ratingCount+' Bewertungen)</span>';
                      
                        html += '</p>';
                        
                        html += '</div>'; //meta ende
                      
                        
                           
                        //comments
                           if(response.comments.length == 1)
                               var header = '1 Kommentar';
                           else
                               var header = response.comments.length+' Kommentare';
                               
                           html += '<h3 class="comments-header">'+header+'</h3>';
                           html += '<div class="comments">';
                       
                       
                           $.each(response.comments, function( i, obj ) {
                               html += '<div class="comment">';
                               html += '<div class="c-wrap">';
                               html += '<div class="comment-author">'+obj.author+'</div>';
                               html += '<div class="comment-date">'+obj.date+'</div>';
                               html += '</div>';
                               html += '<div class="comment-content">'+obj.txt+'</div>';
                               html += '</div>';
                            });
                      
                           html += '</div>';

                           
                           
                           
                           
                        html += '<div id="comment-input">';
                        	html += '<div class="input-view">';
                        		html += '<div class="new-comment">';
                        			html += '<p><textarea id="new-comment-text" maxlength="512" placeholder="Anmerkungen?"></textarea></p>';
                        			html += '<p><input id="new-comment-author" maxlength="32" type="text" placeholder="Ihr Name"></p>';
                        			html += '<input id="postID"  type="hidden" value="'+response.postID+'">';
                        			html += '<input id="autsch"  type="text" >';
                        		html += '</div>';
                        	//	html += '<div class="button-row"><span class="button" id="new-comment-button"><i class="fa fa-comment" aria-hidden="true"></i> Kommentieren</span></div>';
                        	html += ' <div class="button-row"><a href="#" title="kommentieren" id="new-comment-button"><i class="fa fa-comment" aria-hidden="true"></i> kommentieren</a></div>';
                        	html += '</div>';
                        html += '</div>';
                        
                     html += '</div>'; //post wrapper zu   
                    $("#contentOverlay").append(html);
                    $('.mainview').hide();
                    //initRating
                    var rating = 0;
              //init Rating
             //  console.log("hier:::"+ratingVal);
                  $('#rateit').rateit({ max: 5});
                  if(ratingCount > 0)
                    $('#rateit').rateit('value', ratingAvrg+'')
                  $('#rateit').bind('rated reset', function (e) {
                
                           
                          
                           $(this).rateit('readonly', true);
                           var o = {};
                             o.postID = response.postID;
                             o.rating = $(this).rateit('value');
                             o.userAgent =navigator.userAgent;
                             o.action = 'saveRating';
                
                            $.post(my_ajax_object.ajax_url, o, function(response) {
                                if(response.error == "N"){
                                    ratingCount++;
                                    sum += parseFloat(response.bewertung); 
                                     tmp = sum / ratingCount; 
                                     ratingAvrg = Math.round(tmp * 10) / 10;
                                     $('.rate-info').html('('+ratingCount+' Bewertungen) | durchschnittliche Wertung: '+ratingAvrg+')');
                                   
                                    console.log("hier summe:"+sum+'----avrg:'+ratingAvrg+'---ratingCount:'+ratingCount);
                                }else{
                                    
                                }
                            },'json');
                       
                      });
                
                    
                    //nun closebutton init
                    $('.close_overlay').click(function(e){
                        e.preventDefault();
                        //$("#contentOverlay").html('');
                        $("#contentOverlay").remove();
                        $('.mainview').show();
                    });
                   App.initComment();
                   App.initParticipant();
          
                   }else{
                       console.log("Fehler::"+response.msg);
                   }
          
          
              }, 'json');
         });
     }
     App.initItem();
     
     //disable acf warning
     acf.unload.active = false;

     $('.acf-wo').find('input').attr("placeholder", "Wo?");
     $('.acf-wann').find('input').attr("placeholder", "Wann?");
     $('.acf-autor').find('input').attr("placeholder", "*Ihr Name");
     
     $('#weiter1').click(function(){
         
             $('.acf-wo').css("display","block");
             $('.acf-wann').css("display","block");
             $('.acf-autor').css("display","block");
             $('#button-row-weiter-1').css("display","none");
             $('#button-row-file').css("display","block");
             //
             $('html,body').animate({
                scrollTop: $(".next1").offset().top + 10
             }, 600);
        
      });
      //
  
  
   
     //Beitrag
     $('#new-post-button').click(function(e){
           e.preventDefault();
           var txt = $('#new-submission-text').val();
           //var author = $('#new-submission-author').val();
           
           var author = $('.acf-autor > div.acf-input > div.acf-input-wrap').find('input').val();
           var wo = $('.acf-wo > div.acf-input > div.acf-input-wrap').find('input').val();
           var wann = $('.acf-wann > div.acf-input > div.acf-input-wrap').find('input').val();
           //
    console.log("author::"+author+"---wo::"+wo+"-----wann:"+wann);       
           var file = $('#new-submission-file');
           var tags = [];
           $("input[name='new-submission-tags']:checked").each(function() {
               tags.push($(this).val());
           });
           
           if(txt == ''){
                 $('#new-submission-text').attr("placeholder", "Ihr Anliegen?");
                 return;
          }
           if(author != '' && $('#autsch').val() ==''){
             
               
               var o = new FormData();
               o.append('txt', txt);
               o.append('author', author);
               o.append('wo', wo);
               o.append('wann', wann);
               o.append('tags', tags);
               o.append('enctype','multipart/form-data');
               var files_data = $('#new-submission-file'); 
               var imgName ='';
    
                 $.each(files_data, function(i, obj) {
                     $.each(obj.files,function(j,file){
                         o.append('bild[' + j + ']', file);
                        
                        imgName = file['name'];
                         console.log("file:"+file['name']);    
                     })
                 });
             //$('.button-row').prepend('<span>'+imgName+'</span>');
              // o.append('bild', file);
               o.append('action', 'savePost');
              
               $.ajax({
                 type: "POST",
                 url: my_ajax_object.ajax_url,
                 data: o,
                 
                 dataType: 'json',
                 contentType: false,
                processData: false,
                 success: function(response) {
            
                       if(response.error == "N"){
                           document.location = baseUrl;
            
                       }else{
                            console.log("Fehler beim Sichern des Beitrags");
            
                        }
                    }
               });
          
              
          }else{
              $('.acf-autor').find('input').attr("placeholder", "Bitte einen Namen eingeben!");
               $('.acf-autor').find('input').addClass('placeholder-alert');
              //$('#new-submission-author').attr("placeholder", "Bitte einen Namen eingeben!");
              //$('#new-submission-author').addClass('placeholder-alert');
              //$('#new-submission-author').css("border", "2px solid #f23333");
              
          }
          
       });
       $('#new-submission-file').change(function(e) {

            $('.img-preview').html('');
            if (this.files !== 'undefined' && typeof FileReader !== 'undefined') {
                    // Schleife über alle gewählten Dateien
                    for (var f = 0; f < this.files.length; f++) {
                        var file = this.files[f];
                        var reader = new FileReader();

                        // Prüfen, ob auch wirklich ein Bild gewählt wurde
                        if (!/image\/(jpeg|jpg|png|gif)/.test(file.type)) {
                            console.log('Dateityp "' + file.type + '" wird nicht unterstützt.');
                            continue;
                        }
                        // EventListener hinzufügen
                        $(reader).load(previewImage);
                        
                        // Einlesen der Datei beginnen
                        reader.readAsDataURL(file);
                    }
            } else {
                console.log('Leider keine HTML5 File API in Ihrem Browser vorhanden.');
                $('.button-row').prepend('<span>'+imgName+'</span>');
            }
           //var files_data = $('#new-submission-file'); 
           //   var imgName ='';
           //
           //     $.each(files_data, function(i, obj) {
           //         $.each(obj.files,function(j,file){
           //             o.append('bild[' + j + ']', file);
           //            
           //            imgName = file['name'];
           //             console.log("file:"+file['name']);    
           //         })
           //     });
           // $('.button-row').prepend('<span>'+imgName+'</span>');
       });
       function previewImage(e)
       {
           var img = $(document.createElement('img'));
           
           // Bild mittels Data-URL laden
           $(img).attr('src', e.target.result);
           //$('.button-row').prepend(img);
           $(img).hide();
          // $(img).animate({opacity:1},8000);
           $(img).fadeIn('slow');
           $('.img-preview').append(img);
           
           
       }
       
       $('.single .close_overlay').click(function(e){
           e.preventDefault();
           document.location = document.location = baseUrl;
       });
       App.initComment = function(){
           $('input').attr('autocomplete','off');
           $('#new-comment-button').click(function(e){
                 e.preventDefault();
                 var txt = $('#new-comment-text').val();
                 var author = $('#new-comment-author').val();
                 var postID = $('#postID').val();
                 if(txt == ''){
                       $('#new-comment-text').attr("placeholder", "Ihr Kommentar?");
                       return;
                }
                 if(author != '' && $('#autsch').val() ==''){
                     var o = {};
                     o.postID = $('#postID').val();
                     o.txt = txt;
                     o.author = author;
                     o.action = 'saveComment';

                     $.post(my_ajax_object.ajax_url, o, function(response) {
                        
                        if(response.error == "N"){
                            
                            //hier
                            if(response.anzahl == 1)
                               $('.comments-header').html('1 Kommentar');
                           else
                               $('.comments-header').html(response.anzahl+' Kommentare');

                            var html ='';
                            html += '<div class="comment">';
                            html += '<div class="c-wrap">';
                            html += '<div class="comment-author">'+response.comment.author+'</div>';
                            html += '<div class="comment-date">'+response.comment.date+'</div>';
                            html += '</div>';
                            html += '<div class="comment-content">'+response.comment.txt+'</div>';
                            html += '</div>';
                            
                            $('.comments').prepend(html);
                            $('#comment-input').hide()

                        }else{
                             console.log("Fehler beim Sichern des Kommentars");

                         }
                     }, 'json');
                     
                 }else{
                     $('#new-comment-author').attr("placeholder", "Bitte einen Namen eingeben!");
                     $('#new-comment-author').addClass('placeholder-alert');
                     
                 }
                 
             });
           

       }
        App.initParticipant = function(){
            $('input').attr('autocomplete','off');
              $('#mitmachen').click(function(e){
                    e.preventDefault();
                    var participant = $('#participant').val();

                   
                   if(participant != '' ){
                        var o = {};
                        o.postID = $('#postID').val();
                        o.participant = participant;
                        o.action = 'saveParticipant';

                        $.post(my_ajax_object.ajax_url, o, function(response) {

                           if(response.error == "N"){

                              
                               $('.participants-header').html(response.anzahl+' Teilnehmer');

                               
                               var html ='';
                          
                               html += '<li>'+response.teilnehmer+'</li>';
                                      
                              

                               $('.participants').append(html);
                               $('#participant-input').hide()

                           }else{
                                console.log("Fehler beim Sichern des Teilnehmers");

                            }
                        }, 'json');

                    }else{
                        $('#participant').attr("placeholder", "Wer will mitmachen");
                        $('#participant').addClass('placeholder-alert');

                    }

                });


          }
      // App.initComment();
       
      //sort
      var sortorder = false;
      $('.sortorder').click(function(){
          sortorder = $(this).attr('data-sortorder');
         $container.isotope({ sortBy: sortorder }) 
         console.log("sortOrder::"+sortorder);
         
         $('.sortorder').removeClass('filter-active');
         $(this).addClass('filter-active');
         
         
       
      });
});