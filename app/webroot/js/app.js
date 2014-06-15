var App = new App();

function App() {
  this.loadingModalHtml = $('<div class="modal" id="loading-modal"><div class="modal-dialog"><div class="modal-content"><div class="modal-header">Loading...</div><div class="modal-body"><div class="progress progress-striped active"><div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div></div></div></div></div>')

  this.showLoading = function() {
    $('body').append(this.loadingModalHtml);
    $("#loading-modal").modal({
      backdrop: 'static',
      keyboard: false
    });
  };

  this.closeLoading = function() {
    $("#loading-modal").modal("hide");
    $("#loading-modal").remove();
  }
}

// Library functions.

/*
* Converts characters in text that might break HTML parsing to entities.
* @param str String to be converted.
* @param preventDouble Prevent double encoding. (e.g. &amp;amp;)
* @returns str Entitized string.
*/
function htmlEntities(str, preventDouble) {
  str = String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

  if(preventDouble) {
    str = String(str).replace(/&amp;amp;/gi, '&amp;').replace(/&amp;lt;/, '&lt;').replace(/&amp;gt;/, '&gt;').replace(/&amp;quot;/, '&quot;');
  }

  return str;
}

/*
* jQuery function to convert forms to objects.
* @returns object Form serialized as an object.
*/

$.fn.serializeObject = function()
{
  var o = {};
  var a = this.serializeArray();
  $.each(a, function() {
    if (o[this.name] !== undefined) {
      if (!o[this.name].push) {
        o[this.name] = [o[this.name]];
      }
      o[this.name].push(this.value || '');
    } else {
      o[this.name] = this.value || '';
    }
  });
  return o;
};