$(document).ready(function() {
  // Call horizontalNav on the navigations wrapping element
  
  

    function createThumbnail(file, $lelem) {

        var reader = new FileReader();

        reader.addEventListener('load', function() {

            var imgElement = document.createElement('img');
            imgElement.class = 'le_preview'
            imgElement.src = this.result;
            $lelem.src = reader.result;

        });

        reader.readAsDataURL(file);

    }

    // La fonction qui ajoute un formulaire Image
    function addImage($container_image) {
      // Dans le contenu de l'attribut « data-prototype », on remplace :
      // - le texte "__name__label__" qu'il contient par le label du champ
      // - le texte "__name__" qu'il contient par le numéro du champ
  
      var $prototype_image = $($container_image.attr('data-prototype').replace(/__name__label__/g, 'Image n°' + (index_image))
          .replace(/__name__/g, index_image));

      // On ajoute un lien pour ajouter une nouvelle catégorie
      var $add_imageLink = $('<a href="#" id="add_image" class="btn btn-default">Add image</a>');
      //$container_image.append($add_imageLink);

     // On ajoute au prototype un lien pour pouvoir supprimer la catégorie

      addPreview_imageLink($prototype_image);

      addDelete_imageLink($prototype_image);
      // On ajoute le prototype modifié à la fin de la balise <div>
      $container_image.append($prototype_image);

      $('#oc_platformbundle_advert_imagecontents_' + (index_image) + '_startX').parent().hide();
      $('#oc_platformbundle_advert_imagecontents_' + (index_image) + '_startY').parent().hide();
      $('#oc_platformbundle_advert_imagecontents_' + (index_image) + '_sizeX').parent().hide();
      $('#oc_platformbundle_advert_imagecontents_' + (index_image) + '_sizeY').parent().hide();

      var allowedTypes = ['png', 'jpg', 'jpeg', 'gif'],
          fileInput = document.querySelector('#oc_platformbundle_advert_imagecontents_' + (index_image) + '_file'),
          prev = document.querySelector('#le_preview_header' + (index_image));

      fileInput.addEventListener('change', function() {

          var files = this.files,
              filesLen = files.length,
              imgType;

          for (var i = 0; i < filesLen; i++) {

              imgType = files[i].name.split('.');
              imgType = imgType[imgType.length - 1];

              if (allowedTypes.indexOf(imgType) != -1) {
                  createThumbnail(files[i],prev);
              }

          }

      });


      var le_preview_click = document.querySelector('#le_preview_header' + (index_image));
 
      var le_elem = index_image;
      le_preview_click.addEventListener('click',function() {

      alert(le_elem);

        $('#le_preview_header' + le_elem).cropper({
            aspectRatio: 16 / 9,
           // preview:'.le_preview'+ (index_image),
            viewMode: 1,
            crop: function(e) {
              // Output the result data for cropping image.
              console.log(e.x);
              console.log(e.y);

              $('#oc_platformbundle_advert_imagecontents_' + (le_elem) + '_startX').val(Math.round(e.x));
              $('#oc_platformbundle_advert_imagecontents_' + (le_elem) + '_startY').val(Math.round(e.y));
              $('#oc_platformbundle_advert_imagecontents_' + (le_elem) + '_sizeX').val(Math.round(e.width));
              $('#oc_platformbundle_advert_imagecontents_' + (le_elem) + '_sizeY').val(Math.round(e.height));
   
              console.log(e.width);
              console.log(e.height);
              console.log(e.rotate);
              console.log(e.scaleX);
              console.log(e.scaleY);
            }
          });

      });

      $('#le_croop_header_validate' + (le_elem)).click(function(e) {

         $("le_up_prev" + (le_elem)).cropper('destroy').cropper(options);

        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;

       });


      // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
      index_image++;
    }

    function addPreview_imageLink($prototype_image){

      var nouveauDiv = document.createElement("div");
      nouveauDiv.id = 'le_up_prev_div';

      var le_preview = document.createElement("img");
      le_preview.id = 'le_up_prev' + (index_image);
      le_preview.class = 'le_preview';

      var le_preview_header = document.createElement("div"); 
      le_preview.id = 'le_preview_header' + (index_image);
      le_preview.class = 'le_preview';

      var le_croop_header_validate = document.createElement("a");
      le_croop_header_validate.id = 'le_croop_header_validate' + (index_image);
      le_croop_header_validate.class = 'btn btn-danger';
      le_croop_header_validate.innerHTML = 'Validate croop';



      // Ajout des liens
      $prototype_image.append(nouveauDiv);
      $prototype_image.append(le_preview);
      $prototype_image.append(le_preview_header);
      $prototype_image.append(le_croop_header_validate);

    }

    // La fonction qui ajoute un lien de suppression d'une catégorie
    function addDelete_imageLink($prototype_image) {
      // Création du lien
      $delete_imageLink = $('<a href="#" class="btn btn-danger">Delete</a>');

      // Ajout du lien
      $prototype_image.append($delete_imageLink);

      // Ajout du listener sur le clic du lien
      $delete_imageLink.click(function(e) {
        $prototype_image.remove();
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
      });
    }


});