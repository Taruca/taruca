function getFormVal() {
    var data = $("form").serializeArray();
    var obj = {};
    $.each(data, function (k, v) {
        obj[v.name] = v.value;
    });
    return obj;
}