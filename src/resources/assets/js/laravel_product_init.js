tinymce.init({
  selector: ".laravel-product-tinymce",
  height: 500,
  plugins: [
    "advlist autolink lists link image charmap print preview anchor",
    "searchreplace visualblocks code fullscreen",
    "insertdatetime media table paste imagetools wordcount template",
  ],
  toolbar:
    "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code template",
  //content_style:"body { font-family:Helvetica,Arial,sans-serif; font-size:14px }",
  image_title: true,
  automatic_uploads: true,
  images_upload_url: "/backend/upload",
  //images_upload_url: "/admin/media-popup",
  relative_urls: false,
  file_picker_types: "image",
  content_css: "/css/app.css",
  //file_browser_callback: "fileBrowserCallBack",
  //images_upload_base_path: "/media",

  /* style_formats: [
        {
            title: "Container",
            selector: "div",
            classes: "container px-8 mx-auto"
        },
        {
            title: "Page Title",
            selector: "p",
            classes: "text-3xl sm:text-4xl lg:text-5xl lg:leading-normal"
        },
        {
            title: "Block Title",
            selector: "p",
            classes: "text-2xl sm:text-3xl lg:text-4xl lg:leading-normal"
        },
        {
            title: "Block Sub Title",
            selector: "p",
            classes: "text-xs lg:text-base font-bold"
        },
        { title: "Text", selector: "p", classes: "text-xs lg:text-base" }
    ], */
  file_picker_callback: function(callback, value, meta) {
    const input = document.createElement("input");
    input.setAttribute("type", "file");
    input.setAttribute("accept", "image/*");
    input.onchange = function() {
      var file = this.files[0];
      var reader = new FileReader();
      reader.onload = function() {
        var id = "blobid_" + new Date().getTime();
        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
        var base64 = reader.result.split(",")[1];
        var blobInfo = blobCache.create(id, file, base64);
        blobCache.add(blobInfo);
        callback(blobInfo.blobUri(), { title: file.name });
      };
      reader.readAsDataURL(file);
    };
    input.click();
  },
  video_template_callback: function(data) {
    return (
      '<div class="my-test"><video width="' +
      data.width +
      '" height="' +
      data.height +
      '"' +
      (data.poster ? ' poster="' + data.poster + '"' : "") +
      ' controls="controls">\n' +
      '<source src="' +
      data.source +
      '"' +
      (data.sourcemime ? ' type="' + data.sourcemime + '"' : "") +
      " />\n" +
      (data.altsource
        ? '<source src="' +
          data.altsource +
          '"' +
          (data.altsourcemime ? ' type="' + data.altsourcemime + '"' : "") +
          " />\n"
        : "") +
      "</video></div>"
    );
  },
  /* setup: function(editor) {
        editor.on("blur", function(e) {
            const id = this.id;
            const $element = $("#" + id);
            const lang = $element.attr("data-lang");
            Livewire.emit("updateContent", lang, editor.getContent());
        });
    } */
  setup: function(editor) {
    editor.on("change", function(e) {
      const id = this.id;
      const $element = $("#" + id);
      $element.valid();
      //const lang = $element.attr("data-lang");
      //Livewire.emit("updateContent", lang, editor.getContent());
    });
  },
  // templates: [
  //   {
  //     title: "Content Image Text",
  //     description: "subTitle, Title, Description",
  //     content: `
  //             <div class="content_image">
  //                 <div class="text-sm xl:text-xl">SUBTITLE</div>
  //                 <div class="sm:text-3xl lg:text-5xl mt-4">TITLE</div>
  //                 <div class="text-xs lg:text-base mt-4">
  //                     Lorem ipsum dolor sit amet consectetur adipisicing elit. Facere consequuntur aperiam et cupiditate voluptas cumque neque quasi natus deserunt suscipit architecto laudantium nihil fugit ea officia voluptatem quos praesentium, animi magni impedit optio, sequi, officiis placeat autem! Velit, totam natus!
  //                 </div>
  //             </div>
  //         `,
  //   },
  // ],
});
