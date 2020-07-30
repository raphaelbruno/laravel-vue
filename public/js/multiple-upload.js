var MultipleUpload = (function(name, addedItems){
  var files = addedItems ? addedItems : new Array();
  var element = document.querySelector('#multiple-upload-'+name);
  
  document.addEventListener("dragenter", function(event){
      Object.values(document.getElementsByClassName('drop-here')).forEach(function(item){
          item.classList.remove('d-none');
      });
  });
  
  document.addEventListener("dragleave", function(event){
      if(event.clientX == 0 && event.clientX == 0)
          Object.values(document.getElementsByClassName('drop-here')).forEach(function(item){
              item.classList.add('d-none');
          });
  });

  element.querySelector('[type=file]').addEventListener('change', function(event) {
      files = files.concat(Object.values(this.files));
      this.value = "";
      updatePreview();
  });
  element.addEventListener('dragover', function(event) {
      event.preventDefault();
  });
  element.addEventListener('drop', function(event) {
      event.preventDefault();
      Object.values(document.getElementsByClassName('drop-here')).forEach(function(item){
          item.classList.add('d-none');
      });
      
      if (event.dataTransfer.items) {
          for (var i = 0; i < event.dataTransfer.items.length; i++)
              if (event.dataTransfer.items[i].kind === 'file')                    
                  files.push(event.dataTransfer.items[i].getAsFile());
      } else {
          for (var i = 0; i < event.dataTransfer.files.length; i++)
              files.push(event.dataTransfer.files[i]);
      }        
      updatePreview();
  });

  updatePreview();

  function removeFromList(index){
      if(isNaN(index) || index < 0) return;

      files.splice(index, 1);
      updatePreview();
  }

  function updatePreview() {
      var preview = element.querySelector('.multiple-upload-preview');

      preview.innerHTML = '';
      element.querySelector('.multiple-upload-total').innerHTML = files.length;

      element.querySelectorAll("input[name='item[" + name + "][]']").forEach(function(input){
          input.parentNode.removeChild(input);
      });

      files.forEach(function(file, key){
          var li = document.createElement('li');

          var button = document.createElement('button');
          button.innerHTML = 'x';
          button.type = 'button';
          button.classList.add('delete');
          button.dataset.index = key;

          var img = document.createElement('img');
          img.dataset.index = key;

          var input = document.createElement('input');
          input.dataset.index = key;
          input.type = 'hidden';
          input.name = 'item[' + name + '][]';

          li.appendChild(button);
          li.appendChild(img);
          preview.appendChild(li);
          element.appendChild(input);
          
          button.addEventListener('click', function(){
              removeFromList(this.dataset.index)
          });

          if(file.hasOwnProperty('id')){
              input.value = JSON.stringify(file);
              img.src = file.src;
          } else {
              var reader = new FileReader();
              reader.addEventListener('load', function(event) {
                  var value = { id: null, src: event.target.result };
                  element.querySelector('input[data-index="' + key + '"]').value = JSON.stringify(value);
                  element.querySelector('img[data-index="' + key + '"]').src = event.target.result;
              });
              reader.readAsDataURL(file);
          }
      });
  }
});