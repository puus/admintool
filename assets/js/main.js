var images       = false;
var sounds       = false;
var questions    = false;
var quizSections = false;
var quizzes      = false;
var lessons      = false;
var svgs         = false;

function getLessonSelect() {

    if (!lessons) {
        lessons = getLessons();
    }

    var select = "<select class='lessonSelect'>";

    for (var x = 0; x < lessons.length; x++) {
        var lesson = lessons[x];

        select += "<option value='" + lesson.id + "'>" + lesson.title + "</option>";
    }
    select += "</select>";

    return select;
}

function getLessons() {

    if(!lessons) {
        lessons = JSON.parse($.ajax({
            type: "GET",
            url: '/lessons/get',
            async: false
        }).responseText);
    }

    return lessons;
}

function getSvgSelect() {

    if (!svgs) {
        svgs = getSvgs();
    }

    var select = "<select class='svgSelect'>";

    for (var x = 0; x < svgs.length; x++) {
        var svg = svgs[x];

        select += "<option value='" + svg.id + "'>" + svg.nice_name + "</option>";
    }
    select += "</select>";

    return select;
}

function getSvgs() {

    if(!svgs) {
        svgs = JSON.parse($.ajax({
            type: "GET",
            url: '/media/svgs/get',
            async: false
        }).responseText);
    }
    console.log(svgs);
    return svgs;
}


/*
function get lessonOptions() {

    var options = [];
    var lessons = getLessons();

    for(var index in lessons) {
        var lesson = lessons[index];
        options.push([
            ''
        ]);
    }
}
*/


function getQuizSelect() {

    if (!quizzes) {
        quizzes = getQuizzes();
    }

    var select = "<select class='quizSelect'>";

    for (var x = 0; x < quizzes.length; x++) {
        var quiz = quizzes[x];

        select += "<option value='" + quiz.id + "'>" + quiz.title + "</option>";
    }
    select += "</select>";

    return select;
}

function getQuizzes() {

    return JSON.parse($.ajax({
        type: "GET",
        url: '/quizzes/get',
        async: false
    }).responseText);
}

function getQuizSectionSelect() {

    if (!quizSections) {
        quizSections = getQuizSections();
    }

    var select = "<select class='quizSectionSelect'>";

    for (var x = 0; x < quizSections.length; x++) {
        var section = quizSections[x];

        select += "<option value='" + section.id + "'>" + section.name + "</option>";
    }
    select += "</select>";

    return select;
}

function getQuizSections() {

    return JSON.parse($.ajax({
        type: "GET",
        url: '/quiz_sections/get',
        async: false
    }).responseText);
}

function getSoundSelect() {

    if (!sounds) {
        sounds = getSounds();
    }

    var select = "<select class='soundSelect'>";

    for (var x = 0; x < sounds.length; x++) {
        var sound = sounds[x];

        select += "<option value='" + sound.id + "'>" + sound.nice_name + "</option>";
    }
    select += "</select>";

    return select;
}

function getDropzone(id, noun, url, acceptedFiles, type) {

    return $('<div><div class="dropzone ' + id + '" id="' + getId() + '" data-type="' + type + '" data-url="' + url + '" data-accepted-files="' + acceptedFiles + '"><div class="dz-default dz-message"><p>Drag &amp; Drop ' + noun + ' Here</p><p>(click to browse)</p></div></div></div>').html();
}

function getId() {

    var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
    var uniqid = randLetter + Date.now();
    return (uniqid + (Math.random() * (10000 - 100) + 100)).replace('.', '');
}

function getSounds() {

    return JSON.parse($.ajax({
        type: "GET",
        url: '/media/sounds/get',
        async: false
    }).responseText);
}

function getColours() {

    return JSON.parse($.ajax({
        type: "GET",
        url: '/colours/get',
        async: false
    }).responseText);
}

function getColourMenu() {

    var colours = getColours();
    var items = [];

    for(var index in colours) {
        var colour = colours[index];
        var item = {};

        item.classes = colour.class;
        item.id = colour.id;
        item.value = colour.name;
        items.push(item);
    }

    return getRadialMenu('fa fa-ellipsis-v', items);
}

function getRadialMenu(icon, items) {

    var itemsMarkup = '';
    for(var index in items) {
        var item = items[index];

        itemsMarkup += '<li><a href="javascript:void(0);" data-id="' + item.id + '" data-value="' + item.value + '" class="radial-menu-item ' + ((typeof(item.classes) !== 'undefined') ? item.classes : '') + '">' + ((typeof(item.icon) !== 'undefined') ? '<i class="' + item.icon + '</i>' : '' ) + '</a></li>';
    }

    return $('<div><div>'
                + '<ul class="radial-menu" data-open="close" data-close="open">'
                +   itemsMarkup
                + '</ul>'
            + '</div></div>'
        ).html();
}

function getImageSelect() {

    if (!images) {
        images = getImages();
    }

    var select = "<select class='imageSelect'>";

    for (var x = 0; x < images.length; x++) {
        var image = images[x];

        select += "<option value='" + image.id + "'>" + image.nice_name + "</option>";
    }
    select += "</select>";

    return select;
}

function getImages() {

    return JSON.parse($.ajax({
        type: "GET",
        url: '/media/images/get',
        async: false
    }).responseText);
}

function updateImages() {

    var images = getImages();
    console.log(images);

    $('.imageSelect').each(function() {
        var selectedFile = false;
        var dropzone = $(this).parent().parent().first().get(0).firstChild;

        if ($(dropzone).hasClass('dropzone')) {
            selectedFile = $(dropzone).attr('data-filename');
        }

        for (var index in images) {
            var image = images[index];
            var imageExists = false;

            $(this).find('option').each(function() {
                if (image.id == $(this).val()) {
                    imageExists = true;
                }
            });

            if (!imageExists) {
                console.log(selectedFile);
                console.log(image);

                $(this).append("<option " + (selectedFile && selectedFile == image.file_name ? " selected " : "") + " value='" + image.id + "'>" + image.nice_name + "</option>");
            }
        }
    });
}

function updateEntities(entities, selector) {

    $(selector).each(function() {

        var selectedFile = false;
        var dropzone = $(this).parent().parent().first().get(0).firstChild;

        if ($(dropzone).hasClass('dropzone')) {
            selectedFile = $(dropzone).attr('data-filename');
        }

        for (var index in entities) {
            var entity = entities[index];
            var entityExists = false;

            $(this).find('option').each(function() {
                console.log(entity.id + ', ' + $(this).val());
                if (entity.id == $(this).val()) {
                    entityExists = true;
                }
            });

            if (!entityExists) {
                $(this).append("<option " + (selectedFile && selectedFile == entity.file_name ? " selected " : "") + " value='" + entity.id + "'>" + entity.nice_name + "</option>");
            }
        }
    });
}


function getQuestionSelect() {

    if (!questions) {
        questions = getQuestions();
    }

    var select = "<select class='questionSelect'>";

    for (var x = 0; x < questions.length; x++) {
        var question = questions[x];

        select += "<option value='" + question.id + "'>" + question.name + "</option>";
    }
    select += "</select>";

    return select;
}

function getQuestions() {

    return JSON.parse($.ajax({
        type: "GET",
        url: '/questions/get',
        async: false
    }).responseText);
}

function setTableSelected() {
    var selected = '';

    $('.tableChk').each(function() {
        if($(this).is(':checked')){
          selected += $(this).attr('data-id') + ',';
      }
    });
}

function tableDelete(route) {

    var ids = $('.table-delete').get(0).dataset.ids;
    window.location = route + '?ids=' + ids;
}

function initRadialMenu() {

    $('.radial-menu').each(function() {
        var ul = $(this),
            li = $(this).find('> li'),
             i = li.length,
             n = i - 1,
             r = 120;

          ul.unbind('click');
          ul.click(function() {

            $(this).toggleClass('active');
            if($(this).hasClass('active')){
              for(var a=0; a<i; a++){
                li.eq(a).css({
                  'transition-delay': "" + (50 * a) + "ms",
                  '-webkit-transition-delay': "" + (50 * a) + "ms",
                  'left':(r * Math.cos(90 / n * a * (Math.PI / 180))),
                  'top':( -r * Math.sin(90 / n * a * (Math.PI / 180)))
                });
                li.eq(a).click(function() {
                    $(this).parent().find('> li').each(function() {
                        $(this).removeClass('selected');
                    });
                    $(this).toggleClass('selected');
                    ul.attr('data-selected-id', $(this).find('a').attr('data-id'));
                    ul.attr('data-selected-value', $(this).find('a').attr('data-value'));
                });
              }
            }else{
              li.removeAttr('style');
            }
          });
    });
}

function initDropzone(dz) {

    dz.dropzone({
        url: dz.data('url'),
        autoQueue: false,
        addRemoveLinks: true,
        maxFiles: dz.data('maxFiles') || 1,
        maxFilesize: dz.data('maxFilesize') || 100,
        acceptedFiles: dz.data('acceptedFiles') || "*.*",
        dictInvalidFileType: dz.data('invalidFileTypeMessage') || "Invalid File Type",
        dictResponseError: dz.data('responseErrorMessage') || "File could not be uploaded",
        sending: function(file, xhr, formData) {

        },
        success: function (file, response) {

            if (!dz.attr('data-response-id') ||
                !dz.attr('data-response-type')) {

                    switch(dz.attr('data-type')) {
                        case 'image':
                            $('#newImageModal input[name=filename]').val(dz.attr('data-filename'));
                            $('#newImageModal').modal({
                                keyboard: false,
                                backdrop: 'static'
                            });
                            break;
                        case 'video':
                            $('#newVideoModal input[name=filename]').val(dz.attr('data-filename'));
                            $('#newVideoModal').modal({
                                keyboard: false,
                                backdrop: 'static'
                            });
                            break;
                        case 'sound':
                            $('#newSoundModal input[name=filename]').val(dz.attr('data-filename'));
                            $('#newSoundModal').modal({
                                keyboard: false,
                                backdrop: 'static'
                            });
                            break;
                        case 'svg':
                            $('#newSvgModal input[name=filename]').val(dz.attr('data-filename'));
                            $('#newSvgModal').modal({
                                keyboard: false,
                                backdrop: 'static'
                            });
                            break;
                   }

            }

        },
        error: function (file, response) {
            file.previewElement.classList.add("dz-error");
            console.log('error');
            console.log(response);
        },
        renameFilename: function(name) {

            var d   = new Date();
            var ext = name.substring(name.lastIndexOf('.'));
            var inp = $('#' + $(dz).attr('id') + ' #filename');
            var filename = Date.now() + d.getMilliseconds() + (Math.random() * (1 - 10000) + 1) + ext;
            dz.attr('data-filename', filename);
            console.log('renaming');

            inp.value = filename;
            return filename;
        },
        init: function() {
            this.on("addedfile", function() {
              if (this.files[1]!=null){
                this.removeFile(this.files[0]);
              }

              var file = this.files[0];
              var sha1 = CryptoJS.algo.SHA1.create();
              var read = 0;
              var unit = 1024 * 1024;
              var blob;
              var reader = new FileReader();
              reader.readAsArrayBuffer(file.slice(read, read + unit));
              reader.onload = function(e) {
                  var bytes = CryptoJS.lib.WordArray.create(e.target.result);
                  sha1.update(bytes);
                  read += unit;
                  if (read < file.size) {
                      blob = file.slice(read, read + unit);
                      reader.readAsArrayBuffer(blob);
                  } else {
                      var hash = sha1.finalize();
                      hash = hash.toString(CryptoJS.enc.Hex);

                      $.ajax('/media/identify/' + hash).done(function(data) {
                          console.log(data);
                          var response = JSON.parse(data);
                          if (response.type && response.type != 'noresult') {
                                dz.attr('data-response-type', response.type);
                                dz.attr('data-response-id', response.id);
                                dz.parent().find('.select2-hidden-accessible').val(response.id).trigger('change');
                                dz.find('.dz-preview').addClass('dz-complete');
                          } else {
                              //console.log('enqueuing file');
                              dz.removeAttr('data-response-type');
                              dz.removeAttr('data-response-id');

                              dz[0].dropzone.enqueueFile(file);


                          }
                      });
                  }
              }
            });
        }
    });
}

function disableHashLinks() {

    $('a[href="#"]').each(function() {
        if ($(this).attr('onClick') == undefined) {
        /*    $(this).click(function(e) {
                e.preventDefault();
                return false;
            });*/
        }
    });

}

$(document).ready(function(){

    disableHashLinks();

    // Redefinition of the jquery css method to include an event trigger
    // http://stackoverflow.com/questions/2157963/is-it-possible-to-listen-to-a-style-change-event
    (function() {
        var ev = new $.Event('style'),
            orig = $.fn.css;
        $.fn.css = function() {
            $(this).trigger(ev);
            return orig.apply(this, arguments);
        }
    })();

    // Init new media modals
    $('.newMediaModal .ajaxSubmit').click(function() {
        console.log('ajax submit');

        var form = $(this).closest('.newMediaModal').find('.ajaxForm');
        $.post(form.attr('action'), form.serialize()); // TODO: validation
        $(this).closest('.newMediaModal').modal('hide');

        form.find('input').each(function() {
            $(this).val('');
        });

        switch(form.attr('action')) {
            case '/media/images/post':
                updateEntities(getImages(), '.imageSelect');
                break;
            case '/media/videos/post':
                updateEntities(getVideos(), '.videoSelect');
                break;
            case '/media/sounds/post':
                updateEntities(getSounds(), '.soundSelect');
                break;
            case '/media/svgs/post':
                updateEntities(getSvgs(), '.svgSelect');
                break;
        }
    });

    $('select').select2();

    $('.jsExpand').click(function() {
        $(this).next().toggleClass('collapsed');
    });

    $(document).on('DOMNodeInserted', function (e) {

        $(e.target).find('.jsExpand').click(function() {
            $(this).next().toggleClass('collapsed');
        });

        $(e.target).find('.removeAlternative').click(function() {
            console.log($(this).closest('.row'));
            $(this).closest('.row').parent().get(0).removeChild($(this).closest('.row').get(0));
        });

        $(e.target).find('select').select2();

        if($(e.target).find('.radial-menu').length) {
            initRadialMenu();
        }

        $(e.target).find('.tableChk').iCheck({
          checkboxClass: 'icheckbox_square-green',
          radioClass: 'iradio_square-green',
          increaseArea: '20%' // optional
        });

        $(e.target).find('.tableChk').on('ifToggled', function(event){
            var selected = '';

            $('.tableChk').each(function() {
                if($(this).is(':checked')){
                  selected += $(this).attr('data-id') + ',';
              }
            });

            if (selected == '') {
                $('.table-delete').each(function() {
                    $(this).attr('disabled', 'disabled');
                    $(this).removeAttr('data-ids');
                });
            } else {
                $('.table-delete').each(function() {
                    $(this).removeAttr('disabled');
                    $(this).attr('data-ids', selected.replace(/,+$/,''));
                });
            }
        });

        $(e.target).find('.addAlternative').each(function() {
            var btn = $(this);

            btn.click(function(e) {
                //console.log(e);
                $(e.target).closest('.row').find('> .alternatives').append(generateOptionRow(true));
                //$($(e.target).closest('tr').find('.alternatives tbody').get(1)).append(generateOptionRow());
            });
        });

        $(e.target).find('.dropzone').each(function() {
            var dz = $(this);
            initDropzone(dz);
        });
        //$(this).select2();

        disableHashLinks();
    });

  $('input').iCheck({
    checkboxClass: 'icheckbox_square-green',
    radioClass: 'iradio_square-green',
    increaseArea: '20%' // optional
  });

  $('a[disabled]').click(function(e) {
      e.preventDefault();
  });

  $('.checkbox-toggle').click(function() {
      if($(this).attr('data-state') == 'off') {
          $('.tableChk').each(function() {
              $(this).iCheck('check');
          });
          $(this).attr('data-state', 'on');
      } else {
          $('.tableChk').each(function() {
              $(this).iCheck('uncheck');
          });
          $(this).attr('data-state', 'off');
      }
  });

  $('.tableChk').on('ifToggled', function(event){
      var selected = '';

      $('.tableChk').each(function() {
          if($(this).is(':checked')){
            selected += $(this).attr('data-id') + ',';
        }
      });

      if (selected == '') {
          $('.table-delete').each(function() {
              $(this).attr('disabled', 'disabled');
              $(this).removeAttr('data-ids');
          });
      } else {
          $('.table-delete').each(function() {
              $(this).removeAttr('disabled');
              $(this).attr('data-ids', selected.replace(/,+$/,''));
          });
      }
  });
});
