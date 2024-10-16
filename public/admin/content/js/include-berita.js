$(document).ready(function () {
    $('.select2').select2({
        theme: 'bootstrap-5',
    });
    var editor = CKEDITOR.replace('berita', {
        // uiColor: '#CCEAEE'
        toolbarCanCollapse:false,
    });
})